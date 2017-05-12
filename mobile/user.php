<?php

/* 
 * This script is used to view the data of user(s), it uses the GET method, so if the user used another method it will return
 * error message, if the user used GET without no data sent, it will concern him with a message that no data sent, then it checks
 * the token and admin to authorize the connection, if admin and token is right continue, otherwise break with an 401 Unauthorized
 * message, then checks if all required data are found [id-type], then get the data of the user(s) from the database and returns
 * the data to the user as json if everything goes right. If an error occurred send a message to the user tell him about it
 * 
 * @param string $token user's pregiven token after logging in
 * @param int $id id of the user to get its data
 * @param string $type type of users to view[user/admin/new(not approved)/all]
 * @return array $return array of the selected user(s) to be viewed or an error message using respond() if an error occured
 */

require_once '../globals.php';
require_once '../includes/models/UsersModel.php';
$userob   = new UsersModel();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET) && !empty($_GET)) {
        $token = ''; $id = ''; $type = ''; $user = array();
        if (isset($_GET['token']) && !empty($_GET['token'])) {
            $token = $_GET['token'];
        } else {
            respond("You don't have permission, login first!", "401 Unauthorized");
        }
        if (!$userob->chkToken($token)) {
            respond("Incorrect information, Permission denied, login first!", "401 Unauthorized");
        }
        $user= $userob->getUserInfo();
        if ($user['is_approved'] != 1 || $user['is_admin'] != 1) {
            respond("You don't have permission to view this content!", "401 Unauthorized");
        }
        if (isset($_GET['type']) && !empty($_GET['type'])) {
            $type = strval($_GET['type']);
        } else {
            respond("Undetermined type!");
        }
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $id = intval($_GET['id']);
        }
        switch ($type){
            case 'user':$return = $userob->getById($id);    break;
            case 'new': $return = $userob->getNew();     break;
            case 'admin':$return  = $userob->getAdmin();   break;
            case 'all':  $return  = $userob->get();           break;
            default : respond("Undefined type");
        }
        echo json_encode($return, JSON_FORCE_OBJECT);
    } else {
        respond("No data sent");
    }
} else {
    respond("Unsupported method", "405 Method Not Allowed");
}