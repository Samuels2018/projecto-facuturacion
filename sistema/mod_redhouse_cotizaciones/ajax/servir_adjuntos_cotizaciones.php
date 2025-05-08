<?php

session_start();
include "../../conf/conf.php";
// Obtiene la ruta del archivo a través del parámetro de consulta 'file'
require_once ENLACE_SERVIDOR . "mod_files/object/files.object.php";
$files = new Files($dbh);

$img = $_GET['img'];
// la entidad de la imagen
$dataimage = explode('/', $img);
$entidad_imagen = $dataimage[0]; // El número extraído directamente

// LA ENTIDAD DEL USUARIO EN SESION
$entidad_session = isset($_SESSION['Entidad']) ? $_SESSION['Entidad'] : '';

if (empty($entidad_session)) {
    require_once ENLACE_SERVIDOR . '404.php';
    exit;
} else if ($entidad_session != $entidad_imagen) {
    require_once ENLACE_SERVIDOR . '404.php';
    exit;
}

$file = ENLACE_FILES_EMPRESAS . 'imagenes/entidad_' . $img;
$split = strrpos($file, ".");
$extension = strtolower(substr($file, $split + 1));

// Mapear las extensiones a sus tipos MIME
$mime_types = array(
    'pdf' => 'application/pdf',
    'jpg' => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png' => 'image/png',
    'gif' => 'image/gif',
    'bmp' => 'image/bmp',
    'webp' => 'image/webp',
    'doc' => 'application/msword',
    'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'xls' => 'application/vnd.ms-excel',
    'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    'zip' => 'application/zip',
    'rar' => 'application/x-rar-compressed',
    // Agrega otros tipos MIME según sea necesario
);

if (!array_key_exists($extension, $mime_types)) {
    require_once ENLACE_SERVIDOR . '404.php';
    exit;
}

if (file_exists($file)) {
    // Enviar el tipo de contenido correcto
    header('Content-Type: ' . $mime_types[$extension]);
    // Forzar descarga si se requiere
    // header('Content-Disposition: attachment; filename="'.basename($file).'"');
    readfile($file);
    exit;
} else {
    require_once ENLACE_SERVIDOR . '404.php';
    exit;
}
?>
