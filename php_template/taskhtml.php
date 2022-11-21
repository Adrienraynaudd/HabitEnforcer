<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <title>test tasks</title>
    <link href="style.css" rel="stylesheet">
</head>
<html>

<task-form>
    <form id="new_tasks" class="task-form-container" action='../function/tasks.php' method="post">
        <input type="text" placeholder="Task Name" name="task_name" id="task_name" required="required">
        <input type="text" name="task_description" placeholder="Task Description" id="task_description">
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
            <input type="button" id="new-category" value="new category">
            <category-creator id="category-creator" style="display: none">
                <input type="color" name="category-color">
                <input type="text" name="category-name" placeholder="category name">
            </category-creator>
        </categories>
        <select name="task_difficulty" required="required">
            <option value="">task difficulty</option>
            <option value="easy">Easy</option>
            <option value="normal">Normal</option>
            <option value="medium">Medium</option>
            <option value="hard">Hard</option>
        </select>
        <select name="task_recurrence" required="required">
            <option value="">recurrence</option>
            <option value="daily">Dayly</option>
            <option value="weekly">Weekly</option>
        </select>
        <input type="submit" value="Create">
    </form>
</task-form>

</html>

<script>
    const buttonNewCategory = document.getElementById("new-category");
    let isClickedButtonNewCategory = false;
    buttonNewCategory.addEventListener("click", showForm);

    function showForm() {
        if (!isClickedButtonNewCategory) {
            isClickedButtonNewCategory = true;
            document.getElementById("category-creator").style.display = "block";
        } else {
            isClickedButtonNewCategory = false;
            document.getElementById("category-creator").style.display = "none";
        }
    }
</script>