<?php
include_once(ENLACE_SERVIDOR . "mod_documentos_mercantiles/tpl/header_document.php");
?>


<div class="middle-content container-fluid p-0">

    <!-- BREADCRUMB -->
    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Oportunidades</a></li>
                <li class="breadcrumb-item active" aria-current="page">Listado</li>
            </ol>
        </nav>
    </div>
    <!-- /BREADCRUMB -->


    <!-- CONTENT AREA -->
    <div class="row layout-top-spacing">


        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
            <div class="row my-4">
                <div class="col-md-3">
                    <!-- <a href="<?php echo ENLACE_WEB ?>contactos_crm_nuevo" class="btn btn-primary">Nuevo Oportunidad</a> -->
                </div>

            </div>
            <div class="widget-content widget-content-area br-8">

                <form id="formulario">
                    <!-- Tabla  -->
                    <div class="table-responsive">
                        <table id="style-3" class="table style-3 dt-table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">Referencia</th>
                                    <th scope="col">Etiqueta</th>
                                    <th scope="col">Nombre Cliente</th>
                                    <th scope="col">Contacto</th>
                                    <th scope="col">Usuario asignado</th>
                                    <th scope="col">Fecha de creación</th>
                                    <th scope="col">Fecha de Cierre</th>
                                    <th scope="col">Importe</th>
                                    <th scope="col">Estado</th>
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

<?php include_once(ENLACE_SERVIDOR . "mod_documentos_mercantiles/tpl/footer_document.php"); ?>

<!-- Scripts -->
<script>
    $(document).ready(function() {

           
       const urlParams = new URLSearchParams(window.location.search);
       const contactoId = urlParams.get('contacto_id');

    if (contactoId) {
        // Aquí puedes filtrar la tabla o realizar AJAX usando contactoId para cargar sólo sus oportunidades
        cargar_tabla__crm_terceros(contactoId);
        /* get from local storage nombre_contacto and set in nombre */
        
      
        


        
    } else {
        // Cargar tabla normalmente
        cargar_tabla__crm_terceros();
    }
       
        $(".menu").removeClass('active');
        $(".mod_crm").addClass('active');
        $(".mod_crm > .submenu").addClass('show');
        $("#listado_oportunidades").addClass('active');
    });
</script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>


<script>
    function cargar_tabla__crm_terceros(contactId = null) {

        const ajaxoption = {
            url: '<?php echo ENLACE_WEB; ?>mod_crm/ajax/listado_oportunidad.ajax.php',
            type: 'GET',
            data: function(d){
                if (contactId) {
                    d.contacto_id = contactId;
                   
                    
                }
            }
        }
        let options = config_datatable(ajaxoption);
        options.initComplete = function() {
            // Configuración de la búsqueda y otros ajustes aquí
            this.api().columns().every(function(index) {
                var column = this;
                var header = $(column.header());
                var headerText = header.text(); // Guarda el texto original del encabezado
                header.empty(); // Limpia el encabezado
                var inputId = 'search-input-' + index;

                // Crea un contenedor div para el texto del encabezado
                var headerTextContainer = $('<div>').appendTo(header);
                $('<span>').text(headerText).appendTo(headerTextContainer);

                // Crea un contenedor div para el input/select
                var inputContainer = $('<div>').appendTo(header);
                var inputId = 'search-input-' + index;
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
                } else if (column.dataSrc() === 'fecha_cierre') {

                    // Crear el campo de entrada para el rango de fechas
                    var dateInput = $('<input type="text" id="date-range-2" class="form-control" placeholder="Selecciona un rango de fechas">')
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


                } else if (column.dataSrc() === 'Tipo Persona') {
                    let select = generarSelectTipo('tipo_persona');
                    $(select).appendTo(header).on('change', function() {
                        var val = $(this).val();
                        column.search(val ? '^' + val + '$' : '', true, false).draw();
                    });
                } else {
                    // Para los campos de texto
                    var input = $('<input type="text" id="' + inputId + '" class="form-control">')
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

            let nombre_contacto = localStorage.getItem('nombre_contacto');
            let input = $('#search-input-3').val(nombre_contacto);
            $('#search-input-3').trigger('input');
        }
        options.columns = [
            
            {
                data: 'Oportunidad',
                searchable: true,
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('data-label', 'Referencia');
                },
                render: function(data, type, row) {
                    texto = row.length > 20 ? row = row.substring(0, 20) + '...' : row = row;
                    return `<a href="${ENLACE_WEB}ver_oportunidad/${row.ID}"><span title="${row.Oportunidad}">${row.Oportunidad}</span></a>`
                }
            },
            {
                data: 'Referencia',
                searchable: true,
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('data-label', 'Referencia');
                },
                render: function(row, type, row) {
                    texto = row.length > 20 ? row = row.substring(0, 20) + '...' : row = row;
                    return `<a href="${ENLACE_WEB}ver_oportunidad/${row.ID}"><span title="${row.Referencia}">${row.Referencia}</span></a>`
                }
            },
            {
                data: 'Nombre Tercero',
                searchable: true,
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('data-label', 'Tercero');
                },
                render: function(data, type, row) {
                    texto = row.length > 20 ? row = row.substring(0, 20) + '...' : row = data;
                    return `<a href="${ENLACE_WEB}ver_oportunidad/${row.ID}"><span title="${data}">${texto}</span>`
                }
            },
            {
                data: 'Nombre Contacto',
                searchable: true,
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('data-label', 'Contacto');
                },
                render: function(data, type, row) {
                    return `<a href="${ENLACE_WEB}ver_oportunidad/${row.ID}"><span class="badge badge-light-success">${row["Nombre Contacto"]}</span></a>`;
                }
            },
            {
                data: 'Agente Nombre',
                searchable: true,
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('data-label', 'Agente');
                },
                render: function(data, type, row) {

                    if (data != null) {
                        return ` <div class="media">
                    <div class="avatar me-2">
                        <img alt="avatar" src="${row.avatar}" class="rounded-circle" />
                    </div>
                    <div class="media-body align-self-center">
                        <span>${data}</span>
                    </div>
                </div>`
                    } else {
                        return '-';
                    }

                }
            },
            {
                data: 'fecha',
                searchable: true,
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('data-label', 'fecha');
                },
                render: function(data, type, row) {
                    return `<a href="${ENLACE_WEB}ver_oportunidad/${row.ID}"><span title="${row.fecha}">${row.fecha}</span></a>`
                }
            },
            {
                data: 'fecha_cierre',
                searchable: true,
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('data-label', 'fecha_cierre');
                },
                render: function(data, type, row) {
                    return `<a href="${ENLACE_WEB}ver_oportunidad/${row.ID}"><span title="${row.fecha_cierre}">${row.fecha_cierre}</span></a>`
                }
            },
            {
                data: 'Importe',
                searchable: true,
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('data-label', 'Importe');
                    $(td).addClass('text-center').addClass('p-1 ').addClass('monto');
                }
            },
            {
                data: 'estatus_detalle_funnel',
                searchable: true,
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('data-label', 'Detalle Funnel');
                },
                render: function(data, type, row) {
                    return `<span class="badge badge-light-${row.estilo_detalle_funnel}">${row.estatus_detalle_funnel}</span>`;
                }
            },

        ]

        vtabla = $('#style-3').DataTable(options)

        let newButton = $('<button>')
            .html('<i class="fa-solid fa-plus"></i> Nueva oportunidad')
            .addClass('btn btn-primary')
            .attr("type", "button")
            .on('click', function() {
                window.location = '<?= ENLACE_WEB ?>nueva_oportunidad'
            });
        setting_table(vtabla, [newButton],true)
    }


</script>