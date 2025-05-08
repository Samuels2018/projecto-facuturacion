<?php

session_start();
include "../../conf/conf.php";
// Obtiene la ruta del archivo a través del parámetro de consulta 'file'
require_once ENLACE_SERVIDOR . "mod_logs/LoggerSistema.php";

$usuario_cambiar = $_POST['usuario'];
$entidad_cambiar = $_POST['entidad'];
$entidad_nombre = $_POST['entidad_nombre'];

$_SESSION['usuario']            =   $usuario_cambiar;
$_SESSION['Entidad']            =   $entidad_cambiar;
$_SESSION['EntidadNombre']      =   $entidad_nombre;

(new LoggerSistema)->Logger("Exitoso", 'login', 'Usuario correcto Cambio empresa');

?>
