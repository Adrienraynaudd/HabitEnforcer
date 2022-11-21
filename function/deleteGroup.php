<?php
session_start();
require "group.php";
if(isset($_POST['delete'])) {
   $group = new group;
   $group -> delete($_POST['delete']);
}
header('Location: ../php_template/grouphtml.php'); //redirection in home 
?>