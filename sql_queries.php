<?php

/*
 * $sqlCategory - выполняем запрос на получение всего списка категорий
 */
$sqlCategory = "SELECT * FROM category";

/*
 * $sqlLots - выполняем запрос на показ всех активных лотов вместе с названием категории, к которой относится лот. Сортируем по дате добавления от новых к старым
 */
$sqlLots = "SELECT lots.id, lots.title, lots.starting_price, lots.image, lots.date_of_completion, category.title as category 
FROM lots JOIN category ON lots.category_id = category.id
WHERE lots.date_of_completion > CURDATE() ORDER BY lots.date_create DESC";

/*
 * $sqlLot - выполняем запрос на показ лота из списка лотов по его идентификатору - первичному ключу
 */
$sqlLot = "SELECT lots.id, lots.title, lots.starting_price, lots.image, lots.date_of_completion, lots.description, lots.bid_step, category.title as category
FROM lots JOIN category ON lots.category_id = category.id WHERE lots.id = '%s'";

/*
 * $sql - выполняем запрос на добавление нового лота. На месте значений располагаются знаки вопроса - плейсхолдеры
 */
$sql = "INSERT INTO lots (title, description, category_id, date_of_completion, starting_price, bid_step, image, date_create, user_id, winner_id) 
                            VALUES (?, ?, ?, ?, ?, ?, ?,NOW(), ?, 2)";

/*
 * $sqlMail - выполняем запрос на поиск в таблице users пользователя с переданным email
 * $sqlSign - выполняем запрос на добавление нового пользователя в БД
 */
$sqlMail = "SELECT * FROM users WHERE email = '%s'";
$sqlSign = 'INSERT INTO users (date_of_registration, email, name, password, contacts) VALUES (NOW(), ?, ?, ?, ?)';

/*
 * $sqlCount - выполняем запрос на подсчет общего количества всех лотов
 * $sqlSearch - выполняем запрос на показ списка лотов, учитывая смещение и число лотов на странице
 */
$sqlCount = "SELECT COUNT(*) as cnt FROM lots "
    . "WHERE MATCH(title, description) AGAINST( '" . $search . "')";
$sqlSearch = "SELECT lots.id, lots.title, lots.starting_price, lots.image, lots.date_of_completion, category.title as category FROM lots "
    . "JOIN category ON lots.category_id = category.id "
    . "WHERE MATCH(lots.title, description) AGAINST(?)"
    . "ORDER BY lots.date_create DESC LIMIT " . $page_items . " OFFSET " . $offset;
