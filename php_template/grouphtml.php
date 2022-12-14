<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css" />
    <meta name="viewport" content="width=device-width">
</head>

<body>
    <?php
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
                    if ($member["Score"] >= 0) {
                        $score = "<p style=\"color: green\">+" . $member["Score"] . "</p>";
                    } else {
                        "<p style=\"color: red\">-" . $member["Score"] . "</p>";
                    }
                    echo ("<div>" . $score . "<p>" . $member["Name"] . "</p></div>");
                    echo ("<button class='button' id='delete' name='delete' type='submit' value='" . $member["Name"] . "'>🗑️</button>");
                }
                echo ("</form>");
                echo ("
            <form action='../function/addGroup.php' method='POST'>
            <input type='text' id='userAdd' placeholder='User add' name='userAdd' require>
            <input type='submit'> 
            </form>");
            } else {
                $groupMember = $dbFunction->getEveryThingByParam("Users", "GroupID", $iDGroup["GroupID"]);
                echo ("MEMBRES :<br>");
                echo ("<form action='../function/deleteGroup.php' method='POST'>");
                foreach ($groupMember as $member) {
                    if ($member["Name"] == $_SESSION['username']) {
                        if ($member["Score"] >= 0) {
                            $score = "<p style=\"color: green\">+" . $member["Score"] . "</p>";
                        } else {
                            "<p style=\"color: red\">-" . $member["Score"] . "</p>";
                        }
                        echo ("<div>" . $score . "<p>" . $member["Name"] . "</p></div>");
                        echo ("<button class='button' id='delete' name='delete' type='submit' value='" . $member["Name"] . "'>🗑️</button>");
                    } else {
                        if ($member["Score"] >= 0) {
                            $score = "<p style=\"color: green\">+" . $member["Score"] . "</p>";
                        } else {
                            "<p style=\"color: red\">-" . $member["Score"] . "</p>";
                        }
                        echo ("<div>" . $score . "<p>" . $member["Name"] . "</p></div>");
                    }
                }
                echo ("</form>");
            }
            echo "<p>Group Score : " . $iDCreator["Score"] . "</p>";
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
