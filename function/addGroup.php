<?php
session_start();
require "group.php";
if(isset($_POST['userAdd'])) {
   $group = new group;
   $group -> add($_POST['userAdd']); 
}
header('Location: ../php_template/grouphtml.php'); //redirection in home 
?>