<?php
include_once __DIR__."/../../utils/config.php";
include_once __DIR__."/../../utils/classes/manager.php";
include_once __DIR__."/../../utils/functions.php";

$newsID = getNewsID();
$manager = new Manager();

/**
 * if an admin post requested with proper data a post will be deleted
 */
if (isset($_SESSION["admin"])) {
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete"]) && isset($_POST["id"])) {
        $manager->removeNews($_POST["id"]);
        header("location: ../");
    }
}
$news = $manager->getNews($newsID);
?>

<!doctype html>
<html lang="en">
<head>
    <?php
    include "../../templates/head.php";
    ?>
    <title><?=$news->getTitle()?></title>
</head>
<body>
<?php
include "../../templates/header.php";
?>
<main class="site-container">
    <?php
    include "../../templates/sideMenu.php";
    ?>
    <div>
        <?php
        echo $news->toLongHTML();
        ?>
    </div>
</main>
<?php
include "../../templates/footer.php";
?>
<script src="<?=$rootURL?>/static/js/main.js"></script>
</body>
</html>