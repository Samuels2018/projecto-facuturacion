

<div class="middle-content container-xxl p-0">
    <!-- BREADCRUMB -->
    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Cotizaciones</a></li>
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
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Listado de Cotizaciones</h4>
                        </div>
                    </div>
                </div>
                <!-- <div class="widget-content widget-content-area"> -->
                <div class="widget-content widget-content-area br-8">

                    <form id="formulario">
                        <div class="table-responsive"> 
                            <!-- <table class="table table-bordered" id="style-3"> -->
                            <table id="style-3" class="table style-3 dt-table-hover p-0">
                                <thead>
                                    <tr>
                                        <th class="text-center" scope="col">Cotizaci&oacute;n</th>
                                        <th class="text-center" scope="col">Nombre Cliente</th>
                                        <th class="text-center" scope="col">Fecha</th>
                                        <!--<th class="text-center" scope="col">Tipo</th>-->
                                        <th class="text-center" scope="col">Agente</th>
                                        <th class="text-center" scope="col">Recurso Humano</th>
                                        <th class="text-center" scope="col">Total</th>
                                        <th class="text-center" scope="col">Estado</th>
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

<script>
    $(document).ready(function() {
        cargar_tabla_albaranes();

        // Desactivar todos los elementos del menú
        $(".menu").removeClass('active');

        $(".albaranes").addClass('active');
        $(".albaranes > .submenu").addClass('show');
        $("#listado_albaranes").addClass('active');
    });

    function cargar_tabla_albaranes() {
        vtabla = $('#style-3').DataTable({
            'Processing': true,
            'serverSide': true,
            "bSort": false,
            "pageLength": 10,
            "order": [
                [0, "desc"]
            ],
            select: {
                style: 'multi'
            },
            "dom": estiloPaginado.dom,
        "oLanguage": estiloPaginado.oLanguage,
            'ajax': {
                'url': '<?php echo ENLACE_WEB; ?>mod_redhouse_cotizaciones/json/cotizaciones.listado.json.php',
                'Method': 'POST'
            },
            retrieve: true,
            deferRender: true,
            scroller: true,
            responsive: true,
            "initComplete": function () {
                this.api().columns().every(function () {
                    var column = this;
                    var header = $(column.header());
                    var headerText = header.text(); // Guarda el texto original del encabezado
                    header.empty(); // Limpia el encabezado

                    // Crea un contenedor div para el texto del encabezado
                    var headerTextContainer = $('<div>').appendTo(header);
                    $('<span>').text(headerText).appendTo(headerTextContainer);

                    // Crea un contenedor div para el input/select
                    var inputContainer = $('<div>').appendTo(header);

                });
            },
            'columns': [ 
                {
                    data: 'ID',
                    searchable: true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'ID');
                        $(td).addClass('text-center').addClass('p-1');
                    },
                    render: function(data, type, row) {
                        return `
                        <a href="${ENLACE_WEB}redhouse_cotizaciones_detalle/${row.ID}">
                            <i class="fa-regular fa-file-lines"></i> ${row.Referencia}
                        </a>
                        `;
                    }
                },
                {
                    data: 'cliente_tercero',
                    searchable: true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'cliente_tercero').addClass('p-1');
                    }
                },
                {
                    data: 'Fecha',
                    searchable: true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'Fecha').addClass('p-1');
                    }
                },
                /*{
                    data: 'categoria_txt',
                    searchable: true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'categoria_txt');

                        var span = $('<span></span>'); // Crea un elemento span
                        span.text(cellData); // Asigna el texto de la celda al span
                        span.addClass(rowData.color); // Añade la clase correspondiente al color
                        $(td).empty().append(span);
                        $(td).addClass('text-center').addClass('p-1');
                    }
                } ,*/
                {
                    data: 'agente_txt',
                    
                    searchable: true,
                      createdCell: function(td, cellData, rowData, row, col) {
                          $(td).attr('data-label', 'Agente');
                      },
                      render: function(data, type, row ) {

                          if (row.avatar  != null) {
                              return ` <div class="avatar-chip bg-primary mb-2 me-4 position-relative" >
                                            <img alt="avatar" src="${row.avatar}" class="rounded-circle" />
                                            <span class="text" >${data}</span>
                                        </div>`
                          } else {
                              return '-';
                          }

                      }
                      
                },
               

                {
                    data: 'nombres_recursos',
                    searchable: true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'nombres_recursos');

                        var span = $('<span></span>'); // Crea un elemento span
                        span.text(cellData); // Asigna el texto de la celda al span
                        span.addClass("badge badge-light-"+rowData.estado_estilo); // Añade la clase correspondiente al color
                        $(td).empty().append(span);
                        $(td).addClass('text-center').addClass('p-1');
                    }
                },

                {
                    data: 'Total',
                    searchable: true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'Total').addClass('p-1').css('text-align', 'right');
                    }
                },
                {
                    data: 'estado_txt',
                    searchable: true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'estado_txt');

                        var span = $('<span></span>'); // Crea un elemento span
                        span.text(cellData); // Asigna el texto de la celda al span
                        span.addClass("badge badge-light-"+rowData.estado_estilo); // Añade la clase correspondiente al color
                        $(td).empty().append(span);
                        $(td).addClass('text-center').addClass('p-1');
                    }
                }
            ]
        });
    }
</script>
