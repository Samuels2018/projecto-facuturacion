<?php

require_once ENLACE_SERVIDOR . 'mod_utilidad/object/utilidades.object.php';
require_once ENLACE_SERVIDOR . 'mod_impuestos/object/impuestos_object.php';

// Impuestos(
$Utilidades = new Utilidades($dbh);
$impuestos = new impuestos($dbh, $_SESSION['Entidad']);
$Entidad = new Entidad($dbh, $_SESSION['Entidad']);

$idiomas =  $Lan->idiomas;
// $list_provincias = $provincias->provincias;
//diccionario_comunidades_autonomas_provincias
//jalamos la data
$Usuarios->buscar_data_usuario($_SESSION['usuario']);


$paises = $Utilidades->obtener_paises();
// $municipios =  $Utilidades->obtener_municipios($Entidad->direccion_fk_provincia);
$lista_impuestos = $impuestos->listar_impuestos();
// $ubigeo_seleccionado = $Utilidades->obtener_ubigeo_seleccionado($Entidad->direccion_fk_provincia);

$codigo_pais = $Entidad->direccion_fk_pais;
$codigo_ccaa = $Entidad->direccion_fk_ccaa;
$codigo_provincia = $Entidad->direccion_fk_provincia;
$codigo_municipio = $Entidad->direccion_fk_municipio;

$lista_identificacion_fiscal = $Utilidades->obtener_identificaciones_fiscales();

?>

<link rel="stylesheet" href="https://designreset.com/cork/html/src/assets/css/light/users/account-setting.css">
<link rel="stylesheet" href="https://unpkg.com/filepond@4.31.1/dist/filepond.css">
<link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">


<style type="text/css">
    label {
        margin-bottom: 15px !important;
    }
</style>


<div class="middle-content container-xxl p-0">
    <!-- BREADCRUMB -->
    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Usuarios</a></li>
                <li class="breadcrumb-item active" aria-current="page">Mi Empresa</li>
            </ol>
        </nav>
    </div>
    <!-- /BREADCRUMB -->
    <div class="account-settings-container layout-top-spacing">
        <div class="account-content">
            <div class="row mb-3">
                <div class="col-md-12">
                    <h2>Mi Empresa</h2>
                    <ul class="nav nav-pills" id="animateLine" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="animated-underline-home-tab" data-bs-toggle="tab" href="#animated-underline-home" role="tab" aria-controls="animated-underline-home" aria-selected="true"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>Datos</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="animated-underline-profile-tab" data-bs-toggle="tab" href="#animated-underline-profile" role="tab" aria-controls="animated-underline-profile" aria-selected="false" tabindex="-1"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign">
                                    <line x1="12" y1="1" x2="12" y2="23"></line>
                                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                </svg>Configuración de Impuestos</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="animated-underline-preferences-tab" data-bs-toggle="tab" href="#animated-underline-preferences" role="tab" aria-controls="animated-underline-preferences" aria-selected="false" tabindex="-1"><i class="fas fa-gear" aria-hidden="true"></i>Preferencias</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="animated-underline-verifact-tab" data-bs-toggle="tab" href="#animated-underline-verifact" role="tab" aria-controls="animated-underline-verifact" aria-selected="false" tabindex="-1"><i class="fas fa-network-wired" aria-hidden="true"></i>Verifactum</button>
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
                                    <h6 class=""><i class="fa fa-info-circle" aria-hidden="true"></i> Información General</h6>
                                    <div class="row">
                                        <div class="col-lg-11 mx-auto">
                                            <div class="row">
                                                <div class="col-xl-2 col-lg-12 col-md-4">
                                                    <div class="profile-image mt-4 pe-md-4">
                                                        <div class="img-uploader-content">
                                                            <div class="filepond--root filepond filepond--hopper" data-style-panel-layout="compact circle" data-style-button-remove-item-position="left bottom" data-style-button-process-item-position="right bottom" data-style-load-indicator-position="center bottom" data-style-progress-indicator-position="right bottom" data-style-button-remove-item-align="false" style="height: 120px;">
                                                                <input type="file" class="filepond" name="filepond">
                                                                <a class="filepond--credits" aria-hidden="true" href="https://pqina.nl/" target="_blank" rel="noopener noreferrer" style="transform: translateY(120px);">Powered by PQINA</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-10 col-lg-12 col-md-8 mt-md-0 mt-4">

                                                    <!-- Información de Identificación y Residencia -->
                                                    <div class="form">
                                                        <!-- Subtítulo: Datos de la Empresa -->
                                                        <h6 class="mt-3"><i class="fa fa-building" aria-hidden="true"></i> Datos de la Empresa</h6>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="tipo_persona"><i class="fa fa-user" aria-hidden="true"></i> Tipo Persona</label>
                                                                    <select name="tipo_persona" id="tipo_persona" class="form-control">
                                                                        <option value="1" <?php echo ($Entidad->tipo === 'fisica') ? 'selected' : ''; ?>>Física</option>
                                                                        <option value="2" <?php echo ($Entidad->tipo === 'juridica') ? 'selected' : ''; ?>>Jurídica</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <!-- Tipo de Residencia -->
                                                            <div class="form-group col-md-6">
                                                                <label for="tipo_residencia"><i class="fa fa-map-marker" aria-hidden="true"></i> Tipo de Residencia <span>*</span></label>
                                                                <select name="tipo_residencia" id="tipo_residencia" class="form-control">
                                                                    <option value="">Seleccione el tipo de residencia</option>
                                                                    <option value="E" <?php echo ($Entidad->tipo_residencia === 'E') ? 'selected' : ''; ?>>Extranjero</option>
                                                                    <option value="R" <?php echo ($Entidad->tipo_residencia === 'R') ? 'selected' : ''; ?>>Residente</option>
                                                                    <option value="RUE" <?php echo ($Entidad->tipo_residencia === 'RUE') ? 'selected' : ''; ?>>Residente en la UE</option>
                                                                </select>
                                                            </div>

                                                            <!-- Tipo de Identificación Fiscal -->
                                                            <div class="form-group col-md-6">
                                                                <label for="tipo_identificacion_fiscal"><i class="fa fa-id-card" aria-hidden="true"></i> Tipo de Identificación Fiscal <span>*</span></label>
                                                                <select name="tipo_identificacion_fiscal" id="tipo_identificacion_fiscal" class="form-control">
                                                                    <option value="">Seleccione el tipo de identificación fiscal</option>
                                                                    <?php
                                                                    foreach ($lista_identificacion_fiscal as $identificacion_fiscal) {
                                                                    ?>
                                                                        <option <?php echo (intval($Entidad->fk_tipo_identificacion_fiscal) === intval($identificacion_fiscal->rowid)) ? 'selected' : ''; ?> value="<?php echo $identificacion_fiscal->rowid; ?>"><?php echo $identificacion_fiscal->descripcion; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>

                                                            <!-- Número de Identificación -->
                                                            <div class="form-group col-md-6">
                                                                <label for="numero_identificacion"><i class="fa fa-id-card" aria-hidden="true"></i> Número de Identificación <span>*</span></label>
                                                                <input type="text" class="form-control" id="numero_identificacion" name="numero_identificacion" placeholder="Número de Identificación" value="<?php echo $Entidad->numero_identificacion; ?>">
                                                            </div>


                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="razon_social"><i class="fa fa-id-card" aria-hidden="true"></i> Razon Social (Nombre fiscal) <span>*</span></label>
                                                                    <input type="text" class="form-control mb-3" id="razon_social" name="razon_social" placeholder="Razón Social" value="<?php echo $Entidad->nombre_empresa; ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6" id="nombre_field">
                                                                <div class="form-group">
                                                                    <label for="nombre"><i class="fa fa-user" aria-hidden="true"></i> Nombre <span>*</span></label>
                                                                    <input type="text" class="form-control mb-3" id="persona_nombre" name="persona_nombre" placeholder="nombre" value="<?php echo $Entidad->persona_nombre; ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6" id="apellido1_field">
                                                                <div class="form-group">
                                                                    <label for="apellido1"><i class="fa fa-user" aria-hidden="true"></i> Apellido 1</label>
                                                                    <input type="text" class="form-control mb-3" id="apellido1" name="apellido1" placeholder="Apellido 1" value="<?php echo $Entidad->persona_apellido1; ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6" id="apellido2_field">
                                                                <div class="form-group">
                                                                    <label for="apellido2"><i class="fa fa-user" aria-hidden="true"></i> Apellido 2</label>
                                                                    <input type="text" class="form-control mb-3" id="apellido2" name="apellido2" placeholder="Apellido 2" value="<?php echo $Entidad->persona_apellido2; ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6" id="nombre_completo_field">
                                                                <div class="form-group">
                                                                    <label for="nombre_fantasia"><i class="fa fa-user" aria-hidden="true"></i> Nombre Comercial</label>
                                                                    <input type="text" class="form-control mb-3" id="nombre_fantasia" name="nombre_fantasia" placeholder="Nombre Comercial" value="<?php echo $Entidad->nombre_fantasia; ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="telefono_fijo"><i class="fa fa-phone" aria-hidden="true"></i> Teléfono <span>*</span></label>
                                                                    <input type="text" class="form-control mb-3" id="telefono_fijo" name="telefono_fijo" placeholder="Ingresar número" value="<?php echo $Entidad->telefono_fijo; ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="telefono_movil"><i class="fa fa-mobile" aria-hidden="true"></i> Teléfono Móvil <span>*</span></label>
                                                                    <input type="text" class="form-control mb-3" id="telefono_movil" name="telefono_movil" placeholder="Ingresar Teléfono Móvil" value="<?php echo $Entidad->telefono_movil; ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="email"><i class="fa fa-envelope" aria-hidden="true"></i> Correo Electrónico <span>*</span></label>
                                                                    <input type="email" class="form-control mb-3" id="email" name="email" placeholder="Ingresar correo electrónico" value="<?php echo $Entidad->correo_electronico; ?>">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <hr style="margin-top: 15px; margin-bottom: 15px;">
                                                        <!-- Subtítulo: Direcciones -->
                                                        <h6 class="mt-3"><i class="fa fa-map-marker" aria-hidden="true"></i> Direcciones</h6>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="direccion_fk_pais"><i class="fa fa-globe" aria-hidden="true"></i> País <span>*</span></label>
                                                                    <select name="direccion_fk_pais" id="direccion_fk_pais" class="form-control">
                                                                        <option value="">Seleccione el país</option>
                                                                        <?php foreach ($paises as $pais) { ?>
                                                                            <option value="<?php echo $pais->rowid; ?>" <?php echo ($codigo_pais == $pais->rowid ? 'selected' : ''); ?>><?php echo $pais->nombre; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="codigo_postal"><i class="fa fa-postcode" aria-hidden="true"></i> Código Postal</label>
                                                                    <input type="text" class="form-control mb-3" id="codigo_postal" name="codigo_postal" placeholder="Código Postal" value="<?php echo $Entidad->codigo_postal; ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="direccion_fk_ccaa"><i class="fa fa-city" aria-hidden="true"></i> CCAA <span>*</span></label>

                                                                    <select name="direccion_fk_ccaa" id="direccion_fk_ccaa" class="form-control">
                                                                        <option value="">Seleccionar CCAA</option>

                                                                    </select>


                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="direccion_fk_provincia"><i class="fa fa-flag" aria-hidden="true"></i> Provincia <span>*</span></label>
                                                                    <select name="direccion_fk_provincia" id="direccion_fk_provincia" class="form-control">
                                                                        <option value="">Seleccione la provincia</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="direccion_fk_municipio"><i class="fa-solid fa-location-pin"></i> Municipio <span>*</span></label>
                                                                    <select name="direccion_fk_municipio" id="direccion_fk_municipio" class="form-control">
                                                                        <option value="">Seleccione el municipio</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="direccion"><i class="fa fa-address-book" aria-hidden="true"></i> Dirección </label>

                                                                    <textarea id="nombre_direccion" name="nombre_direccion" class="form-control" style="width:100%;  height: 80px;"><?php echo $Entidad->nombre_direccion; ?></textarea>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Botón Guardar -->
                                        <div class="col-12 text-center mt-4">
                                            <button type="submit" class="btn btn-primary"><i class="fa fa-save" aria-hidden="true"></i> Guardar</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="animated-underline-profile" role="tabpanel" aria-labelledby="animated-underline-profile-tab">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing section general-info ">
                            <!-- CONFIGURACION DE IMPUESTOS-->
                            <div class="info">
                                <h6 class="">Configuración de impuestos IVA</h6>
                                <div class="row">
                                    <div class="col-lg-11 mx-auto">
                                        <div class="row">
                                            <div class="col-xl-12 col-lg-12 col-md-8 mt-md-0 mt-4">
                                                <!-- TABLA DE INPUESTOS DE LA EMPRESA-->
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center">Tipo de Iva</th>
                                                                <th class="text-center">% IVA</th>
                                                                <th class="text-center" scope="col">Recargo Equivalencias</th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="lista_impuestos">

                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="form-group text-end">
                                                    <button type="button" class="btn btn-primary _effect--ripple waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#impuestoModal">Agregar otro impuesto</button>
                                                </div>

                                                <!-- IMPUESTOS DE LA EMPRESA-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- CIERRE DE LA CONFIGURACION DE IMPUESTOS--->
                            <!-- RETENCION DEL IRPF-->
                            <div class="info" id="formulario_retenciones">
                                <div class="row">
                                    <h6 class="">Retención al IRPF</h6>
                                </div>
                                <div class="row">
                                    <div class="col-lg-11 mx-auto">
                                        <div class="row">
                                            <div class="col-xl-12 col-lg-12 col-md-8 mt-md-0 mt-4">
                                                <!-- CAMPOS DE RETENCION AQUI-->
                                                <div class="row">
                                                    <div class="col-md-4 form-group" style="display: flex;">
                                                        <label for="country">¿Aplica retención al IRPF?</label>
                                                        <?php
                                                        $checked = '';
                                                        if (intval($Entidad->retencion) === 1) {
                                                            $checked = 'checked';
                                                        }
                                                        ?>
                                                        <div class="switch form-switch-custom switch-inline form-switch-primary">
                                                            <input name="retencion" class="switch-input" type="checkbox" value="1" role="switch" id="retencion" style="margin-left: 5px; " <?php echo $checked; ?>>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 form-group porcentaje_retencion_section">
                                                        <div class="input-group mb-3">
                                                            <span class="input-group-text" id="basic-addon1">Porcentaje de retención:</span>
                                                            <input type="number" class="form-control" placeholder="Ej 15" aria-label="Retencion" aria-describedby="basic-addon1" name="retencion_porcentaje" id="retencion_porcentaje" value="<?php echo  $Entidad->retencion_porcentaje; ?>">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4 form-group porcentaje_retencion_section">
                                                        <div class="input-group mb-3">
                                                            <span class="input-group-text" id="basic-addon1">Rigue hasta:</span>
                                                            <input type="date" class="form-control" placeholder="Ej 15" aria-describedby="basic-addon1" name="retencion_porcentaje_rigue_hasta" id="retencion_porcentaje_rigue_hasta" value="<?php echo  $Entidad->retencion_porcentaje_rigue_hasta; ?>">
                                                        </div>
                                                    </div>


                                                </div>
                                                <div class="col-md-12 mt-1">
                                                    <div class="form-group text-end">
                                                        <button type="submit" class="btn btn-secondary _effect--ripple waves-effect waves-light" Onclick="formulario_retenciones()">Guardar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- CIERRE DE LA SECCIÓN DE RETENCIONES IRPF-->
                            </div>
                        </div>
                    </div>

                </div>

                <div class="tab-pane fade" id="animated-underline-preferences" role="tabpanel" aria-labelledby="animated-underline-preferences-tab">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                            <form method="POST" class="section general-info" id="formulario_retenciones">
                                <div class="info">
                                    <div class="row">
                                        <div class="col-6">
                                            <h6 class="">Descargar Logs</h6>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="fechaDesde">Fecha desde:</label>
                                                        <input type="text" class="form-control fecha" id="fechaDesde">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="fechaHasta">Fecha hasta:</label>
                                                        <input type="text" class="form-control fecha" id="fechaHasta">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group mt-4">
                                                <button onclick="descargar_logs()" class="btn btn-primary _effect--ripple waves-effect waves-light">Descargar</button>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <h6 class="">Descarga Transacciones</h6>

                                            <div class="form-group mt-4">
                                                <button onclick="descargar_logs_tran()" class="btn btn-primary _effect--ripple waves-effect waves-light">Descargar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
                <div class="tab-pane fade" id="animated-underline-verifact" role="tabpanel" aria-labelledby="animated-underline-verifact-tab">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                            <form method="POST" class="section general-info" id="formulario_verifactum">
                                <div class="info">
                                    <div class="row">
                                        <div class="col-4">
                                            <h6 class="">Certificado</h6>
                                            <div class="form-group mt-4">
                                                <input class="form-control" type="file" id="certificadofile" name="certificadofile">
                                                <?php if ($datos_empresa["electronica_certificado"] != '') { ?>
                                                    <div class="from-control">Archivo PEM cargado: <?php echo $datos_empresa["electronica_certificado"]; ?> </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <!-- <div class="col-4">
                                            <h6 class="">Certificado Encriptado</h6>
                                            <div class="form-group mt-4">
                                                <input class="form-control" type="file" id="certificadofile_encriptado" name="certificadofile_encriptado">
                                                <?php if ($datos_empresa["electronica_certificado_encriptado"] != '') { ?>
                                                <div class="from-control">Archivo P12 cargado</div>
                                                <?php } ?>
                                            </div>
                                        </div> -->
                                        <!-- <div class="col-4">
                                            <h6 class="">Clave de certificado</h6>                                            
                                            <div class="form-group mt-4">
                                                <input type="password" class="form-control" id="clave" name="clave" placeholder="Clave de certificado" value="<?php echo $Entidad->electronica_certificado_clave; ?>">
                                            </div>
                                        </div> -->
                                        <div class="col-4">
                                            <h6 class="">Clave de certificado</h6>
                                            <div class="form-group mt-4 position-relative">
                                                <input type="password" class="form-control" id="clave" name="clave" placeholder="Clave de certificado" value="<?php echo $datos_empresa['electronica_certificado_clave']; ?>">
                                                <span class="position-absolute top-50 end-0 translate-middle-y me-3 cursor-pointer" onclick="togglePasswordVisibility()">
                                                    <i class="fa fa-eye" id="togglePasswordIcon"></i>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-4">
                                            <h6 class="">Cambio de entorno</h6>

                                            <div class="form-group mt-4">
                                                <select name="verifact" id="verifact" class="form-control">
                                                    <option value="0" <?php echo ($datos_empresa['verifactum_produccion'] == 0) ? 'selected' : ''; ?>>Sin Verifactum</option>
                                                    <option value="1" <?php echo ($datos_empresa['verifactum_produccion'] == 1) ? 'selected' : ''; ?>>En Desarrollo</option>
                                                    <option style="color:red;" value="3" <?php echo ($datos_empresa['verifactum_produccion'] == 3) ? 'selected' : ''; ?>>En Produccion</option>
                                                </select>
                                                <p>Configurar a la empresa para que permita generar Facturas con VeriFact</p>

                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-12 text-center mt-4">
                                            <button type="submit" class="btn btn-primary"><i class="fa fa-save" aria-hidden="true"></i> Guardar</button>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <?php
    require ENLACE_SERVIDOR . 'mod_empresa/tpl/modal_impuestos_iva.php';
    ?>




    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.3/themes/base/jquery-ui.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js" integrity="sha256-0YPKAwZP7Mp3ALMRVB2i8GXeEndvCq3eSl/WsAl1Ryk=" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>


    <script>
        $(document).ready(function() {
            var contador = 0;
            // Registramos FilePond y el plugin de vista previa
            FilePond.registerPlugin(FilePondPluginImagePreview);
            var user_file = '<?php echo $Entidad->obtener_url_avatar_encriptada($_SESSION['Entidad']); ?>';
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
                    url: '<?= ENLACE_WEB ?>/mod_empresa/class/clases.php',
                    method: 'POST',
                    process: {
                        ondata: (formData) => {
                            formData.append('action', 'actualizarAvatar');
                            formData.append('entidad', <?= $_SESSION['Entidad'] ?>);
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
                            $('.profile-img img').prop('src', url_encriptada);
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
                url: '<?= ENLACE_WEB ?>/mod_empresa/class/clases.php',
                data: {
                    action: 'limpiarAvatar'
                },
                success: function(response) {
                    const user_response = JSON.parse(response);
                    if(user_response.status == 'success'){
                        const url_encriptada = user_response.url_encriptada;
                        $('.profile-img img').prop('src', url_encriptada);
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
            $("#fechaDesde").datepicker({
                dateFormat: "yy-mm-dd",
                showButtonPanel: false,
                changeMonth: false,
                changeYear: false,
                inline: true
            });
            $("#fechaHasta").datepicker({
                dateFormat: "yy-mm-dd",
                showButtonPanel: false,
                changeMonth: false,
                changeYear: false,
                inline: true
            });
            $("#fechaDesdeTran").datepicker({
                dateFormat: "yy-mm-dd",
                showButtonPanel: false,
                changeMonth: false,
                changeYear: false,
                inline: true
            });
            $("#fechaHastaTran").datepicker({
                dateFormat: "yy-mm-dd",
                showButtonPanel: false,
                changeMonth: false,
                changeYear: false,
                inline: true
            });
        });
        $.datepicker.regional['es'] = {
            closeText: 'Cerrar',
            prevText: '<Ant',
            nextText: 'Sig>',
            currentText: 'Hoy',
            monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
            dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
            dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
            weekHeader: 'Sm',
            dateFormat: 'dd/mm/yy',
            firstDay: 1,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ''
        };
        $.datepicker.setDefaults($.datepicker.regional['es']);

        function descargar_logs() {
            event.preventDefault();

            // Obtener los datos del formulario
            const fecha_inicio = $('#fechaDesde').val();
            const fecha_fin = $('#fechaHasta').val();

            // Hacer la solicitud AJAX
            $.ajax({
                url: "<?php echo ENLACE_WEB; ?>mod_logs/ConsultaLog.php",
                method: 'GET',
                data: {
                    fecha_inicio,
                    fecha_fin
                },
                dataType: 'json',
                success: function(response) {
                    // Mostrar los datos obtenidos
                    if (response.message && response.message != '') {
                        const linkSource = `data:application/csv;base64,${response.message}`;
                        const downloadLink = document.createElement("a");
                        const fileName = response.filename;

                        downloadLink.href = linkSource;
                        downloadLink.download = fileName;
                        downloadLink.click();
                    } else {
                        add_notification({
                            text: 'No existe información a generar',
                            actionTextColor: '#fff',
                            backgroundColor: '#e7515a',
                            dismissText: 'Cerrar'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    $('#resultado').text(`Error: ${error}`);
                }
            });
        }

        function descargar_logs_tran() {
            event.preventDefault();

            // Obtener los datos del formulario
            const fecha_inicio = $('#fechaDesde').val();
            const fecha_fin = $('#fechaHasta').val();

            // Hacer la solicitud AJAX
            $.ajax({
                url: "<?php echo ENLACE_WEB; ?>mod_logs/ConsultaLogTran.php",
                method: 'GET',
                data: {
                    fecha_inicio,
                    fecha_fin
                },
                dataType: 'json',
                success: function(response) {
                    // Mostrar los datos obtenidos
                    if (response.message != '') {
                        add_notification({
                            text: "Se ha generado la información. Terminando la generación se enviará un correo con el adjunto",
                            actionTextColor: '#fff',
                            backgroundColor: '#00ab55',
                            dismissText: 'Cerrar'
                        });
                    } else {
                        add_notification({
                            text: 'No existe información a generar',
                            actionTextColor: '#fff',
                            backgroundColor: '#e7515a',
                            dismissText: 'Cerrar'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    add_notification({
                        text: error,
                        actionTextColor: '#fff',
                        backgroundColor: '#e7515a',
                        dismissText: 'Cerrar'
                    });
                }
            });
        }
    </script>

    <script type="text/javascript">
        jQuery(document).ready(function($) {

            function listar_impuestos() {

                $.ajax({
                    type: 'POST',
                    url: '<?= ENLACE_WEB ?>mod_impuestos/ajax/listado_impuestos_ajax.php',
                    success: function(response) {

                        $("#lista_impuestos").html(response);

                    },
                    error: function(xhr, status, error) {
                        // Manejamos los errores de la solicitud AJAX
                        console.error(error);
                        // Puedes mostrar un mensaje de error al usuario
                    }
                });

            }


            function limpiarCampos() {
                $("#impuesto_texto").val("").attr("value", "");
                $("#impuesto").val("").attr("value", "");
                $("#recargo_equivalencia").val("").attr("value", "");
            }

            listar_impuestos();

            //Guardar impuestos
            $("#guardar_impuesto").on('click', function(event) {

                error = false;
                rowid = $("#rowid").attr("value");

                impuesto_texto = $("#impuesto_texto").val();
                impuesto = $("#impuesto").val();
                recargo_equivalencia = $("#recargo_equivalencia").val();

                if (impuesto_texto === '') {
                    error = true;
                }
                if (impuesto === '') {
                    error = true;
                }
                if (recargo_equivalencia === '') {
                    error = true;
                }


                if (error) {
                    add_notification({
                        text: 'Corrigue los datos faltantes para continuar',
                        actionTextColor: '#fff',
                        backgroundColor: '#e7515a',
                        dismissText: 'Cerrar'
                    });

                    return false;
                }

                $(".msj-ajax").remove();
                $("#formulario_impuestos").prepend("<div class='mt-3 alert alert-info msj-ajax'>Actualizando Información...</div>");
                event.preventDefault();
                var formData = $("#formulario_impuestos").serialize() + '&id=' + rowid + '&fk_usuario=<?= $_SESSION['usuario'] ?>&action=CrearActualizarImpuestos';
                $.ajax({
                    type: 'POST',
                    action: 'CrearActualizarImpuestos',
                    url: '<?= ENLACE_WEB ?>/mod_impuestos/class/impuestos_class.php',
                    data: formData,
                    success: function(response) {


                        var data = $.parseJSON(response);
                        $(".msj-ajax").remove();
                        if (parseInt(data.error) === 0) {
                            add_notification({
                                text: data.mensaje,
                                actionTextColor: '#fff',
                                backgroundColor: '#00ab55',
                                dismissText: 'Cerrar'
                            });
                            listar_impuestos();
                            $("#rowid").remove();
                            $('#impuestoModal').modal('hide');
                            limpiarCampos();

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


            $(document).on('click', ".btn-edit", function() {

                $("#rowid").remove();
                rowid = $(this).attr('data-rowid');
                $("#formulario_impuestos").append('<input type="text" style="display:none;" name="rowid" id="rowid" value="' + rowid + '">');

                // Rellenar los campos del modal con los datos del atributo data-
                //$('#rowid').attr("value",$(this).attr('data-rowid'));
                $('#impuesto_texto').attr("value", $(this).attr('data-impuesto_texto'));
                $('#impuesto').attr("value", $(this).attr('data-impuesto'));
                $('#recargo_equivalencia').attr("value", $(this).attr('data-recargo_equivalencia'));

                // Cambiar el título del modal a "Editar Impuesto"
                $('#impuestoModalLabel').text('Editar Impuesto');

                // Cambiar el texto del botón a "Actualizar"
                $('#guardar_impuesto').text('Actualizar');

                // Abrir el modal
                $('#impuestoModal').modal('show');
            });

            // Limpiar el modal al abrirlo para crear un nuevo impuesto
            $('#impuestoModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Botón que abre el modal

                if (!button.hasClass('btn-edit')) {
                    // Si no es el botón de editar (es decir, es un nuevo impuesto)
                    $('#formulario_impuestos')[0].reset(); // Limpiar todos los campos del formulario
                    $('#rowid').val(''); // Asegurarse de que el campo rowid esté vacío

                    // Cambiar el título del modal a "Crear Nuevo Impuesto"
                    $('#impuestoModalLabel').text('Crear Nuevo Impuesto');

                    // Cambiar el texto del botón a "Guardar"
                    $('#guardar_impuesto').text('Guardar');
                }
            });

            // Al hacer clic en el botón de eliminar/desactivar
            $(document).on('click', '.btn-delete', function() {
                var rowid = $(this).attr('data-rowid');
                // Aquí podrías abrir un modal de confirmación o hacer una llamada AJAX para desactivar/eliminar el registro
                if (confirm('¿Estás seguro de que deseas desactivar este impuesto?')) {
                    // Ejemplo de llamada AJAX para eliminar (o desactivar) el impuesto
                    $.ajax({
                        url: '<?= ENLACE_WEB ?>/mod_impuestos/class/impuestos_class.php?action=EliminarImpuesto',
                        type: 'POST',
                        data: {
                            rowid: rowid
                        },
                        success: function(response) {
                            // Manejar la respuesta del servidor
                            var data = $.parseJSON(response);
                            if (parseInt(data.error) === 0) {
                                add_notification({
                                    text: data.mensaje,
                                    actionTextColor: '#fff',
                                    backgroundColor: '#00ab55',
                                    dismissText: 'Cerrar'
                                });
                                listar_impuestos();
                                $("#rowid").remove();
                                $('#impuestoModal').modal('hide');
                                limpiarCampos();

                            } else {
                                add_notification({
                                    text: data.mensaje,
                                    actionTextColor: '#fff',
                                    backgroundColor: '#e7515a'
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Ocurrió un error al desactivar el impuesto:', error);
                        }
                    });
                }
            });


            $("#retencion").change(function() {
                evento_hide_show();
            });

            function evento_hide_show() {
                if ($("#retencion").is(':checked') === true) {
                    $(".porcentaje_retencion_section").fadeIn(500);
                } else {
                    $("#retencion_porcentaje").val(0);
                    $(".porcentaje_retencion_section").hide(0);
                }
            }
            evento_hide_show();



            //formulario_informacion_basica
            $('#formulario_informacion_basica').on('submit', function(event) {

                var error = false;

                // Obteniendo los valores de los campos visibles
                var tipo_persona = $("#tipo_persona").val();
                var tipo_residencia = $("#tipo_residencia").val();
                var tipo_identificacion_fiscal = $("#tipo_identificacion_fiscal").val();
                var numero_identificacion = $("#numero_identificacion").val();
                var nombre_empresa = $("#razon_social").val();
                var nombre_fantasia = $("#nombre").val();
                var persona_nombre = $("#persona_nombre").val();
                var persona_apellido1 = $("#apellido1").val();
                var persona_apellido2 = $("#apellido2").val();
                var direccion_fk_pais = $("#direccion_fk_pais").val();
                var direccion_fk_provincia = $("#direccion_fk_provincia").val();
                var direccion_fk_ccaa = $("#direccion_fk_ccaa").val();
                var direccion_fk_municipio = $("#direccion_fk_municipio").val();
                var poblacion = $("#poblacion").val();
                var telefono_fijo = $("#telefono_fijo").val();
                var telefono_movil = $("#telefono_movil").val();
                var email = $("#email").val();

                // Validaciones sólo si los campos están visibles
                if ($("#telefono_fijo").is(":visible") && telefono_fijo == '') {
                    $("#telefono_fijo").addClass("input_error");
                    error = true;
                } else {
                    $("#telefono_fijo").removeClass("input_error");
                }

                if ($("#telefono_movil").is(":visible") && telefono_movil == '') {
                    $("#telefono_movil").addClass("input_error");
                    error = true;
                } else {
                    $("#telefono_movil").removeClass("input_error");
                }

                if ($("#email").is(":visible") && email == '') {
                    $("#email").addClass("input_error");
                    error = true;
                } else {
                    $("#email").removeClass("input_error");
                }

                if ($("#tipo_persona").is(":visible") && tipo_persona == '') {
                    $("#tipo_persona").addClass("input_error");
                    error = true;
                } else {
                    $("#tipo_persona").removeClass("input_error");
                }

                if ($("#tipo_residencia").is(":visible") && tipo_residencia == '') {
                    $("#tipo_residencia").addClass("input_error");
                    error = true;
                } else {
                    $("#tipo_residencia").removeClass("input_error");
                }

                if ($("#tipo_identificacion_fiscal").is(":visible") && tipo_identificacion_fiscal == '') {
                    $("#tipo_identificacion_fiscal").addClass("input_error");
                    error = true;
                } else {
                    $("#tipo_identificacion_fiscal").removeClass("input_error");
                }

                if ($("#numero_identificacion").is(":visible") && numero_identificacion == '') {
                    $("#numero_identificacion").addClass("input_error");
                    error = true;
                } else {
                    $("#numero_identificacion").removeClass("input_error");
                }

                if ($("#razon_social").is(":visible") && nombre_empresa == '') {
                    $("#razon_social").addClass("input_error");
                    error = true;
                } else {
                    $("#razon_social").removeClass("input_error");
                }

                if ($("#nombre").is(":visible") && nombre_fantasia == '') {
                    $("#nombre").addClass("input_error");
                    error = true;
                } else {
                    $("#nombre").removeClass("input_error");
                }

                if ($("#persona_nombre").is(":visible") && persona_nombre == '') {
                    $("#persona_nombre").addClass("input_error");
                    error = true;
                } else {
                    $("#persona_nombre").removeClass("input_error");
                }

                if ($("#apellido1").is(":visible") && persona_apellido1 == '') {
                    $("#apellido1").addClass("input_error");
                    error = true;
                } else {
                    $("#apellido1").removeClass("input_error");
                }

                if ($("#apellido2").is(":visible") && persona_apellido2 == '') {
                    $("#apellido2").addClass("input_error");
                    error = true;
                } else {
                    $("#apellido2").removeClass("input_error");
                }

                if ($("#direccion_fk_pais").is(":visible") && direccion_fk_pais == '') {
                    $("#direccion_fk_pais").addClass("input_error");
                    error = true;
                } else {
                    $("#direccion_fk_pais").removeClass("input_error");
                }

                if ($("#direccion_fk_provincia").is(":visible") && direccion_fk_provincia == '') {
                    $("#direccion_fk_provincia").addClass("input_error");
                    error = true;
                } else {
                    $("#direccion_fk_provincia").removeClass("input_error");
                }

                if ($("#poblacion").is(":visible") && poblacion == '') {
                    $("#poblacion").addClass("input_error");
                    error = true;
                } else {
                    $("#poblacion").removeClass("input_error");
                }

                // Si hay errores, mostrar mensaje y evitar el envío del formulario
                if (error) {
                    add_notification({
                        text: 'Corrige los datos faltantes para continuar',
                        actionTextColor: '#fff',
                        backgroundColor: '#FF0000',
                        dismissText: 'Cerrar'
                    });

                    return false; // Detenemos el envío del formulario
                }

                $(".msj-ajax").remove();
                event.preventDefault();
                var formData = $(this).serialize() + '&fk_usuario=<?= $_SESSION['usuario'] ?>&action=ActualizarInformacionEmpresa';
                // Enviamos los datos al servidor utilizando AJAX
                $.ajax({
                    type: 'POST',
                    action: 'ActualizarInformacionEmpresa',
                    url: '<?= ENLACE_WEB ?>/mod_empresa/class/clases.php',
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

            //Seleccionar paises
            $("#direccion_fk_pais").change(function() {
                $fk_pais = $(this).val();

                $.ajax({
                    async: false,
                    method: "POST",
                    url: '<?= ENLACE_WEB ?>/mod_empresa/class/clases.php',
                    beforeSend: function(xhr) {},

                    data: {
                        "action": "BuscarComunidadesAutonomas",
                        fk_pais: $fk_pais,
                        fk_ccaa: '<?php echo $codigo_ccaa; ?>'
                    },
                }).done(function(data) {
                    $("#direccion_fk_ccaa").html(data);
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    console.error("Error en la petición AJAX:", textStatus, errorThrown);

                    add_notification({
                        text: 'Error con la Peticion - Vuelve a Intentarlo',
                        actionTextColor: '#fff',
                        backgroundColor: '#e7515a'
                    });
                });

            });
            //Seleccionar Población / Comunidad Autonoma
            $("#direccion_fk_ccaa").change(function() {
                $direccion_fk_ccaa = $(this).val();
                $.ajax({
                    async: false,
                    method: "POST",
                    url: '<?= ENLACE_WEB ?>/mod_empresa/class/clases.php',
                    beforeSend: function(xhr) {},

                    data: {
                        "action": "BuscarProvincias",
                        fk_comunidad_autonoma: $direccion_fk_ccaa,
                        fk_provincia: '<?php echo $codigo_provincia; ?>'
                    },
                }).done(function(data) {
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
            $('#direccion_fk_provincia').on('change', function() {
                $fk_provincia = $(this).val();
                $.ajax({
                    async: false,
                    method: "POST",
                    url: '<?= ENLACE_WEB ?>/mod_empresa/class/clases.php',
                    beforeSend: function(xhr) {},

                    data: {
                        "action": "BuscarMunicipios",
                        fk_provincia: $fk_provincia,
                        fk_municipio: '<?php echo $codigo_municipio; ?>'
                    },
                }).done(function(data) {
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


            //para div de cargando
            function div_mensaje_ajax($element, $text) {
                $(".msj-ajax").remove();
                $element.after("<div class='mt-3 alert alert-info msj-ajax'>" + $text + "</div>");
            }

            // Función que devuelve una promesa que se resuelve cuando se completa el evento 'change'
            function triggerEvent(selector) {
                return new Promise((resolve) => {
                    $(selector).one('change', resolve);
                    $(selector).trigger('change');
                });
            }

            // Encadenar las llamadas de 'trigger' utilizando promesas
            triggerEvent("#direccion_fk_pais").then(() => {
                return triggerEvent("#direccion_fk_ccaa");
            }).then(() => {
                return triggerEvent("#direccion_fk_provincia");
            }).catch((error) => {
                console.error("Error al desencadenar los eventos:", error);
            });
        });

        //Guardar impuestos
        function formulario_retenciones() {
            let error = false;
            let retencion_aplica = $("#retencion").prop('checked');
            let retencion_porcentaje = $("#retencion_porcentaje").val();
            let retencion_vigencia = $("#retencion_porcentaje_rigue_hasta").val();

            if (retencion_aplica) {
                if (parseFloat(retencion_porcentaje) <=0) {
                    $("#retencion_porcentaje").addClass('input_error')
                    error = true;
                }
                if (parseFloat(retencion_porcentaje) > 100) {
                    $("#retencion_porcentaje").addClass('input_error')
                    error = true;
                }
            }

            if (error) {
                add_notification({
                    text: 'Corrigue los datos faltantes para continuar',
                    actionTextColor: '#fff',
                    backgroundColor: '#e7515a',
                    dismissText: 'Cerrar'
                });

                return false;
            }

            const data = {
                action: 'ActualizarEmpresaImpuestos',
                retencionvalor: retencion_aplica,
                retencion_porcentaje: retencion_porcentaje,
                retencion_porcentaje_rigue_hasta: retencion_vigencia
            }

            $.ajax({
                type: 'POST',
                url: '<?= ENLACE_WEB ?>/mod_empresa/class/clases.php',
                data: data,
                success: function(response) {

                    const data = $.parseJSON(response);
                    if (data.exito) {
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
                    console.error(error);
                }
            });
        };


    </script>



    <script type="text/javascript">
        jQuery(document).ready(function($) {
            const $tipoPersonaSelect = $('#tipo_persona');
            const $nombreField = $('#nombre_field');
            const $apellido1Field = $('#apellido1_field');
            const $apellido2Field = $('#apellido2_field');
            const $nombreCompletoField = $('#nombre_completo_field');
            const $razonSocialField = $('#razon_social_field');

            $tipoPersonaSelect.on('change', function() {
                const tipoPersona = $tipoPersonaSelect.val();

                $("#tipo_residencia").trigger("change");

                if (tipoPersona === '2') {
                    $nombreField.hide();
                    $apellido1Field.hide();
                    $apellido2Field.hide();
                    $nombreCompletoField.show();
                    $razonSocialField.show();
                } else {
                    $nombreField.show();
                    $apellido1Field.show();
                    $apellido2Field.show();
                    $nombreCompletoField.hide();
                    $razonSocialField.hide();
                }
            });

            // Disparar el evento 'change' para aplicar la lógica al cargar la página
            $tipoPersonaSelect.trigger('change');





            $('#tipo_residencia').change(function() {
                var tipoPersona = $('#tipo_persona').val();
                var tipoResidencia = $(this).val();
                var tipoIdentificacionFiscal = $('#tipo_identificacion_fiscal');

                // Limpiar las opciones actuales
                tipoIdentificacionFiscal.empty();

                // Opciones según la lógica
                var opciones = [];

                if (tipoPersona === '1' && tipoResidencia === 'E') {
                    opciones = [{
                            value: '1',
                            text: 'NIF/IVA (Operador intracomunitario)'
                        },
                        {
                            value: '2',
                            text: 'Pasaporte'
                        },
                        {
                            value: '3',
                            text: 'Documento oficial de identificación expedido por el país o territorio de residencia'
                        },
                        {
                            value: '4',
                            text: 'Certificado de residencia'
                        },
                        {
                            value: '5',
                            text: 'Otro documento probatorio'
                        }
                    ];
                } else if (tipoPersona === '1' && tipoResidencia === 'R') {
                    opciones = [{
                        value: '1',
                        text: 'NIF/IVA (Operador intracomunitario)'
                    }, ];
                } else if (tipoPersona === '1' && tipoResidencia === 'RUE') {
                    opciones = [{
                        value: '1',
                        text: 'NIF/IVA (Operador intracomunitario)'
                    }, ];
                } else if (tipoPersona === '2' && tipoResidencia === 'E') {
                    opciones = [{
                            value: '1',
                            text: 'NIF/IVA (Operador intracomunitario)'
                        },
                        {
                            value: '2',
                            text: 'Pasaporte'
                        },
                        {
                            value: '5',
                            text: 'Otro documento probatorio'
                        }
                    ];
                } else if (tipoPersona === '2' && tipoResidencia === 'R') {
                    opciones = [{
                        value: '1',
                        text: 'NIF/IVA (Operador intracomunitario)'
                    }, ];
                } else if (tipoPersona === '2' && tipoResidencia === 'RUE') {
                    opciones = [{
                            value: '1',
                            text: 'NIF/IVA (Operador intracomunitario)'
                        },
                        {
                            value: '2',
                            text: 'Pasaporte'
                        },
                    ];
                }

                // Agregar las nuevas opciones
                $.each(opciones, function(index, opcion) {
                    tipoIdentificacionFiscal.append($('<option>', {
                        value: opcion.value,
                        text: opcion.text
                    }));
                });
            });


        });

        $("#formulario_verifactum").on('submit', function(event) {
            event.preventDefault()
            const verifactum_option = $('#verifact').val()
            let message = "¿Está seguro(a) de los cambios?";
            if (verifactum_option == 3) {
                message = "¿Está seguro(a) de pasar a PRODUCCIÓN?";
            }

            var actionText = "<strong onclick='ocultar_snackbar();' id='cancelar_produccion'>Cancelar</strong> <button onclick='confirmar_verifact()' style='margin-left:5px;' id='confirmar_produccion' class='btn btn-danger'>Confirmar</button>";

            // Mostrar el snackbar y definir el callback para el botón de acción
            var snackbar = add_notification({
                text: message,
                width: 'auto',
                duration: 300000,
                actionText: actionText,
            });
        });

        function confirmar_verifact() {
            const verifactum_option = $('#verifact').val()
            const verifactum_certificado = $('#certificadofile')[0].files[0];
            // const verifactum_certificado_encriptado = $('#certificadofile_encriptado')[0].files[0];        
            const verifactum_clave = $('#clave').val()

            const formData = new FormData();
            formData.append("action", "ActualizarVerifact");
            formData.append("verifactum_option", verifactum_option);
            formData.append("verifactum_clave", verifactum_clave);
            // Verificamos si hay un archivo antes de añadirlo
            if (verifactum_certificado) {
                formData.append("verifactum_certificado", verifactum_certificado);
            }
            // if (certificadofile_encriptado) {
            //     formData.append("verifactum_certificado_encriptado", verifactum_certificado_encriptado);
            // }

            $.ajax({
                method: "POST",
                url: '<?= ENLACE_WEB ?>/mod_empresa/class/clases.php',
                async: false,
                data: formData,
                processData: false, // Evitar que jQuery procese los datos
                contentType: false, // Evitar que jQuery establezca un encabezado Content-Type
                beforeSend: function(xhr) {},
                success: function(dataresponse) {
                    const response = JSON.parse(dataresponse)
                    if (response.exito) {
                        add_notification({
                            text: 'El proceso se ha realizado correctamente',
                            width: 'auto',
                            dismissText: 'Cerrar'
                        });
                    } else {
                        add_notification({
                            text: 'No puede dejar PRODUCCIÓN. Contacte con el Administrador',
                            actionTextColor: '#fff',
                            backgroundColor: '#e7515a',
                            dismissText: 'Cerrar'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error en la petición AJAX:", textStatus, errorThrown);
                    add_notification({
                        text: 'No puede dejar PRODUCCIÓN. Contacte con el Administrador',
                        actionTextColor: '#fff',
                        backgroundColor: '#e7515a',
                        dismissText: 'Cerrar'
                    });
                }
            })

        }

        function cambiar_entorno_verifactum() {
            const checked = $(this).prop('checked')
            verifact
            var message = "¿Está seguro(a) de " + (checked ? 'pasar a PRODUCCIÓN' : 'salir de PRODUCCIÓN') + "?";
            var actionText = "<strong onclick='ocultar_snackbar();' id='cancelar_produccion'>Cancelar</strong> <button onclick='confirmar_verifact(" + checked + ")' style='margin-left:5px;' id='confirmar_produccion' class='btn btn-danger'>Confirmar</button>";

            // Mostrar el snackbar y definir el callback para el botón de acción
            var snackbar = add_notification({
                text: message,
                width: 'auto',
                duration: 300000,
                actionText: actionText,
            });
        }

        function ocultar_snackbar() {
            $('#production_verifact').prop('checked', !$('#production_verifact').prop('checked'))
            $(".snackbar-container").fadeOut(50);
        }

        function togglePasswordVisibility() {
            var passwordInput = document.getElementById("clave");
            var togglePasswordIcon = document.getElementById("togglePasswordIcon");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                togglePasswordIcon.classList.remove("fa-eye");
                togglePasswordIcon.classList.add("fa-eye-slash");
            } else {
                passwordInput.type = "password";
                togglePasswordIcon.classList.remove("fa-eye-slash");
                togglePasswordIcon.classList.add("fa-eye");
            }
        }
    </script>