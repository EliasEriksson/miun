<?php
include_once __DIR__ . "/../utils/config.php";
include_once __DIR__ . "/../utils/functions.php";
include_once __DIR__ . "/../utils/classes/manager.php";

$userURL = getCurrentPage();
$manager = new Manager();

if (!($profileUser = $manager->getUserFromURL($userURL))) {
    // there is no user with this URL so redirect to home
    redirect("$rootURL/");
}

if (!($profileUserProfile = $profileUser->getProfile())) {
    // the user that owns this URL have not yet finished their profile so redirect to home
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
                <img class="profile-avatar" src="<?= $profileUserProfile->getWebLinkAvatar() ?>" alt="avatar">
            </div>
            <div class="clucker-heading-wrapper">
                <h1><?= $profileUserProfile->getFirstName() ?> <?= $profileUserProfile->getLastName() ?>'s profil</h1>
                <div class="clucker-metadata">
                    <?php if ($userTotalPosts = $manager->getUserPostCount($profileUserProfile->getUserID())) {
                        if ($userTotalPosts === 1) {
                            $unit = "gång";
                        } else {
                            $unit = "gånger";
                        } ?>
                        <span class="clucker-total-posts"></span>
                    <?php } ?>
                    <?php if ($userTotalReplies = $manager->getReplyCount($profileUserProfile->getUserID())) {
                        if ($userTotalPosts === 1) {
                            $unit = "gång";
                        } else {
                            $unit = "gånger";
                        } ?>
                        <span class="clucker-total-replies"></span>
                    <?php } ?>
                </div>
            </div>
            <div class="cluckers-description-wrapper">
                <p class="profile-description"><?= $profileUserProfile->getDescription() ?></p>
            </div>
        </div>
        <section class="cluck-reply-section">
            <?php if (userProfileLoggedIn() && ($ownUserProfile = getSessionUserProfile()) && $ownUserProfile->getUserID() == $profileUserProfile->getUserID()) {
                ?>
                <h2 class="replies-heading">Mitt kackel</h2>
            <?php } else { ?>
                <h2><?= $profileUserProfile->getFirstName() ?> <?= $profileUserProfile->getLastName() ?>'s kackel</h2>
            <?php } ?>
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
        userID="<?= $profileUser->getId() ?>"
        data-root="<?= $rootURL ?>"
        data-writeLink="<?= $writeDirectoryLink ?>"></script>
</body>
</html>