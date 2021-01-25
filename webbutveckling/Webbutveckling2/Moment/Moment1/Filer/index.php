<?php
include "../utils/functions.php";
?>

<!doctype html>
<html lang="en">
<head>
    <?php
    include "../templates/head.php";
    ?>
    <title>Moment 1 - Läsa in extern textfil</title>
</head>
<body>
<?php
$rootURL = "../";
include "../templates/header.php";
?>

<main>
    <?php
    $filename = "courses.txt";
    if (file_exists($filename)) {
        $courses = explode("\n", readFileContent($filename));
        echo "<ul>";
            foreach ($courses as $course) {
                if ($course) {
                    echo "<li>$course</li>";
                }
            }
        echo "</ul>";
    } else {
        echo "Filen kunde inte hittas";
    }
    ?>
</main>

<?php
include "../templates/footer.php";
?>
</body>
</html>