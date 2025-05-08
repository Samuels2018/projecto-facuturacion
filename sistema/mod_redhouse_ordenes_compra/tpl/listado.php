<div class="middle-content container-xxl p-0">
    <!-- BREADCRUMB -->
    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Órdenes</a></li>
                <li class="breadcrumb-item active" aria-current="page">Listado</li>
            </ol>
        </nav>
    </div>
    <!-- /BREADCRUMB -->

    <!-- CONTENT AREA -->
    <div class="row layout-top-spacing">

        <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
            <div class="widget-content searchable-container list"></div>

            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Listado de órdenes de compra</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area br-8">

                    <form id="formulario">
                        <div class="table-responsive">
                            <style>
                                #style-3 tr td {
                                    text-align:center;
                                }
                            </style>
                            <table id="style-3" class="table style-3 dt-table-hover p-0">
                                <thead>
                                    <tr>
                                        <th class="text-center" scope="col">Referencia</th>
                                        <th class="text-center" scope="col">Proveedor</th>
                                        <th class="text-center" scope="col">Proyecto</th>
                                        <th class="text-center" scope="col">Fecha Creación</th>
                                        <th class="text-center" scope="col">Fecha Vigencia</th>
                                        <th class="text-center" scope="col">Estado</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody" role="alert" aria-live="polite" aria-relevant="all" style="font-size:small;">
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
        cargar_tabla_ordenes();

        // Desactivar todos los elementos del menú
        $(".menu").removeClass('active');
        $(".albaranes").addClass('active');
        $(".albaranes > .submenu").addClass('show');
        $("#listado_albaranes").addClass('active');
    });

    function cargar_tabla_ordenes() {
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
                'url': '<?php echo ENLACE_WEB; ?>mod_redhouse_ordenes_compra/json/ordenes.listado.json.php',
                'method': 'POST'
            },
            retrieve: true,
            deferRender: true,
            scroller: true,
            responsive: true,
            'columns': [
                {
                    data: 'Referencia',
                    searchable: true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'Referencia');
                        $(td).addClass('text-center').addClass('p-1');
                    },
                    render: function(data, type, row) {
                        return `<a href="${ENLACE_WEB}redhouse_ordenes_compra_detalle/${row.ID}">
                                    <i class="fa-regular fa-file-lines"></i> ${row.Referencia}
                                </a>`;
                    }
                },
                {
                    data: 'Proveedor',
                    searchable: true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'Proveedor').addClass('p-1');
                    }
                },
                {
                    data: 'Proyecto',
                    searchable: true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'Proyecto').addClass('p-1');
                    }
                },
                {
                    data: 'Fecha Creación',
                    searchable: true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'Fecha Creación').addClass('p-1');
                    }
                },
                {
                    data: 'Fecha Vigencia',
                    searchable: true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'Fecha Vigencia').addClass('p-1');
                    }
                },
                {
                    data: 'Estado',
                    searchable: true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'Estado').addClass('p-1');
                    },
                    render: function(data, type, row) {
                        let estadoTexto = '';
                        let badgeClass = '';
                      
                        switch (data) {
                            case 'Pendiente':
                                estadoTexto = 'Pendiente';
                                badgeClass = 'badge-warning';
                                break;
                            case 'Procesado':
                                estadoTexto = 'Procesado';
                                badgeClass = 'badge-info';
                                break;
                            case 'Completado':
                                estadoTexto = 'Completado';
                                badgeClass = 'badge-success';
                                break;
                            case 'Cancelado':
                                estadoTexto = 'Cancelado';
                                badgeClass = 'badge-danger';
                                break;
                            default:
                                estadoTexto = 'Desconocido';
                                badgeClass = 'badge-secondary';
                        }

                        return `<span class="badge ${badgeClass}">${data}</span>`;
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
