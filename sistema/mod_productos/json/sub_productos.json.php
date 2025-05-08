<?php

header('Content-Type: text/html; charset=utf-8');
session_start();
require '../../conf/conf.php';

$term = trim(strip_tags($_GET['term']));




if ($_REQUEST['tipo'] == "productos") {
  $filtro = " and tipo  = 1 ";
} else if ($_REQUEST['tipo'] == "servicios") {
  $filtro = " and tipo  = 0 ";
}



$sql =
  "
  SELECT
    p.rowid,
    p.ref,
    p.tipo,
    p.label,
    p.CABYS_descripcion,
    p.CABYS_FECHA,
    (select GROUP_CONCAT( concat( pi.rowid,'/productos/',pi.label)  SEPARATOR ',')  from      fi_productos_imagenes pi where p.rowid = pi.fk_producto )  AS imagenes 
FROM
    fi_productos p  
  WHERE ((p.label LIKE '%" . $term . "%' )   or (p.ref LIKE '%" . $term . "%' ))
  AND p.eliminado = 0 
  AND p.tipo not in ('3')
  AND p.entidad = " . $_SESSION['Entidad'] . "
  LIMIT 0,10  " ;

$db = $dbh->prepare($sql);
$db->execute();
// RUN RECORDS


while ($obj = $db->fetch(PDO::FETCH_ASSOC)) :
  
    $row['value']       = ($obj['label']);
    $row['id']          = (int) $obj['rowid'];


    $row['tipo']        = $obj['tipo'];
    $row['cabys_code']  = (!is_null($obj['CABYS_codigo'])) ? $obj['CABYS_codigo'] : 'N/A';
    $row['cabys_tax']   = (!is_null($obj['CABYS_impuesto'])) ? $obj['CABYS_impuesto'] : 'N/A';
    $row['cabys_desc']  = (!is_null($obj['CABYS_descripcion'])) ? $obj['CABYS_descripcion'] : 'N/A';
    $row['imagenes']    = explode("," , $obj['imagenes']);
 
    $row['moneda']        = $precios['moneda'];

     

  $row_set[] = $row;
endwhile;

echo json_encode($row_set);