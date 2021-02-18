<?php
include_once __DIR__."/../../utils/config.php";
include_once __DIR__."/../../utils/classes/manager.php";
include_once __DIR__."/../../utils/functions.php";

$newsID = getNewsID();
$manager = new Manager();
$news = $manager->getNews($newsID);
?>

<!doctype html>
<html lang="en">
<head>
    <?php
    include "../../templates/head.php";
    ?>
    <title><?=$news->getTitle()?></title>
</head>
<body>
<?php
include "../../templates/header.php";
?>
<main class="site-container">
    <?php
    include "../../templates/sideMenu.php";
    ?>
    <div>
        <?php
        echo $news->toLongHTML();
        ?>
    </div>
</main>

</body>
</html>