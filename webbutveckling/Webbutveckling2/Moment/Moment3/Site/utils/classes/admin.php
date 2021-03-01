<?php


/**
 * Class Admin
 * represents an admin with the ability to authenticate itself
 * @property string $username the users username
 * @property string $passwordHash the hashed password
 */
class Admin
{
    private $username;
    private $passwordHash;

    /**
     * constructs and admin from given username hash and salt
     * @param string $username the users username
     * @param string $passwordHash the hashed password
     */
    public function __construct(string $username, string $passwordHash)
    {

        $this->username = $username;
        $this->passwordHash = $passwordHash;
    }

    /** alternative constructor for easier use when associative arrays are given from queries
     * @param array $adminData associative array with keys username and passwordHash
     * @return Admin the admin representation
     */
    public static function fromAssociativeArray(array $adminData): Admin
    {
        return new Admin($adminData["username"], $adminData["passwordHash"]);
    }

    /**
     * authenticates the user against the given password, if true the passwords match
     * and the user can be considered to be an admin
     * @param string $password the password entered by the user
     * @return bool successful authentication
     */
    public function authenticate(string $password): bool
    {
        if (password_verify($password, $this->passwordHash)) {
            return true;
        }
        return false;
    }

    // getters
    /**
     * @return string the admins username
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string the admins passwordHash
     */
    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }
}