<?php


class FileManager {
    private $file;

    public function __construct(string $filename, string $mode) {
        include "config.php";
        $path = "$writeDirectory/$filename";
        if (!file_exists($path)){
            $file = fopen($path, "w");
            fwrite($file, "[]");
            fclose($file);
        }
        $this->file = fopen($path, $mode);
    }

    public function write(string $text): void {
        fwrite($this->file, $text);
    }

    public function read(): string {
        return stream_get_contents($this->file);
    }

    public function __destruct() {
        fclose($this->file);
    }
}