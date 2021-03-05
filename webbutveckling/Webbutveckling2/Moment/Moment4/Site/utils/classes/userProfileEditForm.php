<?php
include_once __DIR__ . "/../config.php";
include_once __DIR__ . "/../functions.php";
include_once __DIR__ . "/form.php";
include_once __DIR__ . "/field.php";
include_once __DIR__ . "/manager.php";
include_once __DIR__ . "/user.php";
include_once __DIR__ . "/userProfile.php";
include_once __DIR__ . "/userProfileSetupForm.php";


class UserProfileEditForm extends Form
{
    public static function fromID(int $id): UserProfileEditForm
    {
        $manager = new Manager();
        return new UserProfileEditForm($manager->getUserProfile($id));
    }

    public function __construct(UserProfile $userProfile, string $classPrefix = "edit")
    {
        parent::__construct([
            new Field("id", "hidden", $userProfile->getUserID(), $classPrefix),
            new Field("firstName", "text", $userProfile->getFirstName(), $classPrefix, "Förnamn"),
            new Field("lastName", "text", $userProfile->getLastName(), $classPrefix, "Efternamn:"),
            new Field("avatar", "file", "", $classPrefix, "Profilbild:", false, false),
            new Field("description", "textarea", $userProfile->getDescription(), "Beskriv dig själv:"),
            new Field("updateProfile", "submit", "Uppdatera din profil", $classPrefix)
        ], $classPrefix);
    }

    public function validate(): ?UserProfile
    {
        if (!$this->validateFields()) {
            return null;
        }

        $user = getSessionUser();

        $avatarWebPath = validateAvatar($user);

        $manager = new Manager();
        if ($userProfile = $manager->updateUserProfile($user->getId(), $_POST["firstName"], $_POST["lastName"], $avatarWebPath, $_POST["description"])) {
            $_SESSION["userProfile"] = $userProfile;
            return $userProfile;
        }
        return null;
    }
}