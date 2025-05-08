<?php

session_start();

if (empty($_SESSION['Entidad'])) {
    exit(1);
}

require("../../conf/conf.php");


include_once(ENLACE_SERVIDOR . "mod_europa_facturacion/object/facturas.object.php");
include_once(ENLACE_SERVIDOR . "mod_europa_albaran_compra/object/Albaran_compra.object.php");
include_once(ENLACE_SERVIDOR . "mod_europa_albaran_venta/object/Albaran_venta.object.php");
include_once(ENLACE_SERVIDOR . "mod_europa_compra/object/compras.object.php");
include_once(ENLACE_SERVIDOR . "mod_europa_presupuestos/object/presupuestos.object.php");
include_once(ENLACE_SERVIDOR . "mod_europa_pedido/object/pedido.object.php");



$documento_id = $_POST['documento'];
$tipo         = $_POST['tipo'];




if ($tipo == 'Presupuesto') {
} else if ($tipo == 'Pedido') {
} else if ($tipo == 'Albaran_venta') {
} else if ($tipo == 'Factura') {
} else if ($tipo == 'Albaran_compra') {
} else if ($tipo == 'Compra') {
} else {
    $Respuesta['id']    = 0;
    $Respuesta['exito'] = 0;
    echo json_encode($Respuesta);
    exit(1);
}


$Documento = new $tipo($dbh, $_SESSION['Entidad']);
$dataDocumentoClonado = $Documento->clonar_documento($documento_id, $_SESSION['usuario'], $Documento->documento, $Documento->documento_detalle);

$Documento->clonar_documento_detalle(
    $dataDocumentoClonado["id"],
    $documento_id,
    $dataDocumentoClonado['nombre_documento_detalle_base'],
    $dataDocumentoClonado['origen_documento_inicio'],
    $dataDocumentoClonado['origen_fk_documento_inicio'],
    $dataDocumentoClonado['origen_documento_fin'],
    $dataDocumentoClonado['origen_fk_documento_fin']
);

$Respuesta['id']        = $dataDocumentoClonado["id"];
$Respuesta['exito']     = (intval($dataDocumentoClonado["id"]) > 0) ? 1 : 0;
$Respuesta['location']  =  $Documento->ver_url;
$Respuesta['error']     =  $Documento->error;
echo json_encode($Respuesta);
