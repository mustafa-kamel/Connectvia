<?php

require_once '../globals.php';
session_destroy();
System::RedirectTo('login.php');