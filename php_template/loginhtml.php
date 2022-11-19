<html>

<head>
    <title>login</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css" />
    <meta name="viewport" content="width=device-width">
</head>

<body>
    <section class="login-container">

        <form action="../function/login.php" method="POST" id="login-form">
            <h1>Login</h1>
            <input class="login-input" type="text" placeholder="Entrer le nom d'utilisateur" name="username" required>
            <input type="password" placeholder="Entrer le mot de passe" name="password" required>
            <input type="submit" id='submit' value='LOGIN' class="button">
            <input type="button" class="login-button" value="Register" onclick="window.location.href='registerhtml.php'" class="button">
            <?php
            if (isset($_GET['erreur'])) {
                $err = $_GET['erreur'];
                if ($err == 1 || $err == 2) {
                    echo "<p style='color:red'>Utilisateur ou mot de passe incorrect</p>";
                } else if ($err == 3) {
                    echo "<p style='color:red'>Veuillez confirmer votre compte</p>";
                }
            }
            ?>
        </form>
    </section>
</body>

</html>