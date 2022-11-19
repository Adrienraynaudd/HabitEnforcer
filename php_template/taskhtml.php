<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <title>test tasks</title>
    <link href="../style.css" rel="stylesheet">
</head>
<html>

<task-form>
    <form id="new_tasks" class="register-form" action='../function/tasks.php' method="post">
        <input type="text" placeholder="task_name" name="task_name" id="task_name">
        <input type="text" name="task_description" placeholder="task_description" id="task_description">
        <categories>
            <select name="task_category">
                <!-- mettre les options en automatisé avec les catégories de l'auteur -->
                <option value="">select a task category</option>
                <?php
                $dbFunc = new DBHandler;
                $categoriesName = $dbFunc->getNameByAuthor("TasksCategories", $_SESSION["userID"]);
                $options = "";
                foreach ($categoriesName as $categoryName) {
                    $options .= "<option value=" . $categoryName["name"] . ">" . $categoryName["name"] . "</option>";
                }
                echo $options;
                ?>
            </select>
            <input type="button" id="new-cat" value="create new cat">
            <category-creator id="cat-creator">
                <input type="color" name="category-color">
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