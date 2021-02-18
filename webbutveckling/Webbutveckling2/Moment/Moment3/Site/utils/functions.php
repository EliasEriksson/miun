<?php
function parentDirectory(string $path): string {
    $parts = explode("/", $path);
    return implode("/", array_splice($parts,0, -1));
}

function getCurrentPage(): int {
    if(count($_GET) > 0) {
        // $currentPage = array_key_first($_GET);
        // since miun is at version 7.2 not 7.4.........
        foreach ($_GET as $currentPage => $_) break;
        if (isset($currentPage) && !is_numeric($currentPage)) {
            header("location: ./");
        } else {
            $currentPage = 0;
        }
    } else {
        $currentPage = 0;
    }
    return $currentPage;
}

function getNewsID() {
    if (count($_GET) > 0) {
        foreach ($_GET as $get => $_) break;
        if (isset($get)) {
            return $get;
        }
    }
    header("location: ../");
    // to stop IDE from complaining
    return "";
}
