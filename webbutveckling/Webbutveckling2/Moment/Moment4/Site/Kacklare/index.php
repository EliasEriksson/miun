<?php
include_once "../utils/config.php";
?>

<!doctype html>
<html lang="en">
<head>
    <?php
    include "../templates/head.php";
    ?>
    <title>Kacklare</title>
</head>
<body>

<?php
include "../templates/header.php";
include "../templates/cluckers.php";
include "../templates/footer.php";
?>

<script src="<?= $rootURL ?>/static/js/utils.js"></script>
<script src="<?= $rootURL ?>/static/js/timestamp.js"></script>
<script src="<?= $rootURL ?>/static/js/cluckers.js"
        root="<?= $rootURL ?>"
        writeLink="<?= $writeDirectoryLink ?>"></script>
<script src="<?= $rootURL ?>/static/js/latestCluckers.js"></script>
</body>
</html>