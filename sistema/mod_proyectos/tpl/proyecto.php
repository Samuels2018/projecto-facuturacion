<?php

require_once(ENLACE_SERVIDOR . "mod_proyectos/object/Proyectos.object.php");
$Proyectos = new Proyectos($dbh, $_SESSION['Entidad']);

$texto_informativo = "";
$disabled = "disabled";

if (!empty($_POST) && empty($_REQUEST['fiche'])) {
    $result =  $Proyectos->fetch($_REQUEST['fiche']);
    $_REQUEST['fiche'] = $id;
    $disabled = "";
} elseif (!empty($_POST) && !empty($_REQUEST['fiche'])) {
    $result =  $Proyectos->fetch($_REQUEST['fiche']);
} elseif (empty($_REQUEST['fiche'])) {
    $disabled = "";
} elseif (!empty($_REQUEST['fiche']) && $_REQUEST['action'] == "modify") {
    $disabled = "";
    $result =  $Proyectos->fetch($_REQUEST['fiche']);
} elseif (!empty($_REQUEST['fiche'])) {
    $result = $Proyectos->fetch($_REQUEST['fiche']);
}

// Si no hay usuario autenticado, cerrar conexión
if (!isset($_SESSION['usuario'])) {
    echo acceso_invalido();
    exit(1);
}
if (isset($_GET['fiche'])) {
    if ($Proyectos->entidad_proyecto != $_SESSION['Entidad'] || $Proyectos->borrado == 1 || $result['exito'] === 0) {
        echo acceso_invalido();
        exit(1);
    }
}

?>

<script src="bootstrap/jquery.roadmap.js" type="text/javascript"></script>
<link rel="stylesheet" href="bootstrap/plugins/roadmap.css">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<style>
    .tab-servicios.fade,
    .tab-adjuntos.fade {
        display: none;
    }

    .tab-servicios.fade.active,
    .tab-adjuntos.fade.active {
        display: block;
    }

    label.strong {
        font-weight: 600;
    }

    #loading {
        display: none;
        position: fixed;
        z-index: 9999;
        height: 100%;
        width: 100%;
        top: 0;
        left: 0;
        background: rgba(0, 0, 0, 0.5);
        text-align: center;
        color: white;
        font-size: 2em;
    }

    #loading-text {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
</style>

<div id="loading">
    <div id="loading-text"><i class="fa fa-id-card-o" aria-hidden="true"></i> Generando Cotización...</div>
</div>

<div class="row mt-3">
    <div class="col-xs-12" style="margin-top:25px;">
        <div class="card">
            <div class="card-body table-responsive no-padding">


                <div class="row">
                    <div class="col-md-6">

                        <input type="hidden" name="editar" id="editar" value="1">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="strong"><i class="fa fa-map-signs"></i> REFERENCIA :</label>
                            </div>
                            <div class="col-md-9">
                                <label><?php echo $Proyectos->referencia; ?></label>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="strong"><i class="fa fa-user"></i> NOMBRE :</label>
                            </div>
                            <div class="col-md-9">
                                <label><?php echo $Proyectos->nombre; ?></label>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="strong"><i class="fa fa-user-tie"></i> CLIENTE :</label>
                            </div>
                            <?php
                            $nombre_buscador = isset($Proyectos->fk_tercero) ? $Proyectos->nombre_tercero . ' • ' . $Proyectos->email_tercero : '';
                            ?>
                            <div class="col-md-9">
                                <label><?php echo $nombre_buscador; ?></label>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="strong"><i class="fa fa-info-circle"></i> ESTADO :</label>
                            </div>
                            <div class="col-md-9">
                                <?php if ($Proyectos->estado === 1): ?>
                                    <span class="badge bg-success"><i class="fa fa-check-circle"></i> Activo</span>
                                <?php else: ?>
                                    <span class="badge bg-danger"><i class="fa fa-times-circle"></i> Inactivo</span>
                                <?php endif; ?>
                            </div>
                        </div>


                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="strong"><i class="fa fa-dollar-sign"></i> MONTO :</label>
                            </div>
                            <div class="col-md-9">
                                <label><?php echo $Proyectos->monto; ?></label>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="strong"><i class="fa fa-calendar-alt"></i> FECHA INICIO :</label>
                            </div>
                            <div class="col-md-9">
                                <label><?php echo date('d-m-Y', strtotime($Proyectos->fecha_inicio)); ?></label>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="strong"><i class="fa fa-calendar-check"></i> FECHA FIN :</label>
                            </div>
                            <div class="col-md-9">
                                <label><?php echo date('d-m-Y', strtotime($Proyectos->fecha_fin)); ?></label>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-6">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="strong"><i class="fa fa-tags"></i> ETIQUETAS :</label>
                            </div>
                            <div class="col-md-9">
                                <select <?php echo $disabled; ?> class="form-control select2" name="etiquetas_tags" multiple='multiple' id="etiquetas_tags">
                                    <?php
                                    $arrayRecuperadoTags = explode(",", $Proyectos->etiquetas_tags);
                                    foreach ($arrayRecuperadoTags as $item => $value) {
                                        if ($value === '') continue;
                                        echo '<option selected value="' . $value . '">' . $value . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="strong"><i class="fas fa-map-marker-alt"></i> Ubicación en el Mapa :</label>
                            </div>
                            <div class="col-md-9">
                                <div id="ubicacion_mapa" class="border border-dashed p-4 text-center" style="border: 2px dashed #007bff; cursor: pointer;">
                                    <p><?php echo $Proyectos->ubicacion_mapa; ?></p>
                                    <input type="hidden" id="ubicacion_mapa_input" name="latitud_longitud" value="<?php echo $Proyectos->latitud_longitud; ?>">
                                </div>
                                <div id="map" style="height: 400px; width: 100%; margin-top: 15px;"></div>
                            </div>
                        </div>
                    </div><!-- CIERRE DE LA COLUMNA-->
                </div>



                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button">Gastos</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="compras-tab" data-bs-toggle="tab" data-bs-target="#compras-tab-pane" type="button">Compras</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="facturas-tab" data-bs-toggle="tab" data-bs-target="#facturas-tab-pane" type="button">Facturas</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="rentabilidad-tab" data-bs-toggle="tab" data-bs-target="#rentabilidad-tab-pane" type="button">Rentabilidad</button>
                    </li>
                </ul>

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home-tab-pane">
                        <h3 class="text-center"><strong>Gastos</strong></h3>

                        <table id="service-table" class="table table-striped" style="margin-top:20px;">
                            <thead>
                                <tr>
                                    <th>Factura</th>
                                    <th>Proveedor</th>
                                    <th>Fecha</th>
                                    <th>Usuario</th>
                                    <th>Monto</th>
                                    <th>Cuenta de Gasto</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody id="tabla_servicios">
                                <?php include_once(ENLACE_SERVIDOR . "mod_proyectos/ajax/listado_proyecto_gastos.ajax.php"); ?>
                            </tbody>
                        </table>


                    </div>

                    <div class="tab-pane fade" id="compras-tab-pane">
                        <h3 class="text-center"><strong>Compras</strong></h3>
                        <table id="purchase-table" class="table table-striped" style="margin-top:20px;">
                            <thead>
                                <tr>
                                    <th>Tipo</th>
                                    <th>Ver Compra</th>
                                    <th>Nombre Cliente</th>
                                    <th>Fecha</th>
                                    <th>Usuario</th>
                                    <th>Base</th>
                                    <th>Impuesto</th>
                                    <th>Total</th>
                                    <th>Pagado</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody id="tabla_compras">
                                <?php include_once(ENLACE_SERVIDOR . "mod_proyectos/ajax/listado_proyecto_compras.ajax.php"); ?>
                            </tbody>
                        </table>


                    </div>

                    <div class="tab-pane fade" id="facturas-tab-pane">
                        <h3 class="text-center"><strong>Facturas</strong></h3>
                        <table id="purchase-table" class="table table-striped" style="margin-top:20px;">
                            <thead>
                                <tr>
                                    <th>Tipo</th>
                                    <th>Ver Factura</th>
                                    <th>Nombre Cliente</th>
                                    <th>Fecha</th>
                                    <th>Usuario</th>
                                    <th>Base</th>
                                    <th>Impuesto</th>
                                    <th>Total</th>
                                    <th>Pagado</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody id="tabla_facturas">
                                <?php include_once(ENLACE_SERVIDOR . "mod_proyectos/ajax/listado_proyecto_facturas.ajax.php"); ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="tab-pane fade" id="rentabilidad-tab-pane">
                        <h3 class="text-center"><strong>Rentabilidad</strong></h3>
                        <?php include_once(ENLACE_SERVIDOR . "mod_proyectos/ajax/rentabilidad.ajax.php"); ?>
                    </div>


                </div><!-- CIERE TAB -->


                <div class="card-footer mt-12" style="margin-top:20px;">
                    <a href="<?php echo ENLACE_WEB; ?>proyecto_listado" class="btn btn-outline-primary">Volver al Listado</a>
                    <?php if (isset($_GET['fiche'])) { ?>

                        <?php
                        if (!isset($_REQUEST['action'])) {
                        ?>
                            <a href="<?php echo ENLACE_WEB; ?>dashboard.php?accion=<?php echo 'editar_proyecto'; ?>&fiche=<?php echo $_REQUEST['fiche'] ?>&action=modify" class="btn btn-success">
                                <i class="fa fa-fw fa-edit"></i> Modificar
                            </a>
                        <?php } ?>

                        <?php if (!isset($_REQUEST['action'])) { ?>
                            <a href="#" onclick="eliminarProyecto()" class="btn btn-danger">
                                <i class="fa fa-fw fa-trash"></i> Eliminar
                            </a>
                        <?php } ?>

                    <?php } else { ?>
                        <a href="#" onclick="ActualizarProyecto()" class="btn btn-success">
                            <i class="fa fa-fw fa-save"></i> Guardar
                        </a>
                    <?php } ?>

                    <?php if ($_REQUEST['action'] == "modify" && !empty($_REQUEST['fiche'])) { ?>
                        <button type="button" onclick="ActualizarProyecto()" class="btn btn-primary">
                            <i class="fa fa-fw fa-circle"></i>Guardar Cambios
                        </button>
                    <?php } ?>



                </div>


            </div>
        </div>
    </div>
</div>

<?php
require_once ENLACE_SERVIDOR . 'mod_proyectos/tpl/proyecto_scripts.php';
?>