<?php
include_once __DIR__ . "/../config.php";
include_once __DIR__ . "/manager.php";
include_once __DIR__ . "/userProfile.php";
include_once __DIR__ . "/user.php";
include_once __DIR__ . "/form.php";
include_once __DIR__ . "/field.php";


/**
 * Class CluckForm
 *
 * cosntructs, valdiates and generates HTML for a form
 */
class CluckForm extends Form {
    public function __construct(string $classPrefix = "general")
    {
        parent::__construct([
            new Field("title", "text", "", $classPrefix, "Titel:"),
            new Field("content", "textarea", "", $classPrefix, "Kackel:"),
        ], new Field("cluck", "submit", "Kackla!", $classPrefix), $classPrefix);
    }

    /**
     * validates teh form.
     *
     * if teh form is successfully validates a new cluck is created in the database and returned as an object
     * if validation fails null is returned instead.
     *
     * if a database connection is already established form an outer scope the
     * connection can be passed thru as an argument instead of establishing a
     * new connection to the database.
     *
     * @param Manager|null $manager
     * @return Cluck|null
     */
    public function validate(Manager $manager = null): ?Cluck
    {
        if (!$this->validateFields()) {
            return null;
        }

        $userProfile = getSessionUserProfile();
        $manager = new Manager();
        return $manager->createCluck($userProfile->getUserID(), $_POST["title"], $_POST["content"]);
    }
}