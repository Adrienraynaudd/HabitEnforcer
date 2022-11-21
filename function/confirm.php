<?php
require_once 'dbSetting.php';
$re = new Confirm;
$re->confirm();
class Confirm {
    public function __construct() {
        $this->db = new DBHandler;
        $this->con = $this->db->connect();
    }
    public function confirm() {
        if (isset($_GET['username']) && isset($_GET['key']) AND !empty($_GET['username']) AND !empty($_GET['key'])) { // Verify data username and key
            $username = $this->db->SecurityCheck( $this->con, $_GET['username']);// Set username variable for the request
            $key = $_GET['key'];
            $requser =  $this->con->prepare("SELECT * FROM users WHERE name = ? AND confirmkey = ?"); // Select the user with the username and the key
            $requser-> execute(array($username, $key)); // Execute the request
            $userexist = $requser->fetch(); // Fetch the result
            if ($userexist != null) {
                $user = $requser->fetch();
                if ($user['confirme'] == 0) { // If the user is not confirmed
                    $updateUser =  $this->con->prepare("UPDATE users SET confirme = 1 WHERE name = ? AND confirmkey = ?"); // Update the user to confirmed
                    $updateUser->execute(array($username, $key));
                    header('Location: ../php_template/loginhtml.php'); // Redirect to the login page
                    exit();
                }elseif ($user['confirme'] == 3){ // ICI pq je ne rentre pas !
                    if(isset($_GET['Host']) AND !empty($_GET['Host']))
                    {
                        $Host = $this->db -> getFromDbByParam("users","Name",$_GET['Host']);
                        $updateUser =  $this->con->prepare("UPDATE users SET GroupID = ".$Host["GroupID"]." WHERE name = ? AND confirmkey = ?");
                        $updateUser->execute(array($username, $key));
                        /*$updateUser = $con->prepare("UPDATE users SET confirme = 1 WHERE name = ? AND confirmkey = ?");
                        $updateUser->execute(array($username, $key));*/
                        //header('Location: grouphtml.php');
                    }
                } else {
                    echo "your account is already confirmed";
                }
            }else {
                echo "the user does not exist";
            }
        }else {
            echo "Error";
        }
            }
        }
