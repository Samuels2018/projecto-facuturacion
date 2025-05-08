<?php

session_start();
require_once '../../conf/conf.php';

if ($_SESSION['usuario'] == NULL) {
  $respuesta['error']       = 1;
  $respuesta['sesion_rota'] = 1;
  echo json_encode($respuesta);
  exit(1);
}

$term = trim(strip_tags($_GET['term']));

if ($_GET['cliente'] == 1) {
  $where_tercero = "  a.cliente = 1   ";
}

if ($_GET['proveedor'] == 1) {
  $where_tercero = "  a.proveedor = 1   ";
}

$sql = "
            Select 
              a.rowid     , 
              a.nombre    , 
              a.apellidos , 
              a.email     ,
              a.forma_pago,
              a.telefono ,
              IFNULL(a.impuesto_cliente_aplica_recargo_equivalencia,0) AS impuesto_cliente_aplica_recargo_equivalencia  ,
              IFNULL(a.impuesto_cliente_fk_diccionario_regimen_iva,0) AS impuesto_cliente_fk_diccionario_regimen_iva  ,
              IFNULL(a.impuesto_cliente_lleva_retencion,0) AS impuesto_cliente_lleva_retencion,
              IFNULL(a.fk_lista_precio,0) AS fk_lista_precio ,
              a.fk_agente
 
            from fi_terceros  a
              LEFT JOIN 
              diccionario_clientes_categorias dcc 
              ON 
              dcc.rowid = a.fk_categoria_cliente

              where 
              $where_tercero              
              AND               
              (CONCAT(a.nombre,' ',a.apellidos) LIKE '%" . $term . "%' or a.email like '%" . $term . "%' or a.cedula LIKE '%" . $term . "%')  and  a.entidad = " . $_SESSION['Entidad'] . " and a.activo = 1 and a.borrado = 0
              
              limit 0,10  ";

$db = $dbh->prepare($sql);
$db->execute();
$data_json_string = '';
while ($obj = $db->fetch(PDO::FETCH_ASSOC)) {
  $row['value'] = ('' . ucwords(strtolower($obj['nombre'])) . " " . ucwords(strtolower($obj['apellidos'])) . " • " . strtolower($obj['email']));
  $row['id']                                            =  intval($obj['rowid']);
  $row['forma_pago']                                    =  $obj['forma_pago'];
  $row['impuesto_cliente_lleva_retencion']              =  $obj['impuesto_cliente_lleva_retencion'];
  $row['impuesto_cliente_fk_diccionario_regimen_iva']   =  $obj['impuesto_cliente_fk_diccionario_regimen_iva'];
  $row['impuesto_cliente_aplica_recargo_equivalencia']  =  $obj['impuesto_cliente_aplica_recargo_equivalencia'];
  $row['fk_lista_precio']                               =  $obj['fk_lista_precio'];
  $row['telefono']                                      =  $obj['telefono'];
  $row['fk_agente']                                     =  $obj['fk_agente'];
  $row['email']                                     =  $obj['email'];
  $row_set[] = $row;
  $data_json_string .= json_encode($row).',';
}

if ($data_json_string != '' ) {
  $data_json_string = preg_replace('/,+/', ',', $data_json_string);
  $data_json_string = rtrim($data_json_string, ',');
  $data_json_for_string = '['.$data_json_string.']';
  // Decodifica la cadena JSON en un array
  $data_array = json_decode($data_json_for_string, true);
  if (json_last_error() === JSON_ERROR_NONE) {
      $data_json = json_encode($data_array);
      echo $data_json;
  } else {
      echo 'Error: La cadena JSON no es válida.';
  }
}


// if ($data_json_string != '' ) {
//   $data_json = substr($data_json_string, 0, -1);
//   echo ('['.$data_json.']');
// }