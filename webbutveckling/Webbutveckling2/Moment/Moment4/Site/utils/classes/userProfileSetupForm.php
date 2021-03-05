<?php
include_once __DIR__ . "/../config.php";
include_once __DIR__ . "/../functions.php";
include_once __DIR__ . "/form.php";
include_once __DIR__ . "/field.php";
include_once __DIR__ . "/manager.php";
include_once __DIR__ . "/user.php";
include_once __DIR__ . "/userProfile.php";


function validateAvatar(User $user): string {
    if (isset($_FILES["avatar"]) && $extension = getExtensionFromMIME($_FILES["avatar"]["type"])) {
        $url = $user->getUrl();

        $file = "avatar$extension";
        $webDirectory = "/writeable/web2mom4/media/avatars/$url/";
        $fileDirectory = $GLOBALS["writeDirectory"] . "$webDirectory";
        $webPath = "$webDirectory$file";
        $filePath = "$fileDirectory$file";

        echo "write dir: ".$fileDirectory."<br>";

        if (!is_dir($fileDirectory)) {
            mkdir($fileDirectory, 0777, true);
        }
        move_uploaded_file($_FILES["avatar"]["tmp_name"], $filePath);
    } else {
        $webPath = "/writeable/web2mom4/media/avatars/defaultAvatars/default.svg";
    }
    return $webPath;
}

class UserProfileSetupForm extends Form
{
    public function __construct(string $classPrefix = "profile-setup")
    {
        parent::__construct([
            new Field("firstName", "text", "", $classPrefix, "Förnamn:"),
            new Field("lastName", "text", "", $classPrefix, "Efternamn:"),
            new Field("avatar", "file", "", $classPrefix, "Profilbild:", false, false),
            new Field("description", "textarea", "", $classPrefix, "Beskriv dig själv:"),
            new Field("setup", "submit", "Klar", $classPrefix)
        ], $classPrefix);
    }


    public function validate(): ?UserProfile
    {
        if (!$this->validateFields()) {
            return null;
        }
        $user = getSessionUser();

        $webPath = validateAvatar($user);

        $manager = new Manager();
        if ($userProfile = $manager->createUserProfile($user->getId(), $_POST["firstName"], $_POST["lastName"], $webPath, $_POST["description"])) {
            $_SESSION["userProfile"] = $userProfile;
            return $userProfile;
        }
        return null;
    }
}