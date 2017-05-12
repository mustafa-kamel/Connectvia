<?php

class System {
    //objects array
    private static $objects= array();
    
    /**
     * store objects
     * @param string $index
     * @param object $value
     */
    public static function store($index,$value){
        self::$objects[$index]= $value;
    }
    /**
     * get objects
     * @param string $index
     * @return object
     */
    public static function get($index){
        return self::$objects[$index];
    }
    
    public static function RedirectTo($location)
    {
        if(!headers_sent()) {
            //If headers not sent yet... then do php redirect
            header("Location: $location");
            exit;
        } else {
            //If headers are sent... do javascript redirect... if javascript disabled, do html redirect.
            $red    =  '<script type="text/javascript">';
            $red   .=  'window.location.href="'.$location.'";';
            $red   .=  '</script>';
            echo $red;

            /*---------- HTML Meta Refresh ---------*/
            $meta  =  '<noscript>';
            $meta .= '<meta http-equiv="refresh" content="0;url='.$location.'" />';
            $meta .= '</noscript>';
            echo $meta;
            exit;
        }

    }
}

/**
 * Checks if there is a message then respond with it as json and die, else skip executing the function and continue the program
 * @param string $msg
 */
function respond($msg, $code='406 Not Acceptable'){
    if (isset($msg)&&!empty($msg)){
        header("HTTP/1.0 {$code}");
        echo json_encode (["message"=> "Error, {$msg}"]);
        exit();
    }
}