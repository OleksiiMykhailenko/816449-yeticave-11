<?php

require_once('helpers.php');
require_once('functions/common.php');
require_once('init.php');
require_once('data.php');
require_once('sql_queries.php');

$categories = get_all_categories($link);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form = $_POST;

    $required = ['email', 'password'];
    $errors = [];

    foreach ($required as $field) {
        if (empty($form[$field])) {
            $errors[$field] = 'Это поле надо заполнить';
        }
    }

    $errors = array_filter($errors);

    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $email = get_user_by_email($email);
    $res = mysqli_query($link, $sql_mail);
    $user = $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;

    if (!count($errors) and $user) {

        if (password_verify($form['password'], $user['password'])) {
            $_SESSION['user'] = $user;
        } else {
            $errors['password'] = 'Неверный пароль';
        }
    } else {
        $errors['email'] = 'Такой пользователь не найден';
    }

    if (count($errors)) {
        $page_content = include_template('login.php', ['form' => $form, 'errors' => $errors]);
    } else {
        header("Location: /index.php");
        exit();
    }
} else {
    $page_content = include_template('login.php', []);

    if (isset($_SESSION['user'])) {
        header("Location: /index.php");
        exit();
    }
}

$layout_content = include_template('layout.php', [
    'title' => 'YetiCave',
    'categories' => $categories,
    'content' => $page_content,
    'is_auth' => $is_auth,
    'user_name' => $user_name
]);
print($layout_content);
