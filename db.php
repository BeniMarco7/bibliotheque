<?php
// Démarrer la session si ce n'est pas déjà fait.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Paramètres de connexion à la base de données
define('DB_HOST', 'localhost');    
define('DB_USER', 'root');     
define('DB_PASS', '');           
define('DB_NAME', 'biblizone');    // Le nom de la base de données que vous avez confirmée

// Connexion à la base de données avec PDO
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    // Définir le mode d'erreur PDO sur Exception pour qu'il lance des exceptions en cas d'erreur.
    // Cela facilite le débogage.
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Définir le mode de récupération par défaut sur FETCH_ASSOC pour récupérer les résultats
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // En cas d'erreur de connexion, arrêter le script et afficher un message d'erreur.
    // En production, il est préférable de ne pas afficher $e->getMessage() directement pour des raisons de sécurité.
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
