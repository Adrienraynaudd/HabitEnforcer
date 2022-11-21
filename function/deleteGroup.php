<?php
session_start();
require "dbSetting.php";
$dbFunction = new DBHandler;
$con = $dbFunction->connect();
if(isset($_POST['delete'])) {//Ajout de supp le groupe s'il n'y a plus personne !
    $iDGroup = $dbFunction -> getFromDbByParam("users", "Name", $_POST['delete']);
    $updateUser = $con->prepare("UPDATE users SET GroupID = NULL WHERE name = ?");
    $updateUser->execute(array($_POST['delete']));
    $groupMember = $dbFunction -> getEveryThingByParam("Users", "GroupID", $iDGroup["GroupID"]); 
    if($groupMember == array()){
        $dbFunction -> deleteGroup($iDGroup["GroupID"]);
    }
    mysqli_close($con);
    header('Location: ../php_template/grouphtml.php'); 
}
?>