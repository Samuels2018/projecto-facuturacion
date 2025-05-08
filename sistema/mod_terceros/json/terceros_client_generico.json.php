<?php
//header('Content-Type: text/html; charset=utf-8');
session_start();
require_once('../../conf/conf.php');


$query_tercero = "SELECT rowid, nombre, apellidos,
            impuesto_cliente_aplica_recargo_equivalencia,
            impuesto_cliente_fk_diccionario_regimen_iva,
            impuesto_cliente_lleva_retencion,
 
            forma_pago
            FROM fi_terceros 
            WHERE activo= 1 
            AND rowid = ( SELECT valor FROM fi_configuracion WHERE entidad = :entidad AND configuracion = 'cliente_defecto' ) ";
$stmtTercero = $dbh->prepare($query_tercero);
$stmtTercero->bindParam(':entidad', $_SESSION["Entidad"], PDO::PARAM_INT);
$stmtTercero->execute();
$rowTercero = $stmtTercero->fetch(PDO::FETCH_ASSOC);
$codigo_tercero_default = 0;
if ($rowTercero) {
    $codigo_tercero_default = $rowTercero['rowid'];
}

if($codigo_tercero_default!=0){
  echo json_encode( array( 'exito'=> true, 'data' => $rowTercero ) );
}else{
  echo json_encode( array( 'exito'=> false, 'data' => 'Su empresa, no tiene definido un cliente predefinido' ) );
}