<?php 

session_start();

include_once("../../conf/conf.php");
include_once(ENLACE_SERVIDOR."mod_citas_agenda/object/citas.object.php");



if ($_POST['action'] == "Crear_Cita"){

    $Citas = new Citas($dbh);
    $Citas->fecha       =  $_POST['date'];
    $Citas->hora_inicio =  $_POST['start'];
    $Citas->hora_fin    =  $_POST['end'];

    $Citas->fk_cliente    =  $_POST['fk_cliente'];
    $Citas->fk_estado    =  $_POST['fk_estado'];
    
    $Citas->fk_producto    =  $_POST['fk_producto'];
    $Citas->entidad    =  $_SESSION['Entidad'];


    $id = $Citas->insert();


    if (!empty($id)){
        $respuesta['id']            = $id;
        $respuesta['respuesta_txt'] = "Cita Creada con Exito";
        $respuesta['error']         = false; 
    } else {
        $respuesta['respuesta_txt'] = "Ocurrio un Error - ".$Citas->error;
        $respuesta['error']         = true; 
    }

    




}   else {
    $respuesta['respuesta_txt'] = "Accion No Encontrada";
    $respuesta['error']         = true; 
}



echo json_encode($respuesta);

