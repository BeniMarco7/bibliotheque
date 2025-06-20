<?php
include 'header.php'; // Inclut le header, qui gère aussi session_start()


$message_sent = false;
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer et nettoyer les données du formulaire
    $nom = htmlspecialchars(trim($_POST['nom'] ?? ''));
    $prenom = htmlspecialchars(trim($_POST['prenom'] ?? ''));
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $message_content = htmlspecialchars(trim($_POST['message'] ?? ''));

    // Petite validation côté serveur (simple)
    if (empty($nom) || empty($prenom) || empty($email) || empty($message_content)) {
        $error_message = "Veuillez remplir tous les champs du formulaire.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "L'adresse e-mail n'est pas valide.";
    } else {
        // Simuler l'envoi du message
        // Dans une application réelle, vous enverriez un email ici (ex: mail(), PHPMailer)
        // ou enregistreriez le message dans une base de données.

        // Pour cette démonstration, on simule le succès
        $message_sent = true;
        // Rediriger pour éviter le re-submit du formulaire et afficher le message de succès
        header('Location: contact.php?status=success');
        exit();
    }
}

// Vérifier si un message de succès a été passé via l'URL après une soumission
if (isset($_GET['status']) && $_GET['status'] === 'success') {
    $message_sent = true;
}
?>

<div class="contact-page-container d-flex flex-column align-items-center justify-content-center py-5">
    <div class="text-center mb-4">
        <h1 class="display-4 fw-bold mb-2">Contactez-nous</h1>
        <p class="lead text-muted">Besoin de nous dire quelque chose ?</p>
        <p class="lead text-muted">Ne vous gênez pas !</p>
    </div>

    <div class="card p-4 shadow-sm contact-card" style="max-width: 500px; width: 90%;">
        <?php if ($message_sent): ?>
            <div class="alert alert-success text-center mb-3" role="alert">
                Votre message a bien été envoyé ! Nous vous répondrons dans les plus brefs délais.
            </div>
        <?php elseif (!empty($error_message)): ?>
            <div class="alert alert-danger text-center mb-3" role="alert">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <form action="contact.php" method="POST">
            <div class="mb-3">
                <label for="nom" class="form-label visually-hidden">Nom</label>
                <input type="text" class="form-control" id="nom" name="nom" placeholder="Nom" required
                       value="<?php echo isset($nom) && !$message_sent ? htmlspecialchars($nom) : ''; ?>">
            </div>
            <div class="mb-3">
                <label for="prenom" class="form-label visually-hidden">Prénom</label>
                <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Prenom" required
                       value="<?php echo isset($prenom) && !$message_sent ? htmlspecialchars($prenom) : ''; ?>">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label visually-hidden">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Email" required
                       value="<?php echo isset($email) && !$message_sent ? htmlspecialchars($email) : ''; ?>">
            </div>
            <div class="mb-4">
                <label for="message" class="form-label visually-hidden">Message</label>
                <textarea class="form-control" id="message" name="message" rows="5" placeholder="Message" required><?php echo isset($message_content) && !$message_sent ? htmlspecialchars($message_content) : ''; ?></textarea>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-dark btn-lg">Soumettre</button>
            </div>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>
