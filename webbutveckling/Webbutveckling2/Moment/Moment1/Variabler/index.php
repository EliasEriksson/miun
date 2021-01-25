<?php
$myName = "Elias Eriksson";
$myEmail = "mail@eliaseriksson.eu";
$myAge = 25;
?>

<!doctype html>
<html lang="en">
<head>
    <?php
    include "../templates/head.php";
    ?>
    <title>Moment 1 - Variabler</title>
</head>
<body>
<?php
include "../templates/header.php";
?>

<main>
    <?php
    echo "<b>Hej Jag heter $myName, är $myAge år gammal och nås på följande e-post: ".
         "<a href='mailto:$myEmail'>mail@eliaseriksson.eu</a></b>";
    ?>
</main>

<?php
include "../templates/footer.php";
?>
</body>
</html>