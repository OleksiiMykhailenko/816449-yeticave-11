<?php

require_once('helpers.php');
require_once('functions/common.php');
require_once('init.php');
require_once('data.php');

$categories = get_all_categories($link);

$lot_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$lot_id = mysqli_real_escape_string($link, $lot_id);
$lots = mysqli_fetch_all(get_lot($link, $lot_id), MYSQLI_ASSOC);
$result = get_lot($link, $lot_id);

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

        $sql_rates_result = get_lot_rates($link, $lot_id);

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
                $result = add_rate($link, $user_id, $lot, $form);

                if ($result) {
                    $lot_id = mysqli_insert_id($link);
                    header("Location: lot.php?id=" . $lot['id']);
                }
            }
        }

        $lot_is_open = (date_create($lot['date_of_completion']) > date_create('now'));
        $lot_of_current_user = ($lot['user_id'] === $user_id);
        $show_rate_block = ($is_auth && $lot_is_open && !$lot_of_current_user && !$last_bet_added_by_current_user);

        $page_content = include_template('lot.php', ['categories'      => $categories,
                                                     'lot'             => $lot,
                                                     'time_report'     => get_dt_range($lot['date_of_completion']),
                                                     'is_auth'         => $is_auth,
                                                     'show_rate_block' => $show_rate_block,
                                                     'errors'          => $errors,
                                                     'rates'           => $rates,
        ]);
    }
}

$layout_content = include_template('layout.php', [
    'is_auth'    => $is_auth,
    'user_name'  => $user_name,
    'title'      => $page_title,
    'categories' => $categories,
    'content'    => $page_content,
]);
print($layout_content);
