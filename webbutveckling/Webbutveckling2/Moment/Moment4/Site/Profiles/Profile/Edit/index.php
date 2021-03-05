<?php
include_once "../../../utils/config.php";
include_once "../../../utils/functions.php";
include_once "../../../utils/classes/userProfileEditForm.php";

$user = getSessionUser();
$userProfile = getSessionUserProfile();

$userProfileEditForm = new UserProfileEditForm($userProfile);
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($userProfile = $userProfileEditForm->validate()) {
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

<?php
echo $userProfileEditForm->toHTML();
?>

<?php
include "../../../templates/footer.php";
?>
</body>
</html>