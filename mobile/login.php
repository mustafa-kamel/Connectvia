<?php

require_once '../globals.php';
require_once '../includes/models/UsersModel.php';
$userob = new UsersModel;
/**
 * This script includes loging in user for mobile user after some validation
 * first get contents then check if the used method is POST, if condition is satisfied check if the data isn't empty, if satisfied
 * intialize the data array that holds the values and the individual variables, then check each variable of the recieved data for
 * dataType and special chars and remove unsuitable chars, if any of the fields is empty or unsuitable call the (respond fcn)
 * to respond to the user with suitable message. if the data is convenient and there is username or email and password and key
 * push it in the data array then check loging in using data stored in the data array, if logged in successfully respond with token,
 * else respond with suitable message (invalid data)
 * @param string $username
 * @param string $password
 * @param string $key the firebase token for the user [generated in android app]
 * @return string message in json if error occured or data is inconvenient
 * @return string token in json if logged in successfully without any errors
 */
$json = file_get_contents('php://input');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST) && !empty($json)) {
        $data = array();
        $username = ''; $email = ''; $password = ''; $ftoken='';
        $user = json_decode($json);

        if (isset($user->password)) {
            $pass = strval($user->password);
            (isset($pass) && !empty($pass) && strlen($pass) < 25) ? $password = $pass : respond("password can't be empty");
            $data["password"] = sha1($password);
        }  else {
            respond("password field can't be empty");
        }
        if (isset($user->key)){
            $ftoken = strval($user->key);
            (isset($ftoken) && !empty($ftoken) && strlen($ftoken) > 25) ? $data['ftoken'] = $ftoken : respond("invalid key");
        }  else {
            respond("you must send your firebase token");
        }
        if (isset($user->username)) {
            $name = strval($user->username);
            $name = preg_replace('/[^A-Za-z0-9\_]/', '', $name);
            (isset($name) && !empty($name) && strlen($name) < 50) ? $username = $name : respond("username can't be empty, only use A-Z a-z 0-9 and _");
            $data["username"] = $username;
            
            if ($userob->login($data["username"], $data["password"], $data['ftoken'])) {
                $userInfo= $userob->getUserInfo ();
                if ($userInfo['is_approved'] != 1) {
                    respond("Sorry you don't have permission to access this content! You have to wait for approval.", "401 Unauthorized");
                }
                echo json_encode(["token"=> $userInfo['token']]);
                exit();
            } else {
                respond("Invalid data, can't login!", "401 Unauthorized");
            }
        }  elseif (isset($user->email)) {
            $mail = filter_var($user->email, FILTER_VALIDATE_EMAIL);
            (isset($mail) && !empty($mail) && strlen($mail) < 60) ? $email = $mail : respond("email can't be empty, use a valid email address");
            $data["email"] = $email;
            
            if ($userob->login($data["email"], $data["password"], $data['ftoken'])){
                $userInfo= $userob->getUserInfo ();
                if ($userInfo['is_approved'] != 1) {
                    respond("Sorry you don't have permission to access this content! You have to wait for approval.", "401 Unauthorized");
                }
                echo json_encode(["token"=> $userInfo['token']]);
                exit();
            }  else {
                respond("Invalid data, can't login!", "401 Unauthorized");
            }
        }  else {
            respond("Enter username or email");
        }
    }  else {
        respond("No data sent");
    }
}  else {
    respond("Unsupported method", "405 Method Not Allowed");
}