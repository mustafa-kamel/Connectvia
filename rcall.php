<?php
require './vendor/autoload.php';
Predis\Autoloader::register();
$client = new Predis\Client();

require 'redistst.php';
print_r (process_data());