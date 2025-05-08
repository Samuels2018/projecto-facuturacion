<?php

if (!defined('ENLACE_SERVIDOR')) {session_start();require_once "../../conf/conf.php";}
header('Content-Type: application/json');



function message_response( $message="", $flag=false , $data=[]){

    echo json_encode( [
        'success' => $flag,
        'message' => $message ,
        'data' => $data ,
    ]);

}

function get_create_user( $dbh ){

    $control = false;
    $entidad = $_SESSION['Entidad'];

    if (empty($_GET['customer_id'])) return  message_response([ 'El parametro cliente es obligatorio']);

    $customer_id = $_GET['customer_id'];

    $sql="SELECT
            concat(	u.nombre  , ' ' , u.apellidos  ) as name ,
            DATE_FORMAT( t.creado_fecha , '%d/%m/%Y') as date
        FROM
            fi_terceros  as t
            
            join fi_usuarios  as u on u.rowid = t.creado_fk_usuario
        WHERE
            t.creado_fk_usuario is not null 
            and t.rowid = :customer_id
            and t.entidad = :entidad

        limit 1;
     " ;

    $db=$dbh->prepare($sql);
    $db->bindValue(':customer_id',$customer_id ,PDO::PARAM_INT);
    $db->bindValue(':entidad',$entidad ,PDO::PARAM_INT);

    $control = $db->execute();

    $user = [ 'name' => 'No definido' , 'date' => '0000'];

    while ($obj = $db->fetch(PDO::FETCH_ASSOC)) {

        $user['name'] = $obj['name'];
        $user['date'] = $obj['date'];

    } 

    return message_response('Se ha ejecutado la consulta',$control , $user);
}


get_create_user( $dbh);

