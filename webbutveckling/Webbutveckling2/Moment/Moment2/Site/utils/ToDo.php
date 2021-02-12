<?php
include_once "fileManager.php";

class ToDo {
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
        return '<div class="todo">
                    <h4 class="todo-title">'.$this->title.'</h4>
                    <label class="todo-checkbox-label"><input name="'.$index.'" class="todo-checkbox" type="checkbox"></label>
                    <p class="todo-description">'.$this->description.'</p>
                    <time class="todo-deadline">'.$this->deadline->setTimezone(new DateTimeZone("cet"))->format($this->dateTimeFormat).'</time>
                    <div class="todo-underline"></div>
                </div>';
    }

    public function asAssociativeArray(): array {
        return array(
            "title" => $this->title,
            "description" => $this->description,
            "deadline" => $this->deadline->getTimestamp());
    }
}