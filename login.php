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
  $requete = "SELECT password from users where username = '".$username."' ";
  $exec_requete = mysqli_query($db,$requete);
  $reponse      = mysqli_fetch_array($exec_requete);
  $hash = $reponse['password'];
  if (password_verify($password, $hash)) {
    $_SESSION['username'] = $username;
    header('Location: test.php');
  }
  else
  {
   header('Location: loginhtml.php?erreur=1'); // utilisateur ou mot de passe incorrect
  }
 }
 else
 {
 header('Location: loginhtml.php?erreur=2');
 }
}
else
{
 header('Location: loginhtml.php');
}
mysqli_close($db); // fermer la connexion
?>