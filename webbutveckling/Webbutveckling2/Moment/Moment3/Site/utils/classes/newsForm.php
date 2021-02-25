<?php
include_once __DIR__ . "/form.php";
include_once __DIR__ . "/news.php";
include_once __DIR__ . "/manager.php";
include_once __DIR__ . "/field.php";


/**
 * Class NewsForm
 */
class NewsForm extends Form
{
    /**
     * NewsForm constructor.
     *
     * @param string $classPrefix prefix for all the forms components css classes
     */
    public function __construct(string $classPrefix = "")
    {
        parent::__construct([
            new Field("title", "text", "", $classPrefix, "Titel"),
            new Field("preamble", "textarea", "", $classPrefix, "Ingress"),
            new Field("article", "textarea", "", $classPrefix, "Artikel"),
            new Field("publish", "submit", "Publicera", $classPrefix)
        ], $classPrefix);
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
        $news = $manager->addNews($_POST["title"], $_POST["preamble"], $_POST["article"]);
        if ($news) {
            return $news;
        }
        $this->setError("Hur lyckades du med detta???");
        return null;
    }
}