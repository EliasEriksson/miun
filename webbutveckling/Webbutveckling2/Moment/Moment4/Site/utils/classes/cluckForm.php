<?php
include_once __DIR__ . "/../config.php";
include_once __DIR__ . "/manager.php";
include_once __DIR__ . "/userProfile.php";
include_once __DIR__ . "/user.php";
include_once __DIR__ . "/form.php";
include_once __DIR__ . "/field.php";


class CluckForm extends Form {
    public function __construct(string $classPrefix = "cluck")
    {
        parent::__construct([
            new Field("content", "textarea", "", $classPrefix, "Kackel:"),
            new Field("cluck", "submit", "Kackla!", $classPrefix)
        ], $classPrefix);
    }

    public function validate(): ?Cluck
    {
        if (!$this->validateFields()) {
            return null;
        }

        $userProfile = getSessionUserProfile();
        $manager = new Manager();
        return $manager->createCluck($userProfile->getUserID(), $_POST["content"]);
    }
}