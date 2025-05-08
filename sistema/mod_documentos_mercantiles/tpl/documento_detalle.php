<div id="div_linea_edit">

</div>

<div class="col-xs-12">
     <div class="card">
          <div class="card-body table-responsive no-padding">
               <div class="row">
                    <?php if ($Documento->estado == 0) { ?>
                         <div class="col-md-6">
                              <div class="input-group ui-widget ">
                                   <input type="text" id="productos" class="form-control p-2" placeholder="Digite el Producto" aria-label="Buscador " aria-describedby="basic-addon1">
                                   <span class="input-group-text" id="basic-addon1">
                                        <i class="fas fa-search"></i>
                                   </span>
                              </div>
                         </div>
                         <div class="col-md-6">
                              <div id="loading" style="display:none">Cargando</div>
                         </div>
                    <?php } ?>
               </div>

               <div class="row">
                    <div class="col-md-12">

                         <?php if ($Documento->estado == 0) { ?>
                              <table class="table table-striped" style="margin-top:20px !important;border-bottom: solid 1px;border-color: gray;" id="tabla_facturacion">
                                   <tbody class="input_body">
                                        <tr>
                                             <th style="color:gray" width="5%"> Linea </th>
                                             <th style="color:gray" colspan="2" width="30%"> Descripción</th>
                                             <th style="color:gray" width="5%">Cantidad</th>
                                             <th style="color:gray" width="15%">P. Base</th>
                                             <th style="color:gray" class="columnas_descuento_1" width="20%">Descuento</th>
                                             <th style="color:gray" width="10%">IVA</th>
                                             <th style="color:gray" class="columnas_equivalencia_1" width="5%">RE</th>
                                             <th style="color:gray" class="columnas_retencion_1" width="5%">Retenci&oacute;n</th>
                                             <th style="color:gray" width="5%"></th>
                                        </tr>
                                        <tr id="input_linea" valign="top">
                                             <td style="vertical-align:top">
                                                  <select class="form-control form-control-sm" id="_tipo_servicio_producto">
                                                       <option value="2">Servicio</option>
                                                       <option value="1">Producto</option>
                                                  </select>
                                             </td>
                                             <td style="vertical-align:top" colspan="2">
                                                  <input type="hidden" id="_fk_producto" disabled>
                                                  <textarea class="rezisable-item form-control" id="_nombre" maxlength="160" style="width:100%" required></textarea>
                                             </td>
                                             <td style="vertical-align:top">
                                                  <input class=" form-control form-control-sm" type="number" id="_cantidad" style="width:100%; padding-right:2px" required>
                                             </td>
                                             <td style="vertical-align:top">
                                                  <input class="form-control form-control-sm" type="number" id="_precio_unitario" style="width:100%; padding-right:2px" required>
                                             </td>
                                             <td class="columnas_descuento_1" style="vertical-align:top; padding:0px">
                                                  <div class="row no-gutters">
                                                       <div class="col-sm-6">
                                                            <select id="_descuento_tipo" class="form-select form-select-sm">
                                                                 <option value="absoluto">Monto</option>
                                                                 <option value="porcentual" selected="selected"> % </option>
                                                            </select>
                                                       </div>
                                                       <div class="col-sm-6">
                                                            <input type="number" id="_descuento" class="form-control" style="padding-right:2px">
                                                       </div>
                                                  </div>
                                             </td>
                                             <td style="vertical-align:top">
                                                  <select id="_impuesto" required class="form-select form-select-sm">
                                                       <?php
                                                       foreach ($Documento->diccionario_impuesto_iva() as $key =>  $impuestos_iva) {
                                                            echo "<option value='" . $impuestos_iva['impuesto'] . "'> " . $impuestos_iva['impuesto'] . " % - " . $impuestos_iva['impuesto_texto'] . "</option>";
                                                       }
                                                       ?>
                                                  </select>
                                             </td>
                                             <td class="columnas_equivalencia_1" style="vertical-align:top">
                                                  <input type="checkbox" id="_recargo_equivalencia">
                                             </td>
                                             <td class="columnas_retencion_1" style="vertical-align:top">
                                                  <input type="checkbox" id="_retencion">
                                             </td>
                                             <td style="vertical-align:top" width="100%!important" class='tabla_sin_borde' valign="middle">
                                                  <button id="sumar_confirmar" Onclick="sumar_confirmar()" type="button" class="btn btn-info">
                                                       <i class="fa fa-fw fa-plus-square-o"></i>
                                                       Agregar
                                                  </button>
                                             </td>
                                        </tr>
                                   </tbody>
                              </table>
                         <?php }  ?>
                         <table class="table" style="margin-top:20px !important" id="tabla_facturacion_detalle">
                              <tbody id="listado_factura" class="<?= $ver_factura_items ?>">
                                   <?php if (ENLACE_SERVIDOR . $Documento->ruta_detalle_contenido != '') {
                                        if ($Documento->id != '' && $Documento->id != 0) {
                                             include_once(ENLACE_SERVIDOR . $Documento->ruta_detalle_contenido);
                                        }
                                   } else { ?>
                                        <?php if ($Documento->id != '' && $Documento->id != 0) {
                                             include_once(ENLACE_SERVIDOR . "mod_documentos_mercantiles/ajax/documento_detalle.ajax.php");
                                        } ?>
                                   <?php } ?>

                              </tbody>
                         </table>
                         <table class="table table-striped" style="margin-top:20px !important" id="tabla_facturacion_detalle">
                              <tbody id="listado_factura_totales" class="<?= $ver_factura_items ?>">
                                   <?php if ($Documento->id != '' && $Documento->id != 0) {
                                        include_once(ENLACE_SERVIDOR . "mod_documentos_mercantiles/ajax/documento_detalle_totales.ajax.php");
                                   } ?>
                              </tbody>
                         </table>
                    </div>
               </div>
          </div>
     </div>
</div>

<script>
     $(document).ready(function() {
          /* AUTOCOMPLETE Para la Busqueda de Productos */
          icono_buscador = '<i class="fas fa-search"></i>';
          icono_buscando = '<i class="fas fa-gear fa-spin"></i>';
          icono_encontrado = '<i class="fas fa-check "></i>';
          icono_no_encontrado = '<i class="fas fa-search-minus"></i>';
          icono_editar = '<i class="fa-solid fa-rotate-left"></i>';

          $("#productos").keyup(function() {
               valor = $(this).val();
               if (valor === "") {
                    $("#loading").hide();
                    $("#basic-addon1").empty().html(icono_buscador);
                    // background-color: #00c0ef !important;
               }
          });
          $("#productos").autocomplete({
               source: "<?php echo ENLACE_WEB; ?>mod_productos/json/productos.json.php?cliente=",
               delay: 300,
               minLength: 2,

               search: function() {
                    // Muestra la animación de carga cuando inicia la búsqueda
                    $("#loading").empty().fadeOut();
                    $("#basic-addon1").empty().html(icono_buscando);
                    $("#basic-addon1").css("background-color", "");


               },
               response: function(event, ui) {
                    // Oculta la animación de carga cuando termina la búsqueda y aparecen los resultados
                    if (!ui.content || ui.content.length === 0) {
                         // Si no hay resultados, cambia al ícono de "sin resultados"
                         $("#loading").html('<i class="fas fa-exclamation-triangle"></i> No se encontraron resultados.').fadeIn();
                         $("#basic-addon1").empty().html(icono_no_encontrado);
                    } else {
                         // Oculta el ícono de carga cuando hay resultados
                         $("#loading").hide();
                         $("#basic-addon1").empty().html(icono_buscador);
                    }


               },

               select: function(event, ui) {

                    console.log(ui.item);

                    // Lógica para setear el valor de param1
                    let eventFired = new CustomEvent('producto_modal_event', {
                         detail: ui.item
                    });
                    window.dispatchEvent(eventFired);

                    $("#basic-addon1").empty().html(icono_encontrado);
                    $("#basic-addon1").css("background-color", "#E0B15E");

                    console.log(ui);
                    $("#_nombre").val(ui.item.value);

                    $("#_precio_unitario").val(ui.item.subtotal);
                    $("#_subtotal_1").val(ui.item.subtotal);
                    $("#_impuesto").val(ui.item.impuesto);

                    if (!ui.item.subtotal > 0 || !ui.item.impuesto > 0 || !ui.item.impuesto_detalle > 0) {
                         add_notification({
                              text: 'El producto no tiene definido el Precio Base y/o el Impuesto a aplicar.',
                              actionTextColor: '#fff',
                              backgroundColor: '#e2a03f',
                         });
                    }
                    if (ui.item.impuesto_detalle > 0) {
                         $("#_impuesto").val(ui.item.impuesto_detalle);
                    } else if (ui.item.impuesto > 0) {
                         $("#_impuesto").val(ui.item.impuesto);
                    } else if (ui.item.impuesto_fk > 0) {
                         $("#_impuesto").val(ui.item.impuesto_fk + '.00');
                    }

                    $("#_cantidad").val(1);
                    $("#productos").value = ' ';
                    $("#_fk_producto").val(ui.item.id);

                    $("#_tipo_servicio_producto").val(ui.item.tipo);

                    $('#_precio_unitario').focus()

               }
          });
          /* AUTOCOMPLETE Para la Busqueda de Productos */
     })

     function sumar_confirmar() {
          // Obtener todos los campos con el atributo 'required'
          const divlinea = document.getElementById('input_linea')
          const camposRequeridos = divlinea.querySelectorAll('[required]');
          let camposValidos = true;

          camposRequeridos.forEach(campo => {
               if (!campo.value.trim()) {
                    // Si el campo está vacío, añadir la clase is-invalid
                    campo.classList.add('input_error');
                    camposValidos = false;
               } else {
                    if (campo.id == '_precio_unitario') {
                         if (dejar_solo_numero(campo.value) == "") {
                              campo.classList.add('input_error');
                              camposValidos = false;
                         }
                    } else if (campo.id == '_cantidad') {
                         if (dejar_solo_numero(campo.value) == "") {
                              campo.classList.add('input_error');
                              camposValidos = false;
                         }
                    } else {
                         campo.classList.remove('input_error');
                    }
               }
          });

          if (!camposValidos) {
               // Mostrar un mensaje si hay campos vacíos
               // alert("Campos no validos");
               add_notification({
                    text: 'Faltan Datos Obligatorios',
                    actionTextColor: '#fff',
                    backgroundColor: '#e7515a',
               });
               return;
          }

          $("#basic-addon1").empty().html(icono_buscador);
          $("#basic-addon1").css("background-color", "");


          $.post("<?php echo ENLACE_WEB; ?>mod_documentos_mercantiles/ajax/documento.validaciones_cliente.ajax.php?fiche=<?php echo $Documento->id; ?>", {
                    fk_tercero: $('#fk_tercero').val(),
                    forma_pago: $('#forma_pago').val(),
                    monto: ($("#_precio_unitario").val() * $("#_cantidad").val()),
                    forzar_venta: 0
               })
               .done(function(response) {

                    const mensajes = JSON.parse(response)
                    let mensajes_alert = []
                    let tiene_errores = false
                    if (mensajes.length > 0) {
                         // Si llegan mensajes desde el Backend con validaciones de Cliente
                         mensajes_alert = mensajes.map((x) => {
                              return x.mensaje
                         })
                         if (mensajes.find((x) => x.tipo == 'error') != undefined) {
                              tiene_errores = true
                         }
                         if (!tiene_errores && mensajes.length > 0) {
                              // Tiene mensajes warnings
                              mensajes_alert = mensajes_alert.concat('¿Desea continuar con la venta de todos modos?')
                              Swal.fire({
                                   title: 'Validaciones del cliente',
                                   text: mensajes_alert.join('\n'),
                                   icon: 'warning',
                                   showCancelButton: true,
                                   confirmButtonText: 'Si, continuar',
                                   cancelButtonText: 'No'
                              }).then((result) => {
                                   if (result.isConfirmed) {
                                        sumar()
                                   } else {
                                        $("#_subtotal").empty();
                                        $("#_subtotal_1").empty();
                                        $('#_fk_producto').empty();
                                        $("#productos").focus();
                                   }
                              })
                         } else {
                              // Tiene errores
                              Swal.fire({
                                   title: 'Validaciones del cliente',
                                   text: mensajes_alert.join('\n'),
                                   icon: 'error'
                              })
                              $("#_subtotal").empty();
                              $("#_subtotal_1").empty();
                              $('#_fk_producto').empty();
                              $("#productos").focus();
                         }
                    } else {
                         sumar()
                    }
               })
          return
     }

     function sumar() {
          const divlinea = document.getElementById('input_linea')
          const precioDescuento = divlinea.querySelector('#_descuento');
          let inputValue = $('#_descuento').val();
          let normalizedValue = parseFloat(inputValue.replace(',', '.'));
          precioDescuento.value = normalizedValue;
          var _nombre = $("#_nombre").val();
          var _cantidad = $("#_cantidad").val();
          var _precio_unitario = $("#_precio_unitario").val();
          var _impuesto = $("#_impuesto").val();
          if ($('#_recargo_equivalencia').is(':checked')) {
               var _recargo_equivalencia = 1;
          } else {
               var _recargo_equivalencia = 0;
          }
          if ($('#_retencion').is(':checked')) {
               var _retencion = 1;
          } else {
               var _retencion = 0;
          }
          var _fk_producto = $("#_fk_producto").val();
          var _tipo_servicio_producto = $("#_tipo_servicio_producto").val();
          var _descuento = (precioDescuento.value != 'NaN' ? precioDescuento.value : '0.00');
          var _descuento_tipo = $("#_descuento_tipo").val();
          var _total_linea = $("#_total_linea").val();

          //  $("#listado_factura").addClass("borroso");
          $("#listado_factura").html(`
               <div class="row" style="display: flex;justify-content: center;align-items: center;">
                    <img src="<?php echo ENLACE_WEB; ?>bootstrap/img/procesando.gif" width="5px" style="width: 200px;">
               </div>
          `);

          $("#_descuento").val('');
          $("#_nombre").val('');
          $("#_cantidad").val('');
          $("#_precio_unitario").val('');
          $("#_impuesto").val('');
          $("#productos").val('');
          $('#_fk_producto').val('');
          $("#_total_linea").val('');
          $("#code_cabys").val('');
          $("#cabys_code").text('');
          $("#cabys_tax").text('');
          $("#cabys_code").hide();
          $("#cabys_tax").hide();
          $("#trAddCabys").hide();

          // Si el cliente está calificado para compra
          const ruta_update = '<?php echo ENLACE_WEB . $Documento->ruta_detalle_contenido; ?>';
          $.post(ruta_update + "?fiche=<?php echo $Documento->id; ?>", {
                    nombre: _nombre,
                    cantidad: _cantidad,
                    precio_unitario: _precio_unitario,
                    impuesto: _impuesto,
                    fk_producto: _fk_producto,
                    descuento: _descuento,
                    descuento_tipo: _descuento_tipo,
                    tipo: _tipo_servicio_producto,
                    total_linea: _total_linea,
                    recargo_equivalencia: _recargo_equivalencia,
                    retencion: _retencion,
                    fk_tercero: $('#fk_tercero').val(),
                    forma_pago: $('#forma_pago').val(),
                    fk_proyecto: $('#fk_proyecto').val(),
                    fecha: $('#fecha').val(),
                    fecha_vencimiento: $('#fecha_vencimiento').val(),
                    serie_proveedor: $('#serie_proveedor').val() ?? '',
                    asesor_comercial_txt: $('#asesor_comercial_txt').val(),
                    tipo: '<?php echo $Documento->nombre_clase; ?>'
               })
               .done(function(response) {
                    if ("<?php echo $Documento->id; ?>" == '') {
                         const data = JSON.parse(response)
                         const html = data.html
                         const document_id = data.document_id
                         // actualiza_datos_cabecera_nueva_factura(document_id)
                         window.location.href = "<?php echo ENLACE_WEB; ?><?php echo strtolower($Documento->nombre_clase); ?>/" + document_id;
                    } else {
                         $("#listado_factura").empty();
                         $("#listado_factura").empty().html(response);

                         $("#listado_factura_totales").empty();

                         $.post("<?php echo ENLACE_WEB; ?>mod_documentos_mercantiles/ajax/documento_detalle_totales.ajax.php?fiche=<?php echo $Documento->id; ?>", {
                                   tipo: '<?php echo $Documento->nombre_clase; ?>'
                              })
                              .done(function(data_totales) {
                                   $("#listado_factura_totales").empty().html(data_totales);
                              });
                         let eventFired = new CustomEvent('document_modal_event', {
                              detail: 'sumar_finished'
                         });
                         window.dispatchEvent(eventFired);
                    }

               }).catch((error) => {
                    add_notification({
                         text: 'Error: Al agregar la línea',
                         actionTextColor: '#fff',
                         backgroundColor: '#e7515a',
                    });
               });

          console.log("Fin  del Proceso");
          i = '';
          newp = '';
          $("#_subtotal").empty();
          $("#_subtotal_1").empty();
          $('#_fk_producto').empty();
          $("#productos").focus();
     }

     function restar(_linea, id = 0) {
          const current_row = $(`tr[rowid="${id}"]`);
          const linea_nro = $(`tr[rowid="${id}"] td:first`).text().trim();
          if (current_row.length) {
               current_row.css('background-color', '#ffcccc!important');
          }

          const numFilas = $('.producto_item').length
          if (numFilas == 1) {
               Swal.fire({
                    title: 'No puede eliminar todas las filas.',
                    text: 'Por favor, elimina el documento',
                    icon: 'error'
               })
               current_row.css('background-color', 'unset');
               return;
          }
          Swal.fire({
               title: 'Confirmar borrado',
               html: `¿Seguro que deseas eliminar la línea <b>${linea_nro}</b> del documento: <b><?php echo $Documento->nombre_clase ?></b>?`,
               icon: 'warning',
               showCancelButton: true,
               confirmButtonText: 'Si, borrar',
               cancelButtonText: 'No, cancelar!',
               reverseButtons: true
          }).then((result) => {
               if (result.isConfirmed) {

                    $("#listado_factura").addClass("borroso");
                    const ruta_update = '<?php echo ENLACE_WEB . $Documento->ruta_detalle_contenido; ?>';
                    $.post(ruta_update + "?fiche=<?php echo $Documento->id; ?>", {
                              linea: _linea,
                              tipo: '<?php echo $Documento->nombre_clase; ?>'
                         })
                         .done(function(data) {
                              $("#listado_factura").empty().html(data);
                              $("#listado_factura").removeClass("borroso");

                              $("#listado_factura_totales").empty()
                              $.post("<?php echo ENLACE_WEB; ?>mod_documentos_mercantiles/ajax/documento_detalle_totales.ajax.php?fiche=<?php echo $Documento->id; ?>", {
                                        tipo: '<?php echo $Documento->nombre_clase; ?>'
                                   })
                                   .done(function(data) {
                                        $("#listado_factura_totales").empty().html(data);
                                   });
                              let eventFired = new CustomEvent('document_modal_event', {
                                   detail: 'restar_finished'
                              });
                              window.dispatchEvent(eventFired);
                         });
               } else if (result.dismiss === Swal.DismissReason.cancel) {

               }
               current_row.css('background-color', 'unset');
          })

     }

     function html_linea_editar() {
          const table_edit = `<tr id="input_linea_edit" valign="top" class="styled-row">
            <td><input type="hidden" id="_linea_edit" /></td>
            <td style="vertical-align:top; padding-left:0px;padding-right:0px;" colspan="5" width="50%">
               <strong>PRODUCTO</strong>
               <input type="hidden" id="_fk_producto_edit" disabled="">
               <textarea class="rezisable-item form-control" id="_nombre_edit" maxlength="160" style="width:100%" required=""></textarea>
            </td>
            <td style="vertical-align:top" width="15%">
               <strong>CANTIDAD</strong>
               <input type="text" id="_cantidad_edit" style="width:100%" required="" class="form-control">
            </td>
            <td style="vertical-align:top" width="15%">
               <strong>PRECIO</strong>
               <input class="form-control" type="text" id="_precio_unitario_edit" style="width:100%" required="">
            </td>
            <td style="vertical-align:top;">
               <strong>DESCUENTO</strong>
               <div style="display:flex">
                  <select id="_descuento_tipo_edit" style="padding:0;">
                     <option value="absoluto">Monto</option>
                     <option value="porcentual" selected="selected"> % </option>
                  </select>
                  <input class="form-control" style="width:100px" type="text" id="_descuento_edit">    
               </div>
            </td>
            <td style="vertical-align:top">
               <strong>IMPUESTO</strong><br/>
               <select id="_impuesto_edit" class="fixed-width" required="">
                  <?php
                    foreach ($Documento->diccionario_impuesto_iva() as $key =>  $impuestos_iva) {
                         echo "<option value='" . $impuestos_iva['impuesto'] . "'> " . $impuestos_iva['impuesto'] . " % - " . $impuestos_iva['impuesto_texto'] . "</option>";
                    }
                    ?>
               </select>
            </td>
            <td style="vertical-align:top">
               <strong>RE</strong><br/>
               <input type="checkbox" id="_recargo_equivalencia_edit">
            </td>
            <td style="vertical-align:top">
               <strong>RETENCIÓN</strong><br/>
               <input type="checkbox" id="_retencion_edit">
            </td>
            <td style="vertical-align:top" width="100%!important" class="tabla_sin_borde" valign="middle">                     
               <strong>ACCIONES</strong><br/>
               <button type="button" class="btn btn-primary" onclick="editar_grabar(true)">
                  <i class="fa fa-lg fa-save" aria-hidden="true"></i></button>
               <button type="button" class="btn btn-secondary" onclick="editar_grabar(false)">
               <i class="fa fa-lg fa-remove" aria-hidden="true"></i></button>
            </td>
         </tr>`
          return table_edit;
     }

     function editar_grabar(grabar) {
          let editRow = $('#input_linea_edit');
          if (grabar) {

               // Obtener todos los campos con el atributo 'required'
               const divlinea = document.getElementById('input_linea_edit')
               const camposRequeridos = divlinea.querySelectorAll('[required]');
               let camposValidos = true;

               camposRequeridos.forEach(campo => {
                    if (!campo.value.trim()) {
                         // Si el campo está vacío, añadir la clase is-invalid
                         campo.classList.add('input_error');
                         camposValidos = false;
                    } else {
                         if (campo.id == '_precio_unitario_edit') {
                              if (dejar_solo_numero(campo.value) == "") {
                                   campo.classList.add('input_error');
                                   camposValidos = false;
                              }
                         } else if (campo.id == '_cantidad_edit') {
                              if (dejar_solo_numero(campo.value) == "") {
                                   campo.classList.add('input_error');
                                   camposValidos = false;
                              }
                         } else {
                              campo.classList.remove('input_error');
                         }
                    }
               });

               if (!camposValidos) {
                    // Mostrar un mensaje si hay campos vacíos
                    alert("Campos no validos");
                    return;
               }

               //agrego el POST para grabar la línea  
               const data = {
                    fk_producto: $('#_fk_producto_edit').val(),
                    nombre: $('#_nombre_edit').val(),
                    cantidad: $('#_cantidad_edit').val(),
                    precio_unitario: $('#_precio_unitario_edit').val(),
                    impuesto: $('#_impuesto_edit').val(),
                    descuento: $('#_descuento_edit').val(),
                    descuento_tipo: $('#_descuento_tipo_edit').val(),
                    recargo_equivalencia: ($('#_recargo_equivalencia_edit').is(':checked') ? 1 : 0),
                    retencion: ($('#_retencion_edit').is(':checked') ? 1 : 0),
                    linea: $('#_linea_edit').val(),
                    tipo: 'Oportunidad'
               }
               const ruta_update = '<?php echo ENLACE_WEB . $Documento->ruta_detalle_contenido; ?>';

               $.post(ruta_update + "?fiche=<?php echo $Documento->id; ?>", data)
                    .done(function(response) {
                         $("#listado_factura").empty();
                         $("#listado_factura").empty().html(response);
                         $("#listado_factura_totales").empty()
                         $.post("<?php echo ENLACE_WEB; ?>mod_documentos_mercantiles/ajax/documento_detalle_totales.ajax.php?fiche=<?php echo $Documento->id; ?>", {
                                   tipo: '<?php echo $Documento->nombre_clase; ?>'
                              })
                              .done(function(data) {
                                   $("#listado_factura_totales").empty().html(data);
                              });

                         let eventFired = new CustomEvent('document_modal_event', {
                              detail: 'edit_finished'
                         });
                         window.dispatchEvent(eventFired);
                    })
                    .catch(function(response) {
                         const respJson = JSON.parse(response.responseText)
                         Swal.fire({
                              title: 'Validaciones de la línea de documento',
                              text: respJson.error,
                              icon: 'error'
                         })
                    })
          }
          let originalRow = editRow.data('originalRow');
          originalRow.show();
          $('#input_linea_edit').remove();
          $('.botones_accion').css('display', 'block')
     }

     function editar(element, rowidMd5) {

          let row = $(element).closest('tr');
          $('#div_linea_edit').html(html_linea_editar());
          let editRow = $('#input_linea_edit');

          if (!($(row).css('display') == 'none')) {
               let rowid = row.attr('rowid');
               row.hide();
               editRow.insertAfter(row).show();
               editRow.data('originalRow', row);
               $('#_nombre_edit').val($('#etiqueta_' + rowid).val())
               const id_Producto = document.getElementById('label_etiqueta_' + rowid).className.replace('producto_', '')
               $('#_linea_edit').val(rowidMd5);
               $('#_fk_producto_edit').val(id_Producto)
               $('#_cantidad_edit').val($('#cantidad_' + rowid).val())
               $('#_precio_unitario_edit').val($('#precio_' + rowid).val())
               $('#_impuesto_edit').val(($('#label_impuesto_' + rowid + '>small').text()).replace('%', '').replace('(', '').replace(')', '').trim())

               const importe_descuento_texto = $('#label_descuento_' + rowid + '>strong').text()
               const importe_descuento_porcentaje = importe_descuento_texto.includes('(')
               if (importe_descuento_porcentaje) {
                    $('#_descuento_tipo_edit').val('porcentual')
                    $('#_descuento_edit').val(parseFloat(importe_descuento_texto.split('(')[1].replace('%', '').replace('(', '').replace(')', '').trim()));
               } else {
                    $('#_descuento_tipo_edit').val('absoluto')
                    if (importe_descuento_texto != '-') {
                         $('#_descuento_edit').val(parseFloat(importe_descuento_texto.replace('€', '').trim()));
                    } else {
                         $('#_descuento_edit').val(0.00);
                    }
               }

               const item_equivalencia = $('#item_equivalencia_' + rowid + '>span').text()
               const item_equivalencia_porcentaje = parseFloat(item_equivalencia.replace('%', '').replace('(', '').replace(')', '').replace('.', ''))
               if (item_equivalencia_porcentaje) {
                    $('#_recargo_equivalencia_edit').attr('checked', 'checked')
               }
               const item_retencion = $('#item_retencion_' + rowid + '>span').text()
               const item_retencion_porcentaje = parseFloat(item_retencion.replace('%', '').replace('(', '').replace(')', '').replace('.', ''))
               if (item_retencion_porcentaje) {
                    $('#_retencion_edit').attr('checked', 'checked')
               }

               $('.botones_accion').css('display', 'none')

               /* AUTOCOMPLETE Para la Busqueda de Productos en EDIT */
               $("#_nombre_edit").autocomplete({
                    source: "<?php echo ENLACE_WEB; ?>mod_productos/json/productos.json.php?cliente=",
                    delay: 300,
                    minLength: 2,
                    search: function() {
                         // Muestra la animación de carga cuando inicia la búsqueda
                         $("#loading").empty().fadeOut();
                         $("#basic-addon1").empty().html(icono_buscando);
                         $("#basic-addon1").css("background-color", "");
                    },
                    response: function(event, ui) {
                         // Oculta la animación de carga cuando termina la búsqueda y aparecen los resultados
                         if (!ui.content || ui.content.length === 0) {
                              // Si no hay resultados, cambia al ícono de "sin resultados"
                              $("#loading").html('<i class="fas fa-exclamation-triangle"></i> No se encontraron resultados.').fadeIn();
                              $("#basic-addon1").empty().html(icono_no_encontrado);
                         } else {
                              // Oculta el ícono de carga cuando hay resultados
                              $("#loading").hide();
                              $("#basic-addon1").empty().html(icono_buscador);
                         }
                    },
                    select: function(event, ui) {
                         if (ui.item.impuesto_detalle > 0) {
                              $("#_impuesto_edit").val(ui.item.impuesto_detalle);
                         } else if (ui.item.impuesto > 0) {
                              $("#_impuesto_edit").val(ui.item.impuesto);
                         } else if (ui.item.impuesto_fk > 0) {
                              $("#_impuesto_edit").val(ui.item.impuesto_fk + '.00');
                         }
                         $('#_fk_producto_edit').val(ui.item.id)
                         $('#_nombre_edit').val(ui.item.value)
                         $('#_cantidad_edit').val(1)
                         $('#_precio_unitario_edit').val(ui.item.subtotal)
                    }
               });
               /* AUTOCOMPLETE Para la Busqueda de Productos en EDIT */

          }
     }

     function actualizar_nota(tipo) {
          $("#icono_" + tipo).attr('class', 'fa fa-fw fa-rotate-right fa-spin');
          $("#textarea_" + tipo).prop("disabled", true);

          nota = $("#textarea_" + tipo).val();

          $.ajax({
                    method: "POST",
                    url: "<?php echo ENLACE_WEB; ?>mod_documentos_mercantiles/ajax/guardar_cambio_x_ajax.php",
                    beforeSend: function(xhr) {
                         //    $("#lugar_campo_"+x.name).empty();
                         //    $("#lugar_campo_"+x.name).append('<img src="https://sistema-dev.avantecds.es/bootstrap/sistema/carga.gif" />');
                    },
                    data: {
                         documento: "<?php echo $Documento->id; ?>",
                         valor: nota,
                         tipo: tipo,
                         tipo_documento: '<?php echo $Documento->nombre_clase; ?>'
                    }
               })
               .done(function(json) {
                    if (json.error == 1) {
                         $("#icono_" + tipo).attr('class', 'fa fa-fw fa-warning');
                         console.log("Ha Ocurrido un Error", "error");
                         $("#textarea_" + tipo).prop("disabled", false);
                         $("#textarea_" + tipo).val(" ");
                         $("#alerta_" + tipo).empty().hide().html("<i style='color:orange;'> Ocurrio Un Error Por favor intentalo de nuevo</strong>").fadeIn();
                    } else {
                         $("#alerta_" + tipo).empty().hide();
                         $("#icono_" + tipo).attr('class', 'fa fa-fw fa-check-circle');
                         $("#textarea_" + tipo).prop("disabled", false);
                         $("#alerta_" + tipo).empty().hide().html("<i style='color:#00ab55;'> Notas actualizadas correctamente</strong>").fadeIn();
                    }
                    console.log(json.txt, "success");

               }).fail(function(jqxhr, textStatus, error) {
                    var err = textStatus + ", " + error;
                    console.log("Request Failed: " + err);
                    alert("Ocurrio Un Error - Intentalo de nuevo " + err);
                    $("#textarea_" + tipo).prop("disabled", false);
               });
     }



     /*function validar_documento() {
          let codigo_serie_selected = 9999
          let htmlSelect = '';
          $.ajax({
               type: 'POST',
               url: '<?php echo ENLACE_WEB; ?>mod_europa_facturacion_series/json/series_por_empresa.json.php',
               data:{ documento : '<?php echo $Documento->documento; ?>' },
               async: false,
               success: function(message) {
                    const data = JSON.parse(message);
                    if (data.length > 1) {
                         htmlSelect = '<select id="validationOption" class="form-control">';
                         data.forEach(item => {
                              htmlSelect += `<option value="${item.rowid}">${item.serie_por_defecto==1?'(*)':''} ${item.tipo_aeat} (${item.fk_serie_modelo}   ${item.serie_descripcion})</option>`;
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
               title: 'Fiscalizar documento',
               html: `
               ¿Deseas Validar la <?php echo $Documento->nombre_clase . " " . $Documento->referencia; ?> ?<br>
               ${ (codigo_serie_selected == 9999 ? 
               'Debe seleccionar una Serie del documento y continuar. <br>' + htmlSelect:  '') }
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
                    window.location.href = "<?php echo ENLACE_WEB; ?>dashboard.php?&accion=validar_documento&fiche=<?php echo $Documento->id; ?>&option_serie=" + codigo_serie_selected + "&option_bodega=" + codigo_bodega_selected;
               } else if (result.dismiss === Swal.DismissReason.cancel) {
                    // Acciones a realizar si se cancela
               }
          });

     }
    */
</script>