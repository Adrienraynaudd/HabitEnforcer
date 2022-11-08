<?php
require __DIR__ . "./register.php";
class DataBaseHandler
{
    public function dbConnexion()
    {
        $db_host = 'localhost';
        $db_user = 'root';
        $db_password = 'root';
        $db_db = 'HabitUserDB';
        $mysqli = @new mysqli($db_host, $db_user, $db_password, $db_db);
        if ($mysqli->connect_error) {
            echo 'Errno: ' . $mysqli->connect_errno;
            echo '<br>';
            echo 'Error: ' . $mysqli->connect_error;
            exit();
        }
        return $mysqli;
    }
    public function dbTasksPush($taskName, $taskDescription = NULL, $taskDifficulty, $taskRecurrence, $taskCategory)
    {
        $con = self::dbConnexion();
        if ($con == false) {
            die("ERREUR : Can't connect to database : " . mysqli_connect_error());
        }
        if ($stmt = $con->prepare('INSERT INTO Tasks (TaskID, Name, Description, Difficulty, TimeSpan, Recurrence, CreationDate, CategoryID) VALUES (?,?,?,?,?,?,?,?)')) {
            $taskID = IdGenrerate();
            $stmt->bind_param('sssisss', $taskID, $taskName, $taskDescription, $taskDifficulty, $taskRecurrence, $taskCategory);
            $stmt->execute();
            echo 'You have successfully created a new task !';
        } else {
            echo 'could not prepare statement !';
        }
        $stmt->close();
    }
    public function dbTasksGet()
    {
    }
    public function dbCategoryPush($categoryID, $categoryName, $categoryColor, $creatorID)
    {
        $con = self::dbConnexion();
        if ($con == false) {
            die("ERREUR : Can't connect to database : " . mysqli_connect_error());
        }
        if ($stmt = $con->prepare('INSERT INTO TasksCategories (ID, Name, Color, CreatorID) VALUES (?,?,?,?')) {
            $stmt->bind_param('ssss', $categoryID, $categoryName, $categoryColor, $creatorID);
            $stmt->execute();
            echo 'You have successfully created a new category !';
        } else {
            echo 'could not prepare statement !';
        }
        $stmt->close();
    }
    public function dbCategoryIDGet($categoryName, $authorID): string
    {
        $con = self::dbConnexion();
        if ($con == false) {
            die("ERREUR : Can't connect to database : " . mysqli_connect_error());
        }
        $var = $con->query('SELECT ID FROM TasksCategories WHERE Name = ' . $categoryName . ' AND CreatorID = ' . $authorID);
        $categoryID = $var->fetch_array();
        return $categoryID;
    }
}
