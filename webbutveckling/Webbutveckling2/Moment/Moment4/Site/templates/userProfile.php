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
        <section class="cluck-reply-section">
            <h2 class="replies-heading">Mitt kackel</h2>
            <div id="clucks" class="cluck-replies"></div>
        </section>
    </main>
</div>

<?php
include __DIR__ . "/../templates/footer.php";
?>
<script src="<?= $rootURL ?>/static/js/timestamp.js"></script>
<script src="<?= $rootURL ?>/static/js/clucks.js"
        root="<?= $rootURL ?>"
        writeLink="<?= $writeDirectoryLink ?>"></script>
<script src="<?= $rootURL ?>/static/js/userClucks.js" userID="<?=$user->getId()?>"></script>
</body>
</html>