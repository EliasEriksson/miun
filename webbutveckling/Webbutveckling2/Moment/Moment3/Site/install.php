<?php
include_once __DIR__ . "/utils/classes/manager.php";

// OBS does nto use miuns database server.
$manager = new Manager();
$manager->installDatabase();
