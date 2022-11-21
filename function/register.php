<?php
require_once 'dbSetting.php';
$db = new DBHandler;
$con = $db->connect();
$username = $db->SecurityCheck($con, $_POST['username']);
$password = $db->SecurityCheck($con, $_POST['password']);
$email =  $db->SecurityCheck($con, $_POST['email']);
VerifyEnteredData($username, $password, $email); // Verify if the data is correct
$longueurKey = 15;
$key = "";
for ($i = 1; $i < $longueurKey; $i++) { //Generate a random key
	$key .= mt_rand(0, 9);
}
$newUsers = new Users($db->IdGenrerate(), $username, $password, $email, $key); // Create a new Users object
$newUsers->dbUserPush(); // Call the dbUserPush function


class Users
{
	public  $id;
	public $name;
	public $password;
	public $email;
	public $key;
	public $db;
	function __construct($id, $name, $password, $email, $key)
	{
		$this->id = $id;
		$this->name = $name;
		$this->password = password_hash($password, PASSWORD_DEFAULT);
		$this->email = $email;
		$this->key = $key;
		$this->db = new DBHandler;
	}
	public function dbUserPush()
	{
		$data = array(
			"ID" => $this->id,
			"name" => $this->name,
			"password" => $this->password,
			"email" => $this->email,
			"confirmkey" => $this->key
		);
		$this->db->getFromDbByParam("users", "name", $this->name); 
		if ($this->db->getFromDbByParam("users", "name", $this->name) != null) {// Check if the username is already taken
			header('Location: ../php_template/registerhtml.php?erreur=4');
			exit();
		} else {
			$this->db->insert($data, 'users'); // Insert the data into the database
			SendMail($this->email, $this->key, $this->name); // Call the SendMail function
			header('Location: ../php_template/loginhtml.php');
		}
	}
}
function VerifyEnteredData($username, $password, $email)
{
	if (!isset($username, $password, $email)) { // Check if the data is set
		header('Location: /..php_template/registerhtml.php?erreur=1');
		exit();
	}
	if (empty($username) || empty($password) || empty($email)) { // Check if the data is empty
		header('Location: ../php_template/registerhtml.php?erreur=1');
		exit();
	}
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { // Check if the email is valid
		header('Location: ../php_template/registerhtml.php?erreur=2');
		exit();
	}
	if (preg_match('/[A-Za-z0-9]+/', $username) == 0) { // Check if the username is valid
		header('Location: ../php_template/registerhtml.php?erreur=3');
		exit();
	}
}
	function SendMail($email,$key,$username){ // Send a mail to the user
			$to = $email;
			$header = "From: habitenforcer66@gmail.com";
			$header.='Content-Type:text/html; charset="uft-8"'."\n";
			$header.='Content-Transfer-Encoding: 8bit';
        	$subject = "mail de confirmation";
        	$message ="http://localhost/HabitEnforcer/function/confirm.php?username=" . urlencode($username) . "&key=" . $key;
        	mail($to,$subject,$message,$header);
	}
?>