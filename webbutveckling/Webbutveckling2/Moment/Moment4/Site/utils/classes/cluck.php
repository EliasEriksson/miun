<?php
include_once __DIR__ . "/manager.php";
include_once __DIR__ . "/userProfile.php";

class Cluck
{
    private $id;
    private $userID;
    private $content;
    private $postDate;
    private $lastEdited;

    public static function fromAssoc(array $cluckData): Cluck
    {
        return new Cluck(
            $cluckData["id"],
            $cluckData["userID"],
            $cluckData["content"],
            $cluckData["postDate"],
            $cluckData["lastEdited"]
        );
    }

    public function __construct(
        int $id,
        int $userID,
        string $content,
        string $postDate,
        ?string $lastEdited)
    {
        $format = "Y-m-d H:i:s";

        $this->id = $id;
        $this->userID = $userID;
        $this->content = $content;
        $this->postDate = DateTime::createFromFormat($format, $postDate);
        if ($lastEdited) {
            $this->lastEdited = DateTime::createFromFormat($format, $lastEdited);
        } else {
            $this->lastEdited = null;
        }
    }

    public function getUserProfile(): UserProfile
    {
        $manager = new Manager();
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
}