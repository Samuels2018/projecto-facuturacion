<!-- Datatable -->
<style type="text/css">
    #style-3_filter {
        display: none !important;
    }

    #style-3_length {
        display: flex;
    }

    #export-buttons-container {
        margin-left: 25px;
    }

    #export-buttons-container button+button {
        margin-left: 15px;
    }

    #columnVisibilityContainer {
        margin-top: 40px !important;
    }

    button.action {
        width: 40% !important;
    }



    /* Ajustar el ancho de las columnas */
    th,
    td {
        white-space: nowrap;
    }

    table.dataTable th,
    table.dataTable td {
        word-wrap: break-word;
        white-space: normal;
    }

    .dataTables_wrapper {
        width: 100%;
        overflow: auto;
    }
</style>
<div class="middle-content container-xxl p-0">

    <!-- BREADCRUMB -->
    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Configuracion Parametros</a></li>
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
                    <a href="#" onclick="ver_configuracion();" class="btn btn-primary">Nueva configuracion</a>
                </div>

            </div>
            <div class="widget-content widget-content-area br-8">

                <form id="formulario">
                    <!-- Tabla  -->
                    <div class="table-responsive">
                        <table id="style-3" class="table style-3 dt-table-hover" style="width:100%">
                            <thead>
                                <tr>

                                    <th scope="col">Configuracion</th>
                                    <th scope="col">Valor</th>
                                    <th style="width: 15%;" scope="col">Estado</th>
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

<!-- MODAL  -->
<div class="modal fade" id="nueva_configuracion" tabindex="-1" role="dialog" aria-labelledby="nueva_configuracion_label" aria-hidden="true">
    <!-- MODAL  -->

</div>
<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/dompurify/2.3.3/purify.min.js"></script>

<script>
    function cargar_tabla_configuracion() {
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
            "dom": estiloPaginado.dom,
            "oLanguage": estiloPaginado.oLanguage,
            "stripeClasses": [],
            'ajax': {
                'url': '<?php echo ENLACE_WEB; ?>mod_parametros/ajax/listado_parametros_ajax.php',
                'Method': 'POST'
            },
            retrieve: true,
            deferRender: true,
            scroller: true,
            responsive: true,
            "initComplete": function() {
                this.api().columns().every(function(col) {
                    var column = this;
                    var header = $(column.header());
                    var headerText = header.text(); // Guarda el texto original del encabezado
                    header.empty(); // Limpia el encabezado
                    // Crea un contenedor div para el texto del encabezado
                    var headerTextContainer = $('<div class="text-center" >').appendTo(header);
                    $('<span>').text(headerText).appendTo(headerTextContainer);

                    // Lista de índices de columnas donde NO quieres mostrar el input de búsqueda
                    var excludedColumns = [3]; // Por ejemplo, para excluir las columnas 2, 4 y 6

                    // Crea un contenedor div para el input, independientemente de si está oculto o visible
                    var inputContainer = $('<div>').css({
                        'width': '100%', // Asegura que el contenedor ocupe todo el espacio disponible
                        'height': '100%', // Asegura que el contenedor ocupe todo el espacio disponible
                        'position': 'relative' // Posiciona el contenedor de manera relativa para que el input se posicione correctamente dentro de él
                    }).appendTo(header);

                    // Verifica si la columna actual está en la lista de excluidas
                    if ($.inArray(col, excludedColumns) === -1) {
                        // Para los campos de texto
                        var input = $('<input type="text" class="form-control">')
                            .appendTo(inputContainer)
                            .attr('placeholder', headerText) // Usar el texto del encabezado como placeholder
                            .attr('titulo', headerText)
                            .on('input', function() { // Cambiado de 'change' a 'input'
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );
                                column
                                    .search(val ? '^' + val + '$' : '', true, false)
                                    .draw();
                            });
                    } else {


                        if (column.dataSrc() === 'estado') {
                            let select = generarSelectEstatus('estado');

                            $(select).appendTo(header)
                                .attr('titulo', headerText)
                                .on('change', function() {
                                    var val = ($(this).val());

                                    column.search(val ? '^' + val + '$' : '', true, false).draw();
                                });
                        }

                    }
                });
            },
            columnDefs: [{
                    width: '20%',
                    targets: 0
                },
                {
                    width: '70%',
                    targets: 1
                },
                {
                    width: '10%',
                    targets: 2
                }
            ],
            'columns': [{
                    data: 'configuracion',
                    searchable: true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).addClass('text-center').attr('data-label', 'configuracion');
                    },
                    render: function(data, type, row) {
                        return `<a onclick="ver_configuracion(${row.ID});" href="#">${data}</a>`
                    }

                },
                {
                    data: 'valor',
                    searchable: true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).addClass('text-left').attr('data-label', 'valor');
                    },
                    render: function(data, type, row) {
                        let sanitizedData = DOMPurify.sanitize(data);
                        if (sanitizedData.length > 100) {
                            sanitizedData = sanitizedData.substring(0, 100) + '...';
                        }
                        return `<a onclick="ver_configuracion(${row.ID});" href="#">${sanitizedData}</a>`;
                    }

                },
                {
                    data: 'estado',
                    searchable: true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).addClass('text-center').attr('data-label', 'estado');
                    },
                    render: function(data, type, row) {
                        if (data == 1) {
                            return '<span class="shadow-none badge badge-primary">Activo</span>';
                        } else if (data != 1) {
                            return '<span class="shadow-none badge badge-danger">Inactivo</span>';
                        }
                    }
                }


            ]
        });


        // Cargar configuración guardada de columnas
        loadColumnVisibility(vtabla);


        // Crear el div que contendrá los checkboxes
        var columnVisibilityContainer = $('<div>')
            .attr('id', 'columnVisibilityContainer')
            .css({
                display: 'none',
                position: 'absolute',
                right: '0',
                backgroundColor: '#f9f9f9',
                border: '1px solid #ccc',
                padding: '10px',
                zIndex: '1000',
            });

        // Crear checkboxes para cada columna dentro del div
        vtabla.columns().every(function(index) {
            var column = this;
            var checkbox = $('<input type="checkbox">')
                .val(index)
                .prop('checked', column.visible())
                .on('change', function() {
                    var columnIndex = $(this).val();
                    var column = vtabla.column(columnIndex);
                    column.visible(!column.visible());
                    saveColumnVisibility(vtabla);
                });

            var label = $('<label>')
                .css('display', 'block')
                .text($(column.header()).text())
                .prepend(checkbox);

            columnVisibilityContainer.append(label);
        });

        // Añadir el icono y el div al DOM, justo antes de la tabla

        $('#style-3_wrapper').prepend(columnVisibilityContainer);





    }


    // Guardar la visibilidad de las columnas en localStorage
    function saveColumnVisibility(table) {
        var columnVisibility = [];
        table.columns().every(function(index) {
            columnVisibility.push(this.visible());
        });
        localStorage.setItem('columnVisibilityContacto', JSON.stringify(columnVisibility));
    }

    // Cargar la visibilidad de las columnas desde localStorage
    function loadColumnVisibility(table) {
        var columnVisibility = JSON.parse(localStorage.getItem('columnVisibilityContacto'));
        if (columnVisibility) {
            table.columns().every(function(index) {
                this.visible(columnVisibility[index]);
            });
        }
    }
</script>

<script>
    $(document).ready(function() {

        cargar_tabla_configuracion();

        // Desactivar todos los elementos del menú
        $(".menu").removeClass('active');

        $(".configuracion_general").addClass('active');
        $(".configuracion_general > .submenu").addClass('show');
        $("#configuracion_parametros").addClass('active');






        // Función para limpiar los encabezados de la tabla
        function cleanTableForExport(tableID) {
            // Clona la tabla para no modificar la original
            var tableClone = $(`#${tableID}`).clone();

            // Remueve inputs y selects de los encabezados
            tableClone.find('th').each(function() {
                $(this).find('input, select').remove(); // Elimina inputs y selects
            });

            return tableClone;
        }
        // Función para exportar a Excel usando SheetJS
        function exportTableToExcel(tableID, filename = '') {
            // Limpia la tabla antes de exportar
            var tableClone = cleanTableForExport(tableID);
            // Convierte la tabla limpia a Excel
            var wb = XLSX.utils.table_to_book(tableClone.get(0), {
                sheet: "Sheet1"
            });
            XLSX.writeFile(wb, filename);
        }
        // Función para exportar a PDF usando jsPDF
        function exportTableToPDF(tableID, filename = '') {
            // Limpia la tabla antes de exportar
            var tableClone = cleanTableForExport(tableID);
            var {
                jsPDF
            } = window.jspdf;
            var doc = new jsPDF();
            doc.autoTable({
                html: tableClone.get(0)
            });
            doc.save(filename);
        }

    });
</script>




<script type="text/javascript">
    function crear_configuracion(event) {
        event.preventDefault();

        let error = false;

        // Eliminar la clase de error de los campos antes de validar
        $('#configuracion_input').removeClass("input_error");
        $('#valor_input').removeClass("input_error");


        if ($("#configuracion_input").val() == '') {
            $('#configuracion_input').addClass("input_error");
            error = true;
        }


        if ($("#valor_input").val() == '') {
            $('#valor_input').addClass("input_error");
            error = true;
        }



        // Si hay errores, mostrar notificación y detener el envío del formulario
        if (error) {
            add_notification({
                text: 'Faltan Datos Obligatorios',
                actionTextColor: '#fff',
                backgroundColor: '#e7515a',
            });
            return true;
        }

        // Preparar la petición AJAX
        $.ajax({
            method: "POST",
            url: "<?php echo ENLACE_WEB; ?>mod_parametros/ajax/configuracion_parametros_ajax.php",
            beforeSend: function(xhr) {

            },
            data: {
                action: 'crear_configuracion',
                configuracion: $("#configuracion_input").val(),
                activo: $("#activo_configuracion").val(),
                valor: $("#valor_input").val(),

            },
        }).done(function(msg) {
            //    console.log("Actualizando");
            console.log(msg);

            var mensaje = jQuery.parseJSON(msg);

            if (mensaje.exito == 1) {
                $("#nueva_configuracion").modal('hide');

                $('#style-3').DataTable().ajax.reload();

                add_notification({
                    text: 'Configuracion creado exitosamente',
                    actionTextColor: '#fff',
                    backgroundColor: '#00ab55',
                    dismissText: 'Cerrar'
                });

            } else {

                add_notification({
                    text: "Error:" + mensaje.mensaje,
                    actionTextColor: '#fff',
                    actionTextColor: '#fff',
                    backgroundColor: '#e7515a',
                });
            }
        });




    }



    function ver_configuracion(int = null) {


        // Preparar la petición AJAX
        $.ajax({
            method: "POST",
            url: "<?php echo ENLACE_WEB; ?>mod_parametros/tpl/modal_parametros.php",
            beforeSend: function(xhr) {
                // aqui deberia ocurrir una carga
            },
            data: {
                action: 'ver_configuracion',
                fiche: int,
            },
        }).done(function(html) {

            //print html en el modal cargado
            $("#nueva_configuracion").html(html).modal('show');


        });

    }


    function actualizar_configuracion(int) {


        let error = false;

        // Eliminar la clase de error de los campos antes de validar
        $('#configuracion_input').removeClass("input_error");
        $('#valor_input').removeClass("input_error");



        if ($("#configuracion_input").val() == '') {
            $('#configuracion_input').addClass("input_error");
            error = true;
        }


        if ($("#valor_input").val() == '') {
            $('#valor_input').addClass("input_error");
            error = true;
        }

        // Si hay errores, mostrar notificación y detener el envío del formulario
        if (error) {
            add_notification({
                text: 'Faltan Datos Obligatorios',
                actionTextColor: '#fff',
                backgroundColor: '#e7515a',
            });
            return true;
        }

        // Preparar la petición AJAX
        $.ajax({
            method: "POST",
            url: "<?php echo ENLACE_WEB; ?>mod_parametros/ajax/configuracion_parametros_ajax.php",
            beforeSend: function(xhr) {

            },
            data: {
                action: 'actualizar_configuracion',
                configuracion: $("#configuracion_input").val(),
                activo: $("#activo_configuracion").val(),
                valor: $("#valor_input").val(),
                id: int,
            },
        }).done(function(msg) {
            var mensaje = JSON.parse(msg);

            if (mensaje.exito === 1) {
                add_notification({
                    text: 'Configuracion actualizado exitosamente',
                    actionTextColor: '#fff',
                    backgroundColor: '#00ab55',
                    dismissText: 'Cerrar'
                });

                $("#nueva_configuracion").modal('hide');

                $('#style-3').DataTable().ajax.reload();

            } else {

                add_notification({
                    text: "Error:" + mensaje.mensaje,
                    actionTextColor: '#fff',
                    actionTextColor: '#fff',
                    backgroundColor: '#e7515a',
                });
            }
        });
    }




    function confirma_eliminar($id) {


        document.getElementById('actualizar_parametro').disabled = true;
        document.getElementById('borrar_parametro').disabled = true;

        // Preparar el mensaje para el snackbar
        var message = "Está seguro(a) que desea eliminar el parametro?";
        var actionText = "<strong onclick='ocultar_snackbar();' id='cancelar_borrado'>Cancelar</strong> <button onclick='borrar_parametro(" + $id + ")' style='margin-left:5px;' id='confirmar_borrado' class='btn btn-danger'>Confirmar</button>";

        console.log("Mostrando snackbar"); // Depuración

        // Mostrar el snackbar y definir el callback para el botón de acción
        var snackbar = add_notification({
            text: message,
            width: 'auto',
            duration: 300000,
            actionText: actionText,
        });

    }
    // Función que se ejecuta al hacer clic en el botón "confirmar_borrado"
    function mostrarSnackbar() {
        // Cambiar el estilo del snackbar para hacerlo visible y colocarlo en la parte inferior
        $(".snackbar-container").attr('style', 'opacity:1 !important; bottom:0px !important');
    }
    // Asignar el evento de clic al botón "confirmar_borrado"
    $(document).on('click', '#confirmar_borrado', function() {
        mostrarSnackbar();
    });

    function ocultar_snackbar() {
        $(".snackbar-container").fadeOut(); // 500 milisegundos para la animación

        document.getElementById('actualizar_parametro').disabled = false;
        document.getElementById('borrar_parametro').disabled = false;
    }


    document.getElementById('nueva_configuracion').addEventListener('hidden.bs.modal', function() {
        // Llamar a la función ocultar_snackbar cuando el modal se oculte
        ocultar_snackbar();
    });


    // FUNCTION DELETE INFO PRODUCT
    function borrar_parametro($id) {

        $.ajax({
            method: "POST",
            url: "<?php echo ENLACE_WEB; ?>mod_parametros/ajax/configuracion_parametros_ajax.php",
            beforeSend: function(xhr) {},
            data: {
                action: 'borrar_parametro',
                id: $id
            },
        }).done(function(msg) {
            console.log(msg);

            var data = JSON.parse(msg);
            // VALID RESULT
            if (data.exito == 1) {
                add_notification({
                    text: 'Parametro Eliminado exitosamente',
                    pos: 'top-right',
                    actionTextColor: '#fff',
                    backgroundColor: '#00ab55'
                });
                $("#nueva_configuracion").modal('hide');

                $('#style-3').DataTable().ajax.reload();
            }
        });
    }
</script>