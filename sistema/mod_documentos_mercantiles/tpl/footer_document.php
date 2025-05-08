<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

<script id="script_documents">
     
     // Guardar la visibilidad de las columnas en localStorage
     function saveColumnVisibility(table) {
          var columnVisibility = [];
          table.columns().every(function(index) {
               columnVisibility.push(this.visible());
          });
          localStorage.setItem('columnVisibilityContacto', JSON.stringify(columnVisibility));
     }

     // Cargar la visibilidad de las columnas desde localStorage
     function loadColumnVisibility(table) {
          var columnVisibility = JSON.parse(localStorage.getItem('columnVisibilityContacto'));
          if (columnVisibility) {
               table.columns().every(function(index) {
                    this.visible(columnVisibility[index]);
               });
          }
     }

     function setting_table(vtabla, buttons, no_currency=false) {
          // Cargar configuración guardada de columnas
          loadColumnVisibility(vtabla);

          // Crear el icono de configuración dinámicamente
          var configIcon = $('<i class="fa fa-bars" style="cursor: pointer;margin-right: 10px;font-size: 30px;float-right;float: right;"></i>');

          let cleanFilter = $('<button>')
               .html('<i class="fas fa-eraser"></i>')
               .addClass('btn btn-warning m-2 float-right')
               .attr("type", "button")
               .attr("title", "Limpiar Filtros")
               .on('click', function() {      
                    let uri = window.location.toString();                 

                    $('#style-3').find('input[type="text"]').val('');
                    $('#style-3').find('select').val('');
                    $('#style-3').find('select').trigger('change');
                    vtabla.search('').columns().search('').draw();

                    if (uri.indexOf("?") > 0) {
                         var clean_uri = uri.substring(0, uri.indexOf("?"));
                         window.history.replaceState({}, document.title, clean_uri);
                         
                         window.location.reload();
                    }
                    if(localStorage.getItem('nombre_contacto') != null){
                              localStorage.removeItem('nombre_contacto');
                    }
                    /* en el payload se envia esto contacto_id con valor =  3 setealo null */
                    vtabla.ajax.reload();

                    
                   
               });

              
              
          // Crear el div que contendrá los checkboxes
          var columnVisibilityContainer = $('<div>')
               .attr('id', 'columnVisibilityContainer')
               .css({
                    display: 'none',
                    position: 'absolute',
                    right: '0',
                    backgroundColor: '#f9f9f9',
                    border: '1px solid #ccc',
                    padding: '10px',
                    zIndex: '1000',
               });

          // Crear checkboxes para cada columna dentro del div
          vtabla.columns().every(function(index) {
               var column = this;
               var checkbox = $('<input type="checkbox">')
                    .val(index)
                    .prop('checked', column.visible())
                    .on('change', function() {
                         var columnIndex = $(this).val();
                         var column = vtabla.column(columnIndex);
                         column.visible(!column.visible());
                         saveColumnVisibility(vtabla);
                    });

               var label = $('<label>')
                    .css('display', 'block')
                    .text($(column.header()).text())
                    .prepend(checkbox);

               columnVisibilityContainer.append(label);
          });

          
          // Añadir el icono y el div al DOM, justo antes de la tabla
          $('#'+vtabla.context[0].sTableId+'_wrapper').prepend(configIcon);
          $('#'+vtabla.context[0].sTableId+'_wrapper').prepend(columnVisibilityContainer);


          


          // Mostrar/ocultar el div cuando se hace clic en el icono de configuración
          configIcon.on('click', function() {
               columnVisibilityContainer.toggle();
          });

          // Crea un contenedor div para los botones
          var buttonContainer = $('<div>').attr("id", "export-buttons-container").addClass('ml-2');

          // Crea el botón de Excel con el icono de Font Awesome
          let excelButton = $('<button>')
               .html('<i class="fas fa-file-excel"></i> Exportar Excel')
               .addClass('btn btn-success')
               .attr("type", "button")
               .on('click', function() {
                    exportTableToExcel(vtabla.context[0].sTableId, vtabla.context[0].sTableId+'.xlsx');
               });

          // Crea el botón de PDF con el icono de Font Awesome
          let pdfButton = $('<button>')
               .html('<i class="fas fa-file-pdf"></i> Exportar PDF')
               .addClass('btn btn-danger')
               .attr("type", "button")
               .on('click', function() {
                    exportTableToPDF(vtabla.context[0].sTableId, vtabla.context[0].sTableId+'.pdf');
               });
          buttons.forEach(function(button) {
               // Agrega cada botón al contenedor
               buttonContainer.append(button);
          });
         
          buttonContainer.append(excelButton, pdfButton, cleanFilter);

          // Coloca el contenedor de botones en el lugar deseado dentro de la interfaz
          buttonContainer.appendTo("#"+vtabla.context[0].sTableId+"_length");

          // Función para limpiar los encabezados de la tabla
          function cleanTableForExport(tableID, no_currency) {
               // Clona la tabla para no modificar la original
               var tableClone = $(`#${tableID}`).clone();

               // Remueve inputs y selects de los encabezados
               // tableClone.find('th').each(function() {
               //     $(this).find('input, select').remove(); // Elimina inputs y selects
               // });
               tableClone.find('th').each(function(element) {
                    let titulo = $(this).find('input').attr('titulo')
                    if (titulo == undefined) {
                         titulo = $(this).find('select').attr('titulo')
                    }
                    if (titulo == undefined) {
                         titulo = $(this).find('#spinnerloading').attr('titulo')
                    }
                    if (titulo != '' && titulo != undefined && titulo.trim() != 'Loading...') {
                         $(this).html(titulo)
                    }
               });
               // tableClone.find('th').remove();

               tableClone.find('td[data-label="fecha"]').each(function(element) {
                    const split_text = $(this).html().split('-')

                    let dias_limpio = split_text[0]
                    const fecha = moment([split_text[2], split_text[1] - 1])
                    const dias_del_mes = fecha.daysInMonth();
                    const mes = fecha.format('MMMM')
                    // console.log(mes, dias_del_mes, split_text[0])
                    if (split_text[0] < dias_del_mes) {
                         dias_limpio = (parseInt(split_text[0]) + 1).toString().padStart(2, '0')
                         $(this).text(split_text[2] + '/' + split_text[1] + '/' + dias_limpio)
                    } else {
                         $(this).text(moment([split_text[2], split_text[1] - 1, fecha.daysInMonth()]).format('DD/MM/YYYY'))
                    }
               });

               tableClone.find('td').each(function(element) {
                    const label = $(this).data('label');
                    if (label != 'fecha') {
                         let contenido = $(this).text();
                         
                         if (/^\s*[€$£¥]?\s*[\d,]+\.\d{2}\s*$/.test(contenido)) {
                              if(no_currency){
                                   contenido = contenido.replace(/[^\d.-]/g, ''); // Elimina caracteres no numéricos
                                   const numero = parseFloat(contenido); // Convierte a número flotante
                                   $(this).text(numero); // Actualiza el texto de la celda
                              }
                         }
                         
                    }
               });

               return tableClone;
          }
          // Función para exportar a Excel usando SheetJS
          function exportTableToExcel(tableID, filename = '') {
               // Limpia la tabla antes de exportar
               var tableClone = cleanTableForExport(tableID, no_currency);
               // Convierte la tabla limpia a Excel
               var wb = XLSX.utils.table_to_book(tableClone.get(0), {
                    sheet: "Sheet1"
               });
               XLSX.writeFile(wb, filename);
          }
          // Función para exportar a PDF usando jsPDF
          function exportTableToPDF(tableID, filename = '') {
               // Limpia la tabla antes de exportar
               var tableClone = cleanTableForExport(tableID, no_currency);
               var {
                    jsPDF
               } = window.jspdf;
               var doc = new jsPDF();
               doc.autoTable({
                    html: tableClone.get(0)
               });
               doc.save(filename);
          }

          $('.dataTables_length').parent().removeClass('col-sm-6').addClass('col-sm-12');
          $('.dataTables_length').parent().attr("id", "datatable_header");
     }

     function config_datatable(ajax_option, options_array = []) {
          const options = {
               Processing: true,
               serverSide: true,
               bSort: false,
               pageLength: 50,
               order: [
                    [0, "desc"]
               ],
               select: {
                    style: 'multi'
               },
               dom: estiloPaginado.dom,
               oLanguage: estiloPaginado.oLanguage,
               stripeClasses: [],
               // ajax: {
               //      url: ajax_url,
               //      type: ajax_method
               // },
               retrieve: true,
               deferRender: true,
               scroller: true,
               responsive: true,
          }
          if(options_array.length>0){
               options_array.forEach(option_element => {
                    options[option_element.key] = option_element.value
               });
          }
          options.ajax = ajax_option
          return options;
     }

     function generarPdf(documentoId, documentoTipo, nombre_documento='', id_plantilla=0) {
          $.ajax({
                    method: "POST",
                    url: "<?php echo ENLACE_WEB; ?>mod_documentos_mercantiles/ajax/descargar_documento.ajax.php",
                    data: {
                         documento: documentoId,
                         tipo: documentoTipo,
                         id_plantilla : id_plantilla
                    },
                    beforeSend: function(xhr) {
                         // Opcional: mostrar un loader o mensaje
                    }
               })
               .done(function(msg) {
                    if (msg != '') {
                         const linkSource = `data:application/pdf;base64,${msg}`;
                         const downloadLink = document.createElement("a");
                         // const fileName = "PDF-<?php //echo $Documento->referencia; ?>.pdf";
                         const fileName = `${nombre_documento}.pdf`

                         downloadLink.href = linkSource;
                         downloadLink.download = fileName;
                         downloadLink.click();
                    } else {
                         add_notification({
                              text: 'Error: Documento no generado',
                              actionTextColor: '#fff',
                              backgroundColor: '#e7515a',
                         });
                    }
               });
     }

     function clonarFactura(documentoId, documentoTipo) {
          $.ajax({
                    method: "POST",
                    url: "<?php echo ENLACE_WEB; ?>mod_documentos_mercantiles/ajax/clonar_documento.ajax.php",
                    data: {
                       
                         documento: documentoId,
                         tipo: documentoTipo
                    },
                    beforeSend: function(xhr) {
                         // Opcional: mostrar un loader o mensaje
                    }
               })
               .done(function(msg) {

                    data = JSON.parse(msg);
                    console.log(data);

                    if (data.exito) {
                         add_notification({
                              text: 'Documento duplicado',
                              actionTextColor: '#fff',
                              backgroundColor: '#00ab55',
                              dismissText: 'Cerrar',
                              duration: 30000,
                         });

                          

                         if ("location" in data) {
                              window.location.href = "<?php echo ENLACE_WEB; ?>"+ data.location +"/" + data.id;
                         }



                    } else {
                         add_notification({
                              text: 'Error: Documento no generado',
                              actionTextColor: '#fff',
                              backgroundColor: '#e7515a',
                         });
                    }
               });
     }

     /* Valida si se cumplen los requisitos mínimos para una acción */
     // function validar_grabar_factura(minimos_borrador, documento) {
     //      const retorno = validar_requisito_minimo_borrador(minimos_borrador);
     //      if (retorno) {
     //           const existe_li_grabar = $('#dropdown_opciones_avanzadas').find('.dropdown_grabar_factura')
     //           if (existe_li_grabar.length == 0) {
     //                const item = `<li class="dropdown_grabar_factura">
     //                      <a class="dropdown-item" href="#" onclick="validar_documento()">
     //                         <i class="fa fa-fw fa-check-circle-o" aria-hidden="true"></i> ${documento=='Presupuesto'?'Convertir':'Fiscalizar'} ${documento}.
     //                      </a>
     //                   </li>`
     //                $('#dropdown_opciones_avanzadas').append(item)
     //           }
     //      } else {
     //           $('#dropdown_opciones_avanzadas .dropdown_grabar_factura').remove()
     //      }
     // }

     function validar_requisito_minimo_borrador(minimos_borrador) {
          let retorno = false;
          const array_minimos_borrador = minimos_borrador.split(',')
          array_minimos_borrador.forEach(element => {
               const elemento = $('#formulario_factura').find('.' + element)

               switch (element) {
                    case 'monto_total_unico':
                         if (parseFloat(elemento.attr('total')) > 0) {
                              retorno = true;
                         }
                         break;
                    default:
                         // code block
               }
          });
          return retorno;
     }

     function actualizar_detalle_documento(x, tipo_documento = 'facturas', id = 0) {

          x.classList.remove("valid");

          console.log(x.name);

          console.group("Actualizando Datos Detalle ");
          console.log(x);

          $('#value_coversion').val(x.value);
          selected = x.value;
          console.groupEnd();

          if (id == 0 || id == '') {
               return;
          }

          $.ajax({
                    method: "POST",
                    url: "<?php echo ENLACE_WEB; ?>mod_documentos_mercantiles/ajax/guardar_cambio_x_ajax.php",
                    beforeSend: function(xhr) {
                         //    $("#lugar_campo_"+x.name).empty();
                         //    $("#lugar_campo_"+x.name).append('<img src="https://sistema-dev.avantecds.es/bootstrap/sistema/carga.gif" />');
                    },
                    data: {
                         documento: id,
                         campo: x.name,
                         valor: x.value,
                         tipo: tipo_documento
                    }
               })
               .done(function(message) {
                    data = JSON.parse(message);


                    if (data.error == 0) {
                         x.classList.add("valid");

                         $("#lugar_campo_" + x.name).empty();
                         $("#lugar_campo_" + x.name).append(data.mensaje_txt);

                         $("#lugar_campo_" + x.name).find("select option").each(function() {

                              if ($(this).val() === selected) {
                                   $(this).attr("selected", "selected");
                              }
                         });

                         jQuery("input[name='fecha']").datepicker({
                              dateFormat: "yy-mm-dd"
                         });
                         jQuery("input[name='fecha_vencimiento']").datepicker({
                              dateFormat: "yy-mm-dd"
                         });

                        add_notification({
                             text: data.mensaje_txt_actualizado,
                             actionTextColor: '#fff',
                              backgroundColor: '#00ab55',
                        });


                   } else {
                        add_notification({
                             text: data.mensaje_txt_actualizado,
                             actionTextColor: '#fff',
                             backgroundColor: '#e7515a',
                        });
                   }
              });
    }



     function confirmar_eliminar_documento_mercantil(id, tipo = NULL, id_tabla) {

          

          if (tipo === null) {
               add_notification({
                    text: "Faltan Parametros",
                    actionTextColor: '#fff',
                    backgroundColor: '#00ab55',
               });
               return false;
          }


          if (tipo)
               Swal.fire({
                    title: 'Confirmar borrado',
                    text: "¿Seguro que deseas eliminar el Borrador?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si, borrar',
                    cancelButtonText: 'No, cancelar!',
                    reverseButtons: true
               }).then((result) => {
                    if (result.isConfirmed) {

                         return new Promise(resolve => {
                              const url = "<?php echo ENLACE_WEB; ?>mod_documentos_mercantiles/json/eliminar_documento.json.php";

                              const data = {
                                   documento_id: id,
                                   tipo: tipo
                              }
                              $.post(url, data)
                                   .done((respuesta) => {
                                        const response = JSON.parse(respuesta)
                                        console.log("Respuesta de Eliminar");
                                        console.log(respuesta);

                                        if (response.success) {
                                             add_notification({
                                                  text: 'Documento eliminado',
                                                  actionTextColor: '#fff',
                                                  backgroundColor: '#00ab55',
                                                  dismissText: 'Cerrar',
                                                  duration: 30000,
                                             });


                                             if (typeof id_tabla === "undefined") {
                                                  if ("location" in response) {
                                                       window.location.href = '<?php echo ENLACE_WEB; ?>' + response.location;
                                                  }
                                             }    else {
                                                   $(id_tabla).DataTable().ajax.reload();

                                             }






                                        } else {
                                             add_notification({
                                                  text: response.message,
                                                  actionTextColor: '#fff',
                                                  backgroundColor: '#e7515a',
                                             });
                                        }
                                   })
                                   .catch((error) => {
                                        add_notification({
                                             text: 'Error al eliminar el documento',
                                             actionTextColor: '#fff',
                                             backgroundColor: '#e7515a',
                                        });
                                   });

                         })

                    } else if (
                         /* Read more about handling dismissals below */
                         result.dismiss === Swal.DismissReason.cancel
                    ) {

                    }
               })


     }
</script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>