<?php


require_once ENLACE_SERVIDOR . "mod_entidad/object/Entidad.object.php";
$Entidad = new Entidad($dbh,    $_SESSION['Entidad']);
$Entidad->cargar_sincronizaciones();
include_once(ENLACE_SERVIDOR . "mod_documentos_mercantiles/tpl/header_document.php");
?>

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


<div class="middle-content container-xxl p-0">

    <!-- BREADCRUMB -->
    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo ENLACE_WEB; ?>proveedores_listado">Proveedores</a></li>

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
                                    <th scope="col">Nombre Comercial</th>

                                    <th scope="col">Teléfono</th>
                                    <th scope="col">Correo</th>
                                    <th scope="col">Detalle</th>
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
    $(document).ready(function() {

       


        cargar_tabla_terceros();

        $(".menu").removeClass('active');
        $(".proveedores").addClass('active');

        // Crea un contenedor div para los botones
        var buttonContainer = $('<div>').attr("id", "export-buttons-container").addClass('ml-2');

        // Crea el botón de Excel con el icono de Font Awesome
        let newButton = $('<button>')
            .html('<i class="fa-solid fa-plus"></i> Nuevo Proveedor')
            .addClass('btn btn-info _effect--ripple waves-effect waves-light')
            .attr("data-bs-toggle", "modal")
            .attr("data-bs-target", "contactoModal")
            .attr("data-invoice-id", "<?= $id ?>")
            .on('click', function() {
                event.preventDefault()
                window.location = '<?= ENLACE_WEB ?>proveedores_nuevo'
            });

        
    });
</script>

<script>
    function cargar_tabla_terceros() {

        const ajaxoption = {
            url: '<?php echo ENLACE_WEB; ?>mod_terceros/ajax/listado.proveedores.ajax.php',
            type: 'GET'
        }
        let options = config_datatable(ajaxoption);

        
            options.initComplete = function() {
                this.api().columns().every(function() {
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
                        $(select).appendTo(header).on('change', function() {
                            var val = $(this).val();
                            column.search(val ? '^' + val + '$' : '', true, false).draw();
                        });
                    } else {
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
                    }
                });
            },
            options.columns = [
                {
                    data: 'Nombre',
                    searchable: true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'Nombre');
                    },
                    render: function(data, type, row) {

                        let img = '';

                        <?php if ($Entidad->sincronizaciones[1]): ?>


                            if (row.sincronizado == 1) {
                                img = '<img title="' + row.title + '" style="width: 5%;" src="<?= ENLACE_WEB . '/bootstrap/img/quickbooks_activo.png' ?>">';

                            } else {
                                img = '<img title="' + row.title + '" style="width: 5%;" src="<?= ENLACE_WEB . '/bootstrap/img/quickbooks_inactivo.png' ?>">';

                            }
                        <?php endif; ?>

                        return `
                <a href="${ENLACE_WEB}proveedores_editar/${row.ID}">
                ${img}
                  
                    ${row.Nombre}
                </a>
                `;

                    }
                },
                {
                    data: 'Fantasia',
                    searchable: true,
                    render: function(data, type, row) {
                        return `
                       <a href="${ENLACE_WEB}proveedores_editar/${row.ID}">
                           ${row.Fantasia}
                       </a>
                       `;

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
                    data: 'Detalle',
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'Link');
                    },
                    render: function(data, type, row) {
                        return `
                       <a href="${ENLACE_WEB}compras_por_empresa/${data}">
                           <i class="fa-solid fa-chart-simple"></i>
                       </a>
                       `;

                    }
                },
                {
                    data: 'Estatus',
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
            vtabla = $('#style-3').DataTable(options)
            let newButton = $('<button>')
            .html('<i class="fa-solid fa-plus"></i> Nuevo Proveedor')
            .addClass('btn btn-primary')
            .attr("type", "button")
            .on('click', function() {
                window.location = '<?= ENLACE_WEB ?>proveedores_nuevo'
            });
        setting_table(vtabla, [newButton])
    }
</script>


<script>





</script>