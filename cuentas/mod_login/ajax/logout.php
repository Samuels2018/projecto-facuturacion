<?php 
session_start();
// LOCATION
require_once("../../conf/conf.php");

// CLOSE SESSION
session_destroy();

header("location: ".ENLACE_WEB_CUENTAS."login.php");
exit();