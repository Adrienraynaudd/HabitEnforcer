<?php
session_start();
if(isset($_POST['username']) && isset($_POST['password']))
{
  require_once 'dbSetting.php';
  $db = new DBHandler;
  $con = $db->connect();
  $username = $db->SecurityCheck($con,$_POST['username']);
  $password = $db->SecurityCheck($con,$_POST['password']);
  if ($db->getConfimeWithName("users", $username)){

 if($username !== "" && $password !== "")
 {
  $reponse = $db->getPasswordWithName("users",$username);
  $hash = $reponse;
  if (password_verify($password, $hash)) {
    $_SESSION['username'] = $username;
    $_SESSION['email'] = $db->getEmailwithName("users",$username);
    $_SESSION['avatar'] = $db->getAvatarwithName("users",$username);

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