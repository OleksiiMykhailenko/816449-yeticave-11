<?php

require_once('helpers.php');
require_once('functions/common.php');
require_once('init.php');
require_once('data.php');

$categories = get_all_categories($link);

$lots = [];
$search = (string)filter_input(INPUT_GET, 'search') ?? '';
$search = mysqli_real_escape_string($link, $_GET['search']);

if (!empty($search)) {
    $cur_page = filter_input(INPUT_GET, 'page') ?? 1;
    $page_items = 9;

    $items_count = mysqli_fetch_assoc(get_lots_count_by_search($link, $search))['cnt'];

    if ($page_items > 0) {
        $pages_count = ceil($items_count / $page_items);
    }

    $offset = ($cur_page - 1) * $page_items;
    $pages = range(1, $pages_count);

    $lots = mysqli_fetch_all(get_lots_by_search($link, $search, $page_items, $offset), MYSQLI_ASSOC);

    if ($pages_count === 0) {
        $error = "Ничего не найдено по вашему запросу.";
        $page_content = include_template('error.php', ['error' => $error]);
    } else {
        $page_content = include_template('search.php', [
            'categories'  => $categories,
            'search'      => $search,
            'lots'        => $lots,
            'pages'       => $pages,
            'pages_count' => $pages_count,
            'cur_page'    => $cur_page,
        ]);
    }
}

$layout_content = include_template('layout.php', [
    'is_auth'    => $is_auth,
    'user_name'  => $user_name,
    'title'      => 'YetiCave - Результаты поиска',
    'categories' => $categories,
    'content'    => $page_content,
]);
print($layout_content);
