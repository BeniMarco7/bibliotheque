<?php
session_start();
include 'db.php';

// Vérifie que connecté et admin
if (!isset($_SESSION['user']) || $_SESSION['user']['access'] != 1) {
    die("Accès refusé, vous n'êtes pas admin.");
}

// Action : rendre admin OU remettre user
if (isset($_GET['toggle_admin'])) {
    $idAdmin = (int)$_GET['toggle_admin'];

    $stmt = $pdo->prepare("SELECT access FROM users WHERE id = ?");
    $stmt->execute([$idAdmin]);
    $u = $stmt->fetch();

    if ($u) {
        if ($u['access'] == 0) {
            $stmt = $pdo->prepare("UPDATE users SET access = 1 WHERE id = ?");
            $stmt->execute([$idAdmin]);
        } elseif ($u['access'] == 1 && $idAdmin != $_SESSION['user']['id']) {
            $stmt = $pdo->prepare("UPDATE users SET access = 0 WHERE id = ?");
            $stmt->execute([$idAdmin]);
        }
    }

    header("Location: admin.php");
    exit;
}

// Toggle restriction
if (isset($_GET['toggle_restrict'])) {
    $idToggle = (int)$_GET['toggle_restrict'];

    $stmt = $pdo->prepare("SELECT access FROM users WHERE id = ?");
    $stmt->execute([$idToggle]);
    $userToToggle = $stmt->fetch();

    if ($userToToggle) {
        if ($userToToggle['access'] == 0) {
            $stmt = $pdo->prepare("UPDATE users SET access = 2 WHERE id = ?");
            $stmt->execute([$idToggle]);
        } elseif ($userToToggle['access'] == 2) {
            $stmt = $pdo->prepare("UPDATE users SET access = 0 WHERE id = ?");
            $stmt->execute([$idToggle]);
        }
    }

    header("Location: admin.php");
    exit;
}

// Supprimer utilisateur
if (isset($_GET['delete'])) {
    $idToDelete = (int)$_GET['delete'];
    if ($idToDelete != $_SESSION['user']['id']) {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$idToDelete]);
        header("Location: admin.php");
        exit;
    }
}

// Récupérer tous les utilisateurs
$stmt = $pdo->query("SELECT * FROM users ORDER BY id ASC");
$users = $stmt->fetchAll();

// Nombre total utilisateurs
$countStmt = $pdo->query("SELECT COUNT(*) FROM users");
$userCount = $countStmt->fetchColumn();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - Gestion des utilisateurs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            background-color: #d9a66e;
        }
        .container {
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Bonjour <?php echo htmlspecialchars($_SESSION['user']['nom']); ?> Admin !</h2>
        <a class="btn btn-outline-danger" href="logout.php">
            Déconnexion <i class="fa-solid fa-right-from-bracket"></i>
        </a>
    </div>

    <p>Nombre total d'utilisateurs inscrits : <?php echo $userCount; ?></p>

    <table class="table table-bordered table-striped mt-4">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>État</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $u): ?>
            <tr>
                <td><?= htmlspecialchars($u['id']) ?></td>
                <td><?= htmlspecialchars($u['name']) ?></td>
                <td><?= htmlspecialchars($u['username']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td><?= htmlspecialchars($u['tel']) ?></td>
                <td>
                    <?php
                    if ($u['access'] == 1) {
                        echo '<span class="badge text-bg-danger">Admin</span>';
                    } elseif ($u['access'] == 0) {
                        echo '<span class="badge text-bg-success">Utilisateur</span>';
                    } elseif ($u['access'] == 2) {
                        echo '<span class="badge text-bg-warning text-dark">Restreint</span>';
                    }
                    ?>
                </td>
                <td>
                    <!-- Bouton Admin / Utilisateur -->
                    <?php
                    if ($u['access'] == 0) {
                        echo '<a href="admin.php?toggle_admin=' . $u['id'] . '" class="btn btn-sm btn-primary">Admin</a>';
                    } elseif ($u['access'] == 1) {
                        if ($u['id'] != $_SESSION['user']['id']) {
                            echo '<a href="admin.php?toggle_admin=' . $u['id'] . '" class="btn btn-sm btn-success">Utilisateur</a>';
                        } else {
                            echo '<button class="btn btn-sm btn-success" disabled>Utilisateur</button>';
                        }
                    } else {
                        echo '<button class="btn btn-sm btn-primary" disabled>Admin</button>';
                    }
                    ?>

                    <!-- Supprimer -->
                    <?php if ($u['id'] != $_SESSION['user']['id'] && $u['access'] != 1): ?>
                        <a href="admin.php?delete=<?= $u['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cet utilisateur ?')">Supprimer</a>
                    <?php else: ?>
                        <button class="btn btn-sm btn-secondary" disabled>Supprimer</button>
                    <?php endif; ?>

                    <!-- Restreindre / Débloquer -->
                    <?php if ($u['access'] == 1): ?>
                        <button class="btn btn-sm btn-warning" disabled>Restreindre</button>
                    <?php elseif ($u['access'] == 0): ?>
                        <a href="admin.php?toggle_restrict=<?= $u['id'] ?>" class="btn btn-sm btn-warning">Restreindre</a>
                    <?php elseif ($u['access'] == 2): ?>
                        <a href="admin.php?toggle_restrict=<?= $u['id'] ?>" class="btn btn-sm btn-warning">Débloquer</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
