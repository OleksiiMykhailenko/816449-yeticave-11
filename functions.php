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

function down_counter($future_date)
{
    $now_date = time();
    $future_date = strtotime($future_date);
    $diff_time = $future_date - $now_date;
    return Array(
        'hours' => intdiv($diff_time, 3600),
        'minutes' => round(($diff_time - (intdiv($diff_time, 3600) * 3600)) / 60, 0)
    );
}
