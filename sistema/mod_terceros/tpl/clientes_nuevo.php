
<style type="text/css">
    .dataTables_filter {
        display: none !important;
    }

    .dataTables_length {
        display: -webkit-box;
    }

    #export-buttons-container {
        margin-left: 25 px;
    }

    #export-buttons-container button+button {
        margin-left: 15px;
    }

    #columnVisibilityContainer {
        margin-top: 40px !important;
    }
</style>
<script src="<?php echo ENLACE_WEB; ?>bootstrap/jquery.maskedinput.js"></script>
<?php

require_once ENLACE_SERVIDOR . '/mod_terceros/object/terceros.object.php';
require_once ENLACE_SERVIDOR . '/mod_comerciales/object/comerciales.object.php';
require_once ENLACE_SERVIDOR . "/mod_configuracion_agente/object/agente.object.php";
require_once ENLACE_SERVIDOR . "/mod_rutas/object/rutas.object.php";
require_once ENLACE_SERVIDOR . "/mod_regimen_iva/object/regimen_iva_object.php";
require_once ENLACE_SERVIDOR . "/mod_formas_pago/object/forma_pago_object.php";
require_once ENLACE_SERVIDOR . '/mod_diccionario_listas_precios/object/lista_precios_object.php';
require_once ENLACE_SERVIDOR . '/mod_diccionario_moneda/object/moneda_object.php';

require_once ENLACE_SERVIDOR . 'mod_utilidad/object/utilidades.object.php';




$agente = new Agente($dbh);
$agente_actual = $agente->obtener_agente_actual($_REQUEST['fiche']);
$agentes = $agente->obtener_agentes();

$ruta = new Diccionario_ruta($dbh);
$ruta_actual = $ruta->obtener_ruta_actual($_REQUEST['fiche']);
$rutas = $ruta->obtener_rutas();

$cliente = new FiTerceros($dbh, $_SESSION['Entidad']);


$regimen_iva = new regimen_iva($dbh_utilidades_Apoyo);
$lista_regimen = $regimen_iva->listar_regimen_iva();
$listar_tipos_retencion = $regimen_iva->listar_tipos_retencion();


$lista_precios = new ListaPreciosClientes($dbh);
$lista_precios->entidad = $_SESSION['Entidad'];
$listado_precios = $lista_precios->listar_lista_precios();

$moneda = new Moneda($dbh);
$moneda->entidad = $_SESSION['Entidad'];
$listado_moneda = $moneda->listar_monedas();


$ENTIDAD_RED_HOUSE = 5;
$ENTIDAD_USER = $_SESSION['Entidad'];



$comerciales = new Comerciales($dbh);

$forma_pago = new Forma_pago($dbh);
$forma_pago->entidad = $_SESSION['Entidad'];
$formas_pago = $forma_pago->listar_formas_pago();
$lista_formas_pago = $forma_pago->listar_formas_pago();

$disabled = 'disabled="disabled"';

$_REQUEST['action'] == "modify";

$requestUri = $_SERVER['REQUEST_URI'];

$pos = strpos($requestUri, 'proveedor');

if ($pos !== false) {
    $texto_breadcumb = "Proveedores";
    $ruta_breadcumb = "proveedores_listado";
    $ruta_creacion = "proveedores_nuevo";
    $texto_informativo = "Nuevo Proveedor";
    $texto_boton = "Proveedor";
} else {
    $texto_breadcumb = "Clientes";
    $ruta_breadcumb = "clientes_listado";
    $ruta_creacion = "clientes_nuevo";
    $texto_informativo = "Nuevo Cliente";
    $texto_boton = "Cliente";
}

//selects
$formas_pago_diccionario = new Utilidades($dbh);
//$formas_pago = $formas_pago_diccionario->obtener_formas_pago();

$tipo_contacto = $cliente->obtener_tipo_contacto();
$bancos = $formas_pago_diccionario->obtener_bancos();


$labeltext = '';
if($_GET['accion'] == 'proveedores_editar' || $_GET['accion'] == 'proveedores_nuevo'){
    $labeltext = 'Proveedor';
}else{
    $labeltext = 'Cliente';
}




if (!empty($_POST) && empty($_REQUEST['fiche'])) {

    $cliente->fetch($_REQUEST['fiche']);
    $_REQUEST['fiche'] = $id;
    $texto_informativo = $cliente->nombre;
} elseif (!empty($_POST) && (!empty($_REQUEST['fiche']))) {
    $cliente->fetch($_REQUEST['fiche']);
    $texto_informativo = $cliente->nombre;
} elseif (empty($_REQUEST['fiche'])) {
    $disabled = "";
} elseif (!empty($_REQUEST['fiche']) && $_REQUEST['action'] == "modify") {
    $disabled = "";
    $cliente->fetch($_REQUEST['fiche']);
    $texto_informativo = $cliente->nombre;
} elseif (!empty($_REQUEST['fiche'])) {
    $cliente->fetch($_REQUEST['fiche']);
    $texto_informativo = $cliente->nombre . " " . $cliente->apellidos;
}

$tipo_contacto = $cliente->obtener_tipo_contacto();

$listado_categorias_cliente = $cliente->obtener_listado_categorias_clientes($_SESSION['Entidad']);

$Utilidades = new Utilidades($dbh);
$lista_identificacion_fiscal = $Utilidades->obtener_identificaciones_fiscales();
$lista_tipos_residencia = $Utilidades->obtener_tipo_residencias();

$paises = $Utilidades->obtener_paises();
$poblaciones = $Utilidades->obtener_comunidades_autonomas(1);
$provincias = $Utilidades->obtener_provincias($cliente->fk_poblacion);




?>

<div class="middle-content container-xxl p-0">

    </div>
        <input type="hidden" name="fiche" id="fiche" value="<?php echo $_REQUEST['fiche'] ?>">

        <!-- BREADCRUMB -->
        <div class="page-meta">
            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo ENLACE_WEB . $ruta_breadcumb ?>">
                            <?php echo $texto_breadcumb ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?php echo $cliente->rowid != '' ?
                            returnSplitNameClient($texto_informativo) : $texto_informativo ?></li>
                </ol>
            </nav>
        </div>
        <!-- /BREADCRUMB -->


        <div class="row layout-top-spacing">


            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">

                <section class="content">

                    <ul class="nav nav-pills mb-3" id="pills-tab-1" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link <?php echo $_GET['tab'] != 'stock' ? 'active' : '' ?>" id="active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-producto" type="button" role="tab" aria-controls="pills-home" aria-selected="<?php echo $_GET['tab'] != 'stock' ? 'true' : 'false' ?>">Ficha <?php echo $labeltext; ?></button>
                        </li>
                        <?php if ($pos === false): ?>
                            <li class="nav-item" role="presentation" title="Debe registrar primero al <?php echo $texto_boton ?>">
                                <button class="nav-link" id="pills-contactos-tab" data-bs-toggle="pill"
                                    <?php echo $cliente->cliente != 1 && $cliente->proveedor != 1 ? "onclick=' 'alerta_crear(event)'" . ' ' . 'disabled' : "" ?> data-bs-target="#pills-contactos" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                        <polyline points="10 9 9 9 8 9"></polyline>
                                    </svg> Contactos</button>
                            </li>
                        <?php endif; ?>

                        <li class="nav-item" role="presentation" title="Debe registrar primero al <?php echo $texto_boton ?>">
                            <button class="nav-link" <?php echo $cliente->cliente != 1 && $cliente->proveedor != 1 ? "onclick=' 'alerta_crear(event)'" . ' ' . 'disabled' : "" ?> id="pills-impuestos-tab" data-bs-toggle="pill" data-bs-target="#pills-impuestos" type="button" role="tab" aria-controls="pills-impuestos" aria-selected="false">Impuestos</button>
                        </li>

                        <li class="nav-item" role="presentation" title="Debe registrar primero al <?php echo $texto_boton ?>">
                            <button class="nav-link" <?php echo $cliente->cliente != 1 && $cliente->proveedor != 1 ? "onclick=' 'alerta_crear(event)'" . ' ' . 'disabled' : "" ?> id="pills-condiciones-comerciales-tab" data-bs-toggle="pill" data-bs-target="#pills-condiciones-comerciales" type="button" role="tab" aria-controls="pills-condiciones-comerciales" aria-selected="false">Condiciones Comerciales</button>
                        </li>

                        <li class="nav-item" role="presentation" title="Debe registrar primero al <?php echo $texto_boton ?>">
                            <button class="nav-link" <?php echo $cliente->cliente != 1 && $cliente->proveedor != 1 ? "onclick=' 'alerta_crear(event)'" . ' ' . 'disabled' : "" ?> id="pills-imagenes-tab" data-bs-toggle="pill" data-bs-target="#pills-imagenes" type="button" role="tab" aria-controls="pills-contact" aria-selected="false"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                </svg> Proteccion Antifraude</button>
                        </li>
                    </ul>

                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade  <?php echo $_GET['tab'] != 'stock' ? 'show active' : '' ?>" id="pills-producto" role="tabpanel" aria-labelledby="pills-producto-tab" tabindex="0">
                            <div class="row">
                                <!-- left column -->
                                <div class="col-md-9">
                                    <!-- general form elements -->
                                    <div class="card">

                                        <div class="card-body">

                                            <div class="form-group row mt-4">
                                                <div class="col-md-6">
                                                    <label for="nombre"><i class="fa fa-fw fa-user"></i>
                                                        <span id="fisico_juridico_t1">
                                                            <?php if ($pos > 0) {

                                                                echo 'Razón Social';
                                                            } else {
                                                                echo 'Nombre';
                                                            }
                                                            ?></span>
                                                        <span style="color:red">*</span>
                                                    </label>
                                                    <input required="required" type="text" id="nombre" name="nombre" class="form-control" value="<?php echo $cliente->nombre; ?>" <?php echo $disabled; ?> required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="apellidos"><i class="fa fa-fw fa-user"></i>
                                                        <span id="fisico_juridico">
                                                            <?php echo $pos !== false ?
                                                                'Nombre Fantasía' : 'Apellidos'; ?>
                                                        </span>
                                                    </label>
                                                    <input type="text" id="apellidos" name="apellidos" class="form-control" value="<?php echo ($cliente->tipo=='juridica'? $cliente->electronica_nombre_comercial: $cliente->apellidos); ?>" <?php echo $disabled; ?>>
                                                </div>
                                            </div>
                                            <div class="row" id="div_nombre_comercial_fisica" >
                                                <div class="col-md-6">
                                                    <label for="nombre_comercial_fisica"><i class="fa fa-fw fa-user" aria-hidden="true"></i>
                                                        <span>Nombre comercial</span>
                                                    </label>
                                                    <input type="text" id="nombre_comercial_fisica" name="nombre_comercial_fisica" class="form-control" value="<?php echo $cliente->electronica_nombre_comercial; ?>" <?php echo $disabled; ?>>
                                                </div>
                                            </div>

                                            <div class="row mt-4">
                                                <div class="col-md-3">
                                                    <label for="tipo">
                                                        <i class="fa fa-fw fa-paperclip"></i>
                                                        Tipo de persona
                                                        <span style="color:red">*</span>
                                                    </label>
                                                    <select required class="form-control" Onchange="enmascara(this.value)" id="tipo" name="tipo" <?php echo $disabled; ?>>
                                                        <option value="fisica" <?php echo ($cliente->tipo == "fisica" ||
                                                                                    empty($cliente->tipo)) ? 'selected="selected"' : '' ?>>Físico</option>
                                                        <option value="juridica" <?php echo ($cliente->tipo == "juridica") ?
                                                                                        'selected="selected"' : '' ?>>Jurídico </option>

                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="tipo">
                                                        <i class="fa fa-fw fa-paperclip"></i>
                                                        Tipo de residencia
                                                        <span style="color:red">*</span>
                                                    </label>
                                                    <select required class="form-control" Onchange="enmascara(this.value)" name="tipo_residencia" id="tipo_residencia" <?php echo $disabled; ?>>
                                                        <option disabled value="" <?php echo (empty($cliente->fk_tipo_residencia)) ? 'selected="selected"' : '' ?>>
                                                            Seleccione... </option>
                                                        <?php
                                                        foreach ($lista_tipos_residencia as $tipo_residencia) {
                                                            if ($cliente->fk_tipo_residencia  == $tipo_residencia->rowid) {
                                                                $s = 'selected="selected"';
                                                            } else {
                                                                $s = "";
                                                            }
                                                            echo '<option value="' . $tipo_residencia->rowid . '" ' . $s . ' >' . $tipo_residencia->descripcion . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="apellidos"><i class="fa fa-folder-open" aria-hidden="true"></i>
                                                        <span>
                                                            Categoria
                                                        </span>
                                                        <span style="color:red">*</span>
                                                    </label>
                                                    <select class="form-control" <?php echo $disabled; ?> required id="fk_categoria_cliente" name="fk_categoria_cliente">
                                                        <option value="">Seleccionar categoria</option>
                                                        <?php
                                                        foreach ($listado_categorias_cliente as $key => $value) {
                                                        ?>
                                                            <option <?php if ($cliente->fk_categoria_cliente === $listado_categorias_cliente[$key]['rowid']) {
                                                                        echo 'selected';
                                                                    }  ?> value="<?php echo $listado_categorias_cliente[$key]['rowid']; ?>"><?php echo $listado_categorias_cliente[$key]['label']; ?></option>
                                                        <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>


                                            <div class="form-group row mt-4">
                                                <div class="col-md-3">
                                                    <label for="tipo">
                                                        <i class="fa fa-fw fa-paperclip"></i>
                                                        Tipo de identificación
                                                        <span style="color:red">*</span>
                                                    </label>
                                                    <select required class="form-control" Onchange="enmascara(this.value)" name="fk_tipo_identificacion" id="fk_tipo_identificacion" <?php echo $disabled; ?>>
                                                        <option disabled value="" <?php echo (empty($cliente->fk_tipo_identificacion)) ? 'selected="selected"' : '' ?>>Seleccione... </option>
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
                                                <div class="col-md-3">
                                                    <label for="cedula">
                                                        <i class="fa fa-fw fa-book"></i> Número<small>(sin guiones)
                                                            <span style="color:red">*</span></small>
                                                    </label>
                                                    <input required placeholder="Nro Documento" autocomplete="off" type="text" name="cedula" id="cedula" class="form-control" onchange="validar_dni(event)" value="<?php echo $cliente->cedula; ?>" <?php echo $disabled; ?>>
                                                </div>

                                                <div class="col-md-3">
                                                    <label title="(sin guiones y/o espacios)" for="telefono">
                                                        <i class="fa fa-fw fa-phone"></i>
                                                        Teléfono
                                                    </label>
                                                    <input required placeholder="(sin guiones y/o espacios)" type="text" name="telefono" id="telefono" class="form-control" value="<?php echo $cliente->telefono; ?>" <?php echo $disabled; ?>>
                                                </div>
                                                <div class="col-md-3">
                                                    <label title="correo electrónico" for="email"><i class="fa fa-fw fa-envelope-o"></i>Email </label>
                                                    <input required placeholder="correo electrónico" type="text" id="email" name="email" class="form-control" value="<?php echo $cliente->email; ?>" <?php echo $disabled; ?>>
                                                </div>
                                            </div>
                                            <!-- Direccion del cliente - @rojasarmando - 13-06-2024 -->
                                            <div class="row mt-4">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="fk_pais"><i class="fa fa-globe" aria-hidden="true"></i> País <span>*</span></label>
                                                        <?php
                                                        $pais_selected = isset($cliente->fk_pais) ? $cliente->fk_pais : '';
                                                        ?>
                                                        <select required name="fk_pais" id="fk_pais" class="form-control" <?php echo $disabled; ?>>
                                                            <option value="">Seleccione el país</option>
                                                            <?php foreach ($paises as $pais) { ?>
                                                                <option value="<?php echo $pais->rowid; ?>" <?php echo (intval($pais_selected) === intval($pais->rowid)) ? 'selected' : ''; ?>><?php echo $pais->nombre; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="codigo_postal"><i class="fa fa-postcode" aria-hidden="true"></i> Código Postal</label>
                                                        <input required type="text" class="form-control mb-3" id="codigo_postal" name="codigo_postal" placeholder="Código Postal" <?php echo $disabled; ?> value="<?php echo $cliente->codigo_postal; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="poblacion"><i class="fa fa-city" aria-hidden="true"></i> CCAA <span>*</span></label>

                                                        <?php
                                                        $comunidad_seleccionada = isset($cliente->fk_poblacion) ? $cliente->fk_poblacion : '';
                                                        ?>

                                                        <select required name="poblacion" id="poblacion" class="form-control" <?php echo $disabled; ?>>
                                                            <option>Seleccionar CCAA</option>
                                                            <?php foreach ($poblaciones as  $key => $value) {
                                                            ?>
                                                                <option value="<?php echo $value->id; ?>" <?php echo (intval($comunidad_seleccionada) === intval($value->id)) ? 'selected' : ''; ?>>
                                                                    <?php echo $value->nombre; ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="direccion_fk_provincia"><i class="fa fa-flag" aria-hidden="true"></i> Provincia <span>*</span></label>

                                                        <?php
                                                        $provincia_seleccionada = isset($cliente->direccion_fk_provincia) ? $cliente->direccion_fk_provincia : '';
                                                        ?>

                                                        <select required name="direccion_fk_provincia" id="direccion_fk_provincia" class="form-control" <?php echo $disabled; ?>>
                                                            <option value="">Seleccione la provincia</option>
                                                            <?php foreach ($provincias as  $key => $value) {
                                                            ?>
                                                                <option value="<?php echo $value->id; ?>" <?php echo (intval($provincia_seleccionada) === intval($value->id)) ? 'selected' : ''; ?>>
                                                                    <?php echo $value->provincia; ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="direccion"><i class="fa fa-address-book" aria-hidden="true"></i> Dirección </label>

                                                        <textarea required id="direccion" name="direccion" class="form-control" style="width:100%;  height: 80px;" <?php echo $disabled; ?>><?php echo $cliente->direccion; ?></textarea>

                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /Direccion del cliente -->
                                            <div class="form-group row mt-4">
                                                <div class="col-md-6">

                                                    <label class="form-label">Banco</label>
                                                    <select id="nombre_banco" name="nombre_banco" id="nombre_banco" <?php echo $disabled; ?> class="form-control" value="<?php echo $cliente->nombre_banco; ?>">
                                                        <option value="" <?php echo $disabled; ?>>Seleccionar</option>
                                                        <?php foreach ($bancos as $banco) {

                                                            if ($cliente->nombre_banco == $banco->rowid) {
                                                                $s = 'selected="selected"';
                                                            } else {
                                                                $s = "";
                                                            }
                                                            echo '<option value="' . $banco->rowid . '" ' . $s . $disabled . '>' . $banco->nombre_banco . ' </option>';
                                                        }
                                                        ?>
                                                    </select>


                                                </div>

                                                <div class="col-md-6">
                                                    <label for="">Cuenta</label>
                                                    <div class="input-group">

                                                        <input type="text" class="form-control text-muted" maxlength="4" style="max-width:25%" value="<?php echo $cliente->banco_entidad ?>" name="banco_entidad" id="banco_entidad" placeholder="Entidad" <?php echo $disabled; ?>>

                                                        <input type="text" class="form-control text-muted" style="max-width:25%" value="<?php echo $cliente->banco_oficina ?>" placeholder="Oficina" name="banco_oficina" id="banco_oficina" maxlength="3" <?php echo $disabled; ?>>

                                                        <input type="text" placeholder="DC" class="form-control text-muted" style="max-width:15%" value="<?php echo $cliente->banco_digito_control ?>" name="banco_digito_control" maxlength="2" id="banco_digito_control" <?php echo $disabled; ?>>

                                                        <input type="text" class="form-control text-muted" value="<?php echo $cliente->banco_cuenta ?>" placeholder="Cuenta" name="banco_cuenta" id="banco_cuenta" maxlength="10" <?php echo $disabled; ?>>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row mt-4">

                                                <div class="col-md-6">
                                                    <label for="swift1">
                                                        <i class="fa fa-fw fa-folder-o"></i>
                                                        Swift 1
                                                    </label>
                                                    <input type="text" class="form-control text-muted" value="<?php echo $cliente->swift1 ?>" placeholder="Swift 1" name="swift1" id="swift1" <?php echo $disabled; ?>>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="swift2">
                                                        <i class="fa fa-fw fa-folder-o"></i>
                                                        Swift 2
                                                    </label>
                                                    <input type="text" class="form-control text-muted" value="<?php echo $cliente->swift2 ?>" placeholder="Swift 2" name="swift2" id="swift2" <?php echo $disabled; ?>>
                                                </div>
                                            </div>

                                            <div class="row mt-4">
                                                <div class="col-md-12">
                                                    <label for="nota">
                                                        <i class="fa fa-fw fa-folder-o"></i>
                                                        Nota
                                                    </label>
                                                    <textarea <?php echo $disabled; ?> class="form-control" name="nota" rows="3" placeholder="Detalle"><?php echo $cliente->nota; ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <?php
                                //dejar activos por defecto
                                if($labeltext === 'Proveedor'){
                                    
                                    if ($cliente->cliente != 1 && $cliente->proveedor != 1 && $cliente->activo != 1) {
                                        $cliente->proveedor = 1;
                                        $cliente->activo = 1;
                                    }
                                }else{

                                    if ($cliente->cliente != 1 && $cliente->proveedor != 1 && $cliente->activo != 1) {
                                        $cliente->cliente = 1;
                                        $cliente->activo = 1;
                                    }
                                }

                                ?>


                                <div class="col-md-3">
                                    <div class="card">
                                        <div class="card-header"> <i class="fa fa-fw  fa-gear"></i> Uso Interno </a>
                                        </div>

                                        <div class="card-body">
                                            <div class="col-md-12 mt-2">
                                                <div class="switch form-switch-custom switch-inline form-switch-primary">
                                                    <input class="switch-input" type="checkbox" role="switch" id="cliente" name="cliente" value="<?php echo $cliente->cliente; ?>" <?php echo ($cliente->cliente == 1) ? 'checked' : ''; ?> <?php echo $disabled; ?>>
                                                    <label class="switch-label" for="tosell">Cliente</label>
                                                </div>
                                            </div>

                                            <div class="col-md-12 mt-2">
                                                <div class="switch form-switch-custom switch-inline form-switch-primary">
                                                    <input class="switch-input" type="checkbox" role="switch" id="proveedor" name="proveedor" value="<?php echo $cliente->proveedor; ?>" <?php echo ($cliente->proveedor == 1) ? 'checked' : ''; ?> <?php echo $disabled; ?>>
                                                    <label class="switch-label" for="tosell">Proveedor</label>
                                                </div>
                                            </div>

                                            <div class="col-md-12 mt-2">
                                                <div class="switch form-switch-custom switch-inline form-switch-primary">
                                                    <input class="switch-input" type="checkbox" role="switch" id="activo" name="activo" value="<?php echo $cliente->activo; ?>" <?php echo ($cliente->activo == 1) ? 'checked' : ''; ?> <?php echo $disabled; ?>>
                                                    <label class="switch-label" for="tosell">Activo</label>
                                                </div>
                                            </div>

                                        </div><!-- /.card-body -->
                                    </div><!-- /.card -->




                                    <div id="logs-card" class="card" style="margin-top:15px;">
                                        <div class="card-header"> <i class="fa fa-fw  fa-gear"></i> Bitacora </a>
                                        </div>

                                        <div class="card-body">
                                            <div class="col-md-12 mt-2">
                                                <div class="switch form-switch-custom switch-inline form-switch-primary">
                                                <label for="tosell">Creador por: <br />
                                                    <span class="badge bg-primary">
                                                        <i class="fas fa-user"></i> <!-- Ícono de usuario -->
                                                        <?php echo $cliente->nombre_creador; ?>
                                                    </span>
                                                </label>

                                                <label for="tosell">Fecha registro: <br />
                                                <span class="badge bg-secondary">
                                                    <i class="fas fa-calendar-alt"></i> <!-- Ícono de calendario -->
                                                    <?php echo $cliente->creado_fecha; ?>
                                                </span>
                                            </label>
                                            </div>
                                            </div>

                                        </div><!-- /.card-body -->
                                    </div><!-- /.card -->


                                </div> <!-- col-md4 --->

                                <?php if (!empty($_REQUEST['fiche'])) { ?>
                                    <div class="row mt-3">
                                        <div class="col-md-12">

                                            <div class="card">

                                                <div class="card-header"> <i class="fa fa-book"></i> Otro Dato de Contacto </a>
                                                </div>





                                                <div class="card-body" id="zona_contacto">

                                                    <div class="row">

                                                    </div>

                                                    <table class="table style-3 dt-table-hover" id="tabla-contactos">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-left" scope="col">Tipo</th>
                                                                <th class="text-left" scope="col">Dato</th>
                                                                <th class="text-left" scope="col">Valor</th>
                                                                <th class="text-left" scope="col">Acciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="tbody" role="alert" aria-live="polite" aria-relevant="all" id="tbody" style="font-size:small;">
                                                        </tbody>
                                                    </table>

                                                </div><!-- /.card-body -->
                                            </div><!-- /.card -->
                                        </div><!-- /.col-md-4 -->
                                    </div><!-- div row -->
                                <?php } /*fin del IF */ ?>

                            </div>



                            <!-- Botones clientes -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card-footer mt-4">


                                        <a href="<?php echo ENLACE_WEB . $ruta_breadcumb; ?>" class="btn btn-outline-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-box">
                                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                                <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                                <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                            </svg>
                                            Volver al Listado
                                        </a>


                                        <?php if (!empty($_REQUEST['fiche'])) { ?>
                                            <button type="button" onclick="eliminar_tercero(event)" class="btn btn-danger">
                                                <i class="fa fa-fw fa-trash"></i>
                                                Eliminar
                                            </button>

                                        <?php } else { ?>
                                            <a href="<?php echo ENLACE_WEB . $ruta_breadcumb ?>" class="btn btn-primary"><i class="fa fa-fw fa-eraser"></i>Cancelar</a>
                                        <?php } ?>




                                        <?php if (empty($_REQUEST['action']) && !empty($_REQUEST['fiche'])) { ?>

                                            <a href="<?php echo ENLACE_WEB . $ruta_creacion ?>" class="btn btn-primary"><i class="fa fa-fw fa-plus"></i>
                                                Generar Otro <?php echo $texto_boton ?>
                                            </a>


                                            <a href="<?php echo ENLACE_WEB; ?>dashboard.php?accion=<?php echo $_GET['accion'] ?>&fiche=<?php echo $_REQUEST['fiche'] ?>&action=modify" class="btn btn-success">
                                                <i class="fa fa-fw fa-edit"></i> Modificar
                                            </a>

                                        <?php } ?>

                                        <?php if (empty($_REQUEST['action']) && empty($_REQUEST['fiche'])) { ?>
                                            <button type="button" id="creando_cliente" onclick="crear_tercero(event)" class="btn btn-primary">
                                                <i class="fa fa-fw fa-circle"></i>Crear <?php echo $texto_boton ?></button>
                                        <?php } ?>


                                        <?php if ($_REQUEST['action'] == "modify" && !empty($_REQUEST['fiche'])) { ?>
                                            <button type="button" onclick="modificar_tercero(event)" class="btn btn-primary">
                                                <i class="fa fa-fw fa-circle"></i>Guardar Cambios Contacto
                                            </button>
                                        <?php } ?>



                                    </div>

                                </div>
                            </div>

                        </div> <!-- end tab 1 -->

                        <!-- Contactos -->
                        <div class="tab-pane fade" id="pills-contactos" role="tabpanel" aria-labelledby="pills-contactos-tab" tabindex="0">
                            <!--  -->
                            <div class="middle-content container-xxl p-0">
                                <div class="row layout-spacing layout-top-spacing" id="cancel-row">
                                    <div class="col-lg-12">


                                        <!-- Tabla  -->
                                        <div class="table-responsive">
                                            <table id="contactos_crm_table" class="table style-3 dt-table-hover">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Nombre</th>
                                                        <th scope="col">Puesto</th>
                                                        <th scope="col">Email</th>
                                                        <th scope="col">RRSS</th>
                                                        <th scope="col">WhatsApp</th>
                                                        <th scope="col"></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="contactos_crm_body" role="alert" aria-live="polite" aria-relevant="all" id="contactos_crm_body" style="font-size:small;">
                                                </tbody>
                                            </table>
                                        </div>

                                        <!--Fin Tabla  -->

                                        <!-- Modal -->
                                        <div class="modal fade modal-lg" id="addContactModal" tabindex="-1" role="dialog" aria-labelledby="addContactModalTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title add-title" id="addContactModalTitleLabel1"><span id="modal_titulo"></span> Contacto</h5>

                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                                            <svg> ... </svg>
                                                        </button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <div class="add-contact-box">
                                                            <div class="add-contact-content">
                                                                <form id="addContactModalTitle">

                                                                    <div class="row">
                                                                        <div class="col-md-6 mb-3">
                                                                            <div class="contact-name">
                                                                                <label for="nombre_contacto"><i class="fa fa-fw fa-user"></i> Nombre</label>
                                                                                <input type="hidden" name="rowid_contacto" value="">
                                                                                <input required="required" type="text" name="nombre_contacto" class="form-control" value="" required placeholder="Nombre del contacto">
                                                                                <span class="validation-text"></span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6 mb-3">
                                                                            <div class="contact-email">
                                                                                <label for="apellidos_contacto"><i class="fa fa-fw fa-user"></i> Apellidos</label>
                                                                                <input type="text" name="apellidos_contacto" class="form-control" value="" placeholder="Apellido del contacto">
                                                                                <span class="validation-text"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-6 mb-3">
                                                                            <div class="contact-job">
                                                                                <label for="puesto_t"><i class="fa fa-fw fa-file"></i> Puesto</label>
                                                                                <input required="required" type="text" name="puesto_t" class="form-control" value="" required placeholder="Puesto del contacto">
                                                                                <span class="validation-text"></span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6 mb-3">
                                                                            <div class="contact-country">
                                                                                <label for="pais_c"><i class="fa fa-fw fa-flag"></i> Pais</label>
                                                                                <input type="text" name="pais_c" class="form-control" value="" placeholder="Pais del contacto">
                                                                                <span class="validation-text"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-6 mb-3">
                                                                            <div class="contact-name">
                                                                                <label for="fecha_nacimiento_contacto"><i class="fa fa-fw fa-calendar"></i> Fecha de Nacimiento</label>
                                                                                <input type="date" name="fecha_nacimiento_contacto" class="form-control" value="" placeholder="Fecha de nacimiento">
                                                                                <span class="validation-text"></span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6 mb-3">
                                                                            <div class="contact-name">
                                                                                <label for="email_contacto"><i class="fa fa-fw fa-envelope-o"></i> Email</label>
                                                                                <input type="text" name="email_contacto" class="form-control" value="" placeholder="Correo electrónico">
                                                                                <span class="validation-text"></span>
                                                                            </div>
                                                                        </div>
                                                                        <input type="hidden" name="fk_tercero_contacto" value="">
                                                                        <span class="validation-text"></span>
                                                                    </div>
                                                                    <div class="row">

                                                                        <div class="col-md-6 mb-3">
                                                                            <div class="contact-email">
                                                                                <label for="telefono_contacto"><i class="fa fa-fw fa-phone"></i> Teléfono</label>
                                                                                <input type="text" name="telefono_contacto" class="form-control" value="" placeholder="Teléfono del contacto">
                                                                                <span class="validation-text"></span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6 mb-3">
                                                                            <div class="contact-name">
                                                                                <label for="extension_contacto"><i class="fa fa-fw fa-phone"></i> Extensión</label>
                                                                                <input type="text" name="extension_contacto" class="form-control" value="" placeholder="Extensión del teléfono">
                                                                                <span class="validation-text"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">

                                                                        <div class="col-md-6 mb-3">
                                                                            <div class="contact-email">
                                                                                <label for="whatsapp_contacto"><i class="fa fa-fw fa-whatsapp"></i> WhatsApp</label>
                                                                                <input type="text" name="whatsapp_contacto" class="form-control" value="" placeholder="WhatsApp">
                                                                                <span class="validation-text"></span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6 mb-3">
                                                                            <div class="contact-name">
                                                                                <label for="facebook_contacto"><i class="fa fa-fw fa-facebook"></i> Facebook</label>
                                                                                <div class="input-group mb-3">
                                                                                    <span class="input-group-text" id="basic-addon1">https://facebook.com/</span>
                                                                                    <input type="text" class="form-control" name="facebook_contacto" id="basic-url" aria-describedby="basic-addon3" value="" placeholder="Usuario facebook">
                                                                                </div>
                                                                                <span class="validation-text"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">

                                                                        <div class="col-md-6 mb-3">
                                                                            <div class="contact-email">
                                                                                <label for="linkedin_contacto"><i class="fa fa-fw fa-linkedin"></i> LinkedIn</label>
                                                                                <div class="input-group mb-3">
                                                                                    <span class="input-group-text" id="basic-addon2">https://linkedin.com/in/</span>
                                                                                    <input type="text" class="form-control" name="linkedin_contacto" id="linkedin_contacto" aria-describedby="basic-addon3" value="" placeholder="Usuario linkedin">
                                                                                </div>
                                                                                <span class="validation-text"></span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6 mb-3">
                                                                            <div class="contact-name">
                                                                                <label for="instagram_contacto"><i class="fa fa-fw fa-instagram"></i> Instagram</label>
                                                                                <div class="input-group mb-3">
                                                                                    <span class="input-group-text" id="basic-addon3">https://instagram.com/</span>
                                                                                    <input type="text" class="form-control" name="instagram_contacto" id="instagram_contacto" aria-describedby="basic-addon3" value="" placeholder="Usuario instagram">
                                                                                </div>
                                                                                <span class="validation-text"></span>
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                    <div class="row">

                                                                        <div class="col-md-6 mb-3">
                                                                            <div class="contact-name">
                                                                                <label for="live-location"><i class="fa fa-map-marker "></i> Ubicacion</label>
                                                                                <div class="div basic-map" style="height: 300px; width: 370px; margin-right: 30px" id="live-location"></div>
                                                                                <input type="hidden" name="latitude" id="latitude" class="form-control" value="">
                                                                                <input type="hidden" name="longitud" id="longitud" class="form-control" value="">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6 mb-3">
                                                                            <div class="contact-email">
                                                                                <label for="x_twitter_contacto"><i class="fa fa-fw fa-twitter"></i> Twitter</label>
                                                                                <div class="input-group mb-3">
                                                                                    <span class="input-group-text" id="basic-addon4">https://twitter.com/</span>
                                                                                    <input type="text" class="form-control" name="x_twitter_contacto" id="x_twitter_contacto" aria-describedby="basic-addon3" value="" placeholder="Usuario twitter">
                                                                                </div>
                                                                                <span class="validation-text"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal"> <i class="flaticon-delete-1"></i> Cancelar</button>
                                                        <!-- <button type="button" id="boton_eliminar" class="btn btn-danger">Eliminar</button> -->
                                                        <button type="button" onclick="validar_accion();" id="boton_crear_txt" class="float-left btn btn-success">Crear</button>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                        <!-- Fin tab Contactos -->

                        <!-- IMPUESTOS -->
                        <div class="tab-pane fade" id="pills-impuestos" role="tabpanel" aria-labelledby="pills-impuestos-tab" tabindex="0">
                            <!--  -->
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="agente"><i class="fa fa-fw fa-user-tie"></i> Impuestos IVA</label>

                                            <!-- Inicio del formulario de impuestos -->
                                            <div class="form-group row mt-4">
                                                <div class="col-md-6">
                                                    <div>
                                                        <label for="nombre">
                                                            <span id="">¿Aplica Iva?</span>
                                                        </label>
                                                        <select class="form-control" name="regimen_iva" id="regimen_iva">
                                                            <?php
                                                            foreach ($lista_regimen as $key => $value) {
                                                                $selected = ($lista_regimen[$key]['rowid'] == $cliente->impuesto_cliente_fk_diccionario_regimen_iva) ? 'selected' : '';
                                                            ?>
                                                                <option value="<?php echo $lista_regimen[$key]['rowid']; ?>" <?php echo $selected; ?>><?php echo $lista_regimen[$key]['etiqueta']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <small>Regimen IVA</small>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div>
                                                        <label for="nombre">
                                                            <span id="">¿Aplica Recargo de equivalencia?</span>
                                                        </label>
                                                        <div class="switch form-switch-custom switch-inline form-switch-primary" style="display: block;">
                                                            <input class="switch-input" type="checkbox" role="switch" id="recargo_equivalencia"
                                                                name="impuesto_cliente_aplica_recargo_equivalencia"
                                                                <?php echo ($cliente->impuesto_cliente_aplica_recargo_equivalencia == 1) ? 'checked' : ''; ?>>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mt-4">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div style="display:flex;">
                                                <label for="agente"><i class="fa fa-briefcase" aria-hidden="true"></i> <strong>Retención al IRPF</strong></label>
                                                <div class="switch form-switch-custom switch-inline form-switch-primary" style="margin-left:10px;">
                                                    <input name="retencion" class="switch-input" type="checkbox" value="1" role="switch" id="retencion"
                                                        style="margin-top: -2px;"
                                                        <?php echo ($cliente->impuesto_cliente_lleva_retencion == 1) ? 'checked' : ''; ?>>
                                                </div>
                                            </div>

                                            <div class="form-group row mt-4" id="tipo_retencion_seccion">
                                                <div class="col-md-6">
                                                    <div>
                                                        <label for="country">Tipo de Retención</label>
                                                        <select name="tipo_retencion" class="form-control" id="tipo_retencion">
                                                            <option value="">Seleccionar</option>
                                                            <?php foreach ($listar_tipos_retencion as $key => $value) {
                                                                $selected = ($listar_tipos_retencion[$key]['rowid'] == $cliente->impuesto_cliente_regimen_iva_tipos_retencion) ? 'selected' : '';
                                                            ?>
                                                                <option value="<?php echo $listar_tipos_retencion[$key]['rowid']; ?>" <?php echo $selected; ?>><?php echo $listar_tipos_retencion[$key]['etiqueta']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="row mt-4">
                                <div class="col-md-3">
                                    <button type="button" class="btn btn-primary _effect--ripple waves-effect waves-light" onclick="actualizar_informacion_impuestos()">Guardar información</button>
                                </div>
                            </div>

                        </div>
                        <!-- CIERRE DEL TAB DE IMPUESTOS -->


                        <!-- INICIO DE TABS DE pills-condiciones-comerciales -->
                        <div class="tab-pane fade" id="pills-condiciones-comerciales" role="tabpanel" aria-labelledby="pills-condiciones-comerciales-tab" tabindex="0">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">

                                        <div class="col-md-12">
                                            <div class="form-group row mt-4">
                                                <div class="col-md-6">
                                                    <label for="agente"><i class="fa fa-fw fa-user-tie"></i> Agente </label>
                                                    <select class="form-control" name="fk_agente" id="fk_agente">
                                                        <option value="0"> No Asignado</option>
                                                        <?php foreach ($agentes as $agente) : ?>
                                                            <option <?php echo ($agente->rowid == $agente_actual->fk_agente) ? 'selected="selected"' : ''; ?> value="<?php echo $agente->rowid; ?>"><?php echo $agente->nombre; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="agente"><i class="fa fa-fw fa-user-tie"></i> Ruta </label>
                                                    <select class="form-control" name="fk_ruta" id="fk_ruta">
                                                        <option value="0"> No Asignado</option>
                                                        <?php foreach ($rutas as $ruta) : ?>
                                                            <option <?php echo ($ruta->rowid == $ruta_actual->fk_ruta) ? 'selected="selected"' : ''; ?> value="<?php echo $ruta->rowid; ?>"><?php echo $ruta->label; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>


                                        <div class="col-md-12">
                                            <div class="form-group row mt-4">
                                                <div class="col-md-6" style="display:none;">
                                                    <div>
                                                        <label for="nombre">
                                                            <span id="">Moneda del cliente</span>
                                                        </label>
                                                        <select class="form-control" name="codigo_moneda" id="codigo_moneda">
                                                            <option value="">Seleccione</option>
                                                            <?php
                                                            foreach ($listado_moneda as $key => $value) {
                                                                $selected = ($cliente->fk_moneda == $listado_moneda[$key]['rowid']) ? 'selected' : '';
                                                                echo '<option value="' . $listado_moneda[$key]['rowid'] . '" ' . $selected . '>' . $listado_moneda[$key]['etiqueta'] . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                        <small></small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div>
                                                        <label for="nombre">
                                                            <span id="">Limite de Crédito</span>
                                                        </label>
                                                        <input type="number" required class="form-control" name="limite_credito" id="limite_credito" value="<?php echo empty($cliente->limite_credito)?0: $cliente->limite_credito ; ?>">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div>
                                                        <label for="nombre">
                                                            <span id="">Saldo Crédito</span>
                                                        </label>
                                                        <input type="readonly" disabled class="form-control" name="saldo_credito" id="saldo_credito" value="<?php echo $cliente->saldo_credito; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group row mt-4">
                                                <div class="col-md-6">
                                                    <div>
                                                        <label for="nombre">
                                                            <span id="">Lista de Precios</span>
                                                        </label>
                                                        <select class="form-control"   name="fk_lista" id="fk_lista">
                                                            <option value="">Seleccione</option>
                                                            <?php
                                                            foreach ($listado_precios as $key => $value) {
                                                                $selected = ($cliente->fk_lista_precio == $listado_precios[$key]['rowid']) ? 'selected' : '';
                                                                echo '<option value="' . $listado_precios[$key]['rowid'] . '" ' . $selected . '>' . $listado_precios[$key]['etiqueta'] . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                        <small></small>
                                                    </div>
                                                </div>
                                                            
                                                
                                                <div class="col-md-6">
                                                    <div>
                                                        <label for="nombre" style="display:block;">
                                                            <span id="">Credito Cerrado</span>
                                                        </label>
                                                        <div class="switch form-switch-custom switch-inline form-switch-primary" style="margin-left:10px;">
                                                            <input name="credito_cerrado" class="switch-input" type="checkbox" value="1" role="switch" id="credito_cerrado" <?php echo $cliente->credito_cerrado ? 'checked' : ''; ?>>
                                                        </div>
                                                    </div>
                                                </div>

                                            
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group row mt-4">
                                                <div class="col-md-6">
                                                    <div>
                                                        <label for="nombre">
                                                            <span id="">Forma de pago</span>
                                                        </label>

                                                        <select required class="form-control" name="fk_forma_pago" id="fk_forma_pago">
                                                            <option value="">Seleccione</option>
                                                            <?php
                                                            foreach ($lista_formas_pago as $forma_pago_data) {
                                                                $selected = ($cliente->forma_pago == $forma_pago_data->rowid) ? 'selected' : '';
                                                                echo '<option value="' . $forma_pago_data->rowid . '" ' . $selected . '>' . $forma_pago_data->label . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                        <small></small>
                                                    </div>
                                                </div>
                                                            
                                                <div class="col-md-6">
                                                    <div>
                                                        <label for="nombre" style="display:block;">
                                                            <span id="">Cliente Moroso</span>
                                                        </label>
                                                        <div class="switch form-switch-custom switch-inline form-switch-primary" style="margin-left:10px;">
                                                            <input name="cliente_moroso" class="switch-input" type="checkbox" value="1" role="switch" id="cliente_moroso" <?php echo $cliente->moroso ? 'checked' : ''; ?>>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group row mt-4">
                                                <div class="col-md-6">
                                                    <div>
                                                        <label for="dia_pago" style="display:block;">
                                                            <span id="">Días de pago</span>
                                                        </label>
                                                        <select   class="form-control" name="dia_pago[]" id="dia_pago" multiple="multiple" style="width:100%;">
                                                            <option value="">Seleccione</option>
                                                            <?php
                                                            // Convertir la cadena de días separados por comas en un array
                                                            $dias_cliente = explode(',', $cliente->dia_pago);

                                                            $dias_semana = ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo"];
                                                            // Días del 1 al 31
                                                            for ($dia = 0; $dia <= 6; $dia++) {
                                                                // Verificar si el día actual está en el array de días del cliente
                                                                $selected = in_array($dias_semana[$dia], $dias_cliente) ? 'selected' : '';
                                                                echo "<option value='$dias_semana[$dia]' $selected>$dias_semana[$dia]</option>";
                                                            }
                                                            ?>
                                                        </select>

                                                        <small></small>
                                                    </div>

                                                </div>

                                                
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group row mt-4">
                                                <div class="col-md-6">
                                                    <div>
                                                        <label for="mes_no_pago" style="display:block;">
                                                            <span id="">Mes no pago</span>
                                                        </label>
                                                        <select   class="form-control" name="mes_no_pago[]" id="mes_no_pago" multiple="multiple" style="width:100%;">
                                                            <option value="">Seleccione</option>
                                                            <?php
                                                            // Meses del año
                                                            $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

                                                            // Convertir la cadena de meses separados por comas en un array
                                                            $meses_cliente = explode(',', $cliente->mes_no_pago);
                                                            // Generar las opciones
                                                            foreach ($meses as $index => $mes) {
                                                                // Verificar si el mes actual está en el array de meses del cliente
                                                                $selected = in_array($index + 1, $meses_cliente) ? 'selected' : '';
                                                                echo "<option value='" . ($index + 1) . "' $selected>$mes</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                        <small></small>
                                                    </div>
                                                </div>

                                                 
                                            </div>
                                        </div>

 

                                        <div class="col-md-12" id="motivo_cierre_seccion" <?php if (intval($cliente->credito_cerrado) === 0) { ?> style="display:none;" <?php } ?>>
                                            <div class="form-group row mt-4">
                                                <div class="col-md-6">
                                                    <div>
                                                        <label for="nombre" style="display:block;">
                                                            <span id="">Motivo de cierre</span>
                                                        </label>
                                                        <textarea class="form-control" id="motivo_cierre" name="motivo_cierre" rows="3"><?php echo $cliente->motivo_cierre; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-md-3">
                                            <button type="button" class="btn btn-primary" onclick="actualizar_condiciones_comerciales(event)">Guardar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- FIN DE TABS DE pills-condiciones-comerciales -->
                    </div>
            </div><!-- end tab content -->

            </section>
        </div>

</div>
</div>

</div>

<input type="hidden" id="correo_existe">
<input type="hidden" id="cedula_existe">

<?php include_once ENLACE_SERVIDOR . 'mod_terceros/tpl/cliente_contacto_modal.php'; ?>


<script>
    $(document).ready(function() {


        $("#credito_cerrado").change(function() {
            if ($(this).is(':checked')) {
                $("#motivo_cierre_seccion").fadeIn(200);
            } else {
                $("#motivo_cierre_seccion").fadeOut(200);
                $("#motivo_cierre").text("").val("");
            }

        });

        new TomSelect("#dia_pago", {
            maxItems: 3
        });
        new TomSelect("#mes_no_pago", {
            maxItems: 12
        });

        $("#retencion").change(function() {
            hide_show_retencion_seccion()
        });

        function hide_show_retencion_seccion() {
            if ($("#retencion").is(':checked') === true) {
                $("#tipo_retencion_seccion").fadeIn(500);
            } else {
                $("#tipo_retencion_seccion").hide(0);
            }
        }

        hide_show_retencion_seccion()

        $(".menu").removeClass('active');
        labeltext = '<?php echo $labeltext ; ?>';
        if(labeltext === 'Proveedor')
        {
            $(".proveedores").addClass('active');
        }else{
            $(".clientes").addClass('active');

        }

        $('#telefono').on('input', function() {
            // Eliminar cualquier carácter que no sea un dígito
            $(this).val($(this).val().replace(/[^\d]/g, ''));
        });

        // Manejar pegado para asegurarse de que no se introduzcan caracteres no deseados
        $('#telefono').on('paste', function(e) {
            // Prevenir el comportamiento de pegado predeterminado
            e.preventDefault();
            // Acceder al texto copiado del clipboard
            var texto = (e.originalEvent || e).clipboardData.getData('text/plain');
            // Filtrar el texto copiado y solo permitir números
            var textoFiltrado = texto.replace(/[^\d]/g, '');
            // Pegar el texto filtrado en el campo
            document.execCommand("insertText", false, textoFiltrado);
        });

        //DNVT - FUncionalidad para dejar seleccionado un elemento sea cliente o proveedor o ambos mas 1 debe estar seleccionado obligatoriamente
        $('#cliente, #proveedor').change(function() {
            const clienteChecked = $('#cliente').is(':checked');
            const proveedorChecked = $('#proveedor').is(':checked');

            // Si se desmarca "cliente" y "proveedor" no está marcado, marcar "proveedor"
            if (!clienteChecked && !proveedorChecked) {
                if ($(this).is('#cliente')) {
                    $('#proveedor').prop('checked', true); // Marca "proveedor"
                } else {
                    $('#cliente').prop('checked', true); // Marca "cliente"
                }
            }
        });

    });
</script>


<!-- ------------------------------------- Mejora para Buscar Cedula y que Cargue lo demas ------------------- -->
<script src="<?php echo ENLACE_WEB ?>bootstrap/assets/js/apps/contact.js?v=<?php echo time(); ?>"></script>
<?php require_once ENLACE_SERVIDOR . 'mod_terceros/tpl/nuevo_cliente_scripts.php';
?>