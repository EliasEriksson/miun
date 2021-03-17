<?php
include_once __DIR__ . "/manager.php";
include_once __DIR__ . "/userProfile.php";


/**
 * Class UserProfile
 *
 * class for dealing with userProfiles
 */
class UserProfile
{
    private $userID;
    private $firstName;
    private $lastName;
    private $avatar;
    private $description;

    /**
     * alternative constructor.
     *
     * constructs a userProfile object from an associative array instead of passing values
     * this would not have been needed it php supported unpacking with ... on associative arrays...
     *
     * this is mostly used by the Manager class
     *
     * @param array $userProfileData
     * @return UserProfile
     */
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

    /**
     * getters
     */

    /**
     * gets the user the profile is linked to
     *
     * if a database connection is already established form an outer scope the
     * connection can be passed thru as an argument instead of establishing a
     * new connection to the database.
     *
     * @param Manager|null $manager
     * @return User
     */
    public function getUser(Manager $manager = null): User
    {
        if (!$manager) {
            $manager = new Manager();
        }
        return $manager->getUser($this->userID);
    }

    /**
     * gets all the objects properties as an associative array
     *
     * @return array
     */
    public function getAssoc(): array
    {
        return get_object_vars($this);
    }

    /**
     * gets the user ID
     *
     * @return int
     */
    public function getUserID(): int
    {
        return $this->userID;
    }

    /**
     * gets the firstName
     *
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * gets the lastName
     *
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * gets the combined internal URL with the path to the write directory.
     *
     * @return string
     */
    public function getFilePathAvatar(): string
    {
        return $GLOBALS["writeDirectory"] . $this->avatar;
    }

    /**
     * gets the combined internal URL with the web path to the write directory
     *
     * used to created <img> tags
     *
     * @return string
     */
    public function getWebLinkAvatar(): string
    {

        return $GLOBALS["writeDirectoryLink"] . $this->avatar;
    }

    /**
     * gets the description
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }
}
