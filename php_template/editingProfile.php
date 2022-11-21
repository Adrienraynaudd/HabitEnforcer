<?php
session_start();
include "header.php";
require_once '../function/dbSetting.php';
$db = new DBHandler;
$con = $db->connect();
if (isset($_SESSION['username']) && $_SESSION['username'] != "") {
    
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Profile editing</title>
        <meta charset="utf-8">
        </head>
        <body>
            <div align="center">
                <h2>Profile Editing</h2>
                </div>
                <div align="Left">
                <form method="POST" enctype ="multipart/form-data" >
                    <label>Username :</label>
                    <input type="text" name="newUsername" placeholder="Username" value="<?php echo $_SESSION['username']; ?>" /><br /><br />
                    <label>Email :</label>
                    <input type="text" name="newEmail" placeholder="Email" value="<?php echo $_SESSION['email']; ?>" /><br /><br />
                    <label>Profil picture :</label>
                    <input type="file" name="avatar" /><br /><br />
                    <input type="button" value="home" onclick="window.location.href='../php_template/home.php'">
                    <input type = "submit" name="Edit" value="Edit" />
                    <?php
                    if(isset($_GET['erreur'])){
                        $err = $_GET['erreur'];
                    if ($err==1) {
                        echo"<p>Username already taken</p>";
                    }
                    elseif ($err==2) {
                        echo"<p>email is not valid</p>";
                    }
                }
                    ?>
                </form>
            </div>
        </body>
</html>


<?php
if (isset($_FILES['avatar']) AND !empty($_FILES['avatar']['name']))
{
    editAvatar();
}
if (isset($_POST['newUsername']) AND !empty($_POST['newUsername']) AND isset($_POST['newEmail']) AND !empty($_POST['newEmail'])){
    editProfil($_POST['newUsername'], $_POST['newEmail']);
}
exit();
} else {
    header('../php_template/loginhtml.php');
    exit();
}
?>
<?php
function editProfil($newUsername, $newEmail)
{
    $db = new DBHandler;
    $con = $db->connect();
    $checkUsername = $db->SecurityCheck($con, $newUsername);
    $checkEmail = $db->SecurityCheck($con, $newEmail);
    if ($checkUsername == $_SESSION['username']){
        if (filter_var($checkEmail, FILTER_VALIDATE_EMAIL)) {
            $req1 = $con->prepare("UPDATE users SET name = ?, email = ? WHERE name = ?");
            $req1->execute(array($checkUsername, $checkEmail, $_SESSION['username']));
            $_SESSION['username'] = $checkUsername;
            $_SESSION['email'] = $checkEmail;
            exit();
        } else {
            header('Location: editingProfile.php?erreur=2');
            exit();
        }
    }
    $requser = $con->prepare("SELECT * FROM users WHERE name = ?");
    $requser-> execute(array($checkUsername));
    $userexist = $requser->fetch();
    if ($userexist == null) {
        if (filter_var($checkEmail, FILTER_VALIDATE_EMAIL)) {
            $req = $con->prepare("UPDATE users SET name = ?, email = ? WHERE name = ?");
            $req->execute(array($checkUsername, $checkEmail, $_SESSION['username']));
            $_SESSION['username'] = $checkUsername;
            $_SESSION['email'] = $checkEmail;
            exit();
        } else {
            header('Location: editingProfile.php?erreur=2');
            exit();
        }
    }else {
        header('Location: ../php_template/editingProfile.php?erreur=1');
    }
}
function editAvatar()
{
    require_once '../function/dbSetting.php';
    $db = new DBHandler;
    $con = $db->connect();
    $sizeMax = 2097152;
    $extensionsValides = array('jpg', 'jpeg', 'gif', 'png');
    if ($_FILES['avatar']['size'] <= $sizeMax)
    {
        $extensionUpload = strtolower(substr(strrchr($_FILES['avatar']['name'], '.'), 1));
        if (in_array($extensionUpload, $extensionsValides))
        {
            $chemin = "../Avatars/" . $_SESSION['username'] . "." . $extensionUpload;
            $resultat = move_uploaded_file($_FILES['avatar']['tmp_name'], $chemin);
            if ($resultat)
            {
                $avatar = $_SESSION['username'] . "." . $extensionUpload;
                $updateavatar = $con->prepare('UPDATE users SET avatar = ? WHERE name = ?');
                $updateavatar->bind_param("ss",$avatar , $_SESSION['username']);
                $updateavatar->execute();
                $_SESSION['avatar'] = $avatar;
            }
            else
            {
                echo"error during the upload";
            }
        }
        else
        {
            echo"Your avatar must be in jpg, jpeg, gif or png";
        }
    }
    else
    {
        echo"Your avatar must be less than 2Mo";
    }
}
?>
