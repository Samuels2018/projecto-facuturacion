<?php
include ENLACE_SERVIDOR . 'mod_terceros/object/terceros.object.php';
include ENLACE_SERVIDOR . 'mod_contactos_crm/object/contactos_crm.object.php';
$terceros = new FiTerceros($dbh);

$terceros->entidad = $_SESSION['Entidad'];
$terceros_listado = $terceros->obtener_listado_terceros();

$contacto = new TerceroCRMContacto($dbh);

$contacto->fetch($_REQUEST['fiche']);




$latitud = $contacto->latitude != '' ? $contacto->latitude :40.4165;
$longitud = $contacto->longitud != '' ? $contacto->longitud : -3.70256;

$texto_informativo = $contacto->rowid != '' ? $contacto->nombre . ' ' . $contacto->apellidos : "Nuevo Contacto CRM";

$disabled = !empty($_REQUEST['fiche']) ? 'disabled="disabled"' : '';

if ($_REQUEST['fiche'] != '' && $_REQUEST['action'] == 'modify') {
    $disabled = '';
}
?>
<div class="middle-content container-xxl p-0">
    <form role="form" method="POST" action="">
        <input type="hidden" name="fiche" value="<?php echo $_REQUEST['fiche'] ?>">
        <div class="page-meta">
            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo ENLACE_WEB ?>contactos_crm_listado">Contactos</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $contacto->rowid != '' ? returnSplitNameClient($texto_informativo) : $texto_informativo ?></li>
                </ol>
            </nav>
        </div>
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <section class="content">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group row mt-4">
                                        <div class="col-md-6">
                                            <label for="nombre"><i class="fa fa-fw fa-user"></i> Nombre</label>
                                            <input type="hidden" name="rowid" value="<?php echo $contacto->rowid; ?>">
                                            <input required="required" type="text" name="nombre" class="form-control" value="<?php echo $contacto->nombre; ?>" required <?php echo $disabled ?>>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="apellidos"><i class="fa fa-fw fa-user"></i> Apellidos</label>
                                            <input type="text" name="apellidos" class="form-control" value="<?php echo $contacto->apellidos; ?>" <?php echo $disabled ?>>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-4">
                                        <div class="col-md-6">
                                            <label for="puesto_t"><i class="fa fa-fw fa-file"></i> Puesto</label>
                                            <input required="required" type="text" name="puesto_t" class="form-control" value="<?php echo $contacto->puesto_t; ?>" required <?php echo $disabled ?>>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="pais_c"><i class="fa fa-fw fa-user"></i> Pais</label>
                                            <input type="text" name="pais_c" class="form-control" value="<?php echo $contacto->pais_c; ?>" <?php echo $disabled ?>>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-4">
                                        <div class="col-md-6">
                                            <label for="fecha_nacimiento"><i class="fa fa-fw fa-calendar"></i> Fecha de Nacimiento</label>
                                            <input type="date" name="fecha_nacimiento" class="form-control" value="<?php echo $contacto->fecha_nacimiento; ?>" <?php echo $disabled ?>>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="fk_tercero"><i class="fa fa-fw fa-briefcase"></i> Cliente Asociado</label>
                                            <select class="form-control select2" name="fk_tercero" id="fk_tercero" <?php echo $disabled ?>>
                                                <option value="">Seleccionar</option>
                                                <?php foreach ($terceros_listado as $tercero) : ?>
                                                    <option value="<?php echo $tercero->rowid  ?>" <?php echo ($tercero->rowid == $contacto->fk_tercero) ? 'selected' : '' ?>><?php echo $tercero->nombre_cliente; ?></option>
                                                <?php endforeach; ?>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-4">
                                        <div class="col-md-6">
                                            <label for="email"><i class="fa fa-fw fa-envelope-o"></i> Email</label>
                                            <input type="text" name="email" class="form-control" value="<?php echo $contacto->email; ?>" <?php echo $disabled ?>>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="telefono"><i class="fa fa-fw fa-phone"></i> Teléfono</label>
                                            <input type="text" name="telefono" class="form-control" value="<?php echo $contacto->telefono; ?>" <?php echo $disabled ?>>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-4">
                                        <div class="col-md-6">
                                            <label for="extension"><i class="fa fa-fw fa-phone"></i> Extensión</label>
                                            <input type="text" name="extension" class="form-control" value="<?php echo $contacto->extension; ?>" <?php echo $disabled ?>>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="whatsapp"><i class="fa fa-fw fa-whatsapp"></i> WhatsApp</label>
                                            <input type="text" name="whatsapp" class="form-control" value="<?php echo $contacto->whatsapp; ?>" <?php echo $disabled ?>>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-4">
                                        <div class="col-md-6">
                                            <label for="facebook"><i class="fa fa-fw fa-facebook"></i> Facebook</label>

                                            <div class="input-group mb-3">
                                                <span class="input-group-text" id="basic-addon1">https://facebook.com/</span>
                                                <input type="text" class="form-control" name="facebook" id="basic-url" aria-describedby="basic-addon3" value="<?php echo $contacto->facebook; ?>" <?php echo $disabled ?>>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="linkedin"><i class="fa fa-fw fa-linkedin"></i> LinkedIn</label>

                                            <div class="input-group mb-3">
                                                <span class="input-group-text" id="basic-addon2">https://linkedin.com/</span>
                                                <input type="text" class="form-control" name="linkedin" id="linkedin" aria-describedby="basic-addon3" value="<?php echo $contacto->linkedin; ?>" <?php echo $disabled ?>>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-4">
                                        <div class="col-md-6">
                                            <label for="instagram"><i class="fa fa-fw fa-instagram"></i> Instagram</label>

                                            <div class="input-group mb-3">
                                                <span class="input-group-text" id="basic-addon3">https://instagram.com/</span>
                                                <input type="text" class="form-control" name="instagram" id="instagram" aria-describedby="basic-addon3" value="<?php echo $contacto->instagram; ?>" <?php echo $disabled ?>>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="x_twitter"><i class="fa fa-fw fa-twitter"></i> Twitter</label>

                                            <div class="input-group mb-3">
                                                <span class="input-group-text" id="basic-addon4">https://twitter.com/</span>
                                                <input type="text" class="form-control" name="x_twitter" id="x_twitter" aria-describedby="basic-addon3" value="<?php echo $contacto->x_twitter; ?>" <?php echo $disabled ?>>
                                            </div>
                                        </div>
                                    </div>
                                       <div class="form-group row mt-4">
                                        <div class="col-md-6">
                                            <label for="x_twitter"><i class="fa fa-crosshairs" aria-hidden="true"></i> Pagina Web</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text" id="basic-addon4">https://example.com/</span>
                                                <input type="text" class="form-control" name="paginaweb" id="paginaweb" aria-describedby="basic-addon3" value="<?php echo $contacto->paginaweb; ?>" <?php echo $disabled ?>>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-4">
                                        <div class="col-md-6">
    
                                            <label for="live-location"><i class="fa fa-map-marker "></i> Ubicacion</label>
                                            <div class="div basic-map" style="height: 300px; width: 370px; margin-right: 30px" id="live-location"></div>
                                            <input type="hidden" name="latitude" id="latitude" class="form-control" value="<?php echo $latitud; ?>">
                                            <input type="hidden" name="longitud" id="longitud" class="form-control" value="<?php echo $longitud; ?>">

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="card-footer mt-4 botonera-footer">


                                                <a href="<?php echo ENLACE_WEB; ?>contactos_crm_listado" class="btn btn-outline-primary">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-box">
                                                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                                    </svg>
                                                    Volver al Listado
                                                </a>


                                                <?php if (!empty($_REQUEST['fiche'])) { ?>
                                                    <button type="button" onclick="eliminar_contacto(event)" class="btn btn-danger">
                                                        <i class="fa fa-fw fa-trash"></i>
                                                        Eliminar
                                                    </button>

                                                <?php } else { ?>
                                                    <a href="<?php echo ENLACE_WEB . 'contactos_crm_listado' ?>" class="btn btn-primary"><i class="fa fa-fw fa-eraser"></i>Cancelar</a>
                                                <?php } ?>

                                                <?php if (empty($_REQUEST['action']) && !empty($_REQUEST['fiche'])) { ?>

                                                    <a href="<?php echo ENLACE_WEB . 'contactos_crm_nuevo' ?>" class="btn btn-primary"><i class="fa fa-fw fa-plus"></i>
                                                        Generar Otro <?php echo $texto_boton ?>
                                                    </a>


                                                    <a href="<?php echo ENLACE_WEB; ?>dashboard.php?accion=<?php echo $_GET['accion'] ?>&fiche=<?php echo $_REQUEST['fiche'] ?>&action=modify" class="btn btn-success">
                                                        <i class="fa fa-fw fa-edit"></i> Modificar
                                                    </a>

                                                <?php } ?>

                                                <?php if (empty($_REQUEST['action']) && empty($_REQUEST['fiche'])) { ?>
                                                    <button type="button" id="crear_contacto_crm" onclick="crear_contacto(event)" class="btn btn-primary">
                                                        <i class="fa fa-fw fa-circle"></i>Crear <?php echo $texto_boton ?></button>
                                                <?php } ?>


                                                <?php if ($_REQUEST['action'] == "modify" && !empty($_REQUEST['fiche'])) { ?>
                                                    <button type="button" id="modificar_contacto_crm"  onclick="modificar_contacto(event)" class="btn btn-primary">
                                                        <i class="fa fa-fw fa-circle"></i>Guardar Cambios Contacto
                                                    </button>
                                                <?php } ?>



                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </section>
            </div>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {


        $(".menu").removeClass('active');

$(".contactos").addClass('active');


    });
</script>

<?php require_once ENLACE_SERVIDOR . 'mod_contactos_crm/tpl/contactos_crm_editar_scripts.php' ?>