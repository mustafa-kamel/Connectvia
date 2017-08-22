<?php

require_once '../globals.php';
if (isset($_SESSION['username']) && $_SESSION['is_approved'] == 1)
    System::RedirectTo('index.php');

require_once (MODELS . 'UsersModel.php');
$userob = new UsersModel();

if (isset($_POST['login'])) {
    $email = ''; $password = '';
    if (!isset($_POST['email']) || empty($_POST['email']) || strlen($_POST['email']) < 10 || strlen($_POST['email']) > 60) {
        draw('No data sent, enter valid data!');
    }
    if (filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL)) {
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    } else {
        draw('Invalid email');
    }
    if (isset($_POST['password']) && !empty($_POST['password']) && strlen($_POST['password']) >= 8 && strlen($_POST['password']) < 25) {
        $password = strval(trim($_POST['password']));
    } else {
        draw('No password sent');
    }
    if ($userob->webLogin($email, sha1($password))) {
        $userData = $userob->getUserInfo();
        $_SESSION['username'] = $userData['username'];
        $_SESSION['is_approved'] = $userData['is_approved'];
        $_SESSION['is_admin'] = $userData['is_admin'];
        System::RedirectTo('index.php');
    } else {
        draw('Invalid data, You can\'t have access');
    }
} else {
    draw();
}

function draw($err = '') {
    System::get('tpl')->assign('error', $err);
    System::get('tpl')->draw('login');
}
