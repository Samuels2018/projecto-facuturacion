<script>
    $(document).ready(function() {

        <?php
        if ($_GET['accion'] == "productos_editar") {
        ?>
            cargar_unidades($("#tipo").val());
        <?php
        }
        ?>
        cargar_subcategorias_producto($("#diccionario_1").val());

        if ($('[name="fiche"]').val() != '') {
            tab_precio_cliente();
            tab_costo();
            tab_imagenes();
            tab_stock();
        }


        $("#birds").autocomplete({
            source: "<?php echo ENLACE_WEB ?>mod_productos/json/productos.json.php?tipo=" + $('[name="tipo"]').val(),
            minLength: 2,
            select: function(event, ui) {


                $("#id_objeto").val(ui.item.id);
                // alert(ui.item.id);
                // alert(ui.item.label);

            }
        });

        /* estados de checkboxes */
        $('#tosell, #tobuy , #impuesto_retencion').change(function(event) {

            // event.eventPreventDefault();
            // event.stopPropagation();
            console.log($(this).attr('value'));
            if ($(this).attr('value') == 1) {
                $(this).removeAttr('checked');
                $(this).val(0);

                // Aquí puedes agregar el código para manejar el estado marcado
            } else {
                // El checkbox no está marcado
                $(this).val(1);
                $(this).attr('checked', 'checked');

                // Aquí puedes agregar el código para manejar el estado desmarcado
            }
        });




    })


    function subirArchivo(event) {

        event.preventDefault();

        // Recoger los valores del formulario usando jQuery
        const formData = new FormData($('#MyUploadForm')[0]);

        // Preparar la petición AJAX
        $.ajax({
            method: "POST",
            url: "<?php echo ENLACE_WEB; ?>mod_productos/ajax/subir_imagenes.ajax.php",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
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

                add_notification({
                    text: 'Archivo subido exitosamente!',
                    actionTextColor: '#fff',
                    backgroundColor: '#00ab55',
                    ActionText: 'Cerrar'
                })

                tab_imagenes();
            }

            // Aquí puedes añadir código para actualizar la interfaz de usuario si es necesario
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.error("Error en la petición AJAX:", textStatus, errorThrown);
            add_notification({
                text: 'Hubo un error al subir el archivo.',
                actionTextColor: '#fff',
                backgroundColor: '#e7515a'
            })
        });
    }

    function eliminar_imagen(x, label) {
        let fk_producto = $('[name="fiche"]').val();



        var message = "Deseas eliminar esta imagen?";
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
                    url: "<?php echo ENLACE_WEB; ?>mod_productos/class/producto.class.php",
                    data: {
                        action: 'borrarImagenProducto',
                        id: x,
                        fk_producto: fk_producto,
                        label: label
                    },
                    cache: false,
                    //contentType: false,
                    //processData: false,
                }).done(function(msg) {
                    console.log(msg);
                    // const response = JSON.parse(msg);

                    if (msg.error == 1) {

                        add_notification({
                            text: msg.datos,
                            pos: 'top-right',
                            actionTextColor: '#fff',
                            backgroundColor: '#e7515a'
                        });

                    } else {

                        add_notification({
                            text: 'Imagen eliminada exitosamente!',
                            actionTextColor: '#fff',
                            backgroundColor: '#00ab55',
                            dismissText: 'Cerrar'
                        })


                        tab_imagenes();
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

    }


    function confirma_eliminar_relacion(cifrado) {
        console.log("Proceso Eliminado");
        if (confirm("Seguro que deseas desvincular  este producto de  <?php echo $sucursal->label; ?> ")) {


            $("#listado__").empty();
            $("#listado__").html('<img src="<?php echo ENLACE_WEB; ?>bootstrap/sistema/carga.gif" >');


            /**********************************/
            $.post("<?php echo ENLACE_WEB; ?>mod_productos/ajax/listado_compuestos_producto.php?fiche=<?php echo $_REQUEST['fiche']; ?>", {
                    relacion: cifrado
                })
                .done(function(data) {
                    $("#listado__").empty();
                    $("#listado__").html(data);
                    // alert( "Data Loaded: " + data );
                });
            /************************************/



        }
    }
</script>

<script>
    function vincular_objeto() {

        $("#birds").val(' ');
        var cantidad = $("#cantidad_objeto").val();
        var objeto = $("#id_objeto").val();
        var gratis = $("#gratis_objeto").val();


        $("#listado__").empty();
        $("#listado__").html('<img src="<?php echo ENLACE_WEB; ?>bootstrap/sistema/carga.gif" >');

        /**********************************/
        $.post("<?php echo ENLACE_WEB; ?>mod_productos/ajax/listado_compuestos_producto.php?fiche=<?php echo $_REQUEST['fiche']; ?>", {
                cantidad: cantidad,
                objeto: objeto,
                gratis: gratis
            })
            .done(function(data) {
                $("#listado__").empty();
                $("#listado__").html(data);

                // alert( "Data Loaded: " + data );
            });
        /************************************/

    }
</script>
<script>
    // FUNCTION DELETE INFO PRODUCT
    function confirma_eliminar_producto(product) {
        // Preparar el mensaje para el snackbar
        var message = "Está seguro(a) que desea eliminar el producto <?php echo $producto->label; ?> y su información adjunta?";
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
                    url: "<?php echo ENLACE_WEB; ?>mod_productos/class/producto.class.php",
                    beforeSend: function(xhr) {},
                    data: {
                        action: 'proccessDeleteInfoProduct',
                        product: product
                    },
                }).done(function(msg) {
                    var data = JSON.parse(msg);
                    // VALID RESULT
                    if (data.error == 1) {
                        add_notification({
                            text: data.datos,
                            pos: 'top-right',
                            actionTextColor: '#fff',
                            backgroundColor: '#e7515a'
                        });
                    } else {
                        add_notification({
                            text: 'Producto eliminado exitosamente!',
                            actionTextColor: '#fff',
                            backgroundColor: '#00ab55',
                            dismissText: 'Cerrar'
                        });

                        window.location = "<?php echo ENLACE_WEB; ?>dashboard.php?accion=productos_listado&amp;tipo=productos";
                    }
                });
            }
        });
    }


    function traer_unidades(int) {
        $.ajax({
            method: "POST",
            url: "<?php echo ENLACE_WEB; ?>mod_productos/class/producto.class.php",
            beforeSend: function(xhr) {},
            data: {
                action: 'cargar_categorias',
                fk_parent: int
            },
        }).done(function(msg) {
            var data = JSON.parse(msg); // Convierte el mensaje en un objeto JSON

            // Obtén el selector cargar_categorias
            var selector = $('#diccionario_1');

            // Limpia las opciones anteriores del selector
            selector.empty();

            // Si hay datos, añade las opciones
            selector.append(
                $('<option>', {
                    value: '', // El valor es vacío
                    text: 'Seleccione' // El texto es "No configurado"
                })
            );
            // Si hay datos, añade las opciones
            data.forEach(function(item) {
                // Crea la opción
                var option = $('<option>', {
                    value: item.rowid, // El valor de la opción es el rowid
                    text: item.label // El texto que se muestra es el label
                });

                // Si el rowid es el actual, marcarlo como seleccionado
                if (item.rowid == <?= $productos->diccionario_1 ?? 0 ?>) {
                    option.prop('selected', true); // Marcar como seleccionado
                }

                // Añadir la opción al selector
                selector.append(option);
            });


        });
    }



    // traer categorias de productos
    function cargar_categorias(int) {
        $.ajax({
            method: "POST",
            url: "<?php echo ENLACE_WEB; ?>mod_productos/class/producto.class.php",
            beforeSend: function(xhr) {},
            data: {
                action: 'cargar_categorias',
                fk_parent: int
            },
        }).done(function(msg) {
            var data = JSON.parse(msg); // Convierte el mensaje en un objeto JSON

            // Obtén el selector cargar_categorias
            var selector = $('#diccionario_1');

            // Limpia las opciones anteriores del selector
            selector.empty();

            // Si hay datos, añade las opciones
            selector.append(
                $('<option>', {
                    value: '', // El valor es vacío
                    text: 'Seleccione' // El texto es "No configurado"
                })
            );
            // Si hay datos, añade las opciones
            data.forEach(function(item) {
                // Crea la opción
                var option = $('<option>', {
                    value: item.rowid, // El valor de la opción es el rowid
                    text: item.label // El texto que se muestra es el label
                });

                // Si el rowid es el actual, marcarlo como seleccionado
                if (item.rowid == <?= $productos->diccionario_1 ?? 0 ?>) {
                    option.prop('selected', true); // Marcar como seleccionado
                }

                // Añadir la opción al selector
                selector.append(option);
            });


        });
    }



    // traer sub categorias de productos
    function cargar_subcategorias_producto(int) {
        $.ajax({
            method: "POST",
            url: "<?php echo ENLACE_WEB; ?>mod_productos/class/producto.class.php",
            beforeSend: function(xhr) {},
            data: {
                action: 'cargar_subcategorias_producto',
                fk_parent: int
            },
        }).done(function(msg) {
            var data = JSON.parse(msg); // Convierte el mensaje en un objeto JSON

            // Obtén el selector subcategoria_producto
            var selector = $('#subcategoria_producto'); // Asumiendo que el selector tiene el id "subcategoria_producto"

            // Limpia las opciones anteriores del selector
            selector.empty();

            // Verifica si el array data está vacío o nulo
            if (!data || data.length === 0) {
                // Si no hay subcategorías, muestra "No configurado"
                selector.append(
                    $('<option>', {
                        value: '', // El valor es vacío
                        text: 'No configurado' // El texto es "No configurado"
                    })
                );
            } else {
                // Si hay datos, añade las opciones
                selector.append(
                    $('<option>', {
                        value: '', // El valor es vacío
                        text: 'Seleccione' // El texto es "No configurado"
                    })
                );
                // Si hay datos, añade las opciones
                data.forEach(function(item) {
                    // Crea la opción
                    var option = $('<option>', {
                        value: item.rowid, // El valor de la opción es el rowid
                        text: item.label // El texto que se muestra es el label
                    });

                    // Si el rowid es el actual, marcarlo como seleccionado
                    if (item.rowid == <?= $productos->fk_parent_categoria_producto ?? 0 ?>) {
                        option.prop('selected', true); // Marcar como seleccionado
                    }

                    // Añadir la opción al selector
                    selector.append(option);
                });

            }
        });
    }
</script>

<script>
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


    function ver_categoria(int = null) {


        // Preparar la petición AJAX
        $.ajax({
            method: "POST",
            url: "<?php echo ENLACE_WEB; ?>mod_diccionario_categoria/tpl/modal_diccionario_categoria.php",
            beforeSend: function(xhr) {
                // aqui deberia ocurrir una carga
            },
            data: {
                action: 'ver_categoria',
                fiche: int,
            },
        }).done(function(html) {

            //print html en el modal cargado
            $("#nueva_diccionario_categoria").html(html).modal('show');


        });

    }


    function crear_diccionario_categoria(event) {
        event.preventDefault();

        let error = false;

        // Eliminar la clase de error de los campos antes de validar
        $('.modal-body #label').removeClass("input_error");

        // Recoger los valores del formulario usando jQuery
        const label = $('.modal-body #label').val();

        // Validar que los campos no estén vacíos y añadir la clase de error si es necesario
        if (label == '') {
            $('.modal-body #label').addClass("input_error");
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
            url: "<?php echo ENLACE_WEB; ?>mod_diccionario_categoria/ajax/diccionario_categoria_ajax.php",
            beforeSend: function(xhr) {

            },
            data: {
                action: 'crear_diccionario_categoria',
                label: label,
                fk_parent: $("#categoria_padre").val(),
                estado: $("#estado_diccionario_categoria").val(),

            },
        }).done(function(msg) {
            //    console.log("Actualizando");
            console.log(msg);

            var mensaje = jQuery.parseJSON(msg);

            if (mensaje.exito == 1) {
                $("#nueva_diccionario_categoria").modal('hide');



                add_notification({
                    text: 'Categoria Producto creado exitosamente',
                    actionTextColor: '#fff',
                    backgroundColor: '#00ab55',
                    dismissText: 'Cerrar'
                });

                // recargar la lista de opciones
                cargar_categorias();

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

    function ver_log_precios(fk_lista = null) {

        // Preparar la petición AJAX
        $.ajax({
            method: "POST",
            url: "<?php echo ENLACE_WEB; ?>mod_productos/class/producto.class.php",
            beforeSend: function(xhr) {
                // aqui deberia ocurrir una carga
            },
            data: {
                action: 'ver_log_precios',
                fk_producto: $('input[name="fiche"]').val(),
                fk_lista: fk_lista,
            },
        }).done(function(html) {
            //print html en el div historico
            $("#tbody_historico_precio").html(html);
            $("#div_historico").show();
        });

    }
    //Funcion para ver las listas de precios
    function ver_lista_precios(int = null) {
        // Preparar la petición AJAX
        $.ajax({
            method: "POST",
            url: "<?php echo ENLACE_WEB; ?>mod_diccionario_listas_precios/tpl/modal_lista_precios.php",
            beforeSend: function(xhr) {
                // aqui deberia ocurrir una carga
            },
            data: {
                action: 'ver_modal',
                fiche: int,
            },
        }).done(function(html) {
            //print html en el modal cargado
            $("#nueva_diccionario_listas_precios").html(html).modal('show');
        });
    }


    function crear_lista_precios(event) {
        event.preventDefault();

        let error = false;

        // Eliminar la clase de error de los campos antes de validar
        $("#nueva_diccionario_listas_precios").find(".modal-dialog .modal-content .modal-body form div.card-body div.row div input#label").removeClass("input_error");

        // Recoger los valores del formulario usando jQuery
        const label = $("#nueva_diccionario_listas_precios").find(".modal-dialog .modal-content .modal-body form div.card-body div.row div input#label").val();



        // Validar que los campos no estén vacíos y añadir la clase de error si es necesario
        if (label == '') {
            $("#nueva_diccionario_listas_precios").find(".modal-dialog .modal-content .modal-body form div.card-body div.row div input#label").addClass("input_error");
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
            url: "<?php echo ENLACE_WEB; ?>mod_diccionario_listas_precios/ajax/diccionario_lista_precios_ajax.php",
            beforeSend: function(xhr) {

            },
            data: {
                action: 'crear_lista_precios',
                etiqueta: label,
                estado: $("#estado_lista").val(),

            },
        }).done(function(msg) {
            //    console.log("Actualizando");
            console.log(msg);

            var mensaje = jQuery.parseJSON(msg);

            if (mensaje.exito == 1) {
                $("#nueva_diccionario_listas_precios").modal('hide');
                cargar_lista_precios('');
                add_notification({
                    text: 'Nombres Lista precio creado exitosamente',
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

    //Funcion para cargar la lista de precios
    function cargar_lista_precios(x) {
        console.log(x);
        $.ajax({
            method: "POST",
            url: "<?php echo ENLACE_WEB; ?>mod_diccionario_listas_precios/ajax/cargas_listas_precios.ajax.php",
            beforeSend: function(xhr) {},
            data: {
                tipo: x,
            },
        }).done(function(msg) {
            ////console.log(msg);
            $('#lista_precios_div').empty();
            $('#lista_precios_div').html(msg).fadeIn();
        });
    }


    //fUNCION PARA VER LAS MONEDAS
    function ver_moneda(int = null) {
        // Preparar la petición AJAX
        $.ajax({
            method: "POST",
            url: "<?php echo ENLACE_WEB; ?>mod_diccionario_moneda/tpl/modal_moneda.php",
            beforeSend: function(xhr) {
                // aqui deberia ocurrir una carga
            },
            data: {
                action: 'ver_modal',
                fiche: int,
            },
        }).done(function(html) {
            //print html en el modal cargado
            $("#nueva_diccionario_moneda").html(html).modal('show');
        });
    }

    //Creamos la moneda
    function crear_moneda(event) {
        event.preventDefault();

        let error = false;

        // Eliminar la clase de error de los campos antes de validar
        $('#label').removeClass("input_error");

        $("#nueva_diccionario_moneda").find(".modal-dialog .modal-content .modal-body form div.card-body div.row div input#label").removeClass("input_error");

        // Recoger los valores del formulario usando jQuery
        const label = $("#nueva_diccionario_moneda").find(".modal-dialog .modal-content .modal-body form div.card-body div.row div input#label").val();

        // Validar que los campos no estén vacíos y añadir la clase de error si es necesario
        if (label == '') {
            $("#nueva_diccionario_moneda").find(".modal-dialog .modal-content .modal-body form div.card-body div.row div input#label").addClass("input_error");
            error = true;
        }

        if ($("#simbolo").val() == '') {
            $('#simbolo').addClass("input_error");
            error = true;
        }

        if ($("#codigo").val() == '') {
            $('#codigo').addClass("input_error");
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
            url: "<?php echo ENLACE_WEB; ?>mod_diccionario_moneda/ajax/diccionario_moneda_ajax.php",
            beforeSend: function(xhr) {

            },
            data: {
                action: 'crear_moneda',
                etiqueta: label,
                estado: $("#estado_moneda").val(),
                simbolo: $("#simbolo").val(),
                codigo: $("#codigo").val(),
            },
        }).done(function(msg) {
            //    console.log("Actualizando");
            console.log(msg);

            var mensaje = jQuery.parseJSON(msg);

            if (mensaje.exito == 1) {
                $("#nueva_diccionario_moneda").modal('hide');
                cargar_monedas('');
                add_notification({
                    text: 'Moneda creado exitosamente',
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
    //CARGAMOS LA MONEDA
    function cargar_monedas(x = '') {
        console.log(x);
        $.ajax({
            method: "POST",
            url: "<?php echo ENLACE_WEB; ?>mod_diccionario_moneda/ajax/cargar_monedas.ajax.php",
            beforeSend: function(xhr) {},
            data: {
                tipo: x,
                unidad: '<?= $productos->unidad ?? 0 ?>',
                disabled: '<?= $disabled ?>',
            },
        }).done(function(msg) {
            ////console.log(msg);
            $('#unidad_moneda').empty();
            $('#unidad_moneda').html(msg).fadeIn();
        });

    }





    function ver_unidad(int = null) {

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
            $("#nueva_diccionario_categoria").html(html).modal('show');
        });

    }



    function ver_politica(int = null) {



        // Preparar la petición AJAX
        $.ajax({
            method: "POST",
            url: "<?php echo ENLACE_WEB; ?>mod_productos/tpl/modal_politica_descuento.php",
            beforeSend: function(xhr) {
                // aqui deberia ocurrir una carga
            },
            data: {
                action: 'ver_modal',
                fiche: int,
            },
        }).done(function(html) {
            //print html en el modal cargado
            $("#nueva_diccionario_categoria").html(html).modal('show');

            if (int > 0) {
                ver_politica_detalle(int);
                // Agrega aquí un fade al mostrar el div detalle_politica
                $("#detalle_politica").fadeIn();
                $("#agregar_politica").hide();
                $("#borrar_politica").show();
                $("#actualizar_politica").show();
            }

        });

    }



    function ver_politica_detalle(int) {
        // Preparar la petición AJAX
        $.ajax({
            method: "POST",
            url: "<?php echo ENLACE_WEB; ?>mod_productos/class/producto.class.php",
            beforeSend: function(xhr) {
                // Aquí podrías mostrar una animación de carga si lo deseas
                $("#detalle_politicas").fadeOut();
            },
            data: {
                action: 'ver_politica_detalle',
                fiche: int,
            },
        }).done(function(html) {
            // print html en el modal cargado
            $("#detalle_politicas").html(html).fadeIn();
        });
    }


    function crear_politica_detalle(event) {
        event.preventDefault();
        let error = false;
        // Preparar la petición AJAX
        $.ajax({
            method: "POST",
            url: "<?php echo ENLACE_WEB; ?>mod_productos/class/producto.class.php",
            beforeSend: function(xhr) {
                // Puedes agregar alguna función aquí si lo necesitas
            },
            data: {
                action: 'crear_politica_detalle',
                cantidad: $('#cantidad_politica').val(),
                porcentaje_politica: $("#porcentaje_politica").val(),
            },
        }).done(function(msg) {
            var mensaje = jQuery.parseJSON(msg);
            if (mensaje.exito == 1) {
                add_notification({
                    text: 'Politica creada exitosamente',
                    actionTextColor: '#fff',
                    backgroundColor: '#00ab55',
                    dismissText: 'Cerrar'
                });
                // Agrega aquí un fade al mostrar el div detalle_politica
                $("#detalle_politica").fadeIn();
                $("#agregar_politica").hide();
                $("#borrar_politica").show();
                $("#actualizar_politica").show();

                //traemos los registro detalle para esta politica
                traerDetalle();
            } else {
                add_notification({
                    text: "Error:" + mensaje.error_txt,
                    actionTextColor: '#fff',
                    backgroundColor: '#e7515a',
                });
            }
        });
    }


    function crear_politica(event) {
        event.preventDefault();
        let error = false;
        // Preparar la petición AJAX
        $.ajax({
            method: "POST",
            url: "<?php echo ENLACE_WEB; ?>mod_productos/class/producto.class.php",
            beforeSend: function(xhr) {
                // Puedes agregar alguna función aquí si lo necesitas
            },
            data: {
                action: 'crear_politica',
                fecha_inicial: $("#fecha_inicial").val(),
                fecha_final: $("#fecha_final").val(),
                tipo_politica: $("#tipo_politica").val(),
                fk_producto: $('[name="fiche"]').val(),
                cantidad: $('#cantidad_politica').val(),
                porcentaje_politica: $("#porcentaje_politica").val(),
            },
        }).done(function(msg) {
            var mensaje = jQuery.parseJSON(msg);
            if (mensaje.exito == 1) {
                add_notification({
                    text: 'Politica creada exitosamente',
                    actionTextColor: '#fff',
                    backgroundColor: '#00ab55',
                    dismissText: 'Cerrar'
                });
                // Agrega aquí un fade al mostrar el div detalle_politica
                $("#detalle_politica").fadeIn();
                $("#agregar_politica").hide();
                $("#borrar_politica").show();
                $("#actualizar_politica").show();

                //traemos los registro detalle para esta politica
                traerDetalle();
            } else {
                add_notification({
                    text: "Error:" + mensaje.error_txt,
                    actionTextColor: '#fff',
                    backgroundColor: '#e7515a',
                });
            }
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
            //      console.log(msg);

            var mensaje = jQuery.parseJSON(msg);

            if (mensaje.exito == 1) {

                $("#nueva_diccionario_categoria").modal('hide');

                add_notification({
                    text: 'Unidad creado exitosamente',
                    actionTextColor: '#fff',
                    backgroundColor: '#00ab55',
                    dismissText: 'Cerrar'
                });

                cargar_unidades($("#tipo").val());


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



    function cargar_unidades(x) {
        console.log(x);
        $.ajax({
            method: "POST",
            url: "<?php echo ENLACE_WEB; ?>mod_productos/ajax/cargar_unidades.ajax.php",
            beforeSend: function(xhr) {},
            data: {
                tipo: x,
                unidad: '<?= $productos->unidad ?? 0 ?>',
                disabled: '<?= $disabled ?>',
                required: 'required'
            },
        }).done(function(msg) {
            ////console.log(msg);
            $('#unidad_selector').empty();
            $('#unidad_selector').attr('required');
            $('#unidad_selector').html(msg).fadeIn();
        });

    }





    function tab_precio_cliente() {

        $.ajax({
            method: "POST",
            url: "<?php echo  "" . ENLACE_WEB ?>mod_productos/ajax/productos_precio.ajax.php",
            data: {
                // pagina: int,
                fiche: $("[name='fiche']").val(),
            },
        }).done(function(result) {
            //console.log(result)
            $("#pills-clientes").html('');
            $("#pills-clientes").html(result);
        });
    }

    function tab_costo() {

        $.ajax({
            method: "POST",
            url: "<?php echo  "" . ENLACE_WEB ?>mod_productos/ajax/productos_costo.ajax.php",
            data: {
                // pagina: int,
                fiche: $("[name='fiche']").val(),
            },
        }).done(function(result) {
            // console.log(result)
            $("#pills-costo").html('');
            $("#pills-costo").html(result);
        });
    }

    function tab_imagenes() {

        $.ajax({
            method: "POST",
            url: "<?php echo  "" . ENLACE_WEB ?>mod_productos/ajax/productos_imagenes.ajax.php",
            data: {
                // pagina: int,
                fiche: $("[name='fiche']").val(),
            },
        }).done(function(result) {
            // console.log(result)
            $("#pills-imagenes").html('');
            $("#pills-imagenes").html(result);
        });
    }

    function tab_stock() {

        $.ajax({
            method: "POST",
            url: "<?php echo  "" . ENLACE_WEB ?>mod_productos/ajax/productos_stock.ajax.php",
            data: {
                // pagina: int,
                fiche: $("[name='fiche']").val(),
            },
        }).done(function(result) {
            // console.log(result)
            $("#pills-stock").html('');
            $("#pills-stock").html(result);
        });
    }





    //crud via jax
    function actualizarProducto(event) {
        event.preventDefault();

        error = false;

        /* Valida los inputs requeridos */
        const inputTypes = [];
        $('#pills-producto input[name][id][value]').each(function(index, element) {
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
            if (x.required && x.value == '') {
                $('#' + x.name).addClass('input_error');
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
        /* Valida los inputs requeridos */


        // Recoger los valores del formulario usando jQuery
        const id = $('input[name="fiche"]').val();
        const ref = $('input[name="ref"]').val();
        const label = $('input[name="label"]').val();
        const codigo_barras = $('input[name="codigo_barras"]').val();
        const tipo = $('select[name="tipo"]').val();
        const unidad = $('select[name="unidad"]').val();
        const tosell = $('[name=tosell]').val();
        const tobuy = $('[name=tobuy]').val();
        const impuesto_retencion = $('[name=impuesto_retencion]').val();
        const descuento_maximo = $('[name="descuento_maximo"]').val();
        const impuesto_fk = $('[name="impuesto_fk"]').val();
        const subcategoria_producto = $("#subcategoria_producto").val()
        const stock_minimo_alerta = $("#stock_minimo_alerta").val()
        const notas = $("#notas").val()
        const descripcion = $("#descripcion").val()

        const diccionario_1 = $("#diccionario_1").val()

        // Preparar la petición AJAX
        $.ajax({
            method: "POST",
            url: "<?php echo ENLACE_WEB; ?>mod_productos/class/producto.class.php",
            data: {
                action: 'actualizarProducto',
                id: id,
                ref: ref,
                label: label,
                codigo_barras: codigo_barras,
                fk_parent_categoria_producto: subcategoria_producto,
                tipo: tipo,
                unidad: unidad,
                tosell: tosell,
                tobuy: tobuy,
                impuesto_retencion: impuesto_retencion,
                stock_minimo_alerta: stock_minimo_alerta,
                notas: notas,
                diccionario_1: diccionario_1,
                diccionario_2: 0,
                diccionario_3: 0,
                diccionario_4: 0,
                diccionario_5: 0,
                diccionario_6: 0,
                diccionario_7: 0,
                diccionario_8: 0,
                diccionario_9: 0,
                diccionario_10: 0,
                descripcion: descripcion,
                impuesto_fk: impuesto_fk,
                descuento_maximo: descuento_maximo
            },
        }).done(function(msg) {
            const response = JSON.parse(msg);
            console.log(response);

            if (response.exito == 1) {
                add_notification({
                    text: response.datos,
                    pos: 'top-right',
                    backgroundColor: '#00ab55',
                    dismissText: 'Cerrar'

                });
                setTimeout(() => {
                    window.location.href = "<?php echo ENLACE_WEB; ?>dashboard.php?accion=productos_editar&action=modificar&fiche=" + response.id;
                }, 3000);

            } else {
                add_notification({
                    text: response.datos,
                    actionTextColor: '#fff',
                    actionTextColor: '#fff',
                    backgroundColor: '#e7515a'
                })
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


    function actualizarPrecio(event) {
        console.log('se ejecuta');
        event.preventDefault();

        // Recoger los valores del formulario usando jQuery
        const id = $('input[name="fiche"]').val();
        const precio_base = $('input[name="precio_base"]').val();
        const tipo = $('select[name="tipo_impuesto"]').val();
        const impuesto = $('select[name="impuesto"]').val();
        const moneda = $('select[name="moneda"]').val();

        // Preparar la petición AJAX
        $.ajax({
            method: "POST",
            url: "<?php echo ENLACE_WEB; ?>mod_productos/class/producto.class.php",
            data: {
                action: 'actualizarPrecio',
                id: id,
                precio_base: precio_base,
                tipo: tipo,
                fk_lista: $("#listas_precios").val(),
                porcentaje_utilidad: $("#porcentaje_utilidad").val(),
                porcentaje_descuento: $("#porcentaje_descuento").val(),
                impuesto: impuesto,
                moneda: moneda
            },
        }).done(function(msg) {


            var mensaje = jQuery.parseJSON(msg);

            if (mensaje.exito == 1) {
                add_notification({
                    text: mensaje.mensaje,
                    actionTextColor: '#fff',
                    backgroundColor: '#00ab55',
                    dismissText: 'Cerrar'
                });
                tab_precio_cliente();
            } else {

                add_notification({
                    text: mensaje.mensaje,
                    actionTextColor: '#fff',
                })


            }

            // Aquí puedes añadir código para actualizar la interfaz de usuario si es necesario
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.error("Error en la petición AJAX:", textStatus, errorThrown);

            add_notification({
                text: 'Hubo un error al guardar el nuevo precio.',
                actionTextColor: '#fff',
                backgroundColor: '#e7515a'
            })

        });
    }


    function actualizarCosto(event) {
        event.preventDefault();

        // Recoger los valores del formulario usando jQuery 
        const producto = $('input[name="fiche"]').val();
        const precioCosto = $('input[name="precio_costo"]').val();
        const impuesto = $('select[name="impuesto_costo"]').val();
        const nota = $('input[name="nota"]').val();

        // Preparar la petición AJAX
        $.ajax({
            method: "POST",
            url: "<?php echo ENLACE_WEB; ?>mod_productos/class/producto.class.php",
            data: {
                action: 'actualizarCosto',
                fk_producto: producto,
                precio: precioCosto,
                impuesto: impuesto,
                nota: nota
            },
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
                    text: 'Precio de costo actualizado exitosamente!',
                    actionTextColor: '#fff',
                    backgroundColor: '#00ab55',
                    dismissText: 'Cerrar'
                })

                tab_costo();
            }


        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.error("Error en la petición AJAX:", textStatus, errorThrown);

            add_notification({
                text: 'Hubo un error al actualizar el precio de costo.',
                actionTextColor: '#fff',
                backgroundColor: '#e7515a'
            })


        });
    }


    function crearProducto(event) {
        event.preventDefault();

        error = false;

        /* Valida los inputs requeridos */
        const inputTypes = [];
        $('#pills-producto input[name][id][value]').each(function(index, element) {
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
            if (x.required && x.value == '') {
                $('#' + x.name).addClass('input_error');
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
        /* Valida los inputs requeridos */

        // $('input[name="ref"]').removeClass("input_error");
        // $('input[name="stock_minimo_alerta"]').removeClass("input_error");
        // $('input[name="label"]').removeClass("input_error");


        // Recoger los valores del formulario usando jQuery
        const ref = $('input[name="ref"]').val();
        const label = $('input[name="label"]').val();
        const tipo = $('select[name="tipo"]').val();
        const unidad = $('select[name="unidad"]').val();
        const tosell = $('[name="tosell"]').val();
        const tobuy = $('[name="tobuy"]').val();
        const impuesto_retencion = $('[name="impuesto_retencion"]').val();
        const stock_minimo_alerta = $('input[name="stock_minimo_alerta"]').val();
        const notas = $('textarea[name="notas"]').val();
        const diccionario_1 = $('[name="diccionario_1"]').val();
        const codigo_barras = $('[name="codigo_barras"]').val();
        const descripcion = $('[name="descripcion"]').val();
        const conart = $('[name="conart"]').val();
        const descuento_maximo = $('[name="descuento_maximo"]').val();
        const impuesto_fk = $('[name="impuesto_fk"]').val();
        const subcategoria = $("#subcategoria_producto").val();


        // Preparar la petición AJAX
        $.ajax({
            method: "POST",
            url: "<?php echo ENLACE_WEB; ?>mod_productos/class/producto.class.php",
            data: {
                action: 'crearProducto',
                ref: ref,
                label: label,
                codigo_barras: codigo_barras,
                tipo: tipo,
                unidad: unidad,
                tosell: tosell,
                tobuy: tobuy,
                impuesto_retencion: impuesto_retencion,
                stock_minimo_alerta: stock_minimo_alerta,
                fk_parent_categoria_producto: subcategoria,
                notas: notas,
                diccionario_1: diccionario_1,
                descripcion: descripcion,
                conart: conart,
                impuesto_fk: impuesto_fk,
                descuento_maximo: descuento_maximo
            },
        }).done(function(msg) {
            const response = JSON.parse(msg);
            console.log(response);

            if (response.exito == 1) {
                add_notification({
                    text: response.mensaje,
                    pos: 'top-right',
                    backgroundColor: '#00ab55',
                    dismissText: 'Cerrar'

                });

                setTimeout(() => {
                    window.location.href = "<?php echo ENLACE_WEB; ?>dashboard.php?accion=productos_editar&action=modificar&fiche=" + response.id;
                }, 3000);



            } else {
                add_notification({
                    text: response.datos,
                    actionTextColor: '#fff',
                    actionTextColor: '#fff',
                    backgroundColor: '#e7515a'
                })


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

    function actualizarStock(event) {
        event.preventDefault();

        // Recoger los valores del formulario usando jQuery
        const producto = $('input[name="fiche"]').val();
        const bodega = $('select[name="bodega"]').val();
        const tipo = $('select[name="tipo_movimiento"]').val();
        const valor = $('input[name="valor"]').val();
        const motivo = $('input[name="motivo"]').val();

        // Validar los datos
        if (!bodega || !tipo || !valor || !motivo) {

            add_notification({
                text: 'Debes completar todos los campos del formulario.',
                pos: 'top-right',
                actionTextColor: '#fff',
                backgroundColor: '#e7515a'
            });

            return;
        }

        // Preparar la petición AJAX
        $.ajax({
            method: "POST",
            url: "<?php echo ENLACE_WEB; ?>mod_productos/class/producto.class.php",
            data: {
                action: 'actualizarStock',
                fk_producto: producto,
                fk_bodega: bodega,
                tipo: tipo,
                valor: valor,
                motivo: motivo
            },
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
                    text: 'El stock se ha actualizado correctamente.',
                    actionTextColor: '#fff',
                    backgroundColor: '#00ab55',
                    dismissText: 'Cerrar'
                })

                tab_stock();

            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.error("Error en la petición AJAX:", textStatus, errorThrown);

            add_notification({
                text: 'Hubo un error al actualizar el stock.',
                pos: 'top-right',
                actionTextColor: '#fff',
                backgroundColor: '#e7515a'
            });
        });
    }
</script>