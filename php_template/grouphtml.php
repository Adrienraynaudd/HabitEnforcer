<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css" />
    <meta name="viewport" content="width=device-width">
</head>

<body>
    <?php
    session_start();
    if (isset($_SESSION['username']) && $_SESSION['username'] != "") {
        // afficher un message
        echo "Welcome " . $_SESSION['username'];
    } else {
        header('Location: ../php_template/loginhtml.php');
        exit();
    }
    ?>
    <div class="GroupManager">
        <h1>GROUPE</h1>
        <?php
        require "../function/dbSetting.php";
        $dbFunction = new DBHandler;
        $iD_User = $_SESSION["userID"];
        $iDGroup = $dbFunction->getFromDbByParam("users", "ID", $iD_User);
        if ($iDGroup["GroupID"] !== null) {
            $iDCreator = $dbFunction->getFromDbByParam("Groups", "ID", $iDGroup["GroupID"]);
            if ($iD_User === $iDCreator["GroupCreator"]) { // C'est le créateur du Groupe donc ADD et supp disponible 
                $Member = $dbFunction->getMembreGroupFromIDGroup($iDGroup["GroupID"]);
                echo ("MEMBRES :<br>");
                echo ("<form action='../function/deleteGroup.php' method='POST'>");
                foreach ($Member as $value) { //CHANGEMENT l'image par un button avec le smiley corbeille
                    echo "$value <br>   ";
                    echo ("<input class='corbeille' type='image' src='corbeille.png' value='$value' id='delete' name='delete'>");
                }
                echo ("</form>");
                echo ("
            <form action='addGroup.php' method='POST'>
            <input type='text' id='userAdd' placeholder='User add' name='userAdd' require>
            <input type='submit'> 
            </form>");
            } else { // LA personne peut juste ce supp et voir les membres
                echo ("en cours");
            }
        } else {
            echo ("
        <form action='../function/createGroup.php' method='POST'>
        <input type='text' id='groupName' placeholder='Group Name' name='groupName' required>
        <input type='submit'> 
        </form>");
        }

        ?>
    </div>
</body>

</html>