<?php
include_once __DIR__ . "/form.php";
include_once __DIR__ . "/news.php";
include_once __DIR__ . "/manager.php";


class NewsForm extends Form
{
    public function __construct(string $submit = "submit", string $classPrefix = "")
    {
        parent::__construct([
            "titel" => "text",
            "ingress" => "textarea",
            "artikel" => "textarea"
        ], $submit, $classPrefix);
    }

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