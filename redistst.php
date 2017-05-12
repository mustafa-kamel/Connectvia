<?php
require './vendor/autoload.php';
Predis\Autoloader::register();
$client = new Predis\Client();

function process_data()
{	global $client;
	try{
        while (true) {
			//echo "<script type='text/javascript'>alert('submitted!')</script>";
            list($queue, $message) = $client->brPop(["project"], 0);//integer max value for blocking time
			//when brpop block the connection it stops the execution of the program and waits for a value to
			//be pushed, the time out is the time after it, it will leave brpop and execute the program after
			//0 timeout means blocking forever >> it will throw exeption, and non 0 after this time go on
			//and execute the program
			print $message; echo 'x';
        }
    } catch (Exception $ex) {
        echo $error = $ex->getMessage();
        //log_error($error, "redistst.php");
        process_data(); // call the function recursively if connection fails
    }
}
//process_data();