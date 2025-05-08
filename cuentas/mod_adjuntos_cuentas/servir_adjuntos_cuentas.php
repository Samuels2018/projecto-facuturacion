<?php

session_start();
include "../conf/conf.php";
// Obtiene la ruta del archivo a través del parámetro de consulta 'file'
require_once ENLACE_SERVIDOR."mod_empresa/object/empresa.object.php";
// Obtener los datos de la empresa
$Empresa = new empresa($dbh);
$fiche = $_GET['img'];
$kit_pdf_firmado_url_en_disco = '';
if (!empty($_GET['img'])) {
    $datos_empresa = $Empresa->fetch($fiche);
    $kit_pdf_firmado_url_en_disco = $datos_empresa['kit_pdf_firmado_url_en_disco'];
}else{
    require_once ENLACE_SERVIDOR . '404.php';
    exit;
}



$img = $_GET['img'];


$file = ENLACE_FILES_EMPRESAS . 'kit_digital/empresa_'.$img.'/'.$kit_pdf_firmado_url_en_disco;
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

echo "el archivo ".$file;
if (file_exists($file)) {
    echo "PASA PARA ACA";

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
