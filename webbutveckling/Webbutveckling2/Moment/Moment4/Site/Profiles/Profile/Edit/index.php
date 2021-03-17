<?php
include_once "../../../utils/config.php";
include_once "../../../utils/functions.php";
include_once "../../../utils/classes/userProfileEditForm.php";

$user = getSessionUser();
$userProfile = getSessionUserProfile();

$userProfileEditForm = new UserProfileEditForm($userProfile);
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($userProfile = $userProfileEditForm->validate()) {
        // successful edit of a user profile, redirect to the profile
        redirect("$rootURL/Profiles/Profile/?" . $user->getUrl());
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <?php
    include "../../../templates/head.php";
    ?>
    <title>Cluck | Edit profile</title>
</head>
<body>
<?php
include "../../../templates/header.php";
?>

<div class="main-wrapper">
    <main class="main-content">
        <div class="general-form-wrapper">
            <?= $userProfileEditForm->toHTML() ?>
        </div>
    </main>
</div>

<?php
include "../../../templates/footer.php";
?>
</body>
</html>