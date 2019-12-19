<?php

require_once('helpers.php');
require_once('functions/common.php');
require_once('init.php');
require_once('data.php');

$categories = get_all_categories($link);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form = $_POST;
    $errors = [];

    $required = ['email', 'password', 'name', 'contacts'];

    $rules = [
        'email'    => function ($value) {
            return validate_email($value);
        },
        'name'     => function ($value) {
            return validate_length($value, 10, 45);
        },
        'contacts' => function ($value) {
            return validate_length($value, 10, 128);
        },
    ];

    $fields = [
        'email'    => 'E-mail',
        'password' => 'Пароль',
        'name'     => 'Имя',
        'contacts' => 'Контактные данные',
    ];

    $errors = validate_post_data($form, $rules, $required, $fields);
    $errors = array_filter($errors);

    if (empty($errors)) {
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $email = get_user_by_email_result($link, $email);

        if (is_null($email)) {
            $password = password_hash($form['password'], PASSWORD_DEFAULT);
            $res = insert_user($link, $form, $password);
        } else {
            $errors['email'] = 'Пользователь с этим email уже зарегистрирован';
        }
    }

    if ($res && empty($errors)) {
        header("Location: login.php");
        exit();
    }
}

$page_content = include_template('sign-up.php', ['categories' => $categories, 'errors' => $errors,]);

if ($is_auth) {
    http_response_code(403);

    $page_content = include_template('error.php', ['error' => 'Вы уже зарегестрированы']);
    $page_title = 'YetiCave | 403';
}

$layout_content = include_template('layout.php', [
    'title'      => 'YetiCave | Регистрация',
    'categories' => $categories,
    'content'    => $page_content,
    'is_auth'    => $is_auth,
    'user_name'  => $user_name,
]);
print($layout_content);
