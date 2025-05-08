<?php
$documentos_p = 'listado_mecanicos';
// VALID
if(isset($_POST['pagina'])){
    require_once('../../conf/conf.php');
    require_once(ENLACE_SERVIDOR."global/object/info.estados.php");
    session_start();    
    $_SESSION[$documentos_p] = $_POST['pagina'];
}
else{
    if(!isset($_SESSION[$documentos_p])){
        $_SESSION[$documentos_p]=0;
    }    
}

$pagina_actual = $_SESSION[$documentos_p];
// VALID
if(isset($_SESSION[$documentos_p])):
    if($_SESSION[$documentos_p]==0){$paginacion;}
    else{$pagina=$_SESSION[$documentos_p]*15;
    $paginacion = 'OFFSET '.$pagina;}    
endif;

// WHERE 
$where ='';
$where .= (($_SESSION['tipo'])=='doctor')? " AND FC.fk_doctor = ".$_SESSION['usuario_ex'] : "";
$where .= (!empty($_POST['dateFrom']) && !empty($_POST['dateTo'])) ? " AND (DATE_FORMAT(FC.fecha,'%Y-%m-%d') BETWEEN DATE_FORMAT('".$_POST['dateFrom']."','%Y-%m-%d') AND DATE_FORMAT('".$_POST['dateTo']."','%Y-%m-%d'))" : "";
$where .= (!empty($_POST['patient'])) ? " AND (CASE WHEN FC.tipo ='normal' THEN CONCAT_WS(' ',FP.nombre,FP.primer_apellido,FP.segundo_apellido) ELSE FC.cliente END) LIKE '%".$_POST['patient']."%' " : "";
$where .= (!empty($_POST['agreement'])) ? " AND FC.fk_convenio =".$_POST['agreement'] : "";
$where .= (!empty($_POST['doctor'])) ? " AND FC.fk_doctor =".$_POST['doctor'] : "";
$where .= (!empty($_POST['status'])) ? " AND FC.fk_estado =".$_POST['status'] : ""; 
// ROWS
$nrows = $dbh->query("SELECT COUNT(*) as total FROM fi_citas FC LEFT JOIN fi_pacientes FP ON FP.rowid = FC.fk_paciente WHERE FC.fk_sucursal = ".$_SESSION['fk_sucursal']." ".$where)->fetchColumn();

$num_total_registros = $nrows;
$total_paginas       = ceil($num_total_registros / $TAMANO_PAGINA); 
$cuentaUsuarios      = $num_total_registros/15;

if($cuentaUsuarios<1){
    $paginasUsuarios=1;
}else{
    $paginasUsuarios=ceil($cuentaUsuarios);
}

$mostrando = "Mostrando la página " . $pagina . " de " . $total_paginas . "<p>";
// QUERY
$sql = 
    "SELECT 
      FC.rowid,
      FC.fecha,
      FC.inicio,
      FC.fin,
      FC.fk_estado,
      FS.nombre   AS sucursal,
      (CASE WHEN FC.tipo ='normal' THEN CONCAT_WS(' ',FP.nombre,FP.primer_apellido,FP.segundo_apellido) ELSE FC.cliente END) AS paciente,
      FN.nombre   AS convenio,
      DC.nombre   AS doctor,
      DE.etiqueta AS estado,
      DE.label,
      DE.color
    FROM fi_citas FC
    INNER JOIN fi_sucursales           FS ON FS.rowid = FC.fk_sucursal
    LEFT JOIN fi_pacientes             FP ON FP.rowid = FC.fk_paciente
    LEFT JOIN fi_convenios             FN ON FN.rowid = FC.fk_convenio
    INNER JOIN fi_usuario              DC ON DC.rowid = FC.fk_doctor
    INNER JOIN fi_diccionario_estados  DE ON DE.rowid = FC.fk_estado
    WHERE FC.fk_sucursal =:fk_sucursal
    ".$where."
    ORDER BY FC.fecha desc, FC.inicio desc LIMIT 15 ".$paginacion;
$db = $dbh->prepare($sql);
$db->bindValue(":fk_sucursal", $_SESSION['fk_sucursal'], PDO::PARAM_INT);
$db->execute();          
$html = "" ;
// RUN RECORDS
while ($data = $db->fetch(PDO::FETCH_OBJ)):
    // VALID DOCUMENT TYPE
    $btnCopy = (($_SESSION['tipo'])=='usuario')?'<td class="text-center"><button Class="btn btn-info" onclick="viewModalCopy('.$data->rowid.');" style="cursor:pointer;font-weight:bold;" title="permite copiar la cita"><i class="fa fa-clipboard"></i></button></td>': '';
    $Delete = ((($_SESSION['tipo'])=='usuario') && (($data->fk_estado)==json_decode($arrayStatusObject)->Pendiente->rowid))?'<td class="text-center"><button Class="btn btn-danger" onclick="cancelQuote('.$data->rowid.');" style="cursor:pointer;font-weight:bold;" title="permite cancelar la cita"><i class="fa fa-close"></i></button></td>': '<td></td>';
    // HTML
    $html.=
    '<tr class="gradeX odd" role="row"  style="cursor:pointer;">
        <td class="text-left">'.$data->paciente.'</td>
        <td class="text-center">'.$data->convenio.'</td>
        <td class="text-center">'.date('d-m-Y', strtotime($data->fecha)).'</td>
        <td class="text-center">'.date('H:i', strtotime($data->inicio)).'</td>
        <td class="text-center">'.date('H:i', strtotime($data->fin)).'</td>
        <td class="text-left">'.$data->doctor.'</td>             
        <td class="text-center"><a class="badge badge-pill badge-'.$data->label.'" href="#">'.$data->estado.'</a></td>
        '.$btnCopy.'
        '.$Delete.'
    </tr>';
endwhile;
// HTML
echo $html;

// PAGINATION
if($paginasUsuarios>1){
        echo '<tr><td colspan="13" style="text-align:center">';
        $paginas=$paginasUsuarios;
        $ultimo='';
        $anterior=$pagina_actual;
        $disa='';
        if($anterior<=0){$disa2='disabled';}
        $ante=$anterior-1;
        echo '<button class="btn btn-mat waves-effect waves-light btn-inverse " onclick="quoteInfoSearch('.$ante.')"'.$disa2.'><i class="fa fa-arrow-left"></i> Anterior</button>&nbsp;';
        $ult=$pagina_actual+2;
        $pag=$pagina_actual+10;
        if($ult>$paginasUsuarios || $pag>$paginasUsuarios){
            $contadorPaginas=$paginasUsuarios;
        }
        else{
            $contadorPaginas=$pag;
        }

   //     for ($i=0; $i < $contadorPaginas ; $i++) { 
        for ($i=$pagina_actual+$i-5; $i < $contadorPaginas ; $i++) {
          	  if($i == 0 or $i > 0){
            $x=$i+1;
            $active='btn btn-out waves-effect waves-light btn-inverse btn-square';
            $onclick='onclick="quoteInfoSearch('.$i.')"';
            if($i==$pagina_actual){$active='btn btn-out waves-effect waves-light btn-info btn-square'; $onclick='';}
            echo '<button class="'.$active.'" '.$onclick.'>'.$x.'</button>&nbsp;';
            $x++;
                      }
        }
        $ultimo=$pagina_actual+1;
        $ultimo2=$pagina_actual+2;
        if($ultimo2>$paginasUsuarios){$disa='disabled';}
        echo '<button class="btn btn-mat waves-effect waves-light btn-inverse " onclick="quoteInfoSearch('.$ultimo.')" '.$disa.'>Siguiente <i class="fa fa-arrow-right"></i></button>';
        echo '</td></tr>';
    }
?>