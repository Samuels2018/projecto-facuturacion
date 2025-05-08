<?php
include_once(ENLACE_SERVIDOR . "mod_documentos_mercantiles/tpl/header_document.php");
include ENLACE_SERVIDOR . 'mod_productos/object/productos.object.php';
include ENLACE_SERVIDOR . 'mod_stock/object/bodegas.object.php';

$Productos  = new Productos($dbh, $_SESSION['Entidad']);
$Productos->fetch($_GET['fiche']);
$Bodega    = new Bodegas($dbh, $_SESSION['Entidad']);
$Bodega->fetch($_GET['bodega']);

if (empty($Productos->id)) {
    echo "Producto No Encontrado";
    exit(1);
}

?><div class="middle-content container-fluid p-0">

    <!-- BREADCRUMB -->
    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo ENLACE_WEB; ?>productos_listado">Productos</a></li>
                <li class="breadcrumb-item"><a href="<?php echo ENLACE_WEB; ?>productos_editar/<?php echo $Productos->id;  ?>"><?php echo $Productos->ref;  ?></a></li>
                <li class="breadcrumb-item active" aria-current="page">Movimientos de Stock <?php echo $Bodega->label; ?></li>
            </ol>
        </nav>
    </div>
    <!-- /BREADCRUMB -->

    <!-- CONTENT AREA -->
    <div class="row layout-top-spacing">

        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
            <div class="widget-content searchable-container list"></div>

            <div class="statbox widget box box-shadow">
                <div class="widget-content widget-content-area br-8">
                    <form id="formulario">
                        <div class="table-responsive">
                            <table id="style-3" class="table style-3 dt-table-hover p-0">
                                <thead>
                                    <tr>
                                        <th class="text-center" scope="col">Tipo</th>
                                        <th class="text-center" scope="col">Movimiento</th>
                                        <th class="text-center" scope="col">Stock Actual</th>
                                        <th class="text-left" scope="col">Origen Movimiento</th>
                                        <th class="text-left" scope="col">Documento</th>
                                        <th class="text-left" scope="col">Fecha & Hora</th>
                                        <th class="text-left" scope="col">Hora</th>
                                        <th class="text-left" scope="col">Usuario</th>
                                        <th class="text-left" scope="col">Motivo</th>
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

    <div class="row mt-3">
        <div class="">
            <a class="btn btn-primary _effect--ripple waves-effect waves-light" href="<?php echo ENLACE_WEB; ?>productos_editar/<?php echo $Productos->id; ?>"> <i class="fas fa-arrow-left"></i> Volver al Producto <?php echo $Productos->ref;  ?> </a>
        </div>
    </div>

    <!--  END CONTENT AREA  -->

</div>
<?php include_once(ENLACE_SERVIDOR . "mod_documentos_mercantiles/tpl/footer_document.php"); ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>
    $(document).ready(function() {
        cargar_tabla_facturas();

        // Desactivar todos los elementos del menú
        $(".menu").removeClass('active');

        $(".menu productos").addClass('show');
        $("#submenu_productos").addClass('active');
    });

    function cargar_tabla_facturas() {
        const ajaxoption = {
            url: '<?php echo ENLACE_WEB; ?>mod_productos/ajax/movimientos_stock_en_bodega.ajax.php',
            type: 'GET',
            data: {
                id_producto: "<?php echo $_GET['fiche']; ?>",
                fk_bodega: "<?php echo $_GET['bodega']; ?>"
            }
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

                if (column.dataSrc() === 'fecha') {
                    // Crear el campo de entrada para el rango de fechas
                    var dateInput = $('<input type="text" id="date-range" class="form-control" placeholder="Selecciona un rango de fechas">')
                        .appendTo(header)
                        .on('apply.daterangepicker', function(ev, picker) {
                            var startDate = picker.startDate.format('YYYY-MM-DD');
                            var endDate = picker.endDate.format('YYYY-MM-DD');
                            // Enviar las fechas como un único valor separado por "|"
                            column.search(startDate + '|' + endDate).draw();
                        });

                    // Inicializar el Date Range Picker
                    dateInput.daterangepicker({
                        locale: {
                            format: 'YYYY-MM-DD',
                            separator: ' - ',
                            applyLabel: 'Aplicar',
                            cancelLabel: 'Cancelar',
                            fromLabel: 'Desde',
                            toLabel: 'Hasta',
                            customRangeLabel: 'Rango personalizado',
                            weekLabel: 'S',
                            daysOfWeek: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
                            monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                            firstDay: 1
                        },
                        opens: 'left',
                        autoUpdateInput: false, // Para que no se muestre nada hasta que se seleccione un rango
                    });

                    // Llenar el campo con el rango de fechas cuando el usuario aplique su selección
                    dateInput.on('apply.daterangepicker', function(ev, picker) {
                        $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
                    });

                    // Limpiar el campo si el usuario cancela la selección de rango
                    dateInput.on('cancel.daterangepicker', function(ev, picker) {
                        $(this).val('');
                        column.search('').draw();
                    });
                } else if (column.dataSrc() === 'documento_tipo') {
                    let select = generarSelectGeneral('origen_movimiento', [{ value:'compra', text:'compra'}, { value:'venta_albaran', text:'venta_albaran'}, { value:'albaran_compra', text:'albaran_compra'}, { value:'factura', text:'factura'}]);
                    $(select).appendTo(header).on('change', function() {
                        var val = $(this).val();
                        column.search(val ? '^' + val + '$' : '', true, false).draw();
                    });
                } else if (column.dataSrc() === 'Tipo') {
                    let select = generarSelectGeneral('tipo', [{ value:'0', text:'Aumentar'}, { value:'1', text:'Disminuir'}]);
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
        }
        options.columns = [
            {
                data: 'Tipo',
                searchable: true,
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('data-label', 'Tipo').css('text-align', 'center').addClass('p-2');
                },
                render: function(data, type, row) {

                    if (row.Tipo == "Aumentar") {
                        return `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trending-up"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg> Aumentar`;
                    } else if (row.Tipo == "Disminuir") {
                        return `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trending-down"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline><polyline points="17 18 23 18 23 12"></polyline></svg> Disminuir `;
                    } else {
                        return `-`;
                    }

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
                data: 'documento_tipo',
                searchable: true,
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('data-label', 'documento_tipo').addClass('p-1').css('text-align', 'left');
                },
                render: function(data, type, row) {

                    return `<strong> ${row.documento_tipo}</strong>`;

                }
            },


            {
                data: 'documento_fk',
                searchable: true,
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('data-label', 'documento_fk');
                    $(td).addClass('text-left').addClass('py-0 px-3');
                },
                render: function(data, type, row) {

                    if (row.documento_tipo == null) {
                        return `-`;
                    } else if (row.documento_tipo == "factura") {
                        return `
                        <a target="blank" style="color:#4361ee"  href="${ENLACE_WEB}factura/${row.documento_fk}">
                           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-map-pin"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>  Factura  ${row.detalle_documento}
                        </a>
                        `;

                    } else if (row.documento_tipo == "compra") {
                        return `
                        <a target="blank" style="color:#4361ee"  href="${ENLACE_WEB}compra/${row.documento_fk}">
                            <i class="fa-regular fa-file-lines"></i> Compra ${row.detalle_documento}
                        </a>
                        `;

                    } else if (row.documento_tipo == "albaran_compra") {
                        return `
                        <a target="blank" style="color:#4361ee"  href="${ENLACE_WEB}ver_albaran/${row.documento_fk}">
                            <i class="fa-regular fa-file"></i> Albarán Compra ${row.detalle_documento}
                        </a>
                        `;



                    } else if (row.documento_tipo == "venta_albaran") {
                        return `
                        <a target="blank" style="color:#4361ee"  href="${ENLACE_WEB}albaran_venta/${row.documento_fk}">
                            <i class="fa-regular fa-file"></i> Albarán Venta ${row.detalle_documento}
                        </a>
                        `;



                    } else {

                        return ``;
                    }



                }
            },


            {
                data: 'fecha',
                searchable: true,
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('data-label', 'fecha').addClass('p-1');
                }
            },
            {
                data: 'Hora',
                searchable: true,
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('data-label', 'Hora').addClass('p-1');
                }
            },
            {
                data: 'Usuario',
                searchable: true,
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('data-label', 'Usuario').addClass('p-1').css('text-align', 'left');
                },
                render: function(data, type, row) {
                    return `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> ${row.Usuario} `;
                }
            },
            {
                data: 'Motivo',
                searchable: true,
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('data-label', 'Motivo').addClass('p-1');
                }
            }
        ]
        vtabla = $('#style-3').DataTable(options)

        setting_table(vtabla, [])



        
    }
</script>