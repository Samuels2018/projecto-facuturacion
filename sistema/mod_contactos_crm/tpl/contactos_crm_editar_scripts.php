<script>
    $(document).ready(function() {
        $('#fk_tercero').select2();
    })


    function crear_contacto(event) {
        event.preventDefault();

        error = false;

        // Recoger los valores del formulario usando jQuery
        let nombre = $('[name="nombre"]').val();
        let apellidos = $('[name="apellidos"]').val();
        let pais_c = $('[name="pais_c"]').val();
        let puesto_t = $('[name="puesto_t"]').val();
        let email = $('[name="email"]').val();
        let telefono = $('[name="telefono"]').val();
        let facebook = $('[name="facebook"]').val();
        let linkedin = $('[name="linkedin"]').val();
        let fecha_nacimiento = $('[name="fecha_nacimiento"]').val();
        let extension = $('[name="extension"]').val();
        let whatsapp = $('[name="whatsapp"]').val();
        let instagram = $('[name="instagram"]').val();
        let x_twitter = $('[name="x_twitter"]').val();
        let fk_tercero = $('[name="fk_tercero"]').val();
        let latitude = $('[name="latitude"]').val();
        let longitud = $('[name="longitud"]').val();
        let paginaweb = $('[name="paginaweb"]').val();

        if (nombre     == ''  ){   $('input[name="nombre"]').addClass("input_error");         error=true;  }
        if (apellidos   == ''  ){   $('input[name="apellidos"]').addClass("input_error");       error=true;  }

        if (error){
            add_notification({
                text: 'Faltan Datos Obligatorios',
                actionTextColor: '#fff',
                backgroundColor: '#e7515a' ,
             
            })
            return true;
        }

        $("#crear_contacto_crm").attr('disabled',true);
        $(".msj-ajax-cliente").remove();
        $("#crear_contacto_crm").after('<div style="margin-top:15px;" class="alert alert-success msj-ajax-cliente" role="alert"><i class="fa fa-spinner" aria-hidden="true"></i> Creando Contacto</div>');


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
                longitud: longitud,
                paginaweb:paginaweb
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
                    $(".msj-ajax-cliente").remove();
                    setTimeout(() => {
                        window.location.href = `${ENLACE_WEB}/contactos_crm_editar/${response.id}`;
                    }, 3000);

                } else {
                    add_notification({
                        text: response.mensaje,
                        pos: 'top-right',
                        actionTextColor: '#fff',
                        backgroundColor: '#e7515a',
                        actionText: 'Cerrar'
                    });
                     $("#crear_contacto_crm").attr('disabled',false);
                    $(".msj-ajax-cliente").remove();
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }

    $('#linkedin').on('paste blur', function(event) {
        // Defer the event handler to the end of the stack to get the pasted data
        setTimeout(() => {
            var url = $(this).val();
            var baseLink = 'https://www.linkedin.com/';

            // Verificar si la URL contiene la base de LinkedIn
            if (url.indexOf(baseLink) === 0) {
                // Remover la base y actualizar el valor del campo
                var updatedUrl = url.substring(baseLink.length);
                $(this).val(updatedUrl);
            }
        }, 0);
    });


    function modificar_contacto(event) {
        event.preventDefault();


        error = false;
  
        // Recoger los valores del formulario usando jQuery
        let rowid = $('[name="rowid"]').val();
        let nombre = $('[name="nombre"]').val();
        let apellidos = $('[name="apellidos"]').val();
        let pais_c = $('[name="pais_c"]').val();
        let puesto_t = $('[name="puesto_t"]').val();
        let email = $('[name="email"]').val();
        let telefono = $('[name="telefono"]').val();
        let facebook = $('[name="facebook"]').val();
        let linkedin = $('[name="linkedin"]').val();
        let fecha_nacimiento = $('[name="fecha_nacimiento"]').val();
        let extension = $('[name="extension"]').val();
        let whatsapp = $('[name="whatsapp"]').val();
        let instagram = $('[name="instagram"]').val();
        let x_twitter = $('[name="x_twitter"]').val();
        let fk_tercero = $('[name="fk_tercero"]').val();
        let latitude = $('[name="latitude"]').val();
        let longitud = $('[name="longitud"]').val();
        let paginaweb = $('[name="paginaweb"]').val();


        if (nombre     == ''  ){   $('input[name="nombre"]').addClass("input_error");         error=true;  }
        if (apellidos   == ''  ){   $('input[name="apellidos"]').addClass("input_error");       error=true;  }

        if (error){
            add_notification({
                text: 'Faltan Datos Obligatorios',
                actionTextColor: '#fff',
                backgroundColor: '#e7515a' ,
             
            })
            return true;
        }



        $("#modificar_contacto_crm").attr('disabled',true);
        $(".msj-ajax-cliente").remove();
        $("#modificar_contacto_crm").after('<div style="margin-top:15px;" class="alert alert-success msj-ajax-cliente" role="alert"><i class="fa fa-spinner" aria-hidden="true"></i> Actualizando Contacto</div>');


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
                longitud: longitud,
                paginaweb: paginaweb
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
                    window.location.href = `${ENLACE_WEB}/contactos_crm_editar/${rowid}`;
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
            add_notification({
                text: 'Hubo un error al modificar el contacto.',
                actionTextColor: '#fff',
                backgroundColor: '#e7515a'
            });
               $("#modificar_contacto_crm").attr('disabled',false);
                    $(".msj-ajax-cliente").remove();
        });
    }

    function eliminar_contacto(event) {
        event.preventDefault();

        // Asegúrate de que este campo exista en tu formulario o en el elemento que desencadena la eliminación
        let rowid = $('[name="rowid"]').val();
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
                            window.location.href = `${ENLACE_WEB}/contactos_crm_listado`;
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
                        text: 'Hubo un error al marcar el contacto como eliminado.',
                        actionTextColor: '#fff',
                        backgroundColor: '#e7515a'
                    });
                });

            },
        });
    }

    /* MApa */
    function resetLocation() {
        var defaultLat = 40.4165; // Latitud de San José, Costa Rica
        var defaultLng = -3.70256; // Longitud de San José, Costa Rica
        mymap.setView([defaultLat, defaultLng], 10);
        marker.setLatLng([defaultLat, defaultLng]);
        $("#latitude").val(defaultLat);
        $("#longitud").val(defaultLng);
    }

    $(document).ready(function() {
        
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


        // Añade el control de búsqueda
        var geocoder = L.Control.Geocoder.nominatim();
        L.Control.geocoder({
            geocoder: geocoder,
            defaultMarkGeocode: false
        })
        .on('markgeocode', function(e) {
            var bbox = e.geocode.bbox;
            var poly = L.polygon([
                bbox.getSouthEast(),
                bbox.getNorthEast(),
                bbox.getNorthWest(),
                bbox.getSouthWest()
            ]).addTo(mymap);
            mymap.fitBounds(poly.getBounds());
            
            // Actualiza el marcador con la nueva posición
            var newLatLng = e.geocode.center;
            marker.setLatLng(newLatLng);
            $("#latitude").val(newLatLng.lat);
            $("#longitud").val(newLatLng.lng);
        })
        .addTo(mymap);



    // Escuchamos los eventos de cambio en los campos de latitud y longitud
    $("#latitude, #longitud").on('change', function() {
        if ($("#latitude").val() === '' || $("#longitud").val() === '') {
            resetLocation();
        }
    });

       /* L.Control.geocoder({
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
                }).addTo(mymap);
                marker.on('dragend', function(event) {
                    var position = marker.getLatLng();
                    console.log(position);
                    //actualizamos la ubicacion en el form
                    $("#latitude").val(position.latsubstring(0,8));
                    $("#longitud").val(position.lngsubstring(0,8));

                });
                console.log(e.geocode.center);
                $("#latitude").val(e.geocode.center.lat.substring(0,8));
                $("#longitud").val(e.geocode.center.lng.substring(0,8));
            })
            .addTo(mymap);*/
    })

</script>