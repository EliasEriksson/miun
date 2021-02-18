<?php
include_once "../utils/config.php";
include_once "../utils/classes/manager.php";
include_once "../utils/functions.php";


if (!isset($_SESSION["admin"])) {
    header("location: login/");
}

$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && $_POST["submit"]) {
    $errorMessage = validateArticleForm();
    if (!$errorMessage) {
        $manager = new Manager();
        $news = $manager->addNews($_POST["title"], $_POST["preamble"], $_POST["article"]);
        $id = $news->getId();
        header("location: ../nyheter/nyhet/?$id");
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
    include "../templates/articleForm.php";
    ?>
</main>
<?php
include "../templates/footer.php";
?>
</body>
</html>