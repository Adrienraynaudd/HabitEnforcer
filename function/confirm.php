<?php
require_once 'dbSetting.php';
$re = new Confirm;
$re->confirm();
class Confirm {
    public function __construct() {
    }
    public function confirm() {
        $db = new DBHandler;
        $con = $db->connect();
        if (isset($_GET['username']) && isset($_GET['key']) AND !empty($_GET['username']) AND !empty($_GET['key'])) {
            $username = $db->SecurityCheck($con, $_GET['username']);
            $key = $_GET['key'];
            $requser = $con->prepare("SELECT * FROM users WHERE name = ? AND confirmkey = ?");
            $requser-> execute(array($username, $key));
            $userexist = $requser->fetch();
            $userAdd = $db -> getConfimeWithName("users", $username);
            if ($userexist != null) {
                $user = $requser->fetch();
                if ($user['confirme'] == 0) {
                    if ($userAdd == 3){
                        if(isset($_GET['Host']) AND !empty($_GET['Host']))
                        {
                            $Host = $db -> getFromDbByParam("users","Name",$_GET['Host']);
                            $updateUser = $con->prepare("UPDATE users SET GroupID = ? WHERE name = ? AND confirmkey = ?");
                            $updateUser->execute(array($Host["GroupID"], $username, $key));
                            $updateUser = $con->prepare("UPDATE users SET confirme = 1 WHERE name = ? AND confirmkey = ?");
                            $updateUser->execute(array($username, $key));
                            header('Location: ../php_template/loginhtml.php');
                            exit();
                        }
                    }
                    $updateUser = $con->prepare("UPDATE users SET confirme = 1 WHERE name = ? AND confirmkey = ?");
                    $updateUser->execute(array($username, $key));
                    header('Location: ../php_template/loginhtml.php');
                    exit();
                
                } else {
                    echo "Votre compte a déjà été confirmé !";
                }
            }else {
                echo "L'utilisateur n'existe pas !";
            }
        }else {
            echo "Erreur";
        }
            }
        }
?>