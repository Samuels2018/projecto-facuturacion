<?php

session_start();
include "../../conf/conf.php";
// Obtiene la ruta del archivo a través del parámetro de consulta 'file'
require_once ENLACE_SERVIDOR . "mod_files/object/files.object.php";
$files = new Files($dbh);


$img = $_GET['img'];
//la entidad de la imagen
$dataimage = explode('/',$img);
$entidad_imagen = $dataimage[0]; // El número extraído directamente


//LA ENTIDAD DEL USUARIO EN SESION
$entidad_session = isset($_SESSION['Entidad']) ? $_SESSION['Entidad'] : '';

if(empty($entidad_session))
{
    require_once ENLACE_SERVIDOR.'404.php';
    exit;    
}else if($entidad_session != $entidad_imagen)
{   
    require_once ENLACE_SERVIDOR.'404.php';
    exit;    
}


$file =  ENLACE_FILES_EMPRESAS.'imagenes/entidad_'.$img;

$split = strrpos($file, ".");
$extension = substr($file, $split + 1);
$extension = 'image/'.$extension;

// Obtener extensiones permitidas se pasa como parametro la categoria (imagen,texto, documento, comprimido, video o vacio si se se pueden subir todos los tipos)
$extensiones = $files->obtenerExtensiones('imagen');
$extensiones = array_column($extensiones, 'extension');

//en caso de ser jpeg
if($extension === 'image/jpg'){ $extension = 'image/jpeg'; }
if (!in_array(strtolower($extension), $extensiones)) {
    
    $consulta['error'] = 1;
    $consulta['datos'] = $files->obtenerMensajeErrorSubida('No permitido');
    echo json_encode($consulta);
    exit();
}

if (file_exists($file)) {
    header('Content-Type: '.$extension);
    readfile($file);
}
?>
