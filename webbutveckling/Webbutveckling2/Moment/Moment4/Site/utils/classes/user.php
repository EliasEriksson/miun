<?php
include_once __DIR__ . "/../config.php";
include_once __DIR__ . "/manager.php";
include_once __DIR__ . "/userProfile.php";


class User
{
    protected $id;
    protected $email;
    protected $passwordHash;
    protected $url;

    public static function fromAssoc(array $userData): User
    {
        return new User(
            $userData["id"],
            $userData["email"],
            $userData["passwordHash"],
            $userData["url"]
        );
    }

    public function __construct(int $id, string $email, string $passwordHash, string $url)
    {
        $this->id = $id;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->url = $url;
    }

    public function authenticate(string $password): ?User
    {
        if (password_verify($password, $this->passwordHash)) {
            $manager = new Manager();
            return $manager->getUser($this->id);
        }
        return null;
    }

    public function getProfile(Manager $manager = null): ?UserProfile
    {
        if (!$manager) {
            $manager = new Manager();
        }
        return $manager->getUserProfile($this->id);
    }

    public function getAssoc(): array
    {
        $properties = get_object_vars($this);
        unset($properties["passwordHash"]);
        return $properties;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    function getWebURL(): string {
        return $GLOBALS["rootURL"] . "/Profiles/Profile/?$this->url";
    }
}