<?php
//include "utils/config.php";
include "utils/ToDoList.php";
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

<?php
$toDoList = new ToDoList("todos.json");
$toDoList->removeToDo(2);
?>

<main>
    <div class="container">
        <h2>Min att göra lista</h2>
        <h3>Lägg till en ny sak att göra</h3>
        <form class="new-todo-form"
              method="post"
              enctype="application/x-www-form-urlencoded">
            <label class="todo-title-label">
                Titel: <input class="todo-title-input todo-input" type="text">
            </label>
            <label class="todo-description-label">
                Att göra: <textarea rows="5" class="todo-description-input todo-input"></textarea>
            </label>
            <label class="todo-time-label">
                Deadline:
                <span class="time-input">
                <!--            year -->
                <input value="2021" class="year-input todo-input" type="number" pattern="\d{1,2}" placeholder="yyyy">
                    <!--            month-->
                <input value="02" class="month-input todo-input" type="number" pattern="\d{1,2}" placeholder="mm"
                       max="12">
                    <!--            day-->
                <input value="05" class="day-input todo-input" type="number" pattern="\d{1,2}" placeholder="dd"
                       max="31">
                    <!--            hour-->
                <input class="hour-input todo-input" type="number" pattern="\d{1,2}" placeholder="mm" max="60">
                    <!--            minute-->
                <input class="minute-input todo-input" type="text" pattern="\d{1,2}" placeholder="hh" max="24">
            </span>
            </label>
            <input class="todo-submit" type="submit" value="Lägg Till">
        </form>
        <div class="spacer"></div>
        <form method="post" class="todos" enctype="application/x-www-form-urlencoded">
            <div class="todo">
                <div class="todo-title-checkbox">
                    <label><input name="0" class="title-checkbox" type="checkbox"></label><h4>Title</h4>
                </div>
                <p>description</p>
                <time>time</time>
            </div>
            <div class="todo">
                <div class="todo-title-checkbox">
                    <label><input name="1" class="title-checkbox" type="checkbox"></label><h4>Another Title</h4>
                </div>
                <p>more description</p>
                <time>other Time</time>
            </div>

            <input type="submit" value="Remove selected">
        </form>
    </div>
</main>

<?php
include "templates/footer.php";
var_dump($_POST);
?>
</body>
</html>