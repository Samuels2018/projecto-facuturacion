<?php
require_once ENLACE_SERVIDOR . "mod_entidad/object/Entidad.object.php";
require_once ENLACE_SERVIDOR . 'mod_impuestos/object/impuestos_object.php';    


$Entidad = new Entidad($dbh,    $_SESSION['Entidad']);
$Entidad->cargar_sincronizaciones();

$impuestos = new impuestos($dbh, $_SESSION['Entidad']); 
$lista_impuestos = $impuestos->listar_impuestos();



include_once(ENLACE_SERVIDOR . "mod_documentos_mercantiles/tpl/header_document.php");

?>

<style type="text/css">
    /*#style-3_filter {
          display: none !important;
      }*/
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


<link rel="stylesheet" href="https://designreset.com/cork/html/src/assets/css/light/apps/contacts.css">
<div class="middle-content container-fluid p-0">

    <!-- BREADCRUMB -->
    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Productos</a></li>
                <li class="breadcrumb-item active" aria-current="page">Listado</li>
            </ol>
        </nav>
    </div>
    <!-- /BREADCRUMB -->

    <!-- CONTENT AREA -->
    <div class="row layout-top-spacing">


        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
            <div class="widget-content searchable-container list">


            </div>
            <div class="widget-content widget-content-area br-8">

                <form id="formulario">
                    <!-- Tabla  -->
                    <div class="table-responsive">
                        <table id="style-3" class="table style-3 dt-table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">Ref</th>
                                    <th scope="col">Nombre</th>
                                    <th class="text-center" scope="col">Stock</th>
                                    <th class="text-center" scope="col">Ventas</th>
                                    <th class="text-center" scope="col">Compras</th>
                                    <th class="text-center" scope="col">Base</th>
                                    <th class="text-center" scope="col">Imp</th>
                                    <th class="text-center" scope="col">Venta</th>
                                </tr>


                            </thead>

                            <tbody id="tbody" role="alert" aria-live="polite" aria-relevant="all" id="tbody" style="font-size:small;">

                                <?php # require_once(ENLACE_SERVIDOR . "mod_productos/ajax/listado.servicios.ajax.php");  
                                ?>
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
<script>



    function cargar_tabla_productos() {

        const ajaxoption = {
            url: '<?php echo ENLACE_WEB; ?>mod_productos/ajax/listado.productos.ajax.php',
            type: 'GET'
        };

        let options = config_datatable(ajaxoption);  
    
        
            options.initComplete = function() {
                this.api().columns().every(function(col) {
                      var column = this;
                      var header = $(column.header());
                      var headerText = header.text(); // Guarda el texto original del encabezado
                      header.empty(); // Limpia el encabezado
                    
                    // Crea un contenedor div para el texto del encabezado
                    var headerTextContainer = $('<div>').appendTo(header);
                    $('<span>').text(headerText).appendTo(headerTextContainer);
                    
                    // Crea un contenedor div para el input/select
                   

                    var inputContainer = $('<div>').css({
                          'width': '100%', // Asegura que el contenedor ocupe todo el espacio disponible
                          'height': '100%', // Asegura que el contenedor ocupe todo el espacio disponible
                          'position': 'relative' // Posiciona el contenedor de manera relativa para que el input se posicione correctamente dentro de él
                      }).appendTo(header);
                    
                      if (column.dataSrc() === 'ventas' || column.dataSrc() === 'compras') {
    let select = generarSelectSiNo(column.dataSrc());
    $(select).appendTo(header).on('change', function() {
        var val = $(this).val();
        column.search(val ? '^' + val + '$' : '', true, false).draw();
    });
} else if (column.dataSrc() === 'subtotal' || column.dataSrc() === 'total') {
    var input = $('<input type="text" class="form-control">')
        .attr('placeholder', 'Mayor que')
        .appendTo(inputContainer)
        .on('input', function() { // Cambiado de 'change' a 'input'
            var val = $.fn.dataTable.util.escapeRegex(
                $(this).val()
            );
            column
                .search(val ? '^' + val + '$' : '', true, false)
                .draw();
        });
} else if (column.dataSrc() === 'impuesto') {
    let listaImpuestos = <?php echo json_encode($lista_impuestos); ?>;
    
    // Crear el select box
    var select = $('<select class="form-control">')
        .append('<option value="">Todos</option>'); // Opción por defecto

    // Iterar sobre listaImpuestos (que es un objeto)
    for (const key in listaImpuestos) {
        if (listaImpuestos.hasOwnProperty(key)) {
            const impuesto = listaImpuestos[key];
            select.append(
                $('<option>')
                    .val(impuesto.impuesto) // El valor del impuesto
                    .text(impuesto.impuesto_texto) // El texto del impuesto
            );
        }
    }
    $(select).appendTo(header).on('change', function() {
        var val = $(this).val();
        column.search(val ? '^' + val + '$' : '', true, false).draw();
    });
} else {
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
}
                   

                
                });
            
                              
                
            },
            options.columns= [{
                    data: 'ref',
                    searchable: true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'Ref');
                    } , render: function(data, type, row) {   return `<a href="${ENLACE_WEB}productos_editar/${row.rowid}"> ${data} </a>`}
                },
                {
                    'data': 'nombre',
                    searchable: true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'Nombre');
                    },
                    render: function(data, type, row) {

                        let img = '';

                        <?php if ($Entidad->sincronizaciones[1]): ?>


                            if (row.sincronizado == 1) {
                                img = '<img title="' + row.title + '" style="width: 5%;" src="<?= ENLACE_WEB . '/bootstrap/img/quickbooks_activo.png' ?>">';

                            } else {
                                img = '<img title="' + row.title + '" style="width: 5%;" src="<?= ENLACE_WEB . '/bootstrap/img/quickbooks_inactivo.png' ?>">';

                            }

                        <?php endif; ?>

                        return `<a title="${row.title}"  href="${ENLACE_WEB}productos_editar/${row.rowid}">
                        ${img}
                              ${data} </a>`
                    }
                },
                {
                    data: 'stock',
                    searchable: true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'Stock');
                    }
                },
                {
                    data: 'ventas',
                    searchable: true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'Ventas');
                    },
                    render: function(data, type, row) {
                        console.warn(data)
                        if (data == 1) {
                            return `<i class="fa fa-fw fa-check-circle"></i>`;
                        } else {
                            return '-';
                        }

                    }
                },
                {
                    data: 'compras',
                    searchable: true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'Compras');
                    },
                    render: function(data, type, row) {
                        if (data == 1) {
                            return `<i class="fa fa-fw fa-check-circle"></i>`;
                        } else {
                            return '-';
                        }

                    }
                },
                {
                    data: 'subtotal',
                    searchable: true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'Base');
                    }
                },
                {
                    data: 'impuesto',
                    searchable: true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'Imp');
                    }
                },
                {
                    data: 'total',
                    searchable: true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'Total');
                    }
                }
            ]
        


        
        vtabla = $('#style-3').DataTable(options)
        let newButton = $('<button>')
        .html('<i class="fa-solid fa-plus"></i> Nuevo artículo')
        .addClass('btn btn-primary')
        .attr("type", "button")
        .on('click', function() {
            window.location = '<?= ENLACE_WEB ?>productos_nuevo';
        });

        setting_table(vtabla, [newButton]);  

    }


  
</script>


<script>
    $(document).ready(function() {
        cargar_tabla_productos();

        // Desactivar todos los elementos del menú
        $(".menu").removeClass('active');

        $(".productos").addClass('active');
        $(".productos > .submenu").addClass('show');
        $("#productos_listado").addClass('active');


       


    });
</script>




<!-- Scripts -->
<?php # include ENLACE_SERVIDOR . 'mod_productos/tpl/scripts_listado.php'; 
?>