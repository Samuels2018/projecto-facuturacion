<?php
include_once(ENLACE_SERVIDOR . "mod_documentos_mercantiles/tpl/header_document.php");
?>

<div class="middle-content container-fluid p-0">
    <!-- BREADCRUMB -->
    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Pedidos</a></li>
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
                
                 <div class="widget-content widget-content-area br-8">
                    <form id="formulario">
                        <div class="table-responsive">
                             <table id="style-3" class="table style-3 dt-table-hover p-0">
                                <thead>
                                <tr>
                                         <th class="text-left" scope="col">Ver Pedido</th>
                                        <th class="text-left" scope="col">Nombre Cliente</th>
                                        <th class="text-left" scope="col">Fecha</th>
                                        <th class="text-left" scope="col">Usuario</th>
                                        <th class="text-left" scope="col">Base</th>
                                        <th class="text-left" scope="col">Impuesto</th>
                                        <th class="text-left" scope="col">Total</th>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<?php include_once(ENLACE_SERVIDOR . "mod_documentos_mercantiles/tpl/footer_document.php"); ?>
<script>
    function cargar_listado_documento() {
        const ajaxoption = {
            url: '<?php echo ENLACE_WEB; ?>mod_europa_pedido/ajax/pedido.listado.ajax.php',
            type: 'GET'
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
            $('.dataTables_length').parent().removeClass('col-sm-6').addClass('col-sm-12');
        }
        options.columns = [ 
            {
                data: 'referencia',
                searchable: true,
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('data-label', 'referencia');
                    $(td).addClass('text-left').addClass('py-0 px-3');
                },
                render: function(data, type, row) {
                    return `
                    <a href="${ENLACE_WEB}pedido/${row.id}">
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
                        return `<strong >Cliente Gen&eacute;rico</strong>`;
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
            },
            {
                data: 'estado_txt',
                searchable: true,
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('data-label', 'Compras').addClass('py-0 px-3 text-right');
                },
                render: function(data, type, row) {
                    console.log('Renderizando estado_txt:', data, row.estado_txt);
                    return `<a href ='<?php echo ENLACE_WEB; ?>pedido/${row.id}' ><span class="p-2 badge badge-light-${row.color}">${row.estado_txt} </span></a>`;
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
                    const isBorrador = row.estado_final === "Borrador";
                    const archivoExistente = row.archivo_existente; // Validar si el archivo XML existe
 

                    // Estilo para enlaces deshabilitados
                    const disabledStyle = 'color: gray; cursor: not-allowed;pointer-events: none;';
                    const activeStyle = 'color: blue; cursor: pointer;';

                    // Elige el estilo según el estado
                    const linkStyle = isBorrador ? disabledStyle : activeStyle;
 

                    // Enlaces de acciones
                    let htmlAcciones = `
                            <div style="display: flex; justify-content: space-evenly;">
                            <a href="javascript:void(0);" onclick="${isBorrador ? 'return false;' : `generarPdf(${row.id}, 'pedido', 'Pedido-${row.referencia}')`}" style="${linkStyle}" title="Descargar Pedido">
                                <i class="fa-solid fa-file-pdf fa-xl"></i>
                            </a>`
                    if (isBorrador) {
                        htmlAcciones +=
                            `
                            <a href="#" onclick="confirmar_eliminar_documento_mercantil(${row.id},  'Pedido' ,'#style-3'); " title="Eliminar" style="color:red">
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
            .html('<i class="fa-solid fa-plus"></i> Nuevo Pedido')
            .addClass('btn btn-primary')
            .attr("type", "button")
            .on('click', function() {
                window.location = '<?= ENLACE_WEB ?>nuevo_pedido'
            });
        setting_table(vtabla, [newButton])
    }

    $(document).ready(function() {
        cargar_listado_documento();

        // Desactivar todos los elementos del menú
        $(".menu").removeClass('active');
        $(".pedido_europa").addClass('active');
    });
</script>