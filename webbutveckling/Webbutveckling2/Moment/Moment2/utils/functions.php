<?php

function getDateFromPost(){

    if (isset($_POST["year"])) {
        $year = $_POST["year"];
        unset($_POST["year"]);
    }
    if (isset($_POST["month"])) {
        $month = $_POST["month"];
        unset($_POST["month"]);
    }
    if (isset($_POST["day"])) {
        $day = $_POST["day"];
        unset($_POST["day"]);
    }
    if (isset($_POST["hour"])) {
        $hour = $_POST["hour"];
        unset($_POST["hour"]);
    }
    if (isset($_POST["minute"])) {
        $minute = $_POST["minute"];
        if (strlen($minute) === 1) {
            $minute = "0" . $minute;
        }
        unset($_POST["minute"]);
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

