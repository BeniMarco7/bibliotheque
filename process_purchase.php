<?php
session_start();
include 'db.php'; // Inclure la connexion à la base de données

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] <= 0) {
    // Rediriger vers la page de connexion si non connecté
    header('Location: login.php?message=Veuillez vous connecter pour finaliser votre achat.');
    exit;
}

// Vérifier si le panier n'est pas vide
if (empty($_SESSION['cart'])) {
    // Rediriger vers le panier avec un message si vide
    header('Location: panier.php?message=Votre panier est vide.');
    exit;
}

$user_id = $_SESSION['user_id'];
$purchased_books_count = 0;

try {
    // Démarrer une transaction pour s'assurer que toutes les insertions réussissent ou échouent ensemble
    $pdo->beginTransaction();

    // Préparer la requête pour insérer les livres achetés
    // La colonne 'purchase_date' est pour garder une trace de la date d'achat.
    // 'book_id' sera l'ID du livre, 'user_id' l'ID de l'acheteur.
    $stmt_insert_book = $pdo->prepare("INSERT INTO user_books (user_id, book_id, purchase_date) VALUES (?, ?, NOW())");

    foreach ($_SESSION['cart'] as $book_id => $quantity) { // $quantity sera toujours 1 d'après update_cart.php
        // Vérifier si le livre n'est pas déjà dans la bibliothèque de l'utilisateur
        $stmt_check_existing = $pdo->prepare("SELECT COUNT(*) FROM user_books WHERE user_id = ? AND book_id = ?");
        $stmt_check_existing->execute([$user_id, $book_id]);
        $book_exists = $stmt_check_existing->fetchColumn();

        if ($book_exists == 0) {
            // Si le livre n'est pas déjà possédé par l'utilisateur, l'insérer
            $stmt_insert_book->execute([$user_id, $book_id]);
            $purchased_books_count++;
        }
    }

    // Si tout s'est bien passé, vider le panier et valider la transaction
    $_SESSION['cart'] = [];
    $pdo->commit();

    // Rediriger l'utilisateur vers une page de succès ou sa bibliothèque
    header('Location: ma_bibliotheque.php?status=success&count=' . $purchased_books_count);
    exit;

} catch (PDOException $e) {
    // En cas d'erreur, annuler la transaction et afficher un message
    $pdo->rollBack();
    error_log("Erreur lors du processus d'achat : " . $e->getMessage()); // Pour le débogage côté serveur
    header('Location: panier.php?status=error&message=Une erreur est survenue lors du traitement de votre commande. Veuillez réessayer.');
    exit;
}
?>
