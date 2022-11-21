<?php
date_default_timezone_set('Europe/Paris');
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

    public function getFromDbByParam(string $table, string $param, string $condition)
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

    public function getEveryThingByParam(string $table, string $param, string $condition)
    {
        $con = $this->connect();
        if ($con == false) {
            die("ERROR : couldn't connect properly to database : " . mysqli_connect_error());
        }
        $answerArray = [];
        $sql = "SELECT * FROM " . $table . " WHERE " . $param . " = '" . $condition . "'";
        if ($request = $con->prepare($sql)) {
            $request->execute();
            $result = $request->get_result();
            while ($row = mysqli_fetch_assoc($result)) {
                $answerArray[] = $row;
            }
        } else {
            die("Can't prepare the sql request properly : " . $sql . " " . mysqli_error($con));
        }
        mysqli_close($con);
        return $answerArray;
    }
    public function getMembreGroupFromIDGroup(string $idGroup)
    {
        $con = $this->connect();
        if ($con == false) {
            die("ERROR : couldn't connect properly to database : " . mysqli_connect_error());
        }
        $sql = "SELECT * FROM Users WHERE GroupID = '" . $idGroup . "'";
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
    public function IdGenrerate()
    {
        $id = uniqid();
        $id = str_replace(".", "", $id);
        return $id;
    }
    public function taskComplete($taskID)
    {
        $con = $this->connect();
        if ($con == false) {
            die("ERROR : couldn't connect properly to database : " . mysqli_connect_error());
        }
        $sql = "SELECT complete FROM Tasks WHERE ID = '" . $taskID . "'";
        if ($request = $con->prepare($sql)) {
            $request->execute();
            $result = $request->get_result();
        } else {
            die("there has been an error in the process of : " . $sql . " " . mysqli_error($con));
        }
        mysqli_close($con);
        return $result->fetch_assoc()["complete"];
    }
    /**
     * update the boolean state of a value in a table
     * @param table $specify the table you want the function to affect
     * @param toChange $specify the value to change
     * @param ID $the ID of the object you want to change
     * @param state $0 == FALSE || 1 == TRUE
     */
    public function updateBooleanState($table, $toChange, $ID, $state)
    {
        $con = $this->connect();
        if ($con == false) {
            die("ERROR : couldn't connect properly to database : " . mysqli_connect_error());
        }
        $sql = "UPDATE " . $table . " SET " . $toChange . " = " . $state . " WHERE ID = '" . $ID . "'";
        if ($request = $con->prepare($sql)) {
            $request->execute();
        } else {
            die("there has been an error in the process of : " . $sql . " " . mysqli_error($con));
        }
        mysqli_close($con);
    }

    public function userLoginDateUpdate($userID)
    {
        $con = $this->connect();
        if ($con == false) {
            die("ERROR : couldn't connect properly to database : " . mysqli_connect_error());
        }
        $date = date("Y-m-d H:i:s", time());
        $sql = "UPDATE Users SET logdate = '" . $date . "' WHERE ID = '" . $userID . "'";
        if ($request = $con->prepare($sql)) {
            $request->execute();
        } else {
            die("there has been an error in the process of : " . $sql . " " . mysqli_error($con));
        }
        mysqli_close($con);
    }

    public function getPasswordWithName(string $table, string $name)
    {
        $con = $this->connect();
        if ($con == false) {
            die("ERROR : couldn't connect properly to database : " . mysqli_connect_error());
        }
        $date = date("Y-m-d H:i:s", time());
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

    public function getLastRowFromTable($table, $toSearch, $condition)
    {
        $con = $this->connect();
        if ($con == false) {
            die("ERROR : couldn't connect properly to database : " . mysqli_connect_error());
        }
        $sql = "SELECT " . $toSearch . " FROM " . $table . " WHERE CreatorID = '" . $condition . "'";
        if ($request = $con->prepare($sql)) {
            $request->execute();
            $result = $request->get_result();
        } else {
            die("Can't prepare the sql request properly : " . $sql . " " . mysqli_error($con));
        }
        mysqli_close($con);
        return end($result->fetch_assoc());
    }
    public function update(array $dataUpdate, string $table, string $condition)
    { //$Condition = ("ID = 5", Score = 5")
        $con = $this->connect();
        if ($con == false) {
            die("ERROR : couldn't connect properly to database : " . mysqli_connect_error());
        }
        $sql = "UPDATE " . $table . " set " . "GroupID = " . $dataUpdate["GroupID"] . " WHERE " . $condition; //PRoblème avec ma gestion de la table
        if ($request = $con->prepare($sql)) {
            $request->execute();
        } else {
            die("Can't prepare the sql request properly : " . $sql . " " . mysqli_error($con));
        }
        mysqli_close($con);
    }
    public function updateTaskDate($taskID, $span)
    {
        $toadd = $span * 86400;
        $con = $this->connect();
        if ($con == false) {
            die("ERROR : couldn't connect properly to database : " . mysqli_connect_error());
        }
        $date = date("Y-m-d 00:00:00", time() + $toadd);
        $sql = "UPDATE Tasks SET limitDate = '" . $date . "' WHERE ID = '" . $taskID . "'";
        if ($request = $con->prepare($sql)) {
            $request->execute();
        } else {
            die("there has been an error in the process of : " . $sql . " " . mysqli_error($con));
        }
        mysqli_close($con);
    }
    function SecurityCheck($con, $data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        $data = mysqli_real_escape_string($con, $data);
        return $data;
    }
    public function getEmailwithName(string $table, string $name)
    {
        $con = $this->connect();
        if ($con == false) {
            die("ERROR : couldn't connect properly to database : " . mysqli_connect_error());
        }
        $sql = "SELECT Email FROM " . $table . " WHERE Name = '" . $name . "'";
        if ($request = $con->prepare($sql)) {
            $request->execute();
            $resultQuerry = $request->get_result();
        } else {
            die("Can't prepare the sql request properly : " . $sql . " " . mysqli_error($con));
        }
        mysqli_close($con);
        return $resultQuerry->fetch_assoc()['Email'];
    }
    public function getConfimeWithName(string $table, string $name)
    {
        $con = $this->connect();
        if ($con == false) {
            die("ERROR : couldn't connect properly to database : " . mysqli_connect_error());
        }
        $sql = "SELECT confirme FROM " . $table . " WHERE Name = '" . $name . "'";
        if ($request = $con->prepare($sql)) {
            $request->execute();
            $resultQuerry = $request->get_result();
        } else {
            die("Can't prepare the sql request properly : " . $sql . " " . mysqli_error($con));
        }
        mysqli_close($con);
        return $resultQuerry->fetch_assoc()['confirme'];
    }
    public function getAvatarwithName(string $table, string $name)
    {
        $con = $this->connect();
        if ($con == false) {
            die("ERROR : couldn't connect properly to database : " . mysqli_connect_error());
        }
        $sql = "SELECT avatar FROM " . $table . " WHERE Name = '" . $name . "'";
        if ($request = $con->prepare($sql)) {
            $request->execute();
            $resultQuerry = $request->get_result();
        } else {
            die("Can't prepare the sql request properly : " . $sql . " " . mysqli_error($con));
        }
        mysqli_close($con);
        return $resultQuerry->fetch_assoc()['avatar'];
    }

    public function updateScore($table, $score, $ID)
    {
        $con = $this->connect();
        if ($con == false) {
            die("ERROR : couldn't connect properly to database : " . mysqli_connect_error());
        }
        $sql = "UPDATE " . $table . " SET Score = " . $score . " WHERE ID = '" . $ID . "'";
        if ($request = $con->prepare($sql)) {
            $request->execute();
        } else {
            die("there has been an error in the process of : " . $sql . " " . mysqli_error($con));
        }
        mysqli_close($con);
    }

    public function deleteByParam($table, $param, $condition)
    {
        $con = $this->connect();
        if ($con == false) {
            die("ERROR : couldn't connect properly to database : " . mysqli_connect_error());
        }
        $sql = "DELETE FROM " . $table . " WHERE '" . $param . "' = '" . $condition . "'";
        if ($request = $con->prepare($sql)) {
            $request->execute();
        } else {
            die("there has been an error in the process of : " . $sql . " " . mysqli_error($con));
        }
        mysqli_close($con);
    }
    public function deleteGroup(string $iDGroup)
    {
        $con = $this->connect();
        if ($con == false) {
            die("ERROR : couldn't connect properly to database : " . mysqli_connect_error());
        }
        $sql = "DELETE FROM groups WHERE ID = '" . $iDGroup . "'";
        if ($stmt = $con->prepare($sql)) {
            $stmt->execute();
            echo 'successfully delete : ' . $sql;
        } else {
            echo "there has been an issue with : " . $sql . " " . mysqli_error($con);
        }
        mysqli_close($con);
    }
}
