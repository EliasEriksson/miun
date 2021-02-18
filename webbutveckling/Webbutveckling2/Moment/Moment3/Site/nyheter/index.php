<?php
include_once "../utils/config.php";
include_once "../utils/functions.php";
include_once "../utils/classes/newsList.php";
include_once "../utils/classes/manager.php";

$currentPage = getCurrentPage();
$newsList = new NewsList(2, $currentPage);

if (isset($_SESSION["admin"])) {
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete"]) && isset($_POST["id"])) {
        $manager = new Manager();
        $manager->removeNews($_POST["id"]);
        header("location: ./");
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <?php
    include "../templates/head.php";
    ?>
    <title>Nyheter</title>
</head>
<body>
<?php
include "../templates/header.php";
?>
<main class="site-container">
    <?php
    include "../templates/sideMenu.php";
    ?>
    <section class="news-articles">
        <h1>Nyheter</h1>
        <?php
        echo $newsList->toHTML();
        ?>
    </section>
    <?php
    echo $newsList->pageNavigationHTML();
    ?>
</main>
<script src="<?=$rootURL?>/static/js/main.js"></script>
</body>
</html>