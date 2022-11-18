<html>
 <head>
 <meta charset="utf-8">
 <link rel="stylesheet" href="style.css" media="screen" type="text/css" />
 </head>
 <body>
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
    <h1>GROUPE</h1>
    <?php
    

    ?>
    <form action="createGroup.php" method="POST">
        <input type="text" id="groupName" placehorder="Group Name" name="groupName" require>
        <input type="submit"> 
    </form> 
</body>
</html>