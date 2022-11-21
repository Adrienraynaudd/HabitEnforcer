<?php
session_start();
session_destroy(); // Destroy the session
header('Location: ../php_template/home.php'); // Redirect to the home page
?>