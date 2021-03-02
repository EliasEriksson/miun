<?php
include_once __DIR__ . "/manager.php";
include_once __DIR__ . "/userProfile.php";


class User
{
    protected $id;
    protected $email;
    protected $passwordHash;

    public static function fromAssoc(array $userData): User
    {
        return new User(
            $userData["id"],
            $userData["email"],
            $userData["passwordHash"]
        );
    }

    public function __construct(int $id, string $email, string $passwordHash)
    {
        $this->id = $id;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
    }

    public function authenticate(string $password): ?User
    {
        if (password_verify($password, $this->passwordHash)) {
            $manager = new Manager();
            return $manager->getUser($this->id);
        }
        return null;
    }

    public function getProfile(): ?UserProfile {
        $manager = new Manager();
        return $manager->getUserProfile($this->id);
    }
}