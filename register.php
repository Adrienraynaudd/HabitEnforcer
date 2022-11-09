<?php
// connection base de donnee
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'root');
define('DB_NAME', 'habitenforcer');

// Connexion à la base de données MySQL
$con = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
// Vérifier la connexion
if ($con === false) {
	die("ERREUR : Impossible de se connecter. " . mysqli_connect_error());
}
$username = mysqli_real_escape_string($con, htmlspecialchars($_POST['username']));
$password = mysqli_real_escape_string($con, htmlspecialchars($_POST['password']));
$email = mysqli_real_escape_string($con, htmlspecialchars($_POST['email']));
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
if ($stmt = $con->prepare('SELECT id FROM users WHERE username = ?')) {
	$stmt->bind_param('s', $username);
	$stmt->execute();
	$stmt->store_result();
	if ($stmt->num_rows > 0) {
		header('Location: registerhtml.php?erreur=4');
		exit();
	} else {
		if ($stmt = $con->prepare('INSERT INTO `Users` (id,username, password, email) VALUES (?, ?, ?, ?)')) {
			$password = password_hash($password, PASSWORD_DEFAULT);
			$id = IdGenrerate();
			$stmt->bind_param('ssss', $id, $username, $password, $email);
			$state = $stmt->execute();
			if ($state) {
				echo "it worked\n";
			} else {
				echo mysqli_error($con);
			}
			echo 'You have successfully registered, you can now login! \n';
		} else {
			echo 'Could not prepare statement!';
		}
	}
	$stmt->close();
} else {
	echo 'Could not prepare statement!';
}
$con->close();
?>
<?php
function IdGenrerate()
{
	$id = uniqid();
	$id = str_replace(".", "", $id);
	return $id;
}
?>