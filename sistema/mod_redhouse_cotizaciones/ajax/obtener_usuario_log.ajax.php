<?php

if (!defined('ENLACE_SERVIDOR')) {
    session_start();
    require_once "../../conf/conf.php";
}
header('Content-Type: application/json');



function message_response($message = "", $flag = false, $data = [])
{

    echo json_encode([
        'success' => $flag,
        'message' => $message,
        'data' => $data,
    ]);
}

function get_create_user_quotation($dbh)
{

    $control = false;
    $entidad = $_SESSION['Entidad'];

    if (empty($_GET['rowid'])) return  message_response(['El parametro cliente es obligatorio']);

    $rowid = $_GET['rowid'];

    $sql = "SELECT
	
	    concat(	u.nombre  , ' ' , u.apellidos  ) as name ,
        DATE_FORMAT( t.creado_fecha , '%d/%m/%Y') as date
	
        FROM
            a_medida_redhouse_cotizaciones as t
            join fi_usuarios  as u on u.rowid = t.creado_fk_usuario
            and t.rowid = :rowid
                limit 1;
     ";

    $db = $dbh->prepare($sql);
    $db->bindValue(':rowid', $rowid, PDO::PARAM_INT);
    $control = $db->execute();

    $user = ['name' => 'No definido', 'date' => '0000'];

    while ($obj = $db->fetch(PDO::FETCH_ASSOC)) {

        $user['name'] = $obj['name'];
        $user['date'] = $obj['date'];
    }

    return message_response('Se ha ejecutado la consulta', $control, $user);
}


get_create_user_quotation($dbh);
