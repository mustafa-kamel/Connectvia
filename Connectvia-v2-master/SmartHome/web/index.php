<?php
require_once '../globals.php';
require_once '../includes/models/SensorsModel.php';
require_once '../includes/models/UsersModel.php';
define("TITLE", "Documentation");
chkLogin();
?>
<?php include('header.php');?>
<?php include('sidebar.php');?>       

	
<div class="container" style="padding:100px 100px 0px 100px;">
<h1>Mr. Home</h1>
<p><strong>Full documentation can be downloaded from <a href="assets/Connectvia.pdf" style="text-decoration: none !important;"  target="_blank">here</a> </strong></p>      
<p><strong><a href="https://github.com/Mustafa-Kamel/Connectvia" style="text-decoration: none !important;"  target="_blank">Webserver source code</a> </strong></p>
<p><strong><a href="https://github.com/ahmedorabi94/MrHome" style="text-decoration: none !important;"  target="_blank">Mobile App source code</a> </strong></p>
<p><strong><a href="https://github.com/AnasKhedr/Smart-Home-2017" style="text-decoration: none !important;" target="_blank">Raspberry pi and Microcontrollers source code</a> </strong></p>
</div>
<?php include('footer.php');?>
