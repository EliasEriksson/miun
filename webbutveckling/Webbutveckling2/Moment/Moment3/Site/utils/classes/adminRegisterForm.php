<?php
include_once __DIR__ . "/form.php";
include_once __DIR__ . "/admin.php";
include_once __DIR__ . "/manager.php";


/**
 * Class AdminRegisterForm
 * all required functionality to attempt to register an admin
 */
class AdminRegisterForm extends Form
{
    /**
     * AdminRegisterForm constructor.
     * @param string $submit value of the submit button
     * @param string $classPrefix prefix for all the forms components css classes
     */
    public function __construct(string $submit = "submit", string $classPrefix = "")
    {
        parent::__construct([
            "användarnamn" => "text",
            "lösenord" => "password",
            "återupprepa" => "password"
        ], $submit, $classPrefix, ["lösenord", "återupprepa"]);
    }

    /**
     * validates the user input and attempts to register an admin
     * @return Admin|null Admin if successful, null if some form of input error or if the admin already exists
     */
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
