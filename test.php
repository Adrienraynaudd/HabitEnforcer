<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Ma page web</title>
    </head>
    
    <body>
        <h1>Ma page web</h1>
        
        <p>
            Bonjour <!-- Insérer le pseudo du visiteur ici --> !
        </p>

    </body>
</html>
<?php
    // Connexion à la base de données
    try
    {
        $bdd = new PDO('mysql:host=localhost;dbname=test;charset=utf8', 'root', '');
    }
    catch(Exception $e)
    {
        die('Erreur : '.$e->getMessage());
    }
    
    // Récupération du pseudo
    $req = $bdd->prepare('SELECT pseudo FROM membres WHERE id = ?');
    $req->execute(array($_GET['id']));
    
    $donnees = $req->fetch();
    
    echo $donnees['pseudo'];
    
    $req->closeCursor();