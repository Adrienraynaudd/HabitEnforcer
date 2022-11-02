<?php
// connection base de donnee
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'habitenforcer');
 
// Connexion à la base de données MySQL 
$con = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
// Vérifier la connexion
if($con === false){
    die("ERREUR : Impossible de se connecter. " . mysqli_connect_error());
}
echo "Connected successfully \n";
if (!isset($_POST['username'], $_POST['password'], $_POST['email'])) {
	exit('Please complete the registration form!');
}
if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])) {
	exit('Please complete the registration form');
}
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
  exit('Email is not valid!');
}
if (preg_match('/[A-Za-z0-9]+/', $_POST['username']) == 0) {
  exit('Username is not valid!');
}
if ($stmt = $con->prepare('SELECT id, password FROM users WHERE username = ?')) {
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	$stmt->store_result();
	if ($stmt->num_rows > 0) {
		echo 'Username exists, please choose another! \n';
	} else {
if ($stmt = $con->prepare('INSERT INTO users (username, password, email) VALUES (?, ?, ?)')) {
	//$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
	$password = $_POST['password'];
	$stmt->bind_param('sss', $_POST['username'], $password, $_POST['email']);
	$stmt->execute();
	echo 'You have successfully registered, you can now login! \n';
} else {
	echo 'Could not prepare statement!';
}
	}
	$stmt->close();
} else {
	echo 'Could not prepare statement!';
}
header('Location: login.html');
$con->close();
?>