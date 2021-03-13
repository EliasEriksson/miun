<?php
include_once "../utils/config.php";
?>

<!doctype html>
<html lang="en">
<head>
    <?php
    include "../templates/head.php";
    ?>
    <title>Cluck | Home</title>
</head>
<body>
<?php
include "../templates/header.php";
include "../templates/cluckLists.php";
include "../templates/footer.php";
?>
<script src="<?= $rootURL ?>/static/js/utils.js"></script>
<script src="<?= $rootURL ?>/static/js/timestamp.js"></script>
<script src="<?= $rootURL ?>/static/js/clucks.js"></script>
<script src="<?=$rootURL?>/static/js/hotClucks.js"
        data-root="<?= $rootURL ?>"
        data-writeLink="<?= $writeDirectoryLink ?>"></script>
</body>
</html>