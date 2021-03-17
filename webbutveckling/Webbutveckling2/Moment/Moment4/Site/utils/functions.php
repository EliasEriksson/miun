<?php
include_once __DIR__ . "/config.php";
include_once __DIR__ . "/classes/user.php";
include_once __DIR__ . "/classes/userProfile.php";
include_once __DIR__ . "/classes/manager.php";

function redirect($uri)
{
    header("location: $uri");
}

/**
 * takes a path and removes the last part of it.
 * i.e: /home/elias-eriksson/dev would become /home/elias-eriksson
 * @param string $path
 * @return string
 */
function parentDirectory(string $path): string
{
    $parts = explode("/", $path);
    return implode("/", array_splice($parts, 0, -1));
}

/**
 * extracts the name of the first get parameter from the URL.
 *
 * if no get parameter is found the user will be redirected to given redirect URL.
 *
 * @param string $redirect
 * @return string
 */
function getCurrentPage(string $redirect = ""): string
{
    if (count($_GET) > 0) {
        // $currentPage = array_key_first($_GET);
        // since miun is at version 7.2 not 7.4.........
        foreach ($_GET as $currentPage => $_) break;
        if (isset($currentPage)) {
            return $currentPage;
        }
    }
    if ($redirect) {
        redirect($redirect);
    }
    return "";
}

/**
 * used on pages where a user is required to be logged in.
 *
 * if the user is not logged in the user will be redirected to the login page.
 */
function requireUserLogin()
{
    $root = $GLOBALS["rootURL"];
    if (!isset($_SESSION["user"])) {
        redirect("$root/Login/");
    }
}

/**
 * used on pages where the user is required to have a finalized user profile.
 *
 * if the user have not finalized their profiled they will be redirected
 * to the profile page to do so.
 */
function requireUserProfileLogin()
{
    requireUserLogin();
    $root = $GLOBALS["rootURL"];
    if (!isset($_SESSION["userProfile"])) {
        if ($userProfile = $_SESSION["user"]->getProfile()) {
            $_SESSION["userProfile"] = $userProfile;
        } else {
            redirect("$root/Profiles/Profile/");
        }
    }
}

/**
 * attempts to get the current user object from the session.
 *
 * this function requires the user to be logged in.
 *
 * @return User
 */
function getSessionUser(): User
{
    requireUserLogin();
    return $_SESSION["user"];
}

/**
 * attempts to get the current userProfile object from the session.
 *
 * this function requires the user to have a finalized userProfile.
 *
 * @return UserProfile
 */
function getSessionUserProfile(): UserProfile
{
    requireUserProfileLogin();
    return $_SESSION["userProfile"];
}

/**
 * checks whether the user is logged in or not.
 *
 * @return bool
 */
function userLoggedIn(): bool
{
    return isset($_SESSION["user"]);
}

/**
 * checks whether the user have a finalized userProfile or not.
 *
 * @return bool
 */
function userProfileLoggedIn(): bool
{
    return userLoggedIn() && isset($_SESSION["userProfile"]);
}

/**
 * returns the fileextention from a given MIME type
 *
 * if type is unsupported null is returned
 *
 * @param string $mime
 * @return string|null
 */
function getExtensionFromMIME(string $mime): ?string
{
    $supportedFormats = [
        "image/jpeg" => ".jpeg",
        "image/png" => ".png",
        "image/svg+xml" => ".svg"
    ];
    if (array_key_exists($mime, $supportedFormats)) {
        return $supportedFormats[$mime];
    }
    return null;
}



//$manager = new Manager();
//$c = $manager->getCluck(15);
//$extendedC = extendCluck($c);
//var_dump($extendedC);