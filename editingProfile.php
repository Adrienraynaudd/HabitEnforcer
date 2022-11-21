<?php
session_start();
require_once 'dbSetting.php';
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
                    <input type="button" value="home" onclick="window.location.href='test.php'">
                    <input type = "submit" name="Edit" value="Edit" />
                    <?php
                    if(isset($_GET['erreur'])){
                        $err = $_GET['erreur'];
                    if ($err==1) {
                        echo"error during the upload";
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
    header('Location: loginhtml.php');
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
    $newP = $con->prepare("UPDATE users SET name = ?, email = ? WHERE name = ?");
    $newP->bind_param("sss", $checkUsername, $checkEmail, $_SESSION['username']);
    $newP->execute();
    $_SESSION['username'] = $checkUsername;
    $_SESSION['email'] = $checkEmail;
}
function editAvatar()
{
    require_once 'dbSetting.php';
    $db = new DBHandler;
    $con = $db->connect();
    $sizeMax = 2097152;
    $extensionsValides = array('jpg', 'jpeg', 'gif', 'png');
    if ($_FILES['avatar']['size'] <= $sizeMax)
    {
        $extensionUpload = strtolower(substr(strrchr($_FILES['avatar']['name'], '.'), 1));
        if (in_array($extensionUpload, $extensionsValides))
        {
            $chemin = "Avatars/" . $_SESSION['username'] . "." . $extensionUpload;
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
