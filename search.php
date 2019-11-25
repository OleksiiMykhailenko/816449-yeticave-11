<?php

require_once('helpers.php');
require_once('functions/common.php');
require_once('init.php');
require_once('data.php');
require_once('sql_queries.php');

$categories = db_fetch_data($sqlCategory, $link);

$lots = [];
$search = $_GET['search'] ?? '';
$search = mysqli_real_escape_string($link, $_GET['search']);

if ($search) {
    $cur_page = $_GET['page'] ?? 1;
    $page_items = 9;

    $result = mysqli_query($link, "SELECT COUNT(*) as cnt FROM lots "
        . "WHERE MATCH(title, description) AGAINST( '". $search ."')");

    $items_count = mysqli_fetch_assoc($result)['cnt'];
    $pages_count = ceil($items_count / $page_items);
    $offset = ($cur_page - 1) * $page_items;
    $pages = range(1, $pages_count);

    $sqlSearch = "SELECT lots.id, lots.title, lots.starting_price, lots.image, lots.date_of_completion, category.title as category FROM lots "
        . "JOIN category ON lots.category_id = category.id "
        . "WHERE MATCH(lots.title, description) AGAINST(?)"
        . "ORDER BY lots.date_create DESC LIMIT " . $page_items . " OFFSET " . $offset;
    $stmt = db_get_prepare_stmt($link, $sqlSearch, [$search]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);

    if ($pages_count <= 0) {
        $error = "Ничего не найдено по вашему запросу.";
        $page_content = include_template('error.php', ['error' => $error]);
    } else {
        $page_content = include_template('search.php', [
            'categories' => $categories,
            'search' => $search,
            'lots' => $lots,
            'pages' => $pages,
            'pages_count' => $pages_count,
            'cur_page' => $cur_page
        ]);
    }
}

$layout_content = include_template('layout.php', [
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'title' => 'YetiCave - Результаты поиска',
    'categories' => $categories,
    'content' => $page_content
]);
print($layout_content);
