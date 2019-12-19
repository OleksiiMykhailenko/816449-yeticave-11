<?php

require_once('./vendor/autoload.php');
require_once('helpers.php');
require_once('functions/common.php');
require_once('init.php');
require_once('data.php');

$result = get_closed_lots($link);

if (!$result) {
    $error = "Не удалось соединится с базой данных";
    $page_content = include_template('error.php', ['error' => $error]);
}

$open_lots = mysqli_fetch_all($result, MYSQLI_ASSOC);

foreach ($open_lots as $lot) {
    $result_winner = get_rates_winner($link, $lot);

    if (!$result_winner) {
        $error = "Не удалось соединится с базой данных";
        $page_content = include_template('error.php', ['error' => $error]);
    }

    if (mysqli_num_rows($result_winner)) {
        $winner = mysqli_fetch_array($result_winner, MYSQLI_ASSOC);
        $set_winner = lot_winners($link, $winner, $lot);
    }
}
