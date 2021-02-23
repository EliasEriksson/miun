<?php
include_once __DIR__ . "/news.php";
include_once __DIR__ . "/newsForm.php";
include_once __DIR__ . "/manager.php";


class EditNewsForm extends Form
{
    public function __construct(News $news, string $submit = "submit", string $classPrefix = "", array $postRefillExcludes = [])
    {
        parent::__construct([
            "id" => ["hidden", $news->getId()],
            "titel" => ["text", $news->getTitle()],
            "ingress" => ["textarea", $news->getPreamble()],
            "artikel" => ["textarea", $news->getContent()]
        ], $submit, $classPrefix, $postRefillExcludes);
    }

    public static function fromId(string $id, string $submit = "submit", string $classPrefix = "", array $postRefillExcludes = []): EditNewsForm
    {
        $manager = new Manager();
        return new EditNewsForm($manager->getNews($id), $submit, $classPrefix, $postRefillExcludes);
    }

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