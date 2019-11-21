<?php

require_once('helpers.php');
require_once('functions.php');
require_once('init.php');
require_once('data.php');
require_once('sql_queries.php');

$lotId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$lot = get_lot_by_id($lotId);
if ($lot === null) {
    http_response_code(404);
    $page_content = include_template('404.php');
    $page_title = '404 Страница не найдена';
} else {
    $page_content = include_template('lot.php', ['categories' => $categories,
        'lot' => $lot,
        'time_report' => get_dt_range($lot['date_of_completion']),
        'is_auth' => $is_auth]);
    $page_title = $lot['title'];
}
$layout_content = include_template('layout.php', [
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'title' => $page_title,
    'categories' => $categories,
    'content' => $page_content
]);
print($layout_content);
