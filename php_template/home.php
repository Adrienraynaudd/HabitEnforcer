<html>
 <head>
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width">
 <!-- importer le fichier de style -->
 <link rel="stylesheet" href="style.css" media="screen" type="text/css" />
 </head>
 <body>
 <?php include "header.php" ?>
 <div id="content">
 <!-- tester si l'utilisateur est connecté -->
 <?php
 session_start();
    if(isset($_SESSION['username']) && $_SESSION['username']!=""){
    	// afficher un message
    	echo "Welcome ".$_SESSION['username'];
        if(!empty($_SESSION['avatar'])){
            echo '<img src="../Avatars/'.$_SESSION['avatar'].'" alt="Image Perso" width="150" height="150">';
        }
    }
    else{
    	header('Location: loginhtml.php');
        exit();
    }
 ?>
 <input type="button" value="profileEditing" onclick="window.location.href='editingProfile.php'">
 <input type="button" value="Logout" onclick="window.location.href='../function/logout.php'">
 </div>
 </body>
</html>