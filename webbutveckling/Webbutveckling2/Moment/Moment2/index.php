<?php

include "utils/ToDoList.php";
include "utils/functions.php";
$toDoList = new ToDoList("todos.json");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $time = getDateFromPost();
    if ($time && isset($_POST["title"]) && isset($_POST["description"])) {
        $toDoList->addToDo(
            $_POST["title"], $_POST["description"], $time->getTimestamp()
        );
        var_dump($time->getTimestamp());
    }

    foreach (array_reverse($_POST, true) as $index => $_) {
        $toDoList->removeToDo($index);
    }
}


?>

<!doctype html>
<html lang="en">
<head>
    <?php
    include "templates/head.php";
    ?>
    <title>Moment 2 - Home</title>
</head>
<body>
<?php
include "templates/header.php";
?>

<main>
    <div class="container">
        <h2>Min att göra lista</h2>
        <h3>Lägg till en ny sak att göra</h3>
        <form class="new-todo-form"
              method="post"
              enctype="application/x-www-form-urlencoded">
            <label class="todo-title-label">
                Titel: <input name="title" class="todo-title-input todo-input" type="text">
            </label>
            <label class="todo-description-label">
                Att göra: <textarea name="description" rows="5" class="todo-description-input todo-input"></textarea>
            </label>
            <label class="todo-time-label">
                Deadline:
                <span class="time-input">
                <!--            year -->
                <input name="year" value="2021" class="year-input todo-input" type="number" pattern="\d{1,2}"
                       placeholder="yyyy">
                    <!--            month-->
                <input name="month" value="02" class="month-input todo-input" type="number" pattern="\d{1,2}"
                       placeholder="mm"
                       max="12">
                    <!--            day-->
                <input name="day" value="05" class="day-input todo-input" type="number" pattern="\d{1,2}"
                       placeholder="dd"
                       max="31">
                    <!--            hour-->
                <input name="hour" class="hour-input todo-input" type="number" pattern="\d{1,2}" placeholder="hh"
                       max="60">
                    <!--            minute-->
                <input name="minute" class="minute-input todo-input" type="text" pattern="\d{1,2}" placeholder="mm"
                       max="24">
            </span>
            </label>
            <input class="todo-submit" type="submit" value="Lägg Till">
        </form>
        <div class="spacer"></div>
        <?php

        $toDoList = new ToDoList("todos.json");
        echo $toDoList->toHTML();
        ?>
    </div>
</main>

<?php
include "templates/footer.php";
var_dump($_POST);
?>
</body>
</html>