<?php
include_once __DIR__ . "/manager.php";
include_once __DIR__ . "/userProfile.php";


class UserProfile
{
    private $userID;
    private $firstName;
    private $lastName;
    private $avatar;
    private $description;

    public static function fromAssoc(array $userProfileData): UserProfile
    {
        return new UserProfile(
            $userProfileData["userID"],
            $userProfileData["firstName"],
            $userProfileData["lastName"],
            $userProfileData["avatar"],
            $userProfileData["description"]
        );
    }

    public function __construct(
        int $userID,
        string $firstName,
        string $lastName,
        string $avatar,
        string $description)
    {
        $this->userID = $userID;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->avatar = $avatar;
        $this->description = $description;
    }

    public function getUser(): User
    {
        $manager = new Manager();
        return $manager->getUser($this->userID);
    }

    public function getAssoc(): array
    {
        return get_object_vars($this);
    }

    public function getUserID(): int
    {
        return $this->userID;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getFilePathAvatar(): string
    {
        return $GLOBALS["writeDirectory"] . $this->avatar;
    }

    public function getWebLinkAvatar(): string
    {
        return $GLOBALS["writeDirectoryLink"] . $this->avatar;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}
