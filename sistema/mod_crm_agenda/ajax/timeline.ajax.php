<?php
SESSION_START();
// USER
if (empty($_SESSION['Usuario'])) {
 //   header("location: " . ENLACE_WEB . "inicio/");
   // exit(1);
}

include_once "../../conf/conf.php";

if (isset($_GET['term'])) {
    # conectare la base de datos
    //recibe un id
    $term = $_GET['term'];
    $fk_flujo = $_GET['fk_flujo'];
    $return_arr = array();

    $sql = "SELECT cpa.*,
    da.icono,
    de.etiqueta as estado_actividad,
    de.color as color_actividad,
    da.nombre as nombre_actividad
    FROM oportunidad_actividades cpa 
    LEFT JOIN diccionario_crm_actividades da ON da.rowid = cpa.fk_diccionario_actividad
    left join diccionario_crm_actividades_estado de on de.rowid = cpa.fk_estado
    where cpa.fk_oportunidad = $term order by cpa.rowid DESC";


    $db = $dbh->prepare($sql);
    $db->execute();
    $count = $db->rowCount();

    if ($count > 0) {
        while ($data = $db->fetch(PDO::FETCH_OBJ)) :

            setlocale(LC_ALL, 'spanish');
            $fecha_det = explode('-', $data->vencimiento_fecha);
            $fecha_det_hora = explode(':', $fecha_det[2]);
            $fecha_det_hora = explode(' ', $fecha_det[2]);
            $fecha_det[1]; // imprimiría el mes 
            $monthNum  = $fecha_det[1];
            $dateObj   = DateTime::createFromFormat('!m', $monthNum);
            $monthName = strftime('%B', $dateObj->getTimestamp());


            $estatus_txt = '<strong style="color:' . $data->color_actividad . '">' . $data->estado_actividad . '</strong';

            $row_array['content'] = '<i class="fa ' . $data->icono . '" aria-hidden="true"></i> ' . "$data->nombre_actividad";
            $row_array['date'] = $fecha_det_hora[0] . ' ' . $monthName;
            $row_array['rowid'] = $data->rowid;
            $row_array['fk_estado'] = $estatus_txt;

            //cuando sea tipo timeline el texto es el comentario
            // los histos no tienen estado, los que son tipo timeline
            // si es tipo timeline, no usar colores
            if ($data->tipo == 'timeline') {
                $row_array['fk_estado']  = 'Hito';
                $row_array['content'] = '<i class="fa fa fa-compass" aria-hidden="true"></i> ' . $data->comentario;
            }

            array_push($return_arr, $row_array);

        endwhile;
        /* Codifica el resultado del array en JSON. */
        echo json_encode($return_arr);
    }
}
