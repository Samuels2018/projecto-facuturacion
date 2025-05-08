<?php


include_once(ENLACE_SERVIDOR . "mod_documentos_mercantiles/tpl/header_document.php");
include_once(ENLACE_SERVIDOR . "mod_europa_compra/object/compras.object.php");
require_once ENLACE_SERVIDOR . 'mod_stock/object/bodegas.object.php';
require_once ENLACE_SERVIDOR . 'mod_proyectos/object/Proyectos.object.php';
require_once ENLACE_SERVIDOR . 'mod_configuracion_agente/object/agente.object.php';

require_once ENLACE_SERVIDOR . 'mod_documento_pdf/object/plantilla.object.php';

$Documento = new Compra($dbh, $_SESSION['Entidad']);
$Proyectos = new Proyectos($dbh, $_SESSION['Entidad']);
$lista_proyectos = $Proyectos->listar_proyectos();

$mensaje_javascript = [];
$id = $_GET['fiche'];
$Documento->fetch($id);

$tipo = $Documento->nombre_clase;

if ($_GET['accion'] == "validar_compra" and $Documento->estado == 0) {
   $Documento->validar($_SESSION['usuario'], $_GET['option_serie']);

   if ($Documento->movimiento_origen == '') {
      $Bodega = new Bodegas($dbh, $_SESSION['Entidad']);
      $Bodega->usuario = $_SESSION["usuario"];
      $Bodega->documento_tipo = "compra";
      $Bodega->motivo         = "Compra";
      $Bodega->mover_bodega_en_compra($Documento, $_SESSION['usuario'], $_GET['option_bodega']);
   }
}
if ($_GET['accion'] == "anular_compra" and $Documento->estado > 0) {
   $Documento->cambiar_estado(6);
   $Documento->registrar_log_documento($_SESSION['usuario'], 1, "Documento Anulado Dentro del Trimestre Activo");
}
if ($_GET['accion'] == "cancelar_compra" and $Documento->estado > 0) {
   $Documento->cambiar_estado(5);
   $Documento->registrar_log_documento($_SESSION['usuario'], 1, "Documento Cancelado Dentro del Trimestre Activo");
}
$Documento->fetch($id);

$Plantilla = new Plantilla($dbh, $_SESSION['Entidad']);
$lista_plantillas = $Plantilla->obtener_plantilla_tipo_documento($Documento->documento);

$Agentes = new Agente($dbh, $_SESSION['Entidad']);
$lista_agentes = $Agentes->obtener_agentes();

$Documento->fetch_compra($id);

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
            <li class="breadcrumb-item" aria-current="page"><a href="<?php echo ENLACE_WEB; ?>compra_listado"><?php echo $Documento->documento_txt['singular']; ?></a></li>
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
                        <button OnClick="muestra_opciones_descuento()" class="btn btn-success btn-icon mb-2 me-4 btn-rounded _effect--ripple waves-effect waves-light">
                           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings">
                              <circle cx="12" cy="12" r="3"></circle>
                              <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 .85 1.65 1.65 0 0 1-3.1 0 1.65 1.65 0 0 0-1-.85 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-.85-1 1.65 1.65 0 0 1 0-3.1 1.65 1.65 0 0 0 .85-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33h.1a1.65 1.65 0 0 0 1-.85 1.65 1.65 0 0 1 3.1 0 1.65 1.65 0 0 0 1 .85h.1a1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82v.1a1.65 1.65 0 0 0 .85 1 1.65 1.65 0 0 1 0 3.1 1.65 1.65 0 0 0-.85 1v.1z"></path>
                           </svg>
                        </button>
                     </div>
                  </div>


                  <!-- Parte izquierda de la cabecera (Descuentos) -->
                  <div class="row alert alert-outline-primary alert-dismissible fade show mb-4" role="alert" id="muestra_opciones" style="display:none">
                     <button type="button" class="btn-close" onclick="muestra_opciones_descuento()" aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close">
                           <line x1="18" y1="6" x2="6" y2="18"></line>
                           <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                     </button>
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
               </div>
            </div>
         </div>


         <!-- Parte derecha con fechas  -->
         <div class="col-md-5">
            <div class="card">
               <div class="card-body">
                  <div class="form-group row">
                     <div class="col-sm-6">
                        <h3><a href="<?php echo ENLACE_WEB; ?>compra/<?php echo $Documento->id; ?>"><span><?php echo $Documento->referencia; ?></span></a></h3>
                     </div>
                  </div>
                  <div class="form-group row">
                     <label for="serie_proveedor" class="col-sm-6 col-form-label col-form-label-sm">Serie Proveedor</label>
                     <div class="col-sm-6" id="lugar_campo_forma_pago">
                        <input id="serie_proveedor" name="serie_proveedor" type="text" required class="form-control " value="<?php echo $Documento->serie_proveedor; ?>"
                           onblur="actualizar_detalle_documento(this, '<?php echo $Documento->documento; ?>', <?php echo $Documento->id; ?>)">
                     </div>
                  </div>
                  <?php if ($Documento->estado == 0) { ?>
                     <div class="form-group row">
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
                  <div class="form-group row">
                     <label for="forma_pago" class="col-sm-6 col-form-label col-form-label-sm">Estado</label>
                     <div class="col-sm-6 " id="lugar_campo_forma_pago">
                        <span class="badge badge-light-<?php echo $Documento->diccionario_estados[$Documento->estado]['class']; ?>"><?php echo $Documento->diccionario_estados[$Documento->estado]['etiqueta']; ?></span>
                     </div>
                  </div>


               </div>

            </div>

         </div>

      </div>
      <!--- row  Cabecera-->



      <div class="row mt-3 zona_trabajo_factura ">
         <div class="col-xs-12">
            <?php
            include_once(ENLACE_SERVIDOR . "mod_documentos_mercantiles/tpl/documento_detalle.php");
            ?>
         </div>
         <div class="row mt-5">
            <div class="col-xs-12">
               <a href="<?php echo ENLACE_WEB; ?>compra_listado" class="btn btn-outline-primary _effect--ripple waves-effect waves-light">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-box">
                     <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                     <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                     <line x1="12" y1="22.08" x2="12" y2="12"></line>
                  </svg>
                  Volver al Listado
               </a>
               <?php if ($Documento->estado  != 0) { ?>
                  <a class="btn btn-primary _effect--ripple waves-effect waves-light" href="#" onclick="abrir_email()"><i class="fa  fa-stack-exchange" aria-hidden="true"></i> Enviar Por Email</a>
               <?php } ?>

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
                        <li>
                           <hr>
                        </li>

                        <?php if ($Documento->estado == 0) {   ?> <?php } ?>
                        <?php if (count($lista_plantillas) > 0) {
                           foreach ($lista_plantillas as $itemplantilla) {
                        ?>
                              <li>
                                 <a class="dropdown-item" href="#" onclick="generarPdf(<?php echo $Documento->id; ?>, '<?php echo $Documento->nombre_clase; ?>', '<?php echo $Documento->documento_txt['singular'] . '-' . $Documento->referencia; ?>', '<?php echo $itemplantilla["rowid"]; ?>')">Descargar <?php echo $Documento->referencia . "( " . $itemplantilla["titulo"] . " )"; ?> - PDF</a>
                              </li>
                           <?php
                           }
                        } else { ?>
                           <li>
                              <a class="dropdown-item" href="#" onclick="generarPdf(<?php echo $Documento->id; ?>, '<?php echo $Documento->nombre_clase; ?>', '<?php echo $Documento->documento_txt['singular'] . '-' . $Documento->referencia; ?>')">Descargar <?php echo $Documento->referencia; ?> - PDF</a>
                           </li>
                        <?php } ?>
                        <?php if ($Documento->estado  != 0) { ?>

                           <?php if (file_exists($filePath)) { ?>
                              <li> <a class="dropdown-item" href="<?php echo ENLACE_WEB; ?>mod_documentos_mercantiles/ajax/descargar.comprobante.php?tipo=compra&id=<?php echo $Documento->id; ?>">Descargar <?php echo $Documento->referencia; ?> - XML </a></li>
                              </li>
                           <?php } ?>
                           <?php if ($respuestaValida): ?>
                              <li> <a class="dropdown-item" href="<?php echo ENLACE_WEB; ?>mod_documentos_mercantiles/ajax/descargar.xml.php?tipo=compra&id=<?php echo $Documento->id; ?>">Descargar Compra Respuesta <?php echo $Documento->referencia; ?> - XML</a>
                              </li>
                           <?php endif; ?>
                        <?php } ?>


                        <?php if ($Documento->estado > 0) {   ?>
                           <li>
                              <a class="dropdown-item" href="<?php echo ENLACE_WEB . "log/" . $Documento->nombre_clase . "/" . $Documento->id; ?>">Log Auditoria <?php echo $Documento->referencia; ?></a>
                           </li>

                        <?php } ?>
                        <?php if ($Documento->estado >=  1) {  ?>
                           <li>
                              <a class="dropdown-item" href="#" onclick="cancelar_documento()" style="background-color:#e2a03f">
                                 <i class="fa fa-fw fa-warning" aria-hidden="true"></i> Cancelar <?php echo $Documento->documento_txt['singular']; ?>
                              </a>
                           </li>
                        <?php } ?>
                        <?php if ($Documento->estado >=  1) {  ?>
                           <li>
                              <a class="dropdown-item" href="#" onclick="anular_documento()" style="background-color:#E7515A">
                                 <i class="fa fa-fw fa-warning" aria-hidden="true"></i> Anular <?php echo $Documento->documento_txt['singular']; ?>
                              </a>
                           </li>
                        <?php } ?>
                     </ul>

                  </div>
               <?php } ?>

               <?php if ($Documento->estado == 0) {   ?>
                  <a href="#" onclick="validar_compra()" class="btn btn-outline-primary _effect--ripple waves-effect">
                     <i class="fas fa-check"></i>
                     Aprobar
                  </a>
               <?php } ?>

            </div>
            <!-- col -->





         </div>
      </div>
   </div>

</div><!-- Content --->

<!-- Scripts -->
<?php
include_once(ENLACE_SERVIDOR . "mod_documentos_mercantiles/tpl/footer_document.php");
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js"></script>


<script>
   let params_descuento = {
      aplica_descuento_articulo: false,
      aplica_descuento_volumen: false,
      id_articulo: 0,
      cantidad: 0,
      total: 0,
      id_listaprecio: 0
   }


   function actualizar_datos_cliente_atributos_y_forma(id_cliente, nombre_cliente, cliente_aplica_recargo, cliente_aplica_retencion, forma_pago) {

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

      const fk_documento = "<?php echo $Documento->id; ?>" ?? "";
      //Ajax para actualizar el detalle
      if (fk_documento != '') {
         $.post("<?php echo ENLACE_WEB; ?>mod_documentos_mercantiles/ajax/guardar_cambio_x_ajax.php", {
               campo: "fk_tercero",
               valor: id_cliente,
               tipo: '<?php echo $Documento->documento ?>',
               documento: "<?php echo $Documento->id; ?>",
               campovalor: JSON.stringify([{
                  campo: "forma_pago",
                  valor: forma_pago
               }])
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
      $('#icon_edit_client').html('<i class="fas fa-edit" aria-hidden="true"></i>')

   }



   $(document).ready(function() {

      // Desactivar todos los elementos del menú
      $(".menu").removeClass('active');
      $(".compras").addClass('active');
      $(".compras > .submenu").addClass('show');
      $("#nueva_compra").addClass('active');



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
         actualizar_datos_cliente_atributos_y_forma(data_tercero.id, data_tercero.value, data_tercero.impuesto_cliente_aplica_recargo_equivalencia, data_tercero.impuesto_cliente_lleva_retencion, data_tercero.forma_pago)
      });
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

      /* Capturo el evento sumar_confirmar para realizar Validaciones */
      if ($('#sumar_confirmar')) {
         $('#sumar_confirmar').removeAttr('onclick');
         $('#sumar_confirmar').attr('onclick', "sumar_confirmar_compra()");
      }
      if ($('#serie_proveedor')) {
         $('#serie_proveedor').removeAttr('onblur');
         $('#serie_proveedor').attr('onblur', "actualizar_detalle_documento_compra()");
      }
      /* Capturo el evento sumar_confirmar para realizar Validaciones */

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
      window.open('<?php echo ENLACE_WEB ?>mail_sys/mail/enviar_email.tpl.php?id=<?php echo $_REQUEST['fiche'] ?>&opcion=<?php echo $Documento->nombre_clase; ?>', 'Enviar Email Compra', opt);
   }

   function validar_compra() {
      let codigo_serie_selected = 9999
      let htmlSelect = '';
      $.ajax({
         type: 'POST',
         url: '<?php echo ENLACE_WEB; ?>mod_europa_facturacion_series/json/series_por_empresa.json.php',
         data: {
            documento: '<?php echo $Documento->documento; ?>'
         },
         async: false,
         success: function(message) {
            const data = JSON.parse(message);
            if (data.length > 1) {
               htmlSelect = '<select id="validationOption" class="form-control">';
               data.forEach(item => {
                  htmlSelect += `<option value="${item.rowid}">${item.serie_por_defecto==1?'✔':''}  ${item.fk_serie_modelo}  -  ${item.serie_descripcion} </option>`;
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

      Swal.fire({
         title: 'Aprobar documento',
         html: `
               ¿Deseas aprobar el documento <?php echo $Documento->referencia; ?> ?<br>
               ${ (codigo_serie_selected == 9999 ? 
               'Debe seleccionar una Serie del documento y continuar. <br>' + htmlSelect:  '') }
               ${ (codigo_bodega_selected == 9999 ? 
               'Debe seleccionar una Bodega y continuar. <br>' + htmlSelectBodega:  '') }
               `,
         icon: 'warning',
         showCancelButton: true,
         confirmButtonText: 'Si, Aprobar',
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
            window.location.href = "<?php echo ENLACE_WEB; ?>dashboard.php?&accion=validar_compra&fiche=<?php echo $Documento->id; ?>&option_serie=" + codigo_serie_selected + "&option_bodega=" + codigo_bodega_selected;
         } else if (result.dismiss === Swal.DismissReason.cancel) {
            // Acciones a realizar si se cancela
         }
      });
   }


   function anular_documento() {
      Swal.fire({
         title: 'Anular <?php echo $Documento->referencia; ?>',
         html: `Esta accion sobre el documento <?php echo $Documento->documento_txt['singular']; ?>  irrevocable  `,
         icon: 'warning',
         showCancelButton: true,
         confirmButtonText: 'Si, Anular',
         cancelButtonText: 'No',
         reverseButtons: true
      }).then((result) => {
         if (result.isConfirmed) {
            window.location.href = "<?php echo ENLACE_WEB; ?>anular_compra/<?php echo $Documento->id; ?>";
         } else if (result.dismiss === Swal.DismissReason.cancel) {
            // Acciones a realizar si se cancela
         }
      });
   }

   function cancelar_documento() {
      Swal.fire({
         title: 'Cancelar <?php echo $Documento->referencia; ?>',
         html: `Esta accion sobre el documento <?php echo $Documento->documento_txt['singular']; ?>  irrevocable  `,
         icon: 'warning',
         showCancelButton: true,
         confirmButtonText: 'Si, Cancelar',
         cancelButtonText: 'No',
         reverseButtons: true
      }).then((result) => {
         if (result.isConfirmed) {
            window.location.href = "<?php echo ENLACE_WEB; ?>cancelar_compra/<?php echo $Documento->id; ?>";
         } else if (result.dismiss === Swal.DismissReason.cancel) {
            // Acciones a realizar si se cancela
         }
      });
   }


   function validar_serie_proveedor() {
      let retorno_respuesta = true;
      $('#serie_proveedor').removeClass('input_error');
      if ($('#serie_proveedor').val() != '') {
         $.ajax({
            type: 'POST',
            url: "<?php echo ENLACE_WEB; ?>mod_europa_compra/ajax/validaciones_compra_ajax.php",
            data: {
               action: 'validar_serie_proveedor',
               documento: "<?php echo $Documento->id; ?>",
               serie_proveedor: $('#serie_proveedor').val()
            },
            async: false, // Esto lo hace síncrono
            success: function(message) {
               respuesta = JSON.parse(message)

               if (respuesta.error == 0) {
                  retorno_respuesta = false;
               } else {
                  $('#serie_proveedor').addClass('input_error');
                  add_notification({
                     text: respuesta.mensaje,
                     actionTextColor: '#fff',
                     backgroundColor: (respuesta.error == 0 ? '#00ab55' : '#e7515a'),
                     dismissText: 'Cerrar',
                     duration: 30000,
                  });
               }
            },
            error: function(error) {
               console.error('Error en la solicitud:', error);
            }
         });
      }
      else{
         $('#serie_proveedor').addClass('input_error');
         add_notification({
            text: 'Faltan Datos Obligatorios',
            actionTextColor: '#fff',
            backgroundColor: '#e7515a',
         });
         retorno_respuesta = true;
      }
      return retorno_respuesta;
   }



   function sumar_confirmar_compra() {
      const error_confirmar = validar_serie_proveedor();
      // Si hay errores, mostrar notificación y detener el envío del formulario
      if (!error_confirmar) {
         sumar_confirmar();
      }
   }

   function actualizar_detalle_documento_compra() {
      const error_confirmar = validar_serie_proveedor();
      // Si hay errores, mostrar notificación y detener el envío del formulario
      if (!error_confirmar) {
         actualizar_detalle_documento($('#serie_proveedor')[0], `<?php echo $Documento->documento; ?>`, '<?php echo $Documento->id; ?>');
      }
   }
</script>