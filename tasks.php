<?php
require "dbSetting.php";
$db = new DBHandler;
if (isset($_POST["task_name"]) && isset($_POST["task_difficulty"]) && isset($_POST["task_recurrence"])) {
    if ($_POST["task_category"] != "") {
        $categoryID = $db->getIDwithName("TasksCategories", $_POST["task_category"]);
    } else {
        $categoryID = $db->IdGenrerate();
        $authorID = $db->getIDwithName('Users', 'test');
        $newCat = new Category($categoryID, $_POST["category-name"], $_POST["category-color"], $authorID);
        $newCat->dbCategoryPush();
    }
    $taskName = $_POST["task_name"];
    $taskDescription = $_POST["task_description"];
    $taskDifficulty = $_POST["task_difficulty"];
    $taskRecurrence = $_POST["task_recurrence"];
    $newTask = new Task($db->IdGenrerate(), $taskName, $taskDescription, $taskDifficulty, $taskRecurrence, $categoryID, $db->getIDwithName('Users', 'test'));
    $newTask->dbTaskPush();
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
        $data = array(
            "taskID" => $this->taskID,
            "name" => $this->taskName,
            "description" => $this->taskDescription,
            "difficulty" => $this->taskDifficulty,
            "recurrence" => $this->taskRecurrence,
            "creationDate" => date("Y-m-d H:i:s", time()),
            "creatorID" => $this->taskAuthorID,
            "categoryID" => $this->taskCategoryID
        );
        $this->db->insert($data, 'Tasks');
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
