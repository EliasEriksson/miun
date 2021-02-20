<?php
function parentDirectory(string $path): string
    /**
     * takes a path and removes the last part of it
     * i.e: /home/elias-eriksson/dev would become /home/elias-eriksson
     */
{
    $parts = explode("/", $path);
    return implode("/", array_splice($parts, 0, -1));
}

function getCurrentPage(): int
    /**
     * used to get the the name of the first given get parameter on pages where this
     * get parameter is assumed to be page number
     * if this is not a number the user is redirected to the current page without any get parameters
     *
     * thank you miun server for not being on version 7.2 where array_key is not available...
     * not even class attributes can be type hinted on this version but is present on version 7.4...
     */
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
    /**
     * used to get the name of the first given get parameter on pages where this get parameter
     * is assumed to be the news articles id.
     *
     * if this get parameter is not numeric the user is redirected to the news (plural) page
     */
{
    if (count($_GET) > 0) {
        foreach ($_GET as $get => $_) break;
        if (isset($get) && is_numeric($get)) {
            return $get;
        }
    }
    header("location: ../");
    // to stop IDE from complaining
    return "";
}

function validateArticleForm(): string
    /**
     * validates the POST data is valid articleForm data
     *
     * if its not valid data an error message is returned.
     * if the return value is truthy the validation failed
     */
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