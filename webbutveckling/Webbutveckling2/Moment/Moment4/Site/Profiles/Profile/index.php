<?php
include_once __DIR__ . "/../../utils/config.php";
include_once __DIR__ . "/../../utils/functions.php";
include_once __DIR__ . "/../../utils/classes/manager.php";

if ($userURL = getCurrentPage()) {
    if (userLoggedIn()) {
        $user = getSessionUser();
        if ($user->getUrl() === $userURL && !($userProfile = $user->getProfile())) {
            include "../../templates/setupUserProfile.php";
        } else {
            include "../../templates/userProfile.php";
        }
    } else {
        include "../../templates/userProfile.php";
    }
} else {
    $user = getSessionUser();
    $userURL = $user->getUrl();
    redirect("$rootURL/Profiles/Profile/?$userURL");
}