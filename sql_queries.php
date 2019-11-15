<?php

$sqlCategory = "SELECT * FROM category";
$categories = db_fetch_data($sqlCategory, $link);
if (!$categories) {
    $error = mysqli_error($link);
    echo "Error MySQL: " . $error;
}

$sqlLots = "SELECT lots.id, lots.title, lots.starting_price, lots.image, lots.date_of_completion, category.title as category 
FROM lots JOIN category ON lots.category_id = category.id
WHERE lots.date_of_completion > CURDATE() ORDER BY lots.date_create DESC";
$lots = db_fetch_data($sqlLots, $link);
if (!$lots) {
    $error = mysqli_error($link);
    echo "Error MySQL: " . $error;
}

$sqlLot = "SELECT lots.id, lots.title, lots.starting_price, lots.image, lots.date_of_completion, lots.description, lots.bid_step, category.title as category
FROM lots JOIN category ON lots.category_id = category.id WHERE lots.id = '%s'";

$sql = "INSERT INTO lots (title, description, category_id, date_of_completion, starting_price, bid_step, image, date_create, user_id, winner_id) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), 1, 2)";
