<?php
require_once 'dbSetting.php';
require_once 'login-routine.php';
if (isset($_POST['username']) && isset($_POST['password'])) {
  $db = new DBHandler;
  $con = $db->connect();
  if ($db->getFromDbByParam("users", "confirme", 1)) {
    $username = $db->SecurityCheck($con, $_POST['username']);
    $password = $db->SecurityCheck($con, $_POST['password']);

    if ($username !== "" && $password !== "") {
      $reponse = $db->getPasswordWithName("users", $username);
      $hash = $reponse;
      if (password_verify($password, $hash)) {
        $_SESSION['userID'] = $db->getIDwithName('Users', $username);
        $_SESSION['username'] = $username;
        $routine = new Routine;
        $routine->logRoutine();
        header('Location: ../php_template/home.php');
      } else {
        mysqli_close($con); // fermer la connexion
        header('Location: ../php_template/loginhtml.php?erreur=1'); // utilisateur ou mot de passe incorrect
      }
    } else {
      mysqli_close($con); // fermer la connexion
      header('Location: ../php_template/loginhtml.php?erreur=2');
    }
  } else {
    mysqli_close($con); // fermer la connexion
    header('Location: ../php_template/loginhtml.php?erreur=3');
  }
} else {
  header('Location: ../php_template/loginhtml.php');
}
