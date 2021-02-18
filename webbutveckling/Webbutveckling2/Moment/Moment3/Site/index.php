<?php
include_once "utils/config.php";
include_once "utils/functions.php";
include_once "utils/classes/newsList.php";

$currentPage = getCurrentPage();
$newsList = new NewsList(2, $currentPage);
?>

<!doctype html>
<html lang="en">
<head>
    <?php
    include "templates/head.php";
    ?>
    <title>Startsidan</title>
</head>
<body>

<?php
include "templates/header.php";
?>
<main class="site-container">
    <?php
    include "templates/sideMenu.php";
    ?>
    <section class="news-articles">
        <h1>Senaste Artiklarna</h1>
        <?php
        echo $newsList->toHTML();
        ?>
    </section>
</main>
<script src="<?=$rootURL?>/static/js/main.js"></script>
</body>
</html>