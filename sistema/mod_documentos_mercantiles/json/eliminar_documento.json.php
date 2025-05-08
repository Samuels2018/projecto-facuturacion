<?php
session_start();

// Validación de sesión
if (!isset($_SESSION['usuario'])) {
    echo json_encode(['error' => 'Acceso no válido']);
    exit;
}

require_once "../../conf/conf.php";

include_once(ENLACE_SERVIDOR . "mod_europa_facturacion/object/facturas.object.php");
include_once(ENLACE_SERVIDOR . "mod_europa_albaran_compra/object/Albaran_compra.object.php");
include_once(ENLACE_SERVIDOR . "mod_europa_albaran_venta/object/Albaran_venta.object.php");
include_once(ENLACE_SERVIDOR . "mod_europa_compra/object/compras.object.php");
include_once(ENLACE_SERVIDOR . "mod_europa_presupuestos/object/presupuestos.object.php");
include_once(ENLACE_SERVIDOR . "mod_europa_pedido/object/pedido.object.php");


 
try {
    // Parámetros recibidos vía POST
    $documento_id = $_POST['documento_id'];
    $tipo = $_POST['tipo'];
    $entidad = $_SESSION['Entidad']; // Tomado de la sesión


    $Documento = new $tipo($dbh, $_SESSION['Entidad']);
    $Documento->usuario = $_SESSION["usuario"];
    $Documento->fetch($documento_id);
    $eliminado_documento = $Documento->eliminar($documento_id);
    if($eliminado_documento["success"] == true){
        $Documento->actualiza_documento_origen();
    }

    echo json_encode($eliminado_documento);
 

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
