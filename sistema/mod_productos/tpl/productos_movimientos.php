<div class="middle-content container-xxl p-0">
    <!-- BREADCRUMB -->
    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo ENLACE_WEB; ?>productos_listado">Productos</a></li>
                <li class="breadcrumb-item active" aria-current="page">Movimientos de Stock</li>
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
                            <h4><?php echo "Movimientos del Producto: ". $prod_label = $_GET['prod_label'] ." - Id: ". $id_producto = $_GET['id_producto']; ?></h4>
                        </div>
                    </div>
                </div>
                <!-- <div class="widget-content widget-content-area"> -->
                <div class="widget-content widget-content-area br-8">

                    <form id="formulario">
                        <div class="table-responsive"> 
                            <!-- <table class="table table-bordered" id="style-3"> -->
                            <table id="style-3" class="table style-3 dt-table-hover p-0">
                                <thead>
                                    <tr>
                                        <!-- 
                                            EN EL SISTEMA ANTERIOR
                                            Id	
                                            Tipo	
                                            Unidades Transladadas	
                                            Stock Al Momento	
                                            Fecha & Hora
                                         -->
                                        <th class="text-center" scope="col">Id</th>
                                        <th class="text-center" scope="col">Tipo</th>
                                        <th class="text-center" scope="col">Bodega</th>
                                        <th class="text-center" scope="col">Valor</th>
                                        <th class="text-center" scope="col">Stock Actual</th>
                                        <th class="text-center" scope="col">Fecha & Hora</th>
                                        <th class="text-center" scope="col">Motivo</th>
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
        cargar_tabla_facturas();

        // Desactivar todos los elementos del menú
        $(".menu").removeClass('active');

        $(".facturacion").addClass('active');
        //$(".facturacion").addClass('show');
        $(".facturacion > .submenu").addClass('show');
        $("#listado_facturas").addClass('active');
    });

    function cargar_tabla_facturas() {
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
            "dom": "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
                "<'table-responsive'tr>" +
                "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count mx-2 mb-sm-0 mb-3'i><'dt--pagination d-sm-flex justify-content-sm-between'p>>",
            "oLanguage": {
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla",
                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix": "",
                "sSearch": "Buscar:",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": "<i class='fa fa-chevron-right'></i>",
                    "sPrevious": "<i class='fa fa-chevron-left'></i>"
                },
                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            },
            'ajax': {
                'url': '<?php echo ENLACE_WEB; ?>mod_productos/ajax/movimientos_stock.ajax.php',
                'Method': 'POST',
                data: { id_producto: "<?php echo $_GET['id_producto']; ?>" },
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

                });
            },
            'columns': [{
                    data: 'ID',
                    searchable: true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'ID');
                        $(td).addClass('text-center').addClass('p-1');
                    }/* OJO Q DICE FACTURA EL ENLACE,
                    render: function(data, type, row) {
                        return `
                        <a href="${ENLACE_WEB}factura/${row.ID}">
                            <i class="fa-regular fa-file-lines"></i> ${row.ID}
                        </a>
                        `;
                    }*/
                },
                {
                    data: 'Tipo',
                    searchable: true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'Tipo').css('text-align', 'center').addClass('p-2');
                        //$('.mi-elemento').addClass('p-1');
                    }
                },
                {
                    data: 'Bodega',
                    searchable: true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'Bodega').addClass('p-1');
                    }
                },
                {
                    data: 'Valor',
                    searchable: true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'Valor').addClass('p-1').css('text-align', 'center');
                    }
                },
                {
                    data: 'stock_actual',
                    searchable: true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'stock_actual').addClass('p-1').css('text-align', 'center');
                    }
                },
                {
                    data: 'Fecha',
                    searchable: true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'Fecha').addClass('p-1');
                    }
                },
                {
                    data: 'Motivo',
                    searchable: true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'Motivo').addClass('p-1');
                    }
                }/*,
                {
                    data: 'etiqueta',
                    searchable: true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'etiqueta');
                        //$(td).append($('<span>').addClass(rowData.color).text(cellData));
                        var span = $('<span></span>'); // Crea un elemento span
                        span.text(cellData); // Asigna el texto de la celda al span
                        span.addClass(rowData.color); // Añade la clase correspondiente al color
                        $(td).empty().append(span);
                        $(td).addClass('text-center').addClass('p-1');
                    }
                }*/
            ]
        });
    }
</script>
