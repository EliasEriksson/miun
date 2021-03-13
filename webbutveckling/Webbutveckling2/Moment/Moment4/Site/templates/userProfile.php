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
    <title>Kackel | Profil</title>
</head>
<body>
<?php
include __DIR__ . "/../templates/header.php";
?>
<div class="main-wrapper">
    <main class="main-content">
        <div class="profile">
            <div class="profile-avatar-wrapper">
                <img class="profile-avatar" src="<?= $userProfile->getWebLinkAvatar() ?>" alt="avatar">
            </div>
            <div class="clucker-heading-wrapper">
                <h1><?= $userProfile->getFirstName() ?> <?= $userProfile->getLastName() ?>'s profil</h1>
                <div class="clucker-metadata">
                    <?php if ($userTotalPosts = $manager->getUserPostCount($userProfile->getUserID())) {
                        if ($userTotalPosts === 1) { $unit = "gång"; } else { $unit = "gånger"; } ?>
                        <span class="clucker-total-posts"></span>
                    <?php } ?>
                    <?php if ($userTotalReplies = $manager->getReplyCount($userProfile->getUserID())) {
                        if ($userTotalPosts === 1) { $unit = "gång"; } else { $unit = "gånger"; } ?>
                        <span class="clucker-total-replies"></span>
                    <?php } ?>
                </div>
            </div>
            <div class="cluckers-description-wrapper">
                <p class="profile-description"><?= $userProfile->getDescription() ?></p>
            </div>


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
<script src="<?= $rootURL ?>/static/js/utils.js"></script>
<script src="<?= $rootURL ?>/static/js/timestamp.js"></script>
<script src="<?= $rootURL ?>/static/js/clucks.js"></script>
<script src="<?= $rootURL ?>/static/js/userClucks.js"
        userID="<?= $user->getId() ?>"
        data-root="<?= $rootURL ?>"
        data-writeLink="<?= $writeDirectoryLink ?>"></script>
</body>
</html>