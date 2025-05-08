<?php
session_start();

//si no hay usuario autenticado, cerrar conexion
if (!isset($_SESSION['usuario'])) {
     echo acceso_invalido();
     exit(1);
}


include_once("../../conf/conf.php");
include ENLACE_SERVIDOR . 'mod_europa_compra/object/compras.object.php';

$obj = new Compra($dbh, $_SESSION['Entidad']);
$resultado = null;
switch ($_POST["action"]) {
     case 'validar_serie_proveedor':
          $obj->id = $_POST["documento"];
          $result = $obj->validar_serie_proveedor($_POST["serie_proveedor"]);
          $resultado = json_encode($result);
          break;
     default:
          $result['exito'] = 0;
          $result['mensaje'] = "No se encontro la Accion";
          $resultado = json_encode($result);
          break;
}

echo $resultado;