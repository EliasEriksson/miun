<?php
include_once __DIR__ . "/../config.php";
include_once __DIR__ . "/manager.php";
include_once __DIR__ . "/userProfile.php";
include_once __DIR__ . "/user.php";
include_once __DIR__ . "/form.php";
include_once __DIR__ . "/field.php";
include_once __DIR__ . "/cluck.php";


class CluckEditForm extends Form {
    public function __construct(Cluck $cluck, string $classPrefix = "general")
    {
        parent::__construct([
            new Field("id", "hidden", $cluck->getID()),
            new Field("updateContent", "textarea", $cluck->getContent(), $classPrefix, "Redigere Kackel:")
        ], new Field("cluckEditSubmit","submit", "Updatera", $classPrefix), $classPrefix);
    }

    public function validate(): ?Cluck
    {
        if (!$this->validateFields()) {
            return null;
        }

        requireUserProfileLogin();

        $manager = new Manager();
        return $manager->updateCluck($_POST["id"], $_POST["updateContent"]);
    }
}