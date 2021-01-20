<?php
$rootURL = "../";
?>
<!doctype html>
<html lang="en">
<head>
    <?php
    include "../templates/head.php";
    ?>
    <title>Moment 1 - Villkor</title>
</head>
<body>
<?php
include "../templates/header.php";
?>

<main>
    <?php
    $now = date("Y-m-s:H.i");
    echo "$now<br><br>";

    $now = date("H");
    if ( 6 <= $now &&  $now <= 9) {
        echo "Det är morgon.";
    } elseif (9 < $now && $now < 12) {
        echo "Det är förmiddag.";
    } elseif (12 <= $now && $now <= 18) {
        echo "Det är Eftermiddag.";
    } else  {
        echo "Det är kväll/natt.";
    }
    echo "<br><br>";

    echo "Idag är det ";
    switch (date("N")){
        case 1:
            echo "måndag.";
            break;
        case 2:
            echo "tisdag";
            break;
        case 3:
            echo "onsdag.";
            break;
        case 4:
            echo "torsdag.";
        case 5:
            echo "fredag.";
            break;
        case 6:
            echo "lördag.";
            break;
        case 7:
            echo "söndag.";
            break;
    }
    echo "<br><br>";

    for ($i = 10; $i >= 0; $i--) {
        echo "$i<br>";
    }
    echo "<br>";

    // startar på 0 då 0 är exkludera ur intervall.
    // slutar på 100 då 100 är inkluderad i intervall.
    $iterations = 1;
    while (rand(0, 100) != 99) {
        $iterations++;
    }
    echo "Det tog $iterations upprepningar för att slumpa fram talet 99<br><br>";

    $courses = [
        "Webbutveckling I",
        "Introduktion till programmering med JavaScript",
        "Digital bildbehandling för webb",
        "Typografi och form för webb",
        "Webbutveckling II",
        "Databaser",
        "Webbanvändbarhet",
        "Webbdesign för CMS"
    ];

    foreach ($courses as $course) {
        echo "$course<br>";
    }

    echo "<br>";

    sort($courses);
    foreach ($courses as $course) {
        echo "$course<br>";
    }

    ?>
</main>

<?php
include "../templates/footer.php";
?>
</body>
</html>