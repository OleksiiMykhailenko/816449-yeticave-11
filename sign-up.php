<?php

require_once('helpers.php');
require_once('functions.php');
require_once('data.php');
require_once('init.php');
require_once('sql_queries.php');

$result = mysqli_query($link, $sqlCategory);
$cats_ids = [];
if ($result) {
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $cats_ids = array_column($categories, 'id');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form = $_POST;
    $errors = [];

    $required = ['email', 'password', 'name', 'contacts'];

    $rules = [
        'email' => function ($value) {
            return validateEmail($value);
        }
    ];

    $sign = filter_input_array(INPUT_POST, ['email' => FILTER_DEFAULT, 'password' => FILTER_DEFAULT,
        'name' => FILTER_DEFAULT, 'contacts' => FILTER_DEFAULT], true);

    $fields = [
        'email' => 'E-mail',
        'password' => 'Пароль',
        'name' => 'Имя',
        'contacts' => 'Контактные данные',
    ];

    $errors = validatePostData($form, $rules, $required, $fields);
    $errors = array_filter($errors);

    if (empty($errors)) {
        $email = mysqli_real_escape_string($link, $form['email']);
        $res = mysqli_query($link, $sqlMail);

        if (mysqli_num_rows($res) > 0) {
            $errors['email'] = 'Пользователь с этим email уже зарегистрирован';
        } else {
            $password = password_hash($form['password'], PASSWORD_DEFAULT);
            $stmt = db_get_prepare_stmt($link, $sqlSign, $sign);
            $res = mysqli_stmt_execute($stmt);
        }
        if ($res && empty($errors)) {
            header("Location: /pages/login.html");
            exit();
        }
    }
    $page_content = include_template('sign-up.php', ['categories' => $categories, 'errors' => $errors,]);
} else {
    $page_content = include_template('sign-up.php', ['categories' => $categories]);
}

$layout_content = include_template('layout.php', [
    'title' => 'YetiCave | Регистрация',
    'categories' => $categories,
    'content' => $page_content
]);
print($layout_content);
