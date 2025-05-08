<?php

include_once(ENLACE_SERVIDOR . "mod_documentos_mercantiles/tpl/header_document.php");
include_once(ENLACE_SERVIDOR . "mod_europa_facturacion/object/facturas.object.php");
require_once ENLACE_SERVIDOR . 'mod_proyectos/object/Proyectos.object.php';
require_once ENLACE_SERVIDOR . 'mod_stock/object/bodegas.object.php';
require_once ENLACE_SERVIDOR . 'mod_configuracion_agente/object/agente.object.php';

require_once ENLACE_SERVIDOR . 'mod_documento_pdf/object/plantilla.object.php';

include_once(ENLACE_SERVIDOR."mod_documento_pdf/object/documento_pdf.php");

require_once ENLACE_SERVIDOR . 'mod_direcciones/object/Direcciones.object.php';
 
$Direccion = new Direccion($dbh, $_SESSION['Entidad']) ;

$Documento = new Factura($dbh, $_SESSION['Entidad']);
$id = $_GET['fiche'];

$tipo = $Documento->nombre_clase;

$Documento->fetch($id);
 
if ($_GET['accion'] == "validar_factura" and $Documento->estado == 0) {
   $Documento->validar($_SESSION['usuario'], $_GET['option_serie']);

   $Bodega = new Bodegas($dbh, $_SESSION["Entidad"]);
   $Bodega->usuario = $_SESSION["usuario"];
   $Bodega->documento_tipo = "factura";
   $Bodega->motivo         = "Factura Venta";
   $total_productos_movidos = $Bodega->mover_bodega_en_venta($Documento, $_SESSION['usuario'], $_GET['option_bodega'], "disminuir");

   // if ($total_productos_movidos>0){
   //    $Bodega->fetch($Bodega->fk_bodega_por_defecto);
   //    $Documento->registrar_log_documento(   $_SESSION['usuario'] 
   //                                           , 1 
   //                                           ,"{$total_productos_movidos} Producto(s) Movidos al inventario de Almacen <strong>{$Bodega->label}</strong> ");
   // }

   if($_GET["option_email"] !=''){

      $Documento->fetch($id);
      include_once(ENLACE_SERVIDOR."mail_sys/mail/enviar_email_object.php");
      $datos_correo = $Documento->generar_datos_correo();
      $titulo = $datos_correo["titulo"];
      $empresa = $datos_correo["empresa"];
      $texto = $datos_correo["cuerpo"];
      if(empty($texto)){ $texto = 'Sin comentarios'; }
      $asunto = $datos_correo["asunto"];
      $DocumentoPdf = new documento_pdf($dbh, $_SESSION['Entidad']);
      $DocumentoPdf->objDocumento = $Documento;
      $content = $DocumentoPdf->genera_pdf('S', $tipo);
      $enviarEmail = new EnviarEmail($dbh, $_SESSION["Entidad"]);
      $envioCorreo = $enviarEmail->enviar($Documento, $asunto, $_GET["option_email"], $texto, $_SESSION['usuario'], 1, $content, 0, 'ver_factura.php');
   }
}


if ($_GET['accion'] == "anular_factura" and $Documento->estado > 0) {
      $Documento->cambiar_estado(3);
      $Documento->registrar_log_documento( $_SESSION['usuario'] , 1, "Documento Anulado Dentro del Trimestre Activo");
 }





 $Plantilla = new Plantilla($dbh, $_SESSION['Entidad']);
 $lista_plantillas = $Plantilla->obtener_plantilla_tipo_documento($Documento->documento);

$Proyectos = new Proyectos($dbh, $_SESSION['Entidad']);
$lista_proyectos = $Proyectos->listar_proyectos();

$Agentes = new Agente($dbh, $_SESSION['Entidad']);
$lista_agentes = $Agentes->obtener_agentes();

$Documento->fetch($id);





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
            <li class="breadcrumb-item" aria-current="page"><a href="<?php echo ENLACE_WEB; ?>factura_listado"><?php echo $Documento->documento_txt['singular']; ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $Documento->referencia; ?></li>
         </ol>
      </nav>
   </div>


   <div class="content" id="formulario_factura">
      <div class="row">
         <div class="col-md-7">
            <div class="card">
               <div class="card-body">
                  <?php
                  require_once(ENLACE_SERVIDOR . "mod_terceros/tpl/cliente_modal_nuevo.php");
                  ?>

                  <div class="row">
                     <div class="col-sm-3">
                        <label for="company-name" class="col-form-label col-form-label-sm">Forma Pago</label>
                     </div>

                     <div class="col-sm-9" id="lugar_campo_forma_pago">
                        <?php if ($Documento->estado == 0) { ?>
                           <select onchange="actualizar_detalle_documento(this,'<?php echo $Documento->documento; ?>','<?php echo $Documento->id; ?>')" class="form-select form-control-sm " name="forma_pago" id="forma_pago">
                              <option value="0"></option>
                              <?php
                              foreach ($Documento->diccionario_pago() as $key =>  $forma_pago) {
                                 echo "<option value='{$key}' " . (($Documento->forma_pago == $key) ? "selected='selected'" : "") . " > {$forma_pago['label']}  ";
                              }
                              ?>
                           </select>
                        <?php } else {
                           $forma_pago_current = '';
                           foreach ($Documento->diccionario_pago() as $key =>  $forma_pago) {
                              if ($Documento->forma_pago == $key) {
                                 $forma_pago_current = $forma_pago['label'];
                              }
                           }
                        ?>
                           <div class="col-sm-12">
                              <i class="fa-regular fa-calendar-check"></i>
                              <span class="table-inner-text"><?php echo ($forma_pago_current != '' ? $forma_pago_current : 'Sin Forma Pago'); ?></span>
                           </div>
                        <?php } ?>
                     </div>
                  </div>

                  <div class="row" style="margin-top:10px;">
                     <div class="col-sm-3">
                        <label for="campo_agente" class="col-form-label col-form-label-sm">Agente</label>
                     </div>
                     <div class="col-sm-9" id="lugar_campo_agente">                           
                              <select onchange="actualizar_detalle_documento(this,'<?php echo $Documento->documento; ?>','<?php echo $Documento->id; ?>')" class="form-select form-control-sm " name="asesor_comercial_txt" id="asesor_comercial_txt">
                                 <option value="0"></option>
                                 <?php
                                 foreach ($lista_agentes as $dataagente) {
                                    $rowidagente = $dataagente->rowid;
                                    echo "<option value='{$rowidagente}' " . (($Documento->fk_agente == $dataagente->rowid) ? "selected='selected'" : "") . " > {$dataagente->nombre}  ";
                                 }
                                 ?>
                              </select>
                     </div>
                  </div>
               
                  <div class="row" style="margin-top:10px;">
                     <div class="col-sm-3">
                        <label for="company-name" class="col-form-label col-form-label-sm">Proyecto</label>
                     </div>
                     <div class="col-sm-9" id="lugar_campo_fk_proyecto">
                           
                              <select onchange="actualizar_detalle_documento(this,'<?php echo $Documento->documento; ?>','<?php echo $Documento->id; ?>')" class="form-select form-control-sm " name="fk_proyecto" id="fk_proyecto">
                                 <option value="0"></option>
                                 <?php
                                 foreach ($lista_proyectos['data'] as $dataproyecto) {
                                    $rowidproyecto = $dataproyecto['rowid'];

                                    echo "<option value='{$rowidproyecto}' " . (($Documento->fk_proyecto == $dataproyecto['rowid']) ? "selected='selected'" : "") . " > {$dataproyecto['nombre']}  ";
                                 }
                                 ?>
                              </select>

                     </div>
                  </div>
                  
                  <div class="form-group row mt-2">
                     <div class="form-group d-flex align-items-center">
                        <label class="col-sm-3 col-form-label col-form-label-sm">Configuraci&oacute;n</label>
                        <button OnClick="muestra_opciones_descuento()" class="ml-2 btn btn-success btn-icon mb-2 me-4 btn-rounded _effect--ripple waves-effect waves-light">
                              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings">
                                 <circle cx="12" cy="12" r="3"></circle>
                                 <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 .85 1.65 1.65 0 0 1-3.1 0 1.65 1.65 0 0 0-1-.85 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-.85-1 1.65 1.65 0 0 1 0-3.1 1.65 1.65 0 0 0 .85-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33h.1a1.65 1.65 0 0 0 1-.85 1.65 1.65 0 0 1 3.1 0 1.65 1.65 0 0 0 1 .85h.1a1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82v.1a1.65 1.65 0 0 0 .85 1 1.65 1.65 0 0 1 0 3.1 1.65 1.65 0 0 0-.85 1v.1z"></path>
                              </svg>
                           </button>

                        <?php include_once ENLACE_SERVIDOR . 'mod_campos_extra_formularios/tpl/campos_extra_document.php'; ?>

                     </div>
                  </div>

                           


                  <div id="muestra_opciones" class="fade show mb-4 alert alert-outline-primary alert-dismissible" style="display:none"  role="alert">
                     <button type="button" class="btn-close" onclick="muestra_opciones_descuento()" aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close">
                           <line x1="18" y1="6" x2="6" y2="18"></line>
                           <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                     </button>
                      <!-- Parte izquierda de la cabecera (Descuentos) -->
                     <div class="row mt-3">                        
                        <div class="col-md-4">
                           <div class="form-check form-check-primary form-check-inline">
                              <input class="form-check-input" type="checkbox" id="aplicar_descuento" onchange="aplicar_descuento()" <?php echo ($Documento->estado != 0 ? 'disabled readonly' : ''); ?>>
                              <label class="form-check-label" for="aplicar_descuento">
                                 Utilizar Descuentos
                              </label>
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="form-check form-check-primary form-check-inline">
                              <input class="form-check-input" type="checkbox" id="aplicar_RE" onchange="aplicar_RE()" <?php echo ($Documento->estado != 0 ? 'disabled readonly' : ''); ?>>
                              <label class="form-check-label" for="aplicar_RE">
                                 Recargo Equivalencia
                              </label>
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="form-check form-check-primary form-check-inline">
                              <input class="form-check-input" type="checkbox" id="aplicar_irp" onchange="aplicar_irp()" <?php echo ($Documento->estado != 0 ? 'disabled readonly' : ''); ?>>
                              <label class="form-check-label" for="aplicar_irp">
                                 Retenci&oacute;n IRPF <?php echo $Documento->Entidad->retencion_porcentaje ?>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div id="mostrar_direccion" class="row" style="margin-top:10px;display:none">
                           <div class="col-sm-2">
                              <label for="fk_direccion" class="col-form-label col-form-label-sm">Dirección</label>
                           </div>
                           <div class="col-sm-7" id="lugar_campo_fk_direccion">                           
                                 <select  onchange="actualizar_detalle_direccion()" class="form-select form-control-sm " name="fk_direccion" id="fk_direccion">                           
                              
                                 </select>
                           </div>
                           <div class="col-sm-3">
                              <a onclick="agregarDireccion()" class="btn btn-info btn-icon mb-2 me-4 btn-rounded"><i class="fa fa-plus"></i></a>
                           </div>
                        </div>
                     </div>
                  </div>
                 

                 

               </div>
            </div>
         </div>


         <!-- Parte derecha con fechas  -->
         <div class="col-md-5">
            <div class="card">
               <div class="card-body">
                  <div class="form-group row">
                     <div class="col-sm-12">
                        <h3><a href="<?php echo ENLACE_WEB; ?>factura/<?php echo $Documento->id; ?>"><span><?php echo $Documento->referencia; ?></span></a></h3>
                     </div>
                  </div>
                  <?php if ($Documento->estado == 0) { ?>
                     <div class="form-group row mt-3">
                        <label for="company-name" class="col-sm-6 col-form-label col-form-label-sm">Fecha validación</label>
                        <div class="col-sm-6" id="lugar_campo_forma_pago">
                           <input onblur="if(validar_fecha_vencimiento(this)) { actualizar_detalle_documento(this, '<?php echo $Documento->documento; ?>', <?php echo $Documento->id; ?>)}" id="fecha" name="fecha" type="date" class="form-control " value="<?php echo $Documento->fecha; ?>">
                        </div>
                     </div>
                  <?php } else {  ?>
                     <div class="form-group row  mt-3">
                        <label class="col-sm-6 col-form-label col-form-label-sm">Fecha validación</label>
                        <div class="col-sm-6" id="lugar_campo_forma_pago">
                           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar">
                              <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                              <line x1="16" y1="2" x2="16" y2="6"></line>
                              <line x1="8" y1="2" x2="8" y2="6"></line>
                              <line x1="3" y1="10" x2="21" y2="10"></line>
                           </svg>
                           <span class="table-inner-text"><?php echo date("d-m-Y", strtotime($Documento->fecha)); ?></span>
                        </div>
                     </div>
                  <?php }         ?>
                  <?php if ($Documento->estado == 0) { ?>
                     <div class="form-group row">
                        <label for="forma_pago" class="col-sm-6 col-form-label col-form-label-sm">Fecha vencimiento</label>
                        <div class="col-sm-6" id="lugar_campo_forma_pago">
                           <input onblur="if(validar_fecha_vencimiento(this)) {actualizar_detalle_documento(this, '<?php echo $Documento->documento; ?>', <?php echo $Documento->id; ?>)}" id="fecha_vencimiento" name="fecha_vencimiento" type="date" class="form-control" value="<?php echo $Documento->fecha_vencimiento; ?>">
                        </div>
                     </div>
                  <?php } else {  ?>
                     <div class="form-group row  mt-3">
                        <label class="col-sm-6 col-form-label col-form-label-sm">Fecha vencimiento</label>
                        <div class="col-sm-6" id="lugar_campo_forma_pago">
                           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar">
                              <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                              <line x1="16" y1="2" x2="16" y2="6"></line>
                              <line x1="8" y1="2" x2="8" y2="6"></line>
                              <line x1="3" y1="10" x2="21" y2="10"></line>
                           </svg>
                           <span class="table-inner-text"><?php echo date("d-m-Y", strtotime($Documento->fecha_vencimiento)); ?></span>
                        </div>
                     </div>
                  <?php }         ?>
                  <div class="form-group row mt-3">
                  <label class="col-sm-6 col-form-label col-form-label-sm">
                      Estado 
                     </label>
            
                     <div class="col-sm-3">
                        <span class="badge badge-light-<?php echo $Documento->diccionario_estados[$Documento->estado]['class']; ?>" ><?php echo $Documento->diccionario_estados[$Documento->estado]['etiqueta']; ?></span>
                     </div>
                  </div>


                  <?php if ($Documento->estado >  0 and  $Documento->verifactum_produccion > 0 ) { 
                     $Utilidades->obtener_estados_verifactu(); 
                  ?>
                  <div class="form-group row mt-3">
                  <label class="col-sm-6 col-form-label col-form-label-sm">
                        <i class="fas fa-network-wired" aria-hidden="true" style='color:<?php echo $Documento->verifactum_color(); ?>'></i> Verifactum
                     </label>
            
                     <div class="col-sm-3 Verifactum">
                        <span class="badge badge-light-<?php echo $Utilidades->obtener_estados_verifactu[$Documento->estado_hacienda]['class']; ?>" ><?php echo $Utilidades->obtener_estados_verifactu[$Documento->estado_hacienda]['etiqueta']; ?></span>
                     </div>
                  </div>
                  <?php } ?>


                  <?php include_once ENLACE_SERVIDOR . 'mod_europa_facturacion/tpl/listar_pagos.php'; ?>


               </div>





            </div>

         </div>

      </div>
      <!--- row  Cabecera-->


      <!--- row  Informacion Verifact -->
      <?php if ($Documento->estado != 0 and $Documento->verifactum_produccion >0)  { require_once(ENLACE_SERVIDOR."mod_europa_facturacion/tpl/ver_factura_verifactu.php"); } ?>
      <!--- row  Informacion Verifact-->


      
      <div class="row mt-3 zona_trabajo_factura ">
         <div class="col-xs-12">
            <?php
            include_once(ENLACE_SERVIDOR . "mod_documentos_mercantiles/tpl/documento_detalle.php");
            ?>
         </div>
         <div class="row mt-5">
            <div class="col-xs-12">
               <a href="<?php echo ENLACE_WEB . $Documento->listado_url; ?>" class="btn btn-outline-primary _effect--ripple waves-effect waves-light">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-box">
                     <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                     <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                     <line x1="12" y1="22.08" x2="12" y2="12"></line>
                  </svg>
                  Volver al Listado
               </a>


               <?php if ($Documento->id  > 0) { ?>

               <div class="btn-group" role="group">
                     <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle _effect--ripple waves-effect waves-light" data-bs-toggle="dropdown" aria-expanded="false">
                        Opciones Avanzadas
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down">
                           <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                     </button>

                     <ul id="dropdown_opciones_avanzadas" class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        <?php if ($Documento->estado  == 0) { ?>
                           <li><a class="dropdown-item" href="#" onclick='confirmar_eliminar_documento_mercantil(<?php echo $Documento->id; ?> , "<?php echo $Documento->nombre_clase; ?>"  );'><i class="fa fa-fw fa-trash" aria-hidden="true"></i>Eliminar Borrador</a></li>
                        <?php  } ?>

                        <li><a class="dropdown-item" href="#" onclick="clonarFactura(<?php echo $Documento->id; ?>,'<?php echo $Documento->nombre_clase; ?>')"><i class="far fa-copy"></i> Duplicar <?php echo $Documento->documento_txt['singular']; ?></a>
                        <li><hr></li>


                      
                           <?php if(count($lista_plantillas)> 0){ 
                              foreach ($lista_plantillas as $itemplantilla) {
                                 $background_color = '';
                                 $tiene_la_plantilla = $Documento->fk_plantilla == $itemplantilla["rowid"];
                                 if($tiene_la_plantilla){
                                    $background_color = ' style="background-color:#d4ac0d" ';
                                 }

                                 ?>
                                 <li class="items_pdf" id="<?php echo $itemplantilla["rowid"]; ?>" <?php echo $background_color; ?>>
                                    <a class="dropdown-item" href="#" onclick="generarPdf(<?php echo $Documento->id; ?>, '<?php echo $Documento->nombre_clase; ?>', '<?php echo $Documento->documento_txt['singular'].'-'.$Documento->referencia; ?>', '<?php echo $itemplantilla["rowid"]; ?>'); marcar_item(this);">
                                       <?php if($tiene_la_plantilla){ ?>
                                          <i class="fa fa-fw fa-check" aria-hidden="true"></i>
                                       <?php } ?>
                                       <?php echo ($tiene_la_plantilla?'Aplicado: ': 'Aplicar') ?> <?php echo $itemplantilla["titulo"]; ?> - PDF
                                    </a>
                                 </li>
                                 <?php
                              }
                           }
                           else{ ?>
                              <li class="items_pdf">
                                 <a class="dropdown-item" href="#" onclick="generarPdf(<?php echo $Documento->id; ?>, '<?php echo $Documento->nombre_clase; ?>', '<?php echo $Documento->documento_txt['singular'].'-'.$Documento->referencia; ?>')">Descargar <?php echo $Documento->referencia; ?> - PDF</a>
                              </li>
                           <?php } ?>
                        

                        <?php if ($Documento->estado > 0) {   ?>
                              <li>
                                 <a class="dropdown-item" href="<?php echo ENLACE_WEB."log/".$Documento->nombre_clase."/".$Documento->id; ?>" >Log Auditoria <?php echo $Documento->referencia; ?></a>
                              </li>
                        <?php } ?>

                      <?php if ($Documento->estado ==  1 ){  ?>
                        <li>
                           <a  class="dropdown-item"   href="#" onclick="anular_factura()" style="background-color:#E7515A">
                                    <i class="fa fa-fw fa-warning" aria-hidden="true"></i> Anular  <?php echo $Documento->documento_txt['singular']; ?>
                           </a>
                        </li>
                     <?php } ?>
                     </ul>


                   </div>




               <?php if ($Documento->estado  != 0) { ?>
                  <a class="btn btn-primary _effect--ripple waves-effect waves-light" href="#" onclick="abrir_email()"><i class="fa  fa-stack-exchange" aria-hidden="true"></i> Enviar Por Email</a>
               <?php } ?>
               

               <?php if ($Documento->estado  != 0 and ($Documento->verifactum_produccion>0) ) { ?>               
               <button class="btn btn-success position-relative" OnClick="mostrar_detalle_fe()">
                     <i class="fas fa-network-wired"></i>
                     <span class="btn-text-inner">Comunicación AEAT</span>
                     <span class="badge badge-danger counter">0</span>
                  </button>
                  
                  <button class="btn btn-primary _effect--ripple waves-effect waves-light boton_enviar_verifactum"  OnClick="enviar_Verifactu()" >
                        <i class="fas fa-network-wired" aria-hidden="true"></i>  Verifactu <?php echo ($Documento->verifactum_produccion == 1 )? " Pruebas " :""; ?>
                  </button>


                  
                  <?php 
 
                  $filePath = ENLACE_SERVIDOR_FILES_XML . "{$Documento->entidad}/{$Documento->tipo}/" . date('Y', strtotime($Documento->fecha)) . "/{$Documento->referencia}.xml";
                  
                  if (file_exists($filePath) or 1) { ?>
                              <a  class="btn btn-primary _effect--ripple waves-effect waves-light boton_enviar_verifactum"  href="<?php echo ENLACE_WEB; ?>mod_documentos_mercantiles/ajax/descargar.comprobante.php?tipo=factura&id=<?php echo $Documento->id; ?>">Descargar <?php echo $Documento->referencia; ?> - XML </a>
                              
                  <?php } ?>



               <?php } ?>                  


               

               <?php if ($Documento->estado == 0 ){  ?>
                     <a  class="btn btn-outline-primary _effect--ripple waves-effect waves-light"  href="#" onclick="validar_factura()">
                                    <i class="fa fa-fw fa-check-circle-o" aria-hidden="true"></i> Fiscalizar <?php echo $Documento->documento_txt['singular']; ?>.
                     </a>
               <?php } ?>

               


               <?php } ?>



               

                  


             
             <?php if ($Documento->estado != 0 && $Documento->estado_pagada == 0): ?>

                  <button
                     id="btn"
                     type="button"
                     class="btn btn-info _effect--ripple waves-effect waves-light"
                     data-bs-toggle="modal"
                     data-bs-target="#paymentModal"
                     data-invoice-id="<?= $id ?>">
                     $ Registrar Pago
                  </button>

               <?php endif ?>

            </div>
            <!-- col -->
         </div>
      </div>
   </div>







   <!---------------------------------- Verifactu ---------------------------------->
   <!---------------------------------- Verifactu ---------------------------------->
   <!---------------------------------- Verifactu ---------------------------------->
   <div class="row mt-3 zona_trabajo_fe timeline" style="display:none">
      <div class="col-xl-12 col-md-12 col-sm-12 col-12">
         <div class="mt-container mx-auto">
            <div class="timeline-line"></div> <!-- Contenedor del timeline -->
         </div>
      </div>
   </div>

   <div class="row zona_trabajo_fe mt-5 " style="display:none">
      <div class="col-xs-12">
         <a OnClick="mostrar_detalle_factura()" class="btn btn-outline-primary _effect--ripple waves-effect waves-light">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-box">
               <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
               <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
               <line x1="12" y1="22.08" x2="12" y2="12"></line>
            </svg>
            Volver al Documento
         </a>
      </div>
   </div>
   <!---------------------------------- Verifactu ---------------------------------->
   <!---------------------------------- Verifactu ---------------------------------->
   <!---------------------------------- Verifactu ---------------------------------->








</div><!-- Content --->
<!-- MODAL  -->
<div class="modal fade" id="nueva_direccion" tabindex="-1" role="dialog" aria-labelledby="nueva_direccion_label" aria-hidden="true">
      <!-- MODAL  -->

  </div>
<?php
if ($Documento->estado_pagada == 0) {
   include_once ENLACE_SERVIDOR . 'mod_europa_facturacion/tpl/registrar_pagos.php';
}
?>
<!-- Scripts -->
<?php
include_once(ENLACE_SERVIDOR . "mod_documentos_mercantiles/tpl/footer_document.php");
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js"></script>

<script>
   function anular_factura( ){
      Swal.fire({
         title: 'Anular documento',
         html: `Esta acción sobre el documento <?php echo $Documento->referencia; ?> es irrevocable !! `,
         icon: 'warning',
         showCancelButton: true,
         confirmButtonText: 'Si, Anular',
         cancelButtonText: 'No',
         reverseButtons: true 
      }).then((result) => {
         if (result.isConfirmed) {
            window.location.href = "<?php echo ENLACE_WEB; ?>anular_factura/<?php echo $Documento->id; ?>";
         } else if (result.dismiss === Swal.DismissReason.cancel) {
            // Acciones a realizar si se cancela
         }
      });
   }
</script>


<script>
   let params_descuento = {
      aplica_descuento_articulo: false,
      aplica_descuento_volumen: false,
      id_articulo: 0,
      cantidad: 0,
      total: 0,
      id_listaprecio: 0
   }


   function actualizar_datos_cliente_atributos_y_forma(id_cliente, nombre_cliente, cliente_aplica_recargo, cliente_aplica_retencion, forma_pago, id_agente=0) {
     
      //setear valor en checkboxs
      $("#aplicar_descuento").prop('checked', false);
      $("#aplicar_irp").prop('checked', false);
      $("#aplicar_RE").prop('checked', false);

      if (cliente_aplica_recargo == 1 || cliente_aplica_recargo == 'true') {
         $("#aplicar_RE").prop('checked', true);
      }

      if (cliente_aplica_retencion == 1 || cliente_aplica_retencion == 'true') {
         $("#aplicar_irp").prop('checked', true);
      }

      aplicar_descuento();
      aplicar_irp();
      aplicar_RE();
      ajustar_referencia();

      $('#forma_pago').val(forma_pago)
      if(id_agente!=0){
         $('#asesor_comercial_txt').val(id_agente)
      }
      const fk_documento = "<?php echo $Documento->id; ?>" ?? "";
      //Ajax para actualizar el detalle
      if (fk_documento != '') {
         $.post("<?php echo ENLACE_WEB; ?>mod_documentos_mercantiles/ajax/guardar_cambio_x_ajax.php", {
               campo: "fk_tercero",
               valor: id_cliente,
               tipo: '<?php echo $Documento->documento ?>',
               documento: "<?php echo $Documento->id; ?>",
               campovalor: JSON.stringify([ { campo: "forma_pago", valor: forma_pago } ])
            })
            .done(function(message) {
               const respuesta = JSON.parse(message)
               console.log(message);

               if (respuesta.error == 0) {
                  if (fk_documento != '') {
                     add_notification({
                        text: 'Cliente actualizado correctamente',
                        actionTextColor: '#fff',
                        backgroundColor: '#00ab55',
                        dismissText: 'Cerrar',
                        duration: 30000,
                     });
                  }
               } else {
                  add_notification({
                     text: respuesta.mensaje_txt_actualizado,
                     actionTextColor: '#fff',
                     backgroundColor: '#e7515a',
                  });
               }
            });
      }
   }



   $(document).ready(function() {
      // Desactivar todos los elementos del menú
      $(".menu").removeClass('active');
      $(".facturacion").addClass('active');

      // setTimeout(function() {
      //    $("html").addClass('sidebar-noneoverflow');
      //    $(".main-container").addClass("sidebar-closed");
      //    $(".header ").addClass("expand-header");
      //    $(".overlay").addClass('show');
      //    $(".main-container").addClass('sbar-open');
      // }, 5000);
      /* Para el Timeline */



     

      /* Captura del evento cuando se agrega un Producto */
      window.addEventListener('producto_modal_event', function(event) {
         const data_producto = event.detail;
         params_descuento.id_articulo = data_producto.id
         params_descuento.total = data_producto.subtotal
         params_descuento.cantidad = 1
         refrezcar_politica_descuento()
      });
      /* Captura del evento cuando se agrega un Producto */
      /* Captura del evento cuando se agrega o modifica un cliente */
      window.addEventListener('cliente_modal_event', function(event) {
         const data_tercero = event.detail;
         actualizar_datos_cliente_atributos_y_forma(data_tercero.id, data_tercero.value, data_tercero.impuesto_cliente_aplica_recargo_equivalencia, data_tercero.impuesto_cliente_lleva_retencion, data_tercero.forma_pago, data_tercero.fk_agente)
      });
      /* Captura del evento cuando se agrega o modifica un cliente */
      /* Captura del evento cuando se agrega o elimina una línea de documento */
      window.addEventListener('document_modal_event', function(event) {
         console.log('evento documento: ', event.detail)
         $("#_pago_efectivo").removeAttr("style");
         $("#_pago_tarjeta").removeAttr("style");
         $("#_pago_mixto").removeAttr("style");
         $("#_pago_diferido").removeAttr("style");

         aplicar_descuento();
         aplicar_irp();
         aplicar_RE();
         ajustar_referencia();
      });
      /* Captura del evento cuando se agrega o elimina una línea de documento */



      $('#aplicar_descuento').change(function() {
         aplicar_descuento();
         ajustar_referencia();
      });
      $('#aplicar_irp').change(function() {
         aplicar_irp();
         ajustar_referencia();
      });
      $('#aplicar_RE').change(function() {
         aplicar_RE();
         ajustar_referencia();
      });
      aplicar_descuento();
      aplicar_irp();
      aplicar_RE();
      ajustar_referencia();

   });

   function validar_fecha_vencimiento(element) {
      event.preventDefault()
      const fecha_vencimiento = moment($('#fecha_vencimiento').val())
      const fecha = moment($('#fecha').val())

      const dif_fechas = fecha_vencimiento.diff(fecha, 'days');

      if (dif_fechas < 0) {
         add_notification({
            text: 'La Fecha Vencimiento no debe ser posterior a la Fecha Validación',
            actionTextColor: '#fff',
            backgroundColor: '#e7515a',
         });
         $('#fecha_vencimiento').val($('#fecha').val())
         return false
      }
      return true
   }


   function aplicar_irp() {
      const irps_en_fila = $("td[id^='item_retencion']").text()
      const contiene_valores = dejar_solo_numero(irps_en_fila)

      $("#tabla_facturacion").addClass("borroso");
      if (contiene_valores == '') {
         $(".columnas_retencion").fadeOut();
      }
      if ($('#aplicar_irp').is(':checked')) {
         $(".columnas_retencion_1").fadeIn();
      } else {
         $(".columnas_retencion_1").fadeOut();
      }
      // if ($('#aplicar_irp').is(':checked')) {
      //    $(".columnas_retencion").fadeIn();
      // } else {
      //    if (contiene_valores == '') {
      //       $(".columnas_retencion").fadeOut();
      //    }
      // }
      $("#tabla_facturacion").removeClass("borroso");
      // $('#_retencion').attr('checked', $('#aplicar_irp').is(':checked')?'checked':'' )
      $('#_retencion').removeAttr('checked')
      if ($('#aplicar_irp').is(':checked')) {
         $('#_retencion').attr('checked', 'checked')
      }
   }

   function aplicar_RE() {
      const res_en_fila = $("td[id^='item_equivalencia']").text()
      const contiene_valores = dejar_solo_numero(res_en_fila)

      $("#tabla_facturacion").addClass("borroso");
      if (contiene_valores == '') {
         $(".columnas_equivalencia").fadeOut();
      }
      if ($('#aplicar_RE').is(':checked')) {
         $(".columnas_equivalencia_1").fadeIn();
      } else {
         $(".columnas_equivalencia_1").fadeOut();
      }
      // if ($('#aplicar_RE').is(':checked')) {

      //    $(".columnas_equivalencia").fadeIn();
      // } else {
      //    if (contiene_valores == '') {
      //       $(".columnas_equivalencia").fadeOut();
      //    }
      // }
      $("#tabla_facturacion").removeClass("borroso");
      // $('#_recargo_equivalencia').attr('checked', $('#aplicar_RE').is(':checked')?'checked':'' )
      $('#_recargo_equivalencia').removeAttr('checked')
      if ($('#aplicar_RE').is(':checked')) {
         $('#_recargo_equivalencia').attr('checked', 'checked')
      }
   }

   function aplicar_descuento() {
      const descuentos_en_fila = $("label[id^='label_descuento']").text()
      const contiene_valores = dejar_solo_numero(descuentos_en_fila)

      $("#tabla_facturacion").addClass("borroso");
      if (contiene_valores == '') {
         $(".columnas_descuento").fadeOut();
      }
      if ($('#aplicar_descuento').is(':checked')) {
         $(".columnas_descuento_1").fadeIn();
      } else {
         $(".columnas_descuento_1").fadeOut();
      }
      // if ($('#aplicar_descuento').is(':checked')) {

      //    $(".columnas_descuento").fadeIn();
      // } else {
      //    if (contiene_valores == '') {
      //       $(".columnas_descuento").fadeOut();
      //    }
      // }
      $("#tabla_facturacion").removeClass("borroso");
   }

   function ajustar_referencia() {
      let num_columns_left = 0
      if (!$('#aplicar_descuento').is(':checked')) {
         num_columns_left++
      }
      if (!$('#aplicar_irp').is(':checked')) {
         num_columns_left++
      }
      if (!$('#aplicar_RE').is(':checked')) {
         num_columns_left++
      }
      $('#columna_referencia').attr('colspan', 7 - num_columns_left);
   }

   function muestra_opciones_descuento() {
      if ($("#muestra_opciones").css('display') == 'none') {
         $("#muestra_opciones").show();
      } else {
         $("#muestra_opciones").fadeOut(200);
      }
   }



   function refrezcar_politica_descuento() {
      const cliente_id = 0
      $.ajax({
            method: "POST",
            url: "<?php echo ENLACE_WEB; ?>mod_productos/json/productos_politicas.json.php",
            data: params_descuento
         })
         .done(function(msg) {

         });
   }



   function abrir_email() {
      largeur = 600;
      hauteur = 550;
      opt = 'width=' + largeur + ', height=' + hauteur + ', left=' + (screen.width - largeur) / 2 + ', top=' + (screen.height - hauteur) / 2 + '';
      window.open('<?php echo ENLACE_WEB ?>mail_sys/mail/enviar_email.tpl.php?id=<?php echo $_REQUEST['fiche'] ?>&opcion=<?php echo $Documento->nombre_clase; ?>', 'Enviar Email Factura', opt);
   }

   function validar_factura() {
      let codigo_serie_selected = 9999
      let htmlSelect = '';
      let texto = '';
      
      $.ajax({
         type: 'POST',
         url: '<?php echo ENLACE_WEB; ?>mod_europa_facturacion_series/json/series_por_empresa.json.php',
         data: {
            documento: '<?php echo $Documento->documento; ?>' ,
            id       : '<?php echo $Documento->idEncriptado; ?>' 

         },
         async: false,
         success: function(message) {
            const data = JSON.parse(message);
            if (data.length > 1) {
               htmlSelect = '<select id="validationOption" class="form-control">';
               data.forEach(item => {
                  htmlSelect += `<option value="${item.rowid}">${item.serie_por_defecto==1?'✔':''}  ${item.fk_serie_modelo}  -  ${item.serie_descripcion} </option>`;
                  
                  if (item.tipo_aeat == "F1"){
                     texto="Mostrando las Series Facturas Normales";
                  } else {
                     texto="Mostrando las Series Facturas Simplificadas - Cliente no cuenta con datos facturaci&oacute;n ";
                  }

               })
               htmlSelect += '</select>';


            }
            if (data.length == 1) {
               codigo_serie_selected = data[0].rowid
            }
            if (data.length == 0) {
               codigo_serie_selected = 0
            }
         },
         error: function(error) {
            console.error('Error:', error);
         }
      });

      let codigo_bodega_selected = 9999
      let htmlSelectBodega = '';

      $.ajax({
         type: 'POST',
         url: '<?php echo ENLACE_WEB; ?>mod_stock/json/listar_bodegas.json.php',
         async: false,
         success: function(message) {
            const data = JSON.parse(message);
            if (data.length > 1) {
               htmlSelectBodega = '<select id="validationOptionBodega" class="form-control">';
               data.forEach(item => {
                  htmlSelectBodega += `<option value="${item.rowid}">${item.bodega_defecto==1?'(*)':''} ${item.nota} </option>`;
               })
               htmlSelectBodega += '</select>';
            }
            if (data.length == 1) {
               codigo_bodega_selected = data[0].rowid
            }
            if (data.length == 0) {
               codigo_bodega_selected = 0
            }
         },
         error: function(error) {
            console.error('Error:', error);
         }
      });

      let email_label = '<small>Cliente sin correo.¿Desea ingresar uno?</small><br/>';
      const email_correo = $('#fk_tercero_email').val();
      if(email_correo != ''){ email_label = `<small>Enviar PDF a: (OPCIONAL)</small><br/>` }

      Swal.fire({
         title: 'Fiscalizar documento',
         html: `
               ${email_label}
               <div style="position: relative; display: inline-block;" class="col-12">
                  <input type="text" id="email_documento_pdf" name="email_documento_pdf" class="form-control" value="${email_correo}" style="padding-right: 30px;">
                  <button type="button" id="clear-email" style="position: absolute; right: 5px; top: 50%; transform: translateY(-50%); border: none; background: none;" onclick="$(this).prev().val('')">
                     <i class="fa-solid fa-eraser" aria-hidden="true"></i>
                  </button>
               </div><br/>
               ¿Deseas fiscalizar el documento <?php echo $Documento->referencia; ?> ?<br>
               ${ (codigo_serie_selected == 9999 ? 
               texto+' Debe seleccionar una Serie del documento y continuar. <br>' + htmlSelect:  '') }
               ${ (codigo_bodega_selected == 9999 ? 
               'Debe seleccionar una Bodega y continuar. <br>' + htmlSelectBodega:  '') }
               `,
         icon: 'warning',
         showCancelButton: true,
         confirmButtonText: 'Si, Fiscalizar',
         cancelButtonText: 'No',
         reverseButtons: true,
         preConfirm: () => {
            if (codigo_serie_selected == 9999) {
               const optionSelected = $('#validationOption').val()
               if (optionSelected == undefined) {
                  Swal.showValidationMessage('Por favor, seleccione una Serie');
                  return false;
               } else {
                  codigo_serie_selected = optionSelected;
               }
            }

            if (codigo_bodega_selected == 9999) {
               const optionSelectedBodega = $('#validationOptionBodega').val()
               if (optionSelectedBodega == undefined) {
                  Swal.showValidationMessage('Por favor, seleccione una Bodega');
                  return false;
               } else {
                  codigo_bodega_selected = optionSelectedBodega;
               }
            }
         }
      }).then((result) => {
         if (result.isConfirmed) {
            window.location.href = "<?php echo ENLACE_WEB; ?>dashboard.php?&accion=validar_factura&fiche=<?php echo $Documento->id; ?>&option_serie=" + codigo_serie_selected + "&option_bodega=" + codigo_bodega_selected + "&option_email=" + $('#email_documento_pdf').val();
         } else if (result.dismiss === Swal.DismissReason.cancel) {
            // Acciones a realizar si se cancela
         }
      });
   }

   function marcar_item(item){
      $('.items_pdf').css('background-color', '');
      $('.dropdown-item').find('i').remove();

      $(item).parent().css('background-color', '#d4ac0d');
      $(item).prepend('<i class="fa fa-fw fa-check" aria-hidden="true"></i>');

   }
   listarDirecciones();
   function agregarDireccion(int = null){
      console.log('agregarDireccion');     
      // Preparar la petición AJAX
      $.ajax({
         method: "POST",
         url: "<?php echo ENLACE_WEB; ?>mod_direcciones/tpl/modal_direcciones.php",
         beforeSend: function(xhr) {
            // aqui deberia ocurrir una carga
         },
         data: {
            action: 'ver_direccion',
            fiche: int,
            tipo: 1,
            fk_entidad: $('#fk_tercero').val()
         },
      }).done(function(html) {
         //print html en el modal cargado
         $("#nueva_direccion").html(html).modal('show');
      });
   }
   
   function listarDirecciones(idDireccion = null){
      console.log('listarDirecciones');
      $.ajax({
            method: "POST",
            url: '<?= ENLACE_WEB ?>mod_direcciones/class/direcciones.class.php',
            beforeSend: function(xhr) {},
            data: {
               action: "BuscarDirecciones",
               fk_entidad: $('#fk_tercero').val(),
               selected: idDireccion != null ? idDireccion : '<?= $Documento->fk_direccion ?>'
            },
        }).done(function(data) {
            $("#fk_direccion").html(data);
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.error("Error en la petición AJAX:", textStatus, errorThrown);

            add_notification({
                text: 'Error con la Peticion - Vuelve a Intentarlo',
                actionTextColor: '#fff',
                backgroundColor: '#e7515a'
            });
        });
   }

   function actualizar_detalle_direccion(idDireccion = null) {

      $.ajax({
         method: "POST",
         url: '<?= ENLACE_WEB ?>mod_direcciones/class/direcciones.class.php',
         beforeSend: function(xhr) {
            // aqui deberia ocurrir una carga
         },
         data: {
            action: 'ActualizarFacturaDireccion',
            idfactura: '<?php echo $Documento->id ?>',
            iddireccion: idDireccion != null ? idDireccion : $('#fk_direccion').val()
         },
      }).done(function(data) {
              var mensaje = jQuery.parseJSON(data);
              if (mensaje.exito == 1) {
                  add_notification({
                      text: mensaje.mensaje,
                      actionTextColor: '#fff',
                      backgroundColor: '#00ab55',
                      dismissText: 'Cerrar'
                  });
              } else {
                  add_notification({
                      text: "Error:" + mensaje.mensaje,
                      actionTextColor: '#fff',
                      actionTextColor: '#fff',
                      backgroundColor: '#e7515a',
                  });
              }
          });
   }
</script>

