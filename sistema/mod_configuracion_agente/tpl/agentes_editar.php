<?php
include_once ENLACE_SERVIDOR . 'mod_configuracion_agente/object/agente.object.php';
include_once ENLACE_SERVIDOR . 'mod_utilidad/object/utilidades.object.php';
$agentes = new Agente($dbh);
// var_dump($_REQUEST['fiche']);
$agentes->fetch($_REQUEST['fiche']);
$texto_informativo = $agentes->rowid != '' ? $agentes->nombre : "Nuevo Agente";

$disabled = !empty($_REQUEST['fiche']) ? 'disabled="disabled"' : '';

if ($_REQUEST['fiche'] != '' && $_REQUEST['action'] == 'modify') {
    $disabled = '';
}

$agentes->obtenerDirecciones($_REQUEST['fiche']);

$Utilidades = new Utilidades($dbh);
$paises = $Utilidades->obtener_paises();
$lista_identificacion_fiscal = $Utilidades->obtener_identificaciones_fiscales();

?>
<div class="middle-content container-xxl p-0">

    <form role="form" method="POST" action="" id="formAgente">
        <input type="hidden" name="fiche" value="<?php echo $_REQUEST['fiche'] ?>">

        <!-- BREADCRUMB -->
        <div class="page-meta">
            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo ENLACE_WEB . 'agentes_listado' ?>">
                            Agentes</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?php echo $agente->rowid != '' ?
                            returnSplitNameClient($texto_informativo) : $texto_informativo ?></li>
                </ol>
            </nav>
        </div>
        <!-- /BREADCRUMB -->


        <div class="row layout-top-spacing">
            <div class="col-xl-9 col-lg-9 col-sm-9 layout-spacing">

                <section class="content">
                    <div class="card">
                        <div class="card-body">
                            <div>
                                <div class="form-group row mt-4">
                                    <div class="col-md-6">
                                        <label for="nombre"><i class="fa fa-fw fa-user"></i> Nombre</label>
                                        <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre del agente" value="<?php echo $agentes->nombre; ?>" <?php echo $disabled; ?> required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email"><i class="fa fa-fw fa-envelope-o"></i> Email</label>
                                        <input type="email" name="email" id="email" class="form-control" placeholder="Correo del agente" value="<?php echo $agentes->email; ?>" <?php echo $disabled; ?> required>
                                    </div>
                                </div>
                                <div class="form-group row mt-4" <?php echo $disabled; ?>>
                                    <div class="col-md-6">
                                        <label for="tipo">
                                            <i class="fa fa-fw fa-paperclip"></i>
                                            Tipo de identificación
                                            <span style="color:red">*</span>
                                        </label>
                                        <select required class="form-control" Onchange="enmascara(this.value)" name="fk_tipo_identificacion" id="fk_tipo_identificacion" <?php echo $disabled; ?> >
                                            <option disabled value="" <?php echo (empty($agentes->fk_tipo_identificacion)) ? 'selected="selected"' : '' ?>>Seleccione... </option>
                                            <?php
                                            foreach ($lista_identificacion_fiscal as $identificacion_fiscal) {
                                                if ($cliente->fk_tipo_identificacion  == $identificacion_fiscal->rowid) {
                                                    $s = 'selected="selected"';
                                                } else {
                                                    $s = "";
                                                }
                                                echo '<option value="' . $identificacion_fiscal->rowid . '" ' . $s . ' >' . $identificacion_fiscal->descripcion . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="cedula"><i class="fa fa-fw fa-id-card"></i> Nro Documento</label>
                                        <input type="text" name="cedula" id="cedula" class="form-control" placeholder="Número de documento" value="<?php echo $agentes->cedula; ?>" <?php echo $disabled; ?>>
                                    </div>
                                </div>
                                <div class="form-group row mt-4" <?php echo $disabled; ?>>
                                    <div class="col-md-6">
                                        <label for="movil"><i class="fa fa-fw fa-phone"></i> Móvil</label>
                                        <input type="text" name="movil" id="movil" class="form-control" placeholder="Móvil de contacto" value="<?php echo $agentes->movil; ?>" <?php echo $disabled; ?> required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="telefono"><i class="fa fa-fw fa-phone"></i> Teléfono</label>
                                        <input type="text" name="telefono" id="telefono" class="form-control" placeholder="Teléfono de contacto" value="<?php echo $agentes->telefono; ?>" <?php echo $disabled; ?>>
                                    </div>
                                </div>
                                <div class="form-group row mt-4" <?php echo $disabled; ?>>
                                    <div class="col-md-6">
                                        <label for="meta"><i class="fa fa-fw fa-money"></i> Meta mensual de ventas</label>
                                        <input type="number" name="meta" id="meta" class="form-control" placeholder="Meta mensual de ventas" value="<?php echo ($agentes->meta>0?$agentes->meta:0); ?>" <?php echo $disabled; ?>>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="comision"><i class="fa fa-fw fa-money"></i> Porcentaje de comisión</label>
                                        <input type="number" name="comision" id="comision" class="form-control" placeholder="Porcentaje de comisión" value="<?php echo ($agentes->comision>0?$agentes->comision:0); ?>" <?php echo $disabled; ?>>
                                    </div>
                                </div>
                                <div class="form-group row mt-4" <?php echo $disabled; ?>>
                                    <div class="col-md-10">
                                        <label for="direccion"><i class="fa fa-fw fa-phone"></i> Dirección</label>
                                        <input id="direccion_fk" name="direccion_fk" type="hidden" value="<?php echo $agentes->direccion_fk; ?>">
                                        <input type="text" name="direccion" id="direccion" class="form-control" placeholder="Registro de dirección" value="<?php echo $agentes->direccion_txt; ?>" disabled>
                                    </div>
                                    <?php if( $_REQUEST['fiche'] != '' ){ ?>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <br>
                                        <button type="button" onclick="ver_direccion()" class="btn btn-success _effect--ripple waves-effect waves-light">
                                            <i class="fa fa-fw fa-map" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                    <?php } ?>
                                </div>

                                
                                <div class="form-group row mt-4" <?php echo $disabled; ?>>
                                    <div class="col-md-12">
                                        <label for="observacion"><i class="fa fa-fw fa-comment"></i> Observación</label>
                                        <textarea name="observacion" id="observacion" class="form-control" placeholder="Observaciones que requieren tener presentes relacionadas con este agente" rows="3" <?php echo $disabled; ?>><?php echo $agentes->observacion; ?></textarea>
                                    </div>
                                </div>

                                    
                                <div class="form-group row mt-4" <?php echo $disabled; ?>>
                                    <div class="col-md-12 d-flex align-items-center">
                                        <label for="country" class="mr-2">¿Activo / Inactivo? </label>
                                        <div class="switch form-switch-custom switch-inline form-switch-primary" style="margin-left:5px;">
                                            <input name="activo" class="switch-input" type="checkbox" <?php if($agentes->activo == 1){ echo 'checked'; } ?> value="<?php echo $agentes->activo; ?>"  role="switch" id="activo" >
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-12">
                                <div class="card-footer mt-4">


                                    <a href="<?php echo ENLACE_WEB; ?>agentes_listado" class="btn btn-outline-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-box">
                                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                            <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                        </svg>
                                        Volver al Listado
                                    </a>


                                    <?php if (!empty($_REQUEST['fiche'])) { ?>
                                        <button type="button" onclick="eliminar_agente(event)" class="btn btn-danger">
                                            <i class="fa fa-fw fa-trash"></i>
                                            Eliminar
                                        </button>

                                    <?php } else { ?>
                                        <a href="<?php echo ENLACE_WEB . 'agentes_listado' ?>" class="btn btn-primary"><i class="fa fa-fw fa-eraser"></i>Cancelar</a>
                                    <?php } ?>




                                    <?php if (empty($_REQUEST['action']) && !empty($_REQUEST['fiche'])) { ?>

                                        <a href="<?php echo ENLACE_WEB . 'agentes_nuevo' ?>" class="btn btn-primary"><i class="fa fa-fw fa-plus"></i>
                                            Generar Otro <?php echo $texto_boton ?>
                                        </a>


                                        <a href="<?php echo ENLACE_WEB; ?>dashboard.php?accion=<?php echo $_GET['accion'] ?>&fiche=<?php echo $_REQUEST['fiche'] ?>&action=modify" class="btn btn-success">
                                            <i class="fa fa-fw fa-edit"></i> Modificar
                                        </a>

                                    <?php } ?>

                                    <?php if (empty($_REQUEST['action']) && empty($_REQUEST['fiche'])) { ?>
                                        <button type="button" onclick="crear_agente(event)" class="btn btn-primary">
                                            <i class="fa fa-fw fa-circle"></i>Crear <?php echo $texto_boton ?></button>
                                    <?php } ?>


                                    <?php if ($_REQUEST['action'] == "modify" && !empty($_REQUEST['fiche'])) { ?>
                                        <button type="button" onclick="modificar_agente(event)" class="btn btn-primary">
                                            <i class="fa fa-fw fa-circle"></i>Guardar Cambios Contacto
                                        </button>
                                    <?php } ?>



                                </div>

                            </div>
                        </div>

                        <div class="modal fade" id="nueva_direccion" tabindex="-1" role="dialog" aria-labelledby="nueva_direccion_label" aria-hidden="true">
                        </div>

                    </div>

                </section>

            </div>
        </div>
    </form>
</div>

<script>
    let marker = null;
    $(document).ready(function() {
        // Desactivar todos los elementos del menú
        $(".menu").removeClass('active');

        $(".configuracion").addClass('active');
        $(".configuracion > .submenu").addClass('show');
        $("#agentes_nuevo").addClass('active');
    });

    function crear_direccion(event) {
        event.preventDefault();

        let error = false;

        /* Valida los inputs requeridos */
        const inputTypes = [];
        $('.modal-dialog input[name][id][value]').each(function(index, element) {
            inputTypes.push({
                name: $(this).attr('id'),
                value: $(this).val(),
                required: ($(this).attr('required') || false)
            })
        });
        $('.modal-dialog select[name][id]').each(function(index, element) {
            inputTypes.push({
                name: $(this).attr('id'),
                value: $(this).val(),
                required: ($(this).attr('required') || false)
            })
        });
        $('.modal-dialog textarea[name][id]').each(function(index, element) {
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
        const data = inputTypes.reduce((acc, item) => {
            acc[item.name] = item.value;
            return acc;
        }, {
            action: 'crear_direccion'
        });
        
        // Preparar la petición AJAX
        $.ajax({
            method: "POST",
            url: "<?php echo ENLACE_WEB; ?>mod_direcciones/ajax/direcciones_ajax.php",
            beforeSend: function(xhr) {

            },
            data: data,
        }).done(function(msg) {
            var mensaje = jQuery.parseJSON(msg);

            if (mensaje.exito == 1) {
                if(mensaje.data>0){ //Dirección agregada
                    $.ajax({
                        method: "POST",
                        url: "<?php echo ENLACE_WEB; ?>mod_direcciones/ajax/direcciones_ajax.php",
                        beforeSend: function(xhr) {
                            // aqui deberia ocurrir una carga
                        },
                        data: {
                            action: 'actualizar_direccion_tipo_entidad',
                            id:mensaje.data,
                            id_tipo_entidad: '<?php echo $_REQUEST['fiche']; ?>'
                        },
                    }).done(function(html) {
                        //print html en el modal cargado
                        add_notification({
                            text: 'Dirección creado exitosamente',
                            actionTextColor: '#fff',
                            backgroundColor: '#00ab55',
                            dismissText: 'Cerrar'
                        });
                        $('#direccion_fk').val(mensaje.data)
                        $('#direccion').val( $('#descripcion').val() )
                        $("#nueva_direccion").modal('hide');
                    });

                }
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

    function actualizar_direccion(int) {

        let error = false;

        /* Valida los inputs requeridos */
        const inputTypes = [];
        $('.modal-dialog input[name][id][value]').each(function(index, element) { inputTypes.push({ name: $(this).attr('id'), value: $(this).val(), required: ($(this).attr('required') || false) }) });
        $('.modal-dialog select[name][id]').each(function(index, element) { inputTypes.push({ name: $(this).attr('id'), value: $(this).val(), required: ($(this).attr('required') || false) }) });
        $('.modal-dialog textarea[name][id]').each(function(index, element) { inputTypes.push({ name: $(this).attr('id'), value: $(this).val(), required: ($(this).attr('required') || false) }) });
        inputTypes.map(x => $('#' + x.name).removeClass('input_error') )
        inputTypes.map((x) => { if (x.required && x.value == '') { $('#' + x.name).addClass('input_error'); error=true; } })
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

        const data = inputTypes.reduce((acc, item) => { acc[item.name] = item.value; return acc; }, { action: 'actualizar_direccion', id: int });

        // Preparar la petición AJAX
        $.ajax({
            method: "POST",
            url: "<?php echo ENLACE_WEB; ?>mod_direcciones/ajax/direcciones_ajax.php",
            beforeSend: function(xhr) {

            },
            data: data,
        }).done(function(msg) {
            var mensaje = JSON.parse(msg);

            if (mensaje.exito == 1) {
                add_notification({
                    text: 'Dirección actualizada exitosamente',
                    actionTextColor: '#fff',
                    backgroundColor: '#00ab55',
                    dismissText: 'Cerrar'
                });
                $('#direccion').val( $('#descripcion').val() )
                $("#nueva_direccion").modal('hide');
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

    function ver_direccion() {
        let direccion_id = $('#direccion_fk').val()
        // Preparar la petición AJAX
        $.ajax({
            method: "POST",
            url: "<?php echo ENLACE_WEB; ?>mod_direcciones/tpl/modal_direcciones.php",
            beforeSend: function(xhr) {
                // aqui deberia ocurrir una carga
            },
            data: {
                action: 'ver_direccion',
                fiche: direccion_id,
                tipo: 3,
                fk_entidad: '<?php echo $agentes->rowid; ?>'
            },
        }).done(function(html) {
            //print html en el modal cargado
            $("#nueva_direccion").html(html).modal('show');
        });
    }
</script>

<?php include ENLACE_SERVIDOR . 'mod_configuracion_agente/tpl/script_editar_agente.php' ?>