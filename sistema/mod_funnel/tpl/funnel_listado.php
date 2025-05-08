<?php

include ENLACE_SERVIDOR . 'mod_funnel/object/funnel.object.php';
$funnel = new FiFunnel($dbh);
$iconos = $funnel->obtener_diccionario_iconos();

include_once(ENLACE_SERVIDOR . "mod_documentos_mercantiles/tpl/header_document.php");
?>
<div class="middle-content container-xxl p-0">
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <!-- BREADCRUMB -->
    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Funnels</a></li>
                <li class="breadcrumb-item active" aria-current="page">Listado</li>
            </ol>
        </nav>
    </div>
    <!-- /BREADCRUMB -->

    <!-- CONTENT AREA -->
    <div class="row layout-top-spacing">
        <!-- Button trigger modal -->
        <div class="col-md-2 my-2">
           <!--  <button type="button" class="btn btn-primary" onclick="mostrar_modal();">
                Crear Funnel
            </button> -->
        </div>

        <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
            <div class="widget-content widget-content-area br-8">
                <div class="table-responsive">
                    <table id="style-3" class="table style-3 dt-table-hover p-0">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Título</th>
                                <th scope="col">Descripción</th>
                                <th scope="col">Color</th>
                                <th scope="col">Icono</th>
                                <th scope="col">Gesti&oacute;n</th>
                            </tr>
                        </thead>
                        <tbody id="tbody" role="alert" aria-live="polite" aria-relevant="all" style="font-size:small;">
                            <!-- Los datos de los funnels se cargarán aquí dinámicamente -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /CONTENT AREA -->
</div>

<!-- Modal Crear -->
<!-- Modal -->


<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><span id="modal_titulo"></span> Funnel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <svg> ... </svg>
                </button>
            </div>
            <div class="modal-body">
                <?php include ENLACE_SERVIDOR . 'mod_funnel/tpl/editar_funnel.php'; ?>
            </div>
            <div class="modal-footer">
                <button type="button" id="boton_eliminar" onclick="eliminar_funnel(event)" class="btn btn btn-danger"><i class="flaticon-cancel-12"></i> Eliminar</button>
                <button class="btn btn btn-light-dark" data-bs-dismiss="modal"><i class="flaticon-cancel-12"></i> Cancelar</button>
                <button type="button" id="boton_crear" onclick="validar_accion(event)" class="btn btn-primary"><span id="boton_crear_txt"></span></button>
            </div>
        </div>
    </div>
</div>
<?php include_once(ENLACE_SERVIDOR . "mod_documentos_mercantiles/tpl/footer_document.php"); ?>


<script>
    
    function cargar_tabla_funnels() {
        // Desactivar todos los elementos del menú
       

        const ajaxoption = {
            url: '<?php echo ENLACE_WEB; ?>mod_funnel/ajax/listado_funnel.ajax.php',
            type: 'POST'
        }
        let options = config_datatable(ajaxoption);

        options.initComplete = function() {
            var api = this.api();
            api.columns().every(function() {
                var column = this;
                var header = $(column.header());
                var headerText = header.text();

                // Limpia el encabezado y elimina el <span>
                header.empty();
                // Condición para no añadir input en la columna "Acciones"

                if (headerText === 'Acciones') {
                    // No agregar nada, dejar el contenedor vacío
                    return;
                }
                // Crea el contenedor del input o select
                var inputContainer = $('<div>').css({
                    'width': '100%',
                    'height': '100%',
                    'position': 'relative'
                }).appendTo(header);
                $('<span>').text(headerText).appendTo(inputContainer);

                // Crea el input o select

                if (headerText === 'Título') {
                    var input = $('<input type="text" class="form-control">').appendTo(inputContainer);

                }
                if (headerText === 'Descripción') {
                    var input = $('<input type="text" class="form-control">').appendTo(inputContainer);

                }
                if (headerText === 'Color') {
                    var input = $('<input type="text" class="form-control">').appendTo(inputContainer);

                }

                
            });
        };

        options.columns = [{
                    data: 'ID',
                    searchable: false,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'ID');
                    }
                },
                {
                    data: 'Título',
                    searchable: true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'Título');
                    },
                    render: function(data, type, row) {
                        return `
                        <a href="#" onclick="fetch(${row.ID})">
                            <i class="fa-solid fa-chart-line"></i>
                            ${row.Título}
                        </a>
                        `;
                    }
                },
                {
                    data: 'Descripción',
                    searchable: true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'Descripción');
                    }
                },
                {
                    data: 'Color',
                    searchable: true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'Color');
                    },
                    render: function(data, type, row) {
                        return `<span class="badge badge-${row.Color}" style='background-color: ${row.Color};'>${row.Color}</span>`
                    }
                },
                {
                    data: 'Icono',
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'Icono');
                    },
                    render: function(data, type, row) {
                        return `<i class="${row.Icono}"></i>`;
                    }
                },
                {
                    data: 'ID',
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'Gestión');
                    },
                    render: function(data, type, row) {
                        return `
                        <a href="#" onclick="fetch(${row.ID})">
                            <i class="fa-solid fa-cog"></i>
                            
                        </a>
                        `;
                    }
                }
                // Aquí puedes agregar más columnas según sea necesario
            ]

            vtabla = $('#style-3').DataTable(options)

            let newButton = $('<button>')
        .html('<i class="fa-solid fa-plus"></i> Nuevo Funnel')
        .addClass('btn btn-primary')
        .attr("type", "button")
        .on('click', function() {
            mostrar_modal();
        });
    setting_table(vtabla, [newButton])

        };
        
    $(document).ready(function() {
        cargar_tabla_funnels();
        $(".menu").removeClass('active');
        $(".funnels").addClass('active'); // Asegúrate de que esta clase exista en tu menú

    });
    
</script>

<?php include ENLACE_SERVIDOR . 'mod_funnel/tpl/editar_funnel_scripts.php'; ?>