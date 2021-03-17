<?php
include_once __DIR__ . "/../config.php";
include_once __DIR__ . "/manager.php";
include_once __DIR__ . "/userProfile.php";
include_once __DIR__ . "/user.php";

/**
 * Class Cluck
 *
 * class for dealing with user posts
 */
class Cluck
{
    private $id;
    private $userID;
    private $title;
    private $content;
    private $url;
    private $postDate;
    private $lastEdited;

    /**
     * alternative constructor
     *
     * constructs a cluck object from an associative array instead of passing values
     *
     * this is mostly used by the Manager class
     *
     * @param array $cluckData
     * @return Cluck
     */
    public static function fromAssoc(array $cluckData): Cluck
    {
        return new Cluck(
            $cluckData["id"],
            $cluckData["userID"],
            $cluckData["title"],
            $cluckData["content"],
            $cluckData["url"],
            $cluckData["postDate"],
            $cluckData["lastEdited"]
        );
    }

    /**
     * extents a list oc clucks wit information required to generate HTML with javascript
     *
     * used to build json objects meant to be sent off via one of the get APIs under /api
     *
     * if a database connection is already established form an outer scope the
     * connection can be passed thru as an argument instead of establishing a
     * new connection to the database.
     *
     * @param array $clucks
     * @param Manager|null $manager
     * @return array
     */
    public static function extendClucks(array $clucks, Manager $manager = null): array
    {
        if (!$manager) {
            $manager = new Manager();
        }
        $extendedClucks = [];
        foreach ($clucks as $cluck) {
            array_push($extendedClucks, $cluck->extend($manager));

        }
        return $extendedClucks;
    }

    public function __construct(
        int $id,
        int $userID,
        string $title,
        string $content,
        string $url,
        string $postDate,
        ?string $lastEdited)
    {
        $format = "Y-m-d H:i:s";

        $this->id = $id;
        $this->userID = $userID;
        $this->title = $title;
        $this->content = $content;
        $this->url = $url;
        $this->postDate = DateTime::createFromFormat($format, $postDate, new DateTimeZone("utc"));
        if ($lastEdited) {
            $this->lastEdited = DateTime::createFromFormat($format, $lastEdited, new DateTimeZone("utc"));
        } else {
            $this->lastEdited = null;
        }
    }

    /**
     * extends the user object with information and returns this as an associative array
     *
     * extended with the user profile of the user that posted,
     * the users URL,
     * the post this post replies to and
     * the amount of replies to this post.
     *
     * @param Manager|null $manager
     * @param bool $getRepliedClucks
     * @return array
     */
    public function extend(Manager $manager = null, $getRepliedClucks = true): array
    {
        if (!$manager) {
            $manager = new Manager();
        }
        $user = $this->getUser($manager);
        $userProfile = $this->getUserProfile($manager);
        if ($getRepliedClucks) {
            if ($repliedCluck = $manager->getRepliedCluck($this->getID())) {
                $extendedRepliedCluck = $repliedCluck->extend($manager, false);
            } else {
                $extendedRepliedCluck = null;
            }
        } else {
            $extendedRepliedCluck = null;
        }

        return array_merge(
            $this->getAssoc(),
            $userProfile->getAssoc(),
            [
                "userURL" => $user->getUrl(),
                "repliedCluck" => $extendedRepliedCluck,
                "replyCount" => $manager->getReplyCount($this->getID())
            ]
        );
    }

    /**
     * gets the user object of the user who posted this post
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
     * gets the userProfile of the user who posted this post
     *
     * if a database connection is already established form an outer scope the
     * connection can be passed thru as an argument instead of establishing a
     * new connection to the database.
     *
     * @param Manager|null $manager
     * @return UserProfile
     */
    public function getUserProfile(Manager $manager = null): UserProfile
    {
        if (!$manager) {
            $manager = new Manager();
        }
        return $manager->getUserProfile($this->userID);
    }

    /**
     * get the post that this post replies to as a Cluck object
     *
     * if a database connection is already established form an outer scope the
     * connection can be passed thru as an argument instead of establishing a
     * new connection to the database.
     *
     * @param int $id
     * @param Manager|null $manager
     * @return Cluck|null
     */
    public function getRepliedCluck(int $id, Manager $manager = null): ?Cluck
    {
        if (!$manager) {
            $manager = new Manager();
        }
        return $manager->getRepliedCluck($id);
    }

    /**
     * get this object as an associative array instead.
     *
     * time properties are converted to timestamps
     *
     * @return array
     */
    public function getAssoc(): array
    {
        $properties = get_object_vars($this);
        $properties["postDate"] = $properties["postDate"]->getTimestamp();
        if ($properties["lastEdited"]) {
            $properties["lastEdited"] = $properties["lastEdited"]->getTimestamp();
        }
        return $properties;
    }

    /**
     * get the internal URL to the post
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * get the web link to the post
     *
     * @return string
     */
    public function getLink(): string
    {
        return $GLOBALS["rootURL"] . "/Cluck/?$this->url";
    }

    /**
     * get the post date as unix timestamp
     *
     * @return int
     */
    public function getPostDate(): int
    {
        return $this->postDate->getTimestamp();
    }

    /**
     * get the last edited time as unix timestamp
     *
     * @return int|null
     */
    public function getLastEdited(): ?int
    {
        if ($this->lastEdited) {
            return $this->lastEdited->getTimestamp();
        }
        return null;
    }

    /**
     * get the title of the post
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * get the content of the post
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * get the id of the post
     *
     * @return int
     */
    public function getID(): int
    {
        return $this->id;
    }
}