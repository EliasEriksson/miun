<?php
include_once "utils/config.php";
include_once "utils/functions.php";
include_once "utils/classes/newsList.php";

// 0 given as page as the start page should not be paged
$newsList = new NewsList(2, 0);
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
<?php
include "templates/footer.php";
?>
<script src="<?=$rootURL?>/static/js/main.js"></script>
</body>
</html>