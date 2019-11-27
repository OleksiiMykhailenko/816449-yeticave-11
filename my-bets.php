<?php

require_once('helpers.php');
require_once('functions/common.php');
require_once('init.php');
require_once('data.php');
require_once('sql_queries.php');

$categories = get_all_categories();

$sql_rates = <<<SQL
SELECT lots.winner_id, (SELECT users.contacts FROM users WHERE users.id = lots.winner_id) AS contacts, lots.id AS lot_id, lots.image AS lot_img, lots.date_of_completion, lots.title AS lot_name, category.title AS lot_category, rates.price, rates.date_stsrting_rate FROM rates 
JOIN lots ON lots.id = rates.lot_id JOIN category ON category.id = lots.category_id  
WHERE rates.user_id = $user_id ORDER BY rates.date_starting_rate DESC
SQL;

$result = mysqli_query($link, $sql_rates);

if ($result) {

    if (!mysqli_num_rows($result)) {
        $page_content = include_template('error.php', ['error' => 'Ставок не найдено']);
    } else {
        $rates = mysqli_fetch_all($result, MYSQLI_ASSOC);

        foreach ($rates as $key => $rate) {
            $date_of_completion = get_dt_range($rate['date_of_completion']);
            $rates[$key]['timer_class'] = '';
            $rates[$key]['timer_message'] = date_format(date_create($rate['expiry_date']), 'd.m.Y в H:i');
            if ((int)$date_of_completion[0] === 0 && !empty($date_of_completion)) {
                $rates[$key]['timer_class'] = 'timer--finishing';
                $rates[$key]['timer_message'] = implode(':', $date_of_completion);
            }
            if (date_create("now") > date_create($rate['date_of_completion'])) {
                $rates[$key]['timer_class'] = 'timer--end';
                $rates[$key]['timer_message'] = 'Торги окончены';
                $rates[$key]['rate_class'] = 'rates__item--end';
            }
            if ($rate['winner_id']) {
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
    'title' => 'Мои ставки',
    'categories' => $categories,
    'content' => $page_content
]);
print($layout_content);
