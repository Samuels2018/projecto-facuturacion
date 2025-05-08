<script>

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
            if (titulo == undefined) {
                titulo = $(this).find('select').attr('titulo')
            }
            if (titulo == undefined) {
                titulo = $(this).find('#spinnerloading').attr('titulo')
            }
            if (titulo != '' && titulo != undefined && titulo.trim() != 'Loading...') {
                $(this).html(titulo)
            }
        });
        // tableClone.find('th').remove();

        tableClone.find('td[data-label="fecha"]').each(function(element) {
            // console.log('a:'+$(`#${tableID}`).find('td[data-label="fecha"]')[element].innerHTML+':b')
            const split_text = $(this).html().split('-')

            let dias_limpio = split_text[0]
            const fecha = moment([split_text[2], split_text[1] - 1])
            const dias_del_mes = fecha.daysInMonth();
            const mes = fecha.format('MMMM')
            // console.log(mes, dias_del_mes, split_text[0])
            if (split_text[0] < dias_del_mes) {
                dias_limpio = (parseInt(split_text[0]) + 1).toString().padStart(2, '0')
                $(this).text(split_text[2] + '/' + split_text[1] + '/' + dias_limpio)
            } else {
                $(this).text(moment([split_text[2], split_text[1] - 1, fecha.daysInMonth()]).format('DD/MM/YYYY'))
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

    function cargar_tabla_dato_contacto() {

        vtabla = $('#tabla-contactos').DataTable({
            'Processing': true,
            'serverSide': true,
            "bSort": false,
            "pageLength": 100,
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
                'url': '<?php echo ENLACE_WEB; ?>mod_terceros/ajax/ver_contactos.ajax.php?id=<?php echo $_GET['fiche']; ?>',
                'Method': 'POST'
            },
            retrieve: true,
            deferRender: true,
            scroller: true,
            responsive: true,
            initComplete: function() {
                var api = this.api();
                api.columns().every(function() {
                    var column = this;
                    var header = $(column.header());
                    var headerText = header.text();

                    // Limpia el encabezado y elimina el <span>
                    header.empty();
                    // Condición para no añadir input en la columna "Acciones"
                    if (headerText === 'Acciones' || headerText === 'Tipo') {
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
                    } else if (headerText === 'Estado') {
                        // Agrega un spinner mientras se cargan los estados
                        $('<div id="spinnerloading" titulo="Estado">Loading...</div>').appendTo(header);

                        $('#spinnerloading').remove()
                        var select = $('<select>')
                            .addClass('form-control')
                            .attr('titulo', headerText)
                            .append($('<option>').val('').text('Todos')) // Opción por defecto
                            .append($('<option>').val('Borrador').text('Borrador')) // Opción "Borrador" por defecto
                            .appendTo(header);


                        ['Activo', 'Inactivo'].forEach(function(estado) {
                            select.append($('<option>').val(estado == 'Activo' ? 1 : 0).text(estado));
                        });

                        select.on('change', function() {
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
            },
            columns: [{
                    data: 'Label',
                    render: function(data, type, row) {
                        return `<a href="#" onclick="abrir_modal_modificar_contacto(${row.ID}, '${row.Dato}', '${row.Detalle}', ${row.Icono} )">
                            <i class="fa fa-fw ${iconosContacto[row.Icono] ?? iconosContacto.default}"></i>
                            ${data}
                            </a>`
                    }
                },
                {
                    data: 'Dato',
                    searchable: true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'Dato');
                    },
                    render: function(data, type, row) {
                        return `<a href="#" onclick="abrir_modal_modificar_contacto(${row.ID}, '${row.Dato}', '${row.Detalle}', ${row.Icono} )">
                            ${row.Dato}
                        </a>`
                    }
                },
                {
                    data: 'Detalle',
                    searchable: true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'Cantidad');
                    },
                    render: function(data, type, row) {
                        return `<a href="#" onclick="abrir_modal_modificar_contacto(${row.ID}, '${row.Dato}', '${row.Detalle}', ${row.Icono} )">
                            ${row.Detalle}
                        </a>`
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
                        // Enlaces de acciones
                        let htmlAcciones = `
                                <span style="cursor:pointer;" onclick="eliminar_dato_contacto('${row.ID}')">
                                <i class="fa fa-trash"></i></span>`;
                        return htmlAcciones
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
        $('#tabla-contactos_wrapper').prepend(configIcon);
        $('#tabla-contactos_wrapper').prepend(columnVisibilityContainer);

        // Mostrar/ocultar el div cuando se hace clic en el icono de configuración
        configIcon.on('click', function() {
            columnVisibilityContainer.toggle();
        });


        // Crea un contenedor div para los botones
        var buttonContainer = $('<div>').attr("id", "export-buttons-container").addClass('ml-2');

        // Crea el botón de Excel con el icono de Font Awesome
        let newButton = $('<button>')
            .html('<i class="fa-solid fa-plus"></i> Nuevo contacto')
            .addClass('btn btn-info _effect--ripple waves-effect waves-light')
            .attr("data-bs-toggle", "modal")
            .attr("data-bs-target", "contactoModal")
            .attr("data-invoice-id", "<?= $id ?>")
            .on('click', function() {
                event.preventDefault()
                $('#contactoModal').modal('show')
            });

        // Crea el botón de Excel con el icono de Font Awesome
        let excelButton = $('<button>')
            .html('<i class="fas fa-file-excel"></i> Exportar Excel')
            .addClass('btn btn-success')
            .attr("type", "button")
            .on('click', function() {
                exportTableToExcel('tabla-contactos', 'Contactos_Listado.xlsx');
            });

        // Crea el botón de PDF con el icono de Font Awesome
        let pdfButton = $('<button>')
            .html('<i class="fas fa-file-pdf"></i> Exportar PDF')
            .addClass('btn btn-danger')
            .attr("type", "button")
            .on('click', function() {
                exportTableToPDF('tabla-contactos', 'Contactos_Listado.pdf');
            });

        // Agrega los botones al contenedor
        buttonContainer.append(newButton, excelButton, pdfButton);

        // Coloca el contenedor de botones en el lugar deseado dentro de la interfaz
        buttonContainer.appendTo("#tabla-contactos_length");
    }

    
    function obtener_contactos() {
        // Inicializa la tabla
        var vtabla = $('#contactos_crm_table').DataTable({
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
            ajax: {
                url: '<?php echo ENLACE_WEB; ?>mod_terceros/ajax/listado.contactos.ajax.php?fiche='+ $('[name=fiche]').val(),
            },
            retrieve: true,
            deferRender: true,
            scroller: true,
            responsive: true,
            initComplete: function() {
                this.api().columns().every(function() {
                    var column = this;
                    var header = $(column.header());
                    var headerText = header.text(); // Guarda el texto original del encabezado
                    header.empty(); // Limpia el encabezado

                    // Crea un contenedor div para el texto del encabezado
                    var headerTextContainer = $('<div>').addClass('text-center').appendTo(header);
                    $('<span>').text(headerText)
                    .appendTo(headerTextContainer);

                    // Crea un contenedor div para el input/select
                    var inputContainer = $('<div>').appendTo(header);

                    if (column.dataSrc() != null) {
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
            columns: [{
                      data: 'nombre',
                      searchable: true,
                      createdCell: function(td, cellData, rowData, row, col) {
                          $(td).attr('data-label', 'nombre');
                      },
                        render: function(data, type, row) {
                            return `<a href="#" onclick="fetch_contacto(${row.ID})">
                            ${row.nombre+' '+row.apellidos}
                            </a>`
                        }
                  },
                  {
                      data: 'puesto_t',
                      searchable: true,
                      createdCell: function(td, cellData, rowData, row, col) {
                          $(td).attr('data-label', 'puesto_t').addClass('text-center');
                      },
                        render: function(data, type, row) {
                            return `<a href="#" onclick="fetch_contacto(${row.ID})">
                            ${row.puesto_t}
                            </a>`
                        }
                  },
                  {
                      data: 'email',
                      searchable: true,
                      createdCell: function(td, cellData, rowData, row, col) {
                          $(td).attr('data-label', 'email');
                      },
                        render: function(data, type, row) {
                            return `<a href="#" onclick="fetch_contacto(${row.ID})">
                            ${row.email}
                            </a>`
                        }
                  },
                  {
                      data: null,
                      searchable: false,
                      createdCell: function(td, cellData, rowData, row, col) {
                          $(td).attr('data-label', 'facebook').addClass('text-center');
                      },
                        render: function(data, type, row) {
                            let rrss = '';
                            if (row.facebook != '') {
                                rrss += `<a href="https://www.facebook.com/${row.facebook}" target="_blank">
                                                    <i class="fa fa-fw fa-facebook"></i>
                                                </a>`
                            } else {
                                rrss += ``
                            }
                            if (row.instagram != '') {
                                rrss += `<a href="https://instagram.com/${row.instagram}" target="_blank">
                                                    <i class="fa fa-fw fa-instagram"></i>
                                                </a>`;
                            } else {
                                rrss += ``
                            }
                            if (row.x_twitter != '') {
                                rrss += `<a href="https://twitter.com/${row.x_twitter}" target="_blank">
                                                    <i class="fa fa-fw fa-twitter"></i>
                                                </a>`;
                            } else {
                                rrss += ``;
                            }
                            if (row.linkedin != '') {

                                rrss += `<a href="https://www.linkedin.com/in/${row.linkedin}" target="_blank"><i class="fa fa-fw fa-linkedin"></i></a>`;
                            } else {
                                rrss += ``
                            }
                            return `<a href="#" onclick="fetch_contacto(${row.ID})">
                            ${rrss}
                            </a>`
                        }
                  },
                  {
                      data: 'whatsapp',
                      searchable: true,
                      createdCell: function(td, cellData, rowData, row, col) {
                          $(td).attr('data-label', 'whatsapp').addClass('text-center');
                      },
                        render: function(data, type, row) {
                            return `<a href="#" onclick="fetch_contacto(${row.ID})">
                            ${row.whatsapp}
                            </a>`
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
                        // Enlaces de acciones
                        let htmlAcciones = `
                                <span style="cursor:pointer; color:red;" onclick="eliminar_contacto(${row.ID})">
                                <i class="fa fa-trash"></i></span>`;
                        return htmlAcciones
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
        $('#contactos_crm_table_wrapper').prepend(configIcon);
        $('#contactos_crm_table_wrapper').prepend(columnVisibilityContainer);

        // Mostrar/ocultar el div cuando se hace clic en el icono de configuración
        configIcon.on('click', function() {
            columnVisibilityContainer.toggle();
        });
    }

    $(function() {
        $("#fecha_nac").datepicker({
            changeMonth: true,
            changeYear: true,
            yearRange: "1900:<?php echo date('Y'); ?>",


            dateFormat: 'yy-mm-dd',
            showButtonPanel: true,
            closeText: 'Cerrar',
            prevText: '<Ant',
            nextText: 'Sig>',
            currentText: 'Hoy',
            monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo',
                'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
            ],
            monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
            dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
            dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
            weekHeader: 'Sm',
            firstDay: 1,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ''



        });

        
        cargar_tabla_dato_contacto();
        obtener_contactos();

        
    // Crea un contenedor div para los botones
    var buttonContainer = $('<div>').attr("id", "export-buttons-container").addClass('ml-2');

    // Crea el botón de Excel con el icono de Font Awesome
    let newButton = $('<button>')
        .html('<i class="fa-solid fa-plus"></i> Nuevo contacto')
        .addClass('btn btn-primary')
        .attr("type", "button")
        .on('click', function() {
            event.preventDefault();
            mostrar_modal();
        });

    // Crea el botón de Excel con el icono de Font Awesome
    let excelButton = $('<button>')
        .html('<i class="fas fa-file-excel"></i> Exportar Excel')
        .addClass('btn btn-success')
        .attr("type", "button")
        .on('click', function() {
            exportTableToExcel('contactos_crm_table', 'Contactos_Listado.xlsx');
        });

    // Crea el botón de PDF con el icono de Font Awesome
    let pdfButton = $('<button>')
        .html('<i class="fas fa-file-pdf"></i> Exportar PDF')
        .addClass('btn btn-danger')
        .attr("type", "button")
        .on('click', function() {
            exportTableToPDF('contactos_crm_table', 'Contactos_Listado.pdf');
        });

    // Agrega los botones al contenedor
    buttonContainer.append(newButton, excelButton, pdfButton);

    // Coloca el contenedor de botones en el lugar deseado dentro de la interfaz
    buttonContainer.appendTo("#contactos_crm_table_length");

    });
</script>

<script>
    $("#fk_pais").change(function() {
        $fk_pais = $(this).val();
        $.ajax({
            method: "POST",
            url: '<?= ENLACE_WEB ?>/mod_empresa/class/clases.php',
            beforeSend: function(xhr) {},

            data: {
                "action": "BuscarComunidadesAutonomas",
                fk_pais: $fk_pais,
            },
        }).done(function(data) {
            $("#poblacion").html(data);
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.error("Error en la petición AJAX:", textStatus, errorThrown);

            add_notification({
                text: 'Error con la Peticion - Vuelve a Intentarlo',
                actionTextColor: '#fff',
                backgroundColor: '#e7515a'
            });
        });
    });
    //Seleccionar Población / Comunidad Autonoma
    $("#poblacion").change(function() {
        $poblacion = $(this).val();
        $.ajax({
            method: "POST",
            url: '<?= ENLACE_WEB ?>/mod_empresa/class/clases.php',
            beforeSend: function(xhr) {},
            data: {
                "action": "BuscarProvincias",
                fk_comunidad_autonoma: $poblacion,
            },
        }).done(function(data) {
            $("#direccion_fk_provincia").html(data);
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.error("Error en la petición AJAX:", textStatus, errorThrown);

            add_notification({
                text: 'Error con la Peticion - Vuelve a Intentarlo',
                actionTextColor: '#fff',
                backgroundColor: '#e7515a'
            });
        });
    });
    //Seleccionar provincias
    $('#direccion_fk_provincia').on('change', function() {
        $fk_provincia = $(this).val();
        $.ajax({
            method: "POST",
            url: '<?= ENLACE_WEB ?>/mod_empresa/class/clases.php',
            beforeSend: function(xhr) {},
            data: {
                "action": "BuscarMunicipios",
                fk_provincia: $fk_provincia,
            },
        }).done(function(data) {
            $("#direccion_fk_municipio").html(data);
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.error("Error en la petición AJAX:", textStatus, errorThrown);

            add_notification({
                text: 'Error con la Peticion - Vuelve a Intentarlo',
                actionTextColor: '#fff',
                backgroundColor: '#e7515a'
            });
        });
    });
</script>


<script>
    $(document).ready(function() {
        enmascara($("#tipo").val());
    });

    // FUNCTION VALID PROFILE TYPE
    function enmascara(x) {
        $("#fisico_juridico").empty();
        $("#fisico_juridico_t1").empty();

        t = "Apellidos";
        t1 = "Nombre";

        // VALID PROFILE TYPE
        if (x == "fisica" || x == "") {
            t = "Apellidos";
            t1 = "Nombre";
            $("#fecha_nacimiento").show();
            $("#div_nombre_comercial_fisica").show();
        } else if (x == "juridica") {
            t = "Nombre Comercial";
            t1 = "Razón Social";
            $("#fecha_nacimiento").hide();
            $("#div_nombre_comercial_fisica").hide();
        }

        $("#fisico_juridico_t1").html(t1);
        $("#fisico_juridico").html(t);
    }
</script>


<!-- Actualizar información de los impuestos-->
<script type="text/javascript">
    function actualizar_informacion_impuestos() {

        impuesto_cliente_fk_diccionario_regimen_iva = $("#regimen_iva").val();

        if ($("#recargo_equivalencia").is(':checked') === true) {
            impuesto_cliente_aplica_recargo_equivalencia = 1;
        } else {
            impuesto_cliente_aplica_recargo_equivalencia = 0;
        }


        if ($("#retencion").is(':checked') === true) {
            impuesto_cliente_lleva_retencion = 1;
        } else {
            impuesto_cliente_lleva_retencion = 0;
        }

        impuesto_cliente_regimen_iva_tipos_retencion = $("#tipo_retencion").val();


        // Preparar la petición AJAX
        $.ajax({
            method: "POST",
            url: "<?php echo ENLACE_WEB; ?>mod_terceros/class/terceros.class.php",
            data: {
                action: 'modificacion_impuestos',
                impuesto_cliente_fk_diccionario_regimen_iva: impuesto_cliente_fk_diccionario_regimen_iva,
                impuesto_cliente_aplica_recargo_equivalencia: impuesto_cliente_aplica_recargo_equivalencia,
                impuesto_cliente_lleva_retencion: impuesto_cliente_lleva_retencion,
                impuesto_cliente_regimen_iva_tipos_retencion: impuesto_cliente_regimen_iva_tipos_retencion,
                rowid: "<?php echo isset($_REQUEST['fiche']) ? $_REQUEST['fiche'] : 0; ?>"
            },
        }).done(function(msg) {

            console.log(msg);

            const response = JSON.parse(msg);
            console.log(response.sql);
            if (response.error == 1) {
                add_notification({
                    text: response.datos,
                    pos: 'top-right',
                    actionTextColor: '#fff',
                    backgroundColor: '#e7515a'
                });
            } else {

                $(".msj-ajax-cliente").remove();

                <?php if ($Entidad->sincronizaciones[1]) : ?>
                    sincronizar(response.lastid, true, (rsp) => {

                        add_notification({
                            text: 'Impuestos actualizados exitosamente!',
                            actionTextColor: '#fff',
                            backgroundColor: '#00ab55',
                            dismissText: 'Cerrar'
                        })

                        add_notification({
                            text: rsp.message,
                            actionTextColor: '#fff',
                            backgroundColor: '#00ab55',
                            dismissText: 'Cerrar'
                        })

                        /* setTimeout(() => {
                             window.location.href = "<?php echo ENLACE_WEB; ?>clientes_editar/" + response.lastid;
                         }, 3000);*/

                    });

                <?php else : ?>

                    add_notification({
                        text: 'Tercero creado exitosamente!',
                        actionTextColor: '#fff',
                        backgroundColor: '#00ab55',
                        dismissText: 'Cerrar'
                    })


                    /*setTimeout(() => {
                        window.location.href = "<?php echo ENLACE_WEB; ?>clientes_editar/" + response.lastid;
                    }, 3000);*/

                <?php endif; ?>

            }

        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.error("Error en la petición AJAX:", textStatus, errorThrown);

            add_notification({
                text: 'Hubo un error al actualizar la información.',
                pos: 'top-right',
                actionTextColor: '#fff',
                backgroundColor: '#e7515a'
            });
        });

    }
</script>


<script>
    function sumar_contacto() {

        console.log("Inicio del Proceso");
        var _data = $("#contacto_data").val();
        var _tipo = $("#contacto_tipo").val();
        var _detalle = $("#contacto_detalle").val();

        $("#zona_contacto").empty().html("<center><strong style='font-size:18px;'><i class='fa fa-fw fa-rotate-right fa-spin'></i></strong></center>");


        $.post("<?php echo ENLACE_WEB; ?>mod_terceros/ajax/ver_contactos.tpl.php?fiche=<?php echo $_REQUEST['fiche'] ?>", {
                data: _data,
                tipo: _tipo,
                detalle: _detalle
            })
            .done(function(data) {

                $("#zona_contacto").empty();
                $("#zona_contacto").html(data);

                // alert( "Data Loaded: " + data );
            });

        console.log("Fin  del Proceso");

    }
</script>


<script>
    function eliminar_contacto(x) {


        if (confirm("Deseas eliminar el Contacto ?")) {

            $("#zona_contacto").empty().html("<center><strong style='font-size:18px;'><i class='fa fa-fw fa-rotate-right fa-spin'></i></strong></center>");


            console.log("Inicio del Proceso Eliminar " + x);
            $.post("<?php echo ENLACE_WEB; ?>mod_terceros/ajax/ver_contactos.tpl.php?fiche=<?php echo $_REQUEST['fiche'] ?>", {
                    eliminar: x
                })
                .done(function(data) {

                    $("#zona_contacto").empty();
                    $("#zona_contacto").html(data);

                    // alert( "Data Loaded: " + data );
                });

            console.log("Fin  del Proceso");

        }
    }
</script>

<script>
    function adminExo(id) {

        let tipo_documento = $('#tipoDocumento').val();
        let numero_documento = $('#numeroDocumento').val();
        let nombre_institucion = $('#nombreInstitucion').val();
        let fecha_emision = $('#fechaEmsionDocumento').val();

        if (tipo_documento == '' ||
            numero_documento == '' ||
            nombre_institucion == '' ||
            fecha_emision == '') {
            add_notification({
                text: 'Debe seleccionar todos los campos!',
                actionTextColor: '#fff',
                backgroundColor: '#e7515a',
                dismissText: 'Cerrar'
            })

            return false;
        }


        var accionGet = "";
        var e = <?php echo $_SESSION['Entidad']; ?>;
        var i = <?php echo $_GET['fiche'] ? $_GET['fiche'] : 0; ?>;




        if (id == 0) {
            accionGet = "agregar_exoneracion";
        } else {
            accionGet = "desactivar_exoneracion";
        }
        if (id == 0) {
            var texto = "Deseas agregar esta exoneracion para este cliente?";
        } else {
            var texto = "Deseas desactivar esta exoneracion para este cliente?";
        }

        ////Nuevo Proceso
        var actionText = "Confirmar";

        // Mostrar el snackbar y definir el callback para el botón de acción
        add_notification({
            text: texto,
            width: 'auto',
            duration: 30000,
            actionText: actionText,
            dismissText: 'Cerrar',
            onActionClick: function(element) {

                $.ajax({
                    method: "POST",
                    url: "<?php echo ENLACE_WEB; ?>mod_terceros/class/terceros.class.php",
                    beforeSend: function(xhr) {
                        $('#texto_notas').empty();
                        $('#texto_notas').html(`<img src="${ENLACE_WEB}images/salvando.gif" style="width:70px">`);

                    },
                    data: {
                        action: accionGet,
                        ee: e,
                        ii: i,
                        rowid: id,
                        tipo: $('#tipoDocumento').val(),
                        numero: $('#numeroDocumento').val(),
                        nombre: $('#nombreInstitucion').val(),
                        fecha: $('#fechaEmsionDocumento').val()
                    },
                }).done(function(data) {

                    console.log(data);
                    const response = JSON.parse(data);
                    console.log('data es: ' + response.error);
                    if (response.error == 0) {
                        add_notification({
                            text: 'Exoneracion  gestionada exitosamente!',
                            actionTextColor: '#fff',
                            backgroundColor: '#00ab55',
                            dismissText: 'Cerrar'
                        })

                        $('#tipoDocumento').val('');
                        $('#numeroDocumento').val('');
                        $('#nombreInstitucion').val('');
                        $('#fechaEmsionDocumento').val('');

                        $('#style-3').DataTable().destroy();
                        cargar_tabla_exoneraciones_cliente();

                    } else {

                        add_notification({
                            text: response.datos ? response.datos : response,
                            pos: 'top-right',
                            actionTextColor: '#fff',
                            backgroundColor: '#e7515a'
                        });

                    }

                    // Aquí puedes añadir código para actualizar la interfaz de usuario si es necesario
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    console.error("Error en la petición AJAX:", textStatus, errorThrown);

                    add_notification({
                        text: 'Hubo un error al agregar la exoneracion.',
                        actionTextColor: '#fff',
                        backgroundColor: '#e7515a'
                    })

                });


            },

        });

    }
</script>

<script>
    // Dirreccion del cliente  - @rojasarmando - 13-06-2024
    const get_data_address_customer = () => {

        let data_address = {
            'pais': $('[name="pais"]').val(),
            'codigo_postal': $('[name="codigo_postal"]').val(),
            'provincia': $('[name="provincia"]').val(),
            'direccion': $('[name="direccion"]').val(),
            'fk_pais': $('[name="fk_pais"]').val(),
            'poblacion': $('[name="poblacion"]').val(),
            'direccion_fk_provincia': $('[name="direccion_fk_provincia"]').val(),
            'codigo_postal': $('[name="codigo_postal"]').val(),
            'direccion': $('[name="direccion"]').val(),
        };

        return data_address;
    }
    // ------------


    function crear_tercero(event) {
        event.preventDefault();
        var errores_detalles = [];


        ///Removemos todo primero 
        $(".input_error").removeClass("input_error");

        error = false;

 

      


        // Recoger los valores del formulario usando jQuery
        let forma_pago = $('[name="fk_forma_pago"]').val();
        let nombre = $('[name="nombre"]').val();
        let apellidos = $('[name="apellidos"]').val();
        let cedula = $('[name="cedula"]').val();
        let telefono = $('[name="telefono"]').val();
        let email = $('[name="email"]').val();
        let cliente = $('[name="cliente"]').is(':checked') ? 1 : 0;
        let proveedor = $('[name="proveedor"]').is(':checked') ? 1 : 0;
        let credito = $('[name="credito"]').val();
        let nota = $('[name="nota"]').val();
        let fecha_nacimiento = $('[name="fecha_nacimiento"]').val();
        let rx = $('[name="rx"]').val();
        let addd = $('[name="add"]').val();
        let tipo = $('[name="tipo"]').val();
        let DP1 = $('[name="DP1"]').val();
        let DP2 = $('[name="DP2"]').val();
        let comercial = $('[name="comercial"]').val();

        let banco_cuenta = $('[name="banco_cuenta"]').val();
        let nombre_banco = $('[name="nombre_banco"]').val();
        let banco_entidad = $('[name="banco_entidad"]').val();
        let banco_oficina = $('[name="banco_oficina"]').val();
        let banco_digito_control = $('[name="banco_digito_control"]').val();
        let swift1 = $('[name="swift1"]').val();
        let swift2 = $('[name="swift2"]').val();
        let fk_categoria_cliente = $("[name='fk_categoria_cliente']").val();
        let fk_tipo_residencia = $("[name='tipo_residencia']").val();
        let fk_tipo_identificacion = $("[name='fk_tipo_identificacion']").val();
        let nombre_comercial_fisica = $('#nombre_comercial_fisica').val()


        // Dirreccion del cliente  - @rojasarmando - 13-06-2024
        let datos_direccion = get_data_address_customer()
        // ------------


        /* Valida los inputs requeridos */
        const inputTypes = [];
        $('#pills-producto input[name][id]').each(function(index, element) {
            inputTypes.push({
                name: $(this).attr('id'),
                value: $(this).val(),
                required: ($(this).attr('required') || false)
            })
        });
        $('#pills-producto select[name][id]').each(function(index, element) {
            inputTypes.push({
                name: $(this).attr('id'),
                value: $(this).val(),
                required: ($(this).attr('required') || false)
            })
        });
        $('#pills-producto textarea[name][id]').each(function(index, element) {
            inputTypes.push({
                name: $(this).attr('id'),
                value: $(this).val(),
                required: ($(this).attr('required') || false)
            })
        });
        inputTypes.map(x => $('#' + x.name).removeClass('input_error'))
        inputTypes.map((x) => {
            if (x.required && (x.value == '' || x.value == null)) {
                $('#' + x.name).addClass('input_error');
                error = true;
            }
        })

        if(cliente == 0 && proveedor == 0){
            error = true;
            if(cliente == 0){
                $('[name="cliente"]').addClass('input_error');
            }
            if(proveedor == 0){
                $('[name="proveedor"]').addClass('input_error');
            }
        }
        // Si hay errores, mostrar notificación y detener el envío del formulario


        if ($("#cedula_existe").val() == 'yes') {
            alert("Cedula existe");

            var texto_mensaje = $("#miSelect fk_tipo_identificacion:selected").text();

            // Verifica que el texto no sea null, vacío o solo espacios en blanco
            if (texto_mensaje && texto_mensaje.trim() !== "") {
                texto = texto_mensaje;
            } else {
                texto = "Identificacion Registrada Previamente";
            }


            $('[name="cedula"]').addClass('input_error');


            add_notification({
                text: texto                 ,
                actionTextColor: '#fff'     ,
                backgroundColor: '#e7515a'  ,
            });
            error = true;             
          //  $("#creando_cliente").prop("disabled", true); // Deshabilita el botón antes del envío

 
         }





        if (error) {
            add_notification({
                text: 'Faltan Datos Obligatorios',
                actionTextColor: '#fff',
                backgroundColor: '#e7515a',
            });
            return true;
        }
        /* Valida los inputs requeridos */

        //  $("#creando_cliente").hide(0);
        $("#creando_cliente").attr('disabled', true);
        $(".msj-ajax-cliente").remove();
        $("#creando_cliente").after('<span class="msj-ajax-cliente"> <i class="fa fa-spinner" aria-hidden="true"></i> Creando cliente</span>');


        // Preparar la petición AJAX
        $.ajax({
            method: "POST",
            url: "<?php echo ENLACE_WEB; ?>mod_terceros/class/terceros.class.php",
            data: {
                action: 'nuevo_cliente',
                nombre: nombre,
                apellidos: (tipo=='fisica'? apellidos: ''),
                cedula: cedula,
                telefono: telefono,
                email: email,
                cliente: cliente,
                proveedor: proveedor,
                credito: credito,
                nota: nota,
                fecha_nacimiento: fecha_nacimiento,
                rx: rx,
                add: addd,
                tipo: tipo,
                DP1: DP1,
                DP2: DP2,
                comercial: comercial,
                nombre_banco: nombre_banco,
                banco_entidad: banco_entidad,
                banco_oficina: banco_oficina,
                banco_digito_control: banco_digito_control,
                banco_cuenta: banco_cuenta,
                swift1: swift1,
                swift2: swift2,
                forma_pago: forma_pago

                    // Dirreccion del cliente  - @rojasarmando - 13-06-2024
                    ,
                // pais: datos_direccion['pais'],
                // direccion: datos_direccion['direccion'],
                // provincia: datos_direccion['provincia'],

                fk_pais: datos_direccion['fk_pais'],
                fk_poblacion: datos_direccion['poblacion'],
                direccion_fk_provincia: datos_direccion['direccion_fk_provincia'],
                direccion: datos_direccion['direccion'],
                codigo_postal: datos_direccion['codigo_postal'],

                fk_categoria_cliente: fk_categoria_cliente,
                fk_tipo_residencia: fk_tipo_residencia,
                fk_tipo_identificacion: fk_tipo_identificacion,

                electronica_nombre_comercial: (tipo=='juridica'? apellidos: nombre_comercial_fisica)
            },
        }).done(function(msg) {

            console.log(msg);

            const response = JSON.parse(msg);

            if (response.error == 1) {
                add_notification({
                    text: response.datos,
                    pos: 'top-right',
                    actionTextColor: '#fff',
                    backgroundColor: '#e7515a'
                });
            } else {

                $(".msj-ajax-cliente").remove();
                //ejecutar sinconizacion
                $("#creando_cliente").show(0).attr('disabled', true);




                <?php if ($Entidad->sincronizaciones[1]) : ?>
                    sincronizar(response.lastid, true, (rsp) => {

                        add_notification({
                            text: 'Tercero creado exitosamente!',
                            actionTextColor: '#fff',
                            backgroundColor: '#00ab55',
                            dismissText: 'Cerrar'
                        })

                        add_notification({
                            text: rsp.message,
                            actionTextColor: '#fff',
                            backgroundColor: '#00ab55',
                            dismissText: 'Cerrar'
                        })

                      
                        setTimeout(() => {
                            //Esto viene del archivo principal para saber si es proveedor o cliente la vista
                            labeltext = '<?php echo $labeltext ; ?>';
                            if(labeltext === 'Proveedor')
                            {
                                window.location.href = "<?php echo ENLACE_WEB; ?>proveedores_editar/" + response.lastid;
                            }else{
                                window.location.href = "<?php echo ENLACE_WEB; ?>clientes_editar/" + response.lastid;
                            }
                        }, 3000);

                    });

                <?php else : ?>

                    add_notification({
                        text: 'Tercero creado exitosamente!',
                        actionTextColor: '#fff',
                        backgroundColor: '#00ab55',
                        dismissText: 'Cerrar'
                    })


                    setTimeout(() => {
                        window.location.href = "<?php echo ENLACE_WEB; ?>clientes_editar/" + response.lastid;
                    }, 3000);

                <?php endif; ?>

            }

        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.error("Error en la petición AJAX:", textStatus, errorThrown);

            add_notification({
                text: 'Hubo un error al crear el producto.',
                pos: 'top-right',
                actionTextColor: '#fff',
                backgroundColor: '#e7515a'
            });
        });
    }

    async function sincronizar(id, is_new_customer, callback) {
        console.log('Sincronizando con id: ' + id);
        // ----------- Url QuickBooks Dinamico -- @rojasarmando -- 14-06-2024
        <?php
        const SISTEMA_QUICKBOOK_ID = 1;
        $url_quickbook = $Entidad->obtener_url_externa(SISTEMA_QUICKBOOK_ID);
        ?>
        const url = `<?= $url_quickbook ?>/listener-sistema`;
        //--------------------------------------------------

        const data = {
            type: is_new_customer ? 'Customer' : 'update_customer',
            id: id,
        };

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            } else {
                const jsonResponse = await response.json();
                console.log(jsonResponse);

                callback(jsonResponse)
            }
        } catch (error) {
            console.log('Error en la petición Fetch: ' + error);
            // Mostrar notificación en caso de error en la petición Fetch
            add_notification({
                text: 'Error en la petición Fetch: ' + error,
                actionTextColor: '#fff',
                backgroundColor: '#e7515a'
            });

            $("#creando_cliente").prop("disabled", false);
        }
    }

    function modificar_tercero(event) {
        event.preventDefault();

        var errores_detalles = [];
        ///Removemos todo primero 
        $(".input_error").removeClass("input_error");


        error = false;

        // Recoger los valores del formulario usando jQuery
        let rowid = $('[name="fiche"]').val(); // Asegúrate de que este campo exista en tu formulario
        let nombre = $('[name="nombre"]').val();
        let apellidos = $('[name="apellidos"]').val();
        let cedula = $('[name="cedula"]').val();
        let telefono = $('[name="telefono"]').val();
        let email = $('[name="email"]').val();
        let cliente = $('[name="cliente"]').is(':checked') ? 1 : 0;
        let proveedor = $('[name="proveedor"]').is(':checked') ? 1 : 0;
        let credito = $('[name="credito"]').val();
        let nota = $('[name="nota"]').val();
        let fecha_nacimiento = $('[name="fecha_nacimiento"]').val();
        let rx = $('[name="rx"]').val();
        let addd = $('[name="add"]').val();
        let tipo = $('[name="tipo"]').val();
        let DP1 = $('[name="DP1"]').val();
        let DP2 = $('[name="DP2"]').val();
        let comercial = $('[name="comercial"]').val();
        let banco_cuenta = $('[name="banco_cuenta"]').val();
        let nombre_banco = $('[name="nombre_banco"]').val();
        let banco_entidad = $('[name="banco_entidad"]').val();
        let banco_oficina = $('[name="banco_oficina"]').val();
        let banco_digito_control = $('[name="banco_digito_control"]').val();
        let swift1 = $('[name="swift1"]').val();
        let swift2 = $('[name="swift2"]').val();
        let forma_pago = $('[name="fk_forma_pago"]').val();
        let activo = $('[name="activo"]').is(':checked') ? 1 : 0;
        let fk_categoria_cliente = $("[name='fk_categoria_cliente']").val();
        let fk_tipo_residencia = $("[name='tipo_residencia']").val();
        let fk_tipo_identificacion = $("[name='fk_tipo_identificacion']").val();
        let nombre_comercial_fisica = $('#nombre_comercial_fisica').val()

        /* Valida los inputs requeridos */
        const inputTypes = [];
        $('#pills-producto input[name][id]').each(function(index, element) {
            inputTypes.push({
                name: $(this).attr('id'),
                value: $(this).val(),
                required: ($(this).attr('required') || false)
            })
        });
        $('#pills-producto select[name][id]').each(function(index, element) {
            inputTypes.push({
                name: $(this).attr('id'),
                value: $(this).val(),
                required: ($(this).attr('required') || false)
            })
        });
        $('#pills-producto textarea[name][id]').each(function(index, element) {
            inputTypes.push({
                name: $(this).attr('id'),
                value: $(this).val(),
                required: ($(this).attr('required') || false)
            })
        });
        inputTypes.map(x => $('#' + x.name).removeClass('input_error'))
        inputTypes.map((x) => {
            if (x.required && (x.value == '' || x.value == null)) {
                $('#' + x.name).addClass('input_error');
                error = true;
            }
        })
        if(cliente == 0 && proveedor == 0){
            error = true;
            if(cliente == 0){
                $('[name="cliente"]').addClass('input_error');
            }
            if(proveedor == 0){
                $('[name="proveedor"]').addClass('input_error');
            }
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
        /* Valida los inputs requeridos */


        // Dirreccion del cliente  - @rojasarmando - 13-06-2024
        let datos_direccion = get_data_address_customer()
        // ------------



        // El resto del código se mantiene igual hasta la preparación de la petición AJAX
        // Preparar la petición AJAX
        $.ajax({
            method: "POST",
            url: "<?php echo ENLACE_WEB; ?>mod_terceros/class/terceros.class.php",
            data: {
                action: 'modificar_tercero', // Cambia 'nuevo_cliente' a 'modificar_tercero'
                rowid: rowid, // Asegúrate de que este campo se envíe correctamente
                nombre: nombre,
                apellidos: (tipo=='fisica'? apellidos: ''),
                cedula: cedula,
                telefono: telefono,
                email: email,
                cliente: cliente,
                proveedor: proveedor,
                credito: credito,
                nota: nota,
                fecha_nacimiento: fecha_nacimiento,
                rx: rx,
                add: addd,
                tipo: tipo,
                DP1: DP1,
                DP2: DP2,
                comercial: comercial,
                nombre_banco: nombre_banco,
                banco_entidad: banco_entidad,
                banco_oficina: banco_oficina,
                banco_digito_control: banco_digito_control,
                banco_cuenta: banco_cuenta,
                swift1: swift1,
                swift2: swift2,
                forma_pago: forma_pago,
                activo: activo

                    // Dirreccion del cliente  - @rojasarmando - 13-06-2024
                    ,
                // pais: datos_direccion['pais'],
                // direccion: datos_direccion['direccion'],
                // provincia: datos_direccion['provincia'],
                // codigo_postal: datos_direccion['codigo_postal']

                fk_pais: datos_direccion['fk_pais'],
                fk_poblacion: datos_direccion['poblacion'],
                direccion_fk_provincia: datos_direccion['direccion_fk_provincia'],
                direccion: datos_direccion['direccion'],
                codigo_postal: datos_direccion['codigo_postal'],
                // ------------

                fk_categoria_cliente: fk_categoria_cliente,
                fk_tipo_residencia: fk_tipo_residencia,
                fk_tipo_identificacion: fk_tipo_identificacion,
                electronica_nombre_comercial: (tipo=='juridica'? apellidos: nombre_comercial_fisica)
            }
        }).done(function(msg) {
            console.log(msg);
            const response = JSON.parse(msg);

            if (response.error == 1) {
                add_notification({
                    text: response.datos,
                    pos: 'top-right',
                    actionTextColor: '#fff',
                    backgroundColor: '#e7515a'
                });
            } else {

                <?php if ($Entidad->sincronizaciones[1]) : ?>

                    sincronizar(rowid, false, (rsp) => {

                        add_notification({
                            text: 'Tercero Editado exitosamente!',
                            actionTextColor: '#fff',
                            backgroundColor: '#00ab55',
                            dismissText: 'Cerrar'
                        })

                        add_notification({
                            text: rsp.message,
                            actionTextColor: '#fff',
                            backgroundColor: '#00ab55',
                            dismissText: 'Cerrar'
                        })

                        setTimeout(() => {
                            window.location.href = "<?php echo ENLACE_WEB . $ruta_breadcumb; ?>";
                        }, 3000);

                    });

                <?php else : ?>


                    add_notification({
                        text: 'Tercero modificado exitosamente!',
                        actionTextColor: '#fff',
                        backgroundColor: '#00ab55',
                        dismissText: 'Cerrar'
                    });

                    setTimeout(() => {
                        window.location.href = "<?php echo ENLACE_WEB . $ruta_breadcumb; ?>";
                    }, 3000);

                <?php endif; ?>



            }

        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.error("Error en la petición AJAX:", textStatus, errorThrown);

            add_notification({
                text: 'Hubo un error al modificar el tercero.',
                pos: 'top-right',
                actionTextColor: '#fff',
                backgroundColor: '#e7515a'
            });
        });
    }


    function eliminar_tercero(event) {
        event.preventDefault();

        let rowid = $('[name="fiche"]').val(); // Asegúrate de que este campo exista en tu formulario
        let message = "¿Deseas eliminar este tercero?";
        let actionText = "Confirmar";

        // Mostrar el snackbar y definir el callback para el botón de acción
        add_notification({
            text: message,
            width: 'auto',
            duration: 30000,
            actionText: actionText,
            dismissText: 'Cerrar',
            onActionClick: function(element) {

                $.ajax({
                    method: "POST",
                    url: "<?php echo ENLACE_WEB; ?>mod_terceros/class/terceros.class.php",
                    data: {
                        action: 'eliminar_tercero',
                        rowid: rowid
                    },
                    cache: false,
                }).done(function(msg) {
                    const response = JSON.parse(msg);

                    if (response.error == 1) {
                        add_notification({
                            text: response.datos,
                            pos: 'top-right',
                            actionTextColor: '#fff',
                            backgroundColor: '#e7515a'
                        });
                    } else {
                        add_notification({
                            text: 'Tercero eliminado exitosamente!',
                            actionTextColor: '#fff',
                            backgroundColor: '#00ab55',
                            dismissText: 'Cerrar'
                        });

                        setTimeout(() => {
                            window.location.href = "<?php echo ENLACE_WEB . $ruta_breadcumb; ?>";
                        }, 3000);
                    }

                    // Aquí puedes añadir código para actualizar la interfaz de usuario si es necesario
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    console.error("Error en la petición AJAX:", textStatus, errorThrown);

                    add_notification({
                        text: 'Hubo un error al marcar el tercero como eliminado.',
                        actionTextColor: '#fff',
                        backgroundColor: '#e7515a'
                    });
                });

            },
        });
    }

    function validar_correo(event) {
        let email = $('[name="email"]').val(); // Asegúrate de que este selector coincida con tu input de correo electrónico

        if (email === '') {
            // Mostrar notificación cuando el campo de correo electrónico esté vacío
            add_notification({
                text: 'El campo de correo electrónico está vacío.',
                pos: 'top-right',
                actionTextColor: '#fff',
                backgroundColor: '#e7515a'
            });
            return;
        }

        $.ajax({
            method: "POST",
            url: "<?php echo ENLACE_WEB; ?>mod_terceros/class/terceros.class.php",
            data: {
                action: 'validar_correo',
                email: email
            },
            dataType: 'json',
        }).done(function(response) {
            if (response.error == 1) {
                // El correo electrónico ya existe en la base de datos
                add_notification({
                    text: 'El correo electrónico ya existe.',
                    pos: 'top-right',
                    actionTextColor: '#fff',
                    backgroundColor: '#e7515a'
                });
                $("#correo_existe").val("yes").attr("value", "yes");
            } else {
                // El correo electrónico es único
                add_notification({
                    text: 'El correo electrónico es único.',
                    actionTextColor: '#fff',
                    backgroundColor: '#00ab55',
                    dismissText: 'Cerrar'
                });
                $("#correo_existe").val("").attr("value", "");
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            // Mostrar notificación en caso de error en la petición AJAX
            add_notification({
                text: 'Error en la petición AJAX: ' + textStatus,
                actionTextColor: '#fff',
                backgroundColor: '#e7515a'
            });
        });
    }



    function validar_dni(event) {

        

        let cedula = $('[name="cedula"]').val(); // Asegúrate de que este selector coincida con tu input de correo electrónico

        if (cedula === '') {
            // Mostrar notificación cuando el campo de correo electrónico esté vacío
            add_notification({
                text: 'El campo de cedula esta vacio.',
                pos: 'top-right',
                actionTextColor: '#fff',
                backgroundColor: '#e7515a'
            });
            return;
        }

        $.ajax({
            method: "POST",
            url: "<?php echo ENLACE_WEB; ?>mod_terceros/class/terceros.class.php",
            data: {
                action: 'validar_cedula',
                cedula: cedula
            },
            dataType: 'json',
            beforeSend: function() {
                    $("#creando_cliente").prop("disabled", true); // Deshabilita el botón antes del envío
            }
        }).done(function(response) {

            $("#creando_cliente").prop("disabled", false); // Deshabilita el botón antes del envío

            if (response.error == 1) {
                // El correo electrónico ya existe en la base de datos
                

                var texto_mensaje = $("#miSelect fk_tipo_identificacion:selected").text();

                // Verifica que el texto no sea null, vacío o solo espacios en blanco
                if (texto_mensaje && texto_mensaje.trim() !== "") {
                    texto = texto_mensaje;
                } else {
                    texto = "Identificacion Registrada Previamente";
                }


                add_notification({
                    text: texto                 ,
                    actionTextColor: '#fff'     ,
                    backgroundColor: '#e7515a'  ,
                });



                $("#cedula_existe").val("yes").attr("value", "yes");
            } else {
                // El correo electrónico es único
                add_notification({
                    text: 'La Identificacion  No se encuentra registrada previamente.',
                    actionTextColor: '#fff',
                    backgroundColor: '#00ab55',
                    dismissText: 'Cerrar'
                });
                $("#cedula_existe").val("").attr("value", "");
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            // Mostrar notificación en caso de error en la petición AJAX
            add_notification({
                text: 'Error en la petición AJAX: ' + textStatus,
                actionTextColor: '#fff',
                backgroundColor: '#e7515a'
            });

            $("#creando_cliente").prop("disabled", false); // Deshabilita el botón antes del envío


        });
    }
</script>



<!-- ------------------------------------- Mejora para Buscar CEdula y que Cargue lo demas ------------------- -->
<!-- ------------------------------------- Mejora para Buscar Cedula y que Cargue lo demas ------------------- -->
<!-- ------------------------------------- Mejora para Buscar Cedula y que Cargue lo demas ------------------- -->

<script>
</script>
<script>

    function restar(_linea) {
        console.log("Inicio del Proceso de Eliminar Fila");

        /////////
        var message = "Deseas eliminar esta exoneraci&oacute;n?";
        var actionText = "Confirmar";

        // Mostrar el snackbar y definir el callback para el botón de acción
        add_notification({
            text: message,
            width: 'auto',
            duration: 30000,
            actionText: actionText,
            dismissText: 'Cerrar',
            onActionClick: function(element) {

                $.ajax({
                    method: "POST",
                    url: "<?php echo ENLACE_WEB; ?>mod_terceros/class/terceros.class.php",
                    data: {
                        action: 'desactivar_exoneracion',
                        rowid: _linea
                    },
                    cache: false,
                    //contentType: false,
                    //processData: false,
                }).done(function(data) {
                    const response = JSON.parse(data);
                    console.log(response);
                    //const response = JSON.parse(msg);
                    console.log('data es: ' + response);
                    if (response.error == 0) {

                        add_notification({
                            text: 'Exoneraci&oacute;n eliminada exitosamente!',
                            actionTextColor: '#fff',
                            backgroundColor: '#00ab55',
                            dismissText: 'Cerrar'
                        })

                        $('#tipoDocumento').val('');
                        $('#numeroDocumento').val('');
                        $('#nombreInstitucion').val('');
                        $('#fechaEmsionDocumento').val('');

                        // Destruir la instancia actual del DataTable
                        $('#style-3').DataTable().destroy();
                        // Recargar los datos y reinicializar el DataTable
                        cargar_tabla_exoneraciones_cliente();


                    } else {


                        add_notification({
                            text: response.datos ? response.datos : response,
                            pos: 'top-right',
                            actionTextColor: '#fff',
                            backgroundColor: '#e7515a'
                        });



                    }

                    // Aquí puedes añadir código para actualizar la interfaz de usuario si es necesario
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    console.error("Error en la petición AJAX:", textStatus, errorThrown);

                    add_notification({
                        text: 'Hubo un error al eliminar el archivo.',
                        actionTextColor: '#fff',
                        backgroundColor: '#e7515a'
                    })

                });


            },

        });

        console.log("Fin del Proceso de Eliminar Fila");
    }

    function abrir_modal_modificar_contacto(rowid, Dato, Detalle, tipo) {
        $('#contacto_id').val(rowid)
        $('#contacto_dato').val(Dato)
        $('#contacto_detalle').val(Detalle)
        $('#contacto_tipo').val(tipo)
        $('#contactoModal').modal('show')
    }

    function agregar_dato_contacto() {
        console.log("Inicio del Proceso");
        var contacto_tipo = $("#contacto_tipo").val();
        var contacto_data = $("#contacto_data").val();
        var contacto_detalle = $("#contacto_detalle").val();

        $("#contacto_tipo").val('');
        $("#contacto_data").val('');
        $("#contacto_detalle").val('');

        if (contacto_tipo == '' || contacto_data == '' || contacto_detalle == '') {
            add_notification({
                text: 'Debe elegir completar los datos de contacto',
                pos: 'top-right',
                actionTextColor: '#fff',
                backgroundColor: '#e7515a'
            });
            return false;
        }

        $.post("<?php echo ENLACE_WEB; ?>mod_terceros/class/terceros.class.php", {
                contacto_tipo: contacto_tipo,
                contacto_data: contacto_data,
                contacto_detalle: contacto_detalle,
                rowid: '<?php echo $_REQUEST['fiche']; ?>',
                action: 'agregar_dato_contacto'
            })
            .done(function(data) {

                let response = JSON.parse(data);
                if (response.error == 0) {
                    console.log('entro en error');

                    add_notification({
                        text: 'Dato de contacto creado exitosamente!',
                        actionTextColor: '#fff',
                        backgroundColor: '#00ab55',
                        dismissText: 'Cerrar'
                    })
                    // Destruir la instancia actual del DataTable
                    $('#tabla-contactos').DataTable().destroy();
                    // Recargar los datos y reinicializar el DataTable
                    cargar_tabla_dato_contacto();

                } else {

                    add_notification({
                        text: response.datos,
                        pos: 'top-right',
                        actionTextColor: '#fff',
                        backgroundColor: '#e7515a'
                    });

                }

            });

        console.log("Fin del Proceso");

    }


    function eliminar_dato_contacto(_linea) {
        console.log("Inicio del Proceso de Eliminar Fila");

        /////////
        var message = "Deseas eliminar este dato de contacto?";
        var actionText = "Confirmar";

        // Mostrar el snackbar y definir el callback para el botón de acción
        add_notification({
            text: message,
            width: 'auto',
            duration: 30000,
            actionText: actionText,
            dismissText: 'Cerrar',
            onActionClick: function(element) {

                $.ajax({
                    method: "POST",
                    url: "<?php echo ENLACE_WEB; ?>mod_terceros/class/terceros.class.php",
                    data: {
                        action: 'eliminar_dato_contacto',
                        rowid: _linea
                    },

                }).done(function(data) {
                    const response = JSON.parse(data);

                    if (response.error == 0) {

                        add_notification({
                            text: 'Dato de contacto eliminado exitosamente!',
                            actionTextColor: '#fff',
                            backgroundColor: '#00ab55',
                            dismissText: 'Cerrar'
                        })

                        // Destruir la instancia actual del DataTable
                        $('#tabla-contactos').DataTable().destroy();
                        // Recargar los datos y reinicializar el DataTable
                        cargar_tabla_dato_contacto();

                    } else {

                        add_notification({
                            text: response.datos,
                            pos: 'top-right',
                            actionTextColor: '#fff',
                            backgroundColor: '#e7515a'
                        });

                    }

                    // Aquí puedes añadir código para actualizar la interfaz de usuario si es necesario
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    console.error("Error en la petición AJAX:", textStatus, errorThrown);

                    add_notification({
                        text: 'Hubo un error al eliminar el archivo.',
                        actionTextColor: '#fff',
                        backgroundColor: '#e7515a'
                    })

                });


            },

        });

        console.log("Fin del Proceso de Eliminar Fila");
    }


    function obtenerValoresSeleccionados(nombreCampo) {
        // Obtenemos los valores seleccionados en forma de array
        var valoresSeleccionados = $('select[name="' + nombreCampo + '[]"]').val();

        // Si hay valores seleccionados, los unimos en una cadena separada por comas
        if (valoresSeleccionados !== null) {
            return valoresSeleccionados.join(',');
        }

        // Si no hay valores seleccionados, devolvemos una cadena vacía
        return '';
    }

    //Actualizar condiciones comerciales
    function actualizar_condiciones_comerciales(event) {
        event.preventDefault();

        let error = false;
        /* Valida los inputs requeridos */
        const inputTypes = [];
        $('#pills-condiciones-comerciales input[name][id]').each(function(index, element) {
            inputTypes.push({
                name: $(this).attr('id'),
                value: $(this).prop('type') == 'checkbox' ? $(this).prop('checked') : $(this).val(),
                required: ($(this).attr('required') || false)
            })
        });
        $('#pills-condiciones-comerciales select[name][id]').each(function(index, element) {
            inputTypes.push({
                name: $(this).attr('id'),
                value: $(this).val(),
                required: ($(this).attr('required') || false)
            })
        });
        $('#pills-condiciones-comerciales textarea[name][id]').each(function(index, element) {
            inputTypes.push({
                name: $(this).attr('id'),
                value: $(this).val(),
                required: ($(this).attr('required') || false)
            })
        });

        inputTypes.map(x => { 
            $('#' + x.name).removeClass('input_error') 
            if (x.name == 'dia_pago' ) {
                $($('input[id="dia_pago-ts-control"]').parent().parent()).removeClass('input_error') 
            }
            if (x.name == 'mes_no_pago' ) {
                $($('input[id="mes_no_pago-ts-control"]').parent().parent()).removeClass('input_error') 
            }
        })
        inputTypes.map((x) => {
            if (x.required && x.value == '') {
                $('#' + x.name).addClass('input_error');
                error = true;
            }
            if (x.required && x.name == 'dia_pago' && x.value.length == 0 ) {
                $($('input[id="dia_pago-ts-control"]').parent().parent()).addClass('input_error')
                error = true;
            }
            if (x.required && x.name == 'mes_no_pago'  && x.value.length == 0) {
                $($('input[id="mes_no_pago-ts-control"]').parent().parent()).addClass('input_error')
                error = true;
            }
        })
        // Si hay errores, mostrar notificación y detener el envío del formulario
        if (error) {
            add_notification({
                text: 'Faltan Datos Obligatorios',
                actionTextColor: '#fff',
                backgroundColor: '#e7515a',
            });
            return true;
        }

        const data = inputTypes.reduce((acc, item) => {
            acc[item.name] = item.value;
            if(item.name == 'dia_pago' || item.name == 'mes_no_pago'){
                acc[item.name] = item.value.join(',');
            }
            return acc;
        }, {
            action: 'condiciones_comerciales',
            rowid : <?php echo isset($_REQUEST['fiche']) ? $_GET['fiche'] : 0; ?>
        });


        $.post("<?php echo ENLACE_WEB; ?>mod_terceros/class/terceros.class.php", data)
        .done(function(data) {
            let response = JSON.parse(data);
                add_notification({
                    text: response.mensaje   ,
                    actionTextColor: '#fff',
                    backgroundColor: '#00ab55',
                    dismissText: 'Cerrar'
                });
        });

    }




    //Actualizar agente
    function actualizar_agente(event) {
        event.preventDefault();

        let fk_tercero = $('[name="fiche"]').val();
        let fk_agente = $('[name="fk_agente"]').find("option:selected").val();
        let agente_nombre = $('[name="agente"]').find("option:selected").text(); // Asegúrate de que este campo exista en tu formulario
        let message = `¿Deseas seleccionar a ${agente_nombre}  como su agente predeterminado?`;
        let actionText = "Confirmar";

        console.log(agente_nombre)
        // return false;
        // Mostrar el snackbar y definir el callback para el botón de acción
        add_notification({
            text: message,
            width: 'auto',
            duration: 30000,
            actionText: actionText,
            dismissText: 'Cerrar',
            onActionClick: function(element) {

                $.ajax({
                    method: "POST",
                    url: "<?php echo ENLACE_WEB; ?>mod_configuracion_agente/class/agentes.class.php",
                    data: {
                        action: 'actualizar_agente',
                        fk_tercero: fk_tercero,
                        fk_agente: fk_agente
                    },
                    cache: false,
                }).done(function(msg) {
                    const response = JSON.parse(msg);

                    if (response.exito === true) {

                        add_notification({
                            text: response.mensaje,
                            actionTextColor: '#fff',
                            backgroundColor: '#00ab55',
                            dismissText: 'Cerrar'
                        });



                    } else {
                        add_notification({
                            text: response.mensaje,
                            pos: 'top-right',
                            actionTextColor: '#fff',
                            backgroundColor: '#e7515a'
                        });
                    }

                    // Aquí puedes añadir código para actualizar la interfaz de usuario si es necesario
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    console.error("Error en la petición AJAX:", textStatus, errorThrown);

                    add_notification({
                        text: 'Hubo un error al marcar el agente como eliminado.',
                        actionTextColor: '#fff',
                        backgroundColor: '#e7515a'
                    });
                });

            },
        });
    }


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

    // Guardar la visibilidad de las columnas en localStorage
    function saveColumnVisibility(table) {
        var columnVisibility = [];
        table.columns().every(function(index) {
            columnVisibility.push(this.visible());
        });
        localStorage.setItem('columnVisibility', JSON.stringify(columnVisibility));
    }

    // Cargar la visibilidad de las columnas desde localStorage
    function loadColumnVisibility(table) {
        var columnVisibility = JSON.parse(localStorage.getItem('columnVisibility'));
        if (columnVisibility) {
            table.columns().every(function(index) {
                this.visible(columnVisibility[index]);
            });
        }
    }


    function resetLocation() {
        var defaultLat = 40.4165;
        var defaultLng = -3.70256;
        window.mymap.setView([defaultLat, defaultLng], 10);
        window.marker.setLatLng([defaultLat, defaultLng]);
        $("#latitude").val(defaultLat);
        $("#longitud").val(defaultLng);
    }

    var defaultLat = 40.4165;
    var defaultLng = -3.70256;
    $(document).ready(function() {
        // var defaultLat = 40.4165;
        // var defaultLng = -3.70256;
        var mymap = L.map('live-location').setView([$("#latitude").val(), $("#longitud").val()], 10); // San José, Costa Rica
        window.mymap = mymap;
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(mymap);

        var marker = L.marker([$("#latitude").val(), $("#longitud").val()], {
            draggable: 'true'
        }).addTo(mymap);

        marker.on('dragend', function(event) {
            var position = marker.getLatLng();
            console.log(position);
            //actualizmos la ubicacion en el form
            $("#latitude").val(position.lat);
            $("#longitud").val(position.lng);

        });

        window.marker = marker;

        // Escuchamos los eventos de cambio en los campos de latitud y longitud
        $("#latitude, #longitud").on('change', function() {
            if ($("#latitude").val() === '' || $("#longitud").val() === '') {
                resetLocation();
            }
        });

        L.Control.geocoder({
                defaultMarkGeocode: false,
                geocoder: L.Control.Geocoder.nominatim()
            })
            .on('markgeocode', function(e) {
                mymap.fitBounds(e.geocode.bbox);
                if (marker) {
                    mymap.removeLayer(marker);
                }
                marker = L.marker(e.geocode.center, {
                    draggable: 'true'
                }).addTo(window.mymap);
                marker.on('dragend', function(event) {
                    var position = marker.getLatLng();
                    window.position = position;
                    console.log(position);
                    //actualizamos la ubicacion en el form
                    $("#latitude").val(position.lat);
                    $("#longitud").val(position.lng);

                });
                console.log(e.geocode.center);
                $("#latitude").val(e.geocode.center.lat);
                $("#longitud").val(e.geocode.center.lng);
            })
            .addTo(mymap);

    })

    $('#addContactModal').on('shown.bs.modal', function() {
        window.mymap.invalidateSize();
    });

    function fetch_contacto(id) {
        // POST
        $.ajax({
            method: "POST",
            url: "<?php echo ENLACE_WEB; ?>mod_contactos_crm/class/contacto_crm.class.php",
            beforeSend: function(xhr) {},
            data: {
                action: 'fetch_contacto',
                rowid: id
            },
        }).done(function(data) {
            let response = JSON.parse(data);
            let content = '';
            console.log(response.latitude);
            $('[name=rowid_contacto]').val(response.rowid)
            $('[name=fk_tercero_contacto]').val(response.fk_tercero)
            $('[name=nombre_contacto]').val(response.nombre)
            $('[name=apellidos_contacto]').val(response.apellidos)
            $('[name=pais_c]').val(response.pais_c)
            $('[name=puesto_t]').val(response.puesto_t)
            $('[name=email_contacto]').val(response.email)
            $('[name=telefono_contacto]').val(response.telefono)
            $('[name=facebook_contacto]').val(response.facebook)
            $('[name=linkedin_contacto]').val(response.linkedin)
            $('[name=fecha_nacimiento_contacto]').val(response.fecha_nacimiento)
            $('[name=extension_contacto]').val(response.extension)
            $('[name=whatsapp_contacto]').val(response.whatsapp)
            $('[name=instagram_contacto]').val(response.instagram)
            $('[name=x_twitter_contacto]').val(response.x_twitter)
            $('[name=latitude]').val(response.latitude !== null ? response.latitude : defaultLat)
            $('[name=longitud]').val(response.longitud !== null ? response.longitud : defaultLng)

            //Cargar la posición del marcador en el mapa
            var lat = response.latitude !== '' ? response.latitude : defaultLat;
            var lng = response.longitud !== '' ? response.longitud : defaultLng;
            if (lat && lng) {
                mymap.setView([lat, lng], 10); // Ajusta la vista del mapa a la nueva ubicación
                if (marker) {
                    mymap.removeLayer(marker); // Elimina el marcador anterior si existe
                }
                marker = L.marker([lat, lng], {
                    draggable: 'true'
                }).addTo(mymap);

                marker.on('dragend', function(event) {
                    var position = marker.getLatLng();
                    window.position = position;
                    console.log(position);
                    //actualizamos la ubicacion en el form
                    $("[name=latitude]").val(position.lat);
                    $("[name=longitud]").val(position.lng);

                }); // Agrega un nuevo marcador en la nueva ubicación
            }

            $('#modal_titulo').text('Modificar');
            $('#boton_crear_txt').text('Modificar');

            // $('#boton_eliminar').show();

            $('#addContactModal').modal('show');
        });
    }



    function mostrar_modal() {

        $('[name=fk_tercero_contacto]').val($('[name=fiche]').val());
        $('[name=nombre_contacto]').val('');
        $('[name=apellidos_contacto]').val('');
        $('[name=pais_c]').val('');
        $('[name=puesto_t]').val('');
        $('[name=email_contacto]').val('');
        $('[name=telefono_contacto]').val('');
        $('[name=facebook_contacto]').val('');
        $('[name=linkedin_contacto]').val('');
        $('[name=fecha_nacimiento_contacto]').val('');
        $('[name=extension_contacto]').val('');
        $('[name=whatsapp_contacto]').val('');
        $('[name=instagram_contacto]').val('');
        $('[name=x_twitter_contacto]').val('');

        $('[name=latitude]').val('');
        $('[name=longitud]').val('');

        // $('#boton_eliminar').hide();
        $('#modal_titulo').text('Crear');
        $('#boton_crear_txt').text('Crear');

        resetLocation();

        $('#addContactModal').modal('show');
    }

    function validar_accion() {
        let accion = $('#boton_crear_txt').text();
        if (accion == 'Crear') {
            crear_contacto(event);
        } else {
            modificar_contacto(event);
        }
    }


    function crear_contacto(event) {
        event.preventDefault();

        error = false;

        // Recoger los valores del formulario usando jQuery
        let nombre = $('[name="nombre_contacto"]').val();
        let apellidos = $('[name="apellidos_contacto"]').val();
        let pais_c = $('[name="pais_c"]').val();
        let puesto_t = $('[name="puesto_t"]').val();
        let email = $('[name="email_contacto"]').val();
        let telefono = $('[name="telefono_contacto"]').val();
        let facebook = $('[name="facebook_contacto"]').val();
        let linkedin = $('[name="linkedin_contacto"]').val();
        let fecha_nacimiento = $('[name="fecha_nacimiento_contacto"]').val();
        let extension = $('[name="extension_contacto"]').val();
        let whatsapp = $('[name="whatsapp_contacto"]').val();
        let instagram = $('[name="instagram_contacto"]').val();
        let x_twitter = $('[name="x_twitter_contacto"]').val();
        let fk_tercero = $('[name="fk_tercero_contacto"]').val();

        let latitude = $('[name="latitude"]').val();
        let longitud = $('[name="longitud"]').val();



        if (nombre == '') {
            $('input[name="nombre_contacto"]').addClass("input_error");
            error = true;
        }
        if (apellidos == '') {
            $('input[name="apellidos_contacto"]').addClass("input_error");
            error = true;
        }

        if (error) {
            add_notification({
                text: 'Faltan Datos Obligatorios',
                actionTextColor: '#fff',
                backgroundColor: '#e7515a',

            })
            return true;
        }

        // Preparar la petición AJAX
        $.ajax({
            type: 'POST',
            url: '<?php echo ENLACE_WEB; ?>mod_contactos_crm/class/contacto_crm.class.php',
            data: {
                action: 'nuevo_contacto',
                nombre: nombre,
                apellidos: apellidos,
                pais_c: pais_c,
                puesto_t: puesto_t,
                email: email,
                telefono: telefono,
                facebook: facebook,
                linkedin: linkedin,
                fecha_nacimiento: fecha_nacimiento,
                extension: extension,
                whatsapp: whatsapp,
                instagram: instagram,
                x_twitter: x_twitter,
                fk_tercero: fk_tercero,
                latitude: latitude,
                longitud: longitud
            },
            success: function(data) {
                  
                $('#contactos_crm_table').DataTable().ajax.reload();


                let response = JSON.parse(data);
                console.log('Success:', response);
                if (response.exito === true) {
                    add_notification({
                        text: response.mensaje,
                        actionTextColor: '#fff',
                        backgroundColor: '#00ab55',
                        actionText: 'Cerrar'
                    });

 
                    $('#addContactModal').modal('hide');
                } else {
                    add_notification({
                        text: response.mensaje,
                        pos: 'top-right',
                        actionTextColor: '#fff',
                        backgroundColor: '#e7515a',
                        actionText: 'Cerrar'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });


    }

    function modificar_contacto(event) {
        event.preventDefault();

        error = false;

        // Recoger los valores del formulario usando jQuery
        let rowid = $('[name="rowid_contacto"]').val();
        let nombre = $('[name="nombre_contacto"]').val();
        let apellidos = $('[name="apellidos_contacto"]').val();
        let pais_c = $('[name="pais_c"]').val();
        let puesto_t = $('[name="puesto_t"]').val();
        let email = $('[name="email_contacto"]').val();
        let telefono = $('[name="telefono_contacto"]').val();
        let facebook = $('[name="facebook_contacto"]').val();
        let linkedin = $('[name="linkedin_contacto"]').val();
        let fecha_nacimiento = $('[name="fecha_nacimiento_contacto"]').val();
        let extension = $('[name="extension_contacto"]').val();
        let whatsapp = $('[name="whatsapp_contacto"]').val();
        let instagram = $('[name="instagram_contacto"]').val();
        let x_twitter = $('[name="x_twitter_contacto"]').val();
        let fk_tercero = $('[name="fk_tercero_contacto"]').val();
        let latitude = $('[name="latitude"]').val();
        let longitud = $('[name="longitud"]').val();

        if (nombre == '') {
            $('input[name="nombre_contacto"]').addClass("input_error");
            error = true;
        }
        if (apellidos == '') {
            $('input[name="apellidos_contacto"]').addClass("input_error");
            error = true;
        }


        if (error) {
            add_notification({
                text: 'Faltan Datos Obligatorios',
                actionTextColor: '#fff',
                backgroundColor: '#e7515a',

            })
            return true;
        }

        // Preparar la petición AJAX
        $.ajax({
            method: "POST",
            url: '<?php echo ENLACE_WEB; ?>mod_contactos_crm/class/contacto_crm.class.php',
            data: {
                action: 'modificar_contacto',
                rowid: rowid,
                nombre: nombre,
                apellidos: apellidos,
                pais_c: pais_c,
                puesto_t: puesto_t,
                email: email,
                telefono: telefono,
                facebook: facebook,
                linkedin: linkedin,
                fecha_nacimiento: fecha_nacimiento,
                extension: extension,
                whatsapp: whatsapp,
                instagram: instagram,
                x_twitter: x_twitter,
                fk_tercero: fk_tercero,
                latitude: latitude,
                longitud: longitud
            }
        }).done(function(msg) {
            const response = JSON.parse(msg);
            console.log(response)
            if (response.exito === true) {
                add_notification({
                    text: response.mensaje,
                    actionTextColor: '#fff',
                    backgroundColor: '#00ab55',
                    dismissText: 'Cerrar'
                });

                $('#contactos_crm_table').DataTable().ajax.reload();
                
                $('#addContactModal').modal('hide');

            } else {
                add_notification({
                    text: response.mensaje,
                    pos: 'top-right',
                    actionTextColor: '#fff',
                    backgroundColor: '#e7515a'
                });
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.error("Error en la petición AJAX:", textStatus, errorThrown);
            add_notification({
                text: 'Hubo un error al modificar el contacto.',
                actionTextColor: '#fff',
                backgroundColor: '#e7515a'
            });
        });



    }

    function eliminar_contacto(id) {
        event.preventDefault();

        // Asegúrate de que este campo exista en tu formulario o en el elemento que desencadena la eliminación

        let message = "¿Deseas eliminar este contacto?";
        let actionText = "Confirmar";

        // Mostrar el snackbar y definir el callback para el botón de acción
        add_notification({
            text: message,
            width: 'auto',
            duration: 30000,
            actionText: actionText,
            dismissText: 'Cerrar',
            onActionClick: function(element) {

                $.ajax({
                    method: "POST",
                    url: "<?php echo ENLACE_WEB; ?>mod_contactos_crm/class/contacto_crm.class.php",
                    data: {
                        action: 'eliminar_contacto',
                        rowid: id
                    },
                    cache: false,
                }).done(function(msg) {
                    const response = JSON.parse(msg);

                    if (response.exito === true) {
                        add_notification({
                            text: response.mensaje,
                            actionTextColor: '#fff',
                            backgroundColor: '#00ab55',
                            dismissText: 'Cerrar'
                        });

                        $('#contactos_crm_table').DataTable().ajax.reload();

                    } else {
                        add_notification({
                            text: response.mensaje,
                            pos: 'top-right',
                            actionTextColor: '#fff',
                            backgroundColor: '#e7515a'
                        });
                    }

                    // Aquí puedes añadir código para actualizar la interfaz de usuario si es necesario
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    console.error("Error en la petición AJAX:", textStatus, errorThrown);

                    add_notification({
                        text: 'Hubo un error al marcar el contacto como eliminado.',
                        actionTextColor: '#fff',
                        backgroundColor: '#e7515a'
                    });
                });

            },
        });
    }

    $('#addContactModal').on('hidden.bs.modal', function() {
        $('input[name="nombre_contacto"]').removeClass("input_error");
        $('input[name="apellidos_contacto"]').removeClass("input_error");
    })

    function alerta_crear(event) {
        event.preventDefault();
        alert('Debe registrar el cliente antes de agregar datos relacionados.')
    }


    function get_create_user(customer_id) {

        $.ajax({
            method: "GET",
            url: `<?= ENLACE_WEB ?>mod_terceros/ajax/obtener_usuario_creador.ajax.php`,
            data: {
                customer_id
            },
            cache: false,
        }).done(function(response) {

            if (!response.data) {

                add_notification({
                    text: 'Hubo un error al consultar el usuario.',
                    actionTextColor: '#fff',
                    backgroundColor: '#e7515a'
                });
                console.error(response)

                return;
            }
            let customer = response.data

            document.querySelector("#user_create_log").innerHTML = customer.name
            document.querySelector("#date_create_log").innerHTML = customer.date


        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.error("Error en la petición:", textStatus, errorThrown);
            add_notification({
                text: 'Hubo un error al consultar el usuario.',
                actionTextColor: '#fff',
                backgroundColor: '#e7515a'
            });
        });


    }

    var customer_id = '<?= $_REQUEST['fiche'] ?>';

    <?php if ($ENTIDAD_RED_HOUSE  == $ENTIDAD_USER): ?>
        if (customer_id) {
            get_create_user(customer_id)
        }
    <?php endif; ?>


    $("#logs-card").innerHTML = ""
</script>