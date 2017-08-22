<?php

/* 
 * This script is used to update the data[approve/admin] of one user, it uses the GET method, so if the user used
 * another method it will return error message, if the user used GET without no data sent, it will concern him with a message
 * that no data sent, then it checks the token and admin to authorize the connection, if admin and token is right continue
 * otherwise break with an 401 Unauthorized message, then checks if all required data are found [id-op], then update the data
 * of the user in the database and returns the result of the operation as TRUE/FALSE to the user as json if everything goes right.
 * at anytime if an error occurred send a message to the user tell him about it
 * 
 * @param string $token user's pregiven token after logging in
 * @param int $id id of the user to update its state [approve/admin]
 * @param string $op operation to execute for this user
 * @return boolean $return indicating the update result [true or false] or an error message using respond() if an error occured
 */

require_once '../globals.php';
require_once '../includes/models/UsersModel.php';
$userob   = new UsersModel();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET) && !empty($_GET)) {
        $token = ''; $id = ''; $op = ''; $user = array();
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
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $id = intval($_GET['id']);
        } else {
            respond("No ID sent!");
        }
        if (isset($_GET['op']) && !empty($_GET['op'])) {
            $op = strval($_GET['op']);
        } else {
            respond("Undetermined operation!");
        }
        switch ($op){
            case 'delete': $return = $userob->delete($id);        break;
            case 'unsetadmin':$return = $userob->unsetAdmin($id); break;
            case 'approve': $return = $userob->approve($id);    break;
            case 'setadmin':$return = $userob->setAdmin($id);   break;
            default : respond("Undefined Operation");
        }
        echo json_encode(["result" => $return]);
    } else {
        respond("No data sent");
    }
} else {
    respond("Unsupported method", "405 Method Not Allowed");
}