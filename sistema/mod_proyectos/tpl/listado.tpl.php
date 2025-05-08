
<?php
    include_once(ENLACE_SERVIDOR . "mod_documentos_mercantiles/tpl/header_document.php");
?>



<div class="middle-content container-fluid p-0">
    <!-- BREADCRUMB -->
    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Proyectos</a></li>
                <li class="breadcrumb-item active" aria-current="page">Listado</li>
            </ol>
        </nav>
    </div>
    <!-- /BREADCRUMB -->
    <!-- CONTENT AREA -->
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
            <div class="widget-content searchable-container list"></div>
            <div class="statbox widget box box-shadow">
                <div class="widget-content widget-content-area br-8">
                    <form id="formulario">
                        <div class="table-responsive">
                            <table id="style-3" class="table style-3 dt-table-hover p-0">
                                <thead>
                                    <tr>
                                        <th class="text-left" scope="col">Referencia</th>
                                        <th class="text-left" scope="col">Nombre</th>
                                        <th class="text-left" scope="col">Ubicación en el Mapa</th>
                                        <th class="text-left" scope="col">Cliente</th>
                                        <th class="text-left" scope="col">Etiquetas TAGS</th>
                                        <th class="text-left" scope="col">Monto (€)</th>
                                        <th class="text-left" scope="col">Fecha Inicio</th>
                                        <th class="text-left" scope="col">Fecha Fin</th>
                                        <th class="text-left" scope="col">Estado</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody" style="font-size:small;">
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<style type="text/css">

    #export-buttons-container {
        min-width: 500px !important;
    }
</style>

<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

<?php include_once(ENLACE_SERVIDOR . "mod_documentos_mercantiles/tpl/footer_document.php"); ?>
<script>
$(".menu").removeClass('active');
$(".mod_proyectos").addClass('active');


function cargar_tabla() {
    const ajaxoption = {
        url: '<?php echo ENLACE_WEB; ?>mod_proyectos/json/listado.json.php',
        type: 'POST'
    };
    let options = config_datatable(ajaxoption);

    options.initComplete = function() {
        var api = this.api();
        api.columns().every(function() {
            var column = this;
            var header = $(column.header());
            var headerText = header.text();

            header.empty(); // Limpiar encabezado existente

            var inputContainer = $('<div>').css({
                'width': '100%',
                'height': '100%',
                'position': 'relative'
            }).appendTo(header);
            $('<span>').text(headerText).appendTo(inputContainer);

            if (headerText === 'Fecha Inicio' ) {
                var input = $('<input type="text" class="form-control">')
                        .appendTo(inputContainer)
                        .attr('placeholder', headerText)                       
                        .attr('titulo', headerText)
                        .on('input', function() { // Cambiado de 'change' a 'input'
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );
                            column
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                        });
                    inicializarDateRange(input, column);
            }
            else if (headerText === 'Fecha Fin' ) {
                var input = $('<input type="text" class="form-control">')
                        .appendTo(inputContainer)
                        .attr('placeholder', headerText)                       
                        .attr('titulo', headerText)
                        .on('input', function() { // Cambiado de 'change' a 'input'
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );
                            column
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                        });
                    inicializarDateRange(input, column);
            }
            
            else if (headerText === 'Estado') {
                var select = $('<select>')
                    .addClass('form-control')
                    .attr('title', headerText)
                    .append($('<option>').val('').text('Todos'))
                    .append($('<option>').val('1').text('Activo'))
                    .append($('<option>').val('0').text('Inactivo'));

                select.appendTo(inputContainer).on('change', function() {
                    var val = $(this).val();
                    column.search(val ? '^' + val + '$' : '', true, false).draw();
                });
            } else {
                $('<input type="text" class="form-control">')
                    .css({'min-width': '70px', 'width': '100%'})
                    // .attr('placeholder', headerText)
                    .attr('title', headerText)
                    .appendTo(inputContainer)
                    .on('input', function() {
                        var val = $.fn.dataTable.util.escapeRegex($(this).val());
                        column.search(val ? val : '', false, true).draw();
                    });
            }
        });
    }; // Cierre correcto de initComplete

    options.columns = [
        {
            data: 'referencia',
            searchable: true,
            createdCell: function(td) {
                $(td).attr('data-label', 'referencia').addClass('text-left py-0 px-3');
            },
            render: function(data, type, row) {
                return `<strong><a href="<?php echo ENLACE_WEB; ?>ver_proyecto/${row.rowid}">${data}</a></strong>`;
            }
        },
        {
            data: 'nombre',
            searchable: true,
            createdCell: function(td) {
                $(td).attr('data-label', 'nombre').addClass('text-left py-0 px-3');
            },
            render: function(data, type, row) {
                return `<strong><a href="<?php echo ENLACE_WEB; ?>ver_proyecto/${row.rowid}">${data}</a></strong>`;
            }
        },
        {
            data: 'ubicacion_mapa',
            searchable: true,
            createdCell: function(td) {
                $(td).attr('data-label', 'ubicacion_mapa').addClass('text-left py-0 px-3');
            },
            render: function(data) {
                return `<a href="${data}" target="_blank">${data}</a>`;
            }
        },
        {
            data: 'cliente',
            searchable: true,
            createdCell: function(td) {
                $(td).attr('data-label', 'cliente').addClass('text-left py-0 px-3');
            },
            render: function(data, type, row) {
                return `<strong><a target="_blank" href="<?php echo ENLACE_WEB; ?>clientes_editar/${row.client_id}">${data}</a></strong>`;
            }
        },
        {
            data: 'etiquetas_tags',
            searchable: true,
            createdCell: function(td) {
                $(td).attr('data-label', 'etiquetas_tags').addClass('text-left py-0 px-3');
            },
            render: function(data) {
                return data ? `<a href="#" onclick="alert('Etiquetas: ${data}');">${data}</a>` : 'No especificado';
            }
        },
        {
            data: 'monto',
            searchable: true,
            createdCell: function(td) {
                $(td).attr('data-label', 'monto').addClass('text-left py-0 px-3');
            },
            render: function(data) {
                return `€${parseFloat(data).toFixed(2)}`; // Cambiar a Euros
            }
        },
        {
            data: 'fecha_inicio',
            searchable: true,
            createdCell: function(td) {
                $(td).attr('data-label', 'fecha_inicio').addClass('text-left py-0 px-3');
            },
            render: function(data) {
                return `<a href="#" onclick="alert('Fecha Inicio: ${moment(data).format('DD/MM/YYYY')}');">${moment(data).format('DD/MM/YYYY')}</a>`;
            }
        },
        {
            data: 'fecha_fin',
            searchable: true,
            createdCell: function(td) {
                $(td).attr('data-label', 'fecha_fin').addClass('text-left py-0 px-3');
            },
            render: function(data) {
                return `<a href="#" onclick="alert('Fecha Fin: ${moment(data).format('DD/MM/YYYY')}');">${moment(data).format('DD/MM/YYYY')}</a>`;
            }
        },
        {
            data: 'estado',
            searchable: true,
            createdCell: function(td) {
                $(td).attr('data-label', 'estado').addClass('text-left py-0 px-3');
            },
            render: function(data) {
                let etiqueta = `<div style="display: flex; justify-content: space-evenly;" class="py-2">`
                etiqueta += (data == 1 
                    ? '<span class="badge bg-success">Activo</span>' 
                    : '<span class="badge bg-danger">Inactivo</span>');
                etiqueta += '</div>'
                return etiqueta;
            }
        }
    ];

    vtabla = $('#style-3').DataTable(options);

    let newButton = $('<button>')
        .html('<i class="fa-solid fa-plus"></i> Nuevo Proyecto')
        .addClass('btn btn-primary')
        .attr("type", "button")
        .on('click', function() {
            window.location = '<?= ENLACE_WEB ?>proyecto_crear_nuevo';
        });

    setting_table(vtabla, [newButton]);
} // Cierre correcto de cargar_tabla



$(document).ready(function() {
    cargar_tabla();
});



</script>