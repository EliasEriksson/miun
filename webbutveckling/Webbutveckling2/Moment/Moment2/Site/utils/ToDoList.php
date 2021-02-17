<?php
include "config.php";
include_once "fileManager.php";
include_once "ToDo.php";


class ToDoList {
    /**
     * represents a list of to-dos
     *
     * contains an array of to-do objects and have functionality to:
     * load a to-do list from file
     * generate an HTML structure for the list and each to-do item,
     * saving the current list of to-do items to a JSON file and
     * modify the list with by adding / removing to-dos
     */
    private $toDos = [];
    private $filename;

    public function __construct(string $filename){
        /**
         * loads each to-do item and passes each to-do attribute
         * from json file down into the to-do items constructor.
         */
        $this->filename = $filename;
        $manager = new FileManager($filename, "r");
        $todos = json_decode($manager->read());
        foreach ($todos as $todo) {
            var_dump($todo);
            array_push($this->toDos, new ToDo(...$todo));
        }
    }

    public function toHTML(): string{
        /**
         * generates an HTML structure for the list and each to-do item
         */
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
        /**
         * saves the current internal array to JSON file
         */
        $data = [];
        foreach ($this->toDos as $toDo) {
            array_push($data, $toDo->asAssociativeArray());
        }
        $manager = new FileManager($this->filename, "w");
        $manager->write(json_encode($data, JSON_UNESCAPED_UNICODE));
    }

    public function addToDo(string $title, string $description, int $deadline){
        /**
         * adds a to-do item to the list and updates the JSON file.
         */
        array_push($this->toDos, new ToDo($title, $description, $deadline));
        $this->updateFile();
    }

    public function removeToDo(int $index) {
        /**
         * removes an item from the list if it exists and updates the JSON file.
         */
        if (isset($this->toDos[$index])) {
            array_splice($this->toDos, $index, 1);
            $this->updateFile();
        }
    }

    public function removeAll() {
        /**
         * removes all items from the list and updates the JSON file.
         */
        $this->toDos = [];
        $this->updateFile();
    }
}