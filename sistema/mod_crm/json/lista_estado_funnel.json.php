<?php
//header('Content-Type: text/html; charset=utf-8');
session_start();
require('../../conf/conf.php');

$sql = "SELECT  
              color   as estilo     ,
              titulo as etiqueta    ,
              rowid         
        FROM fi_funnel  where entidad  = ".$_SESSION["Entidad"]." and borrado = 0  order by rowid ASC";
$db = $dbh->prepare($sql);
$db->execute();

while ($obj = $db->fetch(PDO::FETCH_ASSOC)) {
  $row['color']                =  $obj['color'];
  $row['etiqueta']                =  $obj['etiqueta'];
  $row['rowid']                =  $obj['rowid'];
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