<?php

require_once('helpers.php');
require_once('functions/common.php');
require_once('init.php');
require_once('data.php');
require_once('sql_queries.php');

$categories = get_all_categories();

$lots = [];
$category_id = filter_input(INPUT_GET, 'id');

$url = '/category.php?id=' . $category_id;
$cur_page = $_GET['page'] ?? 1;
$page_items = 9;

$sql = "SELECT lots.*, category.title AS category_name FROM lots JOIN category ON  lots.category_id = category.id
WHERE lots.category_id = '%s' AND lots.date_of_completion > CURDATE() ";

$sql = sprintf($sql, $category_id);
$result = mysqli_query($link, $sql);

if ($result) {
    $items_count = (mysqli_num_rows($result));
    $pages_count = ceil($items_count / $page_items);
    $offset = ($cur_page - 1) * $page_items;
    $pages = range(1, $pages_count);

    $sql_lots = "SELECT lots.*, category.title AS category FROM lots "
        . "JOIN category ON lots.category_id = category.id "
        . "WHERE lots.category_id = '%s' AND lots.date_of_completion > CURDATE() "
        . "ORDER BY lots.date_create DESC LIMIT " . $page_items . " OFFSET " . $offset;

    $sql_lots = sprintf($sql_lots, $category_id);
    $result_lots = mysqli_query($link, $sql_lots);
    $lots = mysqli_fetch_all($result_lots, MYSQLI_ASSOC);
} else {
    die(mysqli_error($link));
}

$prev_page_link = !($cur_page - 1) ? '#' : $url . '&page=' . ($cur_page - 1);
$next_page_link = ((int)$cur_page === (int)$pages_count) ? '#' : $url . '&page=' . ($cur_page + 1);

$page_content = include_template('category.php', ['categories' => $categories,
    'category_name' => $lots[0]['category'] ?? '',
    'lots' => $lots,
    'pages' => $pages,
    'pages_count' => $pages_count,
    'url' => $url,
    'cur_page' => $cur_page,
    'prev_page_link' => $prev_page_link,
    'next_page_link' => $next_page_link
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Все лоты в категории ' . $lots[0]['category'] ?? '',
    'is_auth' => $is_auth,
    'user_name' => $user_name
]);
print($layout_content);
