<?php
include_once __DIR__ . "/../../utils/config.php";
include_once __DIR__ . "/../../utils/functions.php";
include_once __DIR__ . "/../../utils/classes/manager.php";

$cluckURL = getCurrentPage($rootURL);
$manager = new Manager();

$currentUserProfile = getSessionUserProfile();

if (!($cluck = $manager->getCluckFromURL($cluckURL))) {
    redirect($rootURL);
}

$cluckPostUserProfile = $cluck->getUserProfile($manager);

if (!($currentUserProfile->getUserID() === $cluckPostUserProfile)) {
    redirect($rootURL);
}

$manager->deleteCluck($cluck->getID());

redirect($rootURL);