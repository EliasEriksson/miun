<?php
include_once __DIR__ . "/htmlElement.php";

/**
 * Class Field
 * an HTML input field wrapped in a label tag and a label text wrapped in a span element
 * all tags are given classes on which a prefix can be added to determine the forms from one another
 *
 * @property string $name value of the name attribute
 * @property string $value value of the value attribute
 * @property string $type type of input, this is not necessarily the value of type
 * @property ?string $labelText the label for the input field
 * @property string $classPrefix a prefix to add in front of all auto generated classes
 * @property bool $refillOnFailedPost if true the value that was posted will be put back into the field if post failed
 */
class Field extends HTMLElement
{
    private $name;
    private $value;
    private $type;
    private $labelText;
    private $classPrefix;
    private $refillOnFailedPost;
    private $mustVerify;

    public function __construct(
        string $name,
        string $type,
        string $value = "",
        string $classPrefix = "",
        ?string $labelText = null,
        bool $refillOnFailedPost = true,
        bool $mustVerify = true)
    {
        parent::__construct($classPrefix);
        $this->name = $name;
        $this->type = $type;
        $this->value = $value;
        $this->classPrefix = $classPrefix;
        $this->labelText = $labelText ?? $name;
        $this->refillOnFailedPost = $refillOnFailedPost;
        $this->mustVerify = $mustVerify;
    }


    /**
     * wraps and returns a given html node in a <label> with a few classes
     * @param string $htmlNode the html/text to wrap around
     * @return string <label>$htmlNode</label>
     */
    private function wrapInLabel(string $htmlNode): string
    {
        $prefixedLabelClass = $this->prefixClass("label");
        $prefixedNamedLabelClass = $this->prefixClass("$this->name-label");

        return "<label class='$prefixedNamedLabelClass $prefixedLabelClass'>$htmlNode</label>";

    }

    /**
     * wraps and returns the given html node in a <span> with a few classes
     *
     * @param string $htmlNode the html/text to wrap around
     * @return string <span>$htmlNode</span>
     */
    private function wrapInSpan(string $htmlNode): string
    {
        $prefixedSpanClass = $this->prefixClass("label-text");
        $prefixedNameSpanClass = $this->prefixClass("$this->name-label-text");

        return "<span class='$prefixedNameSpanClass $prefixedSpanClass'>$htmlNode</span>";
    }

    /**
     * generates classes and separates them with a space so they
     * can be added directly to a class attribute
     *
     * @param array $extra extra classes that will not be prefixed
     * @return string all the classes with a space between
     */
    private function getFieldClasses(array $extra): string
    {
        return implode(" ", [
            $this->prefixClass("$this->name-$this->type"),
        ]);
    }

    /**
     * formats a textarea type of input with a label
     * adds a few classes to each element for styling
     *
     * @return string <label><span></span><textarea></textarea></label>
     */
    private function formatTextarea(): string
    {
        $html = $this->wrapInSpan("$this->labelText");

        $classes = $this->getFieldClasses(["textarea", "input"]);
//        $classes .= " textarea input"; // since splat operator crashes on miun

        $html .= "<textarea class='$classes' name='$this->name'>$this->value</textarea>";

        $html = $this->wrapInLabel($html);
        return $html;
    }

    /**
     * formats an unlabeled input field should only(?) be hidden and submit
     * adds a few classes to the element for styling
     *
     * @return string <input>
     */
    private function formatUnlabeledInput(): string
    {
        $classes = $this->getFieldClasses(["submit"]);
//        $classes .= " submit"; // since splat operator crashes on miun

        return "<input class='$classes' name='$this->name' value='$this->value' type='$this->type'>";
    }

    /**
     * formats an input field with <label> and <span>
     * adds classes to each element for styling
     *
     * @return string <label><span><input></span></label>
     */
    private function formatInput(): string
    {
        $html = $this->wrapInSpan("$this->labelText");

        $classes = $this->getFieldClasses(["input"]);
        $classes .= " input"; // since splat operator crashes on miun

        $html .= "<input class='$classes' name='$this->name' value='$this->value' type='$this->type'>";
        $html = $this->wrapInLabel($html);
        return $html;
    }

    /**
     * general validation of each input field
     * returns an error message so if the return value is falsy the validation was successful
     *
     * @return string|null error message
     */
    public function validateField(): ?string
    {
        if ($this->mustVerify) {
            if ($this->type == "submit" && !isset($_POST[$this->name])) {
                return "Det är va inte det formet som va submittat.";
            } elseif ($this->type == "file" && !isset($_FILES[$this->name])) {
                return ucfirst("$this->labelText kan inte vara tom.") . trim(":");
            } elseif (!(isset($_POST[$this->name]) && $_POST[$this->name])) {
                return ucfirst("$this->labelText kan inte vara tom.") . trim(":");
            }
        }
        return null;
    }

    /**
     * formats an input field depending on the type of field
     *
     * @return string an html input field for a form
     */
    public function toHTML(): string
    {
        switch ($this->type) {
            case "textarea":
                return $this->formatTextarea();
            case "hidden":
            case "submit":
                return $this->formatUnlabeledInput();
            default:
                return $this->formatInput();
        }
    }

    // getters

    /**
     * gets the name of the name value of the input field
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * gets the value of the value attribute on the input field
     * (innerHTML of <textarea>)
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * gets the type of the type attribute on the input field
     * (just textarea for <textarea>)
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * get refill on failed post, meaning the field should be filled with accessible data on a failed post
     *
     * @return bool
     */
    public function getRefillOnFailedPost(): bool
    {
        return $this->refillOnFailedPost;
    }

    /**
     * sets the value of the value attribute on the input field
     * (innerHTML for <textarea>)
     *
     * @param string $value
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
    }

}