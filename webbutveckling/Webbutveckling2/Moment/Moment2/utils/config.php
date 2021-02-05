<?php

/**
 * @var string $rootURL
 * @var string $writeDirectory
 */

//error_reporting(-1);
//ini_set("display_errors", 1);

if (isset($_SERVER["CONTEXT_DOCUMENT_ROOT"]) && isset($_SERVER["CONTEXT_PREFIX"])) {
    //if hosted on a server that uses those contexts
    // used for miuns server
    // the context + this file trimmed with context root + 1 directory up since this file is one deep
    $rootURL = $_SERVER["CONTEXT_PREFIX"] . "/" . dirname(trim(__FILE__, $_SERVER["CONTEXT_DOCUMENT_ROOT"])) . "/..";
} else {
    // use the regular root otherwise
    // used for localhost
    //  / + this files directory trimmed with (document root - last directory) + 1 directory up since this file is one deep
    $rootURL = "/" . dirname(trim(__FILE__, implode("/", array_slice(explode("/", $_SERVER["DOCUMENT_ROOT"]), 0, -1)))) . "/..";
}

if (isset($_SERVER["CONTEXT_DOCUMENT_ROOT"])) {
    $writeDirectory = $_SERVER["CONTEXT_DOCUMENT_ROOT"] . "/writeable";
} else {
    $writeDirectory = $_SERVER["DOCUMENT_ROOT"] . "/writeable";
}
