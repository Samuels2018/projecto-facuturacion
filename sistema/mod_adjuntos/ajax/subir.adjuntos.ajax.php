<?php

SESSION_START();

require_once "../../conf/conf.php";
require_once ENLACE_SERVIDOR . "mod_files/object/files.object.php";
require_once(ENLACE_SERVIDOR . 'mod_adjuntos/object/adjuntos.object.php');
$Adjuntos = new Adjunto($dbh, $_SESSION['Entidad']);
$files = new Files($dbh);

// Obtener extensiones permitidas se pasa como parametro la categoria (imagen,texto, documento, comprimido, video o vacio si se se pueden subir todos los tipos)
//$extensiones = $files->obtenerExtensiones('imagen');
$extensiones = $files->obtenerExtensiones('');
$extensiones = array_column($extensiones, 'extension');



$tipo = $_REQUEST['tipo'];



$UploadDirectory = ENLACE_FILES_EMPRESAS . 'imagenes/entidad_' . $_SESSION['Entidad'] . '/'.$tipo.'/';
// Verificar el tamaño del archivo
if ($_FILES["FileInput"]["size"] > 5242880) {
    $consulta['error'] = 1;
    $consulta['datos'] = $files->obtenerMensajeErrorSubida('Excede tamanio');
    echo json_encode($consulta);
    exit(1);
}


if (!in_array(strtolower($_FILES['FileInput']['type']), $extensiones)) {
   
    $consulta['error'] = 1;
    $consulta['datos'] = $files->obtenerMensajeErrorSubida('No permitido',$_FILES['FileInput']['type']);
    echo json_encode($consulta);
    exit();
}


$File_Name = strtolower($_FILES['FileInput']['name']);
$File_Ext = substr($File_Name, strrpos($File_Name, '.')); //get file extension
$Random_Number = rand(0, 9999999999)/*  . date('dHi') */; //rand(0, 9999999999); //Random number to be added to name.

$NewFileName = $Random_Number . $File_Ext; //new file name
chmod(ENLACE_FILES_EMPRESAS, 0777);

// Crear el directorio si no existe
if (!file_exists($UploadDirectory)) {
    mkdir($UploadDirectory, 0777, true);
}

if (move_uploaded_file($_FILES['FileInput']['tmp_name'], $UploadDirectory . $NewFileName)) {

    
     // Asignar valores a las propiedades del objeto
    $Adjuntos->entidad = $_SESSION['Entidad'];
    $Adjuntos->fk_documento = $_REQUEST['fk_documento'];
    $Adjuntos->tipo_documento = $_REQUEST['tipo'];
    $Adjuntos->label = $NewFileName;
    $Adjuntos->descripcion = $File_Name;
    $Adjuntos->creado_fk_usuario = $_SESSION['usuario'];
    // Realizar la inserción a través de la función insertar_adjunto
    $resultado = $Adjuntos->insertar_adjunto();
    
    if ($resultado['error'] == 0) {
        $consulta['error'] = 0;
        $consulta['datos'] = $resultado['datos'];
    } else {
        $consulta['error'] = 1;
        $consulta['datos'] = $resultado['datos'];
    }

    echo json_encode($consulta);


} else {
    $consulta['error'] = 1;
    $consulta['datos'] = $files->obtenerMensajeErrorSubida($_FILES['FileInput']['error'],$_FILES['FileInput']['type']);
    echo json_encode($consulta);
}

