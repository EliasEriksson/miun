<?php
include_once __DIR__ . "/../config.php";
include_once __DIR__ . "/form.php";
include_once __DIR__ . "/manager.php";
include_once __DIR__ . "/field.php";
include_once __DIR__ . "/user.php";
include_once __DIR__ . "/userProfile.php";


class UserLoginForm extends Form
{
    public function __construct(string $classPrefix = "general")
    {
        parent::__construct([
            new Field("email", "email", "", $classPrefix, "E-post:"),
            new Field("password", "password", "", $classPrefix, "Lösenord:", false),
        ], new Field("login", "submit", "Logga in", $classPrefix), $classPrefix);
    }

    public function validate(): ?User
    {
        if (!$this->validateFields()) {
            return null;
        }

        $manager = new Manager();
        $user = $manager->getUserFromEmail($_POST["email"]);
        if ($user) {
            if ($user->authenticate($_POST["password"])) {
                $_SESSION["user"] = $user;
                return $user;
            } else {
                $this->setError("Fel lösenord.");
            }
        } else {
            $this->setError($_POST["username"] . " är inte registrerad.");
        }
        return null;
    }
}