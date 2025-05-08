<?php
if (!defined('ENLACE_SERVIDOR')) {
    session_start();
    require_once "../../conf/conf.php";
}

header('Content-Type: application/json');

// Verificar si el usuario está autenticado
if ($_SESSION['usuario'] == NULL) {
    echo json_encode(['success' => false, 'message' => 'Sesión no válida.']);
    exit;
}

$error = false;

// Obtener los IDs de la solicitud AJAX
// $ids = isset($_POST['ids']) ? $_POST['ids'] : [];
$data = isset($_POST['data']) ? json_decode($_POST['data']) : [];

if (empty($data)) {
    echo json_encode(['success' => false, 'message' => 'No se recibieron IDs para procesar.']);
    exit;
}

include_once(ENLACE_SERVIDOR . "mod_europa_albaran_compra/object/Albaran_compra.object.php");
include_once(ENLACE_SERVIDOR . "mod_europa_compra/object/compras.object.php");
//(reemplazar con la lógica de generación de facturas que viene del modelo)
try {
    foreach ($data as $item) {
        $documento_id = 0;
        $documento_generado  = 0;
        $documento_generado_movimiento  = 0;
        $dataDocumentoClonado = [];
        $Documento = new Albaran_compra($dbh, $_SESSION['Entidad']);

        foreach ($item as $item_detalle) {
            $documento_id = $item_detalle->id;

            $Documento->fetch($documento_id);
            $Documento_compra = new Compra($dbh, $_SESSION['Entidad']);
            if ($documento_generado == 0) {
                $dataDocumentoClonado  = $Documento_compra->clonar_documento($documento_id, $_SESSION['usuario'], $Documento->documento, $Documento->documento_detalle);
                $documento_generado = $dataDocumentoClonado['id'];

                $Documento_compra->id = $dataDocumentoClonado['id'];
            } 
            $Documento_compra->clonar_documento_detalle($documento_generado, $documento_id, 
                $dataDocumentoClonado['nombre_documento_detalle_base'], 
                $dataDocumentoClonado['origen_documento_inicio'], 
                $dataDocumentoClonado['origen_fk_documento_inicio'], 
                $dataDocumentoClonado['origen_documento_fin'], 
                $dataDocumentoClonado['origen_fk_documento_fin'], false);
            $Documento_compra->fetch($documento_generado);
            
            $dataDocumentoLigado = $Documento->ligar_documento( $Documento_compra , $_SESSION['usuario'] ); 
            $documento_generado_movimiento = $dataDocumentoLigado["documento_movimiento_id"];

            $Documento->ligar_documento_detalle($documento_generado_movimiento,$Documento_compra);

            $Documento->cambiar_estado(3); // Estado Completa
        }
    }
    echo json_encode(['success' => true, 'message' => 'Facturas generadas exitosamente.', 'data' => $data]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error al generar las facturas: ' . $e->getMessage()]);
    exit;
}
