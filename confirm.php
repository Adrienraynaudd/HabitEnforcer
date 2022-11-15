<?php
require_once 'dbSetting.php';
$db = new DBHandler;
$con = $db->connect();
if (isset($_GET['username']) && isset($_GET['key']) AND !empty($_GET['username']) AND !empty($_GET['key'])) {
    $username = $db->SecurityCheck($con, $_GET['username']);
    $key = $_GET['key'];
    echo $username;
    echo $key;
    $requser = $con->prepare("SELECT * FROM users WHERE name = ? AND confirmkey = ?");
    $requser-> execute(array($username, $key));
    $userexist = $requser->fetch();
    echo $userexist;
    if ($userexist != null) {
        $user = $requser->fetch();
        if ($user['confirme'] == 0) {
            $updateUser = $con->prepare("UPDATE users SET confirme = 1 WHERE name = ? AND confirmkey = ?");
            $updateUser->execute(array($username, $key));
            header('Location: loginhtml.php');
        } else {
            echo "Votre compte a déjà été confirmé !";
        }
    }else {
        echo "L'utilisateur n'existe pas !";
    }
}else {
    echo "Erreur";
}


?>