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
  if($username !== ""){
    $requete = "SELECT ID from users where username = '".$username."' ";//PB de DEux username pareil
    $exec_requete = mysqli_query($db, $requete);
    $reponse = mysqli_fetch_array($exec_requete);
    $ID_User = $reponse['ID'];
  }
  if($ID_User !== null){
    if ($stmt = $db->prepare('INSERT INTO groups (ID, Score, Members, GroupCreator) VALUES (?, ?, ?, ?)')) {
      $id = IdGenrerate();
      $score = 0;
      $usernameCreator = $_SESSION['username'];
      $GroupCreator = IDUserSession();
      $Members = $ID_User;
      $stmt->bind_param('ssss',$id, $score, $Members, $GroupCreator);
      $stmt->execute();
      echo 'You have successfully registered your group ! \n';
      $stmt->close(); 
    } else {
      echo 'Could not prepare statement!';
    }
    header('Location: test.php');
  }else{
    header('Location: test.php?erreur=1');
  }
  mysqli_close($db); // fermer la connexion
}

function IDUserSession(){  
  // Connexion à la base de données MySQL 
  $db = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
  // Vérifier la connexion
  if($db === false){
      die("ERREUR : Impossible de se connecter. " . mysqli_connect_error());
  }
  $username = $_SESSION['username'];
  $requete = "SELECT ID from users where username = '".$username."' ";
  $exec_requete = mysqli_query($db,$requete);
  $reponse      = mysqli_fetch_array($exec_requete);
  $ID_User = $reponse['ID'];
  mysqli_close($db); // fermer la connexion
  return $ID_User;
}
function IdGenrerate(){
	$id = uniqid();
	$id = str_replace(".", "", $id);
	return $id;
}
?>