<?php

session_start();

$link = mysqli_connect("localhost", "root", "root", "YetiCave");

if (!$link) {
    $error = mysqli_error($link);
    echo "MySQL Error: " . $error;
}
mysqli_set_charset($link, "utf8");

date_default_timezone_set("Europe/Moscow");
