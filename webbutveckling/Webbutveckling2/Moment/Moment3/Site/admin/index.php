<?php
include_once "../utils/config.php";
include_once "../utils/classes/manager.php";

if (!isset($_SESSION["admin"])) {
    header("location: login/");
}

$message = "";
if (isset($_SESSION["admin"])) {
    if ($_SERVER["REQUEST_METHOD"] === "POST" && $_POST["add"]) {
        if (isset($_POST["title"]) && strlen($_POST["title"]) > 0) {
            if (isset($_POST["preamble"]) && strlen($_POST["preamble"]) > 0) {
                if (isset($_POST["article"]) && strlen($_POST["article"]) > 0) {
                    $news = $manager->addNews($_POST["title"], $_POST["preamble"], $_POST["article"]);
                    $id = $news->getId();
                    header("location: ../nyheter/nyhet/?$id");
                } else {
                    $message = "En artikel måste ha ett innehåll.";
                }
            } else {
                $message = "En artikel måste ha en ingress";
            }
        } else {
            $message = "En artikel måste ha en titel.";
        }
    }
}

?>
<!doctype html>
<html lang="en">
<head>
    <?php
    include "../templates/head.php";
    ?>
    <title>Admin</title>
</head>
<body>
<?php
include "../templates/header.php";
?>
<main class="site-container">
    <?php
    include "../templates/sideMenu.php";
    ?>
    <form class="new-article" method="post">
        <span class="error-message"><?=$message?></span>
        <label>
            <span class="label-text">Titel</span>
            <input name="title" class="input" type="text">
        </label>
        <label>
            <span class="label-text">Ingress</span>
            <textarea name="preamble" class="input textarea"></textarea>
        </label>
        <label>
            <span class="label-text">Artikel</span>
            <textarea name="article" class="input textarea"></textarea>
        </label>
        <div class="submit-wrapper">
            <input  class="button" name="add" type="submit" value="Skapa artikel">
        </div>
    </form>
</main>
</body>
</html>