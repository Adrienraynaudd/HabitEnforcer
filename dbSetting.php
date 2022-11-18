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
        $this->password = '';
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
        $sql = "INSERT INTO $table (" . implode(',', $columns) . ") VALUES (\"" . implode("\", \"", $values) . "\" )";
        if ($stmt = $con->prepare($sql)) {
            $stmt->execute();
            echo 'successfully inserted : ' . $sql;
        } else {
            echo "there has been an issue with : " . $sql . " " . mysqli_error($con);
        }
        mysqli_close($con);
    }

    public function getIDwithName(string $table, string $name)
    {
        $con = $this->connect();
        if ($con == false) {
            die("ERROR : couldn't connect properly to database : " . mysqli_connect_error());
        }
        $sql = "SELECT ID FROM " . $table . " WHERE Name = '" . $name . "'";
        if ($request = $con->prepare($sql)) {
            $request->execute();
            $resultQuerry = $request->get_result();
        } else {
            die("Can't prepare the sql request properly : " . $sql . " " . mysqli_error($con));
        }
        mysqli_close($con);
        return $resultQuerry->fetch_assoc()['ID'];
    }

    public function getFromDbByParam(string $table, string $param, string $condition): array|null
    {
        $con = $this->connect();
        if ($con == false) {
            die("ERROR : couldn't connect properly to database : " . mysqli_connect_error());
        }
        $sql = "SELECT * FROM " . $table . " WHERE " . $param . " = '" . $condition . "'";
        if ($request = $con->prepare($sql)) {
            $request->execute();
            $result = $request->get_result();
        } else {
            die("Can't prepare the sql request properly : " . $sql . " " . mysqli_error($con));
        }
        mysqli_close($con);
        return $result->fetch_assoc();
    }

    public function verifyByAuthor(string $table, string $authorID, string $param, string $toverify): bool
    {
        $con = $this->connect();
        if ($con == false) {
            die("ERROR : couldn't connect properly to database : " . mysqli_connect_error());
        }
        $sql = "SELECT " . $param . " FROM " . $table . " WHERE CreatorID = '" . $authorID . "' AND name = '" . $toverify . "'";
        if ($request = $con->prepare($sql)) {
            $request->execute();
            $result = $request->get_result()->fetch_assoc();
            if (count($result["name"]) > 0) {
                mysqli_close($con);
                return true;
            }
        } else {
            die("There has been an error with the authorID verification ! : " . $sql . " " . mysqli_error($con));
        }
        mysqli_close($con);
        return false;
    }

    public function getAllDataFromTable($table, $userID): string
    {
        $con = $this->connect();
        $answerArray = array();
        if ($con == false) {
            die("ERROR : couldn't connect properly to database : " . mysqli_connect_error());
        }
        $sql = "SELECT * FROM " . $table . " WHERE CreatorID = '" . $userID . "'";
        if ($request = $con->prepare($sql)) {
            $request->execute();
            $result = $request->get_result();
            while ($row = mysqli_fetch_assoc($result)) {
                $answerArray[] = $row;
            }
        } else {
            die("there has been an error in the process of : " . $sql . " " . mysqli_error($con));
        }
        mysqli_close($con);
        return json_encode($answerArray);
    }

    public function IdGenrerate()
    {
        $id = uniqid();
        $id = str_replace(".", "", $id);
        return $id;
    }
    public function getPasswordWithName(string $table, string $name)
    {
        $con = $this->connect();
        if ($con == false) {
            die("ERROR : couldn't connect properly to database : " . mysqli_connect_error());
        }
        $sql = "SELECT password FROM " . $table . " WHERE Name = '" . $name . "'";
        if ($request = $con->prepare($sql)) {
            $request->execute();
            $resultQuerry = $request->get_result();
        } else {
            die("Can't prepare the sql request properly : " . $sql . " " . mysqli_error($con));
        }
        mysqli_close($con);
        return $resultQuerry->fetch_assoc()['password'];
    }

    public function update(array $dataUpdate, string $table, array $condition){ //$Condition = ("ID = 5", Score = 5")
        $con = $this->connect();
        if ($con == false) {
            die("ERROR : couldn't connect properly to database : " . mysqli_connect_error());
        }
        $sql = "UPDATE ".$table." set "."GroupID = ".$dataUpdate["GroupID"]." WHERE ".implode(" AND ", $condition); //PRoblème avec ma gestion de la table
        if ($request = $con->prepare($sql)) {
            $request->execute();
            $resultQuerry = $request->get_result();
        } else {
            die("Can't prepare the sql request properly : " . $sql . " " . mysqli_error($con));
        }
        mysqli_close($con);
        return $resultQuerry->fetch_assoc()[$table];
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