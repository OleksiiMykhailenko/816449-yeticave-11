<?php

require_once('helpers.php');
require_once('functions/common.php');
require_once('init.php');
require_once('data.php');

$categories = get_all_categories($link);
$rates = [];
$result = get_user_bets($link, $user_id);

if ($result) {
    if (!mysqli_num_rows($result)) {
        $page_content = include_template('error.php', ['error' => 'Ставок не найдено']);
    } else {
        $rates = mysqli_fetch_all($result, MYSQLI_ASSOC);

        foreach ($rates as $key => $rate) {

            $date_of_completion = get_dt_range($rate['date_of_completion']);

            $rates[$key]['timer_class'] = '';
            $rates[$key]['timer_message'] = date_format(date_create($rate['date_of_completion']), 'd.m.Y в H:i');

            if (!empty($date_of_completion))  {
                $rates[$key]['timer_class'] = 'timer-finishing';
                $rates[$key]['timer_message'] = implode(':', $date_of_completion);
            }

            if (date_create('now') > date_create($rate['date_of_completion'])) {
                $rates[$key]['timer_class'] = 'timer--end';
                $rates[$key]['timer_message'] = 'Торги окончены';
                $rates[$key]['rate_class'] = 'rates__item--end';
            }

            if ($rate['is_winner']) {
                $rates[$key]['timer_class'] = 'timer--win';
                $rates[$key]['timer_message'] = 'Ваша ставка выиграла';
                $rates[$key]['rate_class'] = 'rates__item--win';
            }
        }

        $page_content = include_template('my-bets.php', ['categories' => $categories, 'rates' => $rates]);
    }
} else {
    http_response_code(404);

    $page_content = include_template('404.php');
    $page_title = '404 Страница не найдена';
}

$layout_content = include_template('layout.php', [
    'is_auth'    => $is_auth,
    'user_name'  => $user_name,
    'title'      => 'YetiCave - Мои ставки',
    'categories' => $categories,
    'content'    => $page_content,
]);
print($layout_content);
