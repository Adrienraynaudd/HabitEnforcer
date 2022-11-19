<?php
require "dbSetting.php";
session_start();
date_default_timezone_set('Europe/Paris');
$db = new DBHandler;
if (isset($_POST["task_name"]) && isset($_POST["task_difficulty"]) && isset($_POST["task_recurrence"])) {
    if ($_POST["task_category"] != "") {
        $categoryID = $db->getIDwithName("TasksCategories", $_POST["task_category"]);
    } else {
        $categoryID = $db->IdGenrerate();
        $authorID = $_SESSION['userID'];
        $newCat = new Category($categoryID, $_POST["category-name"], $_POST["category-color"], $authorID);
        $newCat->dbCategoryPush();
    }
    $taskName = $_POST["task_name"];
    $taskName = str_replace(" ", "_", $taskName);
    $taskDescription = $_POST["task_description"];
    $taskDifficulty = $_POST["task_difficulty"];
    $taskRecurrence = $_POST["task_recurrence"];
    $newTask = new Task($db->IdGenrerate(), $taskName, $taskDescription, $taskDifficulty, $taskRecurrence, $categoryID, $_SESSION["userID"]);
    $newTask->dbTaskPush();
    header('Location: http://localhost:8888/php_template/tasklist.php');
}


class Task
{
    public $taskID;
    public $taskName;
    public $taskDescription;
    public $taskDifficulty;
    public $taskRecurrence;
    public $taskCategoryID;
    public $taskAuthorID;
    public $db;

    function __construct(string $id, string $name, string $description = NULL, string $difficulty, string $recurrence, string $categoryID, string $authorID)
    {
        $this->taskID = $id;
        $this->taskName = $name;
        $this->taskDescription = $description;
        $this->taskDifficulty = $difficulty;
        $this->taskRecurrence = $recurrence;
        $this->taskCategoryID = $categoryID;
        $this->taskAuthorID = $authorID;
        $this->db = new DBHandler;
    }

    public function dbTaskPush()
    {
        if ($this->taskRecurrence == "daily") {
            $span = 0;
        } else {
            $span = 6;
        }
        $data = array(
            "ID" => $this->taskID,
            "name" => $this->taskName,
            "description" => $this->taskDescription,
            "difficulty" => $this->taskDifficulty,
            "recurrence" => $this->taskRecurrence,
            "limitDate" => date("Y-m-d 0:0:0", time() + 86400 * $span),
            "creatorID" => $this->taskAuthorID,
            "categoryID" => $this->taskCategoryID
        );
        $this->db->insert($data, 'Tasks');
        $this->db->updateBooleanState("Users", "CanCreate", $this->taskAuthorID, 0);
    }
}

class Category
{
    // TODO Verification si la catégorie existe avec l'utilisateur
    public $categoryID;
    public $categoryName;
    public $categoryColor;
    public $categoryAuthorID;
    public $db;

    function __construct(string $id, string $name, string $color, string $authorID)
    {
        $this->categoryID = $id;
        $this->categoryName = $name;
        $this->categoryColor = $color;
        $this->categoryAuthorID = $authorID;
        $this->db = new DBHandler;
    }

    public function dbCategoryPush()
    {
        $data = array(
            "ID" => $this->categoryID,
            "Name" => $this->categoryName,
            "color" => $this->categoryColor,
            "CreatorID" => $this->categoryAuthorID
        );
        if ($this->db->verifyByAuthor("TasksCategories", $this->categoryAuthorID, "name", $this->categoryName) == true) {
            die("This category already exist");
        } else {
            $this->db->insert($data, "TasksCategories");
        }
    }
}
