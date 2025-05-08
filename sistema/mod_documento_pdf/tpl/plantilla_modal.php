<?php
session_start();

//si no hay usuario autenticado, cerrar conexion
if (!isset($_SESSION['usuario'])) {
     echo acceso_invalido();
     exit(1);
}


include_once("../../conf/conf.php");
include_once ENLACE_SERVIDOR . 'mod_documento_pdf/object/plantilla.object.php';
$obj = new Plantilla($dbh, $_SESSION['Entidad']);
$Utilidades->obtener_diccionario_transacciones_documentos();
if (!empty($_REQUEST['fiche'])) {
     $obj->fetch($_REQUEST['fiche']);
}

if ($obj->id > 0) {
     $titulo = 'Modificar';
} else {
     $titulo = 'Crear';
}
?>

<style>
     .modal-dialog.modal-xl {
          max-width: 90%;
     }

     .header_style_document {
          position: relative !important;
          top: 112 !important;
          left: -15 !important;
          rotate: -90 !important;
          text-align: left !important;
          width: 100% !important;
          font-size: 12px !important;
          border: 1px solid !important;
          padding: 5px !important;
          margin-left: -30px !important;
     }
</style>

<div class="modal-dialog modal-xl" role="document">
     <div class="modal-content">

          <div class="modal-header">
               <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-list"></i> <?= $titulo ?> Plantilla </h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

               </button>
          </div>
          <div class="modal-body">
               <form role="form" method="POST" action="" id="formulario">
                    <div class="card-body">
                         <div class="row">

                              <div class="col-lg-6">
                                   <div class="row mt-3">
                                        <div class="col-md-6">
                                             <label for="ref"><i class="fa fa-fw fa-font "></i> Título </label>
                                             <input type="text" name="titulo" id="titulo" required class="form-control" <?php echo $disabled; ?> value="<?php echo $obj->titulo; ?>"></input>
                                        </div>
                                        <div class="col-md-2">
                                             <label for="ref"><i class="fa fa-fw fa-gear"></i> Orden</label>
                                             <input required placeholder="Orden de la plantilla" type="number" min="1" name="orden" id="orden" class="form-control" value="<?php echo isset($obj->orden) ? $obj->orden : 1; ?>" <?php echo $disabled; ?>>
                                        </div>
                                        <div class="col-md-4">
                                             <label for="ref"><i class="fa fa-fw fa-gear"></i> Tipo de documento</label>
                                             <select name="tipo" id="tipo" class="form-control" required>
                                                  <option value="">Selecciona Tipo de documento</option>
                                                  <?php
                                                  foreach ($Utilidades->diccionario_transacciones_documentos as $key => $array) {
                                                       echo "<option value='{$array['tabla']}'  " . (($array['tabla'] == $obj->tipo) ? 'selected="selected"' : '') . "  >{$array['descripcion']} </option> ";
                                                  }
                                                  ?>
                                             </select>
                                        </div>
                                   </div>
                                   <div class="row mt-3">
                                        <div class="col-md-6">
                                             <div class="switch form-switch-custom switch-inline form-switch-primary mt-3">
                                                  <input class="switch-input" type="checkbox" role="switch" id="defecto" name="defecto" value="<?php echo $obj->defecto; ?>" <?php echo ($obj->defecto == 1) ? 'checked' : ''; ?> <?php echo $disabled; ?>>
                                                  <label class="switch-label" for="tosell">Marcar como Por Defecto</label>
                                             </div>
                                        </div>
                                        <?php if ($obj->id > 0 && $obj->borrado == 0) { ?>
                                             <div class="col-md-6">
                                                  <label for="ref"><i class="fa fa-fw fa-check"></i> Estado</label><br />
                                                  <select class="form-select form-control-sm " name="activo" id="activo" required>
                                                       <option value="1" <?php echo $obj->activo == 1 ? 'selected' : ''; ?>>Activo</option>
                                                       <option value="0" <?php echo $obj->activo == 0 ? 'selected' : ''; ?>>Inactivo</option>
                                                  </select>
                                             </div>
                                        <?php } ?>
                                   </div>

                                   <div class="row mt-3">
                                        <div class="col-md-12">
                                             <label for="ref"><i class="fa fa-fw fa-font "></i> HTML de la plantilla </label>
                                             <textarea name="plantilla_html" id="plantilla_html" rows="5" required class="form-control" <?php echo $disabled; ?>><?php echo html_entity_decode($obj->plantilla_html); ?></textarea>
                                        </div>
                                   </div>
                                   <div class="row mt-3">
                                        <div class="col-md-12">
                                             <label for="ref"><i class="fa fa-fw fa-font "></i> CSS de la plantilla </label>
                                             <textarea name="plantilla_css" id="plantilla_css" rows="5" required class="form-control" <?php echo $disabled; ?>><?php echo html_entity_decode($obj->plantilla_css); ?></textarea>
                                        </div>
                                   </div>

<?php                                                   
        // Mejora Manejo de Extras en el Formulario
        include_once(ENLACE_SERVIDOR . "mod_campos_extra_formularios/object/campos.extra.object.php");
        $Extra = new Extra($dbh, $_SESSION['Entidad']);
        $Extra->Generar_Formulario("fi_europa_facturas", 0);
        $ii=0;
        foreach ($Extra->datos as $key => $extra) {
          $ii++;
          $tr_extra.= "<tr>";
          $tr_extra.= "<td>{$ii} </td> " ;
          $tr_extra.= "<td>{$extra['input_etiqueta']} </td> " ;
          $tr_extra.= "<td>{{extra_".$key."_input_etiqueta}} </td> " ;
          $tr_extra.= "<td>{{extra_".$key."_input_valor}} </td> " ;
          $tr_extra.= "</tr>";
        }
        // Mejora Manejo de Extras en el Formulario
          $tr_extra_="";

        if ($ii>0) {
          $tr_extra_= "<table>";
          $tr_extra.="<p>Estos son los campos Extras Disponibles : </p>";
          $tr_extra_.="<tr>
                    <th></th>
                    <th>Campo</th>
                    <th>Etiqueta </th>
                    <th>Valor</th>
               </tr>";
               $tr_extra_.= $tr_extra;
               $tr_extra_.= "</table>";
        } else{
          $tr_extra_.= "No tienes campos extras configurados para esta Empresa.";
        }

?>

                                   <div class="row mt-5">
                                        <div class="col-md-12">
                                             <label for="ref"><i class="fa fa-fw fa-font "></i> Campos Extras Disponibles </label>
                                                <?php echo $tr_extra_; ?>       
                                        </div>
                                   </div>

                              </div>
                              <div class="col-lg-6">
                                   <ul class="nav nav-pills" id="animateLine" role="tablist">
                                        <li class="nav-item" role="presentation">
                                             <button class="nav-link active" id="animated-html-tab" data-bs-toggle="tab" href="#animated-html" role="tab" aria-controls="animated-html" aria-selected="true" onclick="preview_html_plantilla(event,<?= $obj->id; ?>)">
                                             Vista HTML Preview</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                             <button class="nav-link" id="animated-pdf-tab" data-bs-toggle="tab" href="#animated-pdf" role="tab" aria-controls="animated-pdf" aria-selected="false" tabindex="-1" onclick="preview_pdf_plantilla(event,<?= $obj->id; ?>)">
                                             Vista PDF Preview</button>
                                        </li>
                                   </ul>
                                   <div class="tab-content" id="animateLineContent-4">
                                        <div class="tab-pane fade active show" id="animated-html" role="tabpanel" aria-labelledby="animated-html-tab">
                                             <style id="preview_css">
                                             </style>
                                             <div class="row mt-3" id="preview_html">
                                             </div>
                                        </div>
                                        <div class="tab-pane fade active show" id="animated-pdf" role="tabpanel" aria-labelledby="animated-pdf-tab">
                                             <div class="row mt-3" id="preview_pdf">
                                             </div>
                                        </div>
                                   </div>

                              </div>

                         </div>

                    </div>
               </form>
          </div>
          <div class="modal-footer">
               <button class="btn btn btn-light-dark" data-bs-dismiss="modal"><i class="flaticon-cancel-12"></i>Cancelar</button>

               <?php if (!($obj->id > 0)) { ?>
                    <button type="button" class="btn btn-primary" id="agregar_parametro" onclick="crear_plantilla(event,null)"><i class="fa fa-fw fa-circle" aria-hidden="true"></i> Guardar</button>
               <?php } else { ?>
                    <button type="button" class="btn btn-danger" id="borrar_parametro" onclick="confirma_eliminar(<?= $obj->id; ?>)"><i class="fa fa-fw fa-trash" aria-hidden="true"></i> Borrar</button>
                    <button type="button" class="btn btn-primary" id="actualizar_parametro" onclick="crear_plantilla(event,<?= $obj->id; ?>)"><i class="fa fa-fw fa-circle" aria-hidden="true"></i> Actualizar</button>

               <?php
               } ?>

          </div>

     </div>
</div>

<script>
     $(document).ready(function() {
          $('#plantilla_css').on('input', function(event) {
               $('#preview_css').html('');
               $('#preview_css').html($('#plantilla_css').val());
          })
          $('#plantilla_html').on('input', function(event) {
               $('#preview_html').html('');
               $('#preview_html').html($('#plantilla_html').val());
          })

          $('#preview_css').html('');
          $('#preview_css').html($('#plantilla_css').val());
          $('#preview_html').html('');
          $('#preview_html').html($('#plantilla_html').val());
     })


     function preview_pdf_plantilla(event, id) {
          event.preventDefault()
          let error = false;
          $('#preview_pdf').html('');
          // Preparar la petición AJAX
          $.ajax({
               method: "POST",
               url: "<?php echo ENLACE_WEB; ?>mod_documento_pdf/ajax/plantilla_configuracion.ajax.php",
               beforeSend: function(xhr) {},
               data: {
                    action: 'preview_pdf_plantilla',
                    id: id
               }
          }).done(function(msg) {
               const pdfData = 'data:application/pdf;base64,' + msg;
               const pdfEmbed = `
                    <embed src="` + pdfData + `" type="application/pdf" width="100%" height="600px" />
               `;
               $('#preview_pdf').html(pdfEmbed);                 
          });
     }
     function preview_html_plantilla(event, id) {
          event.preventDefault()
          $('#preview_css').html('');
          $('#preview_css').html($('#plantilla_css').val());
          $('#preview_html').html('');
          $('#preview_html').html($('#plantilla_html').val());
     }

     function crear_plantilla(event, id) {

          let error = false;

          /* Valida los inputs requeridos */
          const inputTypes = [];
          $('.modal-dialog input[name][id][value]').each(function(index, element) {
               inputTypes.push({
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
          });
          $('.modal-dialog textarea[name][id]').each(function(index, element) {
               inputTypes.push({
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
          const data = inputTypes.reduce((acc, item) => {
               if (item.name == 'defecto') {
                    acc[item.name] = $('#defecto').prop('checked') ? 1 : 0;
               } else {
                    acc[item.name] = item.value;
               }
               return acc;
          }, {
               action: (id > 0 ? 'actualizar_plantilla' : 'crear_plantilla'),
               id: id
          });

          // Preparar la petición AJAX
          $.ajax({
               method: "POST",
               url: "<?php echo ENLACE_WEB; ?>mod_documento_pdf/ajax/plantilla_configuracion.ajax.php",
               beforeSend: function(xhr) {},
               data: data
          }).done(function(msg) {
               var mensaje = jQuery.parseJSON(msg);

               if (mensaje.exito == 1) {
                    $("#nueva_plantilla").modal('hide');

                    $('#style-3').DataTable().ajax.reload();

                    add_notification({
                         text: 'Plantilla actualizada exitosamente',
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