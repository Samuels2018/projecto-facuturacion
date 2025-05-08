<?php

include ENLACE_SERVIDOR . 'mod_funnel/object/funnel.object.php';
include ENLACE_SERVIDOR . 'mod_crm_actividades/object/actividades.object.php';

$funnel = new FiFunnel($dbh);
$iconos = $funnel->obtener_diccionario_iconos();
$actividades = new Actividades($dbh, $_SESSION["Entidad"]);
$estado_actividades = $actividades->listaEstadoActividades();

include_once(ENLACE_SERVIDOR . "mod_documentos_mercantiles/tpl/header_document.php");
?>



<div class="middle-content container-fluid p-0">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <!-- BREADCRUMB -->
    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">CRM</a></li>
                <li class="breadcrumb-item active" aria-current="page">Mis actividades pendientes</li>
            </ol>
        </nav>
    </div>
    <!-- /BREADCRUMB -->

    <!-- CONTENT AREA -->
    <div class="row layout-top-spacing">
        <!-- Button trigger modal -->


        <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
            <div class="widget-content widget-content-area br-8">
                <div class="table-responsive">
                    <table id="style-3" class="table style-3 dt-table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Oportunidad</th>
                                <th scope="col">Cliente</th>
                                <th scope="col">Tipos</th>
                                <th scope="col">Fecha Vencimiento</th>
                                <th scope="col">Dias Vencimiento</th>
                                <th scope="col">Usuario Responsable</th>
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
    $(document).ready(function() {
        cargar_tabla();
        $(".menu").removeClass('active');
        $(".mod_crm").addClass('active');
        $(".mod_crm > .submenu").addClass('show');
        $("#mis_pendientes").addClass('active');
    });
</script>

<script>
    function cargar_tabla() {
        const ajaxoption = {
            url: '<?php echo ENLACE_WEB; ?>mod_crm_actividades/ajax/listado_actividades.ajax.php?filtro_pendiente=1',
            type: 'POST'
        }
        let options = config_datatable(ajaxoption);

        options.initComplete = function() {
            // Configuración de la búsqueda y otros ajustes aquí
            this.api().columns().every(function(col) {
                var column = this;
                var header = $(column.header());
                var headerText = header.text(); // Guarda el texto original del encabezado
                header.empty(); // Limpia el encabezado
                // Crea un contenedor div para el texto del encabezado
                var headerTextContainer = $('<div class="text-center" >').appendTo(header);
                $('<span>').text(headerText).appendTo(headerTextContainer);

                // Lista de índices de columnas donde NO quieres mostrar el input de búsqueda
                var excludedColumns = [4]; // Por ejemplo, para excluir las columnas 2, 4 y 6

                // Crea un contenedor div para el input, independientemente de si está oculto o visible
                var inputContainer = $('<div>').css({
                    'width': '100%', // Asegura que el contenedor ocupe todo el espacio disponible
                    'height': '100%', // Asegura que el contenedor ocupe todo el espacio disponible
                    'position': 'relative' // Posiciona el contenedor de manera relativa para que el input se posicione correctamente dentro de él
                }).appendTo(header);

                if ($.inArray(col, excludedColumns) >= 0) {
                    return '';
                    // var input = $('<input type="text" class="form-control">')
                    //     .appendTo(inputContainer)
                    //     .on('input', function() { // Cambiado de 'change' a 'input'
                    //         var val = $.fn.dataTable.util.escapeRegex(
                    //             $(this).val()
                    //         );
                    //         column
                    //             .search(val ? '^' + val + '$' : '', true, false)
                    //             .draw();
                    //     });
                } else if (column.dataSrc() === 'actividad_nombre') {
                    $.ajax({
                        method: "POST",
                        async: false,
                        url: "<?php echo ENLACE_WEB; ?>mod_tipo_actividad/json/listar_tipo_actividad.php",
                    }).done(function(data) {
                        const respuesta = JSON.parse(data)
                        const result = respuesta.message.map(item => ({ value: item.nombre, text: item.nombre }));
                        let select = generarSelectGeneral('actividad_nombre', result );
                        $(select).appendTo(header).on('change', function() {
                            var val = $(this).val();
                            column.search(val ? '^' + val + '$' : '', true, false).draw();
                        });
                    })
                }else if (column.dataSrc() === 'estado') {
                    const listado_estados = JSON.parse('<?php echo json_encode($estado_actividades); ?>')
                    const result = listado_estados.map(item => ({ value: item.etiqueta, text: item.etiqueta }));
                    let select = generarSelectGeneral('estado', result );
                    $(select).appendTo(header).on('change', function() {
                        var val = $(this).val();
                        column.search(val ? '^' + val + '$' : '', true, false).draw();
                    });

                    // let select = generarSelectEstatus('estado');
                    // $(select).appendTo(header).on('change', function() {
                    //     var val = $(this).val();
                    //     column.search(val ? '^' + val + '$' : '', true, false).draw();
                    // });
                } else if (column.dataSrc() === 'vencimiento_fecha') {
                    // Input de fecha con placeholder
                    $('<input type="date" class="form-control">')
                        .attr('placeholder', headerText) // Usar el texto del encabezado como placeholder
                        .attr('titulo', headerText)
                        .appendTo(inputContainer)
                        .on('change', function() {
                            var val = $(this).val();
                            column
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
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
            $('.dataTables_length').parent().removeClass('col-sm-6').addClass('col-sm-12');
        }
        options.columns = [{
                data: 'consecutivo',
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('data-label', 'Consecutivo');
                },
                render: function(data, type, row) {
                    return `
                    <a href="${row.enlace_oportunidad}">
                        ${row.consecutivo}
                    </a>
                    `;
                }
            },
            {
                data: 'cliente_txt',
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('data-label', 'Clientes');
                },
                render: function(data, type, row) {
                    return `
                    <a href="${row.enlace_tercero}">
                        ${row.icono_cliente}
                        ${row.cliente_txt}
                    </a>
                    `;
                }
            },
            {
                data: 'actividad_nombre',
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('data-label', 'Actividad');
                },
                render: function(data, type, row) {
                    // return `<a href="${row.enlace_oportunidad}">${row.actividad_nombre}</a>`;
                    return `<a href="${row.enlace_oportunidad}" title="${row.nombre_actividad}">${row.icono_actividad}</a>`;
                }
            },
            /* {
                    data: 'tipo',
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'tipo');
                    }
                },*/
            {
                data: 'vencimiento_fecha',
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('data-label', 'Fecha Vencimiento');
                },
                render: function(data, type, row) {
                    return `<a href="${row.enlace_oportunidad}">${row.vencimiento_fecha}</a>`;
                }
            },
            {
                data: 'dias_vencimiento',
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('data-label', 'Dias Vencimiento');
                },
                render: function(data, type, row) {
                    return `<a href="${row.enlace_oportunidad}">${row.dias_vencimiento}</a>`;
                }
            },

            {
                data: 'nombre_usuario_asginado_txt',

                searchable: true,
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('data-label', 'Responsable');
                },
                render: function(data, type, row) {

                    return ` <div class="avatar-chip bg-primary mb-2 me-4 position-relative" >
                                        <a href="#" style="color:white;"><img alt="avatar" src="${row.avatar}" />
                                        <span class="text" >${data}</span></a>
                                    </div>`

                }
            }

            // Aquí puedes agregar más columnas según sea necesario
        ]
        vtabla = $('#style-3').DataTable(options)
        
        setting_table(vtabla)
    }

</script>