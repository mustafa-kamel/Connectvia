<?php
/**
 * system can't run without core files
 * DB configuration sets right here
 */

//Database parameters
define('HOSTNAME','localhost');
define('USERNAME','root');
define('PASSWORD','root');
define('DBNAME','homedb');

//to call online pages or on my server
//define('WEBHOST','<ip>');	for the pages inside /www/html/ for online version and mobile/set.php
define('REDIS','tcp://192.168.43.168:6379');//'tcp://<ip>:6379'    tcp://192.168.1.105:6379
define('FIREBASE','AAAAbeCqg88:APA91bGFLswZEjkBPL0NRy7Bu1702zNXb9pomaEcp-2Xo8R7p-O0MSuqUMnrMBwiYlozwCtGNonLlcEGou5wyGzXI9umPUczSqyozO8vjJRoGk0OvdmyzeK8WRzzR2Gm3rTUhIBFfx1M');
/*
 * k252@gmail.com 29Mm
 * define('HOSTNAME','localhost');
 * define('USERNAME','id1582632_root');
 * define('PASSWORD','roothomedb');
 * define('DBNAME','id1582632_homedb');
 */