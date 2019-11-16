<?php

date_default_timezone_set("Europe/Moscow");

function formatting_sum($sum)
{
    $sum = ceil($sum);
    if ($sum >= 1000) {
        $sum = number_format($sum, 0, ',', ' ');
    }
    return $sum . " ₽";
}

function get_dt_range($future_date)
{
    $now_date = time();
    $future_date = strtotime($future_date);
    $diff_time = $future_date - $now_date;
    $hours = intdiv($diff_time, 3600);
    $minutes = round(($diff_time - ($hours * 3600)) / 60, 0);
    $hours = str_pad($hours, 2, "0", STR_PAD_LEFT);
    $minutes = str_pad($minutes, 2, "0", STR_PAD_LEFT);
    return Array(
        'hours' => $hours,
        'minutes' => $minutes
    );
}

function db_fetch_data($sql, $link)
{
    $result = mysqli_query($link, $sql);
    if ($result) {
        $result = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    return $result;
}

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

function show_error(&$content, $error)
{
    $content = include_template('error.php', ['error' => $error]);
}

function getPostVal($name)
{
    return filter_input(INPUT_POST, $name);
}

function validateCategory($id, $allowed_list)
{
    if (!in_array($id, $allowed_list)) {
        return "Указана несуществующая категория";
    }
    return null;
}

function validateLength($value, $min, $max)
{
    if ($value) {
        $len = strlen($value);
        if ($len < $min || $len > $max) {
            return "Значение должно быть от $min до $max символов";
        }
    }
    return null;
}

function validatePrice($value)
{
    if (gettype($value) === 'integer' or 'float' && $value <= 0) {
        return "Содержимое поля начальная цена должно быть числом больше нуля";
    }
}

function validateStep($value)
{
    if (gettype($value) === 'integer' or 'float' && $value <= 0) {
        return "Содержимое поля шаг ставки должно быть целым числом больше ноля";
    }
}

function validateDate($value)
{
    $future_dt = date('Y-m-d', strtotime("+1 days"));
    if ($value < $future_dt || !is_date_valid($value)) {
        return "Дата должна быть на один день больше текущей даты, а также должна быть в формате ГГГГ-ММ-ДД";
    }
    return null;
}
