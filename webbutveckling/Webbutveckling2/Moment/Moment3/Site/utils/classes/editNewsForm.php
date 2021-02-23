<?php
include_once __DIR__ . "/news.php";
include_once __DIR__ . "/newsForm.php";
include_once __DIR__ . "/manager.php";


/**
 * Class EditNewsForm
 */
class EditNewsForm extends Form
{
    /**
     * EditNewsForm constructor.
     *
     * @param News $news a news object to populate the input fields with what the article currently contains
     * @param string $submit value of the submit button
     * @param string $classPrefix prefix for all the forms components css classes
     * @param array $postRefillExcludes if a failed post request occurs these fields wont be filled with the post data
     */
    public function __construct(News $news, string $submit = "submit", string $classPrefix = "", array $postRefillExcludes = [])
    {
        parent::__construct([
            "id" => ["hidden", $news->getId()],
            "titel" => ["text", $news->getTitle()],
            "ingress" => ["textarea", $news->getPreamble()],
            "artikel" => ["textarea", $news->getContent()]
        ], $submit, $classPrefix, $postRefillExcludes);
    }

    /** alternative constructor
     * the default constructor is what it is to prevent a forced query in case
     * the news object is already in the same scope as the call to the constructor
     * this alternative just requires the id and preforms the query
     *
     * @param string $id the news articles id
     * @param string $submit value of the submit button
     * @param string $classPrefix prefix for all the forms components css classes
     * @param array $postRefillExcludes if a failed post request occurs these fields wont be filled with the post data
     * @return EditNewsForm an instance of this class
     */
    public static function fromId(string $id, string $submit = "submit", string $classPrefix = "", array $postRefillExcludes = []): EditNewsForm
    {
        $manager = new Manager();
        return new EditNewsForm($manager->getNews($id), $submit, $classPrefix, $postRefillExcludes);
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
        $result = $manager->updateNews($_POST["id"], $_POST["titel"], $_POST["ingress"], $_POST["artikel"]);
        if ($result) {
            return $manager->getNews($_POST["id"]);
        }
        $this->setError("Could not update the news article.");
        return null;
    }
}