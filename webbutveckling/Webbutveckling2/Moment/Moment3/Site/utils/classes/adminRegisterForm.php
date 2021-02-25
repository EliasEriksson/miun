<?php
include_once __DIR__ . "/form.php";
include_once __DIR__ . "/admin.php";
include_once __DIR__ . "/manager.php";
include_once __DIR__ . "/field.php";


/**
 * Class AdminRegisterForm
 * all required functionality to attempt to register an admin
 */
class AdminRegisterForm extends Form
{
    /**
     * AdminRegisterForm constructor.
     * @param string $classPrefix prefix for all the forms components css classes
     */
    public function __construct(string $classPrefix = "")
    {
        parent::__construct([
            new Field("username", "text", "", $classPrefix, "Användarnamn: "),
            new Field("password1", "password", "", $classPrefix, "Lösenord: ", false),
            new Field("password2", "password", "", $classPrefix, "Återupprepa lösenord: ", false),
            new Field("register", "submit", "Registrera dig", $classPrefix)
        ], $classPrefix);
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

        if ($_POST["password1"] !== $_POST["password2"]) {
            $this->setError("Lösenorden matchar inte.");
            return null;
        }

        $manager = new Manager();
        $admin = $manager->addAdmin($_POST["username"], $_POST["password1"]);
        if ($admin) {
            return $admin;
        }
        $this->setError("Användaren finns redan.");
        return null;
    }
}
