<?php
session_start();

include_once("../conf/conf.php");

require_once ENLACE_SERVIDOR . "mod_logs/LoggerSistema.php";

// Obtener los datos enviados por AJAX
$fecha_inicio = $_GET['fecha_inicio'] ?? null;
$fecha_fin = $_GET['fecha_fin'] ?? null;
// $entidad = ( count($_SESSION['multientidad'])>0 ? $_SESSION["nombre_entidad"][0]: $_SESSION["nombre_entidad"] );
$entidad = $_SESSION['EntidadNombre'];

if (!$fecha_inicio || !$fecha_fin || !$entidad) {
    echo json_encode(['error' => 'Faltan parámetros']);
    exit;
}

try {
    // Instanciar la clase Logger y llamar al método
    $logger = new LoggerSistema();
    $response = $logger->downloadLogs($fecha_inicio, $fecha_fin, $entidad);

    // Devolver respuesta en formato JSON
    echo $response;
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
