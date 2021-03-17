<?php
include_once __DIR__ . "/../../utils/config.php";
include_once __DIR__ . "/../../utils/functions.php";
include_once __DIR__ . "/../../utils/classes/manager.php";

if ($userURL = getCurrentPage()) {
    if (userLoggedIn()) {
        $user = getSessionUser();
        if ($user->getUrl() === $userURL && !($userProfile = $user->getProfile())) {
            // user is logged in and requested its own profile but the own profile is not set up
            // force the user to setup their profile
            include "../../templates/setupUserProfile.php";
        } else {
            // view the requested users profile
            include "../../templates/userProfile.php";
        }
    } else {
        // view the requested users profile
        include "../../templates/userProfile.php";
    }
} else {
    // profile is not specified in the URL an attempt to get the user from session is made
    // else the user is asked to log in
    $user = getSessionUser(); // redirects to login if user is not logged in
    $userURL = $user->getUrl();
    redirect("$rootURL/Profiles/Profile/?$userURL"); // user was logged in so redirects them to their profile
}