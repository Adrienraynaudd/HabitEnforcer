<?php
session_start();
require "dbSetting.php";
$dbFunction = new DBHandler;
$con = $dbFunction->connect();
if(isset($_POST['userAdd'])) {
    $userAdd = mysqli_real_escape_string($dbFunction -> connect(), htmlspecialchars($_POST['userAdd']));
    if($userAdd !== ""){
        $user = $dbFunction -> getFromDbByParam("Users","Name", $userAdd);
        $updateUser = $con->prepare("UPDATE users SET confirme = 3 WHERE name = ? AND confirmkey = ?");
        $updateUser->execute(array($user['Name'], $user['confirmKey']));
        $link = "http://localhost/HabitEnforcer/function/confirm.php?username=" . urlencode($user["Name"]) . "&key=" . $user["confirmKey"]."&Host=".$_SESSION['username'];
        $to = $user["Email"];
        $subject = $_SESSION['username']." aimerait t'inviter dans son Groupe";
        $message = "Hello ".$user["Name"]." ! \n".$_SESSION['username']." aimerait t'inviter dans son Groupe Habit Enforcer\nMerci de cliquer sur le lien suivant pour accepter :".$link;
        $headers = "From: habitenforcer66@gmail.com";
        mail($to,$subject,$message,$headers);
        echo "Mail Sent.";
    }
}
?>