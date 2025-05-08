<script>
    
    jQuery(document).ready(function($)
    {

        //Seleccionar Población / Comunidad Autonoma
        $("#poblacion").change(function(){
            $poblacion = $(this).val();

                $.ajax({
                    method: "POST",
                    url: '<?=ENLACE_WEB_CUENTAS?>/mod_kit_digital/class/clases.php',
                    beforeSend: function(xhr) {
                    },
                    data: {
                        "action"        : "BuscarProvincias"         ,
                        fk_comunidad_autonoma   : $poblacion,
                    },
                }).done(function(data) {
                        console.log(data);
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
    $('#direccion_fk_provincia').on('change', function()
    {   

        $fk_provincia = $(this).val();
        $.ajax({
                   method: "POST",
                   url: '<?=ENLACE_WEB_CUENTAS?>/mod_kit_digital/class/clases.php',
                   beforeSend: function(xhr) {
                   },
                   data: {
                       "action"        : "BuscarMunicipios"         ,
                       fk_provincia   : $fk_provincia,
                   },
               }).done(function(data) {
                    console.log(data);
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


$('#formulario_informacion_basica').on('submit', function(event) {
    event.preventDefault();
    var error = false;

    // Retrieve field values
    var nombre = $("#persona_nombre").val();
    var poblacion = $("#poblacion").val();
    var direccionProvincia = $("#direccion_fk_provincia").val();
    var direccionMunicipio = $("#direccion_fk_municipio").val();
    var telefonoMovil = $("#telefono_movil").val();
    var fiche = $("#fiche").val(); // Value to determine create or update
    var tipo = $("#tipo").val(); // New field for 'Tipo'
    var cedula = $("#cedula").val(); // New field for 'Cedula'
    var notas_empresa = $("#notas_empresa").val();

    // Determine action based on fiche value
    var action = fiche ? 'actualizarEmpresa' : 'crearEmpresa';

    // Validations
    if (nombre === '') {
        $("#persona_nombre").addClass("input_error");
        error = true;
    } else {
        $("#persona_nombre").removeClass("input_error");
    }

    if (poblacion === '') {
        $("#poblacion").addClass("input_error");
        error = true;
    } else {
        $("#poblacion").removeClass("input_error");
    }

    if (direccionProvincia === '') {
        $("#direccion_fk_provincia").addClass("input_error");
        error = true;
    } else {
        $("#direccion_fk_provincia").removeClass("input_error");
    }

    if (direccionMunicipio === '') {
        $("#direccion_fk_municipio").addClass("input_error");
        error = true;
    } else {
        $("#direccion_fk_municipio").removeClass("input_error");
    }

    if (telefonoMovil === '') {
        $("#telefono_movil").addClass("input_error");
        error = true;
    } else {
        $("#telefono_movil").removeClass("input_error");
    }

    // New validations for 'Tipo' and 'Cedula'
    if (tipo === '') {
        $("#tipo").addClass("input_error");
        error = true;
    } else {
        $("#tipo").removeClass("input_error");
    }

    if (cedula === '') {
        $("#cedula").addClass("input_error");
        error = true;
    } else {
        $("#cedula").removeClass("input_error");
    }

    // If there are errors, notify and prevent submission
    if (error) {
        add_notification({
            text: 'Corrige los datos faltantes para continuar',
            actionTextColor: '#fff',
            backgroundColor: '#FF0000',
            dismissText: 'Cerrar'
        });
        return false;
    }

    // AJAX submission if no errors
    var formData = $(this).serialize() + '&action=' + action + '&tipo=' + tipo + '&cedula=' + cedula;
    $.ajax({
        type: 'POST',
        url: '<?=ENLACE_WEB_CUENTAS?>/mod_kit_digital/class/clases.php',
        data: formData,
        success: function(response) {
            console.log(response);
            try {
                var data = $.parseJSON(response);
                $(".msj-ajax").remove();

                // Display server-provided message in the notification
                add_notification({
                    text: data.mensaje || 'Operación completada',
                    actionTextColor: '#fff',
                    backgroundColor: data.exito === 1 ? '#00ab55' : '#e7515a',
                    dismissText: 'Cerrar'
                });

                // Redirect if operation is successful and action is 'crearEmpresa'
                if (data.exito === 1 && action === 'crearEmpresa') {
                    setTimeout(() => {
                        window.location.href = "<?php echo ENLACE_WEB_CUENTAS; ?>editar_kit_digital/" + data.id;
                    }, 3000);
                }
            } catch (e) {
                console.error("Error parsing JSON response:", e);
                add_notification({
                    text: 'Respuesta inesperada del servidor. Intente nuevamente.',
                    actionTextColor: '#fff',
                    backgroundColor: '#e7515a',
                    dismissText: 'Cerrar'
                });
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX error:", error);
            var errorMessage = xhr.responseJSON && xhr.responseJSON.mensaje ? xhr.responseJSON.mensaje : 'Error en la solicitud - Intente nuevamente';
            add_notification({
                text: errorMessage,
                actionTextColor: '#fff',
                backgroundColor: '#e7515a',
                dismissText: 'Cerrar'
            });
        }
    });
});




 // Handle form submission
 // Handle form submission
$('#formulario_kitdigital').on('submit', function(event) {
    event.preventDefault();
    var error = false;



    // Retrieve field values
    var aplicaKitDigital = $('#kit_aplica_kit_digital').is(':checked') ? 1 : 0;
    var tipoKitDigital = $('#kit_fk_tipo').val();
    var fk_kit_digital_estado = $('#fk_kit_digital_estado').val();
    var pdfFirmado = $('#kit_pdf_firmado').is(':checked') ? 1 : 0;
    var vendedorUsuario = $('#vendedor_fk_usuario').val();
    var pdfFirmadoUrl = $('#kit_pdf_firmado_url_en_disco').val();
    var fiche = $('#fiche_kit').val();
    var kit_monto_aprobado = $("#kit_monto_aprobado").val();
    var url_disco = '<?php echo $datos_empresa['kit_pdf_firmado_url_en_disco']; ?>';

    // Validations
    if (aplicaKitDigital && tipoKitDigital === '') {
        $('#kit_fk_tipo').addClass('input_error');
        error = true;
    } else {
        $('#kit_fk_tipo').removeClass('input_error');
    }

    if (aplicaKitDigital && kit_monto_aprobado === '') {
        $('#kit_monto_aprobado').addClass('input_error');
        error = true;
    } else {
        $('#kit_monto_aprobado').removeClass('input_error');
    }

    

    if (pdfFirmado && pdfFirmadoUrl === '') {
        if(url_disco=='')
        {
            $('#kit_pdf_firmado_url_en_disco').addClass('input_error');
            error = true;
        }
    } else {
        $('#kit_pdf_firmado_url_en_disco').removeClass('input_error');
    }

    // If there are errors, notify and prevent submission
    if (error) {
        add_notification({
            text: 'Corrige los datos faltantes para continuar',
            actionTextColor: '#fff',
            backgroundColor: '#FF0000',
            dismissText: 'Cerrar'
        });
        return false;
    }

    // Create form data object for AJAX submission
    var formData = new FormData(this);

    // Append additional form fields manually
    formData.append('action', 'guardarKitDigital');
    formData.append('kit_aplica_kit_digital', aplicaKitDigital);
    formData.append('kit_fk_tipo', tipoKitDigital);
    formData.append('kit_pdf_firmado', pdfFirmado);
    formData.append('vendedor_fk_usuario', vendedorUsuario);
    formData.append('kit_monto_aprobado', kit_monto_aprobado);
    formData.append('url_disco', url_disco);
    formData.append('fk_kit_digital_estado', fk_kit_digital_estado);
    formData.append('fiche', fiche);


    $.ajax({
        type: 'POST',
        url: '<?=ENLACE_WEB_CUENTAS?>/mod_kit_digital/class/clases.php',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            console.log(response);

            try {
                var data = $.parseJSON(response);
                $(".msj-ajax").remove();

                // Display server-provided message in the notification
                add_notification({
                    text: data.mensaje || 'Operación completada',
                    actionTextColor: '#fff',
                    backgroundColor: data.exito === 1 ? '#00ab55' : '#e7515a',
                    dismissText: 'Cerrar'
                });

                // Redirect if needed
                if (data.exito === 1) {
                    setTimeout(() => {
                        window.location.href = "<?php echo ENLACE_WEB_CUENTAS; ?>editar_kit_digital/" + data.id;
                    }, 3000);
                }
            } catch (e) {
                console.error("Error parsing JSON response:", e);
                add_notification({
                    text: 'Respuesta inesperada del servidor. Intente nuevamente.',
                    actionTextColor: '#fff',
                    backgroundColor: '#e7515a',
                    dismissText: 'Cerrar'
                });
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX error:", error);
            var errorMessage = xhr.responseJSON && xhr.responseJSON.mensaje ? xhr.responseJSON.mensaje : 'Error en la solicitud - Intente nuevamente';
            add_notification({
                text: errorMessage,
                actionTextColor: '#fff',
                backgroundColor: '#e7515a',
                dismissText: 'Cerrar'
            });
        }
    });
});



// Handle form submission for Comisiones tab
$('#formulario_comisiones').on('submit', function(event) {
        event.preventDefault();

        // Create form data object
        var formData = $(this).serialize();
        formData += '&action=guardarComisiones';

        $.ajax({
            type: 'POST',
            url: '<?=ENLACE_WEB_CUENTAS?>/mod_kit_digital/class/clases.php',
            data: formData,
            success: function(response) {
                try {
                    var data = $.parseJSON(response);
                    $(".msj-ajax").remove();

                    // Display server-provided message in the notification
                    add_notification({
                        text: data.mensaje || 'Operación completada',
                        actionTextColor: '#fff',
                        backgroundColor: data.exito === 1 ? '#00ab55' : '#e7515a',
                        dismissText: 'Cerrar'
                    });

                } catch (e) {
                    console.error("Error parsing JSON response:", e);
                    add_notification({
                        text: 'Respuesta inesperada del servidor. Intente nuevamente.',
                        actionTextColor: '#fff',
                        backgroundColor: '#e7515a',
                        dismissText: 'Cerrar'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX error:", error);
                var errorMessage = xhr.responseJSON && xhr.responseJSON.mensaje ? xhr.responseJSON.mensaje : 'Error en la solicitud - Intente nuevamente';
                add_notification({
                    text: errorMessage,
                    actionTextColor: '#fff',
                    backgroundColor: '#e7515a',
                    dismissText: 'Cerrar'
                });
            }
        });
    });



});


</script>


