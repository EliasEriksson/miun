<?php
include_once "../utils/config.php";
include_once "../utils/functions.php";
include_once "../utils/classes/manager.php";
include_once "../utils/classes/cluckReplyForm.php";

$cluckURL = getCurrentPage($rootURL);
$manager = new Manager();
if (!($thisCluck = $manager->getCluckFromURL($cluckURL))) {
    redirect($rootURL);
}
$thisCluckUser = $thisCluck->getUser($manager);
$thisCluckUserProfile = $thisCluck->getUserProfile($manager);
$thisExtendedCluck = extendCluck($thisCluck, $manager);

$cluckReplyForm = new CluckReplyForm($thisCluck);
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($reply = $cluckReplyForm->validate()) {
        $replyURL = $reply->getUrl();
        redirect("$rootURL/Cluck/?$replyURL");
    }
}

?>
<!doctype html>
<html lang="en">
<head>
    <?php
    include "../templates/head.php";
    ?>
    <title>Kackel</title>
</head>
<body>
<?php
include "../templates/header.php";
?>
<div class="main-wrapper">
    <main class="main-content fit-content">
        <div class="cluck-page-content-wrapper">
            <?php if ($repliedCluck = $manager->getRepliedCluck($thisCluck->getID())) {
                $repliedUserProfile = $repliedCluck->getUserProfile($manager);
                $repliedExtendedCluck = extendCluck($repliedCluck, $manager) ?>
                <article id="replied" class="cluck replied" data-replied-url="<?= $repliedCluck->getUrl() ?>">
                    <img class="cluck-avatar" src="<?= $repliedUserProfile->getWebLinkAvatar() ?>"
                         alt="cluck user avatar">
                    <div class="cluck-heading-wrapper">
                        <div class="cluck-and-reply">
                            <a class="cluck-heading-link" href="<?= $repliedCluck->getUser()->getWebURL() ?>">
                                <h2><?= $repliedUserProfile->getFirstName() ?> <?= $repliedUserProfile->getLastName() ?></h2>
                            </a>
                            <?php if ($repliedExtendedCluck["repliedCluck"]) { ?>
                                    <span class="cluck-and-reply-text">svarar</span>
                                    <a href="<?= $rootURL . '/Cluck/?' . $repliedExtendedCluck['repliedCluck']['url'] ?>"
                                       class="cluck-heading-link">
                                        <h3><?= $repliedExtendedCluck["repliedCluck"]["firstName"] ?> <?= $repliedExtendedCluck["repliedCluck"]["lastName"] ?></h3>
                                    </a>
                            <?php } ?>
                        </div>
                        <div class="cluck-metadata">
                            <div class="cluck-publish-details">
                                <span class="cluck-post-timestamp timestamp"><?= $repliedCluck->getPostDate() ?></span>
                                <?php if ($lastEdited = $repliedCluck->getLastEdited()) { ?>
                                    <span class="cluck-edit-timestamp">(<span
                                                class="timestamp"><?= $lastEdited ?></span>)</span>
                                <?php } ?>
                            </div>
                            <?php if ($repliedCluckReplyCount = $manager->getReplyCount($repliedCluck->getID())) { ?>
                                <div class="cluck-reply-count-wrapper">
                                    <span class="cluck-reply-count"><?= $repliedCluckReplyCount ?> svar</span>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <p class="cluck-content"><?= $repliedCluck->getContent() ?></p>
                </article>
            <?php } elseif ($manager->isReply($thisCluck->getID())) { ?>
                <article class="cluck replied removed-post">
                    <img class="cluck-avatar" src="<?= $rootURL ?>/media/removedPostProfile.svg"
                         alt="removed post default avatar">
                    <div class="cluck-heading-wrapper">
                        <h2>Borttaget inlägg 😞</h2>
                    </div>
                </article>
            <?php } ?>
            <article class="cluck this-cluck">
                <img class="cluck-avatar" src="<?= $thisCluckUserProfile->getWebLinkAvatar() ?>"
                     alt="cluck user avatar">
                <div class="cluck-heading-wrapper">
                    <div class="cluck-and-reply">
                        <a class="cluck-heading-link" href="<?= $thisCluckUser->getWebURL() ?>">
                            <h1><?= $thisCluckUserProfile->getFirstName() ?> <?= $thisCluckUserProfile->getLastName() ?></h1>
                        </a>
                        <?php if ($thisExtendedCluck["repliedCluck"]) { ?>
                                <span class="cluck-and-reply-text">svarar</span>
                                <a href="<?= $rootURL . '/Cluck/?' . $thisExtendedCluck['repliedCluck']['url'] ?>"
                                   class="cluck-heading-link">
                                    <h3><?= $thisExtendedCluck["repliedCluck"]["firstName"] ?> <?= $thisExtendedCluck["repliedCluck"]["lastName"] ?></h3>
                                </a>
                        <?php } ?>
                    </div>
                    <div class="cluck-metadata">
                        <div class="cluck-publish-details">
                            <span class="cluck-post-timestamp timestamp"><?= $thisCluck->getPostDate() ?></span>
                            <?php if ($lastEdited = $thisCluck->getLastEdited()) { ?>
                                <span class="cluck-edit-timestamp">(<span
                                            class="timestamp"><?= $lastEdited ?></span>)</span>
                            <?php } ?>
                        </div>
                        <?php if ($thisCluckReplyCount = $manager->getReplyCount($thisCluck->getID())) { ?>
                            <div class="cluck-reply-count-wrapper">
                                <span class="cluck-reply-count"><?= $thisCluckReplyCount ?> svar</span>
                            </div>
                        <?php } ?>
                    </div>


                </div>
                <p class="cluck-content"><?= $thisCluck->getContent() ?></p>
                <?php if (userProfileLoggedIn() && getSessionUserProfile()->getUserID() === $thisCluckUserProfile->getUserID()) { ?>
                    <div class="cluck-buttons"><a href="<?= $rootURL ?>/Cluck/Edit/?<?= $cluckURL ?>" class="button">Redigera</a><a
                                href="<?= $rootURL ?>/Cluck/Delete/?<?= $cluckURL ?>" class="button">Radera</a></div>
                <?php } ?>
            </article>
            <?php if (userProfileLoggedIn()) {
                echo "<div class='general-form-wrapper'>" . $cluckReplyForm->toHTML() . "</div>";
            }
            if ($replies = $manager->getCluckReplies($thisCluck->getID(), 0)) {
                ?>
                <section class="cluck-reply-section">
                    <h2 class="replies-heading">Svar till kacklet</h2>
                    <div id="clucks" class="cluck-replies"></div>
                </section>
                <?php
            } ?>
        </div>
    </main>
</div>
<?php
include "../templates/footer.php";
?>
<script src="<?= $rootURL ?>/static/js/utils.js"></script>
<script src="<?= $rootURL ?>/static/js/timestamp.js"></script>
<script src="<?= $rootURL ?>/static/js/clucks.js"
        root="<?= $rootURL ?>"
        writeLink="<?= $writeDirectoryLink ?>"></script>
<script src="<?= $rootURL ?>/static/js/replies.js" cluckID="<?= $thisCluck->getID() ?>"></script>
</body>
</html>