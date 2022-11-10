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
            <div class="task-card" style="border-color: <?php echo $color ?>">
                <card-header>
                    <p style="color: <?php echo $diffcultyArray[$diffculty] ?>">O</p>
                    <p><?php echo $task->Name ?></p>
                </card-header>
                <card-body>
                    <p><?php echo $task->Description ?></p>
                    <p id="count-down-<?php echo $task->Name ?>"></p>
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
                console.log(countDownDate);
            </script>
        <?php } ?>
    </task-list>
</body>