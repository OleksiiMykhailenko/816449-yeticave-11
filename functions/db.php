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
 * Функция выполнения запроса на получение всех категорий
 * @param $link - соединение с базой данных
 * @return array|bool|mysqli_result - выполнение запроса на получение всех категорий, обработка запроса
 */
function get_all_categories($link)
{
    $sql = "SELECT * FROM category";

    return db_fetch_data($link, $sql);
}

/**
 * Функция выполнение запроса на получение всех лотов
 * @param $link - соединение с базой данных
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
 * @return array|null Возврат результата в случае ненахождения
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
 * @param $link - Соединение с базой данных
 * Обработка данного запроса
 * Получение массива
 * Выполнение запроса обновления поля is_winner в таблице ставок, установка значения 1, с условием предыдущего запроса
 * Обработка данного запроса
 * Выполнение запроса на обновление поля is_closed в таблице лотов, установка значения 1 где ранее был 0, где дата окончания лота < текущей
 */
function fill_lot_winners($link)
{
    $sql = "SELECT lots.id FROM lots WHERE lots.date_of_completion <= CURDATE() AND lots.is_closed = 0";
    $lots = mysqli_query($link, $sql);
    $lots = mysqli_fetch_all($lots, MYSQLI_ASSOC);

    foreach ($lots as $key => $value) {
        $id = $value['id'];
        $sql_winner_update = "UPDATE rates SET rates.is_winner = 1 WHERE rates.lot_id = ? ORDER BY rates.price DESC LIMIT 1";
        $stmt = db_get_prepare_stmt($link, $sql_winner_update, [$id]);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_get_result($stmt);
    }

    $sql_lots_update = "UPDATE lots SET lots.is_closed = 1 WHERE lots.date_of_completion <= CURDATE() AND lots.is_closed = 0";
    mysqli_query($link, $sql_lots_update);
}

/**
 * Функция поиска емейл в базе данных
 * @param $link - Соединение с базой данных
 * @param $email - Искомое значение
 * @return array|null Возврат результата в случае ненахождения
 */
function get_user_by_email($link, $email)
{
    $sql = "SELECT * FROM users WHERE email = '%s'";
    $sql = sprintf($sql, $email);

    $result = mysqli_query($link, $sql);

    if ($result) {
        if (mysqli_num_rows($result)) {
            return (mysqli_fetch_array($result, MYSQLI_ASSOC));
        }
    }

    return null;
}

/**
 * Функция нахождения емайл, в случае совпадения по емайл - возврат нулевого результата
 * @param $link - Соединение с базой данных
 * @param $email - Искомое значение
 * @return array|null Возврат результата в случае ненахождения
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

/**
 * Функция ыполняет подготовленный запрос к бд на получение количества лотов,
 * которые соответствуют написанному в поисковой строке
 * @param $link mysqli Ресурс соединения
 * @param string $search - искомое значение
 * @return false|mysqli_result объект mysqli_result
 */
function get_lots_count_by_search($link, $search)
{
    $sql = "SELECT COUNT(*) as cnt FROM lots "
        . "WHERE MATCH(title, description) AGAINST(?) AND lots.date_of_completion > NOW()";
    $stmt = db_get_prepare_stmt($link, $sql, [$search]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    return $result;
}

/**
 * Функция выполняет подготовленный запрос к бд на получение количества лотов,
 * которые соответствуют написанному в поисковой строке
 * @param $link mysqli Ресурс соединения
 * @param string $search - Искомое значение
 * @param int $page_items - количество лотов на страницу
 * @param int $offset - смещение
 * @return false|mysqli_result
 */
function get_lots_by_search($link, $search, $page_items, $offset)
{
    $sql_search = "SELECT lots.id, lots.title, lots.starting_price, lots.image, lots.date_of_completion, category.title as category FROM lots "
        . "JOIN category ON lots.category_id = category.id "
        . "WHERE MATCH(lots.title, description) AGAINST(?) AND lots.date_of_completion > NOW() "
        . "ORDER BY lots.date_create DESC LIMIT ? OFFSET ?";
    $stmt = db_get_prepare_stmt($link, $sql_search, [$search, $page_items, $offset]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    return $result;
}

/**
 * Функция выполняет запрос в бд на добавление нового пользователя с указанными данными
 * @param $link mysqli Ресурс соединения
 * @param array $form - заполнение значений из формы
 * @param string $password - пароль пользователя
 * @return bool объект mysqli_result
 */
function insert_user($link, $form, $password)
{
    $sql = 'INSERT INTO users (date_of_registration, email, name, password, contacts) VALUES (NOW(), ?, ?, ?, ?)';
    $stmt = db_get_prepare_stmt($link, $sql, [$form['email'], $form['name'], $password, $form['contacts']]);
    $result = mysqli_stmt_execute($stmt);

    return $result;
}

/**
 * Функция выполняет подготовленный запрос к бд на список ставок
 * залогиненного пользователя
 * @param $link mysqli Ресурс соединения
 * @param string $user_id - Экранированное значение ID залогиненного пользователя
 * @return false|mysqli_result объект mysqli_result
 */
function get_user_bets($link, $user_id)
{
    $sql = "SELECT rates.date_starting_rate, rates.price, rates.is_winner, rates.lot_id, lots.title AS lot_name, 
                lots.image AS lot_img, lots.date_of_completion, category.title AS lot_category, 
                (SELECT users.contacts FROM users WHERE users.id = lots.user_id) AS contacts FROM rates
            LEFT JOIN lots
            ON rates.lot_id = lots.id
            LEFT JOIN category 
            ON lots.category_id = category.id
            LEFT JOIN users 
            ON rates.user_id = users.id
            WHERE rates.user_id = ?
            ORDER BY rates.id DESC";
    $stmt = db_get_prepare_stmt($link, $sql, [$user_id]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    return $result;
}

/**
 * Функция выполняет запрос к бд на поиск данных пользователя с заданным email
 * @param $link mysqli Ресурс соединения
 * @param string $email email пользователя
 * @return bool|mysqli_result объект mysqli_result
 */
function get_user_by_email_login($link, $email)
{
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = db_get_prepare_stmt($link, $sql, [$email]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    return $result;
}

/**
 * Функция выполняет запрос к бд на получение страницы лота
 * @param $link mysqli Ресурс соединения
 * @param string $lot_id Экранированный ID лота
 * @return bool|mysqli_result объект mysqli_result
 */
function get_lot($link, $lot_id)
{
    $sql = <<<SQL
SELECT lots.id, lots.title, lots.starting_price, lots.image, lots.date_of_completion, lots.description, lots.bid_step, lots.user_id, category.title as category,
CASE 
    WHEN (SELECT MAX(price) FROM rates WHERE rates.lot_id = lots.id) > 0 THEN (SELECT MAX(price) FROM rates WHERE rates.lot_id = lots.id)
    ELSE lots.starting_price
END AS price
    FROM lots JOIN category ON lots.category_id = category.id 
    WHERE lots.id = ?;
SQL;
    $stmt = db_get_prepare_stmt($link, $sql, [$lot_id]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    return $result;
}

/**
 * Функция выполняет запрос к бд на получение ставок лота по его ID
 * @param $link mysqli Ресурс соединения
 * @param string $lot_id Экранированный ID лота
 * @return bool|mysqli_result объект mysqli_result
 */
function get_lot_rates($link, $lot_id)
{
    $sql_rates = "SELECT users.name AS user, rates.price AS price, rates.date_starting_rate AS time, users.id AS user_id FROM rates 
                  JOIN users ON rates.user_id = users.id WHERE rates.lot_id = ? ORDER BY rates.date_starting_rate DESC";
    $stmt = db_get_prepare_stmt($link, $sql_rates, [$lot_id]);
    mysqli_stmt_execute($stmt);
    $sql_rates_result = mysqli_stmt_get_result($stmt);

    return $sql_rates_result;
}

/**
 * Функция выполняет подготовленный запрос к бд на публикацию ставки
 * @param $link mysqli Ресурс соединения
 * @param int $user_id ID залогиненного пользователя
 * @param int $lot ID лота
 * @param array $form значение ставки из формы
 * @return bool|mysqli_result объект mysqli_result
 */
function add_rate($link, $user_id, $lot, $form)
{
    $rate = [$user_id, $lot['id'], $form['cost']];
    $sql = "INSERT INTO rates (date_starting_rate, user_id, lot_id, price) VALUES (NOW(), ?, ?, ?)";
    $stmt = db_get_prepare_stmt($link, $sql, $rate);
    $result = mysqli_stmt_execute($stmt);

    return $result;
}

/**
 * Функция выполняет подготовленный запрос к бд на публикацию лота
 * @param $link mysqli Ресурс соединения
 * @param array $lot массив из полей формы
 * @return bool|mysqli_result объект mysqli_result
 */
function add_lot($link, $lot)
{
    $sql = 'INSERT INTO lots (date_create, title, description, category_id, date_of_completion, starting_price, bid_step, image, user_id) 
                VALUES (NOW(), ?, ?, ?, ?, ?, ?, ?, ?)';
    $stmt = db_get_prepare_stmt($link, $sql, $lot);
    $result = mysqli_stmt_execute($stmt);

    return $result;
}

/**
 * Функция получения лотов, у которых вышло время публикации
 * @param $link mysqli Ресурс соединения
 * @return bool|mysqli_result объект mysqli_result
 */
function get_closed_lots($link)
{
    $sql_open_lots = "SELECT lots.id, lots.title FROM lots WHERE lots.date_of_completion <= CURDATE() AND lots.is_closed = 1";
    $result = mysqli_query($link, $sql_open_lots);

    return $result;
}

/**
 * Функция выборки ставок по ID лота
 * @param $link mysqli Ресурс соединения
 * @param int $lot ID лота
 * @return false|mysqli_result объект mysqli_result
 */
function get_rates_winner($link, $lot)
{
    $sql_winner = "SELECT rates.user_id, users.name, users.email FROM rates JOIN users ON rates.user_id = users.id WHERE rates.lot_id = ? ORDER BY rates.date_starting_rate DESC LIMIT 1";
    $stmt = db_get_prepare_stmt($link, $sql_winner, [$lot['id']]);
    mysqli_stmt_execute($stmt);
    $result_winner = mysqli_stmt_get_result($stmt);

    return $result_winner;
}

/**
 * Функция обновления поля победителя, установка значения
 * @param $link mysqli Ресурс соединения
 * @param array $winner ID победителя
 * @param int $lot ID лота
 * @return bool|mysqli_result объект mysqli_result
 */
function lot_winners($link, $winner, $lot)
{
    $sql = 'UPDATE lots SET winner_id = ' . $winner[0]['user_id'] . ' WHERE lots.id = ' . $lot['id'];
    $set_winner = mysqli_query($link, $sql);

    return $set_winner;
}
