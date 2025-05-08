<?php

$documentos_p = 'listado_agentes';
$TAMANO_PAGINA = 10;

if (!defined('ENLACE_SERVIDOR')) {
    session_start(); 
    require_once('../../conf/conf.php');
   
}


// VALID
if(isset($_POST['pagina'])){
    
    $_SESSION[$documentos_p] = $_POST['pagina'];
}
else{
    if(!isset($_SESSION[$documentos_p])){
        $_SESSION[$documentos_p]=0;
    }    
}

$pagina_actual = $_SESSION[$documentos_p];
// VALID
if(isset($_SESSION[$documentos_p])){
    if($_SESSION[$documentos_p]==0){$paginacion;}
    else{$pagina=$_SESSION[$documentos_p]*15;

    $paginacion = 'OFFSET '.$pagina;}
    
}

$rowid = $_GET['fiche'];


// WHERE 
$where ='';

 $where .=' AND ca.fk_cotizacion = '.$rowid.' ';
 $where .=' AND ca.tipo = "tarea" ';

$where .= (!empty($_POST['filtro_consecutivo'])) ? " and c.number like '%".$_POST['filtro_consecutivo']."%' " : "";
$where .= (!empty($_POST['filtro_cliente'])) ? " and ft.nombre like '%".$_POST['filtro_cliente']."%' " : "";
$where .= (!empty($_POST['filtro_actividad'])) ? " and ca.fk_diccionario_actividad =".$_POST['filtro_actividad'] : "";
$where .= (!empty($_POST['filtro_vencimiento_fecha'])) ? " and ca.vencimiento_fecha like '%".$_POST['filtro_vencimiento_fecha']."%' " : "";
$where .= (!empty($_POST['filtro_dias_vencimiento'])) ? " and DATEDIFF(ca.vencimiento_fecha, ca.creado_fecha) = ".$_POST['filtro_dias_vencimiento'] : "";
$where .= (!empty($_POST['filtro_usuario'])) ? " and fu.usuario like '%".$_POST['filtro_usuario']."%' " : "";
$where .= (!empty($_POST['filtro_estado_actividad'])) ? " and ca.fk_estado =".$_POST['filtro_estado_actividad'] : "";


// filtro_estado_actividad


  $consulta = "SELECT count(*) FROM a_medida_redhouse_cotizaciones_cotizaciones_actividades  ca 
left JOIN a_medida_redhouse_cotizaciones  c ON ca.fk_cotizacion = c.rowid 
left JOIN a_medida_redhouse_cotizaciones_diccionario_crm_actividades  da ON ca.fk_diccionario_actividad = da.rowid
left JOIN fi_terceros ft ON c.fk_tercero = ft.rowid
left JOIN fi_usuarios fu ON ca.fk_usuario_asignado = fu.rowid
left JOIN a_medida_redhous_cotizaciones_diccionario_crm_actividades_estado de ON ca.fk_estado = de.rowid
where 1".$where;




$sqlA = $dbh->prepare($consulta); // Prepare your query with PDO
$sqlA->execute(); // Once it is prepared execute it
 

   if ($sqlA) { // Check if $sql is executed will return TRUE or FALSE
       if ($sqlA->rowCount() > 0 ) { // If you get any rows back then
          $nrows = $sqlA->fetchColumn(); // Get your results
       }
   }
 


$num_total_registros = $nrows;
$total_paginas       = ceil($num_total_registros / $TAMANO_PAGINA); 
$cuentaUsuarios      = $num_total_registros/15;

if($cuentaUsuarios<1){
   $paginasUsuarios=1;
}else{
   $paginasUsuarios=ceil($cuentaUsuarios);
}

 
$mostrando = "Mostrando la página " . $pagina . " de " . $total_paginas . "<p>";

  $sql= "SELECT
             ca.rowid, 
             c.cotizacion_referencia,
             da.nombre as actividad,
             ft.nombre as cliente,
             ca.creado_fecha,
             ca.vencimiento_fecha,
             ca.comentario,
             CONCAT(fu.nombre,' ',fu.apellidos) as usuario , 
             de.etiqueta as estado,
             ca.fk_diccionario_actividad,
             ca.fk_estado,
             DATEDIFF(ca.vencimiento_fecha, now()) 
              AS dias_vencimiento 
        FROM  a_medida_redhouse_cotizaciones_cotizaciones_actividades  ca 
left JOIN a_medida_redhouse_cotizaciones  c ON ca.fk_cotizacion = c.rowid 
left JOIN a_medida_redhouse_cotizaciones_diccionario_crm_actividades da ON ca.fk_diccionario_actividad = da.rowid
left JOIN fi_terceros ft ON c.fk_tercero  = ft.rowid
left JOIN fi_usuarios fu ON ca.fk_usuario_asignado = fu.rowid
left JOIN a_medida_redhous_cotizaciones_diccionario_crm_actividades_estado de ON ca.fk_estado = de.rowid
where 1".$where."
        order by ca.rowid ASC LIMIT 15 " .$paginacion;
        
   //  echo $sql;

$db = $dbh->prepare($sql);
$db->execute();
$tr = "";
$contador = 1;


// RUN RECORDS
while ($data = $db->fetch(PDO::FETCH_OBJ)):
    $vencimiento = '';
    
    if ($data->fk_estado == 1) {
      
        switch ((integer)$data->dias_vencimiento) {

            case 0:
                $clase = $data->fk_estado == 1 ? 'warning' : 'success';
                $vencimiento .= $data->fk_estado == 1 ? 'Vence Hoy' : 'Realizada';
               
                break;

            case ((integer)$data->dias_vencimiento < 0):
                $clase = 'danger';
                $clase = $data->fk_estado == 1 ? 'danger' : 'success';
                $vencimiento .= 'Vencida hace ' . abs($data->dias_vencimiento) . ' dia(s)';
                break;
          

            case 1:
                $clase = $data->fk_estado == 1 ? 'warning' : 'success';
                $vencimiento .=  $data->fk_estado == 1 ? 'Vence en ' . $data->dias_vencimiento . ' dia(s)' : 'Realizada';
                break;
            default:
                $clase = 'success';
                $vencimiento .= $data->fk_estado != 1? 'Realizada': 'Vence en ' . $data->dias_vencimiento . ' dia(s)';
                break;
        }

} else {
    $clase = 'success';
    $vencimiento .= 'Realizada';
}
    

    // switch ($data->dias_vencimiento) {
    //     case 0:
    //         $clase = 'danger';
    //         break;
    //     case 1:
    //         $clase = 'warning';
    //         break;
    //     default:
    //         $clase = 'success';
    //         break;
    // }

    $aa = "<button style='margin-left:5px;background:none; border: none' onclick='editar_agente(".$data->rowid.")'>";
    $aa2 ='</button>';

    $a1 = "<button style='margin-left:5px;background:none; border: none' onclick='cambio_estado(".$data->rowid.")'>";
    $aa1 ='</button>';
 
    $tr.=
        '<tr class="ng-scope '. ( ($_REQUEST['actividad']==$data->rowid) ? 'shake-row' :'' ).'" onclick="editarTarea('.$data->rowid.');" style="cursor:pointer;"   >
            <td>'.ucwords($data->actividad).'</td>
            <td>'.date('d/m/Y',strtotime($data->vencimiento_fecha)).'</td>
            <td><span class="badge bg-'.$clase.'">'.$vencimiento.'</span></td>
            <td>'.ucwords($data->usuario).'</td>
            <td>'.ucwords($data->estado).'</td>
        </tr>';
        $contador++;
endwhile;

if ($tr == '') {
echo '<tr><td colspan="12" style="text-align:center">No se han encontrado tareas</td></tr>';
}else {
    echo $tr;
}




// PAGINATION
if($paginasUsuarios>1){
        echo '<tr><td colspan="13" style="text-align:center">';
        $paginas=$paginasUsuarios;
        $ultimo='';
        $anterior=$pagina_actual;
        $disa='';
        if($anterior<=0){$disa2='disabled';}
        $ante=$anterior-1;
        echo '<button class="btn btn-mat waves-effect waves-light btn-inverse " onclick="pageSUpplier('.$ante.')"'.$disa2.'><i class="fas fa-arrow-left"></i> Anterior</button>&nbsp;';
        $ult=$pagina_actual+2;
        $pag=$pagina_actual+15;
        if($ult>$paginasUsuarios || $pag>$paginasUsuarios){
            $contadorPaginas=$paginasUsuarios;
        }
        else{
            $contadorPaginas=$pag;
        }

        for ($i=0; $i < $contadorPaginas ; $i++) { 
            $x=$i+1;
            $active='btn btn-out waves-effect waves-light btn-inverse btn-square';
            $onclick='onclick="pageSUpplier('.$i.')"';
            if($i==$pagina_actual){$active='btn btn-out waves-effect waves-light btn-info btn-square'; $onclick='';}
            echo '<button class="'.$active.'" '.$onclick.'>'.$x.'</button>&nbsp;';
            $x++;
        }
        $ultimo=$pagina_actual+1;
        $ultimo2=$pagina_actual+2;
        if($ultimo2>$paginasUsuarios){$disa='disabled';}
        echo '<button class="btn btn-mat waves-effect waves-light btn-inverse " onclick="pageSUpplier('.$ultimo.')" '.$disa.'>Siguiente <i class="fas fa-arrow-right"></i></button>';
        echo '</td></tr>';
    }
?>


 
