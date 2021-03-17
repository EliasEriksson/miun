<?php
include_once __DIR__ . "/../utils/functions.php";

if (userLoggedIn()) {
    // if the user somehow got here and is logged in they must have tried t access their own profile
    redirect("Profile/");
} else {
    // if they are not logged in they are lost and are redirected to home
    redirect("../");
}