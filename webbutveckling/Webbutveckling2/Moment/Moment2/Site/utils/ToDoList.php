<?php
include "config.php";
include_once "fileManager.php";
include_once "ToDo.php";


class ToDoList {
    private $toDos = [];
    private $filename;

    public function __construct(string $filename){
        $this->filename = $filename;
        $manager = new FileManager($filename, "r");
        $todos = json_decode($manager->read());
        foreach ($todos as $todo) {
            array_push($this->toDos, new ToDo($todo->title, $todo->description, $todo->deadline));
        }
    }

    public function toHTML(): string{
        $html = '<form method="post" class="todos" enctype="application/x-www-form-urlencoded">';
        $html .= '<div class="remove-buttons"><input name="remove-all" class="remove-all remove-button" type="submit" value="Ta bort alla">
                  <input name="remove-selected" class="remove-selected remove-button" type="submit" value="Ta bort markerade"></div>';

        foreach ($this->toDos as $index => $toDo) {
            $html .= $toDo->toHTML($index);
        }
        $html .= '</form>';
        return $html;
    }

    public function updateFile() {
        $data = [];
        foreach ($this->toDos as $toDo) {
            array_push($data, $toDo->asAssociativeArray());
        }
        $manager = new FileManager($this->filename, "w");
        $manager->write(json_encode($data));
    }

    public function addToDo(string $title, string $description, int $deadline){
        array_push($this->toDos, new ToDo($title, $description, $deadline));
        $this->updateFile();
    }

    public function removeToDo(int $index) {
        if (isset($this->toDos[$index])) {
            array_splice($this->toDos, $index, 1);
            $this->updateFile();
        }
    }

    public function removeAll() {
        $this->toDos = [];
        $this->updateFile();
}
}