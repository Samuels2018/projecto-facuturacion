<!-- Datatable -->
<div class="middle-content container-xxl p-0">

    <!-- BREADCRUMB -->
    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo ENLACE_WEB; ?>proveedores_listado"><i class="fa fa-dashboard"></i> Proveedores</a></li>
                <li class="breadcrumb-item active" aria-current="page">Listado Compras Proveedor <?php echo $tercero->nombre; ?></li>
            </ol>
        </nav>
    </div>
    <!-- /BREADCRUMB -->

    <!-- CONTENT AREA -->
    <div class="row layout-top-spacing">

        <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">

            <div class="widget-content widget-content-area br-8">

                <form id="formulario">
                    <!-- Tabla de Compras Proveedor -->
                    <div class="table-responsive">
                        <table id="comprasProveedor" class="table style-3 dt-table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">Numero compra</th>
                                    <th scope="col">Factura</th>
                                    <th scope="col">Fecha</th>
                                    <th scope="col">Monto</th>
                                    <th scope="col">Pagado</th>
                                    <th scope="col">Estado</th>
                                </tr>
                            </thead>
                            <tbody id="tbody" role="alert" aria-live="polite" aria-relevant="all" id="tbody" style="font-size:small;">
                                <?php echo $tr; ?>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>

            <!--Fin Tabla de Compras Proveedor -->

        </div>
    </div>

    <!-- CONTENT AREA -->

</div>
<!-- Scripts -->
<script>
    $(document).ready(function() {

        cargar_tabla_compras_proveedor();
    });
</script>

<script>
    function cargar_tabla_compras_proveedor() {

        vtabla = $('#comprasProveedor').DataTable({
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
            "dom": "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
                "<'table-responsive'tr>" +
                "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count mb-sm-0 mb-3'i><'dt--pagination'p>>",
            "oLanguage": {
                "oPaginate": {
                    "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                    "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'
                },
                "sInfo": "Showing page _PAGE_ of _PAGES_",
                "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                "sSearchPlaceholder": "Search...",
                "sLengthMenu": "Results : _MENU_",
            },
            "stripeClasses": [],
            'ajax': {
                'url': '<?php echo ENLACE_WEB; ?>mod_informes/compras_por_empresa/ajax/compra_por_empresa.ajax.php',
                'Method': 'POST',
                'data': {
                    'id': '<?php echo $_GET['id']; ?>'
                }
            },
            retrieve: true,
            deferRender: true,
            scroller: true,
            responsive: true,
            'columns': [{
                    data: 'NumeroCompra',
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'Numero compra');
                    },
                    render: function(data, type, row) {
                        console.log('data 1'+data);
                        return '<a href="'+ENLACE_WEB+'dashboard.php?accion=ver_compra&fiche=' +data.rowid+  '"><i class="fa fa-fw fa-paperclip"></i>' +data.referencia+  '</a>';
                    }
                },
                {
                    data: 'Factura',
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'Factura');
                    }
                },
                {
                    data: 'Fecha',
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'Fecha');
                    }
                },
                {
                    data: 'Monto',
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'Monto');
                    }
                },
                {
                    data: 'Pagado',
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'Pagado');
                    }
                },
                {
                    data: 'Estado',
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'Estado');
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