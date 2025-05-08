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
    #export-buttons-container {
        min-width: 500px !important;
    }
</style>
<div class="middle-content container-fluid p-0">
    <!-- BREADCRUMB -->
    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Gastos</a></li>
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
                  
                                        <th class="text-left" scope="col">Factura</th>
                                        <th class="text-left" scope="col">Proveedor</th>
                                        <th class="text-left" scope="col">Fecha</th>
                                        <th class="text-left" scope="col">Usuario</th>
                                        <th class="text-left" scope="col">Monto</th>
                                        <th class="text-left" scope="col">Cuenta de Gasto</th>
                               
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
<script src="<?php echo ENLACE_WEB ?>bootstrap/fe_commons.js?v=<?php echo time(); ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

<script>
    function cargar_tabla() {
        vtabla = $('#style-3').DataTable({
            'Processing': true,
            'serverSide': true,
            "bSort": false,
            "pageLength": 50,
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
                'url': '<?php echo ENLACE_WEB; ?>mod_gastos_europa/json/listado.json.php',
                'Method': 'POST'
            },
            retrieve: true,
            deferRender: true,
            scroller: true,
            responsive: true,
            "initComplete": function() {
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
                    var inputContainer = $('<div>').css({
                        'width': '100%',
                        'height': '100%',
                        'position': 'relative'
                    }).appendTo(header);

                    if (headerText === 'Fecha') {
                        // Input de fecha con placeholder
                        $('<input type="date" class="form-control">')
                            .attr('placeholder', headerText) // Usar el texto del encabezado como placeholder
                            .attr('titulo', headerText)
                            .appendTo(inputContainer)
                            .on('change', function() {
                                var val = $(this).val();
                                column
                                    .search(val ? '^' + val + '$' : '', true, false)
                                    .draw();
                            });
                    }else if (headerText === 'Estado') {
                    // Agrega un spinner mientras se cargan los estados
                    $('<div id="spinnerloading" titulo="Estado">Loading...</div>').appendTo(header);

                    // Llama a obtenerEstados para cargar datos
                    obtenerEstados().done(function(data) {
                        // Crear el select
                        var select = $('<select>')
                            .addClass('form-control')
                            .attr('titulo', headerText)
                            .append($('<option>').val('').text('Todos')) // Opción por defecto
                            .append($('<option>').val('1').text('Pagado')) // Opción "Pagado"
                            .append($('<option>').val('0').text('Pendiente')); // Opción "No Pagado"

                        // Añadir estados del servidor
                        /*if (data && data.estados) {
                            data.estados.forEach(function(estado) {
                                select.append($('<option>').val(estado.estado_hacienda).text(estado.estado_hacienda));
                            });
                        }*/

                        // Reemplazar el contenido de #spinnerloading con el select
                        $('#spinnerloading').empty().append(select);

                        // Manejar el evento change del select
                        select.on('change', function() {
                            var val = $(this).val();
                            column.search(val ? '^' + val + '$' : '', true, false).draw();
                        });

                    }).fail(function() {
                        $('#spinnerloading').text('Error al cargar');
                    });
                } 
                    else {
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
            },
            'columns': [
                {
                    data: 'factura',
                    searchable: true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'factura');
                        $(td).addClass('text-left').addClass('py-0 px-3');
                    },
                    render: function(data, type, row) {
                        return `
                        <a href="${ENLACE_WEB}ver_gasto/${row.id}">
                            <i class="fa-regular fa-file-lines"></i> ${row.factura}
                        </a>
                        `;
                    }
                },
                {
                    data: 'proveedor',
                    searchable: true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'proveedor');
                        $(td).addClass('text-left').addClass('py-0 px-3');
                    },
                    render: function(data, type, row) {

                        if (row.proveedor == null  ) { 
                            return `<strong >Proveedor Gen&eacute;rico</strong>`;
                        } else{

                            url_proveedor = '<?php echo ENLACE_WEB; ?>proveedores_editar/'+row.proveedor_id;
                        return `
                            <strong><a target="_blank" href="${url_proveedor}">${row.proveedor}</a></strong>                  
                        `;
                        }

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
                    data: 'usuario',
                    searchable: true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'usuario');
                        $(td).addClass('text-left').addClass('py-0 px-3');
                    },
                    render: function(data, type, row) {

                        console.log(row)

                        return ` <div class="avatar-chip mb-2 me-4 position-relative" >
                           
                                <span class="text" >${row.usuario}</span>
                            </div>`

                    }
                },  
                {
                    data: 'monto',
                    searchable: true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'monto');
                        $(td).addClass('text-left').addClass('py-0 px-3');
                    },
                    render: function(data, type, row) {
                        return row.monto;
                    }
                },              
                {
                    data: 'cuenta_de_gasto',
                    searchable: true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'cuenta_de_gasto');
                        $(td).addClass('text-left').addClass('py-0 px-3');
                    },
                    render: function(data, type, row) {
                        return row.cuenta_de_gasto;
                    }
                },
            
                {
                    data: 'estado',
                    searchable: true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'estado_final');
                        var span = $('<span></span>'); // Crea un elemento span
                        span.text(cellData); // Asigna el texto de la celda al span
                        if (cellData === '0') {
                            span.addClass("p-2 badge badge-light-warning"); // Clase específica para estado 0 o 'Borrador'
                        } else {
                            var customClass = "p-2 badge badge-" + rowData.color; // Concatenar la clase
                            span.addClass(customClass); // Añade la clase correspondiente al color
                        }
                        $(td).empty().append(span);
                        $(td).addClass('text-left').addClass('py-0 px-3');
                    },
                    render: function(data, type, row) {
                        return row.estado || ''; // Usa el valor de 'estado_final' calculado en el backend                
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
                        const xmlExists = row.xmlexists; // Validar si el archivo XML existe


                        // Estilo para enlaces deshabilitados
                        const disabledStyle = 'color: gray; cursor: not-allowed;pointer-events: none;';
                        const activeStyle = 'color: blue; cursor: pointer;';

                        // Elige el estilo según el estado
                        const linkStyle = isBorrador ? disabledStyle : activeStyle;
                        const linkStyleXML = !archivoExistente || isBorrador ? disabledStyle : activeStyle;
                        const linkStyleXMLT = !xmlExists || isBorrador ? disabledStyle : activeStyle;


                        // Enlaces de acciones
                        let htmlAcciones = `
                             `
                        if(isBorrador){
                            htmlAcciones +=
                                `
                                <a href="#" onclick="confirmar_eliminar(${row.id}, ${row.estado}); " id="eliminar" title="Eliminar" style="color:red">
                                    <i class="fa fa-trash fa-xl" style="opacity: 0.3;"></i>
                                </a>`;
                        }
                        htmlAcciones += `</div>`;
                        return htmlAcciones
                    }
                }

                // Agrega aquí las columnas adicionales que necesites
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

        // Setea la URL base al fe_commons.js
        fe_url_location = "<?php echo ENLACE_WEB; ?>";

        cargar_tabla();

        // Desactivar todos los elementos del menú
        $(".menu").removeClass('active');
        $(".gastos").addClass('active');
         // Desactivar todos los elementos del menú
        $(".gastos > .submenu").addClass('show');
        $("#gastos_listado").addClass('active');




        // Crea un contenedor div para los botones
        var buttonContainer = $('<div>').attr("id", "export-buttons-container").addClass('ml-2');

        // Crea el botón de Excel con el icono de Font Awesome
        let newButton = $('<button>')
            .html('<i class="fa-solid fa-plus"></i> Nuevo Gasto')
            .addClass('btn btn-primary')
            .attr("type", "button")
            .on('click', function() {
                window.location = '<?= ENLACE_WEB ?>gastos_crear_nuevo'
            });

        // Crea el botón de Excel con el icono de Font Awesome
        let excelButton = $('<button>')
            .html('<i class="fas fa-file-excel"></i> Exportar Excel')
            .addClass('btn btn-success')
            .attr("type", "button")
            .on('click', function() {
                exportTableToExcel('style-3', 'Gastos_Listado.xlsx');
            });

        // Crea el botón de PDF con el icono de Font Awesome
        let pdfButton = $('<button>')
            .html('<i class="fas fa-file-pdf"></i> Exportar PDF')
            .addClass('btn btn-danger')
            .attr("type", "button")
            .on('click', function() {
                exportTableToPDF('style-3', 'Gastos_Listado.pdf');
            });

        // Agrega los botones al contenedor
        buttonContainer.append(newButton, excelButton, pdfButton);

        // Coloca el contenedor de botones en el lugar deseado dentro de la interfaz
        buttonContainer.appendTo("#style-3_length");


        // Función para limpiar los encabezados de la tabla
        function cleanTableForExport(tableID) {
            // Clona la tabla para no modificar la original
            var tableClone = $(`#${tableID}`).clone();

            // Remueve inputs y selects de los encabezados
            // tableClone.find('th').each(function() {
            //     $(this).find('input, select').remove(); // Elimina inputs y selects
            // });
            tableClone.find('th').each(function(element) {
                let titulo = $(this).find('input').attr('titulo')
                if(titulo == undefined){
                    titulo = $(this).find('select').attr('titulo')
                }
                if(titulo == undefined){
                    titulo = $(this).find('#spinnerloading').attr('titulo')
                }
                if(titulo!='' && titulo!= undefined && titulo.trim() != 'Loading...'){
                    $(this).html(titulo)
                }
            });
            // tableClone.find('th').remove();
            
            tableClone.find('td[data-label="fecha"]').each(function(element) {
                // console.log('a:'+$(`#${tableID}`).find('td[data-label="fecha"]')[element].innerHTML+':b')
                const split_text = $(this).html().split('-')
                
                let dias_limpio= split_text[0]
                const fecha = moment([split_text[2], split_text[1] - 1])
                const dias_del_mes = fecha.daysInMonth();
                const mes = fecha.format('MMMM')
                // console.log(mes, dias_del_mes, split_text[0])
                if(split_text[0] < dias_del_mes){
                    dias_limpio = (parseInt(split_text[0])+1).toString().padStart(2, '0')
                    $(this).text(split_text[2]+'/'+split_text[1]+'/'+ dias_limpio )
                }else{
                    $(this).text( moment([split_text[2], split_text[1]-1, fecha.daysInMonth()]).format('DD/MM/YYYY') )
                }
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
    function setReferenciaGlobal(referencia) {
        globalReferencia = referencia; // Almacena la referencia en la variable global
    }
    function generarPdf(id) {
        $.ajax({
                method: "POST",
                url: "<?php echo ENLACE_WEB; ?>mod_documentos_mercantiles/ajax/descargar_documento.ajax.php",
                data: {
                    documento: id, // Usa el ID pasado como argumento
                    tipo: "factura"
                },
                beforeSend: function(xhr) {
                    // Opcional: mostrar un loader o mensaje
                }
            })
            .done(function(msg) {
                if(msg != ''){
                    const linkSource = `data:application/pdf;base64,${msg}`;
                    const downloadLink = document.createElement("a");
                    const fileName = `PDF_${globalReferencia}.pdf`;
    
                    downloadLink.href = linkSource;
                    downloadLink.download = fileName;
                    downloadLink.click();
                }else{
                    add_notification({
                        text: 'Error: Documento no generado',
                        actionTextColor: '#fff',
                        backgroundColor: '#e7515a',
                    });
                }
            });
    }


    function obtenerEstados() {
    return $.ajax({
        url: "<?php echo ENLACE_WEB; ?>mod_europa_facturacion/ajax/obtener_estado.php",
        type: 'POST',
        dataType: 'json'
    });
}

</script>
