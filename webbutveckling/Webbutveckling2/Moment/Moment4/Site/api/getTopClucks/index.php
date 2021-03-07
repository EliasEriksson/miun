<?php
include_once __DIR__ . "/../../utils/config.php";
include_once __DIR__ . "/../../utils/functions.php";
include_once __DIR__ . "/../../utils/classes/manager.php";

if (!isset($_GET["page"])) {
    http_response_code(400);
}

$manager = new Manager();
$clucks = $manager->getTopClucks($_GET["page"]);
$data = extendClucks($clucks);

echo json_encode($data, JSON_UNESCAPED_UNICODE);