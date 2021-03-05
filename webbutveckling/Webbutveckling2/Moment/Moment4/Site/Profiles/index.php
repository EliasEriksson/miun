<?php
include_once __DIR__ . "/../utils/functions.php";

if (isset($_SESSION["user"])) {
    redirect("Profile/");
} else {
    redirect("../");
}