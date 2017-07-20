<?php
/**
 * This script is used by the raspberry pi [only] to update the temperature curVal of a sensor in the database
 * 
 * @param int $sid id of the sensor to update its state or value
 * @param decimal $val value of the sensor (if found) to update
 */
require_once 'config.php';
$sid=''; $val='';
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
$url= WEBHOST."/SmartHome/rpi/temp.php?sid=$sid&val=$val";
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
//header("Location: ".WEBHOST."/SmartHome/rpi/temp.php?sid=$sid&val=$val");
//echo '<script type="text/javascript">window.location ="'.WEBHOST.'/SmartHome/rpi/temp.php?sid='.$sid.'&val='.$val.'"</script>';