<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <link rel="stylesheet" href="style.css" media="screen" type="text/css" />
</head>
<?php
session_start();
include "header.php" ?>

<body>
    <?php
    if (isset($_SESSION['username']) && $_SESSION['username'] != "") {
        include("homeCo.php");
    } else {
        header('Location: loginhtml.php');
        exit();
    }
    ?>
    </div>
</body>

</html>