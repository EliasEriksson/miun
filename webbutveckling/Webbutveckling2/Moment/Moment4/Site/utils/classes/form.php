<?php
include_once __DIR__ . "/../config.php";
include_once __DIR__ . "/htmlElement.php";


abstract class Form extends HTMLElement
{
    protected $fields;
    private $error;
    private $userError;

    public function __construct(array $fields, string $classPrefix)
    {
        parent::__construct($classPrefix);
        foreach ($fields as $field) {
            if ($field->getRefillOnFailedPost()) {
                if (isset($_POST[$field->getName()])) {
                    $field->setValue($_POST[$field->getName()]);
                }
            }
        }
        $this->fields = $fields;
        $this->error = "";
        $this->userError = false;
    }

    protected function validateFields(): bool
    {
        foreach ($this->fields as $field) {
            if ($error = $field->validateField()) {
                $this->setError($error);
                return false;
            }
        }
        return true;
    }

    public abstract function validate(); //?object // too bad php7.2 is so bad it cant handle this return type when 7.4 can

    public function toHTML(): string
    {
        $class = $this->prefixClass("form");
        $enctype = "application/x-www-form-urlencoded";
        foreach ($this->fields as $field) {
            if ($field->getType() === "file") {
                $enctype = "multipart/form-data";
                break;
            }
        }
        $html = "<form class='$class' method='post' enctype='$enctype'>";

        if ($this->userError) {
            $html .= "<p class='user-error'>" . $this->getError() . "</p>";
        }

        foreach ($this->fields as $field) {
            $html .= $field->toHTML();
        }

        $html .= "</form>";
        return $html;
    }

    protected function setError(string $error, $userError = true): void
    {
        $this->error = $error;
        $this->userError = $userError;
    }

    public function getError(): string
    {
        return $this->error;
    }
}