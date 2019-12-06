<?php

session_start();

$link = connect_to_db();

if (false === $link) {
    $error = mysqli_error($link);
    echo "MySQL Error: " . $error;
} else {
    mysqli_set_charset($link, "utf8");
}

date_default_timezone_set("Europe/Moscow");
