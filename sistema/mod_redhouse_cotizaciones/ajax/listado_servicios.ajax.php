<?php

    if (!defined('ENLACE_SERVIDOR')) {
        session_start(); 
        require_once('../../conf/conf.php');
    }

    $rowid = $_GET['fiche'];

    // WHERE 
    $where ='';
    $where .=' WHERE  sc.fk_cotizacion = '.$rowid.' ';

    $sql= "SELECT
                p.ref                       ,
                p.label                     ,
                sc.cantidad                 ,
                sc.cantidad_dias            ,
                sc.tipo_duracion            ,
                sc.precio_unitario          ,
                sc.precio_tipo_impuesto     ,
                sc.precio_total,
                sc.rowid 

                FROM    a_medida_redhouse_cotizaciones_cotizaciones_servicios   sc 
        left   JOIN    fi_productos   p ON p.rowid  = sc.fk_producto ".$where."
        order by sc.rowid DESC ";


$db = $dbh->prepare($sql);
$db->execute();
$tr = "";
$contador = 1;


// RUN RECORDS
while ($data = $db->fetch(PDO::FETCH_OBJ)):
     
    $duracion = !empty($data->tipo_duracion) ? $data->tipo_duracion : 'Días';

    $aa = "<button class='boton-servicio' style='margin-left:5px;background:none; border: none' onclick='editarServicio(".$data->rowid.")'>";
    $aa2 ='</button>';
 
    $tr.=
        '<tr class="ng-scope '. ( ($_REQUEST['actividad']==$data->rowid) ? 'shake-row' :'' ).'"  style="cursor:pointer;">
            <td>'.$contador.'</td>
            <td>'.ucwords($data->ref).'</td>
            <td>'.ucwords($data->label).'</td>
            <td><span class="badge bg-info">'.$data->cantidad.'</span></td>
            <td><span class="badge bg-info">'.intval($data->cantidad_dias).' Días</span></td>
            <td> <span class="badge bg-info">'.intval($data->tipo_duracion).' Horas</span></td>
            <td>'.numero_simple($data->precio_unitario).'</td>
            <td>'.numero_simple($data->precio_tipo_impuesto).'</td>
            <td><span class="badge bg-warning">'.$data->precio_total.'</span></td>
            <td>'.$aa.' <i class="fa fa-fw fa-edit" aria-hidden="true"></i> '.$aa2.' <button class="boton-servicio" style="border:none; background-color:transparent;"   onclick="eliminarServicio('.$data->rowid.')"><i class="fa fa-trash-o" aria-hidden="true"></i></button></td>
        </tr>';

        $contador++;

endwhile;

if ($tr == '') {
echo '<tr><td colspan="12" style="text-align:center">No se han encontrado Servicios/Productos en esta cotizacion </td></tr>';
}else {
    echo $tr;
}




 
