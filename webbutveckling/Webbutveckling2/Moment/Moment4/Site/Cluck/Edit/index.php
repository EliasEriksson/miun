<?php
include_once "../../utils/config.php";
include_once "../../utils/functions.php";
include_once "../../utils/classes/cluckEditForm.php";
include_once "../../utils/classes/manager.php";

$cluckURL = getCurrentPage($rootURL);
$manager = new Manager();

$currentUserProfile = getSessionUserProfile();

if (!($cluck = $manager->getCluckFromURL($cluckURL))) {
    redirect($rootURL);
}

$cluckPostUserProfile = $cluck->getUserProfile($manager);

if (!($currentUserProfile->getUserID() === $cluckPostUserProfile->getUserID())) {
    redirect($rootURL);
}

$cluckEditForm = new CluckEditForm($cluck);

if ($_SERVER["REQUEST_METHOD"]) {
    if ($cluck = $cluckEditForm->validate()) {
        redirect("../?$cluckURL");
    }
} ?>

<!doctype html>
<html lang="en">
<head>
    <?php
    include_once "../../templates/head.php";
    ?>
    <title>Redigera kackel</title>
</head>
<body>
<?php
include "../../templates/header.php";
?>
<div class="main-wrapper">
    <main class="main-content">
        <?php
        echo $cluckEditForm->toHTML();
        ?>
    </main>
</div>
<?php
include "../../templates/footer.php";
?>
</body>
</html>