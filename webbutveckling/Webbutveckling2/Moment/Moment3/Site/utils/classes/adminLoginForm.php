<?php
include_once __DIR__ . "/form.php";
include_once __DIR__ . "/admin.php";
include_once __DIR__ . "/manager.php";


class AdminLoginForm extends Form
{

    public function __construct(string $submit = "submit", $classPrefix = "")
    {
        parent::__construct([
            "användarnamn" => "text",
            "lösenord" => "password"
        ], $submit, $classPrefix, ["lösenord"]);
    }

    public function validate(): ?Admin
    {
        if (!$this->validateFields()) {
            return null;
        }

        $manager = new Manager();
        $admin = $manager->getAdmin($_POST["användarnamn"]);

        if ($admin) {
            if ($admin->authenticate($_POST["lösenord"])) {
                return $admin;
            } else {
                $this->setError("Fel lösenord.");
            }
        } else {
            $this->setError(($_POST["användarnamn"] . " är inte registrerad."));
        }
        return null;
    }
}