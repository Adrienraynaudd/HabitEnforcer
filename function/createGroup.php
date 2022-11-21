<?php
session_start();
require "group.php";
if(isset($_POST['groupName'])) {
   $group = new group;
   $group -> create($_POST['groupName']);
}
header('Location: ../php_template/grouphtml.php');
?>