<?php
$rootURL = "../";
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

<main>
    <div class="stack-column">
        <?php
        if (!((!isset($_POST["x"]) || $_POST["x"] != "") && (!isset($_POST["y"]) || $_POST["y"] != ""))) {
            header("location: ../Formulär/");
        }
        echo "Längden " . $_POST["x"] . " och bredden " . $_POST["y"]. " ger en area på ".
            ((int)$_POST["x"]) * ((int)$_POST["y"]) . "."
        ?>
        <a class="button" href="../Formulär/">Tillbaka till formulären</a>
    </div>

</main>

<?php
include "../templates/footer.php";
?>
</body>
</html>