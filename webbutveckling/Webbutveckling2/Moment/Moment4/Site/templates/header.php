<?php
include_once __DIR__ . "/../utils/config.php";
include_once __DIR__ . "/../utils/functions.php";
include_once __DIR__ . "/../utils/classes/cluckForm.php";


$urlParts = explode("/", parentDirectory($_SERVER["SCRIPT_NAME"]));
$urlEnd = $urlParts[count($urlParts) - 1];
$cluckForm = new CluckForm();
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($cluck = $cluckForm->validate()) {
        echo "And even here. ";
        redirect(".");
    }
} ?>
<div class="header-wrapper">
    <header class="header">
        <nav class="main-navigation">
            <a class="button" href="<?= $rootURL ?>">Hett</a>
            <a class="button" href="<?= $rootURL ?>/Top/">Top</a>
            <a class="button" href="<?= $rootURL ?>/Latest/">Latest</a>
        </nav>
        <nav class="account-navigation">
            <?php if(userLoggedIn()){ if ($urlEnd === "Profile") {// end of script name - filename = Edit?>
                <a class="button" href="<?= $rootURL ?>/Profiles/Profile/Edit/">Redigera profil</a>
            <?php } else { ?>
                <a class="button" href="<?= $rootURL ?>/Profiles/Profile/">Min Profil</a>
            <?php }} ?>
            <?php if (isset($_SESSION["user"])) { ?>
                <a class="button" href="<?= $rootURL ?>/Logout/">Logout</a>
            <?php } else { ?>
                <a class="button" href="<?= $rootURL ?>/Login/">Login</a>
                <a class="button" href="<?= $rootURL ?>/Register/">Register</a>
            <?php } ?>
        </nav>
        <?php if (userProfileLoggedIn()) { ?>
            <?= $cluckForm->toHTML() ?>
        <?php } ?>
    </header>
</div>
