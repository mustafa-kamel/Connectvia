<?php
//pop data from redis client and execute redis script and run startup.php script
/**
 * Run the startup.php script on the server that pushes data of all sensors to load the last states on the rpi
 * This script is used to pop data from the redis list of updates and executed whenever a user changes the data
 * of any sensor, it acts as a listener, then passes this data to cpp script on the RPi to be executed
 * 
 * @param int $sid id of the sensor to update its state or value
 * @param boolean $state state of the sensor to update
 * @param decimal $val value of the sensor (if found) to update
 */

require_once 'config.php';
require_once 'predis/autoload.php';
//require_once './vendor/autoload.php';
Predis\Autoloader::register();
try {
    $client = new Predis\Client(REDIS);
} catch (Predis\Connection\ConnectionException $exc) {
    echo $exc->getTraceAsString();
    die( $exc->getMessage());
}
$url=WEBHOST."/SmartHome/rpi/startup.php";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
if ($result === FALSE) {
    die('Curl failed: ' . curl_error($ch));
}
curl_close($ch);
process_data();
function process_data(){
    global $client;
    $data=array(); $sid=''; $state=''; $val='';
    try{
            while (true) {
                list($queue, $message) = $client->brPop("project", 0);
    			$data = unserialize($message);
                    if (isset($data['sid']))
                        $sid = $data['sid'];
                    if (isset($data['state']))
                        $state = $data['state'];
                    if (isset($data['val']))
                        $val = $data['val'];//print_r($data);
                if (exec("./respond $sid $state $val", $output, $return)){
			echo $return;print_r($output);
			//sleep(0.5);
		}
            }
        } catch (Exception $exc) {
            $error = $exc->getMessage();
            //log_error($error, "redistst.php");
            process_data(); // call the function recursively if connection fails
        }
}