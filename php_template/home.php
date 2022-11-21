<html>
 <head>
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width">
 <!-- importer le fichier de style -->
 <link rel="stylesheet" href="style.css" media="screen" type="text/css" />
 </head>
 <body>
 <?php 
 session_start();
 include "header.php" ?>
 <div id="content">
 <!-- tester si l'utilisateur est connecté -->
 <?php
    if(isset($_SESSION['username']) && $_SESSION['username']!=""){
    	// afficher un message
    	echo "Welcome ".$_SESSION['username'];
    }
    else{
    	header('Location: loginhtml.php');
        exit();
    }
 ?>
 <br /><br />
 <input type="button" value="profileEditing" onclick="window.location.href='editingProfile.php'"><br /><br />
 <input type="button" value="Logout" onclick="window.location.href='../function/logout.php'">
 </div>
 </body>
</html>