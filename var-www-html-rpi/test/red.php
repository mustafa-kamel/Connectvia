<?php
require"../predis/autoload.php";
Predis\Autoloader::register();

try{
	$redis = new Predis\Client();
}
catch(Exception $e){
die($e->getMessage());
}

$redis->set('msg','redis');
echo $redis->get('msg');
$redis->del('msg');