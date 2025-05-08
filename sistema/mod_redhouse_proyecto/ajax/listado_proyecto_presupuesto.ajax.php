<?php

    if (!defined('ENLACE_SERVIDOR')) {
        session_start(); 
        require_once('../../conf/conf.php');
    }

    $rowid = $_GET['fiche'];

    // WHERE 
    $where ='';
    $where .=' WHERE  sc.fk_proyecto = '.$rowid.' ';

    $sql= "SELECT
                p.ref                       ,
                p.label                     ,
                sc.cantidad                 ,
                sc.cantidad_dias            ,
                sc.tipo_duracion            ,
                sc.precio_unitario          ,
                sc.precio_tipo_impuesto     ,
                sc.precio_total,
                sc.precio_subtotal,
                sc.rowid 

                FROM    a_medida_redhouse_proyecto_presupuesto   sc 
        left   JOIN    fi_productos   p ON p.rowid  = sc.fk_producto ".$where."
        order by sc.rowid DESC ";


$db = $dbh->prepare($sql);
$db->execute();
$tr = "";
$contador = 1;


// RUN RECORDS
$impuesto = 0 ;
$montos_subtotales = 0;
$montos_totales = 0;
$moneda_simbolo = $Cotizacion->moneda_simbolo;




while ($data = $db->fetch(PDO::FETCH_OBJ)):
     
    $duracion = !empty($data->tipo_duracion) ? $data->tipo_duracion : 'Días';

    $aa = "<button class='boton-servicio' style='margin-left:5px;background:none; border: none' onclick='editarServicio(".$data->rowid.")'>";
    $aa2 ='</button>';

    $impuesto = $impuesto + floatval($data->precio_tipo_impuesto);
    $montos_totales = $montos_totales + floatval($data->precio_total);
    $montos_subtotales = $montos_subtotales + floatval($data->precio_subtotal);

 
    $tr.=
        '<tr class="ng-scope '. ( ($_REQUEST['actividad']==$data->rowid) ? 'shake-row' :'' ).'"  style="cursor:pointer;">
            <td>'.$contador.'</td>
            <td>'.ucwords($data->ref).'</td>
            <td>'.ucwords($data->label).'</td>
            <td><span class="badge bg-info">'.$data->cantidad.'</span></td>
            <td><span class="badge bg-info">'.intval($data->cantidad_dias).' Días</span></td>
            <td> <span class="badge bg-info">'.intval($data->tipo_duracion).' Horas</span></td>
            <td>'. $moneda_simbolo.' '.numero_simple($data->precio_unitario).'</td>
            <td>'. $moneda_simbolo.' '.numero_simple($data->precio_tipo_impuesto).'</td>
            <td><span class="badge bg-warning">'. $moneda_simbolo.' '.$data->precio_total.'</span></td>
        </tr>';

        $contador++;

endwhile;

if ($tr == '') {
echo '<tr><td colspan="12" style="text-align:center">No se han encontrado Servicios/Productos en esta cotizacion </td></tr>';
}else {
    
    $tr.=
        '<tr class=""  style="cursor:pointer;">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><strong>Impuestos: </strong></td>
            <td>'. $moneda_simbolo.' '.numero_simple($impuesto).'</td>
        </tr>';

     $tr.=
        '<tr class=""  style="cursor:pointer;">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><strong>Subtotal: </strong></td>
            <td>'. $moneda_simbolo.' '.numero_simple($montos_subtotales).'</td>
        </tr>';


     $tr.=
        '<tr class=""  style="cursor:pointer;">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><strong>Total: </strong></td>
            <td>'. $moneda_simbolo.' '.numero_simple($montos_totales).'</td>
        </tr>';

    echo $tr;


}




 
