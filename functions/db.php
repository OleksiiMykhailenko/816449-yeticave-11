<?php

/**
 * Функция обработки запроса и соединения
 * @param $sql - запрос к базе данных
 * @param $link - соединение с базой данных
 * @return array|bool|mysqli_result
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
 * @param string $lotId строка с наименованием параметра запроса, получение лота по его id
 * @return string значение параметра запроса
 */
function get_lot_by_id($lotId)
{
    global $sqlLot;
    global $link;
    $sqlLot = sprintf($sqlLot, $lotId);
    $result = mysqli_query($link, $sqlLot);
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
 * Функция получения значения из параметра пост-запроса
 * @param string $name строка с наименованием параметра пост-запроса
 * @return string значение параметра пост-запроса
 */
function getPostVal($name)
{
    return filter_input(INPUT_POST, $name);
}

/**
 * Функция получения значения из параметра запроса
 * @param string $email строка с наименованием параметра запроса, поиск по email
 * @return string значение параметра пост-запроса
 */
function get_user_by_email($email)
{
    global $sqlMail;
    global $link;
    $sqlMail = sprintf($sqlMail, $email);
    $result = mysqli_query($link, $sqlMail);
    if ($result) {
        if (mysqli_num_rows($result)) {
            return mysqli_fetch_all($result, MYSQLI_ASSOC)[0];
        } else {
            return null;
        }
    }
    return null;
}

//function get_lots_by_search($link, $search)
//{
//    $sqlCount = "SELECT COUNT(*) as cnt FROM lots "
//        . "WHERE MATCH(title, description) AGAINST( '" . $search . "')";
//    $sqlCount = sprintf($sqlCount, $search);
//    $result = mysqli_query($link, $sqlCount);
//    if ($result) {
//        if (mysqli_num_rows($result)) {
//            return mysqli_fetch_all($result, MYSQLI_ASSOC)[0];
//        } else {
//            return null;
//        }
//    }
//    return null;
//}
