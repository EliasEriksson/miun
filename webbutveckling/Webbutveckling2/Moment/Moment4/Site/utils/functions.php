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
 * takes a path and removes the last part of it
 * i.e: /home/elias-eriksson/dev would become /home/elias-eriksson
 * @param string $path
 * @return string
 */
function parentDirectory(string $path): string
{
    $parts = explode("/", $path);
    return implode("/", array_splice($parts, 0, -1));
}

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


function popAssoc(array &$array, string $key)
{
    $value = $array[$key];
    unset($array[$key]);
    return $value;
}


function requireUserLogin()
{
    $root = $GLOBALS["rootURL"];
    if (!isset($_SESSION["user"])) {
        redirect("$root/Login/");
    }
}

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

function getSessionUser(): User
{
    requireUserLogin();
    return $_SESSION["user"];
}

function getSessionUserProfile(): UserProfile
{
    requireUserProfileLogin();
    return $_SESSION["userProfile"];
}

function userLoggedIn(): bool
{
    return isset($_SESSION["user"]);
}

function userProfileLoggedIn(): bool
{
    return userLoggedIn() && isset($_SESSION["userProfile"]);
}

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
    echo "mime $mime was not in supported formats. ";
    return null;
}

function extendCluck(Cluck $cluck, Manager $manager = null, bool $getRepliedClucks = true): array
{
    if (!$manager) {
        $manager = new Manager();
    }
    $user = $cluck->getUser($manager);
    $userProfile = $cluck->getUserProfile($manager);
    if ($getRepliedClucks) {
        if ($repliedCluck = $manager->getRepliedCluck($cluck->getID())) {
            $extendedRepliedCluck = extendCluck($repliedCluck, $manager, false);
        } else {
            $extendedRepliedCluck = null;
        }
    } else {
        $extendedRepliedCluck = null;
    }

    return array_merge(
        $cluck->getAssoc(),
        $userProfile->getAssoc(),
        [
            "userURL" => $user->getUrl(),
            "repliedCluck" => $extendedRepliedCluck,
            "replyCount" => $manager->getReplyCount($cluck->getID())
        ]
    );
}

function extendUser(User $user, Manager $manager): ?array
{
    if (!$manager) {
        $manager = new Manager();
    }
    $userProfile = $user->getProfile($manager);
    if (!$userProfile) {
        return null;
    }
    return array_merge(
        $user->getAssoc(),
        $userProfile->getAssoc(),
        [
            "postCount" => $manager->getUserPostCount($user->getId()),
            "replyCount" => $manager->getUserReplyCount($user->getId())
        ]
    );
}

function extendUsers(array $users, Manager $manager): array
{
    if (!$manager) {
        $manager = new Manager();
    }
    $extendedUsers = [];
    foreach ($users as $user) {
        if ($extendedUser = extendUser($user, $manager)) {
            array_push($extendedUsers, $extendedUser);
        }
    }
    return $extendedUsers;
}

function extendClucks(array $clucks, Manager $manager = null, bool $getRepliedClucks = true): array
{
    if (!$manager) {
        $manager = new Manager();
    }
    $extendedClucks = [];
    foreach ($clucks as $cluck) {
        array_push($extendedClucks, extendCluck($cluck, $manager, $getRepliedClucks));
    }
    return $extendedClucks;
}

//$manager = new Manager();
//$c = $manager->getCluck(15);
//$extendedC = extendCluck($c);
//var_dump($extendedC);