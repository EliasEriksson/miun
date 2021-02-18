<?php
function parentDirectory(string $path): string
{
    $parts = explode("/", $path);
    return implode("/", array_splice($parts, 0, -1));
}

function getCurrentPage(): int
{
    $page = 0;
    if (count($_GET) > 0) {
        // $currentPage = array_key_first($_GET);
        // since miun is at version 7.2 not 7.4.........
        foreach ($_GET as $currentPage => $_) break;
        if (isset($currentPage)) {
            if (is_numeric($currentPage)) {
                $page = $currentPage;
            } else {
                header("location: ./");
            }
        }

    }
    return $page;
}

function getNewsID()
{
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

function validateArticleForm(): string
{
    $message = "";
    if (isset($_POST["title"]) && strlen($_POST["title"]) > 0) {
        if (isset($_POST["preamble"]) && strlen($_POST["preamble"]) > 0) {
            if (!isset($_POST["article"]) || strlen($_POST["article"]) <= 0) {
                $message = "En artikel måste ha ett innehåll.";
            }
        } else {
            $message = "En artikel måste ha en ingress";
        }
    } else {
        $message = "En artikel måste ha en titel.";
    }
    return $message;
}