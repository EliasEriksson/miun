<?php
include_once "../../utils/classes/manager.php";


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["login"])) {
    if (isset($_POST["username"])) {
        if (isset($_POST["password"])) {
            $manager = new Manager();
            $admin = $manager->getAdmin($_POST["username"]);
            if ($admin) {
                if ($admin->authenticate($_POST["password"])) {
                    $_SESSION["admin"] = $admin;
                    header("location: ../");
                }
            }
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <?php
    include "../../templates/head.php";
    ?>
    <title>Login</title>
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
                <span class="label-text">Lösenord:</span><input name="password" type="password">
            </label>
            <input name="login" type="submit" value="Logga In">

        </form>
    </div>
</main>
<?php
include "../../templates/footer.php";
?>
</body>
</html>