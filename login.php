<?php
session_start();
if(isset($_POST['username']) && isset($_POST['password']))
{
  require_once 'dbSetting.php';
  $db = new DBHandler;
  $con = $db->connect();
  if ($db->getFromDbByParam("users", "	confirme", 1)){
 $username = $db->SecurityCheck($con,$_POST['username']);
 $password = $db->SecurityCheck($con,$_POST['password']);
 
 if($username !== "" && $password !== "")
 {
  $reponse = $db->getPasswordWithName("users",$username);
  $hash = $reponse;
  if (password_verify($password, $hash)) {
    $_SESSION['username'] = $username;
    header('Location: test.php');
  }else{
   header('Location: loginhtml.php?erreur=1'); // utilisateur ou mot de passe incorrect
  }
 }else{
 header('Location: loginhtml.php?erreur=2');
 }
}else{
 header('Location: loginhtml.php?erreur=3');
}
}else {
 header('Location: loginhtml.php');
}
mysqli_close($db); // fermer la connexion
?>