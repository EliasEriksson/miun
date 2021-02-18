<?php
include_once __DIR__."/functions.php";


session_start();

if (isset($_SERVER["CONTEXT_DOCUMENT_ROOT"]) && isset($_SERVER["CONTEXT_PREFIX"])) {
    //if hosted on a server that uses those contexts
    // used for miuns server
    // the context + this file trimmed with context root + 1 directory up since this file is one deep

    $rootURL = $_SERVER["CONTEXT_DOCUMENT_ROOT"];
    if (strcmp(substr(__FILE__, 0, strlen($rootURL)), $rootURL) === 0) {
        $rootURL = parentDirectory($_SERVER["CONTEXT_PREFIX"] . dirname(substr(__FILE__, strlen($rootURL))));
    } else {
        $rootURL =  dirname($_SERVER["CONTEXT_PREFIX"]);
    }
} else {
    // use the regular root otherwise
    // used for localhost
    // this files directory trimmed with (document root - last directory) + 1 directory up since this file is one deep
    $rootURL = implode("/", array_slice(explode("/", $_SERVER["DOCUMENT_ROOT"]), 0, -1));

    if (strcmp(substr(__FILE__, 0, strlen($rootURL)), $rootURL) === 0) {
        $rootURL = parentDirectory(dirname(substr(__FILE__, strlen($rootURL))));
    } else {
        $rootURL = "";
    }
    error_reporting(-1);
    ini_set("display_errors", 1);
}

if (isset($_SERVER["CONTEXT_DOCUMENT_ROOT"])) {
    // path for writing to a file public host
    $writeDirectory = $_SERVER["CONTEXT_DOCUMENT_ROOT"] . "/writeable";
    // url to a file public host
    $writeDirectoryLink = $_SERVER["CONTEXT_PREFIX"];
} else {
    // path for writing to a file localhost
    $writeDirectory = $_SERVER["DOCUMENT_ROOT"] . "/writeable";
    // url to a file localhost
    $writeDirectoryLink = $rootURL;
}

$GLOBALS["rootURL"] = $rootURL;
$GLOBALS["writeDirectory"] = $writeDirectory;
$GLOBALS["writeDirectoryLink"] = $writeDirectoryLink;
//var_dump($_SERVER);
//echo "<br><br>";
//echo "uri: ".$_SERVER["REQUEST_URI"]."<br>";
//echo "trimmed root:".$_SERVER["REQUEST_URI"];
