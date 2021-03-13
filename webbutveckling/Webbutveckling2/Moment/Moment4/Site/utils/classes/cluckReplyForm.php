<?php
include_once __DIR__ . "/../config.php";
include_once __DIR__ . "/manager.php";
include_once __DIR__ . "/userProfile.php";
include_once __DIR__ . "/user.php";
include_once __DIR__ . "/form.php";
include_once __DIR__ . "/field.php";
include_once __DIR__ . "/cluck.php";


class CluckReplyForm extends Form
{
    public static function fromID(int $id, Manager $manager = null): CluckReplyForm
    {
        if (!$manager) {
            $manager = new Manager();
        }
        return new CluckReplyForm($manager->getCluck($id));
    }

    public function __construct(Cluck $cluck, string $classPrefix = "general")
    {
        parent::__construct([
            new Field("cluckID", "hidden", $cluck->getID()),
            new Field("replyTitle", "text", "", $classPrefix, "Titel:"),
            new Field("replyContent", "textarea", "", $classPrefix, "Ditt svar:"),
        ], new Field("reply", "submit", "Svara", $classPrefix), $classPrefix);
    }

    public function validate(): ?Cluck
    {
        if (!$this->validateFields()) {
            return null;
        }

        $user = getSessionUserProfile();
        $manager = new Manager();
        return $manager->createCluck($user->getUserID(), $_POST["replyTitle"], $_POST["replyContent"], $_POST["cluckID"]);
    }
}