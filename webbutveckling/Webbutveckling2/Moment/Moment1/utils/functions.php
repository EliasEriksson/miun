<?php
function readFileContent($filename) {
    $file = fopen("$filename", "r");
    $content = fread($file, filesize($filename));
    fclose($file);
    return $content;
}