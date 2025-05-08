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
</style>
<div class="middle-content container-xxl p-0">

    <!-- BREADCRUMB -->
    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Unidades</a></li>
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
                    <a href="#" onclick="ver_modal()" class="btn btn-primary">Nueva Unidad</a>
                </div>

            </div>
            <div class="widget-content widget-content-area br-8">

                <form id="formulario">
                    <!-- Tabla  -->
                    <div class="table-responsive">
                        <table id="style-3" class="table style-3 dt-table-hover">
                            <thead>
                                <tr>

                                    <th scope="col">Código</th>
                                    <th scope="col">Detalle</th>
                                    <th scope="col">Tipo</th>
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
</div>
<!-- Scripts -->

<!-- MODAL  -->
<div class="modal fade" id="nuevo_modal" tabindex="-1" role="dialog" aria-labelledby="nuevo_modal_label" aria-hidden="true">
    <!-- MODAL  -->

</div>
<!-- Scripts -->





<script>
    function cargar_tabla_crm() {
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
                'url': '<?php echo ENLACE_WEB; ?>mod_catalogo/ajax/listado_catalogo_ajax.php',
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
                    var excludedColumns = [2, 3]; // Por ejemplo, para excluir las columnas 2, 4 y 6

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
                            .on('input', function() { // Cambiado de 'change' a 'input'
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );
                                column
                                    .search(val ? '^' + val + '$' : '', true, false)
                                    .draw();
                            });
                    } else {
                        // Para las columnas excluidas, agrega un div vacío para reservar el espacio
                        /* $('<div>').css({
                             'width': '100%',
                             'height': '30px', // Aumenta el tamaño del div vacío
                             'visibility': 'hidden' // Oculta el div pero mantiene su espacio
                         }).appendTo(inputContainer);*/

                        if (column.dataSrc() === 'activo') {
                            let select = generarSelectEstatus('activo');

                            $(select).appendTo(header).on('change', function() {
                                var val = $(this).val();
                                column.search(val ? '^' + val + '$' : '', true, false).draw();
                            });
                        }
                        if (column.dataSrc() === 'tipo') {
                            let select = generarSelectGeneral('tipo', [ {value: "1", text: "Producto"}, {value: "2", text: "Servicio"} ]);

                            $(select).appendTo(header).on('change', function() {
                                var val = $(this).val();
                                column.search(val ? '^' + val + '$' : '', true, false).draw();
                            });
                        }

                    }
                });
            },
            'columns': [{
                    data: 'codigo',
                    searchable: true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).addClass('text-center').attr('data-label', 'codigo');
                    },
                    render: function(data, type, row) {

                        return `<a onclick="ver_modal(${row.ID});" href="#">${data}</a>`

                    }

                },
                {
                    data: 'detalle',
                    searchable: true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).addClass('text-left').attr('data-label', 'detalle');
                    },
                    render: function(data, type, row) {

                        return `<a onclick="ver_modal(${row.ID});" href="#">${data}</a>`

                    }

                },
                {
                    data: 'tipo',
                    searchable: true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).addClass('text-center').attr('data-label', 'tipo');
                    },
                    render: function(data, type, row) {

                        return `<a onclick="ver_modal(${row.ID});" href="#">${parseInt(data)==1?'Producto':'Servicio'}</a>`

                    }

                },
                {
                    data: 'activo',
                    searchable: true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).addClass('text-center').attr('data-label', 'activo');
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

        // Crear el icono de configuración dinámicamente
        var configIcon = $('<i class="fa fa-bars" style="cursor: pointer;margin-right: 10px;font-size: 30px;float-right;float: right;"></i>');

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
        $('#style-3_wrapper').prepend(configIcon);
        $('#style-3_wrapper').prepend(columnVisibilityContainer);

        // Mostrar/ocultar el div cuando se hace clic en el icono de configuración
        configIcon.on('click', function() {
            columnVisibilityContainer.toggle();
        });



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

        cargar_tabla_crm();


        // Desactivar todos los elementos del menú
        $(".menu").removeClass('active');

        $(".configuracion_general").addClass('active');
        $(".configuracion_general > .submenu").addClass('show');
        $("#catalogo_listado").addClass('active');





        // Crea un contenedor div para los botones
        var buttonContainer = $('<div>').attr("id", "export-buttons-container").addClass('ml-2');

        // Crea el botón de Excel con el icono de Font Awesome
        var excelButton = $('<button>')
            .html('<i class="fas fa-file-excel"></i> Exportar Excel')
            .addClass('btn btn-success')
            .attr("type", "button")
            .on('click', function() {
                exportTableToExcel('style-3', 'table_export.xlsx');
            });

        // Crea el botón de PDF con el icono de Font Awesome
        var pdfButton = $('<button>')
            .html('<i class="fas fa-file-pdf"></i> Generar PDF')
            .addClass('btn btn-danger')
            .attr("type", "button")
            .on('click', function() {
                exportTableToPDF('style-3', 'table_export.pdf');
            });

        // Agrega los botones al contenedor
        buttonContainer.append(excelButton, pdfButton);

        // Coloca el contenedor de botones en el lugar deseado dentro de la interfaz
        buttonContainer.appendTo("#style-3_length");


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
    function ver_modal(int = null) {

        // Preparar la petición AJAX
        $.ajax({
            method: "POST",
            url: "<?php echo ENLACE_WEB; ?>mod_catalogo/tpl/modal_catalogo.php",
            beforeSend: function(xhr) {
                // aqui deberia ocurrir una carga
            },
            data: {
                action: 'ver_modal',
                fiche: int,
            },
        }).done(function(html) {
            //print html en el modal cargado
            $("#nuevo_modal").html(html).modal('show');
        });

    }



    function crear_unidad(event) {
        event.preventDefault();

        let error = false;

        // Eliminar la clase de error de los campos antes de validar
        $('#unidad_codigo').removeClass("input_error");

        $('#nombre_unidad').removeClass("input_error");




        // Validar que los campos no estén vacíos y añadir la clase de error si es necesario
        if ($("#unidad_codigo").val() == '') {
            $('#unidad_codigo').addClass("input_error");
            error = true;
        }

        if ($("#unidad_detalle").val() == '') {
            $('#unidad_detalle').addClass("input_error");
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
            url: "<?php echo ENLACE_WEB; ?>mod_catalogo/ajax/diccionario_catalogo_ajax.php",
            beforeSend: function(xhr) {

            },
            data: {
                action: 'crear_unidad',
                codigo: $("#unidad_codigo").val(),
                tipo: $("#unidad_tipo").val(),
                detalle: $("#unidad_detalle").val(),
                activo: $("#estado_unidad").val(),
            },
        }).done(function(msg) {
            //    console.log("Actualizando");
            console.log(msg);

            var mensaje = jQuery.parseJSON(msg);

            if (mensaje.exito == 1) {
                add_notification({
                    text: 'Unidad creado exitosamente',
                    actionTextColor: '#fff',
                    backgroundColor: '#00ab55',
                    dismissText: 'Cerrar'
                });

                $("#nuevo_modal").modal('hide');
                $('#style-3').DataTable().ajax.reload();

            } else {

                add_notification({
                    text: "Error:" + mensaje.error_txt,
                    actionTextColor: '#fff',
                    actionTextColor: '#fff',
                    backgroundColor: '#e7515a',
                });
            }
        });
    }


    function actualizar_unidad(id) {
        //event.preventDefault();

        let error = false;

        // Eliminar la clase de error de los campos antes de validar
        $('#unidad_codigo').removeClass("input_error");

        $('#nombre_unidad').removeClass("input_error");




        // Validar que los campos no estén vacíos y añadir la clase de error si es necesario
        if ($("#unidad_codigo").val() == '') {
            $('#unidad_codigo').addClass("input_error");
            error = true;
        }

        if ($("#unidad_detalle").val() == '') {
            $('#unidad_detalle').addClass("input_error");
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
            url: "<?php echo ENLACE_WEB; ?>mod_catalogo/ajax/diccionario_catalogo_ajax.php",
            beforeSend: function(xhr) {

            },
            data: {
                action: 'actualizar_unidad',
                codigo: $("#unidad_codigo").val(),
                tipo: $("#unidad_tipo").val(),
                detalle: $("#unidad_detalle").val(),
                id: id,
                activo: $("#estado_unidad").val(),
            },
        }).done(function(msg) {
            //    console.log("Actualizando");
            console.log(msg);

            var mensaje = jQuery.parseJSON(msg);

            if (mensaje.exito == 1) {
                add_notification({
                    text: 'Unidad actualizado exitosamente',
                    actionTextColor: '#fff',
                    backgroundColor: '#00ab55',
                    dismissText: 'Cerrar'
                });

                $("#nuevo_modal").modal('hide');
                $('#style-3').DataTable().ajax.reload();

            } else {

                add_notification({
                    text: "Error:" + mensaje.error_txt,
                    actionTextColor: '#fff',
                    actionTextColor: '#fff',
                    backgroundColor: '#e7515a',
                });
            }
        });
    }

    // FUNCTION DELETE INFO PRODUCT
    function confirma_eliminar($id) {

        // Deshabilitar los botones "borrar_unidad" y "actualizar_unidad"
        document.getElementById('borrar_unidad').disabled = true;
    document.getElementById('actualizar_unidad').disabled = true;


// Preparar el mensaje para el snackbar
var message = "¿Está seguro(a) que desea eliminar la unidad?";
var actionText = "<strong onclick='ocultar_snackbar();' id='cancelar_borrado'>Cancelar</strong> <button onclick='aplicar_borrado("+$id+")' style='margin-left:5px;' id='confirmar_borrado' class='btn btn-danger'>Confirmar</button>";

// Mostrar el snackbar y definir el callback para el botón de acción
var snackbar = add_notification({
    text: message,
    width: 'auto',
    duration: 30000,
    actionText: actionText,
});
}

function aplicar_borrado($id){
     // Aquí va el código que se ejecutará cuando el usuario confirme
     $.ajax({
                method: "POST",
                url: "<?php echo ENLACE_WEB; ?>mod_catalogo/ajax/diccionario_catalogo_ajax.php",
                beforeSend: function(xhr) {},
                data: {
                    action: 'borrar_catalogo',
                    id: $id
                },
            }).done(function(msg) {
                console.log(msg);

                var data = JSON.parse(msg);
                // VALID RESULT
                if (data.exito == 1) {
                    add_notification({
                        text: 'Unidad eliminada exitosamente',
                        pos: 'top-right',
                        actionTextColor: '#fff',
                        backgroundColor: '#00ab55'
                    });

                            // habilitar los botones "borrar_unidad" y "actualizar_unidad"
         document.getElementById('borrar_unidad').disabled = false;
    document.getElementById('actualizar_unidad').disabled = false;

                    $("#nuevo_modal").modal('hide');
                    $('#style-3').DataTable().ajax.reload();
                }
            });
}

function ocultar_snackbar() {
    $(".snackbar-container").fadeOut(0); // 500 milisegundos para la animación
         // habilitar los botones "borrar_unidad" y "actualizar_unidad"
         document.getElementById('borrar_unidad').disabled = false;
    document.getElementById('actualizar_unidad').disabled = false;
}

// Escuchar el evento de ocultar el modal con id 'nuevo_modal'
document.getElementById('nuevo_modal').addEventListener('hidden.bs.modal', function () {
    // Llamar a la función ocultar_snackbar cuando el modal se oculte
    ocultar_snackbar();
});
</script>