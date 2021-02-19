<?php
include_once __DIR__ . "/../utils/config.php"
?>

<header>
    <div class="site-container header">
        <div class="image-wrapper">
            <img src="<?= $rootURL ?>/media/logo.png" alt="newspaper">
        </div>
        <div class="login-menu-container">
            <?php
            include __DIR__."/loginMenu.php";
            ?>
        </div>
    </div>

</header>