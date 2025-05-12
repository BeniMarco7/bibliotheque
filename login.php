<?php
include('db.php');
session_start();
if (isset($_SESSION['user'])) {
    header("Location: index.php");
}
$error = "";


if (isset($_POST['login'])) {
    extract($_POST);
    if (!empty($username) && !empty($password)) {
        $password = sha1($password);
        try {
            $req = $req = $connection -> query("SELECT * from user WHERE email= '$username' AND password= '$password' ");
            $user = $req ->fetch();
            var_dump($user);
        } catch (PDOException $e){
            var_dump($e -> getMessage());
        }
    } else {
        $error = "Merci de bien vouloir renseigner tous les champs";
    }
}


/*
if (isset($_GET['username']) && isset($_GET['password'])) {
    $_username = $_GET["username"];
    $_password = $_GET["password"];

    if ($username = $_username && $password =$_password) {
        header(google.com);
    } else {
        $error = "Utilisateur introuvable";
    }
    
}
*/
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form  action="login.php">
        <input name="username" type="text" 
        placeholder="Nom d'utilisateur">
        <br><br>
        <input name="password" type ="password" 
        placeholder="Mot de Passe">
        <br><br>
        <input name="login" type="submit"
        value="Connexion">
    </form>

    <p><?php echo $error; ?></p>
       
</body>

</html>

