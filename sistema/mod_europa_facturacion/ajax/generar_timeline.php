<?php
session_start();

// ValidaciÃ³n de sesiÃ³n
if (!isset($_SESSION['usuario'])) {
    echo json_encode(['error' => 'Acceso no vÃ¡lido']);
    exit;
}

require_once "../../conf/conf.php";

try {
    // ParÃ¡metros recibidos vÃ­a POST
    $fk_documento = $_POST['fk_documento'];
    $entidad = $_SESSION['Entidad']; // Tomado de la sesiÃ³n

    // Consulta de datos
    $sql = "
        SELECT 
            rowid, 
            fk_documento, 
            entidad, 
            respuesta_estado_envio , 
            FechaHoraHusoGenRegistro,
            respuesta_estado_registro, 
            respuesta_descripcion_registro_descripcion, 
            respuesta 
        FROM 
            fi_europa_facturas_huellas 
        WHERE 
            fk_documento = :fk_documento
            AND entidad = :entidad
        ORDER BY 
            FechaHoraHusoGenRegistro DESC
    ";
//            AND respuesta IS NOT NULL -- Filtrar solo registros donde 'respuesta' no sea NULL

    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':fk_documento', $fk_documento, PDO::PARAM_INT);
    $stmt->bindParam(':entidad', $entidad, PDO::PARAM_INT);
    $stmt->execute();

    $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Contar registros
    $count = count($registros);

    // Si no hay registros vÃ¡lidos, devolver un mensaje indicando que no hay datos
    if ($count === 0) {
        echo json_encode(['success' => false, 'message' => 'No hay datos disponibles para mostrar en el timeline.']);
    } else {
        echo json_encode(['success' => true, 'data' => $registros, 'count' => $count]);
    }
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>