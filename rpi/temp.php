<?php

/**
 * This script is used by the raspberry pi [only] to update the temperature curVal of a sensor in the database
 * 
 * @param int $sid id of the sensor to update its state or value
 * @param decimal $val value of the sensor (if found) to update
 */
require_once '../globals.php';
require_once '../includes/models/UsersModel.php';
require_once '../includes/models/SensorsModel.php';

$sensor= new SensorsModel();
$userob   = new UsersModel();
$data= array(); $sid=''; $state=''; $val='';
if (PHP_SAPI === 'cli') {
    if ($argc >= 3){
        $sid = intval($argv[1]);
        $val = intval($argv[2]);
        $data['sid'] = $sid;
        $data['val'] = $val;
    }  else {
        echo "Invalid number of arguments.";
        exit();
    }
} elseif (isset($_GET)) {
    if (isset($_GET['sid']) && !empty($_GET['sid'])) {
        $sid = intval($_GET['sid']);
        $data['sid'] = $sid;
    } else {
        echo "No ID sent!";
        exit();
    }
    if (isset($_GET['val']) && !empty($_GET['val'])) {
        $val = intval($_GET['val']);
        $data['val'] = $val;
    } else {
        echo "No value sent!";
        exit();
    }
}
if ($sensor->piTempUpdate($data)){
    $devices= $userob->getDevices();
    $firebase= new Firebase();
    $firebase->send($devices, $data);
    echo 1;
}
else
    echo 0;