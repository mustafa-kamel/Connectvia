<?php

/**
 * This script is used by the raspberry pi [only] to update the data of a sensor in the database
 * 
 * @param int $sid id of the sensor to update its state or value
 * @param boolean $state state of the sensor to update
 * @param decimal $val value of the sensor (if found) to update
 */
require_once 'config.php';

$sid=''; $state=''; $val='';
if (PHP_SAPI === 'cli') {
    if ($argc > 3){
        $sid = intval($argv[1]);
        $val = intval($argv[3]);
        if ($argv[2] == 0 || $argv[2] == 1) {
            $state = intval($argv[2]);
        } else {
            echo "Invalid Status.";
            exit();
        }
    } elseif ($argc == 3) {
        $sid = intval($argv[1]);
        if ($argv[2] == 0 || $argv[2] == 1) {
            $state = intval($argv[2]);
        } else {
            echo "Invalid Status.";
            exit();
        }
    }  else {
        echo "Invalid number of arguments.";
        exit();
    }
}  elseif (isset($_GET)) {
    if (isset($_GET['sid']) && !empty($_GET['sid'])) {
        $sid = intval($_GET['sid']);
    } else {
        echo "No ID sent!";
        exit();
    }
    if (isset($_GET['state']) && is_numeric($_GET['state']) && ($_GET['state'] == 0 || $_GET['state'] == 1)) {
        $state = intval($_GET['state']);
    } else {
        echo "Invalid status!";
        exit();
    }
    if (isset($_GET['val']) && !empty($_GET['val'])) {
        $val = intval($_GET['val']);
    }
}
$url= WEBHOST."/SmartHome/rpi/update.php?sid=$sid&state=$state&val=$val";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
if ($result === FALSE) {
    die('Curl failed: ' . curl_error($ch));
} else {
    echo 1;
}
curl_close($ch);
//header("Location: ".WEBHOST."/SmartHome/rpi/update.php?sid=$sid&state=$state&val=$val");
//echo '<script type="text/javascript">window.location ="'.WEBHOST.'/SmartHome/rpi/temp.php?sid='.$sid.'&state='.$state.'&val='.$val.'"</script>';