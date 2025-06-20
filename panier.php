<?php
include 'header.php'; // Inclut le header, qui gère aussi session_start()
include 'config.php'; // Inclut la connexion à la base de données

// Initialiser le panier si ce n'est pas déjà fait
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$cart_items = [];
$total_price = 0;
$sub_total_price = 0;

// Si le panier n'est pas vide en session, récupérer les détails des livres
if (!empty($_SESSION['cart'])) {
    // Collecter tous les IDs de livres dans le panier
    $book_ids = array_keys($_SESSION['cart']);
    // Créer des placeholders pour la requête IN clause
    $placeholders = implode(',', array_fill(0, count($book_ids), '?'));

    // Requête pour récupérer les détails des livres du panier depuis la base de données
    $sql = "SELECT id, titre, prix, image_url FROM livres WHERE id IN ($placeholders)";
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($book_ids);
        // Récupérer toutes les lignes sous forme de tableau associatif
        $db_books_raw = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Indexer les livres par leur ID pour un accès facile
        $db_books_indexed = [];
        foreach ($db_books_raw as $book) {
            $db_books_indexed[$book['id']] = $book;
        }

        foreach ($_SESSION['cart'] as $book_id => $quantity) {
            if (isset($db_books_indexed[$book_id])) {
                $book_details = $db_books_indexed[$book_id];
                $item_price = $book_details['prix'] * $quantity;
                $sub_total_price += $item_price;
                $cart_items[] = [
                    'id' => $book_id,
                    'title' => $book_details['titre'],
                    'price' => $book_details['prix'],
                    'image' => $book_details['image_url'],
                    'quantity' => $quantity,
                    'item_total' => $item_price
                ];
            }
        }

    } catch (PDOException $e) {
        echo '<p class="alert alert-danger">Erreur lors du chargement des articles du panier : ' . $e->getMessage() . '</p>';
        $cart_items = []; // Vider le panier affiché en cas d'erreur DB
    }
}

$total_price = $sub_total_price; // Pour l'instant pas de frais de port ou taxes supplémentaires
?>

<div class="container book-page-container">
    <h1 class="mb-4 text-center">Votre Panier</h1>

    <div class="row">
        <div class="col-lg-8">
            <?php if (empty($cart_items)): ?>
                <div class="alert alert-info text-center" role="alert">
                    Votre panier est vide. <a href="livres.php" class="alert-link">Découvrez nos livres !</a>
                </div>
            <?php else: ?>
                <?php foreach ($cart_items as $item): ?>
                    <div class="card shadow-sm mb-3 cart-item-card" data-book-id="<?php echo htmlspecialchars($item['id']); ?>">
                        <div class="row g-0">
                            <div class="col-md-3 d-flex align-items-center justify-content-center p-2">
                                <img src="images/<?php echo htmlspecialchars($item['image']); ?>" class="img-fluid rounded-start" alt="<?php echo htmlspecialchars($item['title']); ?>" style="max-height: 100px; object-fit: contain;">
                            </div>
                            <div class="col-md-9">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <h5 class="card-title mb-1"><?php echo htmlspecialchars($item['title']); ?></h5>
                                        <button class="btn btn-sm btn-outline-danger delete-item-btn" data-book-id="<?php echo htmlspecialchars($item['id']); ?>">
                                            <i class="fas fa-trash"></i> Supprimer
                                        </button>
                                    </div>
                                    <p class="card-text mb-1">Prix unitaire: <?php echo number_format($item['price'], 2, ',', ''); ?>€</p>
                                    <p class="card-text mb-1">Quantité: 1</p> 
                                    <p class="card-text mt-2 fw-bold">Total pour l'article: <span class="item-total"><?php echo number_format($item['item_total'], 2, ',', ''); ?>€</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title">Récapitulatif du Panier</h4>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Sous-total:
                            <span id="subTotal"><?php echo number_format($sub_total_price, 2, ',', ''); ?>€</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Frais de port:
                            <span>Gratuit</span> </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center fw-bold">
                            Total:
                            <span id="grandTotal"><?php echo number_format($total_price, 2, ',', ''); ?>€</span>
                        </li>
                    </ul>
                    <?php if (!empty($cart_items)): ?>
                        <?php if (isset($_SESSION['user_id'])): // Si l'utilisateur est connecté, afficher le bouton de paiement ?>
                            <form action="process_purchase.php" method="POST">
                                <button type="submit" class="btn btn-success w-100 mt-3">Procéder au paiement</button>
                            </form>
                        <?php else: // Si l'utilisateur n'est pas connecté, afficher un message et un bouton de connexion ?>
                            <div class="alert alert-warning mt-3 text-center" role="alert">
                                Veuillez vous connecter pour finaliser votre commande.
                            </div>
                            <a href="login.php" class="btn btn-primary w-100 mt-3">Se connecter</a>
                        <?php endif; ?>
                    <?php else: ?>
                        <button class="btn btn-success w-100 mt-3 disabled" disabled>Procéder au paiement</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fonction pour mettre à jour le total (peut être plus sophistiquée avec AJAX)
    function updateCartTotals() {
        let currentSubTotal = 0;
        // La boucle pour les éléments du panier n'est plus pertinente ici si la quantité est fixe à 1
        // Il suffit de reprendre le sub_total_price calculé en PHP
        const subTotalElement = document.getElementById('subTotal');
        const grandTotalElement = document.getElementById('grandTotal');
        
        // Puisque le PHP recalcule déjà les totaux, nous allons juste prendre la valeur affichée
        // et nous assurer que le bouton est activé/désactivé en fonction de la présence d'articles.
        
        // Activer/désactiver le bouton de paiement
        const proceedToPaymentBtn = document.querySelector('.btn-success.w-100');
        // Vérifier si des articles sont présents dans le DOM
        const cartItemsInDOM = document.querySelectorAll('.cart-item-card').length;

        if (cartItemsInDOM > 0) {
            // Si le bouton n'est pas un formulaire, on le retire. Dans notre cas, il est dans un formulaire.
            // On le laisse géré par la logique PHP, ou on désactive le submit du formulaire.
            // Pour l'instant, le bouton est encapsulé dans une condition PHP, donc cette partie JS est moins critique.
        } else {
            // Le panier est vide, désactiver le bouton
            if (proceedToPaymentBtn) { // Vérifier si le bouton existe (si l'utilisateur est connecté et le HTML l'affiche)
                proceedToPaymentBtn.classList.add('disabled');
                proceedToPaymentBtn.disabled = true; // Désactiver l'élément HTML
            }
        }
    }

    // Gestion du bouton Supprimer
    document.querySelectorAll('.delete-item-btn').forEach(button => {
        button.addEventListener('click', function() {
            const bookId = this.dataset.bookId;
            if (confirm('Voulez-vous vraiment supprimer cet article de votre panier ?')) {
                // Envoi de la requête AJAX pour supprimer l'article
                deleteItemFromSession(bookId);
            }
        });
    });

    // Fonction AJAX pour supprimer un article de la session
    function deleteItemFromSession(bookId) {
        fetch('update_cart.php', { // Utilise le même fichier update_cart.php
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=delete_item&book_id=${bookId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Supprimer l'élément du DOM
                document.querySelector(`.cart-item-card[data-book-id="${bookId}"]`).remove();
                // Nous devons recharger la page pour que PHP recalcule les totaux et l'état du bouton "Procéder au paiement"
                // ou avoir une logique JS plus complexe pour mettre à jour les totaux et l'état du bouton.
                // Pour la simplicité et la robustesse, un rechargement est souvent suffisant après une suppression d'article.
                if (data.cart_empty) {
                    // Si le panier est vide, recharger la page ou afficher le message 'panier vide'
                    location.reload(); // Recharger la page pour montrer l'état "panier vide"
                } else {
                    // Sinon, mettre à jour les totaux côté client (moins fiable, mais plus fluide)
                    // Ou recharger quand même pour la simplicité.
                    location.reload(); 
                }
            } else {
                alert('Erreur lors de la suppression de l\'article: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur de communication avec le serveur.');
        });
    }

    // Retirer la logique updateQuantityInSession et les écouteurs de 'change' sur les inputs de quantité
    // car la quantité est fixée à 1 par livre.
    // updateCartTotals(); // Appel initial pas nécessaire si PHP gère les totaux au chargement de la page.
});
</script>

<?php include 'footer.php'; ?>