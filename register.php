<?php
require_once 'dbSetting.php';
$db = new DBHandler;
$con = $db->connect();
$groupeID = 5456484165165;
$username = SecurityCheck($con,$_POST['username']);
 $password = SecurityCheck($con,$_POST['password']);
 $email =  SecurityCheck($con,$_POST['email']);
 VerifyEnteredData($username,$password,$email);
 $newUsers = new Users($db->IdGenrerate(),$username,$password,$email);
 $newUsers->dbUserPush();


class Users
{
	 // TODO ajout groupID
	public  $id;
	public $name;
	public $password;
	public $email;
	public $groupeID;
	public $db;
	function __construct($id,$name, $password, $email)
	{
		$this->id = $id;
		$this->name = $name;
		$this->password = password_hash($password, PASSWORD_DEFAULT);
		$this->email = $email;
		$this->db = new DBHandler;
	}
	public function dbUserPush()
	{
		$data = array(
			"ID" => $this->id,
			"name" => $this->name,
			"password" => $this->password,
			"email" => $this->email,
		);
		$this->db->getFromDbByParam("users", "name", $this->name);
		if ($this->db->getFromDbByParam("users", "name", $this->name) != null) {
			header('Location: register.php?erreur=4');
			exit();
		} else {
			$this->db->insert($data, 'users');
			header('Location: loginhtml.php');
		}
	}
	function SecurityCheck($con,$data)
	{
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		$data = mysqli_real_escape_string($con,$data);
		return $data;
	}
function VerifyEnteredData($username, $password, $email){
	if (!isset($username, $password, $email)) {
		header('Location: registerhtml.php?erreur=1');
		exit();
	}
	if (empty($username) || empty($password) || empty($email)) {
		header('Location: registerhtml.php?erreur=1');
		exit();
	}
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		header('Location: registerhtml.php?erreur=2');
		exit();
	}
	if (preg_match('/[A-Za-z0-9]+/', $username) == 0) {
	  header('Location: registerhtml.php?erreur=3');
	  exit();
		}
	}
}
?>