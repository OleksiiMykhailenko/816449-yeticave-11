<?php

require_once('./vendor/autoload.php');

function send_email_to_winner($winner, $lot)
{
    $transport = new Swift_SmtpTransport('phpdemo.ru', 25);
    $transport->setUsername('keks@phpdemo.ru');
    $transport->setPassword('htmlacademy');
    $message = new Swift_Message('Ваша ставка победила');
    $message->setTo([$winner[0]['email'] => $winner[0]['name']]);
    $message->setBody(include_template('email.php', [
        'user_name' => $winner[0]['name'],
        'lot_id'    => $lot['id'],
        'lot_title' => $lot['title'],
        'host'      => $_SERVER['HTTP_HOST'],
    ]), 'text/html');
    $message->setFrom('keks@phpdemo.ru');
    $mailer = new Swift_Mailer($transport);
    $mailer->send($message);
}
