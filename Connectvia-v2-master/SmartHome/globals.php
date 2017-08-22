<?php
/*requires: core files are required everywhere so it must be included in each file
*in this file we will call all of them then just call this file without rewriting the code
*/
session_start();
define('ROOT',dirname(__FILE__));
define('INC',ROOT.'/includes/');
define('CONTROLLERS',INC.'controllers/');
define('CORE',INC.'core/');
define('LIBS',INC.'libs/');
define('MODELS',INC.'models/');
//CORE files
require_once (CORE.'config.php');
require_once (CORE.'mysql.class.php');
require_once (CORE.'raintpl.class.php');
require_once (CORE.'system.php');
require_once (CORE.'firebase.class.php');
System::store('db',new mysql());
System::store('tpl',new RainTPL());
/******************************************************************************/
/**
 * check if the user isn't logged in then redirect him to the login page
 */
function chkLogin(){
    if (!isset($_SESSION['username']) || $_SESSION['is_approved'] != 1) {
        session_destroy();
        System::RedirectTo('login.php');
    }
}
/**
 * check if the user isn't admin then redirect him to home login page
 */
function chkAdmin(){
    chkLogin();
    if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
        System::RedirectTo('index.php');
    }
}
//header("HTTP/1.0 404 Not Found");
//        $email= filter_input(INPUT_POST, $_POST['email'], FILTER_SANITIZE_EMAIL);       
//        $password= filter_var($_POST['password'], FILTER_VALIDATE_REGEXP, array( "options"=> array( "regexp" => "/.{6,25}/")));
//        $phone = filter_var($_POST['phone'], FILTER_SANITIZE_NUMBER_INT);
//$idString = implode(',', array_map(intval, $ids));
//$number = intval($_GET['id']);
//$string = mysqli_real_escape_string(strval($_GET['str']));
//$string = mysqli->real_escape_string($link, strval($_GET['str']));