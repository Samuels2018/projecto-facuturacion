<?php

header('Content-Type: text/html; charset=utf-8');
session_start();
require '../../conf/conf.php';


$id_producto = $_POST['id_articulo'];
$aplica_descuento_volumen = $_POST['aplica_descuento_volumen'];
$aplica_descuento_articulo = $_POST['aplica_descuento_articulo'];
$cantidad = $_POST['cantidad'];
$total = $_POST['total'];
$id_listaprecio = $_POST['id_listaprecio'];

// $ids_productos = array_map(function($item) { return $item['id']; }, $row_set);
// $ids_productos_str = implode(',', $ids_productos);
// $sql_politica = "SELECT pol.fk_producto, pol.tipo, detpol.base_imponible, detpol.porcentaje_descuento FROM fi_productos_politica_descuentos pol LEFT JOIN fi_productos_politica_descuentos_detalle detpol ON pol.rowid = detpol.fk_politica WHERE pol.fk_producto IN ($ids_productos_str)";

if($aplica_descuento_articulo){
  // Obtengo lista precios
  $sql_politica = "SELECT pol.fk_producto, pol.tipo, detpol.base_imponible, detpol.porcentaje_descuento FROM fi_productos_politica_descuentos pol LEFT JOIN fi_productos_politica_descuentos_detalle detpol ON pol.rowid = detpol.fk_politica WHERE pol.fk_producto = $id";
}
if($aplica_descuento_volumen){
  // Obtengo Descuentos por volumen
  $sql_politica = "SELECT pol.fk_producto, pol.tipo, detpol.base_imponible, detpol.porcentaje_descuento FROM fi_productos_politica_descuentos pol LEFT JOIN fi_productos_politica_descuentos_detalle detpol ON pol.rowid = detpol.fk_politica WHERE pol.fk_producto = $id";
}

$sql_politica = "SELECT pol.fk_producto, pol.tipo, detpol.base_imponible, detpol.porcentaje_descuento FROM fi_productos_politica_descuentos pol LEFT JOIN fi_productos_politica_descuentos_detalle detpol ON pol.rowid = detpol.fk_politica WHERE pol.fk_producto = $id";

try{
  $dbPolitica = $dbh->prepare($sql_politica);
  $dbPolitica->execute();
  $politicas = $dbPolitica->fetchAll();
  // $politicas = $dbPolitica->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($politicas);
}
catch(Exception	$ex){
  echo $ex->getMessage();
  return;
}