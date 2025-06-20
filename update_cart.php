<?php
session_start();
include 'config.php'; // Inclure la connexion à la base de données

header('Content-Type: application/json'); // S'assurer que la réponse est JSON

$response = ['success' => false, 'message' => '', 'cart_count' => 0, 'cart_empty' => false];

// Vérifier si l'action est définie
if (!isset($_POST['action'])) {
    $response['message'] = 'Action non spécifiée.';
    echo json_encode($response);
    exit;
}

$action = $_POST['action'];
$book_id = isset($_POST['book_id']) ? intval($_POST['book_id']) : 0;

// S'assurer que le panier existe dans la session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// --- NOUVEAU CODE : Vérification avant d'ajouter ou de traiter une quantité non valide ---
if ($action === 'add_item') {
    if ($book_id <= 0) {
        $response['message'] = 'ID de livre invalide.';
        echo json_encode($response);
        exit;
    }

    // Vérifier si l'utilisateur est connecté et si le livre est déjà dans sa bibliothèque
    if (isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0) {
        $user_id = $_SESSION['user_id'];
        try {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM user_books WHERE user_id = ? AND book_id = ?");
            $stmt->execute([$user_id, $book_id]);
            $book_owned = $stmt->fetchColumn();

            if ($book_owned > 0) {
                // Le livre est déjà dans la bibliothèque de l'utilisateur
                $response['success'] = false; // Ne pas considérer cela comme un succès d'ajout
                $response['message'] = 'Ce livre est déjà dans votre bibliothèque. Vous ne pouvez pas l\'ajouter au panier.';
                echo json_encode($response);
                exit; // Arrêter le script ici, car le livre est déjà possédé
            }
        } catch (PDOException $e) {
            error_log("Erreur de base de données lors de la vérification de la bibliothèque : " . $e->getMessage());
            $response['message'] = 'Erreur lors de la vérification de votre bibliothèque.';
            echo json_encode($response);
            exit;
        }
    }

    // Si le livre n'est PAS possédé, et qu'il n'est pas déjà dans le panier, l'ajouter
    if (!array_key_exists($book_id, $_SESSION['cart'])) {
        $_SESSION['cart'][$book_id] = 1; // Quantité fixe à 1 pour ce projet
        $response['success'] = true;
        $response['message'] = 'Livre ajouté au panier !';
    } else {
        $response['success'] = false; // Ou true si vous considérez qu'il est "dans le panier"
        $response['message'] = 'Ce livre est déjà dans votre panier.';
    }
}
// --- FIN NOUVEAU CODE pour add_item ---

// Gestion de la suppression d'un élément
elseif ($action === 'delete_item') {
    if ($book_id <= 0) {
        $response['message'] = 'ID de livre invalide pour suppression.';
        echo json_encode($response);
        exit;
    }

    if (isset($_SESSION['cart'][$book_id])) {
        unset($_SESSION['cart'][$book_id]);
        $response['success'] = true;
        $response['message'] = 'Livre supprimé du panier.';
    } else {
        $response['message'] = 'Le livre n\'est pas dans le panier.';
    }
}

// Mettre à jour le nombre d'articles dans le panier
$response['cart_count'] = count($_SESSION['cart']);
$response['cart_empty'] = empty($_SESSION['cart']);

echo json_encode($response);
exit;
?>
