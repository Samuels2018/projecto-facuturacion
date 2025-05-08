<?php
include_once(ENLACE_SERVIDOR . "mod_documentos_mercantiles/tpl/header_document.php");
?>
<div class="middle-content container-fluid p-0">
    <!-- BREADCRUMB -->
    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Albaranes Compra </a></li>
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

                <!-- <div class="widget-content widget-content-area"> -->
                <div class="widget-content widget-content-area br-8">
                    <form id="formulario">
                        <div class="table-responsive">
                            <!-- <table class="table table-bordered" id="style-3"> -->
                            <table id="style-3" class="table style-3 dt-table-hover p-0">
                                <thead>
                                    <tr>
                                        <th class="text-left" scope="col"></th>
                                        <th class="text-left" scope="col">Albarán</th>
                                        <th class="text-left" scope="col">Nombre Proveedor</th>
                                        <th class="text-left" scope="col">Fecha</th>
                                        <th class="text-left" scope="col">Usuario</th>
                                        <th class="text-left" scope="col">Base</th>
                                        <th class="text-left" scope="col">Impuesto</th>
                                        <th class="text-left" scope="col">Total</th>
                                        <th class="text-left" scope="col">Compras</th>
                                        <th class="text-left" scope="col">Estado</th>
                                        <th class="text-left" scope="col">Acciones</th>
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

<!-- Scripts -->
<?php include_once(ENLACE_SERVIDOR . "mod_documentos_mercantiles/tpl/footer_document.php"); ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>
    function cargar_listado_documento() {
        const ajaxoption = {
            url: '<?php echo ENLACE_WEB; ?>mod_europa_albaran_compra/ajax/albaranes.compra.listado.ajax.php',
            type: 'GET'
        }
        let options = config_datatable(ajaxoption);

        options.initComplete = function() {
            var api = this.api();
            api.columns().every(function() {
                var column = this;
                var header = $(column.header());
                var headerText = header.text();

                header.empty();
                // Condición para no añadir input en la columna "Acciones"
                if (headerText === 'Acciones') {
                    // No agregar nada, dejar el contenedor vacío
                    return;
                }
                // Crea el contenedor del input o select
                // Crea el contenedor principal
                const headerTextContainer = $('<div>').appendTo(header);
                $('<span>').text(headerText).appendTo(headerTextContainer);

                // Contenedor para el input/select
                const inputContainer = $('<div>').css({
                    'width': '100%',
                    'height': '100%',
                    'position': 'relative'
                }).appendTo(header);

                if (headerText === 'Fecha') {
                    // Input de fecha con placeholder
                    var dateInput = $('<input type="text" class="form-control">')
                        .attr('placeholder', 'Selecciona rango')
                        .attr('titulo', headerText)
                        .appendTo(inputContainer)
                        .on('apply.daterangepicker', function(ev, picker) {
                            var startDate = picker.startDate.format('YYYY-MM-DD');
                            var endDate = picker.endDate.format('YYYY-MM-DD');
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
                        autoUpdateInput: false
                    });

                    // Actualizar campo cuando se selecciona rango
                    dateInput.on('apply.daterangepicker', function(ev, picker) {
                        $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
                    });

                    // Limpiar campo al cancelar
                    dateInput.on('cancel.daterangepicker', function(ev, picker) {
                        $(this).val('');
                        column.search('').draw();
                    });

               
                } else if (headerText === 'Estado') {
                    // Agrega un spinner mientras se cargan los estados
                    $('<div id="spinnerloading" titulo="Estado">Loading...</div>').appendTo(header);

                    // Llama a obtenerEstados para cargar datos
                    const estados = $.ajax({
                        url: "<?php echo ENLACE_WEB; ?>mod_europa_albaran_compra/json/obtener_estado.php",
                        type: 'POST',
                        dataType: 'json'
                    })
                    estados.done(function(data) {
                        $('#spinnerloading').remove()
                        var select = $('<select>')
                            .addClass('form-control')
                            .attr('titulo', headerText)
                            .append($('<option>').val('').text('Todos')) // Opción por defecto
                            .appendTo(header);

                        if (data && data.data) {
                            data.data.forEach(function(estado) {
                                select.append($('<option>').val(estado.rowid).text(estado.etiqueta));
                            });
                        }

                        select.on('change', function() {
                            var val = $(this).val();
                            column.search(val ? '^' + val + '$' : '', true, false).draw();
                        });
                    }).fail(function() {
                        $('#spinnerloading').text('Error al cargar')
                    });
                } else {
                    // Input de texto para otras columnas con placeholder
                    $('<input type="text" class="form-control">')
                        .css({
                            'min-width': '70px',
                            'width': '100%'
                        })
                        .attr('placeholder', headerText) // Usar el texto del encabezado como placeholder
                        .attr('titulo', headerText)
                        .appendTo(inputContainer)
                        .on('input', function() {
                            var val = $.fn.dataTable.util.escapeRegex($(this).val());
                            column
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                        });
                }
            });
        }
        options.columns = [{
                data: 'id', // O cualquier dato que identifique la fila
                orderable: false,
                searchable: false,
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('data-label', 'Seleccionar');
                    $(td).addClass('text-center').addClass('py-0 px-3');
                },
                render: function(data, type, row) {
                    // Condición para que el checkbox se muestre solo si el estado es 'Activo' o 'Facturado parcialmente' (4)
                    if (parseInt(row.estado) === 1 || (parseInt(row.estado) === 4 ) ) {
                        return `
                            <input type="checkbox" class="row-checkbox" value="${row.id}" />
                        `;
                    } else {
                        return ''; // No muestra nada si no es 'Pendiente'
                    }
                }
            },

            {
                data: 'referencia',
                searchable: true,
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('data-label', 'referencia');
                    $(td).addClass('text-left').addClass('py-0 px-3');
                },
                render: function(data, type, row) {
                    return `
                        <a href="${ENLACE_WEB}ver_albaran/${row.id}">
                            <i class="fa-regular fa-file-lines"></i> ${row.referencia}
                        </a>
                        `;
                }
            },
            {
                data: 'cliente_tercero',
                searchable: true,
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('data-label', 'cliente_tercero');
                    $(td).addClass('text-left').addClass('py-0 px-3');
                },
                render: function(data, type, row) {

                    if (row.tercero == null) {
                        return `<strong >Proveedor Gen&eacute;rico</strong>`;
                    } else {

                        return `
                        <a target="blank" style="color:#4361ee"  href="${ENLACE_WEB}clientes_editar/${row.tercero}">
                            <i class="fa-regular fa-file-lines"></i> ${row.cliente_tercero}
                        </a>
                        `;
                    }


                    // return row.cliente_tercero;
                }
            },
            {
                data: 'fecha',
                searchable: true,
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('data-label', 'fecha');
                    $(td).addClass('text-left').addClass('py-0 px-3');
                },
                render: function(data, type, row) {
                    return row.fecha;
                }
            },
            {
                data: 'usuario_crear',
                searchable: true,
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('data-label', 'usuario_crear');
                    $(td).addClass('text-left').addClass('py-0 px-3');
                },
                render: function(data, type, row) {

                    return ` <div class="avatar-chip mb-2 me-4 position-relative" >
                                <img alt="avatar" src="${row.avatar}" />
                                <span class="text" >${data}</span>
                            </div>`

                }
            },
            {
                data: 'subtotal_pre_retencion',
                searchable: true,
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('data-label', 'subtotal_pre_retencion');
                    $(td).addClass('text-left').addClass('py-0 px-3');
                },
                render: function(data, type, row) {
                    return row.subtotal_pre_retencion;
                }
            },
            {
                data: 'impuesto_iva',
                searchable: true,
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('data-label', 'impuesto_iva');
                    $(td).addClass('text-left').addClass('py-0 px-3');
                },
                render: function(data, type, row) {
                    return row.impuesto_iva;
                }
            },
            {
                data: 'total',
                searchable: true,
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('data-label', 'total');
                    $(td).css('text-align', 'left').addClass('py-0 px-3').addClass('active');
                }
            }, {
                data: 'total_facturas_compras',
                searchable: true,
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('data-label', 'Compras').addClass('py-0 px-3 text-right');

                },
                render: function(data, type, row) {



                    icono = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-bag">
                <path d="M6 2l1.5 12h9L18 2H6z"></path>
                <path d="M1 10h22v12H1V10zm5 3v7m12-7v7"></path>
                <circle cx="12" cy="9" r="1"></circle>
            </svg>`;

                    if (row.total_facturas_compras == 0) {
                        return ``;
                    } else if (row.total_facturas_compras == 1) {
                        return `<a href ='<?php echo ENLACE_WEB; ?>compra/${row.factura_compra_unica_id}' ><span class="p-2 badge badge-light-success"> ${icono} ${row.factura_compra_unica_ref} </span></a>`;
                    } else if (row.total_facturas_compras > 1) {
                        return `<span class="p-2 badge badge-light-warning">Varias</span>`;
                    }

                    return ` <div> 
                               ${row.total_facturas_compras} 
                            </div>`



                }
            },
            {
                data: 'estado_txt',
                searchable: true,
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('data-label', 'Compras').addClass('py-0 px-3 text-right');


                    if (rowData.estado_pagada == 1) {
                        $(td).addClass('text-success');
                    }
                },
                render: function(data, type, row) {
                    return `<a href ='<?php echo ENLACE_WEB; ?>ver_albaran/${row.id}' ><span class="p-2 badge badge-light-${row.estado_class}">${row.estado_txt} </span></a>`;



                }
            },
            {
                data: null, // No se necesita un campo de datos específico
                searchable: false,
                orderable: false,
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('data-label', 'Acciones').addClass('py-0 px-3 text-center');
                },
                render: function(data, type, row) {
                    const isBorrador = row.estado_txt === "Borrador";
                    const archivoExistente = row.archivo_existente; // Validar si el archivo XML existe


                    // Estilo para enlaces deshabilitados
                    const disabledStyle = 'color: gray; cursor: not-allowed;pointer-events: none;';
                    const activeStyle = 'color: blue; cursor: pointer;';

                    // Elige el estilo según el estado
                    const linkStyle = isBorrador ? disabledStyle : activeStyle;


                    // Enlaces de acciones
                    let htmlAcciones = `
                            <div style="display: flex; justify-content: space-evenly;">
                            <a href="javascript:void(0);" onclick="${isBorrador ? 'return false;' : `generarPdf(${row.id}, 'Albaran_compra', 'Albaran Compra-${row.referencia}')`}" style="${linkStyle}" title="Descargar Albarán">
                                <i class="fa-solid fa-file-pdf fa-xl"></i>
                            </a>
                           `
                    if (isBorrador) {
                        htmlAcciones +=
                            `
                            <a href="#" onclick="confirmar_eliminar_documento_mercantil(${row.id}, 'Albaran_compra', '#style-3'); " id="eliminar" title="Eliminar" style="color:red">
                                <i class="fa fa-trash fa-xl" style="opacity: 0.3;"></i>
                            </a>`;
                    }
                    htmlAcciones += `</div>`;
                    return htmlAcciones;
                }
            }

            // Agrega aquí las columnas adicionales que necesites
        ]
        vtabla = $('#style-3').DataTable(options)

        let newButton = $('<button>')
            .html('<i class="fa-solid fa-plus"></i> Nueva Albarán Compra')
            .addClass('btn btn-primary')
            .attr("type", "button")
            .on('click', function() {
                window.location = '<?= ENLACE_WEB ?>nuevo_albaran'
            });

        let generateInvoiceButton = $('<button>')
            .html('<i class="fa-solid fa-file-invoice"></i> Generar Factura')
            .addClass('btn btn-success generar_factura')
            .attr("type", "button")
            .attr("disabled", true);



        generateInvoiceButton.on('click', function() {
            let selectedRows = [];

            // Recorre los checkboxes seleccionados para obtener las filas correspondientes
            $('.row-checkbox:checked').each(function() {
                let rowId = $(this).val();
                let rowData = vtabla.row($(this).closest('tr')).data();
                selectedRows.push({
                    id: rowData.id,
                    proveedor: rowData.cliente_tercero || 'Proveedor Genérico',
                    proveedor_id: rowData.tercero || 0,
                    referencia: rowData.referencia
                });
            });

            if (selectedRows.length > 0) {
                let proveedores = {};
                selectedRows.forEach(row => {
                    if (!proveedores[row.proveedor_id]) {
                        proveedores[row.proveedor_id] = [];
                    }
                    proveedores[row.proveedor_id].push({
                        proveedor: row.proveedor,
                        id: row.id,
                        referencia: row.referencia
                    });
                });

                let mensajeConfirmacion = 'Estás a punto de generar compras para los siguientes proveedores:<br>';
                
                let html_proveedor = ``; let html_albaranes = ``;
                for (let proveedor in proveedores) {
                    if(html_proveedor == ''){
                        html_proveedor = proveedores[proveedor][0].proveedor;
                    }
                    for(let itemproveedor in proveedores[proveedor]){
                        html_albaranes += proveedores[proveedor][itemproveedor].referencia+`<br/>`;
                    }                    
                    mensajeConfirmacion += `<tr><td>${html_albaranes}</td><td><strong>${html_proveedor}</strong></td></tr>`;                    
                    html_proveedor = ''; html_albaranes = '';
                }
                mensajeConfirmacion = '<table class="table table-bordered"><thead><tr><th class="text-center"><b>Albaran(es) de origen</b></th><th class="text-center"><b>Compra para</b></th></tr></thead>' + mensajeConfirmacion + '</table>';

                Swal.fire({
                    title: 'Confirmar generación de compras',
                    html: mensajeConfirmacion,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Generar compra',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Enviar datos por AJAX
                        $.ajax({
                            url: '<?= ENLACE_WEB ?>mod_europa_albaran_compra/ajax/generar.facturas.masivas.ajax.php',
                            method: 'POST',
                            // data: { ids: selectedRows.map(row => row.id) },
                            data: {
                                data: JSON.stringify(proveedores)
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        title: 'Éxito',
                                        text: 'Las compras se han generado exitosamente.',
                                        icon: 'success',
                                        confirmButtonText: 'OK'
                                    }).then(() => {
                                        // Recargar la tabla
                                        vtabla.ajax.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Error',
                                        text: 'Ocurrió un error al generar las compras.',
                                        icon: 'error',
                                        confirmButtonText: 'OK'
                                    });
                                }
                            },
                            error: function() {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Ocurrió un problema con la solicitud.',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        });
                    }
                });
            } else {
                Swal.fire({
                    title: 'Sin selección',
                    text: 'No hay elementos seleccionados.',
                    icon: 'info',
                    confirmButtonText: 'OK'
                });
            }
        });




        setting_table(vtabla, [newButton, generateInvoiceButton]);

        // Detectar cambios en los checkboxes para habilitar/deshabilitar el botón
        $('#style-3').on('change', '.row-checkbox', function() {
            let anyChecked = $('.row-checkbox:checked').length > 0;
            if (anyChecked) {
                generateInvoiceButton.removeAttr('disabled'); // Habilitar el botón
            } else {
                generateInvoiceButton.attr('disabled', true); // Deshabilitar el botón
            }
        });

    }



    $(document).ready(function() {
        cargar_listado_documento();

        // Desactivar todos los elementos del menú
        $(".menu").removeClass('active');
        $(".albaranes").addClass('active');





    });
</script>