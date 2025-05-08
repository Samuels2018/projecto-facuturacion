<script>
    function crear_agente(event) {
        event.preventDefault();

        let error = false;

        /* Valida los inputs requeridos */
        const inputTypes = [];
        $('#formAgente input[name][id][value]').each(function(index, element) {
            inputTypes.push({
                name: $(this).attr('id'),
                value: $(this).val(),
                required: ($(this).attr('required') || false)
            })
        });
        $('#formAgente select[name][id]').each(function(index, element) {
            inputTypes.push({
                name: $(this).attr('id'),
                value: $(this).val(),
                required: ($(this).attr('required') || false)
            })
        });
        $('#formAgente textarea[name][id]').each(function(index, element) {
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

        if(!validateEmail('email')){
            error = true
            $('#email').addClass('input_error');
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

        const nombre = $('#nombre').val();
        const fk_tipo_identificacion = $('#fk_tipo_identificacion').val();
        const email = $('#email').val();
        const movil = $('#movil').val();
        const telefono = $('#telefono').val();
        const meta = $('#meta').val();
        const comision = $('#comision').val();
        const cedula = $('#cedula').val();
        const observacion = $('#observacion').val();
        const activo = $('#activo').is(':checked');
        let value_activo;
        if (activo) {
            value_activo = 1;
        } else {
            value_activo = 0;
        }

        // Preparar la petición AJAX
        $.ajax({
            type: 'POST',
            url: '<?php echo ENLACE_WEB; ?>mod_configuracion_agente/class/agentes.class.php',
            data: {
                action: 'nuevo_agente',
                nombre: nombre,
                fk_tipo_identificacion: fk_tipo_identificacion,
                email: email,
                movil: movil,
                telefono: telefono,
                meta: meta,
                comision: comision,
                cedula: cedula,
                observacion: observacion,
                activo:value_activo,
                action: 'nuevo_agente'
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

                    setTimeout(() => {
                        window.location.href = `${ENLACE_WEB}/agentes_listado`;
                    }, 3000);

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
                // Aquí puedes manejar el error, por ejemplo, mostrando un mensaje en la página
            }
        });
    }

    function modificar_agente(event) {
        event.preventDefault();

        // Recoger los valores del formulario usando jQuery
        let rowid = $('[name="fiche"]').val(); // Asegúrate de que este campo exista en tu formulario
        let nombre = $('[name="nombre"]').val();
        let fk_tipo_identificacion = $('[name="fk_tipo_identificacion"]').val();
        let email = $('[name="email"]').val();
        let movil = $('[name="movil"]').val();
        let telefono = $('[name="telefono"]').val();
        let meta = $('[name="meta"]').val();
        let comision = $('[name="comision"]').val();
        let cedula = $('[name="cedula"]').val();
        let observacion = $('[name="observacion"]').val();
        let activo = $('[name="activo"]').is(':checked');
        let value_activo;
        if (activo) {
            value_activo = 1;
        } else {
            value_activo = 0;
        }


        let errorMessage = "Debes completar los siguientes campos: ";
        let fieldsMissing = false;

        let fields = {
            '[name="nombre"]': 'Nombre',
            '[name="email"]': 'Email',
            '[name="pais"]': 'Pais',
            // Añade aquí los demás campos que desees validar
        };

        // Validación de campos
        $.each(fields, function(id, name) {
            if ($(id).val() == '') {
                errorMessage += name + ', ';
                fieldsMissing = true;
            }
        });

        // Repite la validación para los demás campos según sea necesario

        // Elimina la última coma y espacio del mensaje
        if (fieldsMissing) {
            errorMessage = errorMessage.slice(0, -2);
            console.log(errorMessage);
            // Aquí puedes manejar la notificación de error, por ejemplo, mostrando un mensaje en la página
            add_notification({
                text: errorMessage,
                pos: 'top-right',
                actionTextColor: '#fff',
                backgroundColor: '#e7515a',
                actionText: 'Cerrar'
            });
            return false;
        }

        // Elimina la última coma y espacio del mensaje
        if (fieldsMissing) {
            errorMessage = errorMessage.slice(0, -2);
            console.log(errorMessage);
            // Aquí puedes manejar la notificación de error, por ejemplo, mostrando un mensaje en la página
            return false;
        }

        // Preparar la petición AJAX
        $.ajax({
            method: "POST",
            url: '<?php echo ENLACE_WEB; ?>mod_configuracion_agente/class/agentes.class.php', // Asegúrate de que esta URL sea correcta
            data: {
                action: 'modificar_agente',
                rowid: rowid,
                nombre: nombre,
                fk_tipo_identificacion: fk_tipo_identificacion,
                email: email,
                movil: movil,
                telefono: telefono,
                meta: meta,
                comision: comision,
                cedula: cedula,
                observacion: observacion,
                activo:value_activo
            }
        }).done(function(msg) {
            console.log(msg);

            const response = JSON.parse(msg);
            console.log(response)
            if (response.exito === true) {

                add_notification({
                    text: response.mensaje,
                    actionTextColor: '#fff',
                    backgroundColor: '#00ab55',
                    dismissText: 'Cerrar'
                });

                setTimeout(() => {
                    window.location.href = `${ENLACE_WEB}/agentes_editar/${rowid}`;
                }, 3000);

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
            // Aquí puedes manejar el error, por ejemplo, mostrando un mensaje en la página
        });
    }



    function eliminar_agente(event) {
        event.preventDefault();

        let rowid = $('[name="fiche"]').val(); // Asegúrate de que este campo exista en tu formulario
        let message = "¿Deseas eliminar este agente?";
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
                    url: "<?php echo ENLACE_WEB; ?>mod_configuracion_agente/class/agentes.class.php",
                    data: {
                        action: 'eliminar_agente',
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

                        setTimeout(() => {
                            window.location.href = `${ENLACE_WEB}/agentes_listado`;
                        }, 3000);

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

    $(document).ready(function() {
    $('#pais').select2();
});
</script>