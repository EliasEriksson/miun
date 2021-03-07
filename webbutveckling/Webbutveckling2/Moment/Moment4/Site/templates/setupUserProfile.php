<?php
include_once __DIR__ . "/../utils/config.php";
include_once __DIR__ . "/../utils/functions.php";
include_once __DIR__ . "/../utils/classes/userProfileSetupForm.php";

$setupUserProfileForm = new UserProfileSetupForm();
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($userProfile = $setupUserProfileForm->validate()) {
        redirect("$rootURL/Profiles/Profile/?".getCurrentPage());
    }
}?>

<!doctype html>
<html lang="en">
<head>
    <?php
    include __DIR__ . "/../templates/head.php";
    ?>
    <title>Cluck | User setup</title>
</head>
<body>
<?php
include __DIR__ . "/../templates/header.php";
?>
<div class="main-wrapper">
    <main class="main-content">
        <div class="setup-profile-form-wrapper">
            <?= $setupUserProfileForm->toHTML()?>
        </div>
    </main>
</div>

<?php
include __DIR__ . "/../templates/footer.php";
?>
</body>
</html>