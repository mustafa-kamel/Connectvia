<?php
require_once 'vendor/autoload.php';
Predis\Autoloader::register();
try {
    $client = new Predis\Client();
    
    
} catch (Predis\Connection\ConnectionException $exc) {
    echo $exc->getTraceAsString();
    die( $exc->getMessage());
}


$client->lpush('project', 'data');


while(1){
	list($queue, $message) = $client->brPop(["project"], 3);
	var_dump($queue);	
	var_dump($message);
	exit;
}
