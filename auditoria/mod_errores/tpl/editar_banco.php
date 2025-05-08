<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

include ENLACE_SERVIDOR . 'mod_bancos/object/bancos.object.php';
$obj = new banco($dbh);

$disabled = 'disabled="disabled"';
$texto_informativo = "Nuevo Banco ";
$editar = false;

if (!empty($_REQUEST['fiche'])) {
    $obj->fetch($_REQUEST['fiche']);
    if ($obj->entidad != $_SESSION['Entidad']) {
        //  echo acceso_invalido();
        //  exit(1);
    }

 }

if (!empty($_POST) and (!empty($_REQUEST['fiche'])) and ($_POST['editar'] == "true")) {
} else if (!empty($_REQUEST['fiche']) and $_REQUEST['action'] == "modificar") {
    $disabled = "";
    $obj->fetch($_REQUEST['fiche']);
    $editar = true;
} else if (!empty($_REQUEST['fiche'])) {
    $obj->fetch($_REQUEST['fiche']);
   // $texto_informativo = $cliente->nombre;
} else {
    // supongo que simplemente estoy creando esto!
    $disabled = "";
}



?>
<div class="middle-content container-xxl p-0">

    <!-- BREADCRUMB -->
    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo ENLACE_WEB; ?>bancos_listado">Bancos</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo !empty($_REQUEST['fiche']) ? 'Editar' : 'Nuevo' ?></li>
            </ol>
        </nav>
    </div>
    <!-- /BREADCRUMB -->

    <!-- CONTENT AREA -->
    <div class="row layout-top-spacing">

        <div class="col-md-12">
            <!-- Contenido -->
            <section class="content">
                <div>
                    <form role="form" method="POST" action="" id="formulario">
                        <input type="hidden" name="fiche" value="<?php echo $_REQUEST['fiche'] ?>">
                        <input type="hidden" name="correo_existe" id="correo_existe">
                        <div class="simple-pill">
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade  <?php echo $_GET['tab'] != 'stock' ? 'show active' : '' ?>" id="pills-producto" role="tabpanel" aria-labelledby="pills-producto-tab" tabindex="0">
                                    <div class="row">
                                        <!-- left column -->
                                        <div class="col-md-8">
                                            <!-- general form elements -->
                                            <div class="card">

                                                <div class="card-body">
                                                        <iframe id="cb" style="display:none"></iframe>
                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <label for="ref"><i class="fa fa-fw fa-asterisk"></i> Nombre</label>
                                                                <input required="required" placeholder="nombre_banco" type="text" name="nombre_banco" id="nombre_banco" class="form-control" value="<?php echo $obj->nombre_banco; ?>" <?php echo $disabled; ?>>
                                                            </div>
                                                        </div>

                                                <div class="card-footer mt-12">
                                                    <?php
                                                    if (empty($_REQUEST['fiche'])) { ?>
                                                        <a href="<?php echo ENLACE_WEB; ?>bancos_listado" class="btn btn-primary"><i class="fa fa-fw fa-circle"></i>Cancelar</a>
                                                        <button type="button" class="btn btn-primary" onclick="crearBanco(event)"><i class="fa fa-fw fa-circle"></i>Crear Banco </button>
                                                    <?php }

                                                    if (!empty($_REQUEST['fiche']) and $_GET['action'] !== "modificar") { ?>
                                                        <a href="<?php echo ENLACE_WEB; ?>bancos_listado" class="btn btn-outline-primary">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-box">
                                                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                                                <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                                                <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                                            </svg>
                                                            Volver al Listado
                                                        </a>    


                                                        <?php 
                                                            if(intval($obj->activo) === 1){
                                                        ?>

                                                        <a href="#" onclick="confirma_eliminar('<?php echo $_REQUEST['fiche']; ?>')" class="btn btn-danger  bs-tooltip " data-bs-placement="left" title="Tooltip on left"><i class="fa fa-fw fa-trash"></i> Desactivar </a>


                                                    <?php } ?>


                                                       <?php 
                                                            if(intval($obj->activo) === 0){
                                                        ?>
                                                        <a href="#" onclick="confirma_activar('<?php echo $_REQUEST['fiche']; ?>')" class="btn btn-success"><i class="fa fa-fw fa-edit"></i> Activar</a>

                                                    <?php } else{ ?>
                                                        <a href="<?php echo ENLACE_WEB; ?>dashboard.php?accion=editar_banco&action=modificar&fiche=<?php echo $_REQUEST['fiche']; ?>" class="btn btn-success"><i class="fa fa-fw fa-edit"></i> Modificar</a>


                                                    <?php } ?>

                                                    <?php } ?>

                                                    <?php if ($_REQUEST['action'] == "modificar" and !empty($_REQUEST['fiche'])) { ?>

                                                        <a href="<?php echo ENLACE_WEB; ?>dashboard.php?accion=editar_banco&fiche=<?php echo $_REQUEST['fiche']; ?>" class="btn btn-outline-primary"><i class="fa fa-fw fa-circle"></i>Cancelar modificación de <?php echo $obj->tipo_texto; ?></a>



                                                        <button type="submit" name="editar" value="true" class="btn btn-primary" onclick="actualizarBanco(event)">
                                                            <i class=" fa fa-fw fa-circle"></i>Guardar Cambios <?php echo $obj->tipo_texto; ?>
                                                        </button>


                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>

                    </form>





                    <?php
                    //INICIO CONDICION PERMISO
                    ?>
                    <div class="col-md-6">
                        <!-- general form elements disabled -->

                    </div><!--/.col (right) -->

                    <?php

                    //FIN CONDICION PERMISO

                    ?>

                </div> <!-- /.row -->


                <!-- Fin tab producto -->
        </div>
        <div class="tab-pane fade" id="pills-clientes" role="tabpanel" aria-labelledby="pills-clientes-tab" tabindex="0">

        </div>
        <div class="tab-pane fade" id="pills-costo" role="tabpanel" aria-labelledby="pills-costo-tab" tabindex="0">

        </div>
        <div class="tab-pane fade" id="pills-imagenes" role="tabpanel" aria-labelledby="pills-imagenes-tab" tabindex="0">

        </div>
        <div class="tab-pane fade <?php echo $_GET['tab'] == 'stock' ? 'show active' : '' ?>" <?php echo $_GET['tab'] == 'stock' ? "type='button'" : '' ?> id="pills-stock" role="tabpanel" aria-labelledby="pills-stock-tab" tabindex="0">

        </div>


    </div>
    </form>
</div>


<!-- ALEXIS SANCHEZ 08.12.20 -->
<!-- SCRIPT -->


<div class="sweet-container  container text-left " id='alerta_hacienda' style='display:none'>
    <div class="sweet-overlay" tabindex="-1" style="opacity: 1.13; display: block;"></div>


    <div class="sweet-alert show-sweet-alert visible vertical-center" style="display: block;top:39%!important;width:954px!important;margin-left:-479px!important;" tabindex="-1">
        <div class="icon error" style="display: none;"><span class="x-mark"><span class="line left"></span>
                <span class="line right"></span></span>
        </div>

        <div class="icon warning" style="display: none;"> <span class="body"></span>
            <span class="dot"></span>
        </div>
        <div class="icon info" style="display: none;"></div>
        <div class="icon success animate" style="display: block;"> <span class="line tip animate-success-tip"></span>
            <span class="line long animate-success-long"></span>
            <div class="placeholder"></div>
            <div class="fix"></div>
        </div>

        <img class="sweet-image" style="display: none;">
        <h2 id="alerta_hacienda_h2">Cat&aacute;logo CABYS </h2>
        <div class="sweet-content" id="alerta_hacienda_txt" style=" height:300px; overflow: auto;"></div>
        <hr class="sweet-spacer" style="display: block;">
        <div id="cabys_cantidad_resultados"></div>

    </div>
</div>
<!-- FIN Contenido -->
</div>
</section>
</div>
</div>
<!-- CONTENT AREA -->

</div>
<!-- SCRIPTS -->
<script src="<?php echo ENLACE_WEB ?>bootstrap/plugins/src/autocomplete/autoComplete.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>



<script>
    $(document).ready(function() {

        // Desactivar todos los elementos del menú
        $(".menu").removeClass('active');
        /*
        $(".productos").addClass('active');
        $(".productos > .submenu").addClass('show');
        $("#productos_nuevo").addClass('active');
        */
    });
</script>
<script type="text/javascript">
    


    function crearBanco(event) {
        event.preventDefault(); 
        
        let error = false;

        // Eliminar la clase de error de los campos antes de validar
        $('#nombre_banco').removeClass("input_error");
     
        // Recoger los valores del formulario usando jQuery
        const nombre_banco = $('#nombre_banco').val();
   
        // Validar que los campos no estén vacíos y añadir la clase de error si es necesario
        if (nombre_banco == '') { 
            $('#nombre_banco').addClass("input_error"); 
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
            url: "<?php echo ENLACE_WEB; ?>mod_bancos/class/clases.php",
            beforeSend: function(xhr) {

            },
            data: {
                action: 'crearBanco',
                nombre_banco: nombre_banco,
            },
        }).done(function(msg) {
        //    console.log("Actualizando");
            console.log(msg);

            var mensaje = jQuery.parseJSON(msg);

            if (mensaje.exito ==1 ) {
                        add_notification({
                                text: 'Banco creado exitosamente',
                                actionTextColor: '#fff',
                                backgroundColor: '#00ab55',
                                dismissText: 'Cerrar'
                         });
                     window.location.href = "<?php echo ENLACE_WEB; ?>bancos_listado/";
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


    function actualizarBanco(event) {
        event.preventDefault();
        
        let error = false;

        // Eliminar la clase de error de los campos antes de validar
        $('#nombre_banco').removeClass("input_error");
     
        // Recoger los valores del formulario usando jQuery
        const nombre_banco = $('#nombre_banco').val();
   
        // Validar que los campos no estén vacíos y añadir la clase de error si es necesario
        if (nombre_banco == '') { 
            $('#nombre_banco').addClass("input_error"); 
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
            url: "<?php echo ENLACE_WEB; ?>mod_bancos/class/clases.php",
            beforeSend: function(xhr) {

            },
            data: {
                action: 'actualizarBanco',
                nombre_banco: nombre_banco,
                id: <?php echo isset($_GET['fiche']) ? $_GET['fiche'] : '0'; ?>,
            },
        }).done(function(msg) {
        //    console.log("Actualizando");
            console.log(msg);

            var mensaje = jQuery.parseJSON(msg);

            if (mensaje.exito ==1 ) {
                        add_notification({
                                text: 'Banco actualizado exitosamente',
                                actionTextColor: '#fff',
                                backgroundColor: '#00ab55',
                                dismissText: 'Cerrar'
                         });
                     window.location.href = "<?php echo ENLACE_WEB; ?>bancos_listado/";
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



     // FUNCTION DELETE INFO PRODUCT
    function confirma_eliminar($id) {
        // Preparar el mensaje para el snackbar
        var message = "Está seguro(a) que desea eliminar el Banco? ";
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
                    url: "<?php echo ENLACE_WEB; ?>mod_bancos/class/clases.php",
                    beforeSend: function(xhr) {},
                    data: {
                        action: 'eliminarBanco',
                        id: $id
                    },
                }).done(function(msg) {
                    console.log(msg);

                    var data = JSON.parse(msg);
                    // VALID RESULT
                    if (data.exito == 1) {
                        add_notification({
                            text: 'Banco Eliminado exitosamente',
                            pos: 'top-right',
                            actionTextColor: '#fff',
                            backgroundColor: '#00ab55'
                        });
                        window.location.href = "<?php echo ENLACE_WEB; ?>bancos_listado/";
                    }
                });
            }
        });
    }


     function confirma_activar($id) {
        // Preparar el mensaje para el snackbar
        var message = "Está seguro(a) que desea activar el banco? ";
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
                    url: "<?php echo ENLACE_WEB; ?>mod_bancos/class/clases.php",
                    beforeSend: function(xhr) {},
                    data: {
                        action: 'activarBanco',
                        id: $id
                    },
                }).done(function(msg) {
                    console.log(msg);

                    var data = JSON.parse(msg);
                    // VALID RESULT
                    if (data.exito == 1) {
                        add_notification({
                            text: 'Banco Activado exitosamente',
                            pos: 'top-right',
                            actionTextColor: '#fff',
                            backgroundColor: '#00ab55'
                        });
                        window.location.href = "<?php echo ENLACE_WEB; ?>bancos_listado/";
                    }
                });
            }
        });
    }

</script>