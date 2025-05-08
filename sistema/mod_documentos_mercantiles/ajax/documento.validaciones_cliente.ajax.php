<?php
session_start();
require_once "../../conf/conf.php";

//si no hay usuario autenticado, cerrar conexion
if (!isset($_SESSION['usuario'])) {
    echo acceso_invalido();
    exit(1);
}

include_once(ENLACE_SERVIDOR . "mod_europa_facturacion/object/facturas.object.php");
include_once(ENLACE_SERVIDOR . "mod_europa_albaran_compra/object/Albaran_compra.object.php");
include_once(ENLACE_SERVIDOR . "mod_europa_albaran_venta/object/Albaran_venta.object.php");
include_once(ENLACE_SERVIDOR . "mod_europa_compra/object/compras.object.php");
include_once(ENLACE_SERVIDOR . "mod_europa_presupuestos/object/presupuestos.object.php");
include_once(ENLACE_SERVIDOR . "mod_europa_pedido/object/pedido.object.php");


$entidad = $_SESSION['Entidad'];
$DocumentoMercantil = new documento_mercantil($dbh, $entidad);

$monto_venta = $_POST['monto_venta'];
$fk_tercero = $_POST['fk_tercero'];
$forma_pago = $_POST['forma_pago'];
$forzar_venta = $_POST['forzar_venta'];

$respuesta_validacion = $DocumentoMercantil->obtener_validaciones_tercero($fk_tercero, $monto_venta, $forma_pago, $forzar_venta);

echo json_encode($respuesta_validacion);
