<?php
require_once 'dbSetting.php';
require_once 'login-routine.php';
$re = new Login;
$re->login();
class Login  {
  public function __construct() {
  }
  function login (){
    if (isset($_POST['username']) && isset($_POST['password'])) {
      $db = new DBHandler;
      $con = $db->connect();
    function login (){
      $username = $db->SecurityCheck($con, $_POST['username']);
      $password = $db->SecurityCheck($con, $_POST['password']);
      if ($db->getConfimeWithName("users", $username)) {
        if ($username !== "" && $password !== "") {
          $reponse = $db->getPasswordWithName("users", $username);
          $hash = $reponse;
          if (password_verify($password, $hash)) {
            $_SESSION['userID'] = $db->getIDwithName('Users', $username);
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $db->getEmailwithName("users",$username);
            $_SESSION['avatar'] = $db->getAvatarwithName("users",$username);
            $routine = new Routine;
            $routine->logRoutine();
            header('Location: ../php_template/home.php');
          } else {
            mysqli_close($con); // fermer la connexion
            header('Location: HabitEnforcer/php_template/loginhtml.php?erreur=1'); // utilisateur ou mot de passe incorrect
          }
        } else {
          mysqli_close($con); // fermer la connexion
          header('Location: ../php_template/loginhtml.php?erreur=2');
        }
      } else {
        mysqli_close($con); // fermer la connexion
        header('Location: ../php_template/loginhtml.php?erreur=3');
        }
      }
    }else {
      header('Location: ../php_template/loginhtml.php');
    }
  }
}