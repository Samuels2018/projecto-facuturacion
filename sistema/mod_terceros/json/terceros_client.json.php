<?php
//header('Content-Type: text/html; charset=utf-8');
session_start();
require('../../conf/conf.php');


if ($_GET['cliente'] > 0) {
  $wherecliente = " AND rowid = ".$_GET['cliente'];
}

$sql = "SELECT rowid, nombre, apellidos,
            impuesto_cliente_aplica_recargo_equivalencia,
            impuesto_cliente_fk_diccionario_regimen_iva,
            impuesto_cliente_lleva_retencion,
            aplicar_descuento_por_articulo,
            aplicar_descuento_volumen,
            forma_pago
      FROM fi_terceros  where entidad = " . $_SESSION['Entidad'] . $wherecliente;
$db = $dbh->prepare($sql);
$db->execute();

$row = $db->fetch(PDO::FETCH_ASSOC);

echo json_encode($row);
