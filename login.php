<?php
include 'header.php'; // Inclut le header, qui gère aussi session_start()
include 'db.php'; // Inclut la connexion à la base de données

$error_message = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error_message = "Veuillez remplir tous les champs.";
    } else {
        try {
            // Préparer la requête SQL pour récupérer l'utilisateur par email
            $stmt = $pdo->prepare("SELECT id, username, name, email, password, access FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Vérifier si un utilisateur a été trouvé et si le mot de passe correspond
            if ($user && password_verify($password, $user['password'])) {
                // Connexion réussie : enregistrer les informations de l'utilisateur en session
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'nom' => $user['name'],
                    'email' => $user['email'],
                    'access' => $user['access']
                ];
                $_SESSION['user_id'] = $user['id']; // pour notre header
                $_SESSION['username'] = $user['username']; // pour notre header
                $_SESSION['email'] = $user['email']; // pour notre header


                // Rediriger en fonction du rôle
                if ($user['access'] == 1) {
                    header('Location: admin.php');
                    exit();
                } elseif ($user['access'] == 0) {
                    header('Location: index.php');
                    exit();
                } elseif ($user['access'] == 2) {
                    die('Votre compte est restreint. Contactez un administrateur.');
                }

            } else {
                $error_message = "Email ou mot de passe incorrect.";
            }

        } catch (PDOException $e) {
            $error_message = "Une erreur est survenue lors de la connexion. Veuillez réessayer. (Erreur: " . $e->getMessage() . ")";
            // En production, ne pas afficher $e->getMessage()
        }
    }
}
?>

<div class="auth-page-container">
    <div class="card p-4 shadow-sm login-card">
        <div class="text-center mb-4">
            <img src="images/logo.png" alt="Biblizone Logo" class="img-fluid mb-3" style="max-width: 80px;">
            <h2 class="card-title mb-0">BIBLIZONE</h2>
            <p class="text-muted mt-2">Connectez-vous sur BIBLIZONE pour avoir plus de possibilités</p>
        </div>
        <form action="login.php" method="POST">
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Votre email" required value="<?php echo htmlspecialchars($email); ?>">
            </div>
            <div class="mb-4">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Votre mot de passe" required>
                <small class="d-block text-end mt-2"><a href="#" class="text-decoration-none">Mot de passe oublié ?</a></small>
            </div>
            <div class="d-grid gap-2 mb-4">
                <button type="submit" class="btn btn-dark btn-lg">Connexion</button>
            </div>
        </form>
        <p class="text-center text-muted">Vous n'êtes pas encore sur BIBLIZONE ? <a href="register.php" class="text-decoration-none">Inscription</a></p>
    </div>
</div>

<?php include 'footer.php'; ?>
