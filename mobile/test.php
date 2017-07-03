<?php

require_once '../globals.php';
require_once '../includes/models/UsersModel.php';
$user = new UsersModel();
print_r($user->getDevices());
$x= array('username' => 'mu97', 'email' => 'mu97@host.com', 'password' => '1234564', 'phone' => '1122334455',
            'token' => '407c70ea05496ccc20170519174621', 'ftoken' =>'fadf', 'is_admin' => 1, 'is_approved' => 1);
echo ($user->add($x));