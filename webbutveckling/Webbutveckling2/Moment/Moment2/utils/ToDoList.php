<?php
include "config.php";
include "ToDo.php";

class ToDoList {
    private array $toDos = [];
    private string $filename;

    public function __construct(string $filename){
        $this->filename = $filename;
        $manager = new FileManager($filename, "r");
        $todos = json_decode($manager->read());
        foreach ($todos as $todo) {
            array_push($this->toDos, new ToDo($todo->title, $todo->todo, $todo->deadline));
        }
    }

    public function toHTML(){
        $html = '<form method="post" class="todos" enctype="application/x-www-form-urlencoded">';
        foreach ($this->toDos as $index => $toDo) {
            $html .= $toDo->toHTML($index);
        }
        $html .= '<input type="submit" value="Remove selected"></form>';
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

    public function addToDo(string $title, string $toDo, int $deadline){
        array_push($this->toDos, new ToDo($title, $toDo, $deadline));
        $this->updateFile();
    }
    public function removeToDo(int $index) {
        if (isset($this->toDos[$index])) {
            unset($this->toDos[$index]);
            $this->updateFile();
        }
    }
}