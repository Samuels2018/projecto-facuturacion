<?php
include_once(ENLACE_SERVIDOR . "mod_documentos_mercantiles/tpl/header_document.php");
?>
<div class="middle-content container-xxl p-0">

    <!-- BREADCRUMB -->
    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Tiempos de entrega</a></li>
                <li class="breadcrumb-item active" aria-current="page">Listado</li>
            </ol>
        </nav>
    </div>
    <!-- /BREADCRUMB -->


    <!-- CONTENT AREA -->
    <div class="row layout-top-spacing">

        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
            <div class="widget-content widget-content-area br-8">

                <form id="formulario">
                    <!-- Tabla  -->
                    <div class="table-responsive">
                        <table id="style-3" class="table style-3 dt-table-hover">
                            <thead>
                                <tr>
                                    <!-- <th style="width: 10%;" scope="col" >ID</th> -->
                                    <th scope="col">Descripción</th>
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
    <!-- CONTENT AREA -->
</div>

<!-- MODAL  -->
<div class="modal fade" id="nueva_ventana" tabindex="-1" role="dialog" aria-labelledby="nueva_ventana_label" aria-hidden="true">
</div>
<!-- MODAL  -->
<?php include_once(ENLACE_SERVIDOR . "mod_documentos_mercantiles/tpl/footer_document.php"); ?>

<!-- Scripts -->
<script>
    function cargar_tabla() {
        const ajaxoption = {
            url: '<?php echo ENLACE_WEB; ?>mod_tiempo_entrega/ajax/listado_tiempo_entrega_ajax.php',
            type: 'GET'
        }
        let options = config_datatable(ajaxoption);

        options.initComplete = function() {
            this.api().columns().every(function(col) {
                var column = this;
                var header = $(column.header());
                var headerText = header.text(); // Guarda el texto original del encabezado
                header.empty(); // Limpia el encabezado
                // Crea un contenedor div para el texto del encabezado
                var headerTextContainer = $('<div class="text-center" >').appendTo(header);
                $('<span>').text(headerText).appendTo(headerTextContainer);

                // Lista de índices de columnas donde NO quieres mostrar el input de búsqueda
                var excludedColumns = [1]; // Por ejemplo, para excluir las columnas 2, 4 y 6

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
                    if (column.dataSrc() === 'estado') {
                        let select = generarSelectEstatus('estado');

                        $(select).appendTo(header).on('change', function() {
                            var val = ($(this).val());

                            column.search(val ? '^' + val + '$' : '', true, false).draw();
                        });
                    }

                }
            });
            $('.dataTables_length').parent().removeClass('col-sm-6').addClass('col-sm-12');
        }
        options.columns = [{
                data: 'label',
                searchable: true,
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).addClass('text-left').attr('data-label', 'label');
                },
                render: function(data, type, row) {
                    return `<a onclick="ver_informacion(${row.ID});" href="#">${data}</a>`
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
        vtabla = $('#style-3').DataTable(options)

        var newButton = $('<button>')
            .html('<i class="fas fa-plus"></i> Nuevo tiempo de entrega')
            .addClass('btn btn-primary _effect--ripple waves-effect waves-light')
            .attr("type", "button")
            .on('click', function() {
                ver_informacion();
            });
        setting_table(vtabla, [newButton])



    }
</script>

<script>
    $(document).ready(function() {

        cargar_tabla();


        // Desactivar todos los elementos del menú
        $(".menu").removeClass('active');

        $(".configuracion_general").addClass('active');
        $(".configuracion_general > .submenu").addClass('show');
        $("#medios_pago").addClass('active');

    });
</script>




<script type="text/javascript">
    let estado_accion = 0 // 0: Sin Accion, 1: Creando/Modificando, 2: Borrando

    function guardar(event) {

        event.preventDefault();

        let error = false;

        // Eliminar la clase de error de los campos antes de validar
        $('#label').removeClass("input_error");

        // Recoger los valores del formulario usando jQuery
        const label = $('#label').val();
        const fiche = $("[name='fiche']").val();

        // Validar que los campos no estén vacíos y añadir la clase de error si es necesario
        if (label == '') {
            $('#label').addClass("input_error");
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
            url: "<?php echo ENLACE_WEB; ?>mod_tiempo_entrega/ajax/tiempo_entrega_ajax.php",
            beforeSend: function(xhr) {

            },
            data: {
                action: 'guardar',
                label: label,
                id: fiche,
                estado: $("#estado_tiempo_entrega").val(),
            },
        }).done(function(msg) {
            //    console.log("Actualizando");
            console.log(msg);

            var mensaje = jQuery.parseJSON(msg);

            if (mensaje.exito == 1) {
                $("#nueva_ventana").modal('hide');
                $('#style-3').DataTable().ajax.reload();
                add_notification({
                    text: 'Tiempo de entrega guardado exitosamente',
                    actionTextColor: '#fff',
                    backgroundColor: '#00ab55',
                    dismissText: 'Cerrar'
                });

            } else {

                add_notification({
                    text: "Error: " + mensaje.mensaje,
                    actionTextColor: '#fff',
                    actionTextColor: '#fff',
                    backgroundColor: '#e7515a',
                });
            }
        });
    }



    function ver_informacion(int = null) {

        // Preparar la petición AJAX
        $.ajax({
            method: "POST",
            url: "<?php echo ENLACE_WEB; ?>mod_tiempo_entrega/tpl/modal_tiempo_entrega.php",
            beforeSend: function(xhr) {
                // aqui deberia ocurrir una carga
            },
            data: {
                action: 'ver_informacion',
                fiche: int,
            },
        }).done(function(html) {

            //print html en el modal cargado
            $("#nueva_ventana").html(html).modal('show');
        });

    }


    // FUNCTION DELETE INFO PRODUCT
    function borrar($id) {
        // Preparar el mensaje para el snackbar
        var message = "Está seguro(a) que desea eliminar este Registro? ";
        var actionText = "Confirmar";

        // Mostrar el snackbar y definir el callback para el botón de acción
        add_notification({
            text: message,
            width: 'auto',
            duration: 30000,
            actionText: actionText,
            dismissText: 'Cerrar',
            onActionClick: function(element) {
                // Aquí va el código que se ejecutará cuando el usuario confirme
                $.ajax({
                    method: "POST",
                    url: "<?php echo ENLACE_WEB; ?>mod_tiempo_entrega/ajax/tiempo_entrega_ajax.php",
                    beforeSend: function(xhr) {},
                    data: {
                        action: 'borrar',
                        id: $id
                    },
                }).done(function(msg) {
                    console.log(msg);

                    var data = JSON.parse(msg);
                    // VALID RESULT
                    if (data.exito == 1) {
                        add_notification({
                            text: 'Registro Eliminado exitosamente',
                            pos: 'top-right',
                            actionTextColor: '#fff',
                            backgroundColor: '#00ab55'
                        });
                        $("#nueva_ventana").modal('hide');

                        $('#style-3').DataTable().ajax.reload();
                    }
                });
            }
        });
    }

    function ocultar_snackbar() {
        $(".snackbar-container").fadeOut(0);
    }
    // Escuchar el evento de ocultar el modal con id 'nuevo_modal'
    document.getElementById('nueva_ventana').addEventListener('hidden.bs.modal', function() {
        // Llamar a la función ocultar_snackbar cuando el modal se oculte
        if (estado_accion == 2) {
            ocultar_snackbar();
        }
    });
</script>