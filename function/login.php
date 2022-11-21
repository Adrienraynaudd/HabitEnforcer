<?php
require_once 'dbSetting.php';
require_once 'login-routine.php';
if (isset($_POST['username']) && isset($_POST['password'])) {
  $re = new Login(); // Create a new Login object
  $re->login(); // Call the login function
}else {
  header('Location: ../php_template/loginhtml.php');
  exit();
}

Class Login
{
  function __construct() {
    $this->db = new DBHandler;
    $this->con = $this->db->connect();
    }
      function login()
      {
        $username = $this->db->SecurityCheck($this->con, $_POST['username']);
        $password = $this->db->SecurityCheck($this->con, $_POST['password']);
        if ($this->db->getConfimeWithName("users", $username)) { // If the user is confirmed
          if ($username !== "" && $password !== "") {
            $reponse = $this->db->getPasswordWithName("users", $username); // Get the password of the user
            $hash = $reponse; // Set the hash variable
            if (password_verify($password, $hash)) { // Verify if the password is the same as the hash
              $_SESSION['userID'] = $this->db->getIDwithName('Users', $username); // Set the session userID variable
              $_SESSION['username'] = $username;// Set the session username variable
              $_SESSION['email'] = $this->db->getEmailwithName("users",$username);// Set the session email variable
              $_SESSION['avatar'] = $this->db->getAvatarwithName("users",$username);// Set the session avatar variable
              $routine = new Routine; // Create a new Routine object
              $routine->logRoutine(); // Call the logRoutine function
              header('Location: ../php_template/home.php');
              exit();
            } else {
              mysqli_close($this->con); // Close the connection
              header('Location:../php_template/loginhtml.php?erreur=1'); // Username or password incorrect
              exit();
            }
          } else {
            mysqli_close($this->con); // Close the connection
            header('Location: ../php_template/loginhtml.php?erreur=2'); // One or more fields are empty
            exit();
          }
        } else {
          mysqli_close($this->con); // Close the connection
          header('Location: ../php_template/loginhtml.php?erreur=3'); // The user is not confirmed
          exit();
      }
    }
  }