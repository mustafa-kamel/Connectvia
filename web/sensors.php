<?php

require_once '../globals.php';
require_once '../includes/models/SensorsModel.php';
require_once '../includes/models/UsersModel.php';
require_once '.././vendor/autoload.php';
Predis\Autoloader::register();
$sensorob = new SensorsModel();
$userob = new UsersModel();
try {
    $client = new Predis\Client(REDIS);
} catch (Predis\Connection\ConnectionException $exc) {
    echo $exc->getTraceAsString();
    die( $exc->getMessage());
}

chkLogin();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST) && !empty($_POST)) {
        $id=''; $state=''; $val=''; $msg=''; $data=array();
        if (isset($_POST['id']) && !empty($_POST['id'])) {
            $id = intval($_POST['id']);
            $data['sid'] = $id;
        } else {
            header("HTTP/1.0 503 Internal server errors");
            $msg .= "No ID sent!, ";
        }
        if (isset($_POST['state']) && !empty($_POST)) {
            $status = filter_var($_POST['state'], FILTER_VALIDATE_BOOLEAN);
            $state = intval($status);
            $data['state'] = $state;
        } else {
            header("HTTP/1.0 503 Internal server errors");
            $msg .= "No status sent!, ";
        }
        if (isset($_POST['val']) && !empty($_POST['val'])) {
            $val = intval($_POST['val']);
            $data['val'] = $val;
        }
        if ($client->lpush('project', serialize($data)) && $sensorob->updateSensor($data)) {
            $devices= $userob->getDevices();
            $firebase= new Firebase();
            $firebase->send($devices, $data);
            echo json_encode($data);
            exit;
        } else {
            header("HTTP/1.0 503 Internal server errors");
            $msg .= "Error while updating the database, try again!, ";
        }
        
    } else {
        header("HTTP/1.0 503 Internal server errors");
        $msg .= "No data sent!, ";
    }
} else {
    header("HTTP/1.0 503 Internal server errors");
    $msg .= "Unsupported method";
}
echo json_encode($msg);
exit;