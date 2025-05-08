<?php


session_start();
include '../../conf/conf.php';

if (!isset($_SESSION['usuario'])) {
  echo json_encode(['error' => 'Acceso no válido']);
  exit;
}

if ($_POST['category'] == "Repercutido") {
  $tabla = "fi_europa_facturas";
} else if ($_POST['category'] == "Soporte") {
  $tabla = "fi_europa_compras";
}


$key = array_search($_POST['mes'], $Utilidades->meses);

if ($key !== false) {
  $fechas = $Utilidades->obtenerRangoMes($key);
}

require_once(ENLACE_SERVIDOR . "mod_reporte/object/reporte.object.php");
$Reporte = new Reporte($dbh, $_SESSION['Entidad']);

$Reporte->reporte_general_ivas($tabla, $fechas['primer_dia'], $fechas['ultimo_dia']);

$response = array(
  "draw" => 1,
  "recordsTotal" => $Reporte->DOCUMENTOS_DETALLES!=null?count($Reporte->DOCUMENTOS_DETALLES):0,
  "recordsFiltered" => $Reporte->DOCUMENTOS_DETALLES!=null?count($Reporte->DOCUMENTOS_DETALLES):0,
  "data" => $Reporte->DOCUMENTOS_DETALLES!=null?$Reporte->DOCUMENTOS_DETALLES:[],
);
echo json_encode($response);