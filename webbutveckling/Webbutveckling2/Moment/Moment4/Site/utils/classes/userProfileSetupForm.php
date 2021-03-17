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
 * Class UserProfileSetupForm
 *
 * constructs, validates and generates HTML for a form.
 */
class UserProfileSetupForm extends UserProfileForm
{
    public function __construct(string $classPrefix = "general")
    {
        parent::__construct([
            new Field("firstName", "text", "", $classPrefix, "Förnamn:"),
            new Field("lastName", "text", "", $classPrefix, "Efternamn:"),
            new Field("avatar", "file", "", $classPrefix, "Profilbild:", false, false),
            new Field("description", "textarea", "", $classPrefix, "Beskriv dig själv:"),
        ], new Field("setup", "submit", "Klar", $classPrefix), $classPrefix);
    }

    /**
     * validates the form.
     *
     * if the form is successfully validated a new UserProfile is created in the database
     * and the userProfile object is returned.
     * if the form does not validate null is returned instead.
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

        $webPath = $this->validateAvatar($user);

        if (!$manager) {
            $manager = new Manager();
        }
        if ($userProfile = $manager->createUserProfile($user->getId(), $_POST["firstName"], $_POST["lastName"], $webPath, $_POST["description"])) {
            $_SESSION["userProfile"] = $userProfile;
            return $userProfile;
        }
        return null;
    }
}