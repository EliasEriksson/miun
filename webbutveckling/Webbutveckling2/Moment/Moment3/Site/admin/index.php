<?php
include_once "../utils/config.php";
if (!isset($_SESSION["admin"])) {
    header("location: login/");
}
?>
<!doctype html>
<html lang="en">
<head>
    <?php
    include "../templates/head.php";
    ?>
    <title>Document</title>
</head>
<body>
<?php
include "../templates/header.php";
?>
<main class="site-container">
    <?php
    include "../templates/sideMenu.php";
    ?>
</main>
</body>
</html>