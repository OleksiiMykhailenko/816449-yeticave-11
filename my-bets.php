<?php

require_once('helpers.php');
require_once('functions/common.php');
require_once('init.php');
require_once('data.php');

$categories = get_all_categories($link);

$sql_rates = "SELECT rates.date_starting_rate, rates.price, rates.is_winner, rates.lot_id, lots.title AS lot_name, lots.image AS lot_img, lots.date_of_completion, category.title AS lot_category, (SELECT users.contacts FROM users WHERE users.id = lots.user_id) AS contacts FROM rates
LEFT JOIN lots
ON rates.lot_id = lots.id
LEFT JOIN category 
ON lots.category_id = category.id
LEFT JOIN users 
ON rates.user_id = users.id
WHERE rates.user_id = $user_id
ORDER BY rates.id DESC";

$result = mysqli_query($link, $sql_rates);

if ($result) {

    if (!mysqli_num_rows($result)) {
        $page_content = include_template('error.php', ['error' => 'Ставок не найдено']);
    } else {
        $rates = mysqli_fetch_all($result, MYSQLI_ASSOC);

        foreach ($rates as $key => $rate) {
            $date_of_completion = get_dt_range($rate['date_of_completion']);
            $rates[$key]['timer_class'] = '';
            $rates[$key]['timer_message'] = date_format(date_create($rate['date_of_completion']), 'd.m.Y в H:i');
            if ((int)$date_of_completion[0] === 0 && !empty($date_of_completion)) {
                $rates[$key]['timer_class'] = 'timer--finishing';
                $rates[$key]['timer_message'] = implode(':', $date_of_completion);
                $rates[$key]['rate_class'] = 'rates__item';
            }
            if (date_create("now") > date_create($rate['date_of_completion'])) {
                $rates[$key]['timer_class'] = 'timer--end';
                $rates[$key]['timer_message'] = 'Торги окончены';
                $rates[$key]['rate_class'] = 'rates__item--end';
            }
            if ($rate['is_winner']){
                $rates[$key]['timer_class'] = 'timer--win';
                $rates[$key]['timer_message'] = 'Ваша ставка выиграла';
                $rates[$key]['rate_class'] = 'rates__item--win';
            }
        }
        $page_content = include_template('my-bets.php', ['categories' => $categories,
            'rates' => $rates]);
    }
} else {
    http_response_code(404);
    $page_content = include_template('404.php');
    $page_title = '404 Страница не найдена';
}

$layout_content = include_template('layout.php', [
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'title' => 'YetiCave - Мои ставки',
    'categories' => $categories,
    'content' => $page_content
]);
print($layout_content);
