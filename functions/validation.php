<?php

/**
 * Функция форматирует цену
 * @param string $sum цена
 * @return string отформатированная цена
 */
function formatting_sum($sum)
{
    $sum = ceil($sum);
    if ($sum >= 1000) {
        $sum = number_format($sum, 0, ',', ' ');
    }
    return $sum . " ₽";
}

/**
 * Функция возаращает время истечения лота
 * @param string $future_date дата истечения лота
 * @return array дата и время истечения лота
 */
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

/**
 * Функция возваращает показ ошибок
 */
function show_error(&$content, $error)
{
    $content = include_template('error.php', ['error' => $error]);
}

/**
 * Функция валидации категории
 * @param string $id id переданной категории
 * @param array $allowed_list массив, из которого будут выбираться категории
 * @return string текст ошибки валидации
 */
function validateCategory($id, $allowed_list)
{
    if (!in_array($id, $allowed_list)) {
        return "Указана несуществующая категория";
    }
    return null;
}

/**
 * Функция валидации длины поля
 * @param string $value значения поля
 * @param int $min минимальная длина поля
 * @param int $max максимальная длина поля
 * @return string текст ошибки валидации
 */
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

/**
 * Функция валидации цены лота при его добавлении
 * @param string $value значения поля начальная цена
 * @return string текст ошибки валидации
 */
function validatePrice($value)
{
    if (gettype($value) === 'integer' or 'float' && $value <= 0) {
        return "Содержимое поля начальная цена должно быть числом больше нуля";
    }
}

/**
 * Функция валидации шага ставки лота
 * @param string $value значения поля шаг ставки лота
 * @return string текст ошибки валидации
 */
function validateStep($value)
{
    if (gettype($value) === 'integer' or 'float' && $value <= 0) {
        return "Содержимое поля шаг ставки должно быть целым числом больше ноля";
    }
}

/**
 * Функция валидации даты истечения лота
 * @param string $value значения поля
 * @return string текст ошибки валидации
 */
function validateDate($value)
{
    $future_dt = date('Y-m-d', strtotime("+1 days"));
    if ($value < $future_dt || !is_date_valid($value)) {
        return "Дата должна быть на один день больше текущей даты, а также должна быть в формате ГГГГ-ММ-ДД";
    }
    return null;
}

/**
 * Функция валидации данных, отправленных из формы
 * @param array $form данные из формы в пост-запросе
 * @param array $rules массив с правилами валидации
 * @param array $required массив с правилами валидации
 * @param array $fields словарь с подписями полей
 * @return array массив с ошибками валидации
 */
function validatePostData($form, $rules, $required, $fields)
{
    foreach ($form as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule($value);
        }
        if (in_array($key, $required) && empty($value)) {
            $errors[$key] = "Поле $fields[$key] надо заполнить";
        }
    }
    return $errors;
}

/**
 * Функция валидации e-mail
 * @param string $value значения поля e-mail
 * @return string текст ошибки валидации
 */
function validateEmail($value)
{
    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
        return "Введите корректный email";
    }
}
