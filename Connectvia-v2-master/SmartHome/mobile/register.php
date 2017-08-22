<?php
require_once '../globals.php';
require_once '../includes/models/UsersModel.php';
$userob= new UsersModel;
/**
 * This script includes registering new user for mobile user after some validation
 * first get contents then check if the used method is POST, if condition is satisfied check if the data isn't empty, if satisfied
 * intialize the data array to be inserted and the individual variables, then check each variable of the recieved data for
 * dataType and special chars and remove unsuitable chars, if any of the fields is empty or unsuitable call the (respond fcn)
 * to respond to the user with suitable message. if the data is convenient push it in the data array, after pushing all data
 * generate token, save it in the data array and save it in the database and send it to the user
 * 
 * @param string $username
 * @param string $email
 * @param string $password
 * @param string $phone
 * @return string message if error occured or data is inconvenient
 * @return token if data inserted successfully without any errors
 */
$json = file_get_contents('php://input');
header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if (isset($_POST) && !empty($json)){
        $data= array();
        $username=''; $email=''; $password=''; $phone='';
        $user = json_decode($json);
        if (!isset($user->username))
            respond("username can't be empty, only use A-Z a-z 0-9 and _");
        if (!isset($user->email))
            respond("email can't be empty, use a valid email address");
        if (!isset($user->password))
            respond("password can't be empty");
        if (!isset($user->phone))
            respond("phone can't be empty");
        $name= strval($user->username);
        $name = preg_replace('/[^A-Za-z0-9\_]/', '', $name);
        (isset($name) && !empty($name) && strlen($name)>4 && strlen($name)<50)? $username= $name : respond("username can't be empty, only use A-Z a-z 0-9 and _");
        if ($userob->isFound("username", $username))
            respond("username isn't available");
        $data["username"]=$username;
        
        $mail= filter_var($user->email, FILTER_VALIDATE_EMAIL);
        (isset($mail) && !empty($mail) && strlen($mail)>9 && strlen($mail)<60)? $email= $mail : respond("email can't be empty, use a valid email address");
        if ($userob->isFound("email", $email))
            respond("email isn't available");
        $data["email"]=$email;
        
        $pass= strval($user->password);
        (isset($pass) && !empty($pass) && strlen($pass)<25 && strlen($pass)>7 && preg_match('/[a-z].*[0-9]|[0-9].*[a-z]/', $pass) && preg_match('/[A-Z]/', $pass))? $password= $pass : respond("password can't be empty");
        $data["password"]= sha1($password);
        
//        $phoneNum= filter_var($user->phone, FILTER_VALIDATE_INT);print_r ($phoneNum);
        $phoneNum= strval($user->phone);
        (isset($phoneNum) && !empty($phoneNum) && strlen($phoneNum)>7 && strlen($phoneNum)<25)? $phone= $phoneNum : respond("phone can't be empty");
        $data["phone"]=$phone;
        /* DEPRECATED == NO ACCESS BEFORE APPROVAL
        $token= $userob->genToken();
        $data["token"]=$token;
        $userob->add($data);
        echo json_encode(["token"=> $token]);
         */
        if ($userob->add($data)) {
            echo json_encode(["message" => "Your data has been saved successfully, Please wait until the admin approves you!"]);
            mail($data['email'], "Successful Registeration", "Your data has been saved successfully, Please wait until the admin approve you! \r\n \r\n \r\n Regards, \r\n Connectvia Home", "From: admin@connectvia.net");
            $admin = $userob->getAdmin();
            mail($admin[0]['email'], "New Registeration", "$username has registered to get access to the home, Check the admin panel. \r\n \r\n \r\n Regards, \r\n Connectvia Home", "From: admin@connectvia.net");
        } else {
            respond("An error has occurred while saving your data!", "409 Conflict");
        }
    }  else {
        respond("No data sent");
    }
}  else {
    respond("Unsupported method", "405 Method Not Allowed");
}