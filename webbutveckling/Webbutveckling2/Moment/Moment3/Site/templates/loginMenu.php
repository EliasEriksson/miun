<?php
include_once __DIR__ . "/../utils/config.php";

?>
<nav class="login-menu">
    <?php
    if (isset($_SESSION["admin"])) { ?>
        <a class="button" href="<?=$rootURL?>/admin/">Admin</a>
        <a class="button" href='<?= $rootURL ?>/admin/logout/'>Logout</a>
    <?php } else { ?>
        <a class="button" href="<?= $rootURL ?>/admin/login/">Login</a>
        <a class="button" href="<?= $rootURL ?>/admin/register/">Register</a>
    <?php } ?>
</nav>