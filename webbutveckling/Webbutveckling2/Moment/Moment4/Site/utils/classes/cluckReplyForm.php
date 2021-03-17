<?php
include_once __DIR__ . "/../config.php";
include_once __DIR__ . "/manager.php";
include_once __DIR__ . "/userProfile.php";
include_once __DIR__ . "/user.php";
include_once __DIR__ . "/form.php";
include_once __DIR__ . "/field.php";
include_once __DIR__ . "/cluck.php";


/**
 * Class CluckReplyForm
 *  constructs, validates and generates HTML for a form
 *
 * creates a new cluck but also replies the the cluck given in constructors
 */
class CluckReplyForm extends Form
{
    /**
     * alternative constructor.
     *
     * used if the ID to the cluck that will be replied to is not
     *
     * @param int $id
     * @param Manager|null $manager
     * @return CluckReplyForm
     */
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

    /**
     * validates the form.
     *
     * if the form is successfully validates a new post is made that will be linked as a reply in the database
     * the created post will be returned as a cluck object.
     *
     * if a database connection is already established form an outer scope the
     * connection can be passed thru as an argument instead of establishing a
     * new connection to the database.
     *
     * @param Manager|null $manager
     * @return Cluck|null
     */
    public function validate(Manager $manager = null): ?Cluck
    {
        if (!$this->validateFields()) {
            return null;
        }

        $user = getSessionUserProfile();
        if (!$manager) {
            $manager = new Manager();
        }
        return $manager->createCluck($user->getUserID(), $_POST["replyTitle"], $_POST["replyContent"], $_POST["cluckID"]);
    }
}