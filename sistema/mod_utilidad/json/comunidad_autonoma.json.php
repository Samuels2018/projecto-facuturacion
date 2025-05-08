<?php
session_start();
// Validación de sesión
if (!isset($_SESSION['usuario'])) {
  echo json_encode(['error' => 'Acceso no válido']);
  exit;
}

require_once('../../conf/conf.php');
require_once ENLACE_SERVIDOR . 'mod_utilidad/object/utilidades.object.php';

$utilidades = new Utilidades($dbh, $_SESSION["Entidad"]);
if ($_GET['pais'] > 0) {
  $ccaas = $utilidades->obtener_comunidades_autonomas($_GET['pais']);
  echo json_encode(['success' => true, 'data' => $ccaas]);
}else{
  echo json_encode(['success' => true, 'data' => []]);
}