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
<?php include_once(ENLACE_SERVIDOR . "mod_documentos_mercantiles/tpl/header_document.php"); ?>
  <div class="middle-content container-xxl p-0">

      <!-- BREADCRUMB -->
      <div class="page-meta">
          <nav class="breadcrumb-style-one" aria-label="breadcrumb">
              <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Categorias Producto</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Listadoaasd</li>
              </ol>
          </nav>
      </div>
      <!-- /BREADCRUMB -->


      <!-- CONTENT AREA -->
      <div class="row layout-top-spacing">

          <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
              <div class="row my-4">
                 <!--  <div class="col-md-3">
                      <a href="<?=ENLACE_WEB?>categoria_producto_crear/" class="btn btn-primary">Nueva Categoria</a>
                  </div> -->
              
              </div>
              <div class="widget-content widget-content-area br-8">

                  <form id="formulario">
                      <!-- Tabla  -->
                      <div class="table-responsive">
                          <table id="style-3" class="table style-3 dt-table-hover">
                              <thead>
                                  <tr>
                                      <th scope="col">ID</th>
                                      <th scope="col">Label</th>
                                      <th scope="col">Tipo</th>
                                      <th scope="col">Estatus</th>
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
  <?php include_once(ENLACE_SERVIDOR . "mod_documentos_mercantiles/tpl/footer_document.php"); ?>

  <script>
      function cargar_tabla__crm_terceros() {

        const ajaxoption = {
            url: '<?php echo ENLACE_WEB; ?>mod_categorias/ajax/listado.categorias.ajax.php',
            type: 'POST'
        }
        let options = config_datatable(ajaxoption);


         options.initComplete = function() {
                  this.api().columns().every(function(col) {
                      var column = this;
                      var header = $(column.header());
                      var headerText = header.text(); // Guarda el texto original del encabezado
                      header.empty(); // Limpia el encabezado

                    
                    

                      // Crea un contenedor div para el texto del encabezado
                      var headerTextContainer = $('<div>').appendTo(header);
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
              options.columns = [{
                      data: 'ID',
                      searchable: true,
                      createdCell: function(td, cellData, rowData, row, col) {
                          $(td).attr('data-label', 'ID');
                      },
                       render: function(data, type, row) {
                          return `<a href="${ENLACE_WEB}/categoria_producto_editar/${row.ID}">${row.ID}</a>`
                      }
                  },
                  {
                      data: 'Label',
                      searchable: true,
                      createdCell: function(td, cellData, rowData, row, col) {
                          $(td).attr('data-label', 'Label');
                      },
                      render: function(data, type, row) {
                          return `<a href="${ENLACE_WEB}/categoria_producto_editar/${row.ID}">${data}</a>`
                      }

                  },
                  {
                      data: 'Tipo',
                      searchable: true,
                      createdCell: function(td, cellData, rowData, row, col) {
                          $(td).attr('data-label', 'Tipo');
                      },
                      render: function(data, type, row) {
                          return `<a href="${ENLACE_WEB}/categoria_producto_editar/${row.ID}">${data}</a>`
                      }

                  },
                  {
                      data: 'Estatus',
                      searchable: true,
                      createdCell: function(td, cellData, rowData, row, col) {
                          $(td).attr('data-label', 'Estatus');
                      },
                      render: function(data, type, row) {
                          if (data == 1) {
                              return '<span class="shadow-none badge badge-primary">Activo</span>';
                          } else if (data != 1) {
                              return '<span class="shadow-none badge badge-danger">Inactivo</span>';
                          }
                      }
                  }
              ]
              $('#style-3').DataTable(options);

              let newButton = $('<button>')
            .html('<i class="fa-solid fa-plus"></i> Nueva Categoria')
            .addClass('btn btn-primary')
            .attr("type", "button")
            .on('click', function() {
                window.location = '<?= ENLACE_WEB ?>categoria_producto_crear/'
            });
        setting_table(vtabla, [newButton])
          };
          
 
  </script>

    <script>
      $(document).ready(function() {

          cargar_tabla__crm_terceros();

          $(".menu").removeClass('active');

       

      });
  </script>
