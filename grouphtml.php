<html>
 <head>
 <meta charset="utf-8">
 <link rel="stylesheet" href="style.css" />
 <meta name="viewport" content="width=device-width">
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
    <div class="GroupManager">
    <h1>GROUPE</h1>
    <?php
    require "dbSetting.php";
    $dbFunction = new DBHandler;
    $iD_User = $dbFunction -> getIDwithName("users", $_SESSION['username']);
    $iDGroup = $dbFunction -> getFromDbByParam("users", "ID", $iD_User);
    if($iDGroup["GroupID"] !== null){
        $iDCreator = $dbFunction -> getFromDbByParam("Groups","ID", $iDGroup["GroupID"]);
        if($iD_User === $iDCreator["GroupCreator"]){ // C'est le créateur du Groupe
            $Member = $dbFunction -> getMembreGroupFromIDGroup($iDGroup["GroupID"]);
            echo("MEMBRES :<br>");
            foreach ($Member as $value) {
                echo "$value <br>";
            }
            echo("
            <form action='addGroup.php' method='POST'>
            <input type='text' id='userAdd' placeholder='User add' name='userAdd' require>
            <input type='submit'> 
            </form>");
        }else{
            echo("en cours");
        }
    }else{
        echo("
        <form action='createGroup.php' method='POST'>
        <input type='text' id='groupName' placeholder='Group Name' name='groupName' require>
        <input type='submit'> 
        </form>");
    }

    ?>
    </div>
</body>
</html>