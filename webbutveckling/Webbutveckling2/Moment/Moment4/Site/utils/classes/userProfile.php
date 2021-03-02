<?php
include_once __DIR__ . "/manager.php";
include_once __DIR__ . "/userProfile.php";


class UserProfile
{
    private $userID;
    private $firstName;
    private $lastName;
    private $url;
    private $avatar;

    public static function fromAssoc(array $userProfileData): UserProfile
    {
        return new UserProfile(
            $userProfileData["userID"],
            $userProfileData["firstName"],
            $userProfileData["lastName"],
            $userProfileData["url"],
            $userProfileData["avatar"]
        );
    }

    public function __construct(
        int $userID,
        string $firstName,
        string $lastName,
        string $url,
        string $avatar)
    {
        $this->userID = $userID;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->url = $url;
        $this->avatar = $avatar;
    }

    public function getUser(): UserProfile
    {
        $manager = new Manager();
        return $manager->getUserProfile($this->userID);
    }
}
