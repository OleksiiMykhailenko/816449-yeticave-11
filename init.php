<?php

require_once('functions/common.php');

session_start();

$link = connect_to_db();

if (false === $link) {
    $error = "Не удалось соединится с базой данных";
    $page_content = include_template('error.php', ['error' => $error]);
} else {
    mysqli_set_charset($link, "utf8");
}

date_default_timezone_set("Europe/Moscow");
