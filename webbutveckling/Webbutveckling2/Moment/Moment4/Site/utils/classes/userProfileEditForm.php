<?php
include_once __DIR__ . "/../config.php";
include_once __DIR__ . "/../functions.php";
include_once __DIR__ . "/form.php";
include_once __DIR__ . "/field.php";
include_once __DIR__ . "/manager.php";
include_once __DIR__ . "/user.php";
include_once __DIR__ . "/userProfile.php";
include_once __DIR__ . "/userProfileForm.php";


/**
 * Class UserProfileEditForm
 *
 * constructs, validates and generates HTML for a form
 */
class UserProfileEditForm extends UserProfileForm
{
    /**
     * alternative constructor if the object from outer scope is not in scope but the outer scope
     * have the ID
     *
     * if a database connection is already established form an outer scope the
     * connection can be passed thru as an argument instead of establishing a
     * new connection to the database.
     *
     * @param int $id
     * @param Manager|null $manager
     * @return UserProfileEditForm
     */
    public static function fromID(int $id, Manager $manager = null): UserProfileEditForm
    {
        if (!$manager) {
            $manager = new Manager();
        }
        return new UserProfileEditForm($manager->getUserProfile($id));
    }

    public function __construct(UserProfile $userProfile, string $classPrefix = "general")
    {
        parent::__construct([
            new Field("firstName", "text", $userProfile->getFirstName(), $classPrefix, "Förnamn"),
            new Field("lastName", "text", $userProfile->getLastName(), $classPrefix, "Efternamn:"),
            new Field("avatar", "file", "", $classPrefix, "Profilbild:", false, false),
            new Field("description", "textarea", $userProfile->getDescription(), $classPrefix, "Beskriv dig själv:"),
        ], new Field("updateProfile", "submit", "Uppdatera din profil", $classPrefix), $classPrefix);
    }

    /**
     * validates the form.
     *
     * if the form is successfully validated the given userProfile in the constructor is updated
     * with new data in the database and and updated userProfile object is returned.
     *
     * if a database connection is already established form an outer scope the
     * connection can be passed thru as an argument instead of establishing a
     * new connection to the database.
     *
     * @param Manager|null $manager
     * @return UserProfile|null
     */
    public function validate(Manager $manager = null): ?UserProfile
    {
        if (!$this->validateFields()) {
            return null;
        }

        $user = getSessionUser();

        $avatarWebPath = $this->validateAvatar($user);

        if (!$manager) {
            $manager = new Manager();
        }
        if ($userProfile = $manager->updateUserProfile($user->getId(), $_POST["firstName"], $_POST["lastName"], $avatarWebPath, $_POST["description"])) {
            $_SESSION["userProfile"] = $userProfile;
            return $userProfile;
        }
        return null;
    }
}