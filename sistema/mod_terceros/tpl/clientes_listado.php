

<style type="text/css">
      #style-3_filter {
          display: none !important;
      }
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
<?php
include_once(ENLACE_SERVIDOR . "mod_documentos_mercantiles/tpl/header_document.php");
?>
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
              
              <div class="widget-content widget-content-area br-8">
                  <form id="formulario">
                      <!-- Tabla  -->
                      <div class="table-responsive">
                          <table id="style-3" class="table style-3 dt-table-hover">
                              <thead>
                                  <tr>
                                       <th scope="col">Nombre y apellidos/Razón Social</th>
                                      <th scope="col">Nombre Comercial</th>
                                      <?php if (intval($_SESSION["Entidad"]) == 5) { ?>
                                          <th scope="col">Cédula</th>
                                      <?php } else { ?>
                                          <th scope="col">CIF</th>
                                      <?php } ?>
                                      <th scope="col">Correo</th>
                                      <th scope="col">Tipo Persona</th>
                                      <th scope="col">Categoria</th>
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
  
  <?php include_once(ENLACE_SERVIDOR . "mod_documentos_mercantiles/tpl/footer_document.php"); ?>
<script>
    function cargar_tabla_terceros() {
        const ajaxoption = {
            url: '<?php echo ENLACE_WEB; ?>mod_terceros/ajax/listado.terceros.ajax.php',
            type: 'GET'
        };
    
        let options = config_datatable(ajaxoption);
    
        options.initComplete = function() {
            var api = this.api();
            api.columns().every(function() {
                var column = this;
                var header = $(column.header());
                var headerText = header.text();
                
                // Limpiar el encabezado y eliminar el <span>
                header.empty();
                
                // Crear contenedor para el texto del encabezado y los filtros
                var inputContainer = $('<div>')
                    .css({
                        'width': '100%',
                        'height': '100%',
                        'position': 'relative'
                    })
                    .appendTo(header);
                
                $('<span>').text(headerText).appendTo(inputContainer);

                // Manejar diferentes tipos de columnas según su nombre
                switch(headerText) {
                    case 'Categoria':
                        // Crear select para categorías con spinner de carga
                        $('<div id="spinnerloading" titulo="Estado">Loading...</div>')
                            .appendTo(header);
                        
                        $.ajax({
                            url: '<?php echo ENLACE_WEB; ?>mod_terceros/class/terceros.class.php',
                            method: 'POST',
                            data: { action: 'listar_categoria_clientes' },
                            dataType: 'json',
                            success: function(response) {
                                $('#spinnerloading').remove();
                                
                                var select = $('<select>')
                                    .addClass('form-control')
                                    .attr('titulo', headerText)
                                    .append($('<option>').val('').text('Todos'))
                                    .appendTo(header);
                                
                                $.each(response, function(index, categoria) {
                                    select.append($('<option>')
                                        .val(categoria.id)
                                        .text(categoria.nombre));
                                });
                                
                                select.on('change', function() {
                                    var val = $(this).val();
                                    column.search(val ? '^' + val + '$' : '', true, false).draw();
                                });
                            },
                            error: function(xhr, status, error) {
                                $('#spinnerloading').text('Error al cargar');
                            }
                        });
                        break;

                    case 'Tipo Persona':
                        let select = generarSelectTipo('tipo_persona');
                        $(select).appendTo(header).on('change', function() {
                            var val = $(this).val();
                            column.search(val ? '^' + val + '$' : '', true, false).draw();
                        });
                        break;

                    case 'Estatus':
                        let selectEstatus = generarSelectEstatus('estatus');
                        $(selectEstatus).appendTo(header).on('change', function() {
                            var val = $(this).val();
                            column.search(val ? '^' + val + '$' : '', true, false).draw();
                        });
                        break;

                    default:
                        // Input de texto para otras columnas con placeholder
                        $('<input type="text" class="form-control">')
                            .css({
                                'min-width': '70px',
                                'width': '100%'
                            })
                            .attr('placeholder', headerText)
                            .attr('titulo', headerText)
                            .appendTo(inputContainer)
                            .on('input', function() {
                                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                column.search(val ? '^' + val + '$' : '', true, false).draw();
                            });
                }
            });
        };

    options.columns = [{
        data: 'Nombre',
        searchable: true,
        createdCell: function(td, cellData, rowData, row, col) {
            $(td).attr('data-label', 'Nombre');
            $(td).addClass('text-left').addClass('py-0 px-3');
        },
        render: function(data, type, row) {
            let img = '';
            <?php if ($Entidad->sincronizaciones[1]) : ?>
            if (row.sincronizado == 1) {
                img = '<img title="' + row.title + '" style="width: 5%;" src="<?= ENLACE_WEB . '/bootstrap/img/quickbooks_activo.png' ?>">';
            } else {
                img = '<img title="' + row.title + '" style="width: 5%;" src="<?= ENLACE_WEB . '/bootstrap/img/quickbooks_inactivo.png' ?>">';
            }
            <?php endif; ?>
            
            let nombre_a_mostrar = row["Tipo Persona"] == 'fisica' ? (row.Nombre+' '+ row.Apellidos) : row.Nombre;
            
            return `
                <a href="${ENLACE_WEB}clientes_editar/${row.ID}">
                    ${img} ${nombre_a_mostrar}
                </a>
            `;
        }
    }, {
        data: 'Apellidos',
        searchable: true,
        createdCell: function(td, cellData, rowData, row, col) {
            $(td).attr('data-label', 'Apellidos');
            $(td).addClass('text-left').addClass('py-0 px-3');
        },
        render: function(data, type, row) {
            return row.electronica_nombre_comercial;
        }
    }, {
        data: 'Cedula',
        searchable: true,
        createdCell: function(td, cellData, rowData, row, col) {
            $(td).attr('data-label', 'Cedula');
            $(td).addClass('text-left').addClass('py-0 px-3');
        }
    }, {
        data: 'Correo',
        searchable: true,
        createdCell: function(td, cellData, rowData, row, col) {
            $(td).attr('data-label', 'Correo');
            $(td).addClass('text-left').addClass('py-0 px-3');
        }
    }, {
        data: 'Tipo Persona',
        searchable: true,
        createdCell: function(td, cellData, rowData, row, col) {
            $(td).attr('data-label', 'Tipo Persona');
            $(td).addClass('text-left').addClass('py-0 px-3');
        }
    }, {
        data: 'Categoria',
        searchable: true,
        createdCell: function(td, cellData, rowData, row, col) {
            $(td).attr('data-label', 'Categoria');
            $(td).addClass('text-left').addClass('py-0 px-3');
        }
    }, {
        data: 'Estatus',
        searchable: true,
        createdCell: function(td, cellData, rowData, row, col) {
            $(td).attr('data-label', 'Estatus');
            $(td).addClass('text-left').addClass('py-0 px-3');
        },
        render: function(data, type, row) {
            if (data == 1) {
                return '<span class="shadow-none badge badge-primary">Activo</span>';
            } else if (data == 0) {
                return '<span class="shadow-none badge badge-danger">Inactivo</span>';
            }
        }
    }];

    vtabla = $('#style-3').DataTable(options);

    // Configuración adicional del botón
    let newButton = $('<button>')
        .html('<i class="fa-solid fa-plus"></i> Nuevo Tercero')
        .addClass('btn btn-primary')
        .attr("type", "button")
        .on('click', function() {
            window.location = '<?= ENLACE_WEB ?>clientes_editar/nuevo'
        });

    setting_table(vtabla, [newButton]);

}

   
</script>



<script>
      $(document).ready(function()
      {
          cargar_tabla_terceros();
          $(".menu").removeClass('active');
          $(".clientes").addClass('active');




      });
  </script>
