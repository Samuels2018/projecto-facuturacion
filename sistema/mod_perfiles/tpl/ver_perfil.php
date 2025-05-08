<?php


$idiomas =  $Lan->idiomas;
$list_provincias = $provincias->provincias;
//diccionario_comunidades_autonomas_provincias
//jalamos la data
$Usuarios->buscar_data_usuario($_SESSION['usuario']);


?>

<link rel="stylesheet" href="https://designreset.com/cork/html/src/assets/css/light/users/account-setting.css">
<link rel="stylesheet" href="https://unpkg.com/filepond@4.31.1/dist/filepond.css">
<link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">



<div class="middle-content container-xxl p-0">

    <!-- BREADCRUMB -->
    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Usuarios</a></li>
                <li class="breadcrumb-item active" aria-current="page">Mi Perfil</li>
            </ol>
        </nav>
    </div>
    <!-- /BREADCRUMB -->

    <div class="account-settings-container layout-top-spacing">

        <div class="account-content">
            <div class="row mb-3">
                <div class="col-md-12">
                    <h2>Mi Perfil</h2>

                    <ul class="nav nav-pills" id="animateLine" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="animated-underline-home-tab" data-bs-toggle="tab" href="#animated-underline-home" role="tab" aria-controls="animated-underline-home" aria-selected="true"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg> Datos acceso</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="animated-underline-profile-tab" data-bs-toggle="tab" href="#animated-underline-profile" role="tab" aria-controls="animated-underline-profile" aria-selected="false" tabindex="-1"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign">
                                    <line x1="12" y1="1" x2="12" y2="23"></line>
                                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                </svg> Correo electronico</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="animated-underline-preferences-tab" data-bs-toggle="tab" href="#animated-underline-preferences" role="tab" aria-controls="animated-underline-preferences" aria-selected="false" tabindex="-1"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>

                                </svg> Preferencias</button>
                        </li>

                    </ul>
                </div>
            </div>

            <div class="tab-content" id="animateLineContent-4">
                <div class="tab-pane fade active show" id="animated-underline-home" role="tabpanel" aria-labelledby="animated-underline-home-tab">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                            <form method="POST" class="section general-info" id="formulario_informacion_basica">
                                <div class="info">
                                    <h6 class="">Información básica</h6>
                                    <div class="row">
                                        <div class="col-lg-11 mx-auto">
                                            <div class="row">
                                                <div class="col-xl-2 col-lg-12 col-md-4">
                                                    <div class="profile-image  mt-4 pe-md-4">

                                                        <div class="img-uploader-content">
                                                            <div class="filepond--root filepond filepond--hopper" data-style-panel-layout="compact circle" data-style-button-remove-item-position="left bottom" data-style-button-process-item-position="right bottom" data-style-load-indicator-position="center bottom" data-style-progress-indicator-position="right bottom" data-style-button-remove-item-align="false" style="height: 120px;">
                                                                <input type="file" class="filepond" name="filepond"> <a class="filepond--credits" aria-hidden="true" href="https://pqina.nl/" target="_blank" rel="noopener noreferrer" style="transform: translateY(120px);">Powered by PQINA</a>

                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="col-xl-10 col-lg-12 col-md-8 mt-md-0 mt-4">
                                                    <div class="form">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="fullName">Nombre</label>
                                                                    <input type="text" class="form-control mb-3" id="nombre_completo_usuario" name="nombre" placeholder="Nombre" value="<?php echo $Usuarios->nombre; ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="profession">Apellidos</label>
                                                                    <input type="text" class="form-control mb-3" id="email_usuario" placeholder="Apellidos" value="<?php echo $Usuarios->apellidos; ?>" name="apellidos">
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="address">Idioma</label>
                                                                    <select name="fk_idioma" id="idioma" class="form-control">
                                                                        <option value="">Seleccionar Idioma</option>
                                                                        <?php
                                                                        foreach ($idiomas as $idioma) {
                                                                        ?>
                                                                            <option <?php if ($Usuarios->fk_idioma === $idioma['rowid']) {
                                                                                        echo 'selected';
                                                                                    } ?> value="<?php echo $idioma['rowid']; ?>"><?php echo $idioma['etiqueta']; ?></option>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <!-- CAMPOS NUEVOS -->
                                                                <div class="col-md-6 mt-3">
                                                                    <div class="form-group">
                                                                        <label for="country"><i class="fa fa-whatsapp" aria-hidden="true" style="font-size:30px;"></i> Whatsapp</label>
                                                                        <input type="text" class="form-control mb-3" id="whatsapp" placeholder="Ingresar número" name="usuario_telefono" value="<?php echo @$Usuarios->usuario_telefono; ?>">
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6 mt-3">
                                                                    <div class="form-group">
                                                                        <label for="country"><i class="fa fa-envelope-o" aria-hidden="true" style="font-size:30px;"></i> Correo Electronico</label>
                                                                        <input type="text" class="form-control mb-3" id="correo_electronico" name="acceso_usuario" placeholder="Ingresar correo" value="<?php echo @$Usuarios->acceso_usuario; ?>">

                                                                        <?php
                                                                        if ($Usuarios->acceso_correo_estado === 'pendiente' && !empty($Usuarios->correo_temporal)) {
                                                                        ?>

                                                                            <div class="form_code"><label>Ingresa el codigo de 6 digitos enviados a <?php echo $Usuarios->correo_temporal; ?></label><input type="text" placeholder="Codigo" id="codigo_6_digitos" class="form-control mb-3">

                                                                                <button type="button" id="verificar_codigo" class="verificar_codigo btn btn-secondary _effect--ripple waves-effect waves-light ">Verificar</button>
                                                                            </div>
                                                                        <?php
                                                                        }
                                                                        ?>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 mt-3" id="seccion_verificar_codigo">
                                                                <?php
                                                                if ($Usuarios->acceso_correo_estado === 'pendiente' && !empty($Usuarios->correo_temporal)) {
                                                                ?>
                                                                    <div class="form-group pendiente_verificar">
                                                                        <label style="margin-top: 40px;" for="address">Pendiente de verificar: <a href="javascript:void(0)" id="reenviar_codigo">Reenviar Codigo</a></label>
                                                                    </div>
                                                                <?php } ?>
                                                            </div>

                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="country"><input type="checkbox" name="hacer_empresa"> Hacer esta empresa por defecto al loguearse</label>
                                                                </div>
                                                            </div>

                                                            <!-- -->

                                                            <div class="col-md-12 mt-1">
                                                                <div class="form-group text-end">
                                                                    <button type="submit" class="btn btn-secondary _effect--ripple waves-effect waves-light">Guardar</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>


                </div>
                <div class="tab-pane fade" id="animated-underline-profile" role="tabpanel" aria-labelledby="animated-underline-profile-tab">
                    <div class="row">
                        <!-- COLOCAREMOS EL FUNCIONAMIENTO AQUI DEL HOST EMAIL, ETC-->
                        <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                            <form class="section general-info" id="formulario_host">
                                <div class="info">
                                    <h6 class="">Datos del email HOST</h6>
                                    <div class="row">
                                        <div class="col-lg-11 mx-auto">
                                            <div class="row">
                                                <div class="col-xl-2 col-lg-12 col-md-4">
                                                    <div class="profile-image  mt-4 pe-md-4">
                                                        <i class="fa fa-paper-plane" aria-hidden="true" style="font-size: 6em; color:#536FEE;"></i>
                                                    </div>
                                                </div>
                                                <div class="col-xl-10 col-lg-12 col-md-8 mt-md-0 mt-4">
                                                    <div class="form">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="fullName">Email Host SMTP</label>
                                                                    <input type="text" class="form-control mb-3" id="email_host" name="email_host" placeholder="" value="<?php echo $Usuarios->email_host; ?>">
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="profession">Email Port</label>
                                                                    <input type="text" class="form-control mb-3" id="email_port" name="email_port" placeholder="" value="<?php echo $Usuarios->email_port; ?>">

                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="country">Email Host UserName</label>
                                                                    <input type="text" class="form-control mb-3" id="email_user_name" name="email_user_name" placeholder="" value="<?php echo $Usuarios->email_user_name; ?>">

                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="address">Email Host Password</label>
                                                                    <input type="password" class="form-control mb-3" id="email_password" name="email_password" placeholder="" value="<?php echo $Usuarios->email_password; ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mt-1">
                                                                <div class="form-group text-end">
                                                                    <button id="probar_smtp" type="button" class="btn btn-secondary _effect--ripple waves-effect waves-light">Probar</button>

                                                                    <button type="submit" class="btn btn-secondary _effect--ripple waves-effect waves-light">Guardar</button>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>




                <div class="tab-pane fade" id="animated-underline-preferences" role="tabpanel" aria-labelledby="animated-underline-preferences-tab">
                    <div class="row">
                        <div class="col-xl-6 col-lg-12 col-md-12 layout-spacing">
                            <div class="section general-info">
                                <div class="info">
                                    <h6 class="">Escoge un tema</h6>
                                    <div class="d-sm-flex justify-content-around">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1" checked="">
                                            <label class="form-check-label" for="flexRadioDefault1">
                                                <img class="ms-3" width="100" height="68" alt="settings-dark" src="https://designreset.com/cork/html/src/assets/img/settings-light.svg">
                                            </label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2">
                                            <label class="form-check-label" for="flexRadioDefault2">
                                                <img class="ms-3" width="100" height="68" alt="settings-light" src="https://designreset.com/cork/html/src/assets/img/settings-dark.svg">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- <div class="col-xl-6 col-lg-12 col-md-12 layout-spacing">
                            <div class="section general-info">
                                <div class="info">
                                    <h6 class="">Descargar Respaldo</h6>
                                    <p>Descargar toda tu información</p>
                                    <div class="form-group mt-4">
                                        <button class="btn btn-primary _effect--ripple waves-effect waves-light">Descargar Data</button>
                                    </div>
                                </div>
                            </div>
                        </div> -->

                        <div class="col-xl-4 col-lg-12 col-md-12 layout-spacing">
                            <div class="section general-info">
                                <div class="info">
                                    <h6 class="">Inteligencia Artificial</h6>
                                    <p>Permitir <span class="text-success">Alicia</span> la facturación para esta empresa desde whatsapp mediante Inteligencia Artificial</p>
                                    <div class="form-group mt-4">
                                        <div class="switch form-switch-custom switch-inline form-switch-secondary mt-1">
                                            <input class="switch-input" type="checkbox" role="switch" id="publicProfile" checked="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="animated-underline-contact" role="tabpanel" aria-labelledby="animated-underline-contact-tab">
                    <div class="alert alert-arrow-right alert-icon-right alert-light-warning alert-dismissible fade show mb-4" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-circle">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12" y2="16"></line>
                        </svg>
                        <strong>Warning!</strong> Please proceed with caution. For any assistance - <a href="javascript:void(0);">Contact Us</a>
                    </div>

                    <div class="row">
                        <div class="col-xl-4 col-lg-12 col-md-12 layout-spacing">
                            <div class="section general-info">
                                <div class="info">
                                    <h6 class="">Purge Cache</h6>
                                    <p>Remove the active resource from the cache without waiting for the predetermined cache expiry time.</p>
                                    <div class="form-group mt-4">
                                        <button class="btn btn-secondary btn-clear-purge _effect--ripple waves-effect waves-light">Clear</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-lg-12 col-md-12 layout-spacing">
                            <div class="section general-info">
                                <div class="info">
                                    <h6 class="">Deactivate Account</h6>
                                    <p>You will not be able to receive messages, notifications for up to 24 hours.</p>
                                    <div class="form-group mt-4">
                                        <div class="switch form-switch-custom switch-inline form-switch-success mt-1">
                                            <input class="switch-input" type="checkbox" role="switch" id="socialformprofile-custom-switch-success">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-lg-12 col-md-12 layout-spacing">
                            <div class="section general-info">
                                <div class="info">
                                    <h6 class="">Delete Account</h6>
                                    <p>Once you delete the account, there is no going back. Please be certain.</p>
                                    <div class="form-group mt-4">
                                        <button class="btn btn-danger btn-delete-account _effect--ripple waves-effect waves-light">Delete my account</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/filepond/dist/filepond.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
<script>
    $(document).ready(function() {
        var contador = 0;
        // Registramos FilePond y el plugin de vista previa
        FilePond.registerPlugin(FilePondPluginImagePreview);
        var user_file = '<?php echo $Usuarios->obtener_url_avatar_encriptada($_SESSION['usuario']); ?>';
        // Obtenemos una referencia al input file
        const inputElement = document.querySelector('input[type="file"]');
        // Creamos una instancia de FilePond
        const pond = FilePond.create(inputElement);
        //Automatico
        pond.addFile(user_file);
        // Configuramos FilePond
        // Configuramos FilePond
        pond.setOptions({
            server: {
                url: '<?= ENLACE_WEB ?>/mod_perfiles/class/clases.php',
                method: 'POST',
                process: {
                    ondata: (formData) => {
                        formData.append('action', 'actualizarAvatar');
                        formData.append('fk_usuario', <?= $_SESSION['usuario'] ?>);
                        var fileInput = document.querySelector('input[type="file"]');
                        formData.append('filepond', fileInput.files[0]); // Asegúrate de que este es el campo correcto
                        return formData;

                    }
                }
            },
            // Callback cuando un archivo se ha cargado correctamente
            onprocessfile: (error, file) => {
                if (error) {
                    console.error('Error al cargar el archivo:', error);
                    return;
                }

                // $respuesta_servidor = parseJSON(file.serverId);

                var jsonObject = JSON.parse(file.serverId);

                //console.log(jsonObject);
                console.log('Archivo cargado exitosamente', file);
                console.log('Respuesta del servidor:', file.serverId);

                contador++;
                if (contador > 1) {

                    if (jsonObject.status === 'error') {
                        add_notification({
                            text: jsonObject.message,
                            actionTextColor: '#fff',
                            backgroundColor: '#e7515a'
                        });
                    } else {
                        const url_encriptada = jsonObject.url_encriptada;
                        $('.avatar img').prop('src', url_encriptada);
                        add_notification({
                            text: 'Imagen actualizada exitosamente',
                            actionTextColor: '#fff',
                            backgroundColor: '#00ab55',
                            dismissText: 'Cerrar'
                        });

                        $(".filepond--action-revert-item-processing").trigger("click");
                    }
                }
            },
            // Callback para manejar errores
            onerror: (error, file, status) => {
                console.error('Error durante la carga:', error, status);
                console.error('Archivo afectado:', file);
            }
        });
        pond.on('removefile', (error, file) => {
            $.ajax({
                type: 'POST',
                url: '<?= ENLACE_WEB ?>/mod_perfiles/class/clases.php',
                data: {
                    action: 'limpiarAvatar'
                },
                success: function(response) {
                    const user_response = JSON.parse(response);
                    if(user_response.status == 'success'){
                        const url_encriptada = user_response.url_encriptada;
                        $('.avatar img').prop('src', url_encriptada);
                        add_notification({
                            text: 'Imagen eliminada exitosamente',
                            actionTextColor: '#fff',
                            backgroundColor: '#00ab55',
                            dismissText: 'Cerrar'
                        });
                    }else{
                        add_notification({
                            text: 'Errores en la eliminación de la imagen',
                            actionTextColor: '#fff',
                            backgroundColor: '#e7515a'
                        });    
                    }
                },
                error: function(xhr, status, error) {
                    add_notification({
                        text: 'Errores en la eliminación de la imagen',
                        actionTextColor: '#fff',
                        backgroundColor: '#e7515a'
                    });
                }
            });
        })
        //==============

    });
</script>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        //formulario_informacion_basica
        $('#formulario_informacion_basica').on('submit', function(event) {
            $(".msj-ajax").remove();
            $(this).after("<div class='mt-3 alert alert-info msj-ajax'>Actualizando Información...</div>");

            event.preventDefault();
            var formData = $(this).serialize() + '&fk_usuario=<?= $_SESSION['usuario'] ?>&action=actualizarInformacionBasica';
            // Enviamos los datos al servidor utilizando AJAX
            $.ajax({
                type: 'POST',
                action: 'actualizarInformacionBasica',
                url: '<?= ENLACE_WEB ?>/mod_perfiles/class/clases.php',
                data: formData,
                success: function(response) {
                    console.log(response);
                    var data = $.parseJSON(response);
                    $(".msj-ajax").remove();
                    if (data.exito === true) {
                        add_notification({
                            text: data.mensaje,
                            actionTextColor: '#fff',
                            backgroundColor: '#00ab55',
                            dismissText: 'Cerrar'
                        });
                        //Esto es para el correo electronico
                        if (data.correo_cambio) {
                            //Crearemos el campo para correo temporal 
                            if (data.correo_estatus === 'sucess') {
                                //añadiremos un campo adicional debajo de correo el cual me permitira colocar el codigo de 6 digitos 
                                $(".form_code").remove();
                                $("#correo_electronico").after('<div class="form_code"><label>Ingresa el codigo de 6 digitos enviados a ' + data.correo_nuevo + '</label><input type="text" class="form-control mb-3" placeholder="Codigo" id="codigo_6_digitos"><button type="button" id="verificar_codigo" class="verificar_codigo btn btn-secondary _effect--ripple waves-effect waves-light">Verificar</button></div>');

                                //colocamos tambien la seccion html de reenviar codigo
                                $("#seccion_verificar_codigo").append('<div class="form-group pendiente_verificar"><label style="margin-top: 40px;" for="address">Pendiente de verificar: <a href="javascript:void(0)" id="reenviar_codigo">Reenviar Codigo</a></label>');
                            }
                        }
                    } else {
                        add_notification({
                            text: data.mensaje,
                            actionTextColor: '#fff',
                            backgroundColor: '#e7515a'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    // Manejamos los errores de la solicitud AJAX
                    console.error(error);
                    // Puedes mostrar un mensaje de error al usuario
                }
            });
        });


        //Creamos el ajax para la verificacion de codigo
        $(document).on("click", "#verificar_codigo", function() {
            var formData = '&codigo_6_digitos=' + $("#codigo_6_digitos").val() + '&fk_usuario=<?= $_SESSION['usuario'] ?>&action=verificar_codigo';

            $(".msj-ajax").remove();
            $(this).after("<div class='mt-3 alert alert-info msj-ajax'>Verificando codigo...</div>");

            // Enviamos los datos al servidor utilizando AJAX
            $.ajax({
                type: 'POST',
                url: '<?= ENLACE_WEB ?>/mod_perfiles/class/clases.php',
                data: formData,
                success: function(response) {
                    console.log(response);
                    var data = $.parseJSON(response);
                    $(".msj-ajax").remove();
                    if (data.exito === true) {
                        add_notification({
                            text: data.mensaje,
                            actionTextColor: '#fff',
                            backgroundColor: '#00ab55',
                            dismissText: 'Cerrar'
                        });
                        //removemos el recuadro de verificacion de codigo
                        $(".form_code").remove();
                        $(".pendiente_verificar").remove();
                        //actualizamos el correo en el front end
                        $("#correo_electronico").val(data.correo_nuevo).attr("value", data.correo_nuevo);
                    } else {
                        add_notification({
                            text: data.mensaje,
                            actionTextColor: '#fff',
                            backgroundColor: '#e7515a'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    // Manejamos los errores de la solicitud AJAX
                    console.error(error);
                    // Puedes mostrar un
                }
            });
        }); //=======================
        //========================================================================



        //==========================
        $(document).on("click", "#reenviar_codigo", function() {
            $(".form_code").remove();
            $(".msj-ajax").remove();
            $("#correo_electronico").after("<div class='mt-3 alert alert-info msj-ajax'>Reenviando codigo...</div>");

            var formData = $(this).serialize() + '&fk_usuario=<?= $_SESSION['usuario'] ?>&action=reenviarCodigoEmail';
            // Enviamos los datos al servidor utilizando AJAX
            $.ajax({
                type: 'POST',
                url: '<?= ENLACE_WEB ?>/mod_perfiles/class/clases.php',
                data: formData,
                success: function(response) {
                    $(".msj-ajax").remove();
                    var data = $.parseJSON(response);
                    $(".msj-ajax").remove();
                    if (data.correo === 'sucess') {
                        add_notification({
                            text: data.correo_mensaje,
                            actionTextColor: '#fff',
                            backgroundColor: '#00ab55',
                            dismissText: 'Cerrar'
                        });

                        //colocamos nuevamente el formulario
                        $("#correo_electronico").after('<div class="form_code"><label>Ingresa el codigo de 6 digitos enviados a ' + data.correo_nuevo + '</label><input type="text" class="form-control mb-3" placeholder="Codigo" id="codigo_6_digitos"><button type="button" id="verificar_codigo" class="verificar_codigo btn btn-secondary _effect--ripple waves-effect waves-light">Verificar</button></div>');


                    } else {
                        add_notification({
                            text: data.correo_mensaje,
                            actionTextColor: '#fff',
                            backgroundColor: '#e7515a'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    // Manejamos los errores de la solicitud AJAX
                    console.error(error);
                    // Puedes mostrar un
                }
            });

        });


        //funcion para probar smtp de cliente
        $("#probar_smtp").click(function() {

            $("#email_user_name").after('<label>Email de prueba: </label><input type="text" class="form-control mb-3" id="email_user_prueba" name="email_user_prueba" placeholder="" value=""><button id="enviar_email_smtp" type="button" class="btn btn-secondary _effect--ripple waves-effect waves-light">Enviar Email</button>');
        });


        //================FUNCION PARA ENVIAR EL EMAIL DE PRUEBA
        $(document).on("click", "#enviar_email_smtp", function() {
            div_mensaje_ajax($(this), "Enviando mensaje de prueba....");
            var formData = '&email_user_prueba=' + $("#email_user_prueba").val() + '&fk_usuario=<?= $_SESSION['usuario'] ?>&action=EnviarEmailSmtpPrueba';
            $.ajax({
                type: 'POST',
                url: '<?= ENLACE_WEB ?>/mod_perfiles/class/clases.php',
                data: formData,
                success: function(response) {
                    var data = $.parseJSON(response);
                    $(".msj-ajax").remove();
                    if (data.error === false) {
                        add_notification({
                            text: 'Correo enviado exitosamente',
                            actionTextColor: '#fff',
                            backgroundColor: '#00ab55',
                            dismissText: 'Cerrar'
                        });
                    } else {
                        add_notification({
                            text: data.error_txt,
                            actionTextColor: '#fff',
                            backgroundColor: '#e7515a'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    // Manejamos los errores de la solicitud AJAX
                    console.error(error);
                    // Puedes mostrar un
                }

            });
        });

        //============================================FORMULARIO HOST
        $("#formulario_host").on('submit', function(event) {
            event.preventDefault();

            $(".msj-ajax").remove();
            $(this).after("<div class='mt-3 alert alert-info msj-ajax'>Actualizando Información...</div>");
            var formData = $(this).serialize() + '&fk_usuario=<?= $_SESSION['usuario'] ?>&action=actualizarInfoHostEmail';
            // Enviamos los datos al servidor utilizando AJAX
            $.ajax({
                type: 'POST',
                url: '<?= ENLACE_WEB ?>/mod_perfiles/class/clases.php',
                data: formData,
                success: function(response) {
                    $(".msj-ajax").remove();

                    var data = $.parseJSON(response);
                    $(".msj-ajax").remove();
                    if (data.exito === true) {
                        add_notification({
                            text: data.mensaje,
                            actionTextColor: '#fff',
                            backgroundColor: '#00ab55',
                            dismissText: 'Cerrar'
                        });
                    } else {
                        add_notification({
                            text: data.mensaje,
                            actionTextColor: '#fff',
                            backgroundColor: '#e7515a'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    // Manejamos los errores de la solicitud AJAX
                    console.error(error);
                    // Puedes mostrar un
                }
            });

        });

        //para div de cargando
        function div_mensaje_ajax($element, $text) {
            $(".msj-ajax").remove();
            $element.after("<div class='mt-3 alert alert-info msj-ajax'>" + $text + "</div>");
        }


    });
</script>

<script>
    jQuery(document).ready(function($) {
        $("#flexRadioDefault1").on('click', function(event) {
            event.preventDefault()

            $.ajax({
                type: 'POST',
                url: "<?php echo ENLACE_WEB; ?>mod_perfiles/ajax/perfiles.ajax.php",
                data: {
                    action: 'cambiartheme',
                    thema: 'light',
                },
                success: function(response) {
                    console.log(response)
                },
                error: function(xhr, status, error) {
                    // Manejamos los errores de la solicitud AJAX
                    console.error(error);
                    // Puedes mostrar un mensaje de error al usuario
                }
            });

        })

    })
</script>