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

    function __construct(string $id, string $name, string $description = NULL, string $difficulty, string $recurrence, string $categoryID, string $authorID)
    {
        $this->taskID = $id;
        $this->taskName = $name;
        $this->taskDescription = $description;
        $this->taskDifficulty = $difficulty;
        $this->taskRecurrence = $recurrence;
        $this->taskCategoryID = $categoryID;
        $this->taskAuthorID = $authorID;
    }

    public function dbTaskPush()
    {
        $db = new DBHandler();
        $data = array(
            "taskID" => $this->taskID,
            "name" => $this->taskName,
            "description" => $this->taskDescription,
            "difficulty" => $this->taskDifficulty,
            "recurrence" => $this->taskRecurrence,
            "creationDate" => date("Y-m-d H:i:s", time()),
            "authorID" => $this->taskAuthorID,
            "categoryID" => $this->taskCategoryID
        );
        $db->insert($data, 'Tasks');
    }
}

class Category
{
    public $categoryID;
    public $categoryName;
    public $categoryColor;
    public $categoryAuthorID;

    function __construct(string $id, string $name, string $color, string $authorID)
    {
        $this->categoryID = $id;
        $this->categoryName = $name;
        $this->categoryColor = $color;
        $this->categoryAuthorID = $authorID;
    }

    public function dbCategoryPush()
    {
        $db = new DBHandler();
        $data = array(
            "ID" => $this->categoryID,
            "Name" => $this->categoryName,
            "color" => $this->categoryColor,
            "CreatorID" => $this->categoryAuthorID
        );
        $db->insert($data, "TasksCategories");
    }
}
?>

<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <title>test tasks</title>
    <link href="./test.css" rel="stylesheet">
</head>
<html>

<task-form>
    <form id="new_tasks" class="register-form" method="post">
        <input type="text" placeholder="task_name" name="task_name" id="task_name">
        <input type="text" name="task_description" placeholder="task_description" id="task_description">
        <categories>
            <select name="task_category">
                <option value="" style="color:blue">select a task category</option>
                <option value="hello">hello</option>
            </select>
            <input type="button" id="new-cat" value="create new cat">
            <category-creator id="cat-creator">
                <input type="color" name="category-color" value="<?php echo $color ?>">
                <input type="text" name="category-name" placeholder="category name">
            </category-creator>
        </categories>
        <select name="task_difficulty">
            <option value="">task difficulty</option>
            <option value="easy">Easy</option>
            <option value="normal">Normal</option>
            <option value="medium">Medium</option>
            <option value="hard">Hard</option>
        </select>
        <select name="task_recurrence">
            <option value="">recurrence</option>
            <option value="daily">Dayly</option>
            <option value="weekly">Weekly</option>
        </select>
        <button type="submit">submit</button>
    </form>
    <p style="color: <?php echo $_POST["task_color"] ?>">color</p>
    <?php echo $_SESSION['id'] ?>
</task-form>

</html>

<script>
    const button = document.getElementById("new-cat");
    let isClicked = false;
    button.addEventListener("click", showForm);

    function showForm() {
        if (!isClicked) {
            isClicked = true;
            document.getElementById("cat-creator").style.display = "block";
        } else {
            isClicked = false;
            document.getElementById("cat-creator").style.display = "none";
        }
    }
</script>