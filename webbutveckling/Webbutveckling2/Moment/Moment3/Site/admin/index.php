<?php
include_once "../utils/classes/newsForm.php";

/**
 * if not logged in as admin redirect to login
 */
if (!isset($_SESSION["admin"])) {
    header("location: login/");
}

$newsForm = new NewsForm("article");
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($news = $newsForm->validate()) {
        $id = $news->getId();
        header("location: ../nyheter/nyhet/?$id");
    }
}?>
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
    echo $newsForm->toHTML();
    ?>
</main>
<?php
include "../templates/footer.php";
?>
</body>
</html>