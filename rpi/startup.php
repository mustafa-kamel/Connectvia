<?php

require_once '../globals.php';
require_once '../includes/models/SensorsModel.php';
require_once '.././vendor/autoload.php';
Predis\Autoloader::register();
try {
    $client = new Predis\Client(REDIS);
} catch (Predis\Connection\ConnectionException $exc) {
    echo $exc->getTraceAsString();
    die($exc->getMessage());
}

$sensorob = new SensorsModel(); $fid = 0; $floor_num = 0;
while (TRUE) {
    $floor = $sensorob->getFloor(++$fid);
    if (!$floor)
        break;
    foreach ($floor as $key => $value) {
        $data = array();
        foreach ($value as $k => $v) {
            if (strpos($k, 'sid')) {
                $data[$key]['sid'] = $v;
            }
            if (strpos($k, 'tate') != NULL) {
                $data[$key]['state'] = $v;
            }
            if (strpos($k, 'Val')) {
                $data[$key]['val'] = $v;
            }
        }
        $client->lpush('project', serialize($data[$key]));
    }
}