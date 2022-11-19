<?php
session_start();
require "dbSetting.php";
$dbFunction = new DBHandler;
$con = $dbFunction->connect();
if(isset($_POST['delete'])) {//faire une vérif du mail ! + Ajout de supp le groupe
    $updateUser = $con->prepare("UPDATE users SET GroupID = '' WHERE name = ?");
    $updateUser->execute(array($_POST['delete']));  
}
?>