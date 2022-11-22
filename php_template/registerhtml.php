<?php
session_start();
include "header.php";
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>Register</title>
	<link rel="stylesheet" href="style.css" media="screen" type="text/css" />
</head>

<body>
	<div class="login-register-container">
		<section>
			<form action="../function/register.php" method="post" autocomplete="off" class="login-register-form">
				<h1>Register</h1>
				<label for="username">
					<i class="user"></i>
				</label>
				<input class="login-register-input" type="text" name="username" placeholder="Username" id="username" required>
				<label for="password">
					<i class="lock"></i>
				</label>
				<input class="login-register-input" type="password" name="password" placeholder="Password" id="password" required>
				<label for="email">
					<i class="mail"></i>
				</label>
				<input class="login-register-input" type="email" name="email" placeholder="Email" id="email" required>
				<input class="login-register-input" type="submit" value="Register" class="button">
				<input class="login-register-input" type="button" value="Login" onclick="window.location.href='loginhtml.php'" class="button">
				<?php
				if (isset($_GET['erreur'])) {
					$err = $_GET['erreur'];
					if ($err == 1) {
						echo "<h1 style='color:white'>Complete your registration form</h1>";
					} elseif ($err == 2) {
						echo "<h1 style='color:white'>Email is not valid </h1>";
					} elseif ($err == 3) {
						echo "<h1 style='color:white'>Username is not valid</h1>";
					} elseif ($err == 4) {
						echo "<h1 style='color:white'>Username already exists</h1>";
					}
				}
				?>
			</form>

		</section>
	</div>
</body>

</html>