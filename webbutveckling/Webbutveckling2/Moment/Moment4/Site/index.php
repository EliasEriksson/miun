<?php
include_once "utils/config.php";
?>

<!doctype html>
<html lang="en">
<head>
    <?php
    include "templates/head.php";
    ?>
    <title>Cluck | Home</title>
</head>
<body>
<?php
include "templates/header.php";
?>
<div class="main-wrapper">
    <main class="main-content home">
        <h1>Kackel</h1>
        <div class="clucks-and-cluckers">
            <section id="cluck-section" class="cluck-section scroll-widget">
                <div id="cluck-section-menu" class="section-menu">
                    <h2>Senaste Kacklet</h2>
                    <a id="cluck-button" class="button hide">Kacklare</a>
                </div>

                <div id="clucks" class="clucks"></div>
            </section>
            <section id="clucker-section" class="cluckers-section scroll-widget">
                <div class="section-menu">
                    <h2>Nyaste Kacklarna</h2>
                    <a id="clucker-button" class="button hide">Kackel</a>
                </div>
                <div id="cluckers" class="cluckers"></div>
            </section>
        </div>
    </main>
</div>
<?php
include "templates/footer.php";
?>
<script src="<?= $rootURL ?>/static/js/utils.js"></script>
<script src="<?= $rootURL ?>/static/js/timestamp.js"></script>
<script src="<?= $rootURL ?>/static/js/clucks.js"></script>
<script src="<?= $rootURL ?>/static/js/cluckers.js"></script>
<script src="<?= $rootURL ?>/static/js/latestCluckers.js" data-root="<?= $rootURL ?>"
        data-writeLink="<?= $writeDirectoryLink ?>"></script>
<script src="<?= $rootURL ?>/static/js/latestClucks.js" data-root="<?= $rootURL ?>"
        data-writeLink="<?= $writeDirectoryLink ?>"></script>
<script src="<?=$rootURL?>/static/js/swap.js"></script>
</body>
</html>