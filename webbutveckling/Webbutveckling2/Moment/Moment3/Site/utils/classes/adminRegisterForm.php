<?php
include_once __DIR__ . "/form.php";
include_once __DIR__ . "/admin.php";
include_once __DIR__ . "/manager.php";


class AdminRegisterForm extends Form
{
    public function __construct(string $submit = "submit", string $classPrefix = "")
    {
        parent::__construct([
            "användarnamn" => "text",
            "lösenord" => "password",
            "återupprepa" => "password"
        ], $submit, $classPrefix, ["lösenord", "återupprepa"]);
    }

    public function validate(): ?Admin
    {
        if (!$this->validateFields()) {
            return null;
        }

        if ($_POST["lösenord"] !== $_POST["återupprepa"]) {
            $this->setError("Lösenorden matchar inte.");
            return null;
        }

        $manager = new Manager();
        $admin = $manager->addAdmin($_POST["användarnamn"], $_POST["återupprepa"]);
        if ($admin) {
            return $admin;
        }
        $this->setError("Användaren finns redan.");
        return null;
    }
}
