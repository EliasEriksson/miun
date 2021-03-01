<?php
include_once __DIR__ . "/form.php";
include_once __DIR__ . "/admin.php";
include_once __DIR__ . "/manager.php";
include_once __DIR__ . "/field.php";
include_once __DIR__ . "/form.php";


/**
 * Class AdminLoginForm
 * all required functionality to login a user thru a post request form
 */
class AdminLoginForm extends Form {
    /**
     * AdminLoginForm constructor.
     * @param string $classPrefix prefix for all the forms components css classes
     */
    public function __construct($classPrefix = "")
    {
        parent::__construct([
            new Field("username", "text", "", $classPrefix, "Användarnamn: "),
            new Field("password", "password", "", $classPrefix, "Lösenord: ", false),
            new Field("login", "submit", "Logga in", $classPrefix)
        ], $classPrefix);
    }

    /**
     * validates the user input and attempts to authenticate an admin
     * @return Admin|null Admin if successful, null if user entry was wrong or authentication failed
     */
    public function validate(): ?Admin
    {
        if (!$this->validateFields()) {
            return null;
        }

        $manager = new Manager();
        $admin = $manager->getAdmin($_POST["username"]);

        if ($admin) {
            if ($admin->authenticate($_POST["password"])) {
                return $admin;
            } else {
                $this->setError("Fel lösenord.");
            }
        } else {
            $this->setError(($_POST["username"] . " är inte registrerad."));
        }
        return null;
    }
}