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
                  <li class="breadcrumb-item"><a href="#">Agentes</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Listado</li>
              </ol>
          </nav>
      </div>
      <!-- /BREADCRUMB -->

      <!-- CONTENT AREA -->
      <div class="row layout-top-spacing">

          <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
              <div class="widget-content searchable-container list">
              </div>
              <div class="widget-content widget-content-area br-8">

                  <form id="formulario">
                      <!-- Tabla  -->
                      <div class="table-responsive">
                          <table id="style-3" class="table style-3 dt-table-hover">
                              <thead>
                                  <tr>
                                      <th scope="col">Nombre</th>
                                      <th scope="col">Email</th>
                                      <th scope="col">Meta</th>
                                      <th scope="col">Comisión</th>
                                      <th scope="col">Móvil</th>
                                      <th scope="col">Teléfono</th>
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


  <script>
      $(document).ready(function() {

          cargar_tabla_agentes();

          // Desactivar todos los elementos del menú
          $(".menu").removeClass('active');

          $(".configuracion").addClass('active');
          $(".configuracion > .submenu").addClass('show');
          $("#agentes_listado").addClass('active');

          // Crea un contenedor div para los botones
          var buttonContainer = $('<div>').attr("id", "export-buttons-container").addClass('ml-2');

          // Crea el botón de Excel con el icono de Font Awesome
          let newButton = $('<button>')
              .html('<i class="fa-solid fa-plus"></i> Nuevo agente')
              .addClass('btn btn-primary')
              .attr("type", "button")
              .on('click', function() {
                  window.location = '<?= ENLACE_WEB ?>agentes_nuevo'
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
              .html('<i class="fas fa-file-pdf"></i> Exportar PDF')
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

  <script>
      function cargar_tabla_agentes() {
          // Desactivar todos los elementos del menú
          $(".menu").removeClass('active');
          $(".productos").addClass('active');

          var vtabla = $('#style-3').DataTable({
              'processing': true,
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
                  'url': '<?php echo ENLACE_WEB; ?>mod_configuracion_agente/ajax/listado_agentes.ajax.php'
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
                      var excludedColumns = [6]; // Por ejemplo, para excluir las columnas 2, 4 y 6

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
              },
              columns: [
                  {
                      data: 'nombre',
                      searchable: true,
                      createdCell: function(td, cellData, rowData, row, col) {
                          $(td).attr('data-label', 'nombre');
                      },
                      render: function(data, type, row) {
                          return `
                        <a href="${ENLACE_WEB}agentes_editar/${row.ID}">
                            <i class="fa-solid fa-user"></i>
                            ${row.nombre}
                        </a>
                        `;

                      }
                  },
                  {
                      data: 'email',
                      searchable: true,
                      createdCell: function(td, cellData, rowData, row, col) {
                          $(td).attr('data-label', 'email');
                      }
                  },
                  {
                      data: 'meta',
                      searchable: true,
                      createdCell: function(td, cellData, rowData, row, col) {
                          $(td).attr('data-label', 'meta');
                      }
                  },
                  {
                      data: 'comision',
                      searchable: true,
                      createdCell: function(td, cellData, rowData, row, col) {
                          $(td).attr('data-label', 'comision');
                      }
                  },
                  {
                      data: 'movil',
                      searchable: true,
                      createdCell: function(td, cellData, rowData, row, col) {
                          $(td).attr('data-label', 'movil');
                      }
                  },
                  {
                      data: 'telefono',
                      searchable: true,
                      createdCell: function(td, cellData, rowData, row, col) {
                          $(td).attr('data-label', 'telefono');
                      }
                  },
                  {
                      data: 'activo',
                      searchable: true,
                      createdCell: function(td, cellData, rowData, row, col) {
                          $(td).addClass('text-center').attr('data-label', 'activo');
                      },
                      render: function(data, type, row) {
                          if (data == 1) {
                              return '<span class="shadow-none badge badge-primary">Activo</span>';
                          } else if (data != 1) {
                              return '<span class="shadow-none badge badge-danger">Inactivo</span>';
                          }
                      }
                  }
                  // Aquí puedes agregar más columnas según sea necesario
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