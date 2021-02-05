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
        foreach ($this->toDos as $toDo) {

        }
    }

    public function updateFile() {
        $data = [];
        foreach ($this->toDos as $toDo) {
            array_push($data, $toDo->asAssociativeArray());
        }
        $manager = new FileManager($this->filename, "w");
        $manager->write(json_encode($data));
    }

    public function addToDo(string $title, string $toDo, $deadline){
        array_push($this->toDos, new ToDo($title, $toDo, $deadline));
        $this->updateFile();
    }
    public function removeToDo(int $index) {
        if (isset($this->toDos[$index])) {
            echo "was here";
            unset($this->toDos[$index]);
            $this->updateFile();
        }
    }
}