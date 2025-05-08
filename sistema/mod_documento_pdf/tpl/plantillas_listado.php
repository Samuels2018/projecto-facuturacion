<?php
include_once(ENLACE_SERVIDOR . "mod_documentos_mercantiles/tpl/header_document.php");
?>

<div class="middle-content container-fluid p-0">
    <!-- BREADCRUMB -->
    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Plantillas</a></li>
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
                                        <th class="text-center" scope="col">Título</th>
                                        <th class="text-center" scope="col">HTML</th>
                                        <th class="text-center" scope="col">ESTILO</th>
                                        <th class="text-center" scope="col">Tipo</th>
                                        <th class="text-center" scope="col">Defecto</th>
                                        <th class="text-center" scope="col">Estado</th>
                                        <th class="text-center" scope="col">Acciones</th>
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

<div class="modal fade" id="nueva_plantilla" tabindex="-1" role="dialog" aria-labelledby="nueva_plantilla_label" aria-hidden="true">
</div>
<div class="modal fade" id="preview_plantilla" tabindex="-1" role="dialog" aria-labelledby="preview_plantilla_label" aria-hidden="true">
</div>

<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/dompurify/2.3.3/purify.min.js"></script>

<?php include_once(ENLACE_SERVIDOR . "mod_documentos_mercantiles/tpl/footer_document.php"); ?>
<script>
    function cargar_listado_documento() {
        const ajaxoption = {
            url: '<?php echo ENLACE_WEB; ?>mod_documento_pdf/ajax/plantilla.listado.ajax.php',
            type: 'GET'
        }
        let options = config_datatable(ajaxoption);

        options.initComplete = function() {
            var api = this.api();
            api.columns().every(function(col) {
                var column = this;
                var header = $(column.header());
                var headerText = header.text();
                header.empty(); // Limpia el encabezado
                // Crea un contenedor div para el texto del encabezado
                var headerTextContainer = $('<div class="text-center" >').appendTo(header);
                $('<span>').text(headerText).appendTo(headerTextContainer);

                // Lista de índices de columnas donde NO quieres mostrar el input de búsqueda
                var excludedColumns = [1, 2, 5]; // Por ejemplo, para excluir las columnas 2, 4 y 6

                // Crea un contenedor div para el input, independientemente de si está oculto o visible
                var inputContainer = $('<div>').css({
                    'width': '100%', // Asegura que el contenedor ocupe todo el espacio disponible
                    'height': '100%', // Asegura que el contenedor ocupe todo el espacio disponible
                    'position': 'relative' // Posiciona el contenedor de manera relativa para que el input se posicione correctamente dentro de él

                }).appendTo(header);

                // Verifica si la columna actual está en la lista de excluidas
                if ($.inArray(col, excludedColumns) >= 0) {
                    // Para los campos de texto
                    return '';
                } else if (column.dataSrc() === 'estado_final') {
                    let select = generarSelectEstatus('activo');

                    $(select).appendTo(header).on('change', function() {
                        var val = ($(this).val());

                        column.search(val ? '^' + val + '$' : '', true, false).draw();
                    });
                } else if (column.dataSrc() === 'tipo') {

                     // Agrega un spinner mientras se cargan los estados
                     $('<div id="spinnerloading" titulo="Estado">Loading...</div>').appendTo(header);

                    // Llama a obtenerEstados para cargar datos
                    const tipos_documentos = $.ajax({
                        url: "<?php echo ENLACE_WEB; ?>mod_utilidad/json/tipos_documentos.php",
                        type: 'POST',
                        dataType: 'json'
                    });
                    tipos_documentos.done(function(data) {
                        $('#spinnerloading').remove()
                        var select = $('<select>')
                            .addClass('form-control')
                            .attr('titulo', headerText)
                            .append($('<option>').val('').text('Todos')) // Opción por defecto
                            .appendTo(header);

                        if (data  ) {
                            data.data.forEach(function(tipo) { 
                                select.append($('<option>').val(tipo.tabla).text(tipo.descripcion));
                            });
                        }
                        select.on('change', function() {
                            var val = $(this).val();
                            column.search(val ? '^' + val + '$' : '', true, false).draw();
                        });
                    }).fail(function() {
                        $('#spinnerloading').text('Error al cargar')
                    });                   


                }else {
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
        options.columns = [{
                data: 'titulo',
                searchable: true,
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('data-label', 'titulo');
                    $(td).addClass('text-left').addClass('py-0 px-3');
                },
                render: function(data, type, row) {
                    return `
                    <a href="#" onclick="ver_plantilla(${row.id})">
                        ${data}
                    </a>
                    `;
                }
            },
            {
                data: 'plantilla_html',
                searchable: false,
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('data-label', 'plantilla_html');
                    $(td).addClass('text-left').addClass('py-0 px-3');
                },
                render: function(data, type, row) {
                    return `
                    <a href="#" onclick="ver_plantilla(${row.id})">
                        Click para ver más detalles
                    </a>
                    `;
                }
            },
            {
                data: 'plantilla_css',
                searchable: false,
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('data-label', 'plantilla_css');
                    $(td).addClass('text-left').addClass('py-0 px-3');
                },
                render: function(data, type, row) {
                    return `
                    <a href="#" onclick="ver_plantilla(${row.id})">
                        Click para ver más detalles
                    </a>
                    `;
                }
            },
            {
                data: 'tipo',
                searchable: false,
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('data-label', 'tipo');
                    $(td).addClass('text-left').addClass('py-0 px-3');
                },
                render: function(data, type, row) {
                    return data
                }
            },
            {
                data: 'defecto',
                searchable: false,
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('data-label', 'defecto');
                    $(td).addClass('text-left').addClass('py-0 px-3');
                },
                render: function(data, type, row) {
                    let div = `<div style="display: flex; justify-content: space-evenly;">`
                    if (data == 1) {
                        div += `<i class="fa-solid fa-circle-check fa-lg" style="color: blue;"></i>`
                    } else {
                        div += `<i class="fa-solid fa-ban fa-lg"></i>`
                    }
                    div += `</div>`
                    return div
                }
            },
            {
                data: 'estado_final',
                searchable: true,
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('data-label', 'estado_final');
                    $(td).addClass('text-left').addClass('py-0 px-3');
                },
                render: function(data, type, row) {
                    return `${data}`
                }
            },

            {
                render: function(data, type, row) {
                    // Enlaces de acciones
                    let htmlAcciones = `<div style="display: flex; justify-content: space-evenly;">`
                    htmlAcciones +=
                        `

                            <a href="#" onclick="preview_plantilla(${row.id}); " id="preview" title="Preview" style="color:blue">
                                <i class="fa fa-eye fa-xl" style="opacity: 0.3;"></i>
                            </a>
                            <a href="#" onclick="plantilla_duplicar(${row.id}); " id="duplicar" title="Duplicar" style="color:blue">
                                <i class="fa fa-copy fa-xl" style="opacity: 0.3;"></i>
                            </a>
                            <a href="#" onclick="confirma_eliminar(${row.id}); " id="eliminar" title="Eliminar" style="color:red">
                                <i class="fa fa-trash fa-xl" style="opacity: 0.3;"></i>
                            </a>
                            `;
                    htmlAcciones += `</div>`;
                    return htmlAcciones;
                }
            }
            // Agrega aquí las columnas adicionales que necesites
        ]
        vtabla = $('#style-3').DataTable(options)

        let newButton = $('<button>')
            .html('<i class="fa-solid fa-plus"></i> Nueva plantilla')
            .addClass('btn btn-primary')
            .attr("type", "button")
            .on('click', function() {
                ver_plantilla()
            });

        setting_table(vtabla, [newButton])
    }

    $(document).ready(function() {
        cargar_listado_documento();
        $(".menu").removeClass('active');
    });

    function ver_plantilla(id_plantilla) {
        $.ajax({
            method: "GET",
            url: "<?php echo ENLACE_WEB; ?>mod_documento_pdf/tpl/plantilla_modal.php?fiche=" + id_plantilla
        }).done(function(html) {
            $("#nueva_plantilla").html(html).modal('show');
        });
    }

    function confirma_eliminar($id) {

        // Preparar el mensaje para el snackbar
        var message = "Está seguro(a) que desea eliminar la plantilla?";
        var actionText = "<strong onclick='ocultar_snackbar();' id='cancelar_borrado'>Cancelar</strong> <button onclick='eliminar_plantilla(" + $id + ")' style='margin-left:5px;' id='confirmar_borrado' class='btn btn-danger'>Confirmar</button>";

        // Mostrar el snackbar y definir el callback para el botón de acción
        var snackbar = add_notification({
            text: message,
            width: 'auto',
            duration: 300000,
            actionText: actionText,
        });
    }

    function eliminar_plantilla($id) {
        $.ajax({
            method: "POST",
            url: "<?php echo ENLACE_WEB; ?>mod_documento_pdf/ajax/plantilla_configuracion.ajax.php",
            beforeSend: function(xhr) {},
            data: {
                action: 'borrar_plantilla',
                id: $id
            },
        }).done(function(msg) {
            var data = JSON.parse(msg);
            if (data.exito == 1) {
                add_notification({
                    text: 'Plantilla Eliminado exitosamente',
                    pos: 'top-right',
                    actionTextColor: '#fff',
                    backgroundColor: '#00ab55'
                });
                $("#nueva_plantilla").modal('hide');

                $('#style-3').DataTable().ajax.reload();
            }
        });
    }

    function plantilla_duplicar($id){
        $.ajax({
            method: "POST",
            url: "<?php echo ENLACE_WEB; ?>mod_documento_pdf/ajax/plantilla_configuracion.ajax.php",
            beforeSend: function(xhr) {},
            data: {
                action: 'duplicar_plantilla',
                id: $id
            },
        }).done(function(msg) {
            var data = JSON.parse(msg);
            if (data.exito == 1) {
                add_notification({
                    text: 'Plantilla duplicada exitosamente',
                    pos: 'top-right',
                    actionTextColor: '#fff',
                    backgroundColor: '#00ab55'
                });
                $('#style-3').DataTable().ajax.reload();
            }else{
                add_notification({
                    text: 'Hubieron errores con el duplicado de Plantilla',
                    actionTextColor: '#fff',
                    backgroundColor: '#e7515a',
                });
            }
        });
    }
    
    function preview_plantilla(id_plantilla) {
        $.ajax({
            method: "GET",
            url: "<?php echo ENLACE_WEB; ?>mod_documento_pdf/tpl/plantilla_preview_modal.php?fiche=" + id_plantilla
        }).done(function(html) {
            $("#preview_plantilla").html(html).modal('show');
        });
    }
</script>