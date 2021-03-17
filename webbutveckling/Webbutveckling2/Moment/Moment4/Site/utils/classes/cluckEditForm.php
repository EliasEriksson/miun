<?php
include_once __DIR__ . "/../config.php";
include_once __DIR__ . "/manager.php";
include_once __DIR__ . "/userProfile.php";
include_once __DIR__ . "/user.php";
include_once __DIR__ . "/form.php";
include_once __DIR__ . "/field.php";
include_once __DIR__ . "/cluck.php";

/**
 * Class CluckEditForm
 *
 * constructs, validates and generates HTML for a form
 */
class CluckEditForm extends Form {
    public function __construct(Cluck $cluck, string $classPrefix = "general")
    {
        parent::__construct([
            new Field("id", "hidden", $cluck->getID()),
            new Field("updateTitle", "text", $cluck->getTitle(), $classPrefix, "Redifera titel:"),
            new Field("updateContent", "textarea", $cluck->getContent(), $classPrefix, "Redigere kackel:")
        ], new Field("cluckEditSubmit","submit", "Updatera", $classPrefix), $classPrefix);
    }

    /**
     * validates the form.
     *
     * if the form validates successfully the data in the database is updated
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

        requireUserProfileLogin();

        if (!$manager) {
            $manager = new Manager();
        }
        return $manager->updateCluck($_POST["id"], $_POST["updateTitle"], $_POST["updateContent"]);
    }
}