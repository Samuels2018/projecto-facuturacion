<?php


include_once(ENLACE_SERVIDOR . "mod_documentos_mercantiles/tpl/header_document.php");
 

include_once(ENLACE_SERVIDOR . "mod_europa_facturacion/object/facturas.object.php");
include_once(ENLACE_SERVIDOR . "mod_europa_albaran_compra/object/Albaran_compra.object.php");
include_once(ENLACE_SERVIDOR . "mod_europa_albaran_venta/object/Albaran_venta.object.php");
include_once(ENLACE_SERVIDOR . "mod_europa_compra/object/compras.object.php");
include_once(ENLACE_SERVIDOR . "mod_europa_presupuestos/object/presupuestos.object.php");
include_once(ENLACE_SERVIDOR . "mod_europa_pedido/object/pedido.object.php");



$tipo         = $_GET['tipo'];




if($tipo == 'Presupuesto'){ }
    else if ($tipo=='Pedido')  {}  
        else if ($tipo=='Albaran_venta')  {}  
            else if ($tipo=='Factura')  {}  

    else if ($tipo=='Albaran_compra')  {}  
                else if ($tipo=='Compra')  {}  

    else {
 
        echo acceso_invalido();
        exit(1);
    }



$Documento = new $tipo($dbh, $_SESSION['Entidad']);
$Documento->fetch($_GET['fiche']);
 


    //si no hay usuario autenticado, cerrar conexion
    if (empty($Documento->id)) {
        echo acceso_invalido();
             exit(1);
      }


$date = strtotime($Documento->fecha);
$month = date('m', $date);
$year = date('Y', $date);

if ($Documento->fecha == '' || $Documento->fecha == '0000-00-00') {
   $Documento->fecha = date('Y-m-d');
}
if ($Documento->fecha_vencimiento == '' || $Documento->fecha_vencimiento == '0000-00-00') {
   $Documento->fecha_vencimiento = date('Y-m-d');
}



?>



<div class="middle-content container-xxl p-0">
   <div class="page-meta mb-4">
      <nav class="breadcrumb-style-one" aria-label="breadcrumb">
         <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo ENLACE_WEB; ?>">Inicio</a></li>
            <li class="breadcrumb-item" aria-current="page"><a href="<?php echo ENLACE_WEB.$Documento->listado_url; ?>"><?php echo $Documento->documento_txt['singular']; ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="<?php echo ENLACE_WEB.$Documento->ver_url."/".$Documento->id; ?>"> <?php echo $Documento->referencia; ?></a></li>
         </ol>
      </nav>
   </div>


<?php
 
 
 $sql= "

select 
    log.*   ,
    concat(u.nombre,' ' ,u.apellidos) as usuario_txt    ,
    diccionario.etiqueta     ,
    diccionario.class    

from 
fi_europa_documentos_log log 
left join fi_usuarios u on u.rowid = log.fk_usuario
left join ".DB_NAME_UTILIDADES_APOYO.".{$Documento->diccionario} diccionario on diccionario.rowid = log.estado 
where 
log.entidad = :entidad 
and log.documento_fk =  {$Documento->id} 
and log.documento    = '{$Documento->nombre_clase}'
order by  log.rowid ASC 
";
$db = $dbh->prepare($sql);
$db->bindValue(":entidad" , $_SESSION['Entidad'] , PDO::PARAM_INT);
$db->execute();

$i = 0;
while ($data = $db->fetch(PDO::FETCH_ASSOC)){
    $i++;
    $icono ='<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    <span class="table-inner-text">'.date("d-m-Y H:i", strtotime($data['fecha'])).'</span>';



    $tr.="<tr>
            <td>{$i}</td>
            <td>{$data['usuario_txt']}</td>
            <td>{$data['comentario']}</td>
            <td><span class='badge badge-light-{$data['class']}'>{$data['etiqueta']}</span> </td>
            <td>{$icono}</td>
        </tr>";
    
    }

    ?>



   <div class="content" >
      <div class="row">
         <div class="col-md-12">
      <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col"></th>
                <th scope="col">Usuario</th>
                <th class="text-center" scope="col">Comentario</th>
                <th class="text-center" scope="col">Estado Documento</th>
                <th class="text-center" scope="col">Fecha</th>
            </tr>
        </thead>
        <tbody>
          <?php echo $tr; ?>
</tbody> 
</table>
             </div>
         </div>


       


      <div class="row mt-3 zona_trabajo_factura ">
          
             <div class="col-xs-12">
               <a href="<?php echo ENLACE_WEB; ?><?php echo $Documento->ver_url."/".$Documento->id; ?>" class="btn btn-outline-primary _effect--ripple waves-effect waves-light">
                    Volver Al Documento <?php echo $Documento->referencia; ?>
               </a>
 

             <!-- col -->
         </div>
      </div>


   </div>

</div><!-- Content --->
 