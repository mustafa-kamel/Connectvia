<?php

/**
 * This script is used by the raspberry pi [only] to update the data of a sensor in the database
 * 
 * @param int $sid id of the sensor to update its state or value
 * @param boolean $state state of the sensor to update
 * @param decimal $val value of the sensor (if found) to update
 */
require_once '../globals.php';
require_once '../includes/models/SensorsModel.php';
require_once '../includes/models/UsersModel.php';
require '.././vendor/autoload.php';

$sensor= new SensorsModel();
$data= array(); $sid=''; $state=''; $val='';
if (isset($_GET['sid']) && !empty($_GET['sid'])) {
    $sid = intval($_GET['sid']);
    $data['sid'] = $sid;
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
$sensor->updateSensor($data);