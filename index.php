<?php

require_once('helpers.php');
require_once('functions.php');
require_once('data.php');

$page_content = include_template('main.php', ['categories' => $categories, 'goods' => $goods]);
$layout_content = include_template('layout.php', [
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'title' => 'YetiCave - Главная страница',
    'categories' => $categories,
    'content' => $page_content
]);
print($layout_content);
