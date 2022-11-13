<?php
session_start();
if(isset($_POST['username']))
{
    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '');
    define('DB_NAME', 'habitenforcer');
   
    // Connexion à la base de données MySQL 
    $db = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    // Vérifier la connexion
    if($db === false){
      die("ERREUR : Impossible de se connecter. " . mysqli_connect_error());
    }
    $username = mysqli_real_escape_string($db,htmlspecialchars($_POST['username']));
}
?>