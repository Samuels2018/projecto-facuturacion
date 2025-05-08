<!-- Datatable -->
<style type="text/css">
  #style-3_filter{display: none !important;}
  #style-3_length{
        display: flex;
      }
      #export-buttons-container
      {
        margin-left: 25px;
      }
      #export-buttons-container button+button{
        margin-left: 15px;
      }
      #columnVisibilityContainer{
        margin-top: 40px !important;
    }
</style>
  <div class="middle-content container-xxl p-0">

      <!-- BREADCRUMB -->
      <div class="page-meta">
          <nav class="breadcrumb-style-one" aria-label="breadcrumb">
              <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Errores</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Listado Errores</li>
              </ol>
          </nav>
      </div>
      <!-- /BREADCRUMB -->


      <!-- CONTENT AREA -->
      <div class="row layout-top-spacing">

          <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
              <div class="row my-4">
                
              
              </div>
              <div class="widget-content widget-content-area br-8">

                  <form id="formulario">
                      <!-- Tabla  -->
                      <div class="table-responsive">
                          <table id="style-3" class="table style-3 dt-table-hover">
                              <thead>
                                  <tr>
                                  <th style="width: 10%;" scope="col" >Fecha</th>
                                      <th scope="col">Proceso</th>
                                      <th scope="col">Sql Consulta</th>
                                      <th scope="col">Error</th>
                                      <th style="width: 15%;" scope="col">File</th>
                                  </tr>
                              </thead>

                              <tbody id="tbody" role="alert" aria-live="polite" aria-relevant="all" id="tbody" style="font-size:small;">

                              </tbody>
                          </table>
                  </form>
              </div>

              <!--Fin Tabla  -->

          </div>
      </div>
  </div>
  <!-- CONTENT AREA -->
  </div>
  <!-- Scripts -->

  <!-- MODAL  -->
  <div class="modal fade" id="nuevo_modal" tabindex="-1" role="dialog" aria-labelledby="nuevo_modal_label" aria-hidden="true">
     <!-- MODAL  -->
    
  </div>
  <!-- Scripts -->





  <script>
      function cargar_tabla_crm() {
          vtabla = $('#style-3').DataTable({
              'Processing': true,
              'serverSide': true,
              "bSort": false,
              "pageLength": 20,
              "order": [
                  [0, "desc"]
              ],
              select: {
                  style: 'multi'
              },
              "dom": estiloPaginado.dom,
              "oLanguage": estiloPaginado.oLanguage,
              "stripeClasses": [],
              'ajax': {
                  'url': '<?php echo ENLACE_WEB_ERRORES; ?>mod_errores/ajax/listado_errores.ajax.php',
                  'Method': 'POST'
              },
              retrieve: true,
              deferRender: true,
              scroller: true,
              responsive: true,
              "initComplete": function() {
                  this.api().columns().every(function(col) {
                      var column = this;
                      var header = $(column.header());
                      var headerText = header.text(); // Guarda el texto original del encabezado
                      header.empty(); // Limpia el encabezado

                    
                    

                      // Crea un contenedor div para el texto del encabezado
                      var headerTextContainer = $('<div class="text-center" >').appendTo(header);
                      $('<span>').text(headerText).appendTo(headerTextContainer);

                      // Lista de índices de columnas donde NO quieres mostrar el input de búsqueda
                      var excludedColumns = [2,3,4,6, 7]; // Por ejemplo, para excluir las columnas 2, 4 y 6

                      // Crea un contenedor div para el input, independientemente de si está oculto o visible
                      var inputContainer = $('<div>').css({
                          'width': '100%', // Asegura que el contenedor ocupe todo el espacio disponible
                          'height': '100%', // Asegura que el contenedor ocupe todo el espacio disponible
                          'position': 'relative' // Posiciona el contenedor de manera relativa para que el input se posicione correctamente dentro de él
                      }).appendTo(header);

                      // Verifica si la columna actual está en la lista de excluidas
                      if ($.inArray(col, excludedColumns) === -1) {
                          // Para los campos de texto
                          var input = $('<input type="text" class="form-control">')
                              .appendTo(inputContainer)
                              .on('input', function() { // Cambiado de 'change' a 'input'
                                  var val = $.fn.dataTable.util.escapeRegex(
                                      $(this).val()
                                  );
                                  column
                                      .search(val ? '^' + val + '$' : '', true, false)
                                      .draw();
                              });
                      } else {
                          // Para las columnas excluidas, agrega un div vacío para reservar el espacio
                         /* $('<div>').css({
                              'width': '100%',
                              'height': '30px', // Aumenta el tamaño del div vacío
                              'visibility': 'hidden' // Oculta el div pero mantiene su espacio
                          }).appendTo(inputContainer);*/

                          if (column.dataSrc() === 'Estatus') {
                            let select = generarSelectEstatus('estatus');
                    
                            $(select).appendTo(header).on('change', function() {
                                var val = $(this).val();
 								//Esto es por que el BORRADO es al reves
                                /*if(val === 1)
                                {
                                	val = "0";
                                }else{
                                	val = "1";
                                }*/
                                column.search(val ? '^' + val + '$' : '', true, false).draw();
                            });
                          }
                        
                      }
                  });
              },
              'columns': [{
                      data: 'fecha',
                      searchable: true,
                      createdCell: function(td, cellData, rowData, row, col) {
                          $(td).addClass('text-center').attr('data-label', 'fecha');
                      },
                       render: function(data, type, row) {
                         // return `<a href="${ENLACE_WEB}/banco_editar/${row.ID}">${row.ID}</a>`
                          return `<a onclick="ver_modal(${row.ID});" href="#">${data}</a>`
                        
                      }
                  },
                  {
                      data: 'proceso',
                      searchable: true,
                      createdCell: function(td, cellData, rowData, row, col) {
                          $(td).addClass('text-left').attr('data-label', 'proceso');
                      },
                      render: function(data, type, row) {
                        //  return `<a href="${ENLACE_WEB}/banco_editar/${row.ID}">${data}</a>`
                        return `<a onclick="ver_modal(${row.ID});" href="#">${data}</a>`
                        
                      }

                  },

                  {
                      data: 'sql_consulta',
                      searchable: true,
                      createdCell: function(td, cellData, rowData, row, col) {
                          $(td).addClass('text-left').attr('data-label', 'sql_consulta');
                      },
                      render: function(data, type, row) {
                        //  return `<a href="${ENLACE_WEB}/banco_editar/${row.ID}">${data}</a>`
                        return `<a onclick="ver_modal(${row.ID});" href="#">${data}</a>`
                        
                      }

                  },

                  {
                      data: 'error',
                      searchable: true,
                      createdCell: function(td, cellData, rowData, row, col) {
                          $(td).addClass('text-left').attr('data-label', 'error');
                      },
                      render: function(data, type, row) {
                        //  return `<a href="${ENLACE_WEB}/banco_editar/${row.ID}">${data}</a>`
                        return `<a onclick="ver_modal(${row.ID});" href="#">${data}</a>`
                        
                      }

                  },

                  
                  {
                      data: 'file',
                      searchable: true,
                      createdCell: function(td, cellData, rowData, row, col) {
                          $(td).addClass('text-left').attr('data-label', 'file');
                      },
                      render: function(data, type, row) {
                        //  return `<a href="${ENLACE_WEB}/banco_editar/${row.ID}">${data}</a>`
                        return `<a onclick="ver_modal(${row.ID});" href="#">${data}</a>`
                        
                      }

                  },

                  
                 
              ]
          });

              
         // Cargar configuración guardada de columnas
        loadColumnVisibility(vtabla);

        // Crear el icono de configuración dinámicamente
        var configIcon = $('<i class="fa fa-bars" style="cursor: pointer;margin-right: 10px;font-size: 30px;float-right;float: right;"></i>');

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
        $('#style-3_wrapper').prepend(configIcon);
        $('#style-3_wrapper').prepend(columnVisibilityContainer);

        // Mostrar/ocultar el div cuando se hace clic en el icono de configuración
        configIcon.on('click', function() {
            columnVisibilityContainer.toggle();
        });      
      


    }


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

  </script>

    <script>
      $(document).ready(function() {

          cargar_tabla_crm();

             // Desactivar todos los elementos del menú
             $(".menu").removeClass('active');

            $(".configuracion_general").addClass('active');
            $(".configuracion_general > .submenu").addClass('show');
            $("#bancos_listado").addClass('active');
        


          
        // Crea un contenedor div para los botones
        var buttonContainer = $('<div>').attr("id", "export-buttons-container").addClass('ml-2');

        // Crea el botón de Excel con el icono de Font Awesome
          var excelButton = $('<button>')
              .html('<i class="fas fa-file-excel"></i> Exportar Excel')
              .addClass('btn btn-success')
              .attr("type","button")
              .on('click', function() {
                  exportTableToExcel('style-3', 'table_export.xlsx');
              });
  
          // Crea el botón de PDF con el icono de Font Awesome
          var pdfButton = $('<button>')
              .html('<i class="fas fa-file-pdf"></i> Exportar PDF')
              .addClass('btn btn-danger')
              .attr("type","button")
              .on('click', function() {
                  exportTableToPDF('style-3', 'table_export.pdf');
              });

          // Agrega los botones al contenedor
          buttonContainer.append(excelButton);

          // Coloca el contenedor de botones en el lugar deseado dentro de la interfaz
          buttonContainer.appendTo("#style-3_length");


          // Función para limpiar los encabezados de la tabla
          function cleanTableForExport(tableID) {
              // Clona la tabla para no modificar la original
              var tableClone = $(`#${tableID}`).clone();

              // Remueve inputs y selects de los encabezados
              tableClone.find('th').each(function() {
                  $(this).find('input, select').remove(); // Elimina inputs y selects
              });

              return tableClone;
          }
          // Función para exportar a Excel usando SheetJS
          function exportTableToExcel(tableID, filename = ''){
              // Limpia la tabla antes de exportar
              var tableClone = cleanTableForExport(tableID);
              // Convierte la tabla limpia a Excel
              var wb = XLSX.utils.table_to_book(tableClone.get(0), {sheet:"Sheet1"});
              XLSX.writeFile(wb, filename);
          }
          // Función para exportar a PDF usando jsPDF
          function exportTableToPDF(tableID, filename = ''){
              // Limpia la tabla antes de exportar
              var tableClone = cleanTableForExport(tableID);
              var { jsPDF } = window.jspdf;
              var doc = new jsPDF();
              doc.autoTable({ html: tableClone.get(0) });
              doc.save(filename);
          }

      });
  </script>

<script type="text/javascript">
        

       function ver_modal(int = null) {

          // Preparar la petición AJAX
          $.ajax({
              method: "POST",
              url: "<?php echo ENLACE_WEB; ?>mod_bancos/tpl/modal_bancos.php",
              beforeSend: function(xhr) {
                  // aqui deberia ocurrir una carga
              },
              data: {
                  action: 'ver_modal',
                  fiche: int,
              },
          }).done(function(html) {
              //print html en el modal cargado
              $("#nuevo_modal").html(html).modal('show');
          });

      }



    function crearBanco(event) {
        event.preventDefault(); 
        
        let error = false;

        // Eliminar la clase de error de los campos antes de validar
        $('#nombre_banco').removeClass("input_error");
     
        // Recoger los valores del formulario usando jQuery
        const nombre_banco = $('#nombre_banco').val();
   
        // Validar que los campos no estén vacíos y añadir la clase de error si es necesario
        if (nombre_banco == '') { 
            $('#nombre_banco').addClass("input_error"); 
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

        // Preparar la petición AJAX
        $.ajax({
            method: "POST",
            url: "<?php echo ENLACE_WEB; ?>mod_bancos/class/clases.php",
            beforeSend: function(xhr) {

            },
            data: {
                action: 'crearBanco',
                nombre_banco: nombre_banco,
            },
        }).done(function(msg) {
        //    console.log("Actualizando");
            console.log(msg);

            var mensaje = jQuery.parseJSON(msg);

            if (mensaje.exito ==1 ) {
                        add_notification({
                                text: 'Banco creado exitosamente',
                                actionTextColor: '#fff',
                                backgroundColor: '#00ab55',
                                dismissText: 'Cerrar'
                         });
                    // window.location.href = "<?php echo ENLACE_WEB; ?>bancos_listado/";
                  $("#nuevo_modal").modal('hide');
                    $('#style-3').DataTable().ajax.reload();

            } else {
               
                        add_notification({
                        text: "Error:"+mensaje.mensaje,
                        actionTextColor: '#fff',
                        actionTextColor: '#fff',
                backgroundColor: '#e7515a' ,
                        });
            }
        });
    }


    function actualizarBanco(id) {
        //event.preventDefault();
        
        let error = false;

        // Eliminar la clase de error de los campos antes de validar
        $('#nombre_banco').removeClass("input_error");
     
        // Recoger los valores del formulario usando jQuery
        const nombre_banco = $('#nombre_banco').val();
        const estado_banco = $('#estado_banco').val();


        // Validar que los campos no estén vacíos y añadir la clase de error si es necesario
        if (nombre_banco == '') { 
            $('#nombre_banco').addClass("input_error"); 
            error = true;  
        }

        if(estado_banco == '')
        {
          $("#estado_banco").addClass('input_error');
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

        // Preparar la petición AJAX
        $.ajax({
            method: "POST",
            url: "<?php echo ENLACE_WEB; ?>mod_bancos/class/clases.php",
            beforeSend: function(xhr) {

            },
            data: {
                action: 'actualizarBanco',
                nombre_banco: nombre_banco,
                id: id,
                estado_banco:estado_banco,
            },
        }).done(function(msg) {
        //    console.log("Actualizando");
            console.log(msg);

            var mensaje = jQuery.parseJSON(msg);

            if (mensaje.exito ==1 ) {
                        add_notification({
                                text: 'Banco actualizado exitosamente',
                                actionTextColor: '#fff',
                                backgroundColor: '#00ab55',
                                dismissText: 'Cerrar'
                         });
                     //window.location.href = "<?php echo ENLACE_WEB; ?>bancos_listado/";
                    $("#nuevo_modal").modal('hide');
                    $('#style-3').DataTable().ajax.reload();

            } else {
               
                        add_notification({
                        text: "Error:"+mensaje.mensaje,
                        actionTextColor: '#fff',
                        actionTextColor: '#fff',
                backgroundColor: '#e7515a' ,
                        });
            }
        });
    }

     // FUNCTION DELETE INFO PRODUCT
    function confirma_eliminar($id) {
        // Preparar el mensaje para el snackbar
        var message = "Está seguro(a) que desea eliminar el Banco? ";
        var actionText = "Confirmar";

        // Mostrar el snackbar y definir el callback para el botón de acción
        add_notification({
            text: message,
            width: 'auto',
            duration: 30000,
            actionText: actionText,
            dismissText: 'Cerrar',
            onActionClick: function(element)
            {
                // Aquí va el código que se ejecutará cuando el usuario confirme
                $.ajax({
                    method: "POST",
                    url: "<?php echo ENLACE_WEB; ?>mod_bancos/class/clases.php",
                    beforeSend: function(xhr) {},
                    data: {
                        action: 'eliminarBanco',
                        id: $id
                    },
                }).done(function(msg) {
                    console.log(msg);

                    var data = JSON.parse(msg);
                    // VALID RESULT
                    if (data.exito == 1) {
                        add_notification({
                            text: 'Banco Eliminado exitosamente',
                            pos: 'top-right',
                            actionTextColor: '#fff',
                            backgroundColor: '#00ab55'
                        });
                        //window.location.href = "<?php echo ENLACE_WEB; ?>bancos_listado/";
                        $("#nuevo_modal").modal('hide');
                        $('#style-3').DataTable().ajax.reload();

                        
                    }
                });
            }
        });
    }

     function confirma_activar($id) {
        // Preparar el mensaje para el snackbar
        var message = "Está seguro(a) que desea activar el banco? ";
        var actionText = "Confirmar";

        // Mostrar el snackbar y definir el callback para el botón de acción
        add_notification({
            text: message,
            width: 'auto',
            duration: 30000,
            actionText: actionText,
            dismissText: 'Cerrar',
            onActionClick: function(element)
            {
                // Aquí va el código que se ejecutará cuando el usuario confirme
                $.ajax({
                    method: "POST",
                    url: "<?php echo ENLACE_WEB; ?>mod_bancos/class/clases.php",
                    beforeSend: function(xhr) {},
                    data: {
                        action: 'activarBanco',
                        id: $id
                    },
                }).done(function(msg) {
                    console.log(msg);

                    var data = JSON.parse(msg);
                    // VALID RESULT
                    if (data.exito == 1) {
                        add_notification({
                            text: 'Banco Activado exitosamente',
                            pos: 'top-right',
                            actionTextColor: '#fff',
                            backgroundColor: '#00ab55'
                        });
                       // window.location.href = "<?php echo ENLACE_WEB; ?>bancos_listado/";
                        $("#nuevo_modal").modal('hide');
                        $('#style-3').DataTable().ajax.reload();
                        
                    }
                });
            }
        });
    }
</script>