<?php

/**
 * @return false|mysqli - соединение с базой данных
 */
function connect_to_db()
{
    return mysqli_connect("localhost", "root", "root", "YetiCave");;
}

/**
 * Функция обработки запроса и соединения
 * @param $sql - запрос к базе данных
 * @param $link - соединение с базой данных
 * @return array|bool|mysqli_result - возвращаем массив значений
 */
function db_fetch_data($sql, $link)
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
function get_all_categories()
{
    $sql = "SELECT * FROM category";

    return db_fetch_data($sql, connect_to_db());
}

/**
 * @return array|bool|mysqli_result - выполнение запроса на получение всех лотов, обработка запроса
 */
function get_all_lots()
{
    $sql = "SELECT lots.id, lots.title, lots.starting_price, lots.image, lots.date_of_completion, lots.bid_step, category.title as category 
FROM lots JOIN category ON lots.category_id = category.id
WHERE lots.date_of_completion > CURDATE() ORDER BY lots.date_create DESC";

    return db_fetch_data($sql, connect_to_db());
}

function fill_lot_winners()
{
    $sql = "SELECT lots.id FROM lots WHERE lots.date_of_completion < CURDATE() AND lots.is_closed = '0' ";
    $lots = mysqli_query(connect_to_db(), $sql);
    $lots = mysqli_fetch_all($lots, MYSQLI_ASSOC);
    foreach ($lots as $key => $value) {
        $id = $value['id'];
        $sql_winner_update = "UPDATE rates SET rates.is_winner = 1 WHERE rates.lot_id = '$id' ORDER BY rates.price DESC LIMIT 1";
        $sql_winner = mysqli_query(connect_to_db(), $sql_winner_update);
    }
    $sql_lots_update = "UPDATE lots SET lots.is_closed = 1 WHERE lots.date_of_completion < CURDATE() AND lots.is_closed = 0";
    $sql_closed = mysqli_query(connect_to_db(), $sql_lots_update);
}
