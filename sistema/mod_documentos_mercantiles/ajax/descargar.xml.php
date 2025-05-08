<?php
session_start();
require_once "../../conf/conf.php";

if (!isset($_SESSION['usuario'])) {
    die("Acceso no autorizado.");
}

try {
    // Recibimos el ID del documento
    $tipo = $_GET['tipo'];
    $id = $_GET['id'];

    $entidad = $_SESSION['Entidad'];

    // Consulta para obtener los datos del XML
    $sql = "SELECT respuesta, NumSerie".$tipo."
        FROM 
            fi_europa_".$tipo."s_huellas 
        WHERE 
            fk_".( $tipo=='compra'?'documento':'factura' )." = :id 
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
            echo "El archivo no existe.";
            exit;
        } else {
            $xmlContent = $registro['respuesta'];
            $fileName = $registro['NumSerie'.$tipo] . '.xml';

            // Configurar encabezados para la descarga del archivo
            header("Content-Disposition: attachment; filename=Respuesta-" . $fileName);
            header("Content-Type: application/xml");

            // Enviar el contenido del archivo XML
            echo $xmlContent;
            exit;
        }
    } else {
        echo "El archivo no existe.";
        exit;
    }
} catch (Exception $e) {
    echo "Error al procesar la solicitud.";
    exit;
}
