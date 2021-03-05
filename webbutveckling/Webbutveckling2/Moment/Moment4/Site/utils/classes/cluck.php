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
            $this->lastEdited = DateTime::createFromFormat($format, $lastEdited);
        } else {
            $this->lastEdited = null;
        }
    }

    public function getUser(): User
    {
        $manager = new Manager();
        return $manager->getUser($this->userID);
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
        return $GLOBALS["rootURL"] . "/$this->url";
    }
}