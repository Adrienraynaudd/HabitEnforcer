<?php
require "../function/dbSetting.php";
session_start();
if (!isset($_SESSION["username"])) {
    header('Location: home.php');
}
date_default_timezone_set('Europe/Paris');
$dbFunction = new DBHandler;
$tasks = json_decode($dbFunction->getAllDataFromTable("Tasks", $_SESSION["userID"]), false);
$categories = json_decode($dbFunction->getAllDataFromTable("TasksCategories", $_SESSION["userID"]), false);
$diffcultyArray = array(
    "easy" => "green",
    "normal" => "yellow",
    "medium" => "orange",
    "hard" => "red"

);
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>test tasks</title>
    <link href="style.css" rel="stylesheet">
</head>

<body>
    <?php include "header.php" ?>
    <content>
        <task-menu>
            <task-menu-up>
                <task-filters>
                    <input type="text" onkeyup="searchOnPageByName()" id="searchbar" placeholder="Search for a task with its name">
                </task-filters>
                <task-creator>
                    <?php $userCanCreate = $dbFunction->getFromDbByParam("Users", "ID", $_SESSION["userID"])["CanCreate"];
                    if ($userCanCreate == 1) {
                        echo "<input type=\"button\" id=\"show-form\" value=\"New Task\">";
                    } else {
                        $countDownDate = new DateTime("now");
                        $countDownDate = $countDownDate->format("Y-m-d 0:0:0");
                        echo "<p id=\"count-down-createTask\"></p>";
                    } ?>
                </task-creator>
            </task-menu-up>
            <new-task-form id="new-task-form" style="display: block">
                <?php include "taskhtml.php" ?>
            </new-task-form>
        </task-menu>
        <task-list>
            <?php
            foreach ($tasks as $task) {
                foreach ($categories as $category) {
                    if ($category->CreatorID == $task->CreatorID && $category->ID == $task->CategoryID) {
                        $color = $category->Color;
                    }
                }
                $diffculty = $task->Difficulty;
            ?>
                <div class="task-card" id="task-card-<?php echo $task->Name ?>" style="border-color: <?php echo $color ?>">
                    <card-header>
                        <p style="color: <?php echo $diffcultyArray[$diffculty] ?>">O</p>
                        <p class="task-name"><?php echo str_replace("_", " ", $task->Name) ?></p>
                    </card-header>
                    <card-body>
                        <p><?php echo $task->Description ?></p>
                        <?php if ($dbFunction->taskComplete($task->ID) == 0) {
                            if (isset($_POST['complete-' . $task->Name])) {
                                $dbFunction->updateBooleanState("Tasks", "Complete", $task->ID, 1);
                                echo "<meta http-equiv='refresh' content='0'>";
                            }
                            echo "<form method=\"post\"><input type=\"submit\" value=\"complete\" name=\"complete-$task->Name\"></form>";
                        } else {
                            echo "<p id=\"count-down-$task->ID\"></p>";
                        }
                        ?>
                    </card-body>
                </div>
                <script src="index.js"> </script>
                <script>
                    setCountDown('<?php echo $task->LimitDate ?>', '<?php echo $task->ID ?>');
                </script>
            <?php } ?>
        </task-list>
    </content>
</body>

<script src="index.js"></script>
<script>
    var countDownDate = new Date('<?php echo $countDownDate ?>');
    setCountDown('<?php echo $countDownDate ?>', 'createTask');
    const buttonTask = document.getElementById("show-form");
    let newTaskClicked = false;
    buttonTask.addEventListener("click", newTaskShow);

    function newTaskShow() {
        if (!newTaskClicked) {
            newTaskClicked = true;
            document.getElementById("new-task-form").style.display = "block";
        } else {
            newTaskClicked = false;
            document.getElementById("new-task-form").style.display = "none";
        }
    }
</script>