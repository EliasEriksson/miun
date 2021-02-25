<?php
include_once "../../utils/config.php";
include_once "../../utils/functions.php";
include_once "../../utils/classes/editNewsForm.php";

/**
 * if user is not an admin redirect to login
 */
if (!isset($_SESSION["admin"])) {
    header("location: ../login/");
}

$newsID = getNewsID();
$editNewsForm = EditNewsForm::fromId($newsID, "article");
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($news = $editNewsForm->validate()) {
        header("location: ../../nyheter/nyhet/?$newsID");
    }
}?>
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
    echo $editNewsForm->toHTML();
    ?>
</main>
<?php
include "../../templates/footer.php";
?>
</body>
</html>