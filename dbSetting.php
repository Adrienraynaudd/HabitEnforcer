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

    public function getFromDbByParam(string $table, string $param, string $condition): array
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

    public function getNameByAuthor($table, $authorID): array
    {
        $con = $this->connect();
        $answerArray = array();
        if ($con == false) {
            die("ERROR : couldn't connect properly to database : " . mysqli_connect_error());
        }
        $sql = "SELECT name FROM " . $table . " WHERE CreatorID = '" . $authorID . "'";
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
        return $answerArray;
    }

    public function taskComplete($taskID)
    {
        $con = $this->connect();
        if ($con == false) {
            die("ERROR : couldn't connect properly to database : " . mysqli_connect_error());
        }
        $sql = "SELECT complete FROM Tasks WHERE TaskID = '" . $taskID . "'";
        if ($request = $con->prepare($sql)) {
            $request->execute();
            $result = $request->get_result();
        } else {
            die("there has been an error in the process of : " . $sql . " " . mysqli_error($con));
        }
        mysqli_close($con);
        return $result->fetch_assoc()["complete"];
    }

    public function updateTaskCompleState($taskID, $state)
    {
        if ($state == "complete") {
            $state = 1;
        } else {
            $state = 0;
        }
        $con = $this->connect();
        if ($con == false) {
            die("ERROR : couldn't connect properly to database : " . mysqli_connect_error());
        }
        $sql = "UPDATE Tasks SET complete = " . $state . " WHERE TaskID = '" . $taskID . "'";
        if ($request = $con->prepare($sql)) {
            $request->execute();
        } else {
            die("there has been an error in the process of : " . $sql . " " . mysqli_error($con));
        }
        mysqli_close($con);
    }

    public function IdGenrerate()
    {
        $id = uniqid();
        $id = str_replace(".", "", $id);
        return $id;
    }
}
