<?php
include "fileManager.php";

class ToDo
{
    private string $todo;
    private string $deadline;
    private string $title;

    public function __construct(string $title, string $todo, $deadline)
    {
        $this->title = $title;
        $this->todo = $todo;
        $this->deadline = $deadline;
    }

    public function toHTML(int $index): string
    {
        return '<div class="todo">
                    <div class="todo-title-checkbox">
                        <label><input name="$index" class="title-checkbox" type="checkbox"></label><h4>$this->title</h4>
                    </div>
                    <p>$this->todo</p>
                    <time>$this->time</time>
                </div>';
    }

    public function asAssociativeArray(): array
    {
        return array("todo" => $this->todo, "deadline" => $this->deadline);
    }
}