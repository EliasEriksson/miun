<?php
include_once __DIR__ . "/../config.php";
include_once __DIR__ . "/form.php";
include_once __DIR__ . "/manager.php";
include_once __DIR__ . "/field.php";
include_once __DIR__ . "/user.php";
include_once __DIR__ . "/userProfile.php";

/**
 * Class UserLoginForm
 *
 * constructs, validates and generates HTML for a form.
 */
class UserLoginForm extends Form
{
    public function __construct(string $classPrefix = "general")
    {
        parent::__construct([
            new Field("email", "email", "", $classPrefix, "E-post:"),
            new Field("password", "password", "", $classPrefix, "Lösenord:", false),
        ], new Field("login", "submit", "Logga in", $classPrefix), $classPrefix);
    }

    /**
     * validates the form.
     *
     * if the form successfully validates an attempt is made to authenticate the user
     * if the user is successfully authenticated the authenticated user object is returned.
     * if the form doesnt validate or if the user doesnt authenticate null is returned.
     *
     * if a database connection is already established form an outer scope the
     * connection can be passed thru as an argument instead of establishing a
     * new connection to the database.
     *
     * @param Manager|null $manager
     * @return User|null
     */
    public function validate(Manager $manager = null): ?User
    {
        if (!$this->validateFields()) {
            return null;
        }

        if (!$manager) {
            $manager = new Manager();
        }
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