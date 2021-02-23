<?php
include_once __DIR__ . "/form.php";
include_once __DIR__ . "/news.php";
include_once __DIR__ . "/manager.php";


/**
 * Class NewsForm
 */
class NewsForm extends Form
{
    /**
     * NewsForm constructor.
     *
     * @param string $submit value of the submit button
     * @param string $classPrefix prefix for all the forms components css classes
     */
    public function __construct(string $submit = "submit", string $classPrefix = "")
    {
        parent::__construct([
            "titel" => "text",
            "ingress" => "textarea",
            "artikel" => "textarea"
        ], $submit, $classPrefix);
    }

    /**
     * validates the user input and attempts to create a new news article
     *
     * @return News|null News if the news article was created, null if user input
     */
    public function validate(): ?News
    {
        if (!$this->validateFields()) {
            return null;
        }
        $manager = new Manager();
        $news = $manager->addNews($_POST["titel"], $_POST["ingress"], $_POST["artikel"]);
        if ($news) {
            return $news;
        }
        $this->setError("Hur lyckades du med detta???");
        return null;
    }
}