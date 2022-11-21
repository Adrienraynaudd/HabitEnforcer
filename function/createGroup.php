<?php
session_start();
require "dbSetting.php";
$dbFunction = new DBHandler;
if (isset($_POST['groupName'])) { //faire une vérif du nom
  $nameGroup = mysqli_real_escape_string($dbFunction->connect(), htmlspecialchars($_POST['groupName']));
  if ($nameGroup !== "") {
    $data = array(
      "ID" => $dbFunction->IdGenrerate(),
      "Score" => 0,
      "Name" =>  $nameGroup,
      "GroupCreator" => $_SESSION["userID"],
    );
    $dbFunction->insert($data, "groups");
    $dataUpdate = array(
      "GroupID" => "'" . $data["ID"] . "'",
    );
    $condition = "ID = '" . $data["GroupCreator"] . "'";
    $dbFunction->update($dataUpdate, "users", $condition);
  }
}
header('Location: ../php_template/grouphtml.php');
