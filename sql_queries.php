<?php

///*
// * $sqlCategory - выполняем запрос на получение всего списка категорий
// */
//$sqlCategory = "SELECT * FROM category";
//
///*
// * $sqlLots - выполняем запрос на показ всех активных лотов вместе с названием категории, к которой относится лот. Сортируем по дате добавления от новых к старым
// */
//$sqlLots = "SELECT lots.id, lots.title, lots.starting_price, lots.image, lots.date_of_completion, category.title as category
//FROM lots JOIN category ON lots.category_id = category.id
//WHERE lots.date_of_completion > CURDATE() ORDER BY lots.date_create DESC";

/*
 * $sql_lot - выполняем запрос на показ лота из списка лотов по его идентификатору - первичному ключу
 */
$sql_lot = "SELECT lots.id, lots.title, lots.starting_price, lots.image, lots.date_of_completion, lots.description, lots.bid_step, category.title as category
FROM lots JOIN category ON lots.category_id = category.id WHERE lots.id = '%s'";

/*
 * $sql - выполняем запрос на добавление нового лота. На месте значений располагаются знаки вопроса - плейсхолдеры
 */
$sql = 'INSERT INTO lots (date_create, user_id, title, description, starting_price, bid_step, date_of_completion, category_id, image)
        VALUES (NOW(), ?, ?, ?, ?, ?, ?, ?, ?)';
/*
 * $sql_mail - выполняем запрос на поиск в таблице users пользователя с переданным email
 * $sql_sign - выполняем запрос на добавление нового пользователя в БД
 */
$sql_mail = "SELECT * FROM users WHERE email = '%s'";
$sql_sign = 'INSERT INTO users (date_of_registration, email, name, password, contacts) VALUES (NOW(), ?, ?, ?, ?)';

/*
 * $sql_count - выполняем запрос на подсчет общего количества всех лотов
 * $sql_search - выполняем запрос на показ списка лотов, учитывая смещение и число лотов на странице
 */
$sql_count = "SELECT COUNT(*) as cnt FROM lots "
    . "WHERE MATCH(title, description) AGAINST( '" . $search . "')";
$sql_search = "SELECT lots.id, lots.title, lots.starting_price, lots.image, lots.date_of_completion, category.title as category FROM lots "
    . "JOIN category ON lots.category_id = category.id "
    . "WHERE MATCH(lots.title, description) AGAINST(?)"
    . "ORDER BY lots.date_create DESC LIMIT " . $page_items . " OFFSET " . $offset;
