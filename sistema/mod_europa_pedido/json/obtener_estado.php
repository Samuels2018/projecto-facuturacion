<?php
session_start();

// Validación de sesión
if (!isset($_SESSION['usuario'])) {
    echo json_encode(['error' => 'Acceso no válido']);
    exit;
}

require_once "../../conf/conf.php";
 
$data = $Utilidades->obtener_estados_pedido();

echo json_encode(['success' => true, 'data' => $data]);

?>
