

<div class="middle-content container-xxl p-0">
    <!-- BREADCRUMB -->
    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Proyectos</a></li>
                <li class="breadcrumb-item active" aria-current="page">Listado</li>
            </ol>
        </nav>
    </div>
    <!-- /BREADCRUMB -->

    <!-- CONTENT AREA -->
    <div class="row layout-top-spacing">

        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
        <div class="widget-content searchable-container list"></div>
            
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Listado de Proyectos</h4>
                        </div>
                    </div>
                </div>
                <!-- <div class="widget-content widget-content-area"> -->
                <div class="widget-content widget-content-area br-8">

                    <form id="formulario">
                        <div class="table-responsive"> 
                            <!-- <table class="table table-bordered" id="style-3"> -->
                            <style>
                                #style-3 tr td
                                {
                                    text-align:center;
                                }
                            </style>
                            <table id="style-3" class="table style-3 dt-table-hover p-0">
                                <thead>
                                    <tr>
                                        <th class="text-center" scope="col">Consecutivo</th>
                                        <th class="text-center" scope="col">Proyecto</th>
                                        <th class="text-center" scope="col">Fecha</th>
                                        <th class="text-center" scope="col">Lugar</th>
                                        <th class="text-center" scope="col">Fecha de Creación</th>
                                        <th class="text-center" scope="col">Estado</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody" role="alert" aria-live="polite" aria-relevant="all" id="tbody" style="font-size:small;">
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <!--  END CONTENT AREA  -->

</div>

<script>
    $(document).ready(function() {
        cargar_tabla_proyectos();

        // Desactivar todos los elementos del menú
        $(".menu").removeClass('active');

        $(".albaranes").addClass('active');
        $(".albaranes > .submenu").addClass('show');
        $("#listado_albaranes").addClass('active');
    });

    function cargar_tabla_proyectos() {
    vtabla = $('#style-3').DataTable({
        'processing': true,
        'serverSide': true,
        "bSort": false,
        "pageLength": 10,
        "order": [
            [0, "desc"]
        ],
        select: {
            style: 'multi'
        },
        "dom": estiloPaginado.dom,
        "oLanguage": estiloPaginado.oLanguage,
        'ajax': {
            'url': '<?php echo ENLACE_WEB; ?>mod_redhouse_proyecto/json/proyectos.listado.json.php',
            'method': 'POST'
        },
        retrieve: true,
        deferRender: true,
        scroller: true,
        responsive: true,
        'columns': [
            {
                data: 'ID',
                searchable: true,
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('data-label', 'Consecutivo');
                    $(td).addClass('text-center').addClass('p-1');
                },
                render: function(data, type, row) {
                    return `<a href="${ENLACE_WEB}redhouse_proyecto_detalle/${row.ID}">
                                <i class="fa-regular fa-file-lines"></i> ${row.Referencia}
                            </a>`;
                }
            },
            {
                data: 'proyecto_descripcion',
                searchable: true,
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('data-label', 'Proyecto').addClass('p-1');
                }
            },
            {
                data: 'proyecto_fecha',
                searchable: true,
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('data-label', 'Fecha').addClass('p-1');
                }
            },
            {
                data: 'proyecto_lugar',
                searchable: true,
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('data-label', 'Lugar').addClass('p-1');
                }
            },
            {
                data: 'proyecto_fecha_creacion',
                searchable: true,
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('data-label', 'Fecha de Creación').addClass('p-1');
                }
            },
            {
                data: 'proyecto_estado',
                searchable: true,
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('data-label', 'Estado').addClass('p-1');
                },
                render: function(data, type, row) {
                        let estadoTexto = '';
                        let badgeClass = '';
                        switch (data) {
                            case 1:
                                estadoTexto = 'Pendiente';
                                badgeClass = 'badge-warning';  // Clase personalizada para estado 'Pendiente'
                                break;
                            case 2:
                                estadoTexto = 'Procesado';
                                badgeClass = 'badge-info';  // Clase personalizada para estado 'Procesado'
                                break;
                            case 3:
                                estadoTexto = 'Completado';
                                badgeClass = 'badge-success';  // Clase personalizada para estado 'Completado'
                                break;
                            default:
                                estadoTexto = 'Desconocido';
                                badgeClass = 'badge-secondary';  // Clase para cualquier otro estado no mapeado
                        }

                        return `<span class="badge ${badgeClass}">${estadoTexto}</span>`;
                    }
            }
        ],
        "initComplete": function () {
            this.api().columns().every(function () {
                var column = this;
                var header = $(column.header());
                var headerText = header.text();
                header.empty();
                var headerTextContainer = $('<div>').appendTo(header);
                $('<span>').text(headerText).appendTo(headerTextContainer);
                var inputContainer = $('<div>').appendTo(header);
            });
        }
    });
}



</script>
