<?php
session_start();

// Validación de sesión
if (!isset($_SESSION['usuario'])) {
    echo json_encode(['error' => 1, "mensaje" => 'Acceso no válido']);
    exit;
}

require_once "../../conf/conf.php";
include_once(ENLACE_SERVIDOR . "mod_europa_albaran_venta/object/Albaran_venta.object.php");

$Documento = new Albaran_venta($dbh, $_SESSION['Entidad']);

switch ($_POST['accion']) {

    case 'ligar_documento':

        $Documento->fetch($_POST['documento']);

        $Documento_venta = new Albaran_venta($dbh, $_SESSION['Entidad']);
        $idDocumentoClonado  = $Documento_venta->clonar_documento($Documento->id, $_SESSION['usuario'], "fi_europa_albaranes_ventas", "fi_europa_albaranes_ventas_detalle");
        $Documento_venta->fetch($idDocumentoClonado);

        $return = $Documento->ligar_documento($Documento_venta, $_SESSION['usuario']);
        $Documento->cambiar_estado(3);
        echo json_encode($return);
        break;

    default:
        echo  json_encode(['error' => 1, "mensaje" => 'Accion No valida']);
        break;
}
