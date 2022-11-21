<?php
session_start();
require "dbSetting.php";
$dbFunction = new DBHandler;
$con = $dbFunction->connect();
if(isset($_POST['delete'])) {//Ajout de supp le groupe s'il n'y a plus personne !

    $updateUser = $con->prepare("UPDATE users SET GroupID = '' WHERE name = ?");
    $updateUser->execute(array($_POST['delete']));
    $groupMember = $dbFunction -> getEveryThingByParam("Users", "GroupID", $iDGroup["GroupID"]); 
    
    header('Location: ../php_template/grouphtml.php'); 
}
?>