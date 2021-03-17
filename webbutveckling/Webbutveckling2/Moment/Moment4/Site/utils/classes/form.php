<?php
include_once __DIR__ . "/../config.php";
include_once __DIR__ . "/htmlElement.php";


/**
 * Class Form
 *
 * class to represent required functionality of a form
 */
abstract class Form extends HTMLElement
{
    protected $fields;
    private $error;
    private $userError;
    private $submit;

    public function __construct(array $fields, Field $submit, string $classPrefix)
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
        $this->submit = $submit;
        $this->error = "";
        $this->userError = false;
    }

    /**
     * validates each field in the form
     *
     * true if all fields validated
     *
     * @return bool
     */
    protected function validateFields(): bool
    {
        if ($error = $this->submit->validateField()) {
            $this->setError("wrong form", false);
            return false;
        }
        foreach ($this->fields as $field) {
            if ($error = $field->validateField()) {
                $this->setError($error);
                return false;
            }
        }
        return true;
    }

    /**
     * abstract method that concrete child classes have to implement
     *
     * @return mixed
     */
    public abstract function validate(); //?object // too bad php7.2 is so bad it cant handle this return type when 7.4 can

    /**
     * generates teh HTML representation of the form
     *
     * @return string
     */
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



        foreach ($this->fields as $field) {
            $html .= $field->toHTML();
        }
        if ($this->userError) {
            $class = $this->prefixClass("user-error");
            $html .= "<p class='$class'>" . $this->getError() . "</p>";
        }
        $html .= $this->submit->toHTML();

        $html .= "</form>";
        return $html;
    }

    /**
     * sets an error
     *
     * if an error is a userError the $error will be shown to the user in the HTML
     *
     * @param string $error
     * @param bool $userError
     */
    protected function setError(string $error, $userError = true): void
    {
        $this->error = $error;
        $this->userError = $userError;
    }

    /**
     * gets the error.
     *
     * @return string
     */
    public function getError(): string
    {
        return trim($this->error, ":");
    }
}