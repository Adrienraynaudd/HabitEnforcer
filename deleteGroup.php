<?php
session_start();
require "dbSetting.php";
$dbFunction = new DBHandler;
$con = $dbFunction->connect();
if(isset($_POST['delete'])) {//faire une vérif du mail si c'est vérifier ! + Ajout de supp le groupe s'il n'y a plus personne !
    $updateUser = $con->prepare("UPDATE users SET GroupID = '' WHERE name = ?");
    $updateUser->execute(array($_POST['delete']));  
}
?>