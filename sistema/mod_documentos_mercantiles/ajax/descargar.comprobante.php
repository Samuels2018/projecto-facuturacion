<?php
session_start();
require_once "../../conf/conf.php";

 //si no hay usuario autenticado, cerrar conexion
 if (!isset($_SESSION['usuario'])) {
    echo acceso_invalido();
         exit(1);
  }

  include_once(ENLACE_SERVIDOR."mod_europa_facturacion/object/facturas.object.php");
  include_once(ENLACE_SERVIDOR."mod_europa_compra/object/compras.object.php");

  $tipo = $_GET['tipo'];
  $id = $_GET['id'];

$Documento = new $tipo($dbh, $_SESSION['Entidad']);
$Documento->nombre_clase = $tipo;
$Documento->fetch($id);

if ($_SESSION['Entidad'] != $Documento->entidad) {
    echo acceso_invalido();
         exit(1);
}

$date = strtotime($Documento->fecha);
$month = date('m', $date);
$year = date('Y', $date);

$filePath = ENLACE_FILES_XML . "{$Documento->entidad}/{$Documento->tipo}/" . $year . "/{$Documento->referencia}.xml";

// Validar si el archivo existe antes de leerlo
if (file_exists($filePath)) {
    header("Content-Disposition: attachment; filename=" . $Documento->referencia . ".xml");
    header("Content-Type: application/xml");
    readfile($filePath);
} else {
    echo "El archivo no existe.";
}
