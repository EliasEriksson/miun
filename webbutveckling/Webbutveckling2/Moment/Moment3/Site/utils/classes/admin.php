<?php

class Admin
    /**
     * represents an admin with the ability to authenticate a user
     */
{
    private $username;
    private $passwordHash;

    public function __construct(string $username, string $passwordHash)
        /**
         * constructs and admin from given username hash and salt
         */
    {
        $this->username = $username;
        $this->passwordHash = $passwordHash;
    }

    public static function fromAssociativeArray(array $adminData): Admin
    /**
     * alternative constructor for easier use when associative arrays are given from queries
     */
    {
        return new Admin($adminData["username"], $adminData["passwordHash"]);
    }

    public function authenticate($password): bool
        /**
         * authenticates the user against the given password, if true the passwords match
         * and the user can be considered to be an admin
         */
    {
        if (password_verify($password, $this->passwordHash)) {
            return true;
        }
        return false;
    }

    /**
     * getters
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }
}