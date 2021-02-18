<?php
include_once "../../utils/classes/manager.php";


$message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["register"])) {
    if (isset($_POST["username"])) {
        if (isset($_POST["password0"])) {
            if (isset($_POST["password1"])) {
                if ($_POST["password0"] === $_POST["password1"]) {
                    $manager = new Manager();
                    $admin = $manager->addAdmin($_POST["username"], $_POST["password0"]);
                    if ($admin) {
                        $_SESSION["admin"] = $admin;
                        header("location: ../");
                    } else {
                        $message = "En användare med användarnamnet '" . $_POST["username"] . "' finns redan";
                    }
                } else {
                    $message = "Lösenorden matchar inte";
                }
            } else {
                $message = "Du måste upprepa lösenordet";
            }
        } else {
            $message = "Du måste ange ett lösenord";
        }
    } else {
        $message = "Du måste ange ett användarnamn";
    }
}

?>

<!doctype html>
<html lang="en">
<head>
    <?php
    include "../../templates/head.php";
    ?>
    <title>Registrering</title>
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
        <form class="form" method="post" enctype="application/x-www-form-urlencoded">
            <label>
                <span class="label-text">Användarnamn:</span><input name="username" type="text">
            </label>
            <label>
                <span class="label-text">Lösenord:</span><input name="password0" type="password">
            </label>
            <label>
                <span class="label-text">Upprepa Lösenord:</span><input name="password1" type="password">
            </label>
            <input name="register" type="submit" value="Registrera Dig">
        </form>
    </div>
    <p class="error-message"><?=$message?></p>
</main>
</body>
</html>