<html>
 <head>
 <meta charset="utf-8">
 <!-- importer le fichier de style -->
 <link rel="stylesheet" href="style.css" media="screen" type="text/css" />
 </head>
 <body style='background:#fff;'>
 <div id="content">
 <!-- tester si l'utilisateur est connecté -->
 <?php
 session_start();
    if(isset($_SESSION['username']) && $_SESSION['username']!=""){
    	// afficher un message
    	echo "Welcome ".$_SESSION['username'];
    }
    else{
    	header('Location: loginhtml.php');
        exit();
    }
 ?>
 <input type="button" value="Logout" onclick="window.location.href='logout.php'">
 </div>
    <div class="Groupe">
        <h1>GROUPE</h1>
        <form action="searchUser.php" method="POST">
            <input type="text" id="username" placeholder="Username" required>
            <input type="submit"> 
        </form> 
    </div>
 </body>
</html>
