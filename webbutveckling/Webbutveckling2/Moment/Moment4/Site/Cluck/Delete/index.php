<?php
include_once __DIR__ . "/../../utils/config.php";
include_once __DIR__ . "/../../utils/functions.php";
include_once __DIR__ . "/../../utils/classes/manager.php";

$currentUserProfile = getSessionUserProfile(); // requires the user to have a profile

$cluckURL = getCurrentPage($rootURL);
$manager = new Manager();

if (!($cluck = $manager->getCluckFromURL($cluckURL))) {
    // no post on this URL exists, redirect to home
    redirect("$rootURL");
}

$cluckPostUserProfile = $cluck->getUserProfile($manager);

if (!($currentUserProfile->getUserID() === $cluckPostUserProfile->getUserID())) {
    // the user that requested the delete is not the owner of the post
    redirect("$rootURL");
}

$manager->deleteCluck($cluck->getID());

redirect($rootURL);