<?php

if (!defined('ENLACE_WEB')):
    session_start();
    require_once "../../conf/conf.php";
endif;

include_once(ENLACE_SERVIDOR . "mod_documentos_mercantiles/object/documentos_pagos.object.php");


$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['fk_documento'], $data['monto'], $data['forma_pago'], $data['comentario'], $data['fecha_pago'], $data['tipo'] )) {
    echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios']);
    exit;
}

try {
    $invoicePayment = new DocumentosPagos($dbh, $_SESSION['Entidad']);

    $result = $invoicePayment->create([
        'fk_documento' => $data['fk_documento'],
        'tipo' => $data['tipo'],
        'forma_pago' => $data['forma_pago'],
        'monto' => $data['monto'],
        'comentario' => $data['comentario'],
        'usuario' => $_SESSION['usuario'], 
        'fecha_pago' => $data['fecha_pago'],
    ]);

    if ($result['success']) {
        echo json_encode(['success' => true, 'message' => 'Pago registrado con éxito']);
    } else {
        echo json_encode(['success' => false, 'message' => $result['message'] , 'info'=> $result['error_info']   ]);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
