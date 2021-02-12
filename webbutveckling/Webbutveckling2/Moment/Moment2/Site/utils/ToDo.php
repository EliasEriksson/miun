<?php
include_once "fileManager.php";

class ToDo {
    /**
     * represents a to-do.
     *
     * contains data needed for a to-do and have functionality to generate the HTML for a to-do.
     */
    private $description;
    private $deadline;
    private $title;
    private $dateTimeFormat;

    public function __construct(string $title, string $description, int $deadline) {
        $this->title = $title;
        $this->description = $description;
        $this->deadline = new DateTime("@$deadline");
        $this->dateTimeFormat = "Y-m-d H:i";
    }

    public function toHTML(int $index): string {
        /**
         * generates an HTML representation of the to-do
         */
        return '<div class="todo">
                    <h4 class="todo-title">'.$this->title.'</h4>
                    <label class="todo-checkbox-label"><input name="'.$index.'" class="todo-checkbox" type="checkbox"></label>
                    <p class="todo-description">'.$this->description.'</p>
                    <time class="todo-deadline">'.$this->deadline->setTimezone(new DateTimeZone("cet"))->format($this->dateTimeFormat).'</time>
                    <div class="todo-underline"></div>
                </div>';
    }

    public function asAssociativeArray(): array {
        /**
         * returns data required to reconstruct the object
         *
         * used for saving to JSON
         */
        return array(
            "title" => $this->title,
            "description" => $this->description,
            "deadline" => $this->deadline->getTimestamp());
    }
}