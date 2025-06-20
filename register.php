<?php
include 'header.php'; // Inclut le header, qui gère aussi session_start()
include 'db.php'; // Inclut la connexion à la base de données

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validation des champs
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error_message = "Veuillez remplir tous les champs.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "L'adresse e-mail n'est pas valide.";
    } elseif ($password !== $confirm_password) {
        $error_message = "Les mots de passe ne correspondent pas.";
    } elseif (strlen($password) < 6) { // Exemple de longueur minimale
        $error_message = "Le mot de passe doit contenir au moins 6 caractères.";
    } else {
        try {
            // Vérifier si l'email est déjà utilisé
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            if ($stmt->fetchColumn() > 0) {
                $error_message = "Cette adresse e-mail est déjà utilisée.";
            } else {
                // Hacher le mot de passe avant de le stocker
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Insérer le nouvel utilisateur dans la base de données
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
                $stmt->bindParam(':username', $username, PDO::PARAM_STR);
                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);

                if ($stmt->execute()) {
                    // Inscription réussie : enregistrer les informations de l'utilisateur en session
                    $_SESSION['user_id'] = $pdo->lastInsertId(); // Récupère l'ID du nouvel utilisateur
                    $_SESSION['username'] = $username;
                    $_SESSION['email'] = $email;

                    $success_message = "Votre compte a été créé avec succès ! Redirection...";
                    // Rediriger après un court délai ou directement
                    header('Location: index.php');
                    exit();
                } else {
                    $error_message = "Une erreur est survenue lors de l'inscription. Veuillez réessayer.";
                }
            }
        } catch (PDOException $e) {
            $error_message = "Une erreur est survenue lors de l'inscription : " . $e->getMessage();
            // En production, ne pas afficher $e->getMessage()
        }
    }
}
?>

<div class="auth-page-container">
    <div class="card p-4 shadow-sm register-card">
        <div class="text-center mb-4">
            <img src="images/logo.png" alt="Biblizone Logo" class="img-fluid mb-3" style="max-width: 80px;">
            <h2 class="card-title mb-0">BIBLIZONE</h2>
            <p class="text-muted mt-2">Créez votre compte BIBLIZONE</p>
        </div>
        <form action="register.php" method="POST">
            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo htmlspecialchars($success_message); ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            <div class="mb-3">
                <label for="username" class="form-label">Nom d'utilisateur</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Votre nom d'utilisateur" required value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
            </div>
            <div class="mb-3">
                <label for="email_register" class="form-label">E-mail</label>
                <input type="email" class="form-control" id="email_register" name="email" placeholder="Votre email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            </div>
            <div class="mb-3">
                <label for="password_register" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="password_register" name="password" placeholder="Votre mot de passe" required>
            </div>
            <div class="mb-4">
                <label for="confirm_password" class="form-label">Confirmer le mot de passe</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirmer votre mot de passe" required>
            </div>
            <div class="d-grid gap-2 mb-4">
                <button type="submit" class="btn btn-dark btn-lg">S'inscrire</button>
            </div>
        </form>
        <p class="text-center text-muted">Déjà un compte ? <a href="login.php" class="text-decoration-none">Connexion</a></p>
    </div>
</div>

<?php include 'footer.php'; ?>
