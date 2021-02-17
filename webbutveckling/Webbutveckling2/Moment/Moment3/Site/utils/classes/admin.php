<?php

class Admin {
    private $username;
    private $passwordHash;
    private $salt;

    public function __construct(string $username, string $passwordHash, string $salt)
    {
        $this->username = $username;
        $this->passwordHash = $passwordHash;
        $this->salt = $salt;
    }

    public static function fromAssociativeArray(array $adminData): Admin {
        return new Admin($adminData["username"], $adminData["passwordHash"], $adminData["salt"]);
    }

    public function authenticate($password): bool {
        if ($this->getPasswordHash() === hash("sha256", $password.$this->getSalt())) {
            return true;
        }
        return false;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    /**
     * @return string
     */
    public function getSalt(): string
    {
        return $this->salt;
    }
}