<?php
include_once __DIR__ . "/manager.php";
include_once __DIR__ . "/cluck.php";


class Reply {
    private $id;
    private $thisCluckID;
    private $replyCluckID;

    public function __construct(int $id, int $thisCluckID, int $replyCluckID)
    {
        $this->id = $id;
        $this->thisCluckID = $thisCluckID;
        $this->replyCluckID = $replyCluckID;
    }

    public function getThisCluck(): Cluck
    {
        $manager = new Manager();
        return $manager->getCluck($this->thisCluckID);
    }

    public function getReplyCluck(): Cluck
    {
        $manager = new Manager();
        return $manager->getCluck($this->replyCluckID);
    }



}