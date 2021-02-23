<?php
include_once __DIR__ . "/admin.php";


abstract class Form
{
    protected $fields;
    protected $submit;
    private $classPrefix;
    private $error;
    private $userError;

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

    protected function addValues(array $fields, bool $overwriteExisting = false)
    {
        foreach ($fields as $field => $value) {
            if ($overwriteExisting && $this->fields[$field][1]) {
                continue;
            }
            $this->fields[$field][1] = $value;
        }
    }

    private function generateClass($class): string
    {
        $class = strtolower($class);
        if ($this->classPrefix) {
            return "$this->classPrefix-$class";
        }
        return $class;
    }

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

    public abstract function validate();//:?object //nope cause php 7.2 sucks

    public function toHTML(): string
    {
        $html = "";

        if ($this->submit) {
            $class = $this->generateClass("form");
            $html .= "<form method='post' enctype='application/x-www-form-urlencoded' class='$class'>";
        }

        if ($this->userError) {
            $html .= "<p class='user-error'>" . $this->getError() . "</p>";
        }

        foreach ($this->fields as $field => [$type, $value]) {
            $class = $this->generateClass($field);
            $labelClass = $this->generateClass("$field-label");
            $labelText = ucfirst($field);
            if ($type === "textarea") {
                $html .= "<label class='$labelClass'><span class='label-text $labelClass-text'>$labelText:</span><textarea class='$class $type' name='$field'>$value</textarea></label>";
            } elseif ($type === "hidden") {
                $html .= "<input class='$class input' name='$field' type='$type' value='$value'>";
            } else {
                $html .= "<label class='$labelClass'><span class='label-text $labelClass-text'>$labelText:</span><input class='$class input' name='$field' type='$type' value='$value'></label>";
            }
        }

        $class = $this->generateClass($this->submit);
        if ($this->submit) {
            $html .= "<input class='$class submit' name='$this->submit' type='submit' value='$this->submit'>";
        }

        if ($this->submit) {
            $html .= "</form>";
        }
        return $html;
    }

    protected function setError(string $error, $userError = true)
    {
        $this->error = $error;
        $this->userError = $userError;
    }

    public function getError(): string
    {
        return $this->error;
    }
}

