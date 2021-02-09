<?php

function getDateFromPost(){
    if (isset($_POST["year"])) {
        $year = $_POST["year"];
    }
    if (isset($_POST["month"])) {
        $month = $_POST["month"];
    }
    if (isset($_POST["day"])) {
        $day = $_POST["day"];
    }
    if (isset($_POST["hour"]) && $_POST["hour"] != "") {
        $hour = $_POST["hour"];
    } else {
        $hour = "23";
    }
    if (isset($_POST["minute"]) && $_POST["minute"] != "") {
        $minute = $_POST["minute"];
        if (strlen($minute) === 1) {
            $minute = "0" . $minute;
        }
    } else {
        $minute = "59";
    }

    if (isset($year)
    &&  isset($month)
    &&  isset($day)
    &&  isset($hour)
    &&  isset($minute)) {
        $string = $year . "-" . $month . "-" . $day . "-" . $hour . "-" . $minute;
        return DateTime::createFromFormat(
            "Y-m-d-H-i",
            $string);
    } else {
        return false;
    }
}

