<?php

if (!defined('ENLACE_WEB')):
    session_start();
    require_once "../../conf/conf.php";
endif;

include_once(ENLACE_SERVIDOR . "mod_tipo_actividad/object/tipo_actividad.object.php");


$data = json_decode(file_get_contents('php://input'), true);


try {
    $tipoactividad = new TipoActividad($dbh, $_SESSION['Entidad']);
    $respuesta = $tipoactividad->obtener_todos($_SESSION['Entidad']);
     echo json_encode(['success' => true, 'message' => $respuesta]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
