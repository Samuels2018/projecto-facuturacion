<script>
    $(document).ready(function() {

        // $('#zero-config').DataTable({
        //     "dom": estiloPaginado.dom,
        //     "oLanguage": estiloPaginado.oLanguage,
        //         "stripeClasses": [],
        //         "lengthMenu": [5, 10, 15, 20],
        //         "pageLength": 20
        //     });


        cargar_tabla_productos();

        $('.filtro').on('click', function(event) {
            event.stopPropagation(); // Evita que el clic se propague y active el ordenamiento
        });


    })

    var_pagina_actual = 0;

    function paginaInventario(x) {

        var_pagina_actual = x;


        data = $("#formulario").serialize();
        $("#tbody").empty();

        $.post("<?php echo ENLACE_WEB; ?>mod_productos/ajax/listado.servicios.ajax.php?" + data, {
                pagina: x
            })
            .done(function(data) {
                console.log(data);
                $("#tbody").html(data);
            });
    }

    function orderCabysValue() {
        var order = $("#order").val();
        console.log(order);
        // VALID ORDER CABYS
        if ((order) != '') {
            // VALID ORDER CABYS
            if ((order) == 'desc')
                $("#order").val('asc');
            else
                $("#order").val('desc');
        } else
            $("#order").val('desc');
        // SEARCH PAGE PRODUCT
        paginaInventario(0);
    }

    //----------------------------------------------------------------------------
    //
    //   Funcion Inicial Quitar Menu
    //
    //
    // $(document).ready(function() {
    //     i = 0;
    //     console.log("ready! + lets hide the menu my friend");
    //     change_layout("sidebar-collapse");
    // });




    //----------------------------------------------------------------------------
    //
    //    Variables iniciales
    //
    //----------------------------------------------------------------------------
    selector_buscador = '<select  Onchange="SetEjemplo(this.value)" class="form-control" id="selector_cabys"  style="cursor:pointer;"><option value="texto"> Busqueda por texto  </option><option value="codigo"> Codigo Exacto </option></select>';
    buscador_ = "<br><br><br><p class = 'text-left' > <table width='80%'><tr><td width='25%'><i class='fa fa-fw fa-search'></i> Buscador</td><td width='25%'><input id='input_cabys_text'  class='form-control' type='text' placeholder='Buscador Codigo ' ></td><td width='25%'>" + selector_buscador + "</td><td width='25%' id='td_ejemplo_cabys' ></td></tr></table> </p> ";
    buscador_boton = '<div OnClick="BuscarCabysDesdePantallaNueva()" style="margin-right:10px" class="btn btn-warning btn-fill pull-right">Buscador Cabys en Hacienda</div>';
    cerrar_boton = '<div  OnClick=\'$("#alerta_hacienda").hide()\' style="margin-right:10px"  class="sweet-confirm btn btn-info btn-fill pull-right">Cerrar</div>';
    imagen_cargando = '<br><br><br><br><img src="https://facturacionpymes.tk/sistema/images/salvando.gif" style="width:35px" >';
    ProductoID = 0;
    ProductoImpuesto = 0;
    CABYS_descripcion = "";
    CABYS_impuesto = "";





    //----------------------------------------------------------------------------
    //
    //   Funciones para el CABYS
    //
    //

    function MostrarCabys(producto) {
        //  alert(producto);

        $.ajax({
            url: '<?php echo ENLACE_WEB; ?>mod_CABYS/ajax//mostrar.cabys.php?',
            type: 'post',
            // dataType:  'json',
            data: {
                "ProductoID": producto
            },
            // contentType: 'application/json',
            success: function(datos) {

                $('#alerta_hacienda_txt').empty().hide().html(datos).show();
                $('#cabys_cantidad_resultados').empty().hide().html(cerrar_boton).show();






            }
        });


    }


    //--------------------------------------------------------------------------
    //
    //
    //
    //
    //--------------------------------------------------------------------------
    function definirCABYS(id, codigo, nombre, impuesto) {
        ProductoID = id; // definimos el ID del servicio o producto
        ProductoImpuesto = impuesto;
        // alert("lo marke como " +ProductoImpuesto);

        $("#alerta_hacienda").show();
        $('#alerta_hacienda_txt').html(imagen_cargando);


        $("#alerta_hacienda_h2").empty().hide().html("CABYS " + nombre).show();

        if (codigo == "") {
            buscarCABYSXNOMBRE(nombre);
        } else {
            MostrarCabys(id);
        }

    }



    //--------------------------------------------------------------------------
    //
    //
    //
    //
    //--------------------------------------------------------------------------

    function EnviarEmailCabysFinal() {


        email = $("#cabys_email_correo").val();
        cuerpo = $("#compose-textarea").val();

        $('#alerta_hacienda_txt').html(imagen_cargando);
        $('#cabys_cantidad_resultados').empty();


        /*    */

        // para el paginado !
        data = $("#formulario").serialize();


        $.ajax({
            url: '<?php echo ENLACE_WEB; ?>mod_CABYS/ajax/enviar.correo.ajax.php?' + data,
            type: 'post',
            dataType: 'json',
            data: {
                "ProductoID": ProductoID,
                "email": email,
                "cuerpo": cuerpo
            },
            // contentType: 'application/json',
            success: function(datos) {
                console.log("data " + datos);


                if (datos.error == "" && datos.api != 400) {

                    $('#alerta_hacienda_txt').empty().hide().html("Correo Enviado Con Exito.").show();


                    if (datos.siguiente_rowid > 0) {
                        boton_de_fondo = '<div OnClick="definirCABYS(' + datos.siguiente_rowid + ' , 0 , \'' + datos.label + '\' ,  \'' + datos.impuesto + '\'  )" style="margin-right:10px" class="btn btn-warning btn-fill pull-right">Continuar con ' + datos.label + '</div>';
                        $('#cabys_cantidad_resultados').empty().hide().html(cerrar_boton + boton_de_fondo).show();
                    }



                } else {
                    $('#alerta_hacienda_txt').empty().hide().html("<h1>Correo No pudo enviarse.</h1>Ocurrio Un Error.").show();
                    $('#cabys_cantidad_resultados').empty().hide().html(cerrar_boton).show();


                }



            }
        });


        /*     */


    }


    //--------------------------------------------------------------------------
    //
    //
    //
    //
    //--------------------------------------------------------------------------

    function ConsultarHaciendaEmail() {
        $('#alerta_hacienda_txt').html(imagen_cargando);




        $.post("<?php echo ENLACE_WEB; ?>mod_CABYS/ajax/preparar.correo.ajax.php?", {
                ProductoID: ProductoID
            })
            .done(function(data) {

                $('#alerta_hacienda_txt').empty().hide().html(data).show();
                $('#cabys_cantidad_resultados').empty().html(cerrar_boton + '<div OnClick="EnviarEmailCabysFinal()" style="margin-right:10px" class="btn btn-warning btn-fill pull-right"> Enviar Email  </div>');
                $("#compose-textarea").wysihtml5();



            });


    }


    //--------------------------------------------------------------------------
    //
    //   Function para pantalla Nueva de Buscar (al hacer click)
    //   input_cabys_text
    //   Revisa que no existan errores en la busqueda manual
    //   No la automatica
    //
    //--------------------------------------------------------------------------
    function BuscarCabysDesdePantallaNueva() {

        $("#input_cabys_text").removeClass("input_error");

        texto_error = "";
        codigo = $("#selector_cabys").val();
        error_cabys_analisis = false;


        if (codigo === "codigo") {
            texto_codigo = "codigo Cabys Exacto";
        } else {
            texto_codigo = "producto a buscar ";
        }






        texto_a_buscar = $("#input_cabys_text").val();

        if (texto_a_buscar === "") {
            texto_error = "Digite el " + texto_codigo;
            error_cabys_analisis = true;

        }





        if (error_cabys_analisis) {

            $("#input_cabys_text").addClass("input_error");
            $("#input_cabys_text").notify(texto_error);
            return false; /* La pantalla tiene errores y debe detenerse la ejecucion  */

        }

        $('#alerta_hacienda_txt').html(imagen_cargando);



        if (codigo === "codigo") {
            definirCABIS(texto_a_buscar);
        } else {
            buscarCABYSXNOMBRE(texto_a_buscar);
        }



    }






    //--------------------------------------------------------------------------
    //
    //   Function para pantalla buscar de nuevo
    //
    //--------------------------------------------------------------------------
    function buscar_de_nuevo() {



        $("#alerta_hacienda_txt").empty().hide().html(buscador_).show();
        $("#cabys_cantidad_resultados").empty().hide().html(cerrar_boton + buscador_boton).show();



    }


    //--------------------------------------------------------------------------
    //
    //   Funcion para Buscar por Nombre
    //
    //--------------------------------------------------------------------------

    function buscarCABYSXNOMBRE(busquedaCABISVALOR) {

        cantidad = 100;
        texto = "";


        $("#cabys_cantidad_resultados").empty();



        $.getJSON("https://api.hacienda.go.cr/fe/cabys?q=" + busquedaCABISVALOR + "&top=" + cantidad)
            .done(function(json) {
                console.log("JSON Data: " + json.total);

                //mensajito=    ""+ json.total   + " Resultados similares encontrados ";
                mensajito = '<div OnClick="buscar_de_nuevo()" style="margin-right:10px" class="btn btn-warning btn-fill pull-right"> <i class="fa fa-fw fa-search"></i> Realizar nueva Consulta CABYS </div>';
                mensajito += '<div OnClick="ConsultarHaciendaEmail()" style="margin-right:10px" class="btn btn-info btn-fill pull-right">¿No Encuentras el Codigo? Consultar Hacienda <i class="fa fa-fw fa-envelope"></i> </div>';

                $("#cabys_cantidad_resultados").empty().hide().html(cerrar_boton + mensajito).show();

                if (json.total == 0) {
                    $.notify("No se encontraron Resultados", "info");
                    buscador_adendum = "<br><br>No se encontraron resultados para elcodigo <strong>" + busquedaCABISVALOR + "</strong>";

                    $("#alerta_hacienda_txt").empty().hide().html(buscador_ + buscador_adendum).show();
                    $("#cabys_cantidad_resultados").empty().hide().html(buscador_boton + cerrar_boton).show();
                    $("#input_cabys_text").focus();



                    return false;
                }

                $.each(json.cabys, function(i, item) {


                    console.log(item.codigo);
                    console.log(item.descripcion);
                    console.log(item.categorias);



                    texto += "<tr class='encima'  style='cursor:pointer;' OnClick = 'definirCABIS(\"" + item.codigo + "\")'   >";
                    texto += "<td ><strong >" + item.codigo + "</strong></td>";
                    texto += "<td>" + item.descripcion + "</td>";


                    texto_interno = "";
                    $.each(item.categorias, function(ii, categoria) {
                        texto_interno += "<td align='center' >" + categoria + "</td>";
                    });
                    texto += texto_interno;
                    texto += "</tr>";


                });

                cabecera = "<br>";
                cabecera += "<table id='myTable2' style='background-color:white;font-size:10px;-webkit-app-region: no-drag;' width='100%' border='1'  >";
                cabecera += "<thead>";
                cabecera += "<th>Codigo</th>";
                cabecera += "<th>Nivel 1</th>";
                cabecera += "<th>Nivel 2</th>";
                cabecera += "<th>Nivel 3</th>";
                cabecera += "<th>Nivel 4</th>";
                cabecera += "<th>Nivel 5</th>";
                cabecera += "<th>Nivel 6</th>";
                cabecera += "<th>Nivel 7</th>";
                cabecera += "<th>Nivel 8</th>";
                cabecera += "<th>Nivel 9</th>";
                cabecera += "</thead>";
                cabecera += "<tbody>";

                texto = cabecera + texto + "</tbody></table>";




                $.when($("#alerta_hacienda_txt").empty().hide().html(texto).show()).then(function(data, textStatus, jqXHR) {

                    $('#myTable2').DataTable({
                        dom: 'Bfrtip',
                        buttons: [
                            'copy', 'csv', 'excel',
                            {
                                extend: 'pdfHtml5',
                                title: 'Codigo CABYS ' + json.codigo,
                                orientation: 'landscape',


                                customize: function(doc) {
                                    doc.defaultStyle.fontSize = 9; //<-- set fontsize to 16 instead of 10
                                }
                            }
                            /* ,
                                        {
                                           text: 'Realizar Nueva Busqueda CABYS en Hacienda',
                                           action: function ( e, dt, node, config ) {
                                               buscar_de_nuevo( );
                                               alert( 'Button activated' );
                                           }
                                       }
                                       */


                        ]
                    });

                });





            }).fail(function(jqxhr, textStatus, error) {
                var err = textStatus + ", " + error;
                console.log("Request Failed: " + err);
                texto += "<br><Br>Ocurrio Un Error.<br>Por favor intentalo de nuevo.";
                $("#alerta_hacienda_txt").empty().hide().html(texto).show()


                //   $("#json_nombre").empty().html( "Error : "+ err);
                //  $("#destino_informacion").fadeIn("slow");


            });


    }

 
 

    function cargar_tabla_productos() {
        var vtabla = $('#style-3').DataTable({
            'processing': true,
            'serverSide': true,
            'bSort': false,
            'pageLength': 20,
            'order': [
                [0, "desc"]
            ],
            'select': {
                'style': 'multi'
            },
            'dom': estiloPaginado.dom,
            'oLanguage': estiloPaginado.oLanguage,
            'stripeClasses': [],
            'ajax': {
                'url': '<?php echo ENLACE_WEB; ?>mod_productos/ajax/listado.servicios.ajax.php', // Asegúrate de que esta URL sea correcta
                'type': 'POST',
                'data': function(d) {
                    // Aquí puedes ajustar los parámetros que se envían al servidor
                    // Por ejemplo, si necesitas enviar datos adicionales, puedes hacerlo aquí
                }
            },
            'retrieve': true,
            'deferRender': true,
            'scroller': true,
            'responsive': true,
            // 'initComplete': function() {
            //     this.api().columns().every(function() {
            //         var column = this;
            //         var header = $(column.header());
            //         var headerText = header.text(); // Guarda el texto original del encabezado
            //         header.empty(); // Limpia el encabezado

            //         // Crea un contenedor div para el texto del encabezado
            //         var headerTextContainer = $('<div>').appendTo(header);
            //         $('<span>').text(headerText).appendTo(headerTextContainer);
            //         var inputContainer = $('<div>').appendTo(header);
            //         // Crea un contenedor div para el input/select
            //         var input = $('<input type="text" class="form-control">')
            //             .appendTo(inputContainer)
            //             .on('input', function() { // Cambiado de 'change' a 'input'
            //                 var val = $.fn.dataTable.util.escapeRegex(
            //                     $(this).val()
            //                 );
            //                 column
            //                     .search(val ? '^' + val + '$' : '', true, false)
            //                     .draw();
            //             });
            //     });
            // },
            "initComplete": function() {
                this.api().columns().every(function() {
                    var column = this;
                    var header = $(column.header());
                    var headerText = header.text(); // Guarda el texto original del encabezado
                    header.empty(); // Limpia el encabezado
                    console.log(headerText)

                    // Crea un contenedor div para el texto del encabezado
                    var headerTextContainer = $('<div>').appendTo(header);
                    $('<span>').text(headerText).appendTo(headerTextContainer);

                    // Crea un contenedor div para el input/select
                    var inputContainer = $('<div>').appendTo(header);

                    if (column.dataSrc() === 'tosell' || column.dataSrc() === 'tobuy') {
                        let select = generarSelectSiNo(column.dataSrc());
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
            'columns': [{
                    'data': 'ref',
                    'searchable': true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'Ref');
                    }
                },
                {
                    'data': 'label',
                    'searchable': true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'Nombre');
                    },
                    render: function(data, type, row) {
                        let icono;
                        if (row.tipo == 1) {
                            icono = `<i class="fa fa-fw fa-fw  fa-box"></i> ${data}`;
                        } else if (row.tipo == 2) {
                            icono = `<i class="fa fa fa-fw fa-handshake"></i> ${data}`;
                        }
                        return `<a href="${ENLACE_WEB}productos_editar/${row.rowid}">
                              ${icono} </a>`
                    }
                },
                {
                    'data': 'stock_actual_txt',
                    'searchable': true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'Stock');
                    }
                },
                {
                    'data': 'tosell',
                    'searchable': true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'Venta');
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
                    'data': 'tobuy',
                    'searchable': true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'Compra');
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
                    'data': 'CABYS',
                    'searchable': true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'CABYS');
                    }
                },
                {
                    'data': 'impuesto',
                    'searchable': true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'Impuesto');
                    }
                },
                {
                    'data': 'precio',
                    'searchable': true,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'Precio');
                    }
                },
                //{ 'data': 'total', 'searchable': true },
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
    


    /* datatable */
</script>