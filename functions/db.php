<?php

/**
 * @return false|mysqli - соединение с базой данных
 */
function connect_to_db()
{
    return mysqli_connect("localhost", "root", "root", "YetiCave");
}

/**
 * Функция обработки запроса и соединения
 * @param $sql - запрос к базе данных
 * @param $link - соединение с базой данных
 * @return array|bool|mysqli_result - возвращаем массив значений
 */
function db_fetch_data($link, $sql)
{
    $result = mysqli_query($link, $sql);
    if ($result) {
        $result = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    return $result;
}

/**
 * Функция получения значения из параметра запроса
 * @param string $lot_id строка с наименованием параметра запроса, получение лота по его id
 * @return string значение параметра запроса
 */
function get_lot_by_id($lot_id)
{
    global $sql_lot;
    global $link;
    $sql_lot = sprintf($sql_lot, $lot_id);
    $result = mysqli_query($link, $sql_lot);
    if ($result) {
        if (mysqli_num_rows($result)) {
            return mysqli_fetch_all($result, MYSQLI_ASSOC)[0];
        } else {
            return null;
        }
    }
    return null;
}

/**
 * Функция получения значения из параметра запроса
 * @param string $email строка с наименованием параметра запроса, поиск по email
 * @return string значение параметра пост-запроса
 */
function get_user_by_email($email)
{
    global $sql_mail;
    global $link;
    $sql_mail = sprintf($sql_mail, $email);
    $result = mysqli_query($link, $sql_mail);
    if ($result) {
        if (mysqli_num_rows($result)) {
            return mysqli_fetch_all($result, MYSQLI_ASSOC)[0];
        } else {
            return null;
        }
    }
    return null;
}

/**
 * @return array|bool|mysqli_result - выполнение запроса на получение всех категорий, обработка запроса
 */
function get_all_categories($link)
{
    $sql = "SELECT * FROM category";

    return db_fetch_data($link, $sql);
}

/**
 * @return array|bool|mysqli_result - выполнение запроса на получение всех лотов, обработка запроса
 */
function get_all_lots($link)
{
    $sql = "SELECT lots.id, lots.title, lots.starting_price, lots.image, lots.date_of_completion, lots.bid_step, category.title as category 
FROM lots JOIN category ON lots.category_id = category.id
WHERE lots.date_of_completion > CURDATE() ORDER BY lots.date_create DESC";

    return db_fetch_data($link, $sql);
}

/**
 * Функция получения конкретной категории
 * @param $link - Соединение с базой данных
 * @param $id - Получение по id конкретного лота
 * Обработка запроса
 * @return |null Возврат значения null если значение из запроса не было получено
 */
function get_category_by_id($link, $id)
{
    $sql = "SELECT * FROM category WHERE id = ?";
    $stmt = db_get_prepare_stmt($link, $sql, [$id]);

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (false !== $result) {
        $result = mysqli_fetch_all($result, MYSQLI_ASSOC);

        if (!empty($result[0])) {
            return $result[0];
        }
    }

    return null;
}

/**
 * Функция получения и подсчета всех активных лотов в конкретной категории по id
 * @param $link - Соединение с базой данных
 * @param $id - Получение по id конкретного лота
 * Обработка запроса
 * Получение массива значений
 * @return int Возврат значения и преведение его к конкретному типу
 */
function get_active_lots_count_by_category_id($link, $id)
{
    $sql = "SELECT COUNT(id) as count FROM LOTS WHERE category_id = %d AND lots.date_of_completion > CURDATE()";
    $sql = sprintf($sql, $id);

    $result = mysqli_query($link, $sql);

    if (false !== $result) {
        $result = mysqli_fetch_all($result, MYSQLI_ASSOC);

        if (is_array($result) && !empty($result[0]['count'])) {
            return $result[0]['count'];
        }
    }

    return 0;
}

/**
 * Функция выборки и показа лотов, которые относятся к конкретной категории
 * @param $link - Соединение с базой данных
 * @param $id - id лота который относится к конкретной категории
 * @param $limit - Лимит показа лотов в конкретной категории
 * @param $offset - - Смещение относительно начала получаемого списка в ситуации
 * Обработка запроса
 * @return array|bool|mysqli_result Получение массива значений
 */
function get_active_lots_by_category_id($link, $id, $limit, $offset)
{
    $sql = "SELECT lots.*, category.title AS category FROM lots JOIN category ON lots.category_id = category.id 
            WHERE lots.category_id = '%d' 
            AND lots.date_of_completion > CURDATE() 
            ORDER BY lots.date_create DESC 
            LIMIT %d OFFSET %d";

    $sql = sprintf($sql, $id, $limit, $offset);

    $result = mysqli_query($link, $sql);

    if (false !== $result) {
        $result = mysqli_fetch_all($result, MYSQLI_ASSOC);

        return $result;
    }

    return [];
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

/**
 * Функция выполнения запроса на получение id всех лотов, у которых дата окончания лота < текущей, а также поле is_closed = 0
 * Обработка данного запроса
 * Получение массива
 * Выполнение запроса обновления поля is_winner в таблице ставок, установка значения 1, с условием предыдущего запроса
 * Обработка данного запроса
 * Выполнение запроса на обновление поля is_closed в таблице лотов, установка значения 1 где ранее был 0, где дата окончания лота < текущей
 */
function fill_lot_winners($link)
{
    $sql = "SELECT lots.id FROM lots WHERE lots.date_of_completion < CURDATE() AND lots.is_closed = '0' ";
    $lots = mysqli_query($link, $sql);
    $lots = mysqli_fetch_all($lots, MYSQLI_ASSOC);

    foreach ($lots as $key => $value) {
        $id = $value['id'];
        $sql_winner_update = "UPDATE rates SET rates.is_winner = 1 WHERE rates.lot_id = '$id' ORDER BY rates.price DESC LIMIT 1";
        $sql_winner = mysqli_query($link, $sql_winner_update);
    }

    $sql_lots_update = "UPDATE lots SET lots.is_closed = 1 WHERE lots.date_of_completion < CURDATE() AND lots.is_closed = 0";
    $sql_closed = mysqli_query($link, $sql_lots_update);
}
