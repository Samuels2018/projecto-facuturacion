<?php
require_once ENLACE_SERVIDOR . "mod_formas_pago/object/forma_pago_object.php";
require_once ENLACE_SERVIDOR . "mod_regimen_iva/object/regimen_iva_object.php";
require_once ENLACE_SERVIDOR . "mod_terceros/object/terceros.object.php";

$forma_pago = new Forma_pago($dbh);
$forma_pago->entidad = $_SESSION['Entidad'];
$formas_pago = $forma_pago->listar_formas_pago();
$regimen_iva = new regimen_iva($dbh_utilidades_Apoyo);
$lista_regimen = $regimen_iva->listar_regimen_iva();
$listar_tipos_retencion = $regimen_iva->listar_tipos_retencion();

if ($Documento == null) {
     echo acceso_invalido();
     exit(1);
}
$es_cliente = true;
if ($Documento->cliente_proveedor != '' && $Documento->cliente_proveedor == 'proveedor') {
     $es_cliente = false;
}

$Utilidades = new Utilidades($dbh);
$paises = $Utilidades->obtener_paises();
$tipos_residencias = $Utilidades->obtener_tipo_residencias();
$tipos_identificacion = $Utilidades->obtener_identificaciones_fiscales();

if(!empty($Documento->fk_tercero) ){
     $objTercero = new FiTerceros($dbh, $_SESSION["Entidad"]);
     $objTercero->fetch(($Documento->fk_tercero));
}

?>

<div class="row">
     <div class="col-sm-3">
          <label for="company-name" class="col-form-label col-form-label-sm"><?php echo $es_cliente ? 'Cliente' : 'Proveedor' ?></label>
          <?php if ($Documento->estado == 0): ?>
               <span class="offset-1" style="cursor:pointer;" data-bs-toggle="modal" data-bs-target="#NewClient">[Nuevo]</span>
          <?php endif ?>
     </div>
     <div class="col-sm-9">
          <input type="text" class="form-control form-control-sm" style="display:none" id="company-name" placeholder="" value="">
          <div class="row pb-2">
               <div class="col-sm-9">
                    <input type="hidden" id="fk_tercero" value="<?php echo ($Documento->fk_tercero) ?>">
                    <input type="hidden" id="fk_tercero_name" value="<?php echo ($Documento->nombre_cliente) ?>">
                    <input type="hidden" id="fk_tercero_email" value="<?php echo ($Documento->fk_tercero_email) ?>">
                    <div id="input_busqueda_tercero">
                         <input type="text" id="search_tercero" placeholder="✔️ Digite nombre del <?php echo $es_cliente ? 'Cliente' : 'Proveedor' ?>" class="form-control form-control-sm ui-autocomplete-input" autocomplete="off">
                    </div>
                    <div id="mostrar_nombre" class="row">
                         <a id="link_tercero"
                              href="<?php echo (($Documento->fk_tercero != '') ? ENLACE_WEB . 'clientes_editar/' . $Documento->fk_tercero : '#'); ?>"
                              <?php echo ($Documento->fk_tercero != '' ? 'target="blank"' : ''); ?>>
                              <span id="basic-cliente" style="<?php echo ($Documento->fk_tercero != '' ? 'color:#00c0ef"' : ''); ?>">
                                   <?php echo (($Documento->fk_tercero != '') ? $Documento->nombre_cliente. ' • ' .$Documento->email_cliente : ($es_cliente ? 'Cliente' : 'Proveedor') . ' Genérico'); ?>
                              </span>
                         </a>
                    </div>
                    <div class="row">
                         <div class="col-md-12">
                              <div id="loading_cliente" style="display:none">Cargando</div>
                         </div>
                    </div>
               </div>
               <?php if ($Documento->estado == 0): ?>
                    <div class="col-sm-1">
                         <span id="icon_edit_client" style="color: white !important;  background-color: #00c0ef !important; cursor: pointer; display: flex; justify-content: center; align-items: center;" onclick="eliminar_tercero_buscador(false)" class="btn">
                              <i class="fas fa-edit" aria-hidden="true"></i>
                         </span>
                    </div>
                    <div class="col-sm-1">
                         <span id="icon_generic_client" style="color: white !important;  background-color: red !important; cursor: pointer; display: flex; justify-content: center; align-items: center;" onclick="tercero_generico()" class="btn">
                              <i class="fas fa-cancel" aria-hidden="true"></i>
                         </span>
                    </div>
               <?php endif; ?>
          </div>
     </div>
</div>


<!-- modal crear cliente-->
<div id="NewClient" class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-lg">

          <div class="modal-content">
               <div class="modal-header">
                    <strong><i class="fa fa-user"></i> REGISTRO DE <?php echo $es_cliente ? 'CLIENTE' : 'PROVEEDOR'; ?> NUEVO</strong>
               </div>

               <div class="modal-body">

                    <div class="row">
                         <div class="col-md-6">
                              <div class="mb-3">
                                   <label for="cliente_tipo" class="form-label"><i class="fa fa-fw fa-paperclip"></i>Tipo de persona<span style="color:red">*</span></label>
                                   <select class="form-control" required name="cliente_tipo" id="cliente_tipo" onchange="cliente_cambio_tipo_cliente()">
                                        <option value="fisica">Físico</option>
                                        <option value="juridica">Jurídico</option>
                                   </select>
                              </div>
                         </div>
                         <div class="col-md-6">
                              <div class="mb-3">
                                   <label for="tipo_residencia" class="form-label"><i class="fa fa-fw fa-paperclip"></i>Tipo de residencia<span style="color:red">*</span></label>
                                   <select class="form-control" required name="tipo_residencia" id="tipo_residencia">
                                        <option value="">Seleccione</option>
                                        <?php
                                        foreach ($tipos_residencias as $tipo) {
                                             echo '<option value="' . $tipo->rowid . '"  >' . $tipo->descripcion . '</option>';
                                        }
                                        ?>
                                   </select>
                              </div>
                         </div>

                         <div class="col-md-6">
                              <div class="mb-3">
                                   <label for="tipo_documento" class="form-label"><i class="fa fa-fw fa-paperclip"></i>Tipo de identificación<span style="color:red">*</span></label>
                                   <select class="form-control" required name="tipo_documento" id="tipo_documento">
                                        <option value="">Seleccione</option>
                                        <?php
                                        foreach ($tipos_identificacion as $tipo) {
                                             echo '<option value="' . $tipo->rowid . '"  >' . $tipo->descripcion . '</option>';
                                        }
                                        ?>
                                   </select>
                              </div>
                         </div>
                         <div class="col-md-6">
                              <div class="mb-3">
                                   <label for="cliente_cedula" class="form-label"><i class="fa fa-fw fa-book"></i>Nro identificación<span style="color:red">*</span></label>
                                   <input autocomplete="off" required type="text" name="cliente_cedula" id="cliente_cedula" data-mask-clearifnotmatch="true" class="form-control" value="" placeholder="Nro identificación">
                              </div>
                         </div>
                         <div class="col-md-6">
                              <div class="mb-3">
                                   <label for="cliente_nombre" class="form-label" id="cliente_nombre_label"><i class="fa fa-fw fa-paperclip"></i>Nombre</label>
                                   <input required autocomplete="off" type="text" name="cliente_nombre" id="cliente_nombre" class="form-control" value="" placeholder="Nombre">
                              </div>
                         </div>
                         
                         <div class="col-md-6" id="div_apellidos">
                              <div class="mb-3">
                                   <label for="cliente_apellidos" class="form-label" id="cliente_apellido_label"><i class="fa fa-fw fa-user"></i>Apellidos</label>
                                   <input autocomplete="off" type="text" name="cliente_apellidos" id="cliente_apellidos" class="form-control" value="" placeholder="Apellidos">
                              </div>
                         </div>
                         <div class="col-md-6" id="div_nombre_comercial_fisica">
                              <label for="cliente_comercial"><i class="fa fa-fw fa-user" aria-hidden="true"></i>
                                   <span>Nombre comercial</span>
                              </label>
                              <input type="text" id="cliente_comercial" name="cliente_comercial" class="form-control" placeholder="Nombre comercial">
                         </div>
                    </div>
                    <div class="row">
                         <div class="col-md-6">
                              <div>
                                   <label for="cliente_forma_pago">
                                        <span id="">Forma de Pago</span>
                                   </label>
                                   <select required class="form-control" name="cliente_forma_pago" id="cliente_forma_pago">
                                        <option value="0">Seleccione</option>
                                        <?php
                                        foreach ($formas_pago as $formas_pago) {
                                             echo '<option value="' . $formas_pago->rowid . '"  >' . $formas_pago->label . '</option>';
                                        }
                                        ?>
                                   </select>
                              </div>
                         </div>
                         <div class="col-md-6">
                              <label for="email"> Email</label>
                              <input type="text" id="cliente_email" name="cliente_email" class="form-control" value="" required>
                         </div>
                         <div class="col-md-6">
                              <label for="cliente_telefono"> Teléfono</label>
                              <input type="number" id="cliente_telefono" name="cliente_telefono" placeholder="(sin guiones y/o espacios)" class="form-control" value="" required>
                         </div>

                         <div class="row">
                              <div class="col-md-6">
                                   <div class="form-group">
                                        <label for="fk_pais"><i class="fa fa-globe" aria-hidden="true"></i> País <span>*</span></label>
                                        <?php
                                        $pais_selected = isset($cliente->fk_pais) ? $cliente->fk_pais : '';
                                        ?>
                                        <select required name="fk_pais" id="fk_pais" class="form-control" <?php echo $disabled; ?>>
                                             <option value="">Seleccione el país</option>
                                             <?php foreach ($paises as $pais) { ?>
                                                  <option value="<?php echo $pais->rowid; ?>" <?php echo (intval($pais_selected) === intval($pais->rowid)) ? 'selected' : ''; ?>><?php echo $pais->nombre; ?></option>
                                             <?php } ?>
                                        </select>
                                   </div>
                              </div>
                              <div class="col-md-6">
                                   <div class="form-group">
                                        <label for="codigo_postal"><i class="fa fa-postcode" aria-hidden="true"></i> Código Postal</label>
                                        <input required type="number" class="form-control mb-3" id="codigo_postal" name="codigo_postal" placeholder="Código Postal" value="<?php echo $cliente->codigo_postal; ?>">
                                   </div>
                              </div>
                              <div class="col-md-6">
                                   <div class="form-group">
                                        <label for="ccaa"><i class="fa fa-city" aria-hidden="true"></i> CCAA <span>*</span></label>
                                        <select required name="ccaa" id="ccaa" class="form-control" >
                                             <option value='' >Seleccione una CCAA</option>
                                        </select>
                                   </div>
                              </div>

                              <div class="col-md-6">
                                   <div class="form-group">
                                        <label for="provincia"><i class="fa fa-flag" aria-hidden="true"></i> Provincia <span>*</span></label>
                                        <select required name="provincia" id="provincia" class="form-control" >
                                             <option value='' >Seleccione una Provincia</option>
                                        </select>
                                   </div>
                              </div>
                              <div class="col-md-12">
                                   <div class="form-group">
                                        <label for="direccion"><i class="fa fa-address-book" aria-hidden="true"></i> Dirección </label>

                                        <textarea required id="direccion" name="direccion" class="form-control" style="width:100%;  height: 80px;" ><?php echo $cliente->direccion; ?></textarea>

                                   </div>
                              </div>
                         </div>

                    </div>
                    <div class="row">
                         <div class="col-md-6">
                              <div>
                                   <label for="cliente_impuesto_cliente_fk_diccionario_regimen_iva">
                                        <span id="">¿Aplica Iva?</span>
                                   </label>
                                   <select class="form-control" name="cliente_impuesto_cliente_fk_diccionario_regimen_iva" id="cliente_impuesto_cliente_fk_diccionario_regimen_iva">
                                        <?php
                                        foreach ($lista_regimen as $key => $value) { ?>
                                             <option value="<?php echo $lista_regimen[$key]['rowid']; ?>" <?php echo $selected; ?>><?php echo $lista_regimen[$key]['etiqueta']; ?></option>
                                        <?php } ?>
                                   </select>
                              </div>
                         </div>
                         <div class="col-md-6">
                              <div>
                                   <label for="cliente_impuesto_cliente_aplica_recargo_equivalencia">
                                        <span id="">¿Aplica Recargo de equivalencia?</span>
                                   </label>
                                   <div class="switch form-switch-custom switch-inline form-switch-primary" style="display: block;">
                                        <input class="switch-input" type="checkbox" role="switch" id="cliente_impuesto_cliente_aplica_recargo_equivalencia"
                                             name="cliente_impuesto_cliente_aplica_recargo_equivalencia">
                                   </div>
                              </div>
                         </div>

                         <div class="col-md-6">
                              <div>
                                   <label for="cliente_impuesto_cliente_lleva_retencion">
                                        <span id="">Retención al IRPF</span>
                                   </label>
                                   <div class="switch form-switch-custom switch-inline form-switch-primary" style="display: block;">
                                        <input class="switch-input" type="checkbox" role="switch" id="cliente_impuesto_cliente_lleva_retencion"
                                             name="cliente_impuesto_cliente_lleva_retencion">
                                   </div>
                              </div>
                         </div>
                         <div class="col-md-6">
                              <div>
                                   <label for="country">Tipo de Retención</label>
                                   <select name="cliente_impuesto_cliente_regimen_iva_tipos_retencion" class="form-control" id="cliente_impuesto_cliente_regimen_iva_tipos_retencion">
                                        <option value="">Seleccionar</option>
                                        <?php foreach ($listar_tipos_retencion as $key => $value) {  ?>
                                             <option value="<?php echo $listar_tipos_retencion[$key]['rowid']; ?>"><?php echo $listar_tipos_retencion[$key]['etiqueta']; ?></option>
                                        <?php } ?>
                                   </select>
                              </div>
                         </div>
                    </div>




               </div>

               <div class="modal-footer">
                    <div class="mt-2">
                         <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="crear_cliente_modal()"><i class="fa fa-fw fa-plus" aria-hidden="true"></i>Crear <?php echo $es_cliente ? 'Cliente' : 'Proveedor' ?></button>
                         <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="$('#NewClient').modal('hide');">Cerrar</button>
                    </div>
               </div>

          </div>

     </div>
</div>
<!-- fin modal-->

<script>
     let codigo_tercero_factura_temporal = 0;
     let codigo_tercero_factura_nombre_temporal = '';

     function eliminar_tercero_buscador(setting, id_cliente = 0, name_cliente = '') {
          codigo_tercero_factura_temporal = $("#fk_tercero").val();
          codigo_tercero_factura_nombre_temporal = $("#fk_tercero_name").val();

          $("#search_tercero").val('');
          if ($('#mostrar_nombre').css('display') == 'none') {
               $('#icon_edit_client').html('<i class="fas fa-edit" aria-hidden="true"></i>')
               $("#input_busqueda_tercero").hide();
               $("#mostrar_nombre").show();
               $("#fk_tercero").val(codigo_tercero_factura_temporal);
               $("#fk_tercero_name").val(codigo_tercero_factura_nombre_temporal)
               $("#mostrar_direccion").show();
          } else {
               $('#icon_edit_client').html('<i class="fa-solid fa-rotate-left"></i>')
               $("#input_busqueda_tercero").show();
               $("#mostrar_nombre").hide();
               $("#search_tercero").focus();
               $("#mostrar_direccion").hide();
          }
          if (setting) {
               $("#input_busqueda_tercero").hide();
               $("#mostrar_nombre").show();
               $("#mostrar_nombre").html(`
               <a id="link_tercero" href="<?php echo ENLACE_WEB . 'clientes_editar/'; ?>${id_cliente}"  target="blank" > 
                    <span id="basic-cliente" style="color:#00c0ef">
                         ${name_cliente}
                    </span>
               </a> `);
               $("#fk_tercero").val(id_cliente)
               $("#fk_tercero_name").val(name_cliente)
               $('#icon_edit_client').html('<i class="fas fa-edit" aria-hidden="true"></i>')
               $("#mostrar_direccion").show();
               listarDirecciones();
          }
     }

     function cliente_cambio_tipo_cliente() {
          $('#cliente_comercial').val('')
          $('#cliente_apellidos').val('')
          if ($('#cliente_tipo').val() == 'fisica') {
               $('#cliente_nombre_label').text('Nombre')
               $('#cliente_nombre_label').attr('placeholder', 'Nombre')
               $('#div_apellidos').css('display', 'block')               
          } 
          if ($('#cliente_tipo').val() == 'juridica') {
               $('#cliente_nombre_label').text('Razón Social')
               $('#cliente_nombre_label').attr('placeholder', 'Razón Social')
               $('#div_apellidos').css('display', 'none')
          }
     }

     function crear_cliente_modal(event) {
          let error = false;

          /* Valida los inputs requeridos */
          const inputTypes = []; const AllinputTypes = [];
          $('.modal-dialog input[name][id]').each(function(index, element) {
               inputTypes.push({
                    name: $(this).attr('id'),
                    value: $(this).val(),
                    required: ($(this).attr('required') || false)
               })
               AllinputTypes.push({
                    name: $(this).attr('id'),
                    value: $(this).val(),
                    required: ($(this).attr('required') || false)
               })
          });
          $('.modal-dialog select[name][id]').each(function(index, element) {
               inputTypes.push({
                    name: $(this).attr('id'),
                    value: $(this).val(),
                    required: ($(this).attr('required') || false)
               })
               AllinputTypes.push({
                    name: $(this).attr('id'),
                    value: $(this).val(),
                    required: ($(this).attr('required') || false)
               })
          });
          $('.modal-dialog textarea[name][id]').each(function(index, element) {
               inputTypes.push({
                    name: $(this).attr('id'),
                    value: $(this).val(),
                    required: ($(this).attr('required') || false)
               })
               AllinputTypes.push({
                    name: $(this).attr('id'),
                    value: $(this).val(),
                    required: ($(this).attr('required') || false)
               })
          });
          inputTypes.map(x => $('#' + x.name).removeClass('input_error'))
          inputTypes.map((x) => {
               if (x.required && x.value == '') {
                    $('#' + x.name).addClass('input_error');
                    error = true;
               }
          })
          if($('#cliente_tipo').val() == 'fisica'){
               if($('#cliente_apellidos').val() == ''){
                    $('#cliente_apellidos').addClass('input_error');
                    error = true;
               }
          }
          if($('#cliente_tipo').val() == 'juridica'){
               if($('#cliente_comercial').val() == ''){
                    $('#cliente_comercial').addClass('input_error');
                    error = true;
               }
          }
          if(!validateEmail('cliente_email')){
               $('#cliente_email').addClass('input_error');
               error = true;
          }

          // Si hay errores, mostrar notificación y detener el envío del formulario
          if (error) {
               add_notification({
                    text: 'Faltan Datos Obligatorios',
                    actionTextColor: '#fff',
                    backgroundColor: '#e7515a',
               });
               return true;
          }
          /* Valida los inputs requeridos */

          inputTypes.forEach(item => {
               if (item.name.startsWith("cliente_")) {
                    item.name = item.name.replace("cliente_", "");
               }
          });
          inputTypes.forEach(item => {
               if (item.value == "on") {
                    item.value = 1
               }
          });
          inputTypes.forEach(item => {
               if (item.value == "off") {
                    item.value = 0
               }
          });
          const data = inputTypes.reduce((acc, item) => {
               acc[item.name] = item.value;
               return acc;
          }, {
               action: 'nuevo_cliente_factura',
               cliente: <?php echo $es_cliente ? 1 : 0 ?>,
               proveedor: <?php echo $es_cliente ? 0 : 1 ?>
          });
          data.impuesto_cliente_lleva_retencion = 0;
          data.impuesto_cliente_aplica_recargo_equivalencia = 0;
          if($('#cliente_impuesto_cliente_lleva_retencion').is(':checked')){
               data.impuesto_cliente_lleva_retencion = 1;
          }
          if($('#cliente_impuesto_cliente_aplica_recargo_equivalencia').is(':checked')){
               data.impuesto_cliente_aplica_recargo_equivalencia = 1;
          }
          
          $.ajax({
               method: "POST",
               url: "<?php echo ENLACE_WEB; ?>mod_terceros/class/terceros.class.php",
               data: data,
          }).done(function(msg) {
               const response = JSON.parse(msg);
               if (response.error == 0) {
                    add_notification({
                         text: 'Se creó correctamente al cliente',
                         actionTextColor: '#fff',
                         backgroundColor: '#00ab55',
                    });
                    $("#NewClient").modal('hide');
                    let nombre_completo = $('#cliente_nombre').val() + ' ' + $('#cliente_apellidos').val() +' • '+ $('#cliente_email').val()
                    $('#fk_tercero_email').val($('#cliente_email').val());

                    // Lógica para setear el valor de param1
                    let eventFired = new CustomEvent('cliente_modal_event', {
                         detail: { id: response.lastid, label : nombre_completo, value : nombre_completo, impuesto_cliente_aplica_recargo_equivalencia: $('#cliente_impuesto_cliente_aplica_recargo_equivalencia').prop('checked'), 
                              impuesto_cliente_lleva_retencion: $('#cliente_impuesto_cliente_lleva_retencion').prop('checked'), forma_pago: $('#cliente_forma_pago').val(), email: $('#cliente_email').val(), telefono: $('#cliente_telefono').val() }
                    });

                    eliminar_tercero_buscador(true, response.lastid, nombre_completo);
                    window.dispatchEvent(eventFired);
                    $('#icon_edit_client').html('<i class="fas fa-edit" aria-hidden="true"></i>')
                    // actualizar_datos_cliente_atributos_y_forma(response.lastid, nombre_completo, $('#cliente_impuesto_cliente_aplica_recargo_equivalencia').prop('checked'), $('#cliente_impuesto_cliente_lleva_retencion').prop('checked'), $('#cliente_forma_pago').val())

                    $.each(AllinputTypes, function(index, item) {
                         if(item.name == 'cliente_forma_pago'){
                              $("#" + item.name).val('0');     
                         }
                         else if(item.name == 'cliente_tipo'){
                              $("#" + item.name).val('fisica');     
                         }else{
                              $("#" + item.name).val('');
                         }
                    });
                    $('#cliente_impuesto_cliente_lleva_retencion')[0].checked = false;
                    $('#cliente_impuesto_cliente_aplica_recargo_equivalencia')[0].checked = false;
               }else{
                    add_notification({
                         text: 'Hubo un error al actualizar la información.',
                         pos: 'top-right',
                         actionTextColor: '#fff',
                         backgroundColor: '#e7515a'
                    });
               }
          }).fail(function(jqXHR, textStatus, errorThrown) {
               console.error("Error en la petición AJAX:", textStatus, errorThrown);

               add_notification({
                    text: 'Hubo un error al actualizar la información.',
                    pos: 'top-right',
                    actionTextColor: '#fff',
                    backgroundColor: '#e7515a'
               });
          });
     }

     function tercero_generico(){
          // Lógica para setear el valor de param1
          eliminar_tercero_buscador(true)
          $('#link_tercero').attr('href', '#');
          $('#basic-cliente').removeAttr('style');
          $('#basic-cliente').text( '<?php echo ($es_cliente ? 'Cliente' : 'Proveedor')?> Genérico' );
          $('#fk_tercero').val('');
          $('#fk_tercero_name').val('');
          $('#fk_tercero_email').val('');
          let eventFired = new CustomEvent('cliente_modal_event', {
               detail: { id: 0, value : '', impuesto_cliente_aplica_recargo_equivalencia: 0, 
                    impuesto_cliente_lleva_retencion: 0, forma_pago: 0, email: '' }
          });
          window.dispatchEvent(eventFired);
          $("#mostrar_direccion").hide();
     }

     $(document).ready(function() {
          $("#input_busqueda_tercero").hide();
          $("#search_tercero").keyup(function() {
               valor = $(this).val();
               if (valor === "") {
                    $("#loading_cliente").hide();
                    $("#icon_edit_client").empty().html(icono_editar);
               }
          });
          $("#search_tercero").autocomplete({
               source: "<?php echo ENLACE_WEB; ?>mod_terceros/json/terceros_facturas.json.php?<?php echo $es_cliente ? 'cliente' : 'proveedor'; ?>=1",
               delay: 300,
               minLength: 2,
               search: function() {
                    // Muestra la animación de carga cuando inicia la búsqueda
                    $("#loading_cliente").empty().fadeOut();
                    $("#icon_edit_client").empty().html(icono_buscando);
               },
               response: function(event, ui) {
                    // Oculta la animación de carga cuando termina la búsqueda y aparecen los resultados
                    if (!ui.content || ui.content.length === 0) {
                         // Si no hay resultados, cambia al ícono de "sin resultados"
                         $("#loading_cliente").html('<i class="fas fa-exclamation-triangle"></i> No se encontraron resultados.').fadeIn();
                         $("#icon_edit_client").empty().html(icono_no_encontrado);
                    } else {
                         // Oculta el ícono de carga cuando hay resultados
                         $("#loading_cliente").hide();
                         $("#icon_edit_client").empty().html(icono_editar);
                    }
               },
               select: function(event, ui) {
                    console.log(ui.item)
                    $('#fk_tercero_email').val(ui.item.email);
                    // Lógica para setear el valor de param1
                    let eventFired = new CustomEvent('cliente_modal_event', {
                         detail: ui.item
                    });

                    eliminar_tercero_buscador(true, ui.item.id, ui.item.value);
                    window.dispatchEvent(eventFired);
                    $('#icon_edit_client').html('<i class="fas fa-edit" aria-hidden="true"></i>')
                    // actualizar_datos_cliente_atributos_y_forma(ui.item.id, ui.item.value, ui.item.impuesto_cliente_aplica_recargo_equivalencia, ui.item.impuesto_cliente_lleva_retencion, ui.item.forma_pago)

                    // //setear valor en checkboxs
                    // $("#aplicar_descuento").prop('checked', false);

                    // if (ui.item.aplicar_descuento_por_articulo == 1) {
                    //      $("#aplicar_descuento").prop('checked', true);
                    // }

                    // params_descuento.aplica_descuento_articulo = ui.item.aplicar_descuento_por_articulo
                    // params_descuento.aplica_descuento_volumen = ui.item.aplicar_descuento_volumen
                    // params_descuento.id_listaprecio = ui.item.fk_lista_precio

                    // refrezcar_politica_descuento()
               }
          }); //FIN AUTOCOMPLETE

          $("#fk_pais").change(function() {
               $('#ccaa').html('')
               $('#ccaa').append(`<option value='' >Seleccione una CCAA</option>`)
               const pais = $(this).val()
               if(pais!=''){
                    const ccaas = modal_cargar_comunidades(pais)
                    ccaas.data.forEach(item => {
                         $('#ccaa').append(`<option value=${item.id} >${item.nombre}</option>`)
                    });
               }
               $("#ccaa").trigger('change')
          })          
          $("#ccaa").change(function() {
               $('#provincia').html('')
               $('#provincia').append(`<option value='' >Seleccione una Provincia</option>`)
               const ccaa = $(this).val()
               if(ccaa!=''){
                    const provincias = modal_cargar_provincia(ccaa)
                    provincias.data.forEach(item => {
                         $('#provincia').append(`<option value=${item.id} >${item.provincia}</option>`)
                    });
               }
          })


           // Evento inicial del componente Tercero
           if('<?php echo ($Documento->fk_tercero) ?>' != ''){
                let eventFired = new CustomEvent('cliente_modal_event', {
                    detail: { 
                         id: '<?php echo ($Documento->fk_tercero) ?>', 
                         label : '<?php echo ($Documento->nombre_cliente) ?>', 
                         value : '<?php echo ($Documento->nombre_cliente) ?>', 
                         impuesto_cliente_aplica_recargo_equivalencia: '<?php echo $objTercero->impuesto_cliente_aplica_recargo_equivalencia ?>', 
                         impuesto_cliente_lleva_retencion: '<?php echo $objTercero->impuesto_cliente_lleva_retencion ?>', 
                         forma_pago: '<?php echo $objTercero->forma_pago ?>', 
                         email: '<?php echo $objTercero->email ?>', 
                         telefono: '<?php echo $objTercero->telefono ?>'
                    },
                    bubbles: true
               });
               window.lastClienteModalEvent = eventFired;
               document.dispatchEvent(eventFired);
               $("#mostrar_direccion").show();

           }
     })
     function modal_cargar_comunidades(pais){
          const comunidades = $.ajax({
               url: `<?php echo ENLACE_WEB; ?>mod_utilidad/json/comunidad_autonoma.json.php?pais=${pais}`,
               async: false,
               dataType: 'json'
          });
          return comunidades.responseJSON
     }     
     function modal_cargar_provincia(ccaa){
          const provincias = $.ajax({
               url: `<?php echo ENLACE_WEB; ?>mod_utilidad/json/comunidad_autonoma_provincia.json.php?ccaa=${ccaa}`,
               async: false,
               dataType: 'json'
          });
          return provincias.responseJSON
     }
</script>