<?php


class FileManager {
    /**
     * Manages opening and closing of files as well as reading and writing to them.
     */
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
        /**
         * $text to $file opened in write mode.
         *
         * will throw error if mode is not compatible.
         */
        fwrite($this->file, $text);
    }

    public function read(): string {
        /**
         * reads and returns the content from $file.
         *
         * will throw error if mode is not compatible .
         */
        return stream_get_contents($this->file);
    }

    public function __destruct() {
        /**
         * closes the file when the object goes out of scope and gets garbage collected.
         */
        fclose($this->file);
    }
}