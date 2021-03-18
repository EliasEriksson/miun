<?php
include_once "../../utils/config.php";
include_once "../../utils/functions.php";
include_once "../../utils/classes/cluckEditForm.php";
include_once "../../utils/classes/manager.php";

$currentUserProfile = getSessionUserProfile(); // forces the user to be logged in and have a profile

$cluckURL = getCurrentPage($rootURL);
$manager = new Manager();

if (!($cluck = $manager->getCluckFromURL($cluckURL))) {
    // no cluck with this URL exists, redirect to home
    redirect($rootURL);
}

$cluckPostUserProfile = $cluck->getUserProfile($manager);

if (!($currentUserProfile->getUserID() === $cluckPostUserProfile->getUserID())) {
    // the user who requested the edit is not the owner of the post
    // redirect to home
    redirect($rootURL);
}

$cluckEditForm = new CluckEditForm($cluck);

if ($_SERVER["REQUEST_METHOD"]) {
    if ($cluck = $cluckEditForm->validate($manager)) {
        // successful edit of post, redirect to the post that was edited
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