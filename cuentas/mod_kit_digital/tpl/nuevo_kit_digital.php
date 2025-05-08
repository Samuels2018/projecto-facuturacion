<?php 

require_once ENLACE_SERVIDOR."mod_empresa/object/empresa.object.php";
require_once ENLACE_SERVIDOR_CUENTAS . 'mod_kit_digital/object/kit_digital.object.php';

$Utilidades = new Utilidades($dbh);
$kitDigital = new kit_digital($dbh);

// Fetch all kit digital types
$kitDigitalTipos = $kitDigital->fetchAllKitDigitalTipo();

//Fetch all para los estados de kit digital
$estados_kit_digital = $kitDigital->listar_estados_kitdigital();



// Obtener datos de España
$comunidad_autonoma = $Utilidades->obtener_comunidades_autonomas(1);

// Obtener los datos de la empresa
$Empresa = new empresa($dbh);
$fiche = $_GET['fiche'];

if (!empty($_GET['fiche'])) {
    $datos_empresa = $Empresa->fetch($fiche);
    $ubigeo_seleccionado = $Utilidades->obtener_ubigeo_seleccionado($datos_empresa['direccion_fk_provincia']);
}else{
    $datos_empresa = array();
}



?>  

<style>
    .form-control{
        padding: 0.75rem 1.25rem !important;
        font-size: 15px !important;
    }

</style>

<div class="middle-content container-xxl p-0">
    <!-- BREADCRUMB -->
    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Licencias</li>
            </ol>
        </nav>
    </div>
    <!-- /BREADCRUMB -->

    <div class="account-settings-container layout-top-spacing">
        <div class="account-content">
            <div class="row mb-3">
                <div class="col-md-12">
                    <h2>Reseller</h2>
                    <ul class="nav nav-pills" id="animateLine" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="animated-underline-home-tab" data-bs-toggle="tab" href="#animated-underline-home" role="tab" aria-controls="animated-underline-home" aria-selected="true">
                                <i class="fa fa-info-circle me-2"></i> DATOS
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link <?php echo empty($_GET['fiche']) ? 'disabled' : ''; ?>" 
                                    id="animated-underline-kitdigital-tab" 
                                    data-bs-toggle="tab" 
                                    href="#animated-underline-kitdigital" 
                                    role="tab" 
                                    aria-controls="animated-underline-kitdigital" 
                                    aria-selected="false" 
                                    <?php echo empty($_GET['fiche']) ? 'disabled' : ''; ?>>
                                <i class="fa fa-digital-tachograph me-2"></i> KIT DIGITAL
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link <?php echo empty($_GET['fiche']) ? 'disabled' : ''; ?>" 
                                    id="animated-underline-comisiones-tab" 
                                    data-bs-toggle="tab" 
                                    href="#animated-underline-comisiones" 
                                    role="tab" 
                                    aria-controls="animated-underline-comisiones" 
                                    aria-selected="false" 
                                    <?php echo empty($_GET['fiche']) ? 'disabled' : ''; ?>>
                                <i class="fa fa-money-bill-wave me-2"></i> COMISIONES
                            </button>
                        </li>
                    </ul>

                </div>
            </div>

            <div class="tab-content" id="animateLineContent-4">
                <!-- Datos Tab -->
                <div class="tab-pane fade active show" id="animated-underline-home" role="tabpanel" aria-labelledby="animated-underline-home-tab">
    <div class="row justify-content-center">
        <form method="POST" class="section general-info p-4" id="formulario_informacion_basica">
            <!-- Header Section -->
            <div class="statbox widget box box-shadow mb-4">
                <div class="widget-header p-3">
                    <h4><i class="fa fa-info-circle me-2"></i> Información General</h4>
                </div>
                
                <div class="widget-content widget-content-area bg-light p-4 rounded">
                    <div class="row">
                        <!-- Tipo de Entidad (Física o Jurídica) -->
                        <div class="col-md-6 mb-3">
                            <label for="tipo" class="col-form-label"><i class="fas fa-building me-2"></i> Tipo de Entidad</label>
                            <select name="tipo" id="tipo" class="form-select">
                                <option value="">Seleccione el tipo</option>
                                <option value="fisica" <?php echo ($datos_empresa['tipo'] == 'fisica') ? 'selected' : ''; ?>>Física</option>
                                <option value="juridica" <?php echo ($datos_empresa['tipo'] == 'juridica') ? 'selected' : ''; ?>>Jurídica</option>
                            </select>
                        </div>

                        <!-- Nombre o Razón Social -->
                        <div class="col-md-6 mb-3">
                            <label for="nombre" id="label-nombre" class="col-form-label"><i class="fa fa-user me-2"></i>Nombre <span>*</span></label>
                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingrese" value="<?php echo $datos_empresa['nombre']; ?>">
                        </div>

                        <!-- Apellido o Nombre Fantasía -->
                        <div class="col-md-6 mb-3">
                            <label for="nombre_comercial" id="label-apellido" class="col-form-label"><i class="fa fa-user me-2"></i>Apellido </label>
                            <input type="text" class="form-control" id="nombre_comercial" name="nombre_comercial" placeholder="Ingrese" value="<?php echo $datos_empresa['nombre_comercial']; ?>">
                        </div>

                        <!-- Cedula -->
                        <div class="col-md-6 mb-3">
                            <label for="cedula" class="col-form-label"><i class="fa fa-id-card me-2"></i> NIF</label>
                            <input type="text" class="form-control" id="cedula" name="cedula" placeholder="NIF" value="<?php echo $datos_empresa['cedula']; ?>">
                        </div>

                        <!-- Comunidad Autonoma -->
                        <div class="col-md-6 mb-3">
                            <label for="poblacion" class="col-form-label"><i class="fa fa-city me-2"></i>Comunidad Autónoma <span>*</span></label>
                            <?php 
                                $lista_poblaciones = $ubigeo_seleccionado->nombre_comunidad_autonoma;
                                $comunidad_seleccionada = isset($ubigeo_seleccionado[0]->comunidad_autonoma_id) ? $ubigeo_seleccionado[0]->comunidad_autonoma_id : '';
                              ?>
                            <select name="poblacion" id="poblacion" class="form-select">
                                <option value="">Seleccionar CCAA</option>
                                <?php 
                                foreach ($comunidad_autonoma as $key => $value) {
                                    if ($comunidad_autonoma[$key]->nombre === NULL) continue; ?>
                                    <option value="<?php echo $comunidad_autonoma[$key]->id; ?>" <?php echo (intval($comunidad_seleccionada) === intval($comunidad_autonoma[$key]->id)) ? 'selected' : ''; ?>>
                                        <?php echo $comunidad_autonoma[$key]->nombre; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <!-- Provincia -->
                        <div class="col-md-6 mb-3">
                            <label for="direccion_fk_provincia" class="col-form-label"><i class="fa fa-flag me-2"></i>Provincia <span>*</span></label>
                            <select name="direccion_fk_provincia" id="direccion_fk_provincia" class="form-select">
                                <option value="">Seleccione la provincia</option>
                                <?php 
                                $added_provincia_ids = [];
                                foreach ($ubigeo_seleccionado as $key => $value) {
                                    if (intval($key) === 0) continue;
                                    $provincia_id = $ubigeo_seleccionado[$key]->provincia_id;
                                    if ($provincia_id === NULL || in_array($provincia_id, $added_provincia_ids)) continue;
                                    $added_provincia_ids[] = $provincia_id; ?>
                                    <option value="<?php echo $provincia_id; ?>" <?php echo (intval($datos_empresa['direccion_fk_provincia']) === $provincia_id) ? 'selected' : ''; ?>>
                                        <?php echo $ubigeo_seleccionado[$key]->nombre_provincia; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <!-- Municipio -->
                        <div class="col-md-6 mb-3">
                            <label for="direccion_fk_municipio" class="col-form-label"><i class="fa fa-city me-2"></i>Municipio <span>*</span></label>
                            <select name="direccion_fk_municipio" id="direccion_fk_municipio" class="form-select">
                                <option value="">Seleccione el municipio</option>
                                <?php foreach ($ubigeo_seleccionado as $key => $value) {
                                    if (intval($key) === 0) continue;
                                    if ($ubigeo_seleccionado[$key]->municipio_id === NULL) continue; ?>
                                    <option value="<?php echo $ubigeo_seleccionado[$key]->municipio_id; ?>" <?php echo (intval($datos_empresa['direccion_fk_municipio']) === $ubigeo_seleccionado[$key]->municipio_id) ? 'selected' : ''; ?>>
                                        <?php echo $ubigeo_seleccionado[$key]->nombre_municipio; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <!-- Teléfono Fijo -->
                        <div class="col-md-6 mb-3">
                            <label for="telefono_fijo" class="col-form-label"><i class="fa fa-phone me-2"></i>Teléfono Fijo</label>
                            <input type="text" class="form-control" id="telefono_fijo" name="telefono_fijo" placeholder="Teléfono Fijo" value="<?php echo $datos_empresa['telefono_fijo']; ?>">
                        </div>

                        <!-- Teléfono Móvil -->
                        <div class="col-md-6 mb-3">
                            <label for="telefono_movil" class="col-form-label"><i class="fa fa-mobile me-2"></i>Teléfono Móvil <span>*</span></label>
                            <input type="text" class="form-control" id="telefono_movil" name="telefono_movil" placeholder="Teléfono Móvil" value="<?php echo $datos_empresa['telefono_movil']; ?>">
                        </div>

                        <!-- Código Postal -->
                        <div class="col-md-6 mb-3">
                            <label for="kit_codigo_postal" class="col-form-label"><i class="fa fa-envelope me-2"></i>Código Postal</label>
                            <input type="text" class="form-control" id="kit_codigo_postal" name="kit_codigo_postal" placeholder="Código Postal" value="<?php echo $datos_empresa['kit_codigo_postal']; ?>">
                        </div>

                        <!-- Dirección Completa -->
                        <div class="col-md-12 mb-3">
                            <label for="kit_direccion_completa" class="col-form-label"><i class="fa fa-address-book me-2"></i>Dirección Completa</label>
                            <textarea id="kit_direccion_completa" name="kit_direccion_completa" class="form-control" style="height: 80px;" placeholder="Dirección Completa"><?php echo $datos_empresa['kit_direccion_completa']; ?></textarea>
                        </div>

                        <!-- Notas-->
                        <div class="col-md-12 mb-3">
                            <label for="notas_empresa" class="col-form-label"><i class="fa fa-address-book me-2"></i>Notas Empresa</label>
                            <textarea id="notas_empresa" name="notas_empresa" class="form-control" style="height: 80px;" placeholder="Ingresa la nota"><?php echo $datos_empresa['notas_empresa']; ?></textarea>
                        </div>

                    </div>

                    <!-- Botón Guardar -->
                    <div class="row mt-5">
                        <div class="col text-start">
                            <input type="hidden" id="fiche" name="fiche" value="<?php echo $fiche; ?>">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save me-2" aria-hidden="true"></i> <?php echo empty($fiche) ? 'Guardar' : 'Actualizar Información'; ?></button>
                            <?php if (!empty($fiche)) : ?>
                                <a href="<?php echo ENLACE_WEB_CUENTAS . 'kit_digital_listado/'; ?>" class="btn btn-secondary ms-3">Volver al listado</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


   
                <!-- KIT DIGITAL Tab -->
                <div class="tab-pane fade" id="animated-underline-kitdigital" role="tabpanel" aria-labelledby="animated-underline-kitdigital-tab">
    <div class="row justify-content-center">
        <form method="POST" class="section general-info p-4" id="formulario_kitdigital" enctype="multipart/form-data">
            
            <!-- Header Section -->
            <div class="statbox widget box box-shadow mb-4">
                <div class="widget-header p-3">
                    <h4><i class="fas fa-digital-tachograph me-2"></i> Kit Digital</h4>
                </div>
                
                <div class="widget-content widget-content-area bg-light p-4 rounded">
                    
                    <!-- Aplica Kit Digital -->
                    <div class="row mb-4 align-items-center">
                        <label for="kit_aplica_kit_digital" class="col-sm-3 col-form-label fw-semibold">¿Aplica Kit Digital?</label>
                        <div class="col-sm-9">
                            <div class="form-switch">
                                <input name="kit_aplica_kit_digital" class="form-check-input" type="checkbox" value="1" role="switch" id="kit_aplica_kit_digital" <?php echo !empty($datos_empresa['kit_aplica_kit_digital']) ? 'checked' : ''; ?>>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Fields Section -->
                    <div id="kit_digital_fields" style="<?php echo !empty($datos_empresa['kit_aplica_kit_digital']) ? 'display: block;' : 'display: none;'; ?>">

                        <!-- Tipo de Kit Digital -->
                        <div class="row mb-4">
                            <label for="kit_fk_tipo" class="col-sm-3 col-form-label"><i class="fas fa-box-open me-2"></i> Tipo de Kit Digital</label>
                            <div class="col-sm-9">
                                <select name="kit_fk_tipo" id="kit_fk_tipo" class="form-select">
                                    <option value="">Seleccione el tipo de Kit Digital</option>
                                    <?php foreach ($kitDigitalTipos as $tipo): ?>
                                        <option value="<?php echo $tipo['rowid']; ?>" <?php echo ($datos_empresa['kit_fk_tipo'] == $tipo['rowid']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($tipo['etiqueta']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label for="fk_kit_digital_estado" class="col-sm-3 col-form-label"><i class="fas fa-box-open me-2"></i>Estado Kit Digital</label>
                            <div class="col-sm-9">
                                <select name="fk_kit_digital_estado" id="fk_kit_digital_estado" class="form-select">
                                    <option value="">Seleccione el estado</option>
                                    <?php foreach ($estados_kit_digital as $estado_kit): ?>
                                        <option value="<?php echo $estado_kit['rowid']; ?>" <?php echo ($datos_empresa['fk_kit_digital_estado'] == $estado_kit['rowid']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($estado_kit['etiqueta']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                           <!-- Municipio -->
                   
                        <!-- PDF Firmado -->
                        <div class="row mb-4 align-items-center">
                            <label for="kit_pdf_firmado" class="col-sm-3 col-form-label"><i class="fas fa-file-signature me-2"></i> PDF Firmado</label>
                            <div class="col-sm-9">
                                <div class="form-switch">
                                    <input name="kit_pdf_firmado" class="form-check-input" type="checkbox" value="1" role="switch" id="kit_pdf_firmado" <?php echo !empty($datos_empresa['kit_pdf_firmado']) ? 'checked' : ''; ?>>
                                </div>
                            </div>
                        </div>

                        <!-- Subir PDF (si está firmado) -->
                        <div class="row mb-4 align-items-center" id="kit_pdf_firmado_url_field" style="<?php echo !empty($datos_empresa['kit_pdf_firmado']) ? '' : 'display: none;'; ?>">
                            <label for="kit_pdf_firmado_url_en_disco" class="col-sm-3 col-form-label"><i class="fas fa-upload me-2"></i> Subir PDF Firmado</label>
                            <div class="col-sm-9">
                                <input type="file" class="form-control" id="kit_pdf_firmado_url_en_disco" name="kit_pdf_firmado_url_en_disco" accept=".pdf">
                                <?php if (!empty($datos_empresa['kit_pdf_firmado_url_en_disco'])): ?>
                                    <p class="mt-2">Archivo actual: <a download="<?php echo $datos_empresa['kit_pdf_firmado_url_en_disco']; ?>" href="<?php echo ENLACE_WEB_CUENTAS; ?>/servir_adjuntos_cuentas/?img=<?php echo $_GET['fiche']; ?>" target="_blank" class="text-decoration-underline"><?php echo $datos_empresa['kit_pdf_firmado_url_en_disco']; ?></a></p>
                                <?php endif; ?>
                            </div>
                        </div>

                     
                        <!-- Vendedor (Usuario actual, no editable) -->
                        <div class="row mb-4 align-items-center">
                            <label for="kit_monto_aprobado" class="col-sm-3 col-form-label"><i class="fas fa-user-tie me-2"></i>Monto Aprobado</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" id="kit_monto_aprobado" name="kit_monto_aprobado" placeholder="Monto Aprobado" value="<?php echo $datos_empresa['kit_monto_aprobado']; ?>">
                            </div>
                        </div>

                        <!-- Vendedor (Usuario actual, no editable) -->
                        <div class="row mb-4 align-items-center" style="display:none;">
                            <label for="vendedor_fk_usuario" class="col-sm-3 col-form-label"><i class="fas fa-user-tie me-2"></i> Vendedor (Usuario)</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="vendedor_fk_usuario" name="vendedor_fk_usuario" placeholder="Usuario actual" value="<?php echo $datos_empresa['vendedor_fk_usuario']; ?>" readonly>
                            </div>
                        </div>

                    </div>

                    <!-- Botón Guardar -->
                    <div class="row mt-5">
                        <div class="col text-start">
                            <input type="hidden" id="fiche_kit" name="fiche" value="<?php echo $_GET['fiche']; ?>">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Guardar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


                                   


              <!-- Comisiones Tab -->
<div class="tab-pane fade" id="animated-underline-comisiones" role="tabpanel" aria-labelledby="animated-underline-comisiones-tab">
    <div class="row justify-content-center">
        <form method="POST" class="section general-info p-4" id="formulario_comisiones">
            
            <!-- Header Section -->
            <div class="statbox widget box box-shadow mb-4">
                <div class="widget-header p-3">
                    <h4><i class="fas fa-coins me-2"></i> Comisiones</h4>
                </div>
                
                <div class="widget-content widget-content-area bg-light p-4 rounded">

                    <!-- Comisión -->
                    <div class="row mb-4">
                        <label for="kit_monto_comision" class="col-sm-3 col-form-label"><i class="fas fa-percentage me-2"></i> Comisión</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="kit_monto_comision" name="kit_monto_comision" placeholder="Comisión" readonly value="<?php echo $datos_empresa['kit_monto_comision']; ?>">
                        </div>
                    </div>

                    <!-- Comisión Pagada -->
                    <div class="row mb-4">
                        <label for="kit_monto_comision_pagada" class="col-sm-3 col-form-label"><i class="fas fa-check-circle me-2"></i> Comisión Pagada</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="kit_monto_comision_pagada" name="kit_monto_comision_pagada" placeholder="Comisión Pagada" readonly value="<?php echo $datos_empresa['kit_monto_comision_pagada']; ?>">
                        </div>
                    </div>

                    <!-- Factura Emitida -->
                    <div class="row mb-4 align-items-center">
                        <label for="kit_factura_emitida" class="col-sm-3 col-form-label"><i class="fas fa-file-invoice me-2"></i> Factura Emitida</label>
                        <div class="col-sm-9">
                            <div class="form-switch">
                                <input name="kit_factura_emitida" class="form-check-input" type="checkbox" value="1" role="switch" id="kit_factura_emitida" <?php echo !empty($datos_empresa['kit_factura_emitida']) ? 'checked' : ''; ?>>
                            </div>
                        </div>
                    </div>

                    <!-- Fecha de Emisión de Factura -->
                    <div class="row mb-4" id="kit_factura_emitida_fecha_field" style="<?php echo !empty($datos_empresa['kit_factura_emitida']) ? 'display: flex;' : 'display: none;'; ?>">
                        <label for="kit_factura_emitida_fecha" class="col-sm-3 col-form-label"><i class="fas fa-calendar-alt me-2"></i> Fecha de Emisión</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="kit_factura_emitida_fecha" name="kit_factura_emitida_fecha" placeholder="Fecha de Emisión de Factura" value="<?php echo date('Y-m-d',strtotime($datos_empresa['kit_factura_emitida_fecha'])); ?>">
                        </div>
                    </div>

                    <!-- Factura Emitida Pagada -->
                    <div class="row mb-4 align-items-center">
                        <label for="kit_factura_emitida_pagada" class="col-sm-3 col-form-label"><i class="fas fa-receipt me-2"></i> Factura Emitida Pagada</label>
                        <div class="col-sm-9">
                            <div class="form-switch">
                                <input name="kit_factura_emitida_pagada" class="form-check-input" type="checkbox" value="1" role="switch" id="kit_factura_emitida_pagada" <?php echo !empty($datos_empresa['kit_factura_emitida_pagada']) ? 'checked' : ''; ?>>
                            </div>
                        </div>
                    </div>

                    <!-- Botón Guardar -->
                    <div class="row mt-5">
                        <div class="col text-start">
                            <input type="hidden" id="fiche_comision" name="fiche" value="<?php echo $_GET['fiche']; ?>">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Guardar
                            </button>
                        </div>
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
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<?php require_once ENLACE_SERVIDOR_CUENTAS.'mod_kit_digital/tpl/nuevo_kit_digital.scripts.php'; ?>

<script>
    $(document).ready(function () {
        // Datepicker para la fecha de emisión de factura
        $("#kit_factura_emitida_fecha").datepicker({
            dateFormat: "yy-mm-dd",
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+10",
            showAnim: "slideDown"
        });

        // Mostrar/ocultar campos en el tab KIT DIGITAL
        $('#kit_aplica_kit_digital').change(function () {
            if ($(this).is(':checked')) {
                $('#kit_digital_fields').slideDown();
            } else {
                $('#kit_digital_fields').slideUp();
                $('#kit_pdf_firmado_url_field').hide();
                $('#kit_pdf_firmado').prop('checked', false);
                $('#kit_pdf_firmado_url_en_disco').val('');
            }
        });

        // Mostrar/ocultar campo de PDF firmado
        $('#kit_pdf_firmado').change(function () {
            if ($(this).is(':checked')) {
                $('#kit_pdf_firmado_url_field').slideDown();
            } else {
                $('#kit_pdf_firmado_url_field').slideUp();
                $('#kit_pdf_firmado_url_en_disco').val('');
            }
        });

        // Mostrar/ocultar fecha de emisión de factura en el tab Comisiones
        $('#kit_factura_emitida').change(function () {
            if ($(this).is(':checked')) {
                $('#kit_factura_emitida_fecha_field').slideDown();
            } else {
                $('#kit_factura_emitida_fecha_field').slideUp();
                $('#kit_factura_emitida_fecha').val('');
            }
        });

         // Cambiar labels según tipo de entidad
         $('#tipo').change(function() {
            if ($(this).val() === 'fisica') {
                
                $('#label-nombre').text('Nombre');
                $('#label-apellido').text('Apellido');


            } else {
               
                $('#label-nombre').text('Razón Social');
                $('#label-apellido').text('Nombre Fantasía');
            }
        });

        // Trigger para mantener la selección en caso de recarga
        $('#tipo').trigger('change');


    });
</script>
