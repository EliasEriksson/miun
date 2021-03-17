<?php
include_once __DIR__ . "/../utils/config.php";
include_once __DIR__ . "/../utils/functions.php";
include_once __DIR__ . "/../utils/classes/userLoginForm.php";

if (userLoggedIn()) {
    // user is already logged in, redirect to the users profile
    $user = getSessionUser();
    redirect($user->getWebURL());
}

$userLoginForm = new UserLoginForm();
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($user = $userLoginForm->validate()) {
        if ($userProfile = $user->getProfile()) {
            // log in validated and user profile is setup, redirect to home
            $_SESSION["userProfile"] = $userProfile;
            redirect("$rootURL/");
        } else {
            // log in validated but users profile is not setup,
            // redirect to profile so user can setup profile
            redirect("$rootURL/Profiles/Profile/");
        }
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
            <?=$userLoginForm->toHTML()?>
        </div>
    </main>
</div>
<?php
include "../templates/footer.php";
?>
</body>
</html>