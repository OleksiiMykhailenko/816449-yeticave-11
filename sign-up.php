<?php

require_once('helpers.php');
require_once('functions/common.php');
require_once('init.php');
require_once('data.php');
require_once('sql_queries.php');

$categories = get_all_categories();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form = $_POST;
    $errors = [];

    $required = ['email', 'password', 'name', 'contacts'];

    $rules = [
        'email' => function ($value) {
            return validate_email($value);
        }
    ];

    $fields = [
        'email' => 'E-mail',
        'password' => 'Пароль',
        'name' => 'Имя',
        'contacts' => 'Контактные данные',
    ];

    $errors = validate_post_data($form, $rules, $required, $fields);
    $errors = array_filter($errors);

    if (empty($errors)) {
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $email = get_user_by_email($email);
        $res = mysqli_query($link, $sql_mail);

        if (mysqli_num_rows($res) > 0) {
            $errors['email'] = 'Пользователь с этим email уже зарегистрирован';
        } else {
            $password = password_hash($form['password'], PASSWORD_DEFAULT);
            $stmt = db_get_prepare_stmt($link, $sql_sign, [$form['email'], $form['name'], $password, $form['contacts']]);
            $res = mysqli_stmt_execute($stmt);
        }

        if (null !== $res && empty($errors)) {
            header("Location: login.php");
            exit();
        }
    }
}

$page_content = include_template('sign-up.php', ['categories' => $categories, 'errors' => $errors,]);

if ($is_auth) {
    http_response_code(403);
    $page_content = include_template('error.php', ['error' => 'Вы уже зарегестрированы']);
    $page_title = 'YetiCave | 403';
}

$layout_content = include_template('layout.php', [
    'title' => 'YetiCave | Регистрация',
    'categories' => $categories,
    'content' => $page_content,
    'is_auth' => $is_auth,
    'user_name' => $user_name
]);
print($layout_content);
