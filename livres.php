<?php
include 'header.php';
include 'db.php';

$is_logged_in = isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0;
?>

<script>
const IS_LOGGED_IN = <?php echo json_encode($is_logged_in); ?>;
</script>

<?php
$search_query = $_GET['search'] ?? '';
$selected_genres = $_GET['genre'] ?? [];
$price_max = $_GET['price_max'] ?? 20;

$sql = "SELECT id, titre, prix, image_url, genre, auteur FROM livres WHERE 1=1";
$params = [];
$param_types = [];

if (!empty($search_query)) {
    $sql .= " AND (titre LIKE :search_query_titre OR auteur LIKE :search_query_auteur)";
    $params[':search_query_titre'] = '%' . $search_query . '%';
    $param_types[':search_query_titre'] = PDO::PARAM_STR;
    $params[':search_query_auteur'] = '%' . $search_query . '%';
    $param_types[':search_query_auteur'] = PDO::PARAM_STR;
}

if (!empty($selected_genres)) {
    $genre_placeholders = [];
    foreach ($selected_genres as $index => $genre) {
        $placeholder_name = ":genre" . $index;
        $genre_placeholders[] = $placeholder_name;
        $params[$placeholder_name] = $genre;
        $param_types[$placeholder_name] = PDO::PARAM_STR;
    }
    $sql .= " AND genre IN (" . implode(',', $genre_placeholders) . ")";
}

$sql .= " AND prix <= :price_max";
$params[':price_max'] = $price_max;
$param_types[':price_max'] = PDO::PARAM_INT;

$sort_by = $_GET['sort_by'] ?? 'note';
switch ($sort_by) {
    case 'price_asc':
        $sql .= " ORDER BY prix ASC";
        break;
    case 'price_desc':
        $sql .= " ORDER BY prix DESC";
        break;
    case 'note':
    default:
        $sql .= " ORDER BY titre ASC";
        break;
}

try {
    $stmt = $pdo->prepare($sql);
    foreach ($params as $param_name => &$param_value) {
        $stmt->bindParam($param_name, $param_value, $param_types[$param_name]);
    }
    $stmt->execute();
    $books = $stmt->fetchAll();
} catch (PDOException $e) {
    echo '<p class="alert alert-danger">Erreur lors du chargement des livres : ' . $e->getMessage() . '</p>';
    $books = [];
}

$available_genres = [
    'Littérature & Fiction' => ['Romans et Nouvelles', 'Théâtre', 'Poésie'],
    'Art, Culture & Société' => ['Cinéma', 'Musique', 'Histoire', 'Actualité'],
    'Scolaire' => ['Soutien scolaire', 'Concours'],
    'Jeunesse' => ['Mangas', 'Comics', 'BD']
];
?>

<div class="container-fluid book-page-container">
    <div class="row">
        <div class="col-lg-2 col-md-3 filters-sidebar p-4">
            <form action="livres.php" method="GET" id="filterForm">
                <h4 class="mb-3">Genre</h4>
                <?php foreach ($available_genres as $main_genre => $sub_genres): ?>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="genre[]" value="<?php echo htmlspecialchars($main_genre); ?>" id="genre<?php echo str_replace(' ', '', $main_genre); ?>" <?php echo in_array($main_genre, $selected_genres) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="genre<?php echo str_replace(' ', '', $main_genre); ?>">
                            <?php echo htmlspecialchars($main_genre); ?><br>
                            <small class="text-muted"><?php echo implode(', ', $sub_genres); ?></small>
                        </label>
                    </div>
                <?php endforeach; ?>

                <h4 class="mt-4 mb-3">Prix</h4>
                <div class="price-range">
                    <span id="priceValue">$0 - <?php echo htmlspecialchars($price_max); ?>€</span>
                    <input type="range" class="form-range" min="0" max="20" step="1" name="price_max" id="priceRange" value="<?php echo htmlspecialchars($price_max); ?>">
                </div>
                <button type="submit" class="btn btn-primary mt-3 w-100">Appliquer les filtres</button>
            </form>
        </div>

        <div class="col-lg-10 col-md-9 books-content p-4">
            <div class="row align-items-center mb-4">
                <div class="col-md-6 mb-3 mb-md-0">
                    <form action="livres.php" method="GET" class="input-group search-bar">
                        <input type="text" class="form-control" placeholder="Rechercher un livre..." name="search" value="<?php echo htmlspecialchars($search_query); ?>">
                        <button class="btn btn-outline-secondary search-button" type="submit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.085.12l3.204 3.204a.5.5 0 0 0 .708-.708l-3.204-3.204a.5.5 0 0 0-.12-.085zm-5.442 1.401a5.5 5.5 0 1 1 0-11 5.5 0 0 1 0 11"/>
                            </svg>
                        </button>
                    </form>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="sort-options">
                        <a href="?<?php echo http_build_query(array_merge($_GET, ['sort_by' => 'note'])); ?>" class="btn btn-sort <?php echo ($sort_by == 'note' || !isset($_GET['sort_by'])) ? 'active' : ''; ?>">Note</a>
                        <a href="?<?php echo http_build_query(array_merge($_GET, ['sort_by' => 'price_asc'])); ?>" class="btn btn-sort <?php echo ($sort_by == 'price_asc') ? 'active' : ''; ?>">Prix croissant</a>
                        <a href="?<?php echo http_build_query(array_merge($_GET, ['sort_by' => 'price_desc'])); ?>" class="btn btn-sort <?php echo ($sort_by == 'price_desc') ? 'active' : ''; ?>">Prix décroissant</a>
                    </div>
                </div>
            </div>

            <div id="message-container" class="mt-4 mb-4">
                <?php if (isset($_GET['message']) && $_GET['message'] === 'ajout_reussi'): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Livre ajouté au panier avec succès !
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
            </div>

            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4 book-grid">
                <?php if (empty($books)): ?>
                    <div class="col-12">
                        <p class="text-center alert alert-info">Aucun livre trouvé avec ces critères de recherche.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($books as $book): ?>
                        <div class="col">
                            <div class="book-card">
                                <!-- lien autour de l'image -->
                                <a href="livre_solo.php?id=<?php echo $book['id']; ?>">
                                    <img src="images/<?php echo htmlspecialchars($book['image_url']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($book['titre']); ?>">
                                </a>
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($book['titre']); ?></h5>
                                    <?php if (!empty($book['auteur'])): ?>
                                        <p class="card-text text-muted"><?php echo htmlspecialchars($book['auteur']); ?></p>
                                    <?php endif; ?>
                                    <p class="card-text fw-bold"><?php echo number_format($book['prix'], 2, ',', ''); ?>€</p>
                                    <button class="btn btn-dark w-100 add-to-cart-btn mt-2" data-book-id="<?php echo htmlspecialchars($book['id']); ?>">Ajouter au panier</button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const priceRange = document.getElementById('priceRange');
    const priceValue = document.getElementById('priceValue');
    priceValue.textContent = `$0 - ${priceRange.value}€`;

    priceRange.addEventListener('input', function() {
        priceValue.textContent = `$0 - ${this.value}€`;
    });

    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
        button.addEventListener('click', function() {
            const bookId = this.dataset.bookId;
            if (!IS_LOGGED_IN) {
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

<?php include 'footer.php'; ?>
