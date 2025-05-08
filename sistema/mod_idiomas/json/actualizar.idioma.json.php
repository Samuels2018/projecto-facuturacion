<?php 
SESSION_START();
require("../../conf/conf.php");




require_once(ENLACE_SERVIDOR."mod_idiomas/object/idioma.object.php");
$Lan = new Idioma($dbh_utilidades_Apoyo) ;

$_SESSION['idioma'] = $_GET['idioma'];

$respuesta['exito'] = 1;

echo json_encode($respuesta);