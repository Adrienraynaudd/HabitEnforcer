<?php
class DBHandler
{
    private $name;
    private $user;
    private $password;
    private $host;

    function __construct()
    {
        $this->name = 'habitenforcer';
        $this->user = 'root';
        $this->password = 'root';
        $this->host = 'localhost';
    }

    public function connect()
    {
        $link = mysqli_connect($this->host, $this->user, $this->password, $this->name);
        return $link;
    }

    public function insert(array $data, string $table)
    {
        $con = $this->connect();
        if ($con == false) {
            die("ERROR : couldn't connect properly to database : " . mysqli_connect_error());
        }
        $columns = array_keys($data);
        $values = array_values($data);
        $sql = "INSERT INTO $table (" . implode(',', $columns) . ") VALUES ('" . implode("', '", $values) . "' )";
        if ($stmt = $con->prepare($sql)) {
            $stmt->execute();
        } else {
            echo "there has been an issue";
        }
        echo 'successfully inserted : ' . $sql;
        mysqli_close($con);
    }

    public function getIDwithName(string $table, string $name)
    {
        if ($table == 'Users') $param = 'Username';
        else $param = 'name';
        $con = $this->connect();
        if ($con == false) {
            die("ERROR : couldn't connect properly to database : " . mysqli_connect_error());
        }
        $sql = "SELECT ID FROM " . $table . " WHERE " . $param . " = '" . $name . "'";
        if ($request = $con->prepare($sql)) {
            $request->execute();
            $resultQuerry = $request->get_result();
        } else {
            die("Can't prepare the sql request right : " . $sql);
        }
        mysqli_close($con);
        return $resultQuerry->fetch_assoc()['ID'];
    }

    public function IdGenrerate()
    {
        $id = uniqid();
        $id = str_replace(".", "", $id);
        return $id;
    }

    // public function dbTasksPush($taskName, $taskDescription = NULL, $taskDifficulty, $taskRecurrence, $taskCategory)
    // {
    //     $con = mysqli_connect($this->dbserver, $this->dbname, $this->dbpassword, $this->dbname);
    //     if ($con == false) {
    //         die("ERREUR : Can't connect to database : " . mysqli_connect_error());
    //     }
    //     if ($stmt = $con->prepare('INSERT INTO Tasks (TaskID, Name, Description, Difficulty, TimeSpan, Recurrence, CreationDate, CategoryID) VALUES (?,?,?,?,?,?,?,?)')) {
    //         $taskID = IdGenrerate();
    //         $stmt->bind_param('sssisss', $taskID, $taskName, $taskDescription, $taskDifficulty, $taskRecurrence, $taskCategory);
    //         $stmt->execute();
    //         echo 'You have successfully created a new task !';
    //     } else {
    //         echo 'could not prepare statement !';
    //     }
    //     $stmt->close();
    // }
    // public function dbTasksGet()
    // {
    // }
    // public function dbCategoryPush($categoryID, $categoryName, $categoryColor, $creatorID)
    // {
    //     $con = mysqli_connect($this->dbserver, $this->dbname, $this->dbpassword, $this->dbname);
    //     if ($con == false) {
    //         die("ERREUR : Can't connect to database : " . mysqli_connect_error());
    //     }
    //     if ($stmt = $con->prepare('INSERT INTO TasksCategories (ID, Name, Color, CreatorID) VALUES (?,?,?,?')) {
    //         $stmt->bind_param('ssss', $categoryID, $categoryName, $categoryColor, $creatorID);
    //         $stmt->execute();
    //         echo 'You have successfully created a new category !';
    //     } else {
    //         echo 'could not prepare statement !';
    //     }
    //     $stmt->close();
    // }
    // public function dbCategoryIDGet($categoryName, $authorID): string
    // {
    //     $con = mysqli_connect($this->dbserver, $this->dbname, $this->dbpassword, $this->dbname);
    //     if ($con == false) {
    //         die("ERREUR : Can't connect to database : " . mysqli_connect_error());
    //     }
    //     $var = $con->query('SELECT ID FROM TasksCategories WHERE Name = ' . $categoryName . ' AND CreatorID = ' . $authorID);
    //     $categoryID = $var->fetch_array();
    //     return $categoryID;
    // }
}
