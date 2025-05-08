<?php
require_once(ENLACE_SERVIDOR . "mod_usuarios/object/usuarios.object.php");
require_once(ENLACE_SERVIDOR . "mod_idiomas/object/idioma.object.php");
$idioma = new Idioma($dbh_utilidades_Apoyo);

$obj = new usuario($dbh, $_SESSION["Entidad"]);

$disabled = 'disabled="disabled"';
$texto_informativo = "Nuevo Usuario ";
$editar = false;

if (!empty($_REQUEST['fiche'])) {
    $obj->buscar_data_usuario($_REQUEST['fiche']);
    if ($obj->entidad != $_SESSION['Entidad']) {
        //  echo acceso_invalido();
        //  exit(1);
    }
}

if (!empty($_POST) and (!empty($_REQUEST['fiche'])) and ($_POST['editar'] == "true")) {
} else if (!empty($_REQUEST['fiche']) and $_REQUEST['action'] == "modificar") {
    $obj->fetch($_REQUEST['fiche']);
    $disabled = "";
    $obj->buscar_data_usuario($_REQUEST['fiche']);
    $editar = true;
} else if (!empty($_REQUEST['fiche'])) {
    $obj->fetch($_REQUEST['fiche']);
    $obj->buscar_data_usuario($_REQUEST['fiche']);
    $texto_informativo = $cliente->nombre;
} else {
    // supongo que simplemente estoy creando esto!
    $disabled = "";
}
$categorias = null;

//Listar
$perfiles = $obj->listar_perfiles_usuario($_SESSION['Entidad']);
$perfiles_asignados = $obj->listar_perfiles_del_usuario($_GET['fiche']);

// Convertir los perfiles asignados en un array simple para facilitar la verificación
$perfiles_asignados_ids = array_map(function ($perfil_asignado) {
    return $perfil_asignado->fk_usuario_perfil;
}, $perfiles_asignados);

$es_dueno = $_SESSION["usuario"] == $obj->Entidad->usuario_dueno_empresa["rowid"];

?>
<div class="middle-content container-xxl p-0">

    <!-- BREADCRUMB -->
    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo ENLACE_WEB; ?>usuarios_listado">Usuarios</a></li>
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
                            <?php if (!empty($_REQUEST['fiche'])) : ?>
                                <ul class="nav nav-pills mb-3" id="pills-tab-1" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link <?php echo $_GET['tab'] != 'stock' ? 'active' : '' ?>" id="active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-producto" type="button" role="tab" aria-controls="pills-home" aria-selected="<?php echo $_GET['tab'] != 'stock' ? 'true' : 'false' ?>">Ficha usuario</button>
                                    </li>

                                </ul>
                            <?php endif; ?>
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
                                                            <label for="ref"><i class="fa fa-fw fa-user"></i> Nombre</label>
                                                            <input required="required" placeholder="Nombre" type="text" name="nombre" id="nombre" class="form-control" value="<?php echo $obj->nombre; ?>" <?php echo $disabled; ?>>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="label"><i class="fa fa-fw fa-user"></i> Apellidos</label>
                                                            <input type="text" placeholder="Apellidos" name="apellidos" id="apellidos" class="form-control" value="<?php echo $obj->apellidos; ?>" <?php echo $disabled; ?>>
                                                        </div>
                                                    </div>


                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <label for="ref"><i class="fa-solid fa-envelope"></i> Correo</label>
                                                            <input onchange="validar_correo(event)" required="required" placeholder="Correo" type="text" name="correo" id="correo" class="form-control" value="<?php echo $obj->acceso_usuario; ?>" <?php echo $disabled; ?>>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="label"><i class="fa-solid fa-phone"></i> Telefono</label>
                                                            <input type="text" placeholder="Telefono" name="usuario_telefono" id="usuario_telefono" class="form-control" value="<?php echo $obj->usuario_telefono; ?>" <?php echo $disabled; ?>>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <label for="ref"><i class="fa-solid fa-lock"></i> Contraseña</label>
                                                            <input required="required" placeholder="Contraseña" type="password" name="acceso_clave" id="acceso_clave" class="form-control" value="<?php echo $obj->acceso_clave; ?>" <?php echo $disabled; ?>>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="fk_perfil"><i class="fa-solid fa-address-card"></i> Perfil</label>
                                                            <select <?php echo $disabled; ?> name="fk_perfil[]" id="fk_perfil" class="form-control select2" multiple="multiple" required="required">
                                                                <?php
                                                                // Recorrer todos los perfiles disponibles para crear el select
                                                                foreach ($perfiles as $perfil) {
                                                                    // Verificar si el perfil actual está en la lista de perfiles asignados
                                                                    $selected = in_array($perfil->rowid, $perfiles_asignados_ids) ? 'selected' : '';
                                                                ?>
                                                                    <option value="<?php echo $perfil->rowid; ?>" <?php echo $selected; ?>>
                                                                        <?php echo $perfil->etiqueta; ?>
                                                                    </option>
                                                                <?php
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>


                                                    <!-- Añadir el select múltiple con select2 -->
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <label for="fk_idioma"><i class="fa-solid fa-language"></i> Idioma</label>
                                                            <select name="fk_idioma" id="fk_idioma" class="form-control" <?php echo $disabled; ?>>
                                                                <option value="">Seleccionar Idioma</option>
                                                                <?php
                                                                foreach ($idioma->idiomas as $idioma) {
                                                                ?>
                                                                    <option <?php if ($obj->fk_idioma == $idioma['rowid']) {
                                                                                echo 'selected';
                                                                            } ?> value="<?php echo $idioma['rowid']; ?>"><?php echo $idioma['etiqueta']; ?></option>
                                                                <?php
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <?php if (isset($obj) && $obj->id > 0) { ?>
                                                            <div class="col-md-3">
                                                                <div class="row">
                                                                    <label for="country">Activo en la empresa</label>
                                                                    <?php
                                                                    $checked = '';

                                                                    if (intval($obj->activo_empresa) === 1) {
                                                                        $checked = 'checked';
                                                                    }
                                                                    ?>
                                                                    <div class="switch form-switch-custom switch-inline form-switch-primary">
                                                                        <input name="activo_empresa" class="switch-input" type="checkbox" value="1" role="switch" id="activo_empresa" style="margin-left: 5px; " <?php echo $checked; ?> <?php echo $disabled; ?>>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                        <div class="col-md-3">
                                                            <div class="row">
                                                                <label for="country">Estado Actual </label>
                                                                <div class="switch form-switch-custom switch-inline form-switch-primary">
                                                                    <?php
                                                                    if(intval($obj->activo) == 1){ 
                                                                        $estado_icon = 'primary';
                                                                    } else if(intval($obj->activo) == 0){ 
                                                                        $estado_icon = 'danger';
                                                                    } else if(intval($obj->activo) == 3){ 
                                                                        $estado_icon = 'warning';
                                                                    }
                                                                    ?>
                                                                    <span class="badge badge-<?php echo $estado_icon; ?> sidebar-label "><?php echo $obj->activo_descripcion; ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-footer mt-12">
                                                        <?php
                                                        if (empty($_REQUEST['fiche'])) { ?>
                                                            <a href="<?php echo ENLACE_WEB; ?>usuarios_listado" class="btn btn-primary"><i class="fa fa-fw fa-circle"></i>Cancelar</a>
                                                            <button type="button" class="btn btn-primary" onclick="crearUsuario(event)"><i class="fa fa-fw fa-circle"></i>Crear Usuario </button>
                                                        <?php }

                                                        if (!empty($_REQUEST['fiche']) and $_GET['action'] !== "modificar") { ?>
                                                            <a href="<?php echo ENLACE_WEB; ?>usuarios_listado" class="btn btn-outline-primary">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-box">
                                                                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                                                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                                                    <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                                                </svg>
                                                                Volver al Listado
                                                            </a>


                                                            <?php if ($es_dueno && intval($obj->activo) === 1) { ?>
                                                                <a href="#" onclick="confirma_eliminar_usuario('<?php echo $_REQUEST['fiche']; ?>')" class="btn btn-danger  bs-tooltip " data-bs-placement="left" title="Tooltip on left"><i class="fa fa-fw fa-trash"></i> Desactivar </a>
                                                            <?php } ?>

                                                            <?php if ($es_dueno && intval($obj->activo) === 0) { ?>
                                                                <a href="#" onclick="confirma_activar_usuario('<?php echo $_REQUEST['fiche']; ?>')" class="btn btn-success"><i class="fa fa-fw fa-edit"></i> Activar</a>
                                                            <?php } else { ?>
                                                                <a href="<?php echo ENLACE_WEB; ?>dashboard.php?accion=usuario_editar&action=modificar&fiche=<?php echo $_REQUEST['fiche']; ?>" class="btn btn-success"><i class="fa fa-fw fa-edit"></i> Modificar</a>
                                                            <?php } ?>

                                                        <?php } ?>

                                                        <?php if ($_REQUEST['action'] == "modificar" and !empty($_REQUEST['fiche'])) { ?>
                                                            <a href="<?php echo ENLACE_WEB; ?>dashboard.php?accion=usuario_editar&fiche=<?php echo $_REQUEST['fiche']; ?>" class="btn btn-outline-primary"><i class="fa fa-fw fa-circle"></i>Cancelar modificación de <?php echo $obj->tipo_texto; ?></a>

                                                            <button type="submit" name="editar" value="true" class="btn btn-primary" onclick="actualizarUsuario(event)">
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
        $(".usuarios").addClass('active');
        $(".usuarios > .submenu").addClass('show');
        $("#usuarios_crear").addClass('active');
    });
</script>
<?php include ENLACE_SERVIDOR . 'mod_usuarios/tpl/scripts_editar.php'; ?>