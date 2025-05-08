<?php 
session_start();
// LOCATION
require_once("../../conf/conf.php");

require_once ENLACE_SERVIDOR . "mod_logs/LoggerSistema.php";
(new LoggerSistema)->Logger("success", 'logout', 'Usuario desconectado correctamente');
// CLOSE SESSION
session_destroy();

header("location: ".ENLACE_WEB."login");
exit();