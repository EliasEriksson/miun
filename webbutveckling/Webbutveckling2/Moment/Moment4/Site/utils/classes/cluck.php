<?php
include_once __DIR__ . "/../config.php";
include_once __DIR__ . "/manager.php";
include_once __DIR__ . "/userProfile.php";
include_once __DIR__ . "/user.php";

class Cluck
{
    private $id;
    private $userID;
    private $content;
    private $url;
    private $postDate;
    private $lastEdited;


    public static function fromAssoc(array $cluckData): Cluck
    {
        return new Cluck(
            $cluckData["id"],
            $cluckData["userID"],
            $cluckData["content"],
            $cluckData["url"],
            $cluckData["postDate"],
            $cluckData["lastEdited"]
        );
    }

    public function __construct(
        int $id,
        int $userID,
        string $content,
        string $url,
        string $postDate,
        ?string $lastEdited)
    {
        $format = "Y-m-d H:i:s";

        $this->id = $id;
        $this->userID = $userID;
        $this->content = $content;
        $this->url = $url;
        $this->postDate = DateTime::createFromFormat($format, $postDate, new DateTimeZone("utc"));
        if ($lastEdited) {
            $this->lastEdited = DateTime::createFromFormat($format, $lastEdited, new DateTimeZone("utc"));
        } else {
            $this->lastEdited = null;
        }
    }

    public function getUser(Manager $manager = null): User
    {
        if (!$manager) {
            $manager = new Manager();
        }
        return $manager->getUser($this->userID);
    }

    public function getUserProfile(Manager $manager = null): UserProfile
    {
        if (!$manager) {
            $manager = new Manager();
        }
        return $manager->getUserProfile($this->userID);
    }

    public function getRepliedCluck(int $id): ?Cluck
    {
        $manager = new Manager();
        return $manager->getRepliedCluck($id);
    }

    public function getAssoc(): array
    {
        $properties = get_object_vars($this);
        $properties["postDate"] = $properties["postDate"]->getTimestamp();
        if ($properties["lastEdited"]) {
            $properties["lastEdited"] = $properties["lastEdited"]->getTimestamp();
        }
        return $properties;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getLink(): string
    {
        return $GLOBALS["rootURL"] . "/Cluck/?$this->url";
    }

    public function getPostDate(): int
    {
        return $this->postDate->getTimestamp();
    }

    public function getLastEdited(): ?int
    {
        if ($this->lastEdited) {
            return $this->lastEdited->getTimestamp();
        }
        return null;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getID(): int
    {
        return $this->id;
    }
}