<?php
include_once __DIR__ . "/form.php";
include_once __DIR__ . "/admin.php";
include_once __DIR__ . "/manager.php";

/**
 * Class AdminLoginForm
 * all required functionality to login a user thru a post request form
 */
class AdminLoginForm extends Form
{
    /**
     * AdminLoginForm constructor.
     * @param string $submit value of the submit button
     * @param string $classPrefix prefix for all the forms components css classes
     */
    public function __construct(string $submit = "submit", $classPrefix = "")
    {
        parent::__construct([
            "användarnamn" => "text",
            "lösenord" => "password"
        ], $submit, $classPrefix, ["lösenord"]);
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