<?php
include ("db.php");
if (isset($_POST['register'])) {
    extract($_POST);
    $password = sha1($password);
    try {

        $req = $connection -> query("INSERT INTO user (name, telephone, email, password) VALUES ('$name', '$telephone', '$email', '$password')");
        $connection -> exec($req);
    } catch (PDOException $e) {
        echo "Une erreur est survenue. Rééssayez ulterieurement";
    }
    echo "Inscription réussie";
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
</head>
<body>
    <h1>Inscription</h1>
<form  action="register.php" method="post">

        <input name= "name" type="text" placeholder="Nom">
        <br><br>
        <input name="name" type="text" placeholder="Prenom">
        <br><br>
        <input name="email" type="email" placeholder="Adresse email">
        <br><br>
        <input name="telephone" type="tel" placeholder="Numero de téléphone">
        <br><br> 
        <input name="password" type ="password" placeholder="Mot de Passe">
        <br><br>
        <input name="login" type="submit" value="Inscription">
        
    </form>

</body>
</html>