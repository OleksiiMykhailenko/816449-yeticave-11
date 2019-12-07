<?php

require_once('helpers.php');
require_once('functions/common.php');
require_once('init.php');
require_once('data.php');
require_once('getwinner.php');

fill_lot_winners($link);

$categories = get_all_categories($link);

$lots = get_all_lots($link);

$page_content = include_template('main.php', ['categories' => $categories, 'lots' => $lots]);

$layout_content = include_template('layout.php', [
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'title' => 'YetiCave - Главная страница',
    'categories' => $categories,
    'content' => $page_content
]);
print($layout_content);
