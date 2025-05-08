  <!-- Datatable -->

  <div class="middle-content container-xxl p-0">

      <!-- BREADCRUMB -->
      <div class="page-meta">
          <nav class="breadcrumb-style-one" aria-label="breadcrumb">
              <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Clientes</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Listado</li>
              </ol>
          </nav>
      </div>
      <!-- /BREADCRUMB -->


      <!-- CONTENT AREA -->
      <div class="row layout-top-spacing">
        

          <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
              <div class="row my-4">
                  <div class="col-md-3">
                      <a href="<?php echo ENLACE_WEB ?>clientes_nuevo" class="btn btn-primary">Nuevo Cliente</a>
                  </div>
                  <!-- Snippet Login Quickbooks -->
                  <?php include ENLACE_SERVIDOR."mod_quickbooks/tpl/login_quickbooks.php" ?>
              </div>
              <div class="widget-content widget-content-area br-8">

           



                  <form id="formulario">
                      <!-- Tabla  -->
                      <div class="table-responsive">
                          <table id="style-3" class="table style-3 dt-table-hover">
                              <thead>
                                  <tr>
                                      <th scope="col">ID</th>
                                      <th scope="col">Nombre</th>
                                      <th scope="col">Cédula</th>
                                      <th scope="col">Teléfono</th>
                                      <th scope="col">Correo</th>
                                      <th scope="col">Tipo Persona</th>
                                      <th scope="col">Estatus</th>
                                  </tr>

                              </thead>
                              <tbody id="tbody" role="alert" aria-live="polite" aria-relevant="all" id="tbody" style="font-size:small;">
                                  <?php # require_once(ENLACE_SERVIDOR . "mod_terceros/ajax/listado.terceros.ajax.php");
?>
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
  <script>
      $(document).ready(function() {


          cargar_tabla_terceros();
      });
  </script>

  <script>
      function cargar_tabla_terceros() {

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
                  'url': '<?php echo ENLACE_WEB; ?>mod_terceros/ajax/listado.terceros.ajax.php',
                  'Method': 'POST'
              },
              retrieve: true,
              deferRender: true,

              scroller: true,
              responsive: true,
              "initComplete": function () {
    this.api().columns().every(function () {
        var column = this;
        var header = $(column.header());
        var headerText = header.text(); // Guarda el texto original del encabezado
        header.empty(); // Limpia el encabezado

        // Crea un contenedor div para el texto del encabezado
        var headerTextContainer = $('<div>').appendTo(header);
        $('<span>').text(headerText).appendTo(headerTextContainer);

        // Crea un contenedor div para el input/select
        var inputContainer = $('<div>').appendTo(header);

        if (column.dataSrc() === 'Estatus') {
            let select = generarSelectEstatus('estatus');
            $(select).appendTo(header).on('change', function () {
                 var val = $(this).val();
                 column.search(val ? '^' + val + '$' : '', true, false).draw();
             });
        }else if(column.dataSrc() === 'Tipo Persona'){
            let select = generarSelectTipo('tipo_persona');
            $(select).appendTo(header).on('change', function () {
                 var val = $(this).val();
                 column.search(val ? '^' + val + '$' : '', true, false).draw();
             });
        } else {
            // Para los campos de texto
var input = $('<input type="text" class="form-control">')
    .appendTo(inputContainer)
    .on('input', function () { // Cambiado de 'change' a 'input'
        var val = $.fn.dataTable.util.escapeRegex(
            $(this).val()
        );
        column
            .search(val ? '^' + val + '$' : '', true, false)
            .draw();
    });

        }
    });
},
              'columns': [{
                      data: 'ID',
                     searchable: true,
                      createdCell: function(td, cellData, rowData, row, col) {
                          $(td).attr('data-label', 'ID');
                      }
                  },
                  {
                      data: 'Nombre',
                     searchable: true,
                      createdCell: function(td, cellData, rowData, row, col) {
                          $(td).attr('data-label', 'Nombre');
                      },
                      render: function(data, type, row) {
                        return `
                        <a href="${ENLACE_WEB}clientes_editar/${row.ID}">
                            <i class="fa-solid fa-user"></i>
                            ${row.Nombre} ${row.Apellidos}
                        </a>
                        `;

                      }

                  },
                  {
                      data: 'Cedula',
                     searchable: true,
                      createdCell: function(td, cellData, rowData, row, col) {
                          $(td).attr('data-label', 'Cedula');
                      }
                  },
                  {
                      data: 'Telefono',
                     searchable: true,
                      createdCell: function(td, cellData, rowData, row, col) {
                          $(td).attr('data-label', 'Telefono');
                      }
                  },
                  {
                      data: 'Correo',
                     searchable: true,
                      createdCell: function(td, cellData, rowData, row, col) {
                          $(td).attr('data-label', 'Correo');
                      }
                  },
                  {
                      data: 'Tipo Persona',
                     searchable: true,
                      createdCell: function(td, cellData, rowData, row, col) {
                          $(td).attr('data-label', 'Tipo Persona');
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
                          } else if (data == 0) {
                              return '<span class="shadow-none badge badge-danger">Inactivo</span>';
                          }
                      }
                  }
              ]
          });
      }
  </script>


  <script>





  </script>