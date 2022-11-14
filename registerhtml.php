<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>Register</title>
	<link rel="stylesheet" href="style.css" media="screen" type="text/css" />
</head>

<body>
	<div class="register">
		<h1>Register</h1>
		<form action="register.php" method="post" autocomplete="off">
			<label for="username">
				<i class="user"></i>
			</label>
			<input type="text" name="username" placeholder="Username" id="username" required>
			<label for="password">
				<i class="lock"></i>
			</label>
			<input type="password" name="password" placeholder="Password" id="password" required>
			<label for="email">
				<i class="mail"></i>
			</label>
			<input type="email" name="email" placeholder="Email" id="email" required>
			<input type="submit" value="Register">
			<?php
			if (isset($_GET['erreur'])) {
				$err = $_GET['erreur'];
				if ($err == 1) {
					echo "<p style='color:red'>Complete your registration form</p>";
				} elseif ($err == 2) {
					echo "<p style='color:red'>Email is not valid </p>";
				} elseif ($err == 3) {
					echo "<p style='color:red'>Username is not valid</p>";
				} elseif ($err == 4) {
					echo "<p style='color:red'>Username already exists</p>";
				}
			}
			?>
		</form>
	</div>
</body>

</html>