<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css" />
    <meta name="viewport" content="width=device-width">
</head>

<body>
    <?php
    session_start();
    if (!isset($_SESSION['username']) && $_SESSION['username'] != "") {
        header('Location: ../php_template/loginhtml.php');
        exit();
    }
    ?>
    <div class="GroupManager">
        <h1>GROUPE</h1>
        <?php
        require_once "../function/dbSetting.php";
        $dbFunction = new DBHandler;
        $iD_User = $dbFunction->getIDwithName("users", $_SESSION['username']);
        $iDGroup = $dbFunction->getFromDbByParam("users", "ID", $iD_User);
        if ($iDGroup["GroupID"] !== null) {
            $iDCreator = $dbFunction->getFromDbByParam("Groups", "ID", $iDGroup["GroupID"]);
            if ($iD_User === $iDCreator["GroupCreator"]) { // C'est le créateur du Groupe donc ADD et supp disponible
                $groupMember = $dbFunction->getEveryThingByParam("Users", "GroupID", $iDGroup["GroupID"]);
                echo ("MEMBRES :<br>");
                echo ("<form action='../function/deleteGroup.php' method='POST'>");
                foreach ($groupMember as $member) {
                    echo ("<div class=\"group-user\">");
                    echo ("<div><p>" . $member["Name"] . "</p></div>");
                    echo ("<button class='button' id='delete' name='delete' type='submit' value='" . $member["Name"] . "'>🗑️</button>");
                    echo ("</div>");
                }
                echo ("</form>");
                echo ("
            <form action='../function/addGroup.php' method='POST'>
            <input type='text' id='userAdd' placeholder='User add' name='userAdd' require>
            <input type='submit'> 
            </form>");
                echo ("<p>Score du groupe : " . $dbFunction->getFromDbByParam("Groups", "ID", $iDGroup["GroupID"])["Score"] . "</p>");
            } else { // LA personne peut juste ce supp et voir les membres
                echo ("en cours");
            }
        } else {
            echo ("
        <form action='../function/createGroup.php' method='POST'>
        <input type='text' id='groupName' placeholder='Group Name' name='groupName' require>
        <input type='submit'> 
        </form>");
        }

        ?>
    </div>
</body>

</html>