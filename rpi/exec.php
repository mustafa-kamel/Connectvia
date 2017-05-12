<?php

/**
 * This script is used to call the raspberry pi [only] and send it data, and executed when a user changes the data of any sensor
 * to update its data using its provided id its supposed to call a script on pi written in [c] or whatever to recieve the data
 * and process it by doing the suitable operation
 * 
 * @param int $sid id of the sensor to update its state or value
 * @param boolean $state state of the sensor to update
 * @param decimal $val value of the sensor (if found) to update
 */
$sid=''; $state=''; $val='';
 if (isset($_GET['sid']))
    $sid = $_GET['sid'];
if (isset($_GET['state']))
    $state = $_GET['state'];
if (isset($_GET['val']))
    $val = $_GET['val'];

#To Be Tested
$lastline = exec("./exescript $sid $state $val", $output, $return);
echo 'Return/Result'; print_r($return);echo '</br></br>Last Line'; print_r($lastline);echo '</br></br>Every Line Output'; print_r($output);
#exec("python exescript.py $sid $state $val", $output, $return);
/*
exec('C://abc//wkhtmltopdf home.html sample.pdf', $output, $return);
// Return will return non-zero upon an error
if (!$return) {
    echo "PDF Created Successfully";
} else {
    echo "PDF not created";
}
*/