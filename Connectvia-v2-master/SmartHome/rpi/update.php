<?php

/**
 * This script is used by the raspberry pi [only] to update the data of a sensor in the database
 * 
 * @param int $sid id of the sensor to update its state or value
 * @param boolean $state state of the sensor to update
 * @param decimal $val value of the sensor (if found) to update
 */
require_once '../globals.php';
require_once '../includes/models/UsersModel.php';
require_once '../includes/models/SensorsModel.php';

$sensor= new SensorsModel();
$userob   = new UsersModel();
$data= array(); $sid=''; $state=''; $val='';
if (PHP_SAPI === 'cli') {
    if ($argc > 3){
        $sid = intval($argv[1]);
        $val = intval($argv[3]);
        $data['sid'] = $sid;
        $data['val'] = $val;
        if ($argv[2] == 0 || $argv[2] == 1) {
            $state = intval($argv[2]);
        } else {
            echo "Invalid Status.";
            exit();
        }
        $data['state'] = $state;
    } elseif ($argc == 3) {
        $sid = intval($argv[1]);
        $data['sid'] = $sid;
        if ($argv[2] == 0 || $argv[2] == 1) {
            $state = intval($argv[2]);
        } else {
            echo "Invalid Status.";
            exit();
        }
        $data['state'] = $state;
    }  else {
        echo "Invalid number of arguments.";
        exit();
    }
}  elseif (isset($_GET)) {
    if (isset($_GET['sid']) && !empty($_GET['sid'])) {
        $sid = intval($_GET['sid']);
        $data['sid'] = $sid;
    } else {
        echo "No ID sent!";
        exit();
    }
    if (isset($_GET['state']) && is_numeric($_GET['state']) && ($_GET['state'] == 0 || $_GET['state'] == 1)) {
        $state = intval($_GET['state']);
        $data['state'] = $state;
    } else {
        echo "Invalid status!";
        exit();
    }
    if (isset($_GET['val']) && !empty($_GET['val'])) {
        $val = intval($_GET['val']);
        $data['val'] = $val;
    }
}
if ($sensor->updateSensor($data)){
    $devices= $userob->getDevices();
    $firebase= new Firebase();
    $firebase->send($devices, $data);
    echo 1;
}
else
    echo 0;