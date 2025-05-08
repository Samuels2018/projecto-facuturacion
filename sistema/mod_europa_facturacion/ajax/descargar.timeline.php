<?php
session_start();
require_once "../../conf/conf.php";

if (!isset($_SESSION['usuario'])) {
    die("Acceso no autorizado.");
}

try {
    // Recibimos el ID de la factura
    $id = $_GET['id'];
    $entidad = $_SESSION['Entidad'];

    // Consulta para obtener los datos del XML
    $sql = "
        SELECT 
            respuesta, NumSerieFactura
        FROM 
            fi_europa_facturas_huellas 
        WHERE 
            rowid = :id 
            AND entidad = :entidad
    ";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':entidad', $entidad, PDO::PARAM_INT);
    $stmt->execute();

    $registro = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($registro) {
        // Verificar si 'respuesta' es NULL
        if ($registro['respuesta'] === null) {
            // Redirigir con mensaje de error si no existe
            echo "El archivo no existe.";
            exit;
        } else {
            $xmlContent = $registro['respuesta'];
            $fileName = $registro['NumSerieFactura'] . '.xml';

            // Configurar encabezados para la descarga del archivo
            header("Content-Disposition: attachment; filename=Respuesta-" . $fileName);
            header("Content-Type: application/xml");

            // Enviar el contenido del archivo XML
            echo $xmlContent;
            exit;
        }
    } else {
        // Redirigir con mensaje de error si no hay registro
        echo "El archivo no existe.";
        exit;
    }
} catch (Exception $e) {
    // Redirigir con mensaje de error en caso de excepciÃ³n
    echo "El archivo no existe.";
    exit;
}