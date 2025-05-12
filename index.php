<?php
include ('db.php');


$users = [];

try {
    $connection = new PDO("mysql:host=$hostname;dbname=$dbname", $dbuser, $dbpassword);
    $connection -> setAttribute(PDO :: ATTR_ERRMODE, PDO :: ERRMODE_EXCEPTION);
    
    $req = $connection -> query("select * from user");
    // fetchAll : permet de récupérer toutes le lignes retournés par la requête
    $users = $req ->fetchAll(PDO :: FETCH_ASSOC);
    echo "<pre>";
    var_dump($users);
    echo "</pre>"

} catch (PDOException $e) {
    var_dump($e);
    die("Stop");
}

?>
