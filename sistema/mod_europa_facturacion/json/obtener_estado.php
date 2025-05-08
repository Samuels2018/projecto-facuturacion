<?php
session_start();

// Validación de sesión
if (!isset($_SESSION['usuario'])) {
    echo json_encode(['error' => 'Acceso no válido']);
    exit;
}

require_once "../../conf/conf.php";

if ($_GET['tipo']=="hacienda"){

    $Utilidades->obtener_estados_verifactu();

   // var_dump($Utilidades->obtener_estados_verifactu);
    
    echo json_encode(['success' => true, 'data' =>  $Utilidades->obtener_estados_verifactu ]);

} else {

    $data = $Utilidades->obtener_estados_factura();
    echo json_encode(['success' => true, 'data' => $data]);
    
}
