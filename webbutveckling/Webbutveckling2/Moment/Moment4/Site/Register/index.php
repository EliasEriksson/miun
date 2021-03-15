<?php
include_once "../utils/config.php";
include_once "../utils/functions.php";
include_once "../utils/classes/userRegisterForm.php";

if (isset($_SESSION["user"])) {
    redirect($rootURL);
}

$userRegisterForm = new UserRegisterForm();
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($user = $userRegisterForm->validate()) {
        redirect("$rootURL/Profiles/Profile/");
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <?php
    include "../templates/head.php";
    ?>
    <title>Cluck | Login</title>
</head>
<body>
<?php
include "../templates/header.php";
?>

<div class="main-wrapper">
    <main class="main-content">
        <div class="general-form-wrapper">
            <?= $userRegisterForm->toHTML() ?>
        </div>

    </main>
</div>

<?php
include "../templates/footer.php";
?>
</body>
</html>