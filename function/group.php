<?php
require "dbSetting.php";
Class Group
{
    public $dbFunction;
    public $con;
    function __construct(){
        $this -> dbFunction = new DBHandler;
        $this -> con = $this -> dbFunction->connect();
    }
    public function add($name){
        $userAdd = mysqli_real_escape_string($this -> dbFunction -> connect(), htmlspecialchars($name));
        if($userAdd !== ""){
            $user = $this -> dbFunction -> getFromDbByParam("Users","Name", $userAdd);
            $updateUser = $this -> con->prepare("UPDATE users SET confirme = 3 WHERE name = ? AND confirmkey = ?");
            $updateUser->execute(array($user['Name'], $user['confirmKey']));
            $link = "http://localhost/HabitEnforcer/function/confirm.php?username=" . urlencode($user["Name"]) . "&key=" . $user["confirmKey"]."&Host=".$_SESSION['username'];
            $to = $user["Email"];
            $subject = $_SESSION['username']." aimerait t'inviter dans son Groupe";
            $message = "Hello ".$user["Name"]." ! \n".$_SESSION['username']." aimerait t'inviter dans son Groupe Habit Enforcer\nMerci de cliquer sur le lien suivant pour accepter :".$link;
            $headers = "From: habitenforcer66@gmail.com";
            mail($to,$subject,$message,$headers);
            mysqli_close($this -> con);
        }
    }
    public function delete($name){
        $iDGroup = $this -> dbFunction -> getFromDbByParam("users", "Name", $name);
        $updateUser = $this -> con->prepare("UPDATE users SET GroupID = NULL WHERE name = ?");
        $updateUser->execute(array($name));
        $groupMember = $this -> dbFunction -> getEveryThingByParam("Users", "GroupID", $iDGroup["GroupID"]); 
        if($groupMember == array()){
            $this -> dbFunction -> deleteGroup($iDGroup["GroupID"]);
        }
        mysqli_close($this -> con);
    }
    public function create($name){
        $nameGroup = mysqli_real_escape_string($this -> dbFunction -> connect(), htmlspecialchars($name));
        if($nameGroup !== ""){
          $data = array(
            "ID" => $this -> dbFunction -> IdGenrerate(),
            "Score" => 0,
            "Name" =>  $nameGroup,
            "GroupCreator" => $this -> dbFunction -> getIDwithName('Users', $_SESSION['username']),
          );  
          $this -> dbFunction -> insert($data, "groups");
          $dataUpdate = array(
            "GroupID" => "'".$data["ID"]."'",
          );
          $condition = "ID = '".$data["GroupCreator"]."'"; 
          $this -> dbFunction -> update($dataUpdate, "users", $condition);
        }
    }
}
?>