<html>
 <head>
    <title>login</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css" />
    <meta name="viewport" content="width=device-width">
 </head>
 <body>
    <section>
        
        <form  action="login.php" method="POST">
            <h1>Login</h1>
            <input type="text" placeholder="Entrer le nom d'utilisateur" name="username" required>
            <input type="password" placeholder="Entrer le mot de passe" name="password" required>
            <input type="submit" id='submit' value='LOGIN' class="button" >
            <input type="button" value="Register" onclick="window.location.href='registerhtml.php'" class="button">
            <?php
            if(isset($_GET['erreur'])){
                $err = $_GET['erreur'];
                if($err==1 || $err==2){
                echo "<h1 style='color:white'>Utilisateur ou mot de passe incorrect</h1>";
                }else if($err==3){
                echo "<h1 style='color:white'>Veuillez confirmer votre compte</h1>";
                }
            }
            ?>
        </form>
    </section>
 </body>
</html>