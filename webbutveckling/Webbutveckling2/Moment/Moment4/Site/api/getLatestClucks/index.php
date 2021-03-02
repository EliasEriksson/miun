<?php
include_once __DIR__ . "/../../utils/config.php";
include_once __DIR__ . "/../../utils/classes/manager.php";

if (!$_GET["page"]) {
    http_response_code(400);
}

$manager = new Manager();
$json = json_encode($manager->getLatestClucks($_GET["page"]));
echo $json;