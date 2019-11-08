<?php

$sqlCategory = "SELECT * FROM category";
$categories = db_fetch_data($sqlCategory, $link);
if (!$categories) {
    $error = mysqli_error($link);
    echo "Error MySQL: " . $error;
}

$sqlGoods = "SELECT lots.title, lots.starting_price, lots.image, lots.date_of_completion, category.title as category 
FROM lots JOIN category ON lots.category_id = category.id
WHERE lots.date_create <= CURDATE() ORDER BY lots.date_create DESC";
$goods = db_fetch_data($sqlGoods, $link);
if (!$goods) {
    $error = mysqli_error($link);
    echo "Error MySQL: " . $error;
}
