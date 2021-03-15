<?php
include_once __DIR__ . "/../config.php";
include_once __DIR__ . "/form.php";
include_once __DIR__ . "/field.php";
include_once __DIR__ . "/manager.php";
include_once __DIR__ . "/user.php";


class UserRegisterForm extends Form
{
    public function __construct(string $classPrefix = "general")
    {
        parent::__construct([
            new Field("email", "email", "", $classPrefix, "E-post:"),
            new Field("password1", "password", "", $classPrefix, "Lösenord", false),
            new Field("password2", "password", "", $classPrefix, "Återupprepa Lösenord", false),
        ], new Field("register", "submit", "Registrera dig", $classPrefix), $classPrefix);
    }

    public function validate(): ?User
    {
        if (!$this->validateFields()) {
            return null;
        }
        if ($_POST["password1"] !== $_POST["password2"]) {
            $this->setError("Lösenorden matchar inte.");
            return null;
        }

        $manager = new Manager();
        $user = $manager->createUser($_POST["email"], $_POST["password1"]);
        if ($user) {
            $_SESSION["user"] = $user;
            return $user;
        }

        $this->setError("Användaren finns redan.");
        return null;
    }
}