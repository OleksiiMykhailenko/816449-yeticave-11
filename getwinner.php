<?php

require_once('./vendor/autoload.php');
require_once('helpers.php');
require_once('functions/common.php');
require_once('init.php');
require_once('data.php');


$sql_open_lots = "SELECT lots.id, lots.title FROM lots WHERE lots.date_of_completion <= CURDATE() AND lots.is_closed = 1";
$result = mysqli_query($link, $sql_open_lots);

if (!$result) {
    die(mysqli_error($link));
}
$open_lots = mysqli_fetch_all($result, MYSQLI_ASSOC);

foreach ($open_lots as $lot) {
    $sql_winner = "SELECT rates.user_id, users.name, users.email FROM rates JOIN users ON rates.user_id = users.id WHERE rates.lot_id = " . $lot['id'] . " ORDER BY rates.date_starting_rate DESC LIMIT 1";
    $result_winner = mysqli_query($link, $sql_winner);

    if (!$result_winner) {
        die(mysqli_error($link));
    }

    if (mysqli_num_rows($result_winner)) {
        $winner = mysqli_fetch_all($result_winner, MYSQLI_ASSOC);

        $set_winner = mysqli_query($link, 'UPDATE lots SET winner_id = ' . $winner[0]['user_id']
            . ' WHERE lots.id = ' . $lot['id']);

        if ($set_winner) {
            $transport = new Swift_SmtpTransport('phpdemo.ru', 25);
            $transport->setUsername('keks@phpdemo.ru');
            $transport->setPassword('htmlacademy');
            $message = new Swift_Message('Ваша ставка победила');
            $message->setTo([$winner[0]['email'] => $winner[0]['name']]);
            $message->setBody(include_template('email.php', [
                'user_name' => $winner[0]['name'],
                'lot_id' => $lot['id'],
                'lot_title' => $lot['title'],
                'host' => $_SERVER['HTTP_HOST']
            ]), 'text/html');
            $message->setFrom('keks@phpdemo.ru');
            $mailer = new Swift_Mailer($transport);
            $mailer->send($message);
        }
    }
}
