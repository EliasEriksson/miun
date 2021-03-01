<?php
include_once "../../utils/classes/adminRegisterForm.php";
/**
 * creates a new admin user if the proper post data is present
 */
$adminRegisterForm = new AdminRegisterForm();
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($admin = $adminRegisterForm->validate()) {
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
        <?php
        echo $adminRegisterForm->toHTML();
        ?>
    </div>
</main>
<?php
include "../../templates/footer.php";
?>
</body>
</html>