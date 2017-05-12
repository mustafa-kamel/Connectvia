<?php

/**
 * This script is used to update the data[status and val] of one sensor, it uses the GET method, so if the user used
 * another method it will return error message, if the user used GET without no data sent, it will concern him with a message
 * that no data sent, then it checks the token to authorize the connection, if token is right continue otherwise break with an
 * 401 Unauthorized message, then checks if all required data are found, then send the data to the raspberry pi and update
 * the data of this sensor in the database and returns the result of the operation as TRUE/FALSE to the user as json if everything
 * goes right. at anytime if an error occurred send a message to the user tell him about it
 * 
 * @param string $token user's pregiven token after logging in
 * @param int $id id of the sensor to update its state or value
 * @param boolean $state state of the sensor to update
 * @param decimal $val value of the sensor (if found) to update
 * @return string message indicating the update result [true or false] or an error message using respond() if an error occured
 */
require_once '../globals.php';
require_once '../includes/models/SensorsModel.php';
require_once '../includes/models/UsersModel.php';
require '.././vendor/autoload.php';
Predis\Autoloader::register();
$client = new Predis\Client();
$sensorob = new SensorsModel();
$userob   = new UsersModel();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET) && !empty($_GET)) {
        $data = array(); $token = ''; $id = ''; $state = ''; $val = '';
        if (isset($_GET['token']) && !empty($_GET['token'])) {
            $token = $_GET['token'];
        } else {
            respond("You don't have permission, login first!", "401 Unauthorized");
        }
        if (!$userob->chkToken($token)/*|| !$userob->isApproved($id)*/) {
            respond("Incorrect information, Permission denied, login first!", "401 Unauthorized");
        }
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $id = intval($_GET['id']);
            $data['sid'] = $id;
        } else {
            respond("No ID sent!");
        }
        if (isset($_GET['state']) && is_numeric($_GET['state']) && ($_GET['state'] == 0 || $_GET['state'] == 1)) {
            $state = intval($_GET['state']);
            $data['state'] = $state;
        } else {
            respond("Invalid status!");
        }
        if (isset($_GET['val'])) {
            $val = intval($_GET['val']);
            $data['val'] = $val;
        }
        /****************************PUSH $data in REDIS list HERE**************************/
/**************************************************************************************************************************/
        #header('location: ../rpi/exec.php?sid='.$data['sid'].'&state='.$data['state'].'&val='.$data['val']);
        $lastline = exec("./exescript $id $state $val", $output, $return);
        /*$result = $client->rpush('project', serialize($data))*/;
        if ($return)
            $sensorob->updateSensor($data);
/**************************************************************************************************************************/
            
        echo json_encode(["result" => $return]);
    } else {
        respond("No data sent");
    }
} else {
    respond("Unsupported method", "405 Method Not Allowed");
}