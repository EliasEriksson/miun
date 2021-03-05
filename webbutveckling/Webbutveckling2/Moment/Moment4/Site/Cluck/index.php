<?php
include_once "../utils/config.php";
include_once "../utils/functions.php";
include_once "../utils/classes/manager.php";

$cluckURL = getCurrentPage($rootURL);
$manager = new Manager();
$cluck = $manager->getCluckFromURL($cluckURL);

?>

<!doctype html>
<html lang="en">
<head>
    <?php
    include "../templates/head.php";
    ?>
    <title>Document</title>
</head>
<body>
<?php
include "../templates/header.php";
?>

<div class="main-wrapper">
    <main class="main-content">
        <div>

        </div>
    </main>
</div>



<?php
include "../templates/footer.php";
?>
</body>
</html>