<?php
include_once __DIR__ . "/../utils/config.php";
include_once __DIR__ . "/../utils/functions.php";
include_once __DIR__ . "/../utils/classes/cluckForm.php";


$urlParts = explode("/", parentDirectory($_SERVER["SCRIPT_NAME"]));
$urlEnd = $urlParts[count($urlParts) - 1];
$cluckForm = new CluckForm();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($cluck = $cluckForm->validate()) {
        redirect(".");
    }
} ?>
<div class="header-wrapper">
    <header class="header">
        <nav class="main-navigation">
            <a class="button navigation-button" href="<?= $rootURL ?>/">Senaste</a>
            <a class="button navigation-button" href="<?= $rootURL ?>/Topp/">Topp</a>
            <a class="button navigation-button" href="<?= $rootURL ?>/Hett/">Hett</a>
            <a class="button navigation-button" href="<?=$rootURL?>/Kacklare/">Kacklare</a>
        </nav>
        <nav class="account-navigation">
            <?php if (userLoggedIn()) {
                if ($urlEnd === "Profile") {// end of script name - filename = Edit?>
                    <a class="button navigation-button" href="<?= $rootURL ?>/Profiles/Profile/Edit/">Redigera profil</a>
                <?php } else { ?>
                    <a class="button navigation-button" href="<?= $rootURL ?>/Profiles/Profile/">Min Profil</a>
                <?php }
            } ?>
            <?php if (isset($_SESSION["user"])) { ?>
                <a class="button navigation-button" href="<?= $rootURL ?>/Logout/">Logga ut</a>
            <?php } else { ?>
                <a class="button navigation-button" href="<?= $rootURL ?>/Login/">Logga in</a>
                <a class="button navigation-button" href="<?= $rootURL ?>/Register/">Registrera dig</a>
            <?php } ?>
        </nav>
        <?php if (userProfileLoggedIn() && !($urlEnd === "Edit")) { ?>
            <?= $cluckForm->toHTML() ?>
        <?php } ?>
    </header>
</div>
