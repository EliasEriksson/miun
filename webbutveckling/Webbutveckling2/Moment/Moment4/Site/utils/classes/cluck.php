<?php
include_once __DIR__ . "/../config.php";
include_once __DIR__ . "/manager.php";
include_once __DIR__ . "/userProfile.php";
include_once __DIR__ . "/user.php";

class Cluck
{
    private $id;
    private $userID;
    private $title;
    private $content;
    private $url;
    private $postDate;
    private $lastEdited;


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

    public function getTitle(): string
    {
        return $this->title;
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