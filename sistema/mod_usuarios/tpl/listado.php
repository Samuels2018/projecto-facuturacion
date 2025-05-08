<?php
include_once(ENLACE_SERVIDOR . "mod_documentos_mercantiles/tpl/header_document.php");
?>
<div class="middle-content container-xxl p-0">

    <!-- BREADCRUMB -->
    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Usuarios</a></li>
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
                                    <th scope="col">ID</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Apellido</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Pefiles</th>
                                    <th scope="col">Relación</th>
                                    <th scope="col">Estatus</th>
                                    <th scope="col">Estatus Empresa</th>
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

<?php include_once(ENLACE_SERVIDOR . "mod_documentos_mercantiles/tpl/footer_document.php"); ?>

<script>
    function cargar_tabla__crm_terceros() {
        const ajaxoption = {
            url: '<?php echo ENLACE_WEB; ?>mod_usuarios/ajax/listado.usuarios.ajax.php',
            type: 'GET'
        }
        let options = config_datatable(ajaxoption);
        options.initComplete = function() {
            var api = this.api();
            api.columns().every(function(col) {
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
                var inputContainer = $('<div>').css({
                    'width': '100%',
                    'height': '100%',
                    'position': 'relative'
                }).appendTo(header);
                $('<span>').text(headerText).appendTo(inputContainer);

                var excludedColumns = [4]; // Por ejemplo, para excluir las columnas 2, 4 y 6

                if ($.inArray(col, excludedColumns) >= 0) {
                    // Para los campos de texto
                    $('<br/><br/>')
                        .appendTo(header);
                } else if (headerText === 'Estatus') {
                    let select = generarSelectEstatus('estatus');
                    $(select).appendTo(header).on('change', function() {
                        var val = $(this).val();
                        column.search(val ? '^' + val + '$' : '', true, false).draw();
                    });
                } else if (headerText === 'Estatus Empresa') {
                    let select = generarSelectEstatus('estatus');
                    $(select).appendTo(header).on('change', function() {
                        var val = $(this).val();
                        column.search(val ? '^' + val + '$' : '', true, false).draw();
                    });
                }  else if (headerText === 'Relación') {
                    let select = generarSelectGeneral('relacion', [{value:1, text:'Dueño'},{value:2, text:'Gestor'},{value:3, text:'Invitado'}])
                    $(select).appendTo(header).on('change', function() {
                        var val = $(this).val();
                        column.search(val ? '^' + val + '$' : '', true, false).draw();
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
        options.columns = [{
                data: 'ID',
                searchable: true,
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('data-label', 'ID');
                },
                render: function(data, type, row) {
                    return `<a href="${ENLACE_WEB}/usuario_editar/${row.ID}">${row.ID}</a>`
                }

            },
            {
                data: 'Nombre',
                searchable: true,
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('data-label', 'Nombre');
                },
                render: function(data, type, row) {
                    return `<a href="${ENLACE_WEB}/usuario_editar/${row.ID}">${data}</a>`
                }

            },
            {
                data: 'Apellido',
                searchable: true,
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('data-label', 'Apellido');
                },
                render: function(data, type, row) {
                    return `<a href="${ENLACE_WEB}/usuario_editar/${row.ID}">${row.Apellido}</a>`
                }
            },
            {
                data: 'Email',
                searchable: true,
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('data-label', 'Email');
                },
                render: function(data, type, row) {
                    return `<a href="${ENLACE_WEB}/usuario_editar/${row.ID}">${row.Email}</a>`
                }
            },
            {
                data: 'perfiles',
                searchable: true,
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('data-label', 'perfiles');
                },
                render: function(data, type, row) {
                    return data;
                }
            },
            {
                data: 'relacion',
                searchable: true,
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('data-label', 'relacion');
                },
                render: function(data, type, row) {
                    return data;
                }
            },
            {
                data: 'Estatus',
                searchable: true,
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('data-label', 'Estatus');
                },
                render: function(data, type, row) {
                    if (data == 1) {
                        return '<span class="shadow-none badge badge-primary">Activo</span>';
                    } else if (data == 0) {
                        return '<span class="shadow-none badge badge-danger">Inactivo</span>';
                    } else if (data == 3) {
                        const link_invitacion = `<a href="#" onclick="genera_correo_activacion(${row.ID})"><br/><i class="fa-solid fa-paper-plane fa-xl"></i></a>`;
                        return '<span class="shadow-none badge badge-warning">Pendiente de Activar</span>'+link_invitacion;
                    } else {
                        return '<span class="shadow-none badge badge-primary">Sin estado</span>';
                    }
                }
            },
            {
                data: 'EstatusEmpresa',
                searchable: true,
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('data-label', 'EstatusEmpresa');
                },
                render: function(data, type, row) {
                    if (data == 1) {
                        return '<span class="shadow-none badge badge-primary">Activo</span>';
                    } else if (data == 0) {
                        return '<span class="shadow-none badge badge-danger">Inactivo</span>';
                    } else {
                        return '<span class="shadow-none badge badge-warning">Sin status</span>';
                    }
                }
            }
        ]
        vtabla = $('#style-3').DataTable(options)
        let newButton = $('<button>')
            .html('<i class="fa-solid fa-plus"></i> Nuevo usuario')
            .addClass('btn btn-primary')
            .attr("type", "button")
            .on('click', function() {
                window.location = '<?= ENLACE_WEB ?>usuario_crear'
            });
        setting_table(vtabla, [newButton])

    }
</script>






<script>
    $(document).ready(function() {
        cargar_tabla__crm_terceros();
        $(".menu").removeClass('active');
        $(".usuarios").addClass('active');
        $(".usuarios > .submenu").addClass('show');
        $("#usuarios_listado").addClass('active');
    });

    function genera_correo_activacion(id) {
        var message = "Está seguro(a) que desea solicitar aprobación del usuario? ";
        var actionText = "Confirmar";

        // Mostrar el snackbar y definir el callback para el botón de acción
        add_notification({
            text: message,
            width: 'auto',
            duration: 30000,
            actionText: actionText,
            dismissText: 'Cerrar',
            onActionClick: function(element)
            {
                $.ajax({
                    method: "POST",
                    url: "<?php echo ENLACE_WEB; ?>mod_usuarios/ajax/usuario.ajax.php",
                    beforeSend: function(xhr) {},
                    data: {
                        action: 'generar_correo_activacion',
                        id: id
                    },
                }).done(function(msg) {
                    if (msg) {
                        add_notification({
                            text: 'Activación enviada exitosamente',
                            pos: 'top-right',
                            actionTextColor: '#fff',
                            backgroundColor: '#00ab55'
                        });
                    }else{
                        add_notification({
                            text: 'Error al enviar la solicitud',
                            actionTextColor: '#fff',
                            backgroundColor: '#e7515a',
                        });
                    }
                });
            }
        })
    }
</script>