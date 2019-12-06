<?php

require_once('helpers.php');
require_once('functions/common.php');
require_once('init.php');
require_once('data.php');

$categories = get_all_categories($link);

$lot_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$sql_lot = <<<SQL
SELECT lots.id, lots.title, lots.starting_price, lots.image, lots.date_of_completion, lots.description, lots.bid_step, lots.user_id, category.title as category,
CASE 
    WHEN (SELECT MAX(price) FROM rates WHERE rates.lot_id = lots.id) > 0 THEN (SELECT MAX(price) FROM rates WHERE rates.lot_id = lots.id)
    ELSE lots.starting_price
END AS price
    FROM lots JOIN category ON lots.category_id = category.id 
    WHERE lots.id = '%s';
SQL;

$sql_lot = sprintf($sql_lot, $lot_id);
$result = mysqli_query($link, $sql_lot);

if ($result) {

    if (!mysqli_num_rows($result)) {
        http_response_code(404);
        $page_content = include_template('404.php');
        $page_title = '404 Страница не найдена';
    } else {
        $lot = mysqli_fetch_all($result, MYSQLI_ASSOC)[0];
        $min_price = $lot['starting_price'];
        $step = $lot['bid_step'];
        $rates = [];

        $sql_rates = "SELECT users.name AS user, rates.price AS price, rates.date_starting_rate AS time, users.id AS user_id FROM rates JOIN users ON rates.user_id = users.id WHERE rates.lot_id = $lot_id ORDER BY rates.date_starting_rate DESC";
        $sql_rates_result = mysqli_query($link, $sql_rates);

        if ($sql_rates_result && mysqli_num_rows($sql_rates_result)) {
            $rates = mysqli_fetch_all($sql_rates_result, MYSQLI_ASSOC);
            $last_bet_added_by_current_user = ($rates[0]['user_id'] === $user_id);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $is_auth) {
            $required = ['cost'];
            $form = $_POST;
            $rules = [
                'cost' => function ($value) use ($min_price, $step) {
                    return validate_cost($value, $min_price, $step);
                }
            ];
            $fields = ['cost' => 'Ставка'];
            $errors = validate_post_data($form, $rules, $required, $fields);

            if (!$errors['cost']) {
                $rate = [$user_id, $lot['id'], $form['cost']];
                $sql = "INSERT INTO rates (date_starting_rate, user_id, lot_id, price) VALUES (NOW(), ?, ?, ?)";
                $stmt = db_get_prepare_stmt($link, $sql, $rate);
                $res = mysqli_stmt_execute($stmt);

                if ($res) {
                    $lot_id = mysqli_insert_id($link);
                    header("Location: lot.php?id=" . $lot['id']);
                }
            }
        }

        $lot_is_open = (date_create($lot['date_of_completion']) > date_create('now'));
        $lot_of_current_user = ($lot['user_id'] === $user_id);
        $show_rate_block = ($is_auth && $lot_is_open && !$lot_of_current_user && !$last_bet_added_by_current_user);

        $page_content = include_template('lot.php', ['categories' => $categories,
            'lot' => $lot,
            'time_report' => get_dt_range($lot['date_of_completion']),
            'is_auth' => $is_auth,
            'show_rate_block' => $show_rate_block,
            'errors' => $errors,
            'rates' => $rates
        ]);
    }
}

$layout_content = include_template('layout.php', [
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'title' => $page_title,
    'categories' => $categories,
    'content' => $page_content
]);
print($layout_content);
