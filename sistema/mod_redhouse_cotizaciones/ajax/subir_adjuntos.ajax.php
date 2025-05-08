<?php

SESSION_START();

require_once "../../conf/conf.php";
require_once ENLACE_SERVIDOR . "mod_files/object/files.object.php";
$files = new Files($dbh);

// Obtener extensiones permitidas se pasa como parametro la categoria (imagen,texto, documento, comprimido, video o vacio si se se pueden subir todos los tipos)
//$extensiones = $files->obtenerExtensiones('imagen');
$extensiones = $files->obtenerExtensiones('');
$extensiones = array_column($extensiones, 'extension');


$UploadDirectory = ENLACE_FILES_EMPRESAS . 'imagenes/entidad_' . $_SESSION['Entidad'] . '/cotizacion/';
// Verificar el tamaño del archivo
if ($_FILES["FileInput"]["size"] > 5242880) {
    $consulta['error'] = 1;
    $consulta['datos'] = $files->obtenerMensajeErrorSubida('Excede tamanio');
    echo json_encode($consulta);
    exit(1);
}


if (!in_array(strtolower($_FILES['FileInput']['type']), $extensiones)) {
   
    $consulta['error'] = 1;
    $consulta['datos'] = $files->obtenerMensajeErrorSubida('No permitido');
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

     $sql = "INSERT INTO a_medida_redhouse_cotizaciones_adjuntos (fk_cotizacion, label, activo, descripcion, creado_fecha, creado_fk_usuario, borrado, borrado_fecha,borrado_fk_usuario) VALUES (:fk_cotizacion, :label, 1, :descripcion, NOW(), :creado_fk_usuario, 0, NOW(),0)";
    $st = $dbh->prepare($sql);
    $st->bindValue(':fk_cotizacion', $_POST['fk_cotizacion'], PDO::PARAM_INT);
    $st->bindValue(':label', $NewFileName, PDO::PARAM_STR);
    $st->bindValue(':descripcion', $File_Name, PDO::PARAM_STR);
    $st->bindValue(':creado_fk_usuario', $_SESSION['usuario'], PDO::PARAM_INT);
    $result = $st->execute();


    if ($result) {
        $consulta['error'] = 0;
        $consulta['datos'] = $result;
    } else {
        $a = implode('-', $st->errorInfo());
        $a .= implode('-', $dbh->errorInfo());
        $consulta['error'] = 1;
        $consulta['datos'] = $a;
    }


    echo json_encode($consulta);
} else {
    $consulta['error'] = 1;
    $consulta['datos'] = $files->obtenerMensajeErrorSubida($_FILES['FileInput']['error']);
    echo json_encode($consulta);
}
