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
        mysqli_query($link, $sql_winner_update);
    }

    $sql_lots_update = "UPDATE lots SET lots.is_closed = 1 WHERE lots.date_of_completion < CURDATE() AND lots.is_closed = 0";
    mysqli_query($link, $sql_lots_update);
}

/**
 * Функция поиска емейл в базе данных
 * @param $link - Соединение с базой данных
 * @param $email - Искомое значение
 * @return |null - Возврат результата в случае ненахождения
 */
function get_user_by_email($link, $email) {
    $sql = "SELECT * FROM users WHERE email = '%s'";
    $sql = sprintf($sql, $email);

    $result = mysqli_query($link, $sql);

    if ($result) {
        if (mysqli_num_rows($result)) {
            return mysqli_fetch_all($result, MYSQLI_ASSOC)[0];
        }
    }

    return null;
}

/**
 * Функция нахождения емайл, в случае совпадения по емайл - возврат нулевого результата
 * @param $link - Соединение с базой данных
 * @param $email - Искомое значение
 * @return |null - Возврат результата в случае нахождения
 */
function get_user_by_email_result($link, $email)
{
    $result = get_user_by_email($link, $email);
    if ($result) {
        if (count($result) > 0) {
            return $result;
        }
    }

    return null;
}
