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
</head>
<body>

<form>
    <p>hello</p>
</form>
</body>
</html>