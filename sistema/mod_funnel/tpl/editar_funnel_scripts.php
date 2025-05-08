<script>
    function crear_funnel(event) {
        event.preventDefault();

        // Recoger los valores del formulario usando jQuery
        let titulo = $('[name="titulo"]').val();
        let descripcion = $('[name="descripcion"]').val();
        let color = $('[name="color"]').val();
        let icono = $('[name="icono"]').val();

        let fields = {
            '[name="titulo"]': 'Título',
            '[name="descripcion"]': 'Descripción',
            '[name="color"]': 'Color',
            '[name="icono"]': 'Icono',
        };

        let errorMessage = "Debes completar los siguientes campos: ";
        let fieldsMissing = false;

        $.each(fields, function(id, name) {
            if ($(id).val() == '') {
                errorMessage += name + ', ';
                fieldsMissing = true;
            }
        });

        if (fieldsMissing) {
            errorMessage = errorMessage.slice(0, -2);
            console.log(errorMessage);
            add_notification({
                text: errorMessage,
                pos: 'top-right',
                actionTextColor: '#fff',
                backgroundColor: '#e7515a',
                actionText: 'Cerrar'
            });
            return false;
        }

        $.ajax({
            type: 'POST',
            url: '<?php echo ENLACE_WEB; ?>mod_funnel/class/funnel.class.php',
            data: {
                action: 'nuevo_funnel',
                titulo: titulo,
                descripcion: descripcion,
                color: color,
                icono: icono
            },
            success: function(data) {
                let response = JSON.parse(data);
                console.log('Success:', response);
                if (response.exito === true) {
                    add_notification({
                        text: response.mensaje,
                        actionTextColor: '#fff',
                        backgroundColor: '#00ab55',
                        actionText: 'Cerrar'
                    });

                    $('#style-3').DataTable().destroy();
                    cargar_tabla_funnels();
                    $('#exampleModal').modal('hide');
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

    function modificar_funnel(event) {
        event.preventDefault();

        let rowid = $('[name="rowid"]').val();
        let titulo = $('[name="titulo"]').val();
        let descripcion = $('[name="descripcion"]').val();
        let color = $('[name="color"]').val();
        let icono = $('[name="icono"]').val();

        let errorMessage = "Debes completar los siguientes campos: ";
        let fieldsMissing = false;

        let fields = {
            '[name="titulo"]': 'Título',
            '[name="descripcion"]': 'Descripción',
            '[name="color"]': 'Color',
            '[name="icono"]': 'Icono',
        };

        $.each(fields, function(id, name) {
            if ($(id).val() == '') {
                errorMessage += name + ', ';
                fieldsMissing = true;
            }
        });

        if (fieldsMissing) {
            errorMessage = errorMessage.slice(0, -2);
            console.log(errorMessage);
            add_notification({
                text: errorMessage,
                pos: 'top-right',
                actionTextColor: '#fff',
                backgroundColor: '#e7515a',
                actionText: 'Cerrar'
            });
            return false;
        }

        $.ajax({
            method: "POST",
            url: '<?php echo ENLACE_WEB; ?>mod_funnel/class/funnel.class.php',
            data: {
                action: 'modificar_funnel',
                rowid: rowid,
                titulo: titulo,
                descripcion: descripcion,
                color: color,
                icono: icono
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
                $('#style-3').DataTable().destroy();
                cargar_tabla_funnels();
                $('#exampleModal').modal('hide');
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
        });
    }

    function eliminar_funnel(event) {
        event.preventDefault();

        let rowid = $('[name="rowid"]').val();
        let message = "¿Deseas eliminar este funnel?";
        let actionText = "Confirmar";

        add_notification({
            text: message,
            width: 'auto',
            duration: 30000,
            actionText: actionText,
            dismissText: 'Cerrar',
            onActionClick: function(element) {
                $.ajax({
                    method: "POST",
                    url: "<?php echo ENLACE_WEB; ?>mod_funnel/class/funnel.class.php",
                    data: {
                        action: 'eliminar_funnel',
                        rowid: rowid
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

                        $('#style-3').DataTable().destroy();
                        cargar_tabla_funnels();
                        $('#exampleModal').modal('hide');
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
                        text: 'Hubo un error al marcar el funnel como eliminado.',
                        actionTextColor: '#fff',
                        backgroundColor: '#e7515a'
                    });
                });
            },
        });
    }

    function fetch(rowid) {
        // POST
        $.ajax({
            method: "POST",
            url: "<?php echo ENLACE_WEB; ?>mod_funnel/class/funnel.class.php",
            beforeSend: function(xhr) {},
            data: {
                action: 'fetch',
                rowid: rowid
            },
        }).done(function(data) {

            let response = JSON.parse(data);
            let content = '';

            obtener_detalles(rowid);

            $('#form-detalles').show();

            $('#pills-tab').css('display', 'flex');
            $('#pills-home').addClass('show');
            $('#pills-home').addClass('active');
            $('#pills-home-tab').addClass('active');
            $('#pills-profile-tab').removeClass('active');
            $('#pills-profile-tab').removeClass('show');
            $('#pills-profile').removeClass('active');
            $('#pills-profile').removeClass('show');
            $('#rowid').val(rowid);
            $("#titulo").val(response.titulo);
            $('#descripcion').val(response.descripcion);
            $('#color').attr('value', response.color).trigger('input');
            $('#icono').val(response.icono);


            $('#modal_titulo').text('Modificar');
            $('#boton_crear_txt').text('Modificar');
            $('#boton_eliminar').show();

            $('#funnel-order').append(content);

            $('#exampleModal').modal('show');


            $(".icon-selector").html('<div class="selector_icono"><i style="font-size:20px;" class="'+response.icono+'"></i> <a href="#" class="cambiar-icono">Cambiar</a></div>');


        });
    }


    //cambiar icono
    $(document).on("click",".cambiar-icono",function(){
        if($(".selector_iconos").is(':visible') === false){
            $(".selector_iconos").slideDown(500);
            $(this).text("Ocultar");
        }else{
            $(".selector_iconos").slideUp(500);
            $(this).text("Cambiar");
        }
    });

    $(document).on("click",".usar_icono",function(){
        clase = $(this).attr("claseicon");
        $("#icono").val(clase);
        $(".selector_icono").find('i').attr('class',clase);

    }); 



    function mostrar_modal() {
        $('#funnel-order').html('');
        $('#pills-tab').css('display', 'none');
        $('#pills-profile-tab').removeClass('active');
        $('#pills-profile-tab').removeClass('show');
        $('#pills-home').addClass('show');
        $('#pills-home').addClass('active');
        $("#titulo").val('');
        $('#descripcion').val('');
        $('#color').val('');
        $('#icono').val('');

        $('#form-detalles').hide();
        $('#boton_eliminar').hide();
        $('#modal_titulo').text('Crear');
        $('#boton_crear_txt').text('Crear');

           $(".icon-selector").html('<div class="selector_icono"><i style="font-size:20px;" class=""></i> <a href="#" class="cambiar-icono">Seleccionar Icono</a></div>');

        $('#exampleModal').modal('show');
    }

    function validar_accion() {
        let accion = $('#boton_crear_txt').text();
        if (accion == 'Crear') {
            crear_funnel(event);
        } else {
            modificar_funnel(event);
        }
    }

    function crear_detalle(event) {
        event.preventDefault();

        // Recoger los valores del formulario usando jQuery
        let fk_funnel = $('[name="rowid"]').val();
        let etiqueta = $('[name="etiqueta"]').val();
        let descripcion = $('[name="descripcion_funnel"]').val();
        //canvan mostrar coomp oclumna
        let canvan_mostrar_como_columna = $("#canvan_mostrar_como_columna").val();


        let fields = {
            '[name="etiqueta"]': 'Etiqueta',
            '[name="descripcion_funnel"]': 'Descripción',
        };

        let errorMessage = "Debes completar los siguientes campos: ";
        let fieldsMissing = false;

        $.each(fields, function(id, name) {
            if ($(id).val() == '') {
                errorMessage += name + ', ';
                fieldsMissing = true;
            }
        });

        if (fieldsMissing) {
            errorMessage = errorMessage.slice(0, -2);
            console.log(errorMessage);
            add_notification({
                text: errorMessage,
                pos: 'top-right',
                actionTextColor: '#fff',
                backgroundColor: '#e7515a',
                actionText: 'Cerrar'
            });
            return false;
        }

        $.ajax({
            type: 'POST',
            url: '<?php echo ENLACE_WEB; ?>mod_funnel/class/funnel.class.php',
            data: {
                action: 'crear_detalle',
                fk_funnel: fk_funnel,
                etiqueta: etiqueta,
                descripcion: descripcion,
                canvan_mostrar_como_columna:canvan_mostrar_como_columna
            },
            success: function(data) {

                console.log("Respuesta desde El Servidor");
                
                let response = JSON.parse(data);
                console.log('Success:', response);
                if (response.exito === true) {
                    add_notification({
                        text: response.mensaje,
                        actionTextColor: '#fff',
                        backgroundColor: '#00ab55',
                        actionText: 'Cerrar'
                    });

                    $('#etiqueta').val('');
                    $('#descripcion_funnel').val('');

                    obtener_detalles(fk_funnel);

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

    function actualizar_detalle(event) {
        event.preventDefault();

        // Recoger los valores del formulario usando jQuery
        let fk_estado = $('[name="fk_estado"]').val();
        let rowid = $('[name="rowid"]').val();

        // Verificar que todos los campos requeridos estén llenos
        if (!fk_estado || !rowid) {
            alert('Por favor, complete todos los campos requeridos.');
            return false;
        }

        $.ajax({
            type: 'POST',
            url: '<?php echo ENLACE_WEB; ?>mod_funnel/class/funnel.class.php',
            data: {
                action: 'actualizar_detalle',
                fk_estado: fk_estado,
                rowid: rowid
            },
            success: function(data) {
                let response = JSON.parse(data);
                if (response.exito === 1) {
                    alert(response.mensaje);
                    // Aquí puedes recargar la tabla o realizar cualquier otra acción necesaria
                } else {
                    alert('Error al actualizar el registro: ' + response.mensaje);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }

    function obtener_detalles(id) {
        console.warn('llega funnel: ', id);
        $.ajax({
            method: "POST",
            url: "<?php echo ENLACE_WEB; ?>mod_funnel/class/funnel.class.php",
            beforeSend: function(xhr) {},
            data: {
                action: 'obtener_detalles_funnel_general',
                rowid: id
            },
        }).done(function(data) {

            $('#funnel-order').html('');

            let response = JSON.parse(data);
            let content = '';
           
            $.each(response, function(index, item) {

                var icono = '<i class="fa fa-eye" aria-hidden="true"></i>';
                var clase_color = '';

                if(parseInt(item.canvan_mostrar_como_columna) === 0)
                {
                    icono = '<i class="fa fa-eye-slash" aria-hidden="true"></i>';
                    cambio_valor = 1;
                    clase_color = 'warning';
                }else{
                    icono = '<i class="fa fa-eye" aria-hidden="true"></i>';
                    cambio_valor = 0;
                    clase_color = 'success';
                }

                let template = `<div class="media  d-md-flex d-block" id="${item.rowid}" style="cursor: move">
                <div class="media-body">
                    <div class="d-xl-flex d-block justify-content-between">
                        <div class="">
                            <h6 class="" style="cursor: pointer" title="${item.etiqueta}"> ${item.etiqueta}</h6>
                            <p class="" style="cursor: pointer" title="${item.descripcion}">${item.descripcion.substring(0,25)}...</p>
                        </div>
                        <div>
                            <button class="btn btn-danger btn-xs" onclick="eliminar_detalle(${item.rowid})"><i class="fa fa-trash"></i></button>
                       

                            <button class="btn btn-${clase_color} btn-xs" onclick="cambiar_visualizacion(${item.rowid},${cambio_valor})">${icono}</button>

                        </div>
                    </div>
                </div>
            </div>`;

                content += template;
            });


            $('#funnel-order').append(content);
        });

    }

    //vamos a cambiar la visualizacion si se vera o no se vera en el funnel
    function cambiar_visualizacion(id,valor)
    {   
        event.preventDefault();
        let fk_funnel = $('[name="rowid"]').val();
        let texto_confirmacion = '';
        if(parseInt(valor) === 0)
        {
            texto_confirmacion = '¿Desea ocultarlo en el funnel?';
        }else{
            texto_confirmacion = '¿Desea visualizarlo en el funnel?';
        }

        result = confirm(texto_confirmacion);
        if(result)
        {

            //actualiazcion ajax aqui
            $.ajax({
                    type: 'POST',
                    url: '<?php echo ENLACE_WEB; ?>mod_funnel/class/funnel.class.php',
                    data: {
                        action: 'cambiar_visualizacion_detalle',
                        rowid: id,
                        fk_funnel: fk_funnel,
                        canvan_mostrar_como_columna:valor
                    },
                    success: function(data) {
                        console.log(data);
                        let response = JSON.parse(data);
                        if (response.exito === true) {
                            console.log('dentro de success: ', fk_funnel);
                            obtener_detalles(fk_funnel);
                            add_notification({
                                text: response.mensaje,
                                actionTextColor: '#fff',
                                backgroundColor: '#00ab55',
                                dismissText: 'Cerrar'
                            });
                            // Aquí puedes recargar la tabla o realizar cualquier otra acción necesaria
                        } else {
                            add_notification({
                                text: response.mensaje,
                                pos: 'top-right',
                                actionTextColor: '#fff',
                                backgroundColor: '#e7515a'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });


        }else{
            return false;
        }

    }


    function eliminar_detalle(id) {
        event.preventDefault();
        let fk_funnel = $('[name="rowid"]').val();


        $.ajax({
            type: 'POST',
            url: '<?php echo ENLACE_WEB; ?>mod_funnel/class/funnel.class.php',
            data: {
                action: 'eliminar_detalle',
                rowid: id,
                fk_funnel: fk_funnel
            },
            success: function(data) {
                let response = JSON.parse(data);
                if (response.exito === true) {
                    console.log('dentro de success: ', fk_funnel);
                    obtener_detalles(fk_funnel);

                    add_notification({
                        text: response.mensaje,
                        actionTextColor: '#fff',
                        backgroundColor: '#00ab55',
                        dismissText: 'Cerrar'
                    });


                    // Aquí puedes recargar la tabla o realizar cualquier otra acción necesaria
                } else {
                    add_notification({
                        text: response.mensaje,
                        pos: 'top-right',
                        actionTextColor: '#fff',
                        backgroundColor: '#e7515a'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }

    
    $(document).ready(function() {

        //inicializar plugin dragula
        var drake = dragula([document.getElementById('funnel-order')], {
            direction: 'vertical', // Limita el movimiento a solo vertical
            revertOnSpill: true // Opcional: Devuelve el elemento a su posición original si se suelta fuera del contenedor
        });


        //evento al cambiar de posicion
        drake.on('drop', function(el, target, source, sibling) {
            var items = Array.from(target.children);
            var itemsData = items.map(function(item, index) {
                return {
                    rowid: item.id, // Asumiendo que cada elemento tiene un ID único
                    posicion: index + 1 // La nueva posición basada en el orden actual de los elementos
                };
            });


            let fk_funnel = $('[name="rowid"]').val();
            console.log('Items data: ', itemsData);

            // Realiza la llamada AJAX
            $.ajax({
                type: 'POST',
                url: '<?php echo ENLACE_WEB; ?>mod_funnel/class/funnel.class.php',
                data: {
                    fk_funnel: fk_funnel,
                    itemsData: itemsData,
                    'action': 'actualizar_posiciones'
                },
                success: function(data) {

                    console.log(data);

                    let response = JSON.parse(data);
                    if (response.exito === true) {
                        add_notification({
                            text: response.mensaje,
                            actionTextColor: '#fff',
                            backgroundColor: '#00ab55',
                            dismissText: 'Cerrar'
                        });
                        obtener_detalles(fk_funnel);

                        // Aquí puedes recargar la tabla o realizar cualquier otra acción necesaria
                    } else {
                        add_notification({
                            text: response.mensaje,
                            pos: 'top-right',
                            actionTextColor: '#fff',
                            backgroundColor: '#e7515a'
                        });
                    }

                },
                error: function(error) {
                    console.error(error);
                }
            });
        });



        $('.select2').select2({
            templateResult: formatIcon,
        });

        function formatIcon(icon) {
          var originalOption = icon.element;
          if (!icon.id) {
            return icon.text;
          }
          var iconClass = $(originalOption).data("icon");
          var $icon = $(
            '<span><i class="fa ' + iconClass + '"></i> ' + icon.text + "</span>"
          );
          return $icon;
        }



    });
</script>