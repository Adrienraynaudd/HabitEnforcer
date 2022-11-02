<?php
session_start();
if(isset($_POST['username']) && isset($_POST['password']))
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
 // on applique les deux fonctions mysqli_real_escape_string et htmlspecialchars
 // pour éliminer toute attaque de type injection SQL et XSS
 $username = mysqli_real_escape_string($db,htmlspecialchars($_POST['username'])); 
 $password = mysqli_real_escape_string($db,htmlspecialchars($_POST['password']));
 
 if($username !== "" && $password !== "")
 {
 $requete = "SELECT count(*) FROM users where 
  username = '".$username."' and password = '".$password."' ";
 $exec_requete = mysqli_query($db,$requete);
 $reponse = mysqli_fetch_array($exec_requete);
 $count = $reponse['count(*)'];
 if($count!=0) 
 {
 $_SESSION['username'] = $username;
 header('Location: test.php');
 }
 else
 {
 header('Location: login.html?erreur=1');
 }
 }
 else
 {
 header('Location: login.html?erreur=2');
 }
}
else
{
 header('Location: login.html');
}
mysqli_close($db); // fermer la connexion
?>