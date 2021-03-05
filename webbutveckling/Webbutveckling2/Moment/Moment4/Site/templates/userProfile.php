<?php
include_once __DIR__ . "/../utils/config.php";
include_once __DIR__ . "/../utils/functions.php";
include_once __DIR__ . "/../utils/classes/manager.php";

$userURL = getCurrentPage();
$manager = new Manager();

if (!($user = $manager->getUserFromURL($userURL))) {
    redirect("$rootURL/");
}
if (!($userProfile = $user->getProfile())) {
    redirect("$rootURL/");
}
?>

<!doctype html>
<html lang="en">
<head>
    <?php
    include __DIR__ . "/../templates/head.php";
    ?>
    <title>Cluck | User profile</title>
</head>
<body>
<?php
include __DIR__ . "/../templates/header.php";
?>
<div class="main-wrapper">
    <main class="main-content">
        <div class="profile">
            <img class="profile-avatar" src="<?= $userProfile->getWebLinkAvatar() ?>" alt="avatar">
            <h1><?= $userProfile->getFirstName() ?> <?= $userProfile->getLastName() ?></h1>
            <p class="profile-description"><?= $userProfile->getDescription() ?></p>
        </div>
    </main>
</div>

<article>
        <div>
            <img>
            <div><a><h2></h2></a><time></time></div>
            <p></p>
        </div>
</article>

<?php
include __DIR__ . "/../templates/footer.php";
?>
</body>
</html>