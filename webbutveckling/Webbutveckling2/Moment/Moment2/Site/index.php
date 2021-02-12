<?php
include "utils/config.php";
include "utils/ToDoList.php";
include "utils/functions.php";
$toDoList = new ToDoList("todolist.json");
$now = new DateTime();
$errorMessage = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // process POST data if its a POST request
    // if post data is processed successfully redirect user to same page to clear POST data
    if (isset($_POST["add-todo"])) {
        if (isset($_POST["description"]) && strlen($_POST["description"]) > 4) {
            if (isset($_POST["title"]) && !$_POST["title"] == "") {
                $time = getDateFromPost();
                if ($time) {
                    $toDoList->addToDo(
                        $_POST["title"], $_POST["description"], $time->getTimestamp()
                    );
                header("location: ./");
                } else {
                    $errorMessage = "Tiden måste vara angiven på formatet YYYY | MM | DD | hh | mm";
                }
            } else {
                $errorMessage = "Att göra måste ha en titel";
            }
        } else {
            $errorMessage = "Att göra måste vara minst fem tecken!";
        }
    } elseif (isset($_POST["remove-selected"])){
        foreach (array_reverse($_POST, true) as $key => $_) {
            if (is_numeric($key)) {
                $toDoList->removeToDo($key);
            }
        }
        header("location: ./");
    } elseif (isset($_POST["remove-all"])) {
        $toDoList->removeAll();
        header("location: ./");
    }

} ?>

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
                Titel: <input name="title"
                              placeholder="Handla"
                              class="todo-title-input todo-input"
                              type="text">
            </label>
            <label for="description" class="todo-description-label">
                Att göra: <textarea id="description"
                                    placeholder="Mjöl & kakao"
                                    name="description"
                                    rows="1"
                                    class="todo-description-input todo-input"></textarea>
            </label>
                <div class="time-input">
                    <label><input name="year"
                           id="year"
                           value="<?= $now->format('Y') ?>"
                           class="year-input todo-input"
                           type="text"
                                  placeholder="Y"></label>
                    <span class="date-separator">-</span>
                    <label><input name="month"
                           id="month"
                           value="<?= $now->format('m') ?>"
                           class="month-input todo-input"
                           type="text"
                           placeholder="M"></label>
                    <span class="date-separator">-</span>
                    <label><input name="day"
                           id="day"
                           value="<?= $now->format('d') ?>"
                           class="day-input todo-input"
                           type="text"
                           placeholder="D"></label>
                    <span></span>
                    <label><input name="hour"
                           id="hour"
                           class="hour-input todo-input"
                           type="text"
                           placeholder="h"></label>
                    <span class="date-separator">:</span>
                    <label><input name="minute"
                           id="minute"
                           class="minute-input todo-input"
                           type="text"
                           placeholder="m"></label>
            </div>
            <input name="add-todo" class="todo-submit" type="submit" value="Lägg Till">
        </form>
        <p class="error-message"><?= $errorMessage ?></p>
        <a href="<?=$writeDirectoryLink?>/writeable/todolist.json">json data fil</a>
        <?php
        echo $toDoList->toHTML();
        ?>
    </div>
</main>

<?php
include "templates/footer.php";
?>
<script src="<?= $rootURL ?>/static/js/main.js"></script>
</body>
</html>