<?php
include_once "../../utils/config.php";
include_once "../../utils/classes/manager.php";
include_once "../../utils/functions.php";

/**
 * if user is not an admin redirect to login
 */
if (!isset($_SESSION["admin"])) {
    header("location: ../login/");
}

$newsID = getNewsID();
$manager = new Manager();

$errorMessage = "";
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])) {
    $errorMessage = validateArticleForm();
    if (!$errorMessage) {
        $manager->updateNews($newsID, $_POST["title"], $_POST["preamble"], $_POST["article"]);
        header("location: ../../nyheter/nyhet/?$newsID");
    }
}
// sets a few variables for articleForm.php
$news = $manager->getNews($newsID);
$title = $news->getTitle();
$preamble = $news->getPreamble();
$content = $news->getContent();
?>

<!doctype html>
<html lang="en">
<head>
    <?php
    include "../../templates/head.php";
    ?>
    <title>Redigera</title>
</head>
<body>
<?php
include "../../templates/header.php";
?>
<main class="site-container">
    <?php
    include "../../templates/sideMenu.php";
    include "../../templates/articleForm.php";
    ?>
</main>
<?php
include "../../templates/footer.php";
?>
</body>
</html>