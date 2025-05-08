<?php

    if (!defined('ENLACE_SERVIDOR')) {
        session_start(); 
        require_once('../../conf/conf.php');
    }


    $rowid = $_GET['id'];
    


    // WHERE 
    $where ='';
    $where .=' AND  sc.fk_oportunidad = '.$rowid.' ';

    $sql= "SELECT p.ref , p.label , sc.cantidad , sc.precio_unitario , sc.precio_tipo_impuesto , sc.tipo_descuento , sc.monto_descuento , sc.precio_subtotal, sc.precio_total, sc.rowid FROM fi_oportunidades_servicios sc , fi_oportunidades op, fi_productos p WHERE p.rowid = sc.fk_producto AND sc.fk_oportunidad = op.rowid 
        ".$where."
       order by sc.rowid DESC; ";

//    echo "LA SQL ".$sql;


$db = $dbh->prepare($sql);
$db->execute();
$tr = "";
$contador = 1;
$montos_totales = 0;
$montos_subtotales = 0;
$monto_descuento = 0;
$impuesto = 0;
$moneda_simbolo = '';

// RUN RECORDS
while ($data = $db->fetch(PDO::FETCH_OBJ)):
        
    $moneda_simbolo = '€';

    $simbolo_descuento = '';
    if($data->tipo_descuento === 'porcentual')
    {
        $simbolo_descuento = '%';
        $monto_descuento = $monto_descuento +  (floatval($data->precio_subtotal) *  floatval($data->monto_descuento) / 100 );

    }else{
        $monto_descuento = $monto_descuento +  floatval($data->monto_descuento);
    }   

    //ACUMULACION DEL IMPUESTO
    $impuesto = $impuesto +  (floatval($data->precio_subtotal) *  floatval($data->precio_tipo_impuesto) / 100);  


    $aa = "<button class='boton-servicio' style='margin-left:5px;background:none; border: none' onclick='editarServicio(".$data->rowid.")'>";
    $aa2 ='</button>';
 
    $tr.=
        '<tr class="ng-scope '. ( ($_REQUEST['actividad']==$data->rowid) ? 'shake-row' :'' ).'"  style="cursor:pointer;">
            <td>'.$contador.'</td>
            <td>'.ucwords($data->ref).'</td>
            <td>'.ucwords($data->label).'</td>
            <td><span class="badge bg-info">'.$data->cantidad.'</span></td>
            <td>'. $moneda_simbolo.' '.numero_simple($data->precio_unitario).'</td>
            <td>'.numero_simple($data->precio_tipo_impuesto).'%</td>
            <td>'. $moneda_simbolo.' '.numero_simple($data->monto_descuento).$simbolo_descuento.'</td>
            <td><span class="badge bg-warning">'. $moneda_simbolo.' '.numero_simple($data->precio_total).'</span></td>
            <td>'.$aa.' <i class="fa fa-fw fa-edit" aria-hidden="true"></i> '.$aa2.' <button class="boton-servicio" style="border:none; background-color:transparent;"   onclick="eliminarServicio('.$data->rowid.')"><i class="fa fa-trash-o" aria-hidden="true"></i></button></td>
        </tr>';

        $contador++;

        $montos_totales = $montos_totales + floatval($data->precio_total);
        $montos_subtotales = $montos_subtotales + floatval($data->precio_subtotal);
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
            <td><strong>Impuestos: </strong></td>
            <td>'. $moneda_simbolo.' '.numero_simple($impuesto).'</td>
            <td></td>
        </tr>';

      $tr.=
        '<tr class=""  style="cursor:pointer;">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><strong>Descuento: </strong></td>
            <td>'. $moneda_simbolo.' '.numero_simple($monto_descuento).'</td>
            <td></td>
        </tr>';

       
     $tr.=
        '<tr class=""  style="cursor:pointer;">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><strong>Subtotal: </strong></td>
            <td>'. $moneda_simbolo.' '.numero_simple($montos_subtotales).'</td>
            <td></td>
        </tr>';


     $tr.=
        '<tr class=""  style="cursor:pointer;">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><strong>Total: </strong></td>
            <td>'. $moneda_simbolo.' '.numero_simple($montos_totales).'</td>
            <td></td>
        </tr>';

    echo $tr;
}




 
