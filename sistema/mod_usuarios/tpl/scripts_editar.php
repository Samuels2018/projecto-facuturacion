
<script>
    //----------------------------------------------------------------------------

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



    function crearUsuario(event) {
        event.preventDefault();
        

        let error = false;

        // Eliminar la clase de error de los campos antes de validar
        $('#nombre').removeClass("input_error");
        $('#apellidos').removeClass("input_error");
        $('#fk_idioma').removeClass("input_error");
        $('#fk_provincia').removeClass("input_error");
        $('#usuario_telefono').removeClass("input_error");
        $('#correo').removeClass("input_error");
        $('#acceso_clave').removeClass("input_error");

        // Recoger los valores del formulario usando jQuery
        const nombre = $('#nombre').val();
        const apellidos = $('#apellidos').val();
        const fk_idioma = $('#fk_idioma').val();
        const fk_provincia = $('#fk_provincia').val();
        const usuario_telefono = $('#usuario_telefono').val();
        const acceso_usuario = $('#correo').val();
        const acceso_clave = $('#acceso_clave').val();
        const  correo_existe = $("#correo_existe").val();
        const fk_perfil = $("#fk_perfil").val();

      
        // Validar que los campos no estén vacíos y añadir la clase de error si es necesario
        if (nombre == '') { 
            $('#nombre').addClass("input_error"); 
            error = true;  
        }
        if (apellidos == '') { 
            $('#apellidos').addClass("input_error"); 
            error = true;  
        }
        if (fk_idioma == '') { 
            $('#fk_idioma').addClass("input_error"); 
            error = true;  
        }
        if (fk_provincia == '') { 
            $('#fk_provincia').addClass("input_error"); 
            error = true;  
        }
        if (usuario_telefono == '') { 
            $('#usuario_telefono').addClass("input_error"); 
            error = true;  
        }
            
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (acceso_usuario == '') { 
            $('#correo').addClass("input_error"); 
            error = true;  
        }else if (!emailRegex.test(acceso_usuario))
        {
            $('#correo').addClass("input_error");
            add_notification({
                text: 'Formato de correo electrónico inválido',
                actionTextColor: '#fff',
                backgroundColor: '#e7515a',
            });
            error = true;
            return false;
        }


        if (acceso_clave == '') { 
            $('#acceso_clave').addClass("input_error"); 
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

        if(correo_existe === 'yes')
        {
             add_notification({
                text: 'El correo electrónico ya existe',
                actionTextColor: '#fff',
                backgroundColor: '#e7515a',
            });
            return true;
        }
        
        // Preparar la petición AJAX
        $.ajax({
            method: "POST",
            url: "<?php echo ENLACE_WEB; ?>mod_usuarios/ajax/usuario.ajax.php",
            beforeSend: function(xhr) {

            },
            data: {
                action: 'crearUsuario',
                nombre: $("#nombre").val(),
                apellidos: $("#apellidos").val(),
                acceso_usuario: $("#correo").val(),
                fk_idioma: $("#fk_idioma").val(),
                usuario_telefono: $("#usuario_telefono").val(),
                acceso_clave: $("#acceso_clave").val(),
                fk_perfil:fk_perfil,
            },
        }).done(function(msg) {
        //    console.log("Actualizando");
            console.log(msg);

            var mensaje = jQuery.parseJSON(msg);

            if (mensaje.exito   ==1 ) {
                        add_notification({
                                text: 'Usuario creado exitosamente',
                                actionTextColor: '#fff',
                                backgroundColor: '#00ab55',
                                dismissText: 'Cerrar'
                         });
                     window.location.href = "<?php echo ENLACE_WEB; ?>usuarios_listado/";
            } else {
               
                        add_notification({
                        text: "Error:"+mensaje.mensaje,
                        actionTextColor: '#fff',
                        actionTextColor: '#fff',
                backgroundColor: '#e7515a' ,
                        });
            }
        });
    }


     function validar_correo(event) {
        let email = $('#correo').val(); // Asegúrate de que este selector coincida con tu input de correo electrónico

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
            url: "<?php echo ENLACE_WEB; ?>mod_usuarios/ajax/usuario.ajax.php",
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



    //crud via jax
    function actualizarUsuario(event) {
        event.preventDefault();

        let error = false;

        // Eliminar la clase de error de los campos antes de validar
        $('#nombre').removeClass("input_error");
        $('#apellidos').removeClass("input_error");
        $('#fk_idioma').removeClass("input_error");
        $('#fk_provincia').removeClass("input_error");
        $('#usuario_telefono').removeClass("input_error");
        $('#correo').removeClass("input_error");
        $('#acceso_clave').removeClass("input_error");

        // Recoger los valores del formulario usando jQuery
        const nombre = $('#nombre').val();
        const apellidos = $('#apellidos').val();
        const fk_idioma = $('#fk_idioma').val();
        const fk_provincia = $('#fk_provincia').val();
        const usuario_telefono = $('#usuario_telefono').val();
        const acceso_usuario = $('#correo').val();
        const acceso_clave = $('#acceso_clave').val();
        const correo_existe = $("#correo_existe").val();
        const activo_empresa = $("#activo_empresa").prop('checked')

        // Validar que los campos no estén vacíos y añadir la clase de error si es necesario
        if (nombre == '') { 
            $('#nombre').addClass("input_error"); 
            error = true;  
        }
        if (apellidos == '') { 
            $('#apellidos').addClass("input_error"); 
            error = true;  
        }
        if (fk_idioma == '') { 
            $('#fk_idioma').addClass("input_error"); 
            error = true;  
        }
        if (fk_provincia == '') { 
            $('#fk_provincia').addClass("input_error"); 
            error = true;  
        }
        if (usuario_telefono == '') { 
            $('#usuario_telefono').addClass("input_error"); 
            error = true;  
        }


        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (acceso_usuario == '') { 
            $('#correo').addClass("input_error"); 
            error = true;  
        }else if (!emailRegex.test(acceso_usuario))
        {
            $('#correo').addClass("input_error");
            add_notification({
                text: 'Formato de correo electrónico inválido',
                actionTextColor: '#fff',
                backgroundColor: '#e7515a',
            });
            error = true;
            return false;
        }

        if (acceso_clave == '') { 
            $('#acceso_clave').addClass("input_error"); 
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

        if(correo_existe === 'yes')
        {
             add_notification({
                text: 'El correo electrónico ya existe',
                actionTextColor: '#fff',
                backgroundColor: '#e7515a',
            });
            return true;
        }

            
        const fk_perfil = $("#fk_perfil").val();


        // Preparar la petición AJAX
        $.ajax({
            method: "POST",
            url: "<?php echo ENLACE_WEB; ?>mod_usuarios/ajax/usuario.ajax.php",
            beforeSend: function(xhr) {
            },
            data: {
                action: 'actualizarUsuario',
                id: <?php echo isset($_REQUEST['fiche']) ? $_REQUEST['fiche'] : 0;  ?>,
                nombre: $("#nombre").val(),
                apellidos: $("#apellidos").val(),
                fk_idioma: $("#fk_idioma").val(),
                usuario_telefono: $("#usuario_telefono").val(),
                acceso_usuario: $("#correo").val(),
                acceso_clave: $("#acceso_clave").val(),
                fk_perfil:fk_perfil,
                usuario_editar: 'yes',
                activo_empresa: activo_empresa==true?1:0
            },
        }).done(function(msg) {
        //    console.log("Actualizando");
            console.log(msg);

            var mensaje = jQuery.parseJSON(msg);

            if (mensaje.exito ==1 ) {
                        add_notification({
                                text: mensaje.mensaje,
                                actionTextColor: '#fff',
                                backgroundColor: '#00ab55',
                                dismissText: 'Cerrar'
                         });

                         window.location.href = "<?php echo ENLACE_WEB; ?>usuarios_listado/";

 
            
            } else {
               
                        add_notification({
                        text: "Error:"+mensaje.mensaje,
                        actionTextColor: '#fff',
                        actionTextColor: '#fff',
                backgroundColor: '#e7515a' ,
                        });

            }
            
        });
    }


    //Funcion para activar el usuario nuevamente
    function confirma_activar_usuario($id)
    {

        // Preparar el mensaje para el snackbar
        var message = "Está seguro(a) que desea Activar el usuario? ";
        var actionText = "Confirmar";

        // Mostrar el snackbar y definir el callback para el botón de acción
        add_notification({
            text: message,
            width: 'auto',
            duration: 30000,
            actionText: actionText,
            dismissText: 'Cerrar',
            onActionClick: function(element)
            {
                // Aquí va el código que se ejecutará cuando el usuario confirme
                $.ajax({
                    method: "POST",
                    url: "<?php echo ENLACE_WEB; ?>mod_usuarios/ajax/usuario.ajax.php",
                    beforeSend: function(xhr) {},
                    data: {
                        action: 'activarUsuario',
                        id: $id
                    },
                }).done(function(msg) {
                    console.log(msg);
                    var data = JSON.parse(msg);
                    // VALID RESULT
                    if (data.exito == 1) {
                        add_notification({
                            text: 'Usuario Activado exitosamente',
                            pos: 'top-right',
                            actionTextColor: '#fff',
                            backgroundColor: '#00ab55'
                        });
                        window.location.href = "<?php echo ENLACE_WEB; ?>usuarios_listado/";
                    }
                });
            }
        });

    }


    // FUNCTION DELETE INFO PRODUCT
    function confirma_eliminar_usuario($id) {
        // Preparar el mensaje para el snackbar
        var message = "Está seguro(a) que desea eliminar el usuario? ";
        var actionText = "Confirmar";

        // Mostrar el snackbar y definir el callback para el botón de acción
        add_notification({
            text: message,
            width: 'auto',
            duration: 30000,
            actionText: actionText,
            dismissText: 'Cerrar',
            onActionClick: function(element)
            {
                // Aquí va el código que se ejecutará cuando el usuario confirme
                $.ajax({
                    method: "POST",
                    url: "<?php echo ENLACE_WEB; ?>mod_usuarios/ajax/usuario.ajax.php",
                    beforeSend: function(xhr) {},
                    data: {
                        action: 'eliminarUsuario',
                        id: $id
                    },
                }).done(function(msg) {
                    console.log(msg);

                    var data = JSON.parse(msg);
                    // VALID RESULT
                    if (data.exito == 1) {
                        add_notification({
                            text: 'Usuario Eliminado exitosamente',
                            pos: 'top-right',
                            actionTextColor: '#fff',
                            backgroundColor: '#00ab55'
                        });
                        window.location.href = "<?php echo ENLACE_WEB; ?>usuarios_listado/";
                    }
                });
            }
        });
    }



    $('#fk_perfil').select2({
            placeholder: "Seleccione uno o más perfiles",
            allowClear: true
    });
  
</script>

