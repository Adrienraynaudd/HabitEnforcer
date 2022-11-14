<?php
require "dbSetting.php";
$dbFunction = new DBHandler;
$tasks = json_decode($dbFunction->getAllDataFromTable("Tasks", $dbFunction->getIDwithName("users", "test")), false);
$categories = json_decode($dbFunction->getAllDataFromTable("TasksCategories", $dbFunction->getIDwithName("users", "test")), false);
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
    <link href="./test.css" rel="stylesheet">
</head>

<body>
    <task-menu>
        <task-filters>
            <input type="text">
            <input type="button" id="show-form" value="New Task">
            <new-task-form id="new-task-form" style="display: none">
                <?php include "taskhtml.php" ?>
            </new-task-form>
        </task-filters>
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
                    <p><?php echo str_replace("_", " ", $task->Name) ?></p>
                </card-header>
                <card-body>
                    <p><?php echo $task->Description ?></p>
                    <?php if ($dbFunction->taskComplete($task->TaskID) == 0) {
                        if (isset($_POST['complete-' . $task->Name])) {
                            $dbFunction->updateTaskCompleState($task->TaskID, "complete");
                        }
                        echo "<form method=\"post\"><input type=\"submit\" value=\"complete\" name=\"complete-$task->Name\"></form>";
                    } else {
                        echo "<p id=\"count-down-$task->Name\"></p>";;
                    }
                    ?>
                </card-body>
            </div>
            <script>
                var countDownDate<?php echo $task->Name ?> = new Date('<?php echo $task->CreationDate ?>');
                countDownDate<?php echo $task->Name ?>.setDate(countDownDate<?php echo $task->Name ?>.getDate() + 1);
                var x = setInterval(function() {
                    var now = new Date().getTime();
                    var distance = countDownDate<?php echo $task->Name ?> - now;
                    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    document.getElementById("count-down-<?php echo $task->Name ?>").innerHTML = days + "d " + hours + "h " +
                        minutes + "m " + seconds + "s ";
                    if (distance < 0) {
                        clearInterval(x);
                        document.getElementById("count-down-<?php echo $task->Name ?>").innerHTML = "EXPIRED";
                    }
                }, 1000);
            </script>
        <?php } ?>
    </task-list>
</body>

<script>
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