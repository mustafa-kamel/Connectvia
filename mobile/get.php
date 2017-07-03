<?php

/**
 * This script is used to view the data of one sensor, sensors in the same room, sensors in the same floor or sensors of
 * the same category, it uses the GET method, so if the user used another method it will return error message, if the user
 * used GET without no data sent, it will concern him with a message that no data sent, then it checks the token to authorize
 * the connection, if token is right continue otherwise break with an 401 Unauthorized message, then checks if all required
 * data are found, then returns the data to the user as json if all the data are authenticated and found in the database
 * 
 * @param string $token user's pregiven token after logging in
 * @param string $type the type of the data user want to get (it can be: sensor, room, floor or cat{i.e light})
 * @param int/string $id id for the sensor, room, floor and string in case of cat
 * @return string message in json if error occured or data is inconvenient
 * @return string of data required as array or 2D array in json
 */

require_once '../globals.php';
require_once '../includes/models/SensorsModel.php';
require_once '../includes/models/UsersModel.php';
$sensorob = new SensorsModel();
$userob   = new UsersModel();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET) && !empty($_GET)) {
        $data = array(); $type = ''; $id = ''; $token = '';
        if (isset($_GET['token']) && !empty($_GET['token'])) {
            $token = $_GET['token'];
        } else {
            respond("You don't have permission, login first!", "401 Unauthorized");
        }
        if (!$userob->chkToken($token) /*|| !$userob->isApproved($id)*/) {
            respond("Incorrect information, Permission denied, login first!", "401 Unauthorized");
        }
        $user = $userob->getUserInfo();
        if (!$user['is_approved']) {
            respond("Sorry you don't have permission to access this content! You have to wait for approval.", "401 Unauthorized");
        }
        if (isset($_GET['type']) && !empty($_GET['type'])) {
            $type = $_GET['type'];
        } else {
            respond("Type can't be empty!");
        }
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $id = $_GET['id'];
        } else {
            respond("No ID sent!");
        }

        switch ($type) {
            case 'sensor':  $data = $sensorob-> getSensor($id);  break;
            case 'room':    $data = $sensorob-> getRoom($id);    break;
            case 'floor':   $data = $sensorob-> getFloor($id);   break;
            case 'cat':     $data = $sensorob-> getCat($id);     break;
            default:
                respond("Unsupported type!");
                break;
        }
        echo json_encode($data, JSON_FORCE_OBJECT);
    } else {
        respond("No data sent");
    }
} else {
    respond("Unsupported method", "405 Method Not Allowed");
}