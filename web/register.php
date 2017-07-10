<?php

require_once '../globals.php';
require_once (MODELS . 'UsersModel.php');
$userob = new UsersModel();

$username = ''; $email = ''; $phone = ''; $password = '';
if (isset($_POST['register'])) {
    if (isset($_POST['username']) && !empty($_POST['username']) && strlen($_POST['username']) >= 5 && strlen($_POST['username']) < 50) {
        $username = filter_var(trim($_POST['username']), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        if ($userob->isFound("username", $username)) {
            error("Username can't be repeated, username isn't available.");
        }
        $data['username'] = $username;
    } else {
        error("Enter valid username.");
    }
    if (!isset($_POST['email']) || empty($_POST['email']) || strlen($_POST['email']) < 10 || strlen($_POST['email']) > 60) {
        error("Enter valid Email.");
    }
    if (filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL)) {
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        if ($userob->isFound("email", $email)) {
            error("Email can\'t be repeated, email isn\'t available.");
        }
        $data['email'] = $email;
    } else {
        error("Enter valid Email.");
    }
    if (isset($_POST['phone']) && !empty($_POST['phone']) && strlen($_POST['phone']) >= 8 && strlen($_POST['phone']) < 25) {
        $phone = intval(trim($_POST['phone']));
        $data['phone'] = $phone;
    } else {
        error("Enter valid phone number.");
    }
    if (isset($_POST['pass']) && !empty($_POST['pass']) && strlen($_POST['pass']) >= 8 && strlen($_POST['pass']) < 25 && preg_match('/[a-z].*[0-9]|[0-9].*[a-z]/', $_POST['pass']) && preg_match('/[A-Z]/', $_POST['pass'])) {
        if (isset($_POST['repass']) && !empty($_POST['repass']) && strlen($_POST['repass']) >= 8 && strlen($_POST['repass']) < 25) {
            if (strval(trim($_POST['pass'])) == strval(trim($_POST['repass']))) {
                $password = strval(trim($_POST['pass']));
                $data['password'] = sha1($password);
            } else {
                error("Password mismatch.");
            }
        } else {
            error("Confirm your password.");
        }
    } else {
        error("Enter valid password.");
    }
    if ($userob->add($data)) {
        mail($data['email'], "Successful Registeration", "Your data has been saved successfully, Please wait until the admin approve you! \r\n \r\n \r\n Regards, \r\n Connectvia Home", "From: admin@connectvia.net");
        $admin = $userob->getAdmin();
        mail($admin[0]['email'], "New Registeration", "$username has registered to get access to the home, Check the admin panel. \r\n \r\n \r\n Regards, \r\n Connectvia Home", "From: admin@connectvia.net");
        error("Your data has been saved successfully, Please wait until the admin approve you!");
    } else {
        error("Unexpectedly an error has been occured, the user hasn\'t been added.");
    }
} else {
    error();
}

function error($err = '') {
    if ($err != '')
        echo "<script>alert(\"$err\");</script>";
    System::get('tpl')->assign('error', '');
    System::get('tpl')->draw('login');
    exit();
}
