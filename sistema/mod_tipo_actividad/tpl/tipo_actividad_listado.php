  <!-- Datatable -->
  <style type="text/css">
      #style-3_filter {
          display: none !important;
      }

      #style-3_length {
          display: flex;
      }

      #export-buttons-container {
          margin-left: 25px;
      }

      #export-buttons-container button+button {
          margin-left: 15px;
      }

      #columnVisibilityContainer {
          margin-top: 40px !important;
      }
  </style>
  <div class="middle-content container-xxl p-0">

      <!-- BREADCRUMB -->
      <div class="page-meta">
          <nav class="breadcrumb-style-one" aria-label="breadcrumb">
              <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Tipo Actividad</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Listado</li>
              </ol>
          </nav>
      </div>
      <!-- /BREADCRUMB -->


      <!-- CONTENT AREA -->
      <div class="row layout-top-spacing">

          <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
              <div class="widget-content widget-content-area br-8">

                  <form id="formulario">
                      <!-- Tabla  -->
                      <div class="table-responsive">
                          <table id="style-3" class="table style-3 dt-table-hover">
                              <thead>
                                  <tr>
                                      <th scope="col">Nombre</th>
                                      <th style="width: 15%;" scope="col">Color</th>
                                      <th style="width: 15%;" scope="col">Ícono</th>
                                      <th style="width: 15%;" scope="col">Estado</th>
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

  <!-- MODAL  -->
  <div class="modal fade" id="nuevo_tipo_actividad" tabindex="-1" role="dialog" aria-labelledby="nuevo_tipo_actividad_label" aria-hidden="true">
      <!-- MODAL  -->

  </div>
  <!-- Scripts -->

  <script src="https://cdn.jsdelivr.net/npm/@simonwep/pickr@1.8.2/dist/pickr.min.js"></script>

  <script>
      function cargar_tabla_tipo_actividad() {
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
                  'url': '<?php echo ENLACE_WEB; ?>mod_tipo_actividad/ajax/listado_tipo_actividad_ajax.php',
                  'Method': 'POST'
              },
              retrieve: true,
              deferRender: true,
              scroller: true,
              responsive: true,
              initComplete: function() {
                  this.api().columns().every(function(col) {
                      var column = this;
                      var header = $(column.header());
                      var headerText = header.text(); // Guarda el texto original del encabezado
                      header.empty(); // Limpia el encabezado
                      // Crea un contenedor div para el texto del encabezado
                      var headerTextContainer = $('<div class="text-center" >').appendTo(header);
                      $('<span>').text(headerText).appendTo(headerTextContainer);

                      // Lista de índices de columnas donde NO quieres mostrar el input de búsqueda
                      var excludedColumns = [2, 3]; // Por ejemplo, para excluir las columnas 2, 4 y 6

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
                          if (column.dataSrc() === 'activo') {
                              let select = generarSelectEstatus('activo');

                              $(select).appendTo(header).on('change', function() {
                                  var val = ($(this).val());

                                  column.search(val ? '^' + val + '$' : '', true, false).draw();
                              });
                          }

                      }
                  });
                  $('.dataTables_length').parent().removeClass('col-sm-6').addClass('col-sm-12');
              },
              columns: [{
                      data: 'nombre',
                      searchable: true,
                      createdCell: function(td, cellData, rowData, row, col) {
                          $(td).addClass('text-left').attr('data-label', 'nombre');
                      },
                      render: function(data, type, row) {
                          return `<a onclick="ver_tipo_actividad(${row.ID});" href="#">${data}</a>`
                      }

                  },
                  {
                      data: 'color',
                      searchable: true,
                      createdCell: function(td, cellData, rowData, row, col) {
                          $(td).addClass('text-center').attr('data-label', 'color');
                      },
                      render: function(data, type, row) {

                          return `
                            <span>
                                <a onclick="ver_tipo_actividad(${row.ID});" href="#">${data}</a>
                            </span>
                        `;
                      }
                  },
                  {
                      data: 'icono',
                      createdCell: function(td, cellData, rowData, row, col) {
                          $(td).addClass('text-center').attr('data-label', 'icono');
                      },
                      render: function(data, type, row) {
                          return `
                            <a onclick="ver_tipo_actividad(${row.ID});" href="#">
                                <i class="fa-solid fa-lg ${row.icono}"></i>
                            </a>
                        `;
                      }
                  },
                  {
                      data: 'activo',
                      searchable: true,
                      createdCell: function(td, cellData, rowData, row, col) {
                          $(td).addClass('text-center').attr('data-label', 'activo');
                      },
                      render: function(data, type, row) {
                          // Verificar si bodega_defecto es 1
                          let badge = '';
                          if (row.activo == 1) {
                              badge = `
                                <span class="badge badge-success" style="font-size: 0.75rem; margin-left: 5px;">
                                    <i class="fas fa-warehouse" style="margin-right: 3px;"></i>Habilitada
                                </span>`;
                          } else {
                              badge = `
                                <span class="badge badge-danger" style="font-size: 0.75rem; margin-left: 5px;">
                                    <i class="fas fa-warehouse" style="margin-right: 3px;"></i>Inhabilitada
                                </span>`;
                          }

                          return `
                            <span>
                                <a onclick="ver_tipo_actividad(${row.ID});" href="#"> ${badge}</a>
                            </span>
                        `;
                      }
                  }
              ],
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

          cargar_tabla_tipo_actividad();


          // Desactivar todos los elementos del menú
          $(".menu").removeClass('active');

          $(".configuracion_general").addClass('active');
          $(".configuracion_general > .submenu").addClass('show');





          // Crea un contenedor div para los botones
          var buttonContainer = $('<div>').attr("id", "export-buttons-container").addClass('ml-2');
          // Crea el botón de Excel con el icono de Font Awesome
          let newButton = $('<button>')
              .html('<i class="fa-solid fa-plus"></i> Nuevo Tipo Actividad')
              .addClass('btn btn-primary')
              .attr("type", "button")
              .on('click', function() {
                  ver_tipo_actividad()
              });
          // Crea el botón de Excel con el icono de Font Awesome
          var excelButton = $('<button>')
              .html('<i class="fas fa-file-excel"></i> Exportar Excel')
              .addClass('btn btn-success')
              .attr("type", "button")
              .on('click', function() {
                  exportTableToExcel('style-3', 'table_export.xlsx');
              });

          // Crea el botón de PDF con el icono de Font Awesome
          var pdfButton = $('<button>')
              .html('<i class="fas fa-file-pdf"></i> Generar PDF')
              .addClass('btn btn-danger')
              .attr("type", "button")
              .on('click', function() {
                  exportTableToPDF('style-3', 'table_export.pdf');
              });

          // Agrega los botones al contenedor
          buttonContainer.append(newButton, excelButton, pdfButton);

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
          function exportTableToExcel(tableID, filename = '') {
              // Limpia la tabla antes de exportar
              var tableClone = cleanTableForExport(tableID);
              // Convierte la tabla limpia a Excel
              var wb = XLSX.utils.table_to_book(tableClone.get(0), {
                  sheet: "Sheet1"
              });
              XLSX.writeFile(wb, filename);
          }
          // Función para exportar a PDF usando jsPDF
          function exportTableToPDF(tableID, filename = '') {
              // Limpia la tabla antes de exportar
              var tableClone = cleanTableForExport(tableID);
              var {
                  jsPDF
              } = window.jspdf;
              var doc = new jsPDF();
              doc.autoTable({
                  html: tableClone.get(0)
              });
              doc.save(filename);
          }

      });
  </script>

  <script type="text/javascript">
      function guardar(event) {

          event.preventDefault();

          let error = false;

          /* Valida los inputs requeridos */
          const inputTypes = [];
          $('.modal-dialog input[name][id]').each(function(index, element) {
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

          const id = $("#modal_fiche").val()
          const data = {
                action: 'crear_tipo_actividad',
                nombre: $('#modal_nombre').val(),
                activo: $('#modal_activo').val(),
                icono: $("#modal_icono").val(),
                color: ($("#modal_color").val() != ''? $("#modal_color").val():'#ffffff'),
                id: id
            }
          // Preparar la petición AJAX
          $.ajax({
              method: "POST",
              url: "<?php echo ENLACE_WEB; ?>mod_tipo_actividad/ajax/tipo_actividad_ajax.php",
              beforeSend: function(xhr) {
                  // Puedes mostrar un loader aquí si lo deseas
              },
              data: data,
          }).done(function(msg) {
              var mensaje = jQuery.parseJSON(msg);

              if (mensaje.exito == 1) {
                  $("#nuevo_tipo_actividad").modal('hide');
                  $('#style-3').DataTable().ajax.reload();

                  add_notification({
                      text: 'Tipo actividad creada exitosamente',
                      actionTextColor: '#fff',
                      backgroundColor: '#00ab55',
                      dismissText: 'Cerrar'
                  });

              } else {
                  add_notification({
                      text: "Error: " + mensaje.mensaje,
                      actionTextColor: '#fff',
                      backgroundColor: '#e7515a',
                  });
              }
          });
      }

      function ver_tipo_actividad(int = null) {
          // Preparar la petición AJAX
          $.ajax({
              method: "POST",
              url: "<?php echo ENLACE_WEB; ?>mod_tipo_actividad/tpl/modal_tipo_actividad.php",
              beforeSend: function(xhr) {
                  // aqui deberia ocurrir una carga
              },
              data: {
                  action: 'ver_tipo_actividad',
                  fiche: int,
              },
          }).done(function(html) {
              //print html en el modal cargado
              $("#nuevo_tipo_actividad").html(html).modal('show');
          });

      }



      // FUNCTION DELETE INFO PRODUCT
      function borrar($id) {
          // Preparar el mensaje para el snackbar
          var message = "Está seguro(a) que desea eliminar el Tipo de Actividad? ";
          var actionText = "Confirmar";

          // Mostrar el snackbar y definir el callback para el botón de acción
          add_notification({
              text: message,
              width: 'auto',
              duration: 30000,
              actionText: actionText,
              dismissText: 'Cerrar',
              onActionClick: function(element) {
                  // Aquí va el código que se ejecutará cuando el usuario confirme
                  debugger
                  $.ajax({
                      method: "POST",
                      url: "<?php echo ENLACE_WEB; ?>mod_tipo_actividad/ajax/tipo_actividad_ajax.php",
                      beforeSend: function(xhr) {},
                      data: {
                          action: 'borrar_tipo_actividad',
                          id: $id
                      },
                  }).done(function(msg) {
                      console.log(msg);
                      var data = JSON.parse(msg);
                      // VALID RESULT
                      if (data.exito == 1) {
                          add_notification({
                              text: 'Tipo Actividad eliminado exitosamente',
                              pos: 'top-right',
                              actionTextColor: '#fff',
                              backgroundColor: '#00ab55'
                          });
                          $("#nuevo_tipo_actividad").modal('hide');

                          $('#style-3').DataTable().ajax.reload();
                      }
                  });
              }
          });
      }


      function ocultar_snackbar() {
          $(".snackbar-container").fadeOut(500); // 500 milisegundos para la animación

          document.getElementById('agregar_bodega').disabled = false;
          document.getElementById('borrar_bodega').disabled = false;
      }

      // Escuchar el evento de ocultar el modal con id 'nueva_diccionario_categoria'
      document.getElementById('nuevo_tipo_actividad').addEventListener('hidden.bs.modal', function() {
          // Llamar a la función ocultar_snackbar cuando el modal se oculte
          ocultar_snackbar();
      });
  </script>