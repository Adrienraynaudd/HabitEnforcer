<h1>GROUPE</h1>
        <form action="createGroup.php" method="POST">
            <input type="text" id="username" placeholder="Username" name="username" required>
            <input type="submit"> 
            <?php
            if(isset($_GET['erreur'])){
                $erreur = $_GET['erreur'];
                if($erreur === 1){
                    echo "<p style='color:red'>Utilisateur introuvable</p>";
                }
            }
            
            ?>
        </form> 