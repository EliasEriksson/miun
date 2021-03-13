<?php
echo "Post:" . ($_POST) . "<br><br>";
echo "Files:" . var_dump($_FILES) . "<br><br>";

error_reporting(-1);
ini_set("display_errors", 1);

?>


<!doctype html>
<html lang="en">
<head>
    <title>Document</title>
    <style>
        .wrapper {
            position: relative;
        }
        article > a {
            display: block;
            position: relative;
            z-index: 2;
        }
        .abs {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 1;
        }
    </style>
</head>
<body>

<div class="wrapper">
    <article>
        <a href="heading"><h2>This is me</h2></a>
        <p>THis is not me</p>
    </article>
    <a class="abs" href="cover"></a>
</div>

</body>
</html>