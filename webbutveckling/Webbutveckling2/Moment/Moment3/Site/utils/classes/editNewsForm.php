<?php
include_once __DIR__ . "/news.php";
include_once __DIR__ . "/newsForm.php";
include_once __DIR__ . "/manager.php";
include_once __DIR__ . "/field.php";


/**
 * Class EditNewsForm
 */
class EditNewsForm extends Form
{
    /**
     * EditNewsForm constructor.
     *
     * @param News $news a news object to populate the input fields with what the article currently contains
     * @param string $classPrefix prefix for all the forms components css classes
     */
    public function __construct(News $news, string $classPrefix = "")
    {
        parent::__construct([
            new Field("id", "hidden", $news->getId(), $classPrefix),
            new Field("title", "text", $news->getTitle(), $classPrefix, "Titel"),
            new Field("preamble", "textarea", $news->getPreamble(), $classPrefix, "Ingress"),
            new Field("article", "textarea", $news->getContent(), $classPrefix, "Artikel"),
            new Field("update", "submit", "Uppdatera", $classPrefix)
        ], $classPrefix);
    }

    /** alternative constructor
     * the default constructor is what it is to prevent a forced query in case
     * the news object is already in the same scope as the call to the constructor
     * this alternative just requires the id and preforms the query
     *
     * @param string $id the news articles id
     * @param string $classPrefix prefix for all the forms components css classes
     * @return EditNewsForm an instance of this class
     */
    public static function fromId(string $id, string $classPrefix = ""): EditNewsForm
    {
        $manager = new Manager();
        return new EditNewsForm($manager->getNews($id), $classPrefix);
    }

    /**
     * validates the user input and attempts to update the news article
     *
     * @return News|null News if successful, null if user input error
     */
    public function validate(): ?News
    {
        if (!$this->validateFields()) {
            return null;
        }

        $manager = new Manager();
        $result = $manager->updateNews($_POST["id"], $_POST["title"], $_POST["preamble"], $_POST["article"]);
        if ($result) {
            return $manager->getNews($_POST["id"]);
        }
        $this->setError("Could not update the news article.");
        return null;
    }
}