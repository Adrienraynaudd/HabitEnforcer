<?php
require __DIR__ . "/dbSetting.php";
$dbFunction = new DataBaseHandler;
$db = $dbFunction->dbConnexion();
if (isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["email"]) && $_POST["password"] == $_POST["passwordConfirmation"]) {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $sqlCommand = "INSERT INTO Users (ID, Username, Password) VALUES (001, $username, $password)";
} else {
    echo ("something is not right with your forms");
}

if (mysqli_query($db, $sqlCommand)) {
    echo ("New user created successfuly !");
} else {
    echo ("Error: " . $sqlCommand . "<br>" . mysqli_error($db));
}
$email = $_POST["email"];
$username = $_POST["username"];
mysqli_close($db);
?>
<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <title>test</title>
    <link href="./test.css" rel="stylesheet">
</head>
<html>

<register-form>
    <form id="register" class="register-form" method="post">
        <input type="text" placeholder="username" name="username" id="username">
        <input type="password" name="password" placeholder="password" id="password">
        <input type="password" name="passwordConfirmation" placeholder="password conf" id="passwordConf">
        <input type="email" id="email" name="email" placeholder="email">
        <button type="submit">submit</button>
    </form>
    <p><?php echo ($username) ?></p>
</register-form>

</html>