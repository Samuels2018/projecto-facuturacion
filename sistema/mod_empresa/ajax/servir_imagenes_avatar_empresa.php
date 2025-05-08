<?php

session_start();
include "../../conf/conf.php";
// Obtiene la ruta del archivo a través del parámetro de consulta 'file'
require_once ENLACE_SERVIDOR . "mod_files/object/files.object.php";
require_once (ENLACE_SERVIDOR."mod_entidad/object/Entidad.object.php" );

$Entidad  = new Entidad($dbh);
$files = new Files($dbh);

//LA ENTIDAD DEL USUARIO EN SESION
$entidad_session = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : '';
if(empty($entidad_session))
{
    require_once ENLACE_SERVIDOR.'404.php';
    exit;    
}

$img = $_GET['img'];
$token = $_GET['token'];
$avatar_url = $Entidad->devolver_avatar_url_by_code($img, $token);

$split = strrpos($avatar_url, ".");
$extension = substr($avatar_url, $split + 1);
$extension = 'image/'.$extension;

if (strpos($avatar_url, 'https://ui-avatars') !== false) 
{
    // Obtiene el contenido de la imagen
    $image_data = file_get_contents($avatar_url);
    // Obtiene la extensión del archivo para el header Content-Type
    $extension = pathinfo($avatar_url, PATHINFO_EXTENSION);
    $mime_types = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        // Agrega más tipos MIME según sea necesario
    ];
    // Configura el encabezado Content-Type
    if (array_key_exists($extension, $mime_types)) {
        header('Content-Type: ' . $mime_types[$extension]);
    } else {
        header('Content-Type: application/octet-stream');
    }
    // Envía el contenido de la imagen
    echo $image_data;
}else{
    header('Content-Type: '.$extension);
    readfile($avatar_url);
}


?>
