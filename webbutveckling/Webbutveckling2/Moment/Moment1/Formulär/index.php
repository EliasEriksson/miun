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
        <form class="form" method="get" action="./">
            <label>
                Förnamn: <input name="name" type="text">
            </label>
            <label>
                Efternamn: <input name="lastname" type="text">
            </label>
            <input type="submit">
        </form>
        <div class="form-reply">
            <?php
            if ((isset($_GET["name"]) && $_GET["name"] != "")
                && (isset($_GET["lastname"]) && $_GET["lastname"] != "")) {
                echo "Hej " . $_GET["name"] . " " . $_GET["lastname"] . "!";
            } else {
                echo "Både förnamn och efternamn måste fyllas i!";
            }
            ?>
        </div>
        <form class="form" method="post" action="../Calculate/">
            <label>
                x: <input type="number" name="x">
            </label>
            <label>
                y: <input type="number" name="y">
            </label>
            <input type="submit">
        </form>
    </div>

</main>

<?php
include "../templates/footer.php";
?>
</body>
</html>