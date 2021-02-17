<?php
function parentDirectory(string $path): string {
    $parts = explode("/", $path);
    return implode("/", array_splice($parts,0, -1));
}
