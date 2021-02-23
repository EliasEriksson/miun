<?php
include_once __DIR__ . "/admin.php";


/**
 * Class Form
 * abstract class for base functionality of a form
 * @property array $fields array[string=>[string, string]] name, type and optional value of an input
 * @property string $submit value of the submit button
 * @property string $classPrefix prefix for all the forms components css classes
 * @property array $postRefillExcludes if a failed post request occurs these fields wont be filled with the post data
 * @property string $error an error message, if falsy there is no error
 * @property bool $userError true if the error should be displayed for the user
 */
abstract class Form
{
    protected $fields;
    protected $submit;
    private $classPrefix;
    private $error;
    private $userError;

    /**
     * Form constructor.
     * @param array $fields array[string=>[string, string]] name, type and optional value of an input
     * @param string $submit value of the submit button
     * @param string $classPrefix prefix for all the forms components css classes
     * @param array $postRefillExcludes if a failed post request occurs these fields wont be filled with the post data
     */
    public function __construct(array $fields, string $submit = "submit", string $classPrefix = "", array $postRefillExcludes = [])
    {
        $this->fields = [];
        foreach ($fields as $field => $data) {
            if (is_array($data)) {
                if (!in_array($field, $postRefillExcludes)) {
                    $data[1] = $_POST[$field] ?? $data[1];
                }
                [$type, $value] = $data;
            } else {
                $type = $data;
                if (!in_array($field, $postRefillExcludes)) {
                    $value = $_POST[$field] ?? "";
                } else {
                    $value = "";
                }
            }
            $this->fields[$field] = [$type, $value];
        }

        $this->submit = $submit;
        $this->classPrefix = $classPrefix;
        $this->error = "";
        $this->userError = false;
    }

    /**
     * used to fill $fields with values for the input,
     * can be used to overwrite given post data if $overwriteExisting is set to true
     * @param array $fields array[string=>[string, string]] name, type and optional value of an input
     * @param bool $overwriteExisting overwrites an already set value
     */
    protected function addValues(array $fields, bool $overwriteExisting = false)
    {
        foreach ($fields as $field => $value) {
            if ($overwriteExisting && $this->fields[$field][1]) {
                continue;
            }
            $this->fields[$field][1] = $value;
        }
    }

    /**
     * adds the prefix to a given class
     * @param string $class css class that should potentially be prefixed
     * @return string css class to be used for some component
     */
    private function prefixClass(string $class): string
    {
        $class = strtolower($class);
        if ($this->classPrefix) {
            return "$this->classPrefix-$class";
        }
        return $class;
    }

    /**
     * checks if this was the submitted form and if all the fields are set in $_POST
     * @return bool true if no errors where found
     */
    protected function validateFields(): bool
    {
        if ($this->submit && !isset($_POST[$this->submit])) {
            $this->setError("Det här formet va inte det formet som va submittat.", false);
            return false;
        }
        foreach ($this->fields as $field => $_) {
            if (!(isset($_POST[$field]) && $_POST[$field])) {
                $this->setError(ucfirst("$field kan inte vara tom."));
                return false;
            }
        }
        return true;
    }

    /**
     * specialized validation method for child form
     * @return mixed (?object) should return some instance of a class to indicate success else null
     */
    public abstract function validate();//:?object //nope cause php 7.2 sucks

    /**
     * generates an HTML representation of a form
     * @return string form in HTML
     */
    public function toHTML(): string
    {
        $html = "";

        if ($this->submit) {
            $class = $this->prefixClass("form");
            $html .= "<form method='post' enctype='application/x-www-form-urlencoded' class='$class'>";
        }

        if ($this->userError) {
            $html .= "<p class='user-error'>" . $this->getError() . "</p>";
        }

        foreach ($this->fields as $field => [$type, $value]) {
            $class = $this->prefixClass($field);
            $labelClass = $this->prefixClass("$field-label");
            $labelText = ucfirst($field);
            if ($type === "textarea") {
                $html .= "<label class='$labelClass'><span class='label-text $labelClass-text'>$labelText:</span><textarea class='$class $type' name='$field'>$value</textarea></label>";
            } elseif ($type === "hidden") {
                $html .= "<input class='$class input' name='$field' type='$type' value='$value'>";
            } else {
                $html .= "<label class='$labelClass'><span class='label-text $labelClass-text'>$labelText:</span><input class='$class input' name='$field' type='$type' value='$value'></label>";
            }
        }

        $class = $this->prefixClass($this->submit);
        if ($this->submit) {
            $html .= "<input class='$class submit' name='$this->submit' type='submit' value='$this->submit'>";
        }

        if ($this->submit) {
            $html .= "</form>";
        }
        return $html;
    }

    /**
     * sets an error that can be generated by toHTML
     * @param string $error the error message
     * @param bool $userError if the user should see it
     */
    protected function setError(string $error, $userError = true)
    {
        $this->error = $error;
        $this->userError = $userError;
    }

    // getters
    /**
     * @return string
     */
    public function getError(): string
    {
        return $this->error;
    }
}

