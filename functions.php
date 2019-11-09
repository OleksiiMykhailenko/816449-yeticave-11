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
