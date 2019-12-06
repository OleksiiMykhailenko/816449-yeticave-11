<?php

require_once('helpers.php');
require_once('functions/common.php');
require_once('init.php');
require_once('data.php');

$categories = get_all_categories($link);

$lots = [];

$category_id = (int)filter_input(INPUT_GET, 'id');

$category = get_category_by_id($link, $category_id);

if (null === $category) {
    http_response_code(404);
    $page_content = include_template('404.php');
    $page_title = '404 Страница не найдена';
} else {
    $page_title = 'Все лоты в категории ' . ($category['title'] ?? '');
    $url = '/category.php?id' . $category_id;
    $cur_page = (int)($_GET['page'] ?? 1);
    $page_items = 9;
    $lots_count = get_active_lots_count_by_category_id($link, $category_id);

    list($pages_count, $offset, $navigation_pages) = get_navigation_data($lots_count, $cur_page, $page_items);

    if ($lots_count) {
        $lots = get_active_lots_by_category_id($link, $category_id, $page_items, $offset);
    }

    list($prev_page_link, $next_page_link) = get_navigation_links($url, $cur_page, $pages_count);

    $page_content = include_template('category.php', ['categories' => $categories,
        'category_name' => $category['title'],
        'lots' => $lots,
        'pages' => $navigation_pages,
        'pages_count' => $pages_count,
        'url' => $url,
        'cur_page' => $cur_page,
        'prev_page_link' => $prev_page_link,
        'next_page_link' => $next_page_link
    ]);
}

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => $page_title,
    'is_auth' => $is_auth,
    'user_name' => $user_name
]);
print($layout_content);
