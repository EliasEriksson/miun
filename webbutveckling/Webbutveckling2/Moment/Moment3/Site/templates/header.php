<?php
include_once __DIR__ . "/../utils/config.php"
?>

<header>
    <div class="site-container header">
        <img src="<?= $rootURL ?>/media/logo.png" alt="newspaper">
        <div class="login-menu-container">
            <?php
            include __DIR__."/loginMenu.php";
            ?>
        </div>
    </div>

</header>