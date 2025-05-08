<?php

header('Content-Type: text/html; charset=utf-8');
session_start();
require '../../conf/conf.php';
include ENLACE_SERVIDOR . 'mod_productos/object/productos.object.php';


$term = trim(strip_tags($_GET['term']));

$filtro = '';

/*if ($_REQUEST['tipo'] == "productos") {
  $filtro = " AND p.tipo  = 1 ";
} else if ($_REQUEST['tipo'] == "servicios") {
  $filtro = " AND p.tipo  = 2 ";
}*/

$entidad = $_SESSION['Entidad'];

$sql = "
  SELECT
    p.rowid,
    p.ref,
    p.tipo,
    p.label,
    p.impuesto_fk,
    pc.impuesto,
    pc.subtotal,
    pc.total,
    pc.moneda,
    moneda.etiqueta AS moneda_txt,
    moneda.simbolo AS moneda_simbolo,
    moneda.codigo AS moneda_codigo,
    p.descripcion,
    (
        SELECT GROUP_CONCAT(CONCAT(" . $_SESSION['Entidad'] . ", '/productos/', pi.label) SEPARATOR ',') 
        FROM fi_productos_imagenes pi 
        WHERE p.rowid = pi.fk_producto
    ) AS imagenes,
    pc.impuesto AS impuesto_detalle
FROM
    fi_productos p
LEFT JOIN
    (
        SELECT 
            pc1.fk_producto,
            pc1.impuesto,
            pc1.subtotal,
            pc1.total,
            pc1.moneda
        FROM 
            fi_productos_precios_clientes pc1
        INNER JOIN 
            (SELECT fk_producto, MAX(rowid) AS max_rowid
             FROM fi_productos_precios_clientes
             GROUP BY fk_producto) subquery
        ON pc1.fk_producto = subquery.fk_producto
        AND pc1.rowid = subquery.max_rowid
    ) pc ON p.rowid = pc.fk_producto
LEFT JOIN diccionario_monedas moneda ON moneda.rowid = pc.moneda
WHERE 
    (p.label LIKE '%$term%' OR p.ref LIKE '%$term%')
    AND p.eliminado = 0 
    AND p.entidad = " . $_SESSION['Entidad'] . "
LIMIT 0, 10;
 
";

if ($filtro != '') {
  $sql .= $filtro;
}

$sql .= ' LIMIT 0, 10';


$db = $dbh->prepare($sql);
$db->execute();
// RUN RECORDS


while ($obj = $db->fetch(PDO::FETCH_ASSOC)) :


  $total_valor = (float) $obj['subtotal'];
  //hacemos la conversion de la moneda

  // $hubo_cambio_valor = false;
  // //el valor, el codigo de origen del producto y el codigo destino al que haremos la conversion
  // $total_valor_convertido = conversionMoneda_old($total_valor, $obj['moneda_codigo'], $_REQUEST['codigo_moneda_oportunidad'], $_REQUEST['tipo_cambio_oportunidad']);

  // //Total valor si fue distinto es que si hubo un cambio
  // if (floatval($total_valor_convertido) != floatval($total_valor)) {
  //   $hubo_cambio_valor = "si";
  // }


  //$row['codigo_moneda_oportunidad'] = $_REQUEST['codigo_moneda_oportunidad'];
  $row['value']       = ($obj['label']);
  $row['id']          = (int) $obj['rowid'];
  $row['impuesto']    =  $obj['impuesto'];
  $row['subtotal'] = number_format($total_valor, 2, '.', '');
  $row['total']       = $obj['total'];
  $row['tipo']        = $obj['tipo'];
  $row['imagenes']    = explode(",", $obj['imagenes']);
  $row['moneda']        = $obj['moneda'];
  $row['moneda_simbolo'] = $obj['moneda_simbolo'];
  $row['moneda_codigo'] = $obj['moneda_codigo'];
  $row['descripcion']        = $obj['descripcion'];
  // $row['hubo_cambio_valor']        = $hubo_cambio_valor;
  $row['precio_original'] = round($total_valor, 2);
  $row['impuesto_fk'] = $obj['impuesto_fk'];
  $row['impuesto_detalle'] = $obj['impuesto_detalle'];

  if ((int)$row['tipo'] == 3) {

    $productos = new Productos($dbh, $_SESSION['Entidad']);
    $productos->fetch($obj['rowid']);
   }

 
  $row_set[] = $row;
endwhile;
 

echo json_encode($row_set);
