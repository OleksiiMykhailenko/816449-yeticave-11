<?php

/**
 * Функция получения значения из параметра пост-запроса
 * @param string $name строка с наименованием параметра пост-запроса
 * @return string значение параметра пост-запроса
 */
function get_post_val($name)
{
    return filter_input(INPUT_POST, $name);
}


/**
 * Функция навигации по страницам
 * @param $url - Конкретный лот в своей конкретной категории
 * @param $cur_page - Конкретная страница
 * @param $pages_count - Количество страниц
 * @return array - Возврат массива значений
 */
function get_navigation_links($url, $cur_page, $pages_count)
{
    return [
        !($cur_page - 1) ? '#' : $url . '&page=' . ($cur_page - 1),
        ((int)$cur_page === (int)$pages_count) ? '#' : $url . '&page=' . ($cur_page + 1)
    ];
}

/**
 * Функция подсчета и показа лотов на странице, выполнение пагинации
 * @param $lots_count - Подсчет количества лотов
 * @param $cur_page - Конкретная страница
 * @param $page_items - Количество странци
 * @return array - Возврат массива значений
 */
function get_navigation_data($lots_count, $cur_page, $page_items)
{
    $pages_count = ceil($lots_count / $page_items);

    return [
        $pages_count,
        ($cur_page - 1) * $page_items,
        range(1, $pages_count)
    ];
}
