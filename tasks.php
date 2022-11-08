<?php
require __DIR__ . "/dbSetting.php";
require __DIR__ . "./register.php";
$dbFunction = new DataBaseHandler;
if (isset($_POST["task_name"]) && isset($_POST["task_difficulty"]) && isset($_POST["task_recurrence"])) {
    if (isset($_POST["task_category"])) {
        $categoryID = $dbFunction->dbCategoryIDGet($_POST["task_category"], $_SESSION['id']);
    } else {
        $categoryID = IdGenrerate();
        $dbFunction->dbCategoryPush($categoryID, $_POST["category-name"], $_POST['category-color'], $_SESSION['id']);
    }
    $taskName = $_POST["task_name"];
    $taskDescription = $_POST["task_description"];
    $taskDifficulty = $_POST["task_difficulty"];
    $taskRecurrence = $_POST["task_recurrence"];
    $dbFunction->dbTasksPush($taskName, $taskDescription, $taskDifficulty, $taskRecurrence, $categoryID);
} else {
    echo ("something is not right with your forms");
}
?>
<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <title>test_tasks</title>
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
    <p><?php echo ($username) ?></p>
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