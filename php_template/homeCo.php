<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <!-- importer le fichier de style -->
    <link rel="stylesheet" href="style.css" media="screen" type="text/css" />
</head>

<html>
<input type="button" value="profileEditing" onclick="window.location.href='editingProfile.php'"><br /><br />
 <input type="button" value="Logout" onclick="window.location.href='../function/logout.php'">
<home-container>
    <left-container>
        <header-left-container>
            <img src="../Avatars/<?php echo $_SESSION["avatar"] ?>" alt=" Image Perso" width="75px" height="75px" style="border-radius:30px">
            <p>Welcome <?php echo strtoupper($_SESSION['username']) ?></p>
        </header-left-container>
        <content-left-container>
            <task-display>
                <?php require_once "../function/dbSetting.php";
                $diffcultyArray = array(
                    "easy" => "green",
                    "normal" => "rgb(255, 242, 0)",
                    "medium" => "orange",
                    "hard" => "red"

                );
                $dbFunc = new DBHandler;
                $userCategories = $dbFunc->getEveryThingByParam("TasksCategories", "CreatorID", $_SESSION["userID"]);
                $userTasks = $dbFunc->getEveryThingByParam("Tasks", "CreatorID", $_SESSION["userID"]);
                if (count($userTasks)>2){
                    $someTasks = array($userTasks[random_int(0, count($userTasks) - 1)], $userTasks[random_int(0, count($userTasks) - 1)], $userTasks[random_int(0, count($userTasks)) - 1]);
                }else{
                    $someTasks= $userTasks;
                }

                foreach ($someTasks as $task) {
                    foreach ($userCategories as $category) {
                        if ($category["ID"] == $task["CategoryID"]) {
                            $borderColor = $category["Color"];
                            $backgroundColor = hex2rgba($borderColor);
                        }
                    } ?>
                    <task-card style="border-color: <?php echo $borderColor ?>; border-radius: 10px; background-color:<?php echo $backgroundColor ?>">
                        <task-card-header>
                            <p style="color: <?php echo $diffcultyArray[$task["Difficulty"]] ?>; margin-right: 5%">●</p>
                            <p><?php echo str_replace("_", " ", $task["Name"]) ?></p>
                        </task-card-header>
                        <task-card-body>
                            <p style="margin-left: 5%; width: 50%"><?php echo $task["Description"] ?></p>
                            <input style="margin-left: 5%; height: 75%;" type="button" onclick="window.location.href='tasklist.php'" value="Go to Task">
                        </task-card-body>
                    </task-card>
                <?php }
                ?>
            </task-display>
        </content-left-container>
    </left-container>
    <right-container>
        <?php include "grouphtml.php" ?>
    </right-container>
</home-container>
</html>

<?php
// Thanks BOJAN PETROVIC for the func
function hex2rgba($color)
{
    $default = 'rgb(0,0,0)';
    //Return default if no color provided
    if (empty($color))
        return $default;
    //Sanitize $color if "#" is provided 
    if ($color[0] == '#') {
        $color = substr($color, 1);
    }
    //Check if color has 6 or 3 characters and get values
    if (strlen($color) == 6) {
        $hex = array($color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]);
    } elseif (strlen($color) == 3) {
        $hex = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]);
    } else {
        return $default;
    }
    //Convert hexadec to rgb
    $rgb =  array_map('hexdec', $hex);
    //Check if opacity is set(rgba or rgb)
    $opacity = 0.5;
    $output = 'rgba(' . implode(",", $rgb) . ',' . $opacity . ')';
    return $output;
}
?>