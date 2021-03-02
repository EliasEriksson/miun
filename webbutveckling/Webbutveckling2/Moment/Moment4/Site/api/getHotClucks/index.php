<?php
include_once __DIR__ . "/../../utils/config.php";
include_once __DIR__ . "/../../utils/classes/manager.php";

if (!isset($_GET["page"])) {
    http_response_code(400);
}

$manager = new Manager();
$clucks = $manager->getHotClucks($_GET["page"]);

$data = [];
foreach ($clucks as $cluck) {
    array_push($data, $cluck->getAssoc());
}

$json = json_encode($data, JSON_UNESCAPED_UNICODE);
echo $json;

