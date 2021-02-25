<?php
include_once "../../utils/config.php";
include_once "../../utils/classes/manager.php";
include_once "../../utils/classes/adminLoginForm.php";

/**
 * if proper data is posted and authentication of the user is successful the session is set
 */
$adminLoginForm = new AdminLoginForm();
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($admin = $adminLoginForm->validate()) {
        $_SESSION["admin"] = $admin;
        header("location: ../");
    }
}?>
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
        <?php
        echo $adminLoginForm->toHTML();
        ?>
    </div>
</main>
<?php
include "../../templates/footer.php";
?>
</body>
</html>