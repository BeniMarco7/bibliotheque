<?php
include 'header.php';
include 'db.php';

// Vérifie que l'ID du livre est bien fourni
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo '<p class="alert alert-danger">Aucun livre sélectionné.</p>';
    include 'footer.php';
    exit;
}

$book_id = (int)$_GET['id'];

// Récupérer le livre en base
$stmt = $pdo->prepare("SELECT * FROM livres WHERE id = ?");
$stmt->execute([$book_id]);
$book = $stmt->fetch();

if (!$book) {
    echo '<p class="alert alert-danger">Livre introuvable.</p>';
    include 'footer.php';
    exit;
}

// Vérifier si l'utilisateur est connecté
$is_logged_in = isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0;
?>

<script>
    const IS_LOGGED_IN = <?php echo json_encode($is_logged_in); ?>;
</script>

<div class="container mt-4">
    <button class="btn btn-outline-secondary mb-3" onclick="history.back();">
        <i class="fa-solid fa-arrow-left"></i> Retour
    </button>
</div>

<div class="container my-5">
    <div class="row g-5">
        <!-- Colonne image -->
        <div class="col-md-5 text-center">
            <img src="images/<?php echo htmlspecialchars($book['image_url']); ?>" alt="<?php echo htmlspecialchars($book['titre']); ?>" class="img-fluid rounded shadow">
        </div>

        <!-- Colonne infos -->
        <div class="col-md-7">
            <h2 class="mb-3"><?php echo htmlspecialchars($book['titre']); ?></h2>
            <p class="text-muted mb-1"><?php echo htmlspecialchars($book['auteur']); ?></p>
            <p><span class="badge bg-success"><?php echo htmlspecialchars($book['genre']); ?></span></p>
            <p class="h4 text-primary mb-4"><?php echo number_format($book['prix'], 2, ',', ''); ?>€</p>

            <p class="mb-4"><strong>Date de publication :</strong> <?php echo htmlspecialchars($book['date_publication']); ?></p>

            <button type="button" class="btn btn-dark btn-lg add-to-cart-btn" data-book-id="<?php echo (int)$book['id']; ?>">Ajouter au panier</button>

            <div class="mt-4 p-4 bg-white rounded shadow-sm">
                <h5>Description</h5>
                <p><?php echo nl2br(htmlspecialchars($book['description'])); ?></p>
            </div>
        </div>
    </div>
</div>

<div id="message-container" class="mt-4 mb-4"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
        button.addEventListener('click', function() {
            const bookId = this.dataset.bookId;

            if (!IS_LOGGED_IN) {
                // Non connecté : proposer la connexion
                const loginPromptModal = new bootstrap.Modal(document.getElementById('loginPromptModal'));
                loginPromptModal.show();
            } else {
                fetch('update_cart.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `action=add_item&book_id=${bookId}&quantity=1`
                })
                .then(response => response.json())
                .then(data => {
                    const msgContainer = document.getElementById('message-container');
                    msgContainer.innerHTML = '';

                    let alertClass = data.success ? 'alert-success' : 'alert-danger';
                    msgContainer.innerHTML = `<div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                                                ${data.message}
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                              </div>`;

                    // Auto-close après 5s
                    setTimeout(() => {
                        const alert = msgContainer.querySelector('.alert');
                        if (alert) {
                            const bsAlert = bootstrap.Alert.getInstance(alert);
                            if (bsAlert) {
                                bsAlert.close();
                            } else {
                                alert.remove();
                            }
                        }
                    }, 5000);
                })
                .catch(error => {
                    console.error('Erreur:', error);
                });
            }
        });
    });
});
</script>

<!-- Modal pour connexion si non connecté -->
<div class="modal fade" id="loginPromptModal" tabindex="-1" aria-labelledby="loginPromptModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginPromptModalLabel">Connexion requise</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Vous devez être connecté pour ajouter des livres à votre panier.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <a href="login.php" class="btn btn-primary">Se connecter</a>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
