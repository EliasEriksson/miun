<?php
include_once __DIR__ . "/../config.php";
include_once __DIR__ . "/manager.php";
include_once __DIR__ . "/userProfile.php";

/**
 * Class User
 *
 * class for dealing with users
 */
class User
{
    protected $id;
    protected $email;
    protected $passwordHash;
    protected $url;

    /**
     * alternative constructor
     *
     * constructs a user object from an associative array instead of passing values
     *
     * this is mostly used by the Manager class
     *
     * @param array $userData
     * @return User
     */
    public static function fromAssoc(array $userData): User
    {
        return new User(
            $userData["id"],
            $userData["email"],
            $userData["passwordHash"],
            $userData["url"]
        );
    }

    /**
     * extents a list of users with information required to generate HTML with javascript
     *
     * used to build json object meant to be sent off via one of the get APIs under /api
     *
     * if a database connection is already established form an outer scope the
     * connection can be passed thru as an argument instead of establishing a
     * new connection to the database.
     *
     * @param array $users
     * @param Manager|null $manager
     * @return array
     */
    public static function extendUsers(array $users, Manager $manager = null): array
    {
        if (!$manager) {
            $manager = new Manager();
        }
        $extendedUsers = [];
        foreach ($users as $user) {
            if ($extendedUser = $user->extend($manager)) {
                array_push($extendedUsers, $extendedUser);
            }
        }
        return $extendedUsers;
    }

    public function __construct(int $id, string $email, string $passwordHash, string $url)
    {
        $this->id = $id;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->url = $url;
    }

    /**
     * authenticates a user.
     *
     * checks if the password hash matches with the given password.
     *
     * returns the authenticated user if authentication is successful else null.
     *
     * @param string $password
     * @return User|null
     */
    public function authenticate(string $password): ?User
    {
        if (password_verify($password, $this->passwordHash)) {
            $manager = new Manager();
            return $manager->getUser($this->id);
        }
        return null;
    }

    /**
     * extends the user object with information and returns this as an associative array
     *
     * extended with the users profile, post count and reply count.
     *
     * if a database connection is already established form an outer scope the
     * connection can be passed thru as an argument instead of establishing a
     * new connection to the database.
     *
     * @param Manager|null $manager
     * @return array|null
     */
    public function extend(Manager $manager = null): ?array
    {
        if (!$manager) {
            $manager = new Manager();
        }
        $userProfile = $this->getProfile($manager);
        if (!$userProfile) {
            return null;
        }
        return array_merge(
            $this->getAssoc(),
            $userProfile->getAssoc(),
            [
                "postCount" => $manager->getUserPostCount($this->getId()),
                "replyCount" => $manager->getUserReplyCount($this->getId())
            ]
        );
    }

    /**
     * gets the user profile the user is associated with
     *
     * if a database connection is already established form an outer scope the
     * connection can be passed thru as an argument instead of establishing a
     * new connection to the database.
     *
     * @param Manager|null $manager
     * @return UserProfile|null
     */
    public function getProfile(Manager $manager = null): ?UserProfile
    {
        if (!$manager) {
            $manager = new Manager();
        }
        return $manager->getUserProfile($this->id);
    }

    /**
     * getters
     */

    /**
     * gets the current user objects properties as an associative array
     * this array will exclude the passwordHash
     *
     * @return array
     */
    public function getAssoc(): array
    {
        $properties = get_object_vars($this);
        unset($properties["passwordHash"]);
        return $properties;
    }

    /**
     * gets the users ID
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * gets the users email
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * gets the users URL
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * gets the URL combined with the root url + path to a profile
     *
     * used to link to a users profile
     *
     * @return string
     */
    function getWebURL(): string
    {
        return $GLOBALS["rootURL"] . "/Profiles/Profile/?$this->url";
    }
}