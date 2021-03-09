<?php
include_once __DIR__ . "/../utils/functions.php";

if (userLoggedIn()) {
    redirect("Profile/");
} else {
    redirect("../");
}