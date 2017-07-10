<?php
require_once '../globals.php';
require_once '../includes/models/UsersModel.php';
$userob = new UsersModel();
chkAdmin();
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET) && !empty($_GET)) {
        $id=''; $op=''; $type = ''; $msg='';
        if (isset($_GET['type']) && !empty($_GET['type'])) {
            $type = strval($_GET['type']);
        } else {
                header("HTTP/1.0 503 Internal server errors");
                $msg .= "Undetermined type! ";
        }
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $id = intval($_GET['id']);
        } else {
            header("HTTP/1.0 503 Internal server errors");
            $msg .= "No ID sent! ";
        }
        if (isset($_GET['op']) && !empty($_GET['op'])) {
            $op = strval($_GET['op']);
        } else {
            header("HTTP/1.0 503 Internal server errors");
            $msg .= "Undetermined operation! ";
        }
        switch ($op){
            case 'delete': $return = $userob->delete($id);        break;
            case 'unsetadmin':$return = $userob->unsetAdmin($id); break;
            case 'approve': $return = $userob->approve($id);    break;
            case 'setadmin':$return = $userob->setAdmin($id);   break;
            default : $msg .= "Undefined Operation! ";
        }
        echo $return == 1 ? "Successful" : "Failed";
        System::RedirectTo("user.php?type=$type");
    } else {
        header("HTTP/1.0 503 Internal server errors");
        $msg .= "No data sent! ";
    }
} else {
    header("HTTP/1.0 503 Internal server errors");
    $msg .= "Unsupported method! ";
}
echo $msg;
System::RedirectTo("user.php?type=$type");