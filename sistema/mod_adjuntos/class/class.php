<?php

SESSION_START();
// USER
if (empty($_SESSION['usuario'])) {
    header("location: " . ENLACE_WEB . "inicio/");
    exit(1);
}

include_once "../../conf/conf.php";
require_once(ENLACE_SERVIDOR . 'mod_adjuntos/object/adjuntos.object.php');

$Adjuntos = new Adjunto($dbh, $_SESSION['Entidad']);

switch ($_POST['action']) {


    case 'BorrarAdjunto':

        $datos = new stdClass();
        $datos->id = $_POST['id'];
        $datos->fk_documento = $_POST['fk_documento'];
        $datos->label = $_POST['label'];
        $datos->creado_fk_usuario = $_SESSION['usuario'];
        $datos->borrado_fk_usuario = $_SESSION['usuario'];
        $datos->entidad = $_SESSION['Entidad'];
        $datos->tipo_documento = $_REQUEST['tipo_documento'];
        $Adjuntos->entidad = $_SESSION['Entidad'];
        $result = $Adjuntos->borrar_adjunto($datos);
        echo json_encode($result);

    break;
    
    default:
        # code...
    break;
}



