<?php
session_start();

//si no hay usuario autenticado, cerrar conexion
if (!isset($_SESSION['usuario'])) {
    echo acceso_invalido();
    exit(1);
}


include_once("../../conf/conf.php");
include_once ENLACE_SERVIDOR . 'mod_europa_facturacion_series/object/configuracion_series_object.php';
include_once ENLACE_SERVIDOR . 'mod_documento_pdf/object/plantilla.object.php';

$obj = new Series($dbh, $_SESSION['Entidad']);
$Utilidades->obtener_diccionario_transacciones_documentos();


// if (!empty($_REQUEST['fiche'])) {

//     $obj->fetch($_REQUEST['fiche']);
//     if ($obj->entidad != $_SESSION['Entidad']) {
//         echo acceso_invalido();
//         exit(1);
//     }
// }

$obj->fetch($_REQUEST['fiche']);

if ($obj->id > 0) {
    $titulo = 'Modificar';
} else {
    $titulo = 'Crear';
}

$plantilla = new Plantilla($dbh, $_SESSION['Entidad']);
$plantillas = $plantilla->obtener_plantilla_entidad();
// $plantilla_serie = $plantilla->obtener_plantilla_serie($obj->id);
// echo 'AAA: '.$plantilla_serie["id"];
?>


<div class="modal-dialog" role="document">
    <div class="modal-content">



        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-list"></i> <?= $titulo ?> Serie </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

            </button>
        </div>
        <div class="modal-body">
            <form role="form" method="POST" action="" id="formulario">
                <!-- left column -->

                <!-- general form elements -->


                <div class="card-body">

                    <input type="hidden" id="plantilla_fk" value="<?php echo $obj->plantilla_fk ?>">

                    <div class="row mt-2">

                        <div class="col-md-6">
                            <label for="ref"><i class="fa fa-fw fa-gear"></i> Configuraci&oacute;n M&aacute;scara </label>
                            <input required="required" placeholder="Configuracion M&aacute;scara " type="text" name="fk_serie_modelo" id="fk_serie_modelo" class="form-control" value="<?php echo $obj->fk_serie_modelo; ?>" <?php echo $disabled; ?>>
                        </div>

                        <div class="col-md-6">
                            <label for="ref"><i class="fa fa-fw fa-list"></i> Siguiente Documento</label>
                            <?php echo $obj->enmascarar_($obj->siguiente_documento, $obj->fk_serie_modelo); ?>
                        </div>

                        <div class="col-md-12">
                            <p>_Y_ ser&aacute; reeemplazado por el año en curso <?php echo date("Y"); ?></p>
                            <p># ser&aacute; reemplazado por el siguiente n&uacute;mero de Serie <?php echo date("Y"); ?></p>

                        </div>
                    </div>


                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="ref"><i class="fa fa-fw fa-asterisk"></i> Siguiente Documento </label>
                            <input required="required" placeholder="N&uacute;mero Siguiente Documento" type="number" name="siguiente_documento" id="siguiente_documento" class="form-control" value="<?php echo $obj->siguiente_documento; ?>" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-md-6">
                            <label for="ref"><i class="fa fa-fw fas fa-calendar-alt"></i> Reinicia Anualmente </label>
                            <select name="serie_reinicio_anual" id="serie_reinicio_anual" class="form-control">
                                <option value="1" <?php echo ((strval($obj->serie_reinicio_anual) == "1" || strval($obj->serie_reinicio_anual) == "") ? 'selected' : ''); ?>>Si</option>
                                <option value="0" <?php echo ((strval($obj->serie_reinicio_anual) == "0") ? 'selected' : ''); ?>>No</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="ref"><i class="fa fa-fw fa-asterisk"></i> Tipo Documento </label>
                            <select name="tipo" id="tipo" class="form-control" OnChange="selector_tipo_aeat(this.value)">
                                <option value="">Selecciona</option>
                                <?php
                                foreach ($Utilidades->diccionario_transacciones_documentos as $key => $array) {
                                    echo "<option value='{$array['tabla']}'  " . (($array['tabla'] == $obj->tipo) ? 'selected="selected"' : '') . "  >{$array['descripcion']} </option> ";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6 tipo_aeat" <?php echo ($obj->tipo != "fi_europa_facturas") ? 'style="display:none"' : ''; ?>>
                            <label for="ref"><i class="fa fa-fw fa-asterisk"></i> Tipo AEAT </label>
                            <select name="tipo_aeat" id="tipo_aeat" class="form-control">
                                <!-- <option value="F1" <?php //echo ($obj->tipo_aeat  == "F1" || strval($obj->tipo_aeat) == "") ? 'selected' : ''; ?>>F1</option> -->
                                <option value="F1" <?php echo ($obj->tipo_aeat  == "F1" ||  ($obj->tipo=='fi_europa_facturas' &&  strval($obj->tipo_aeat) == "") ) ? 'selected' : ''; ?>>F1</option>
                                <option value="F2" <?php echo ($obj->tipo_aeat  == "F2") ? 'selected' : ''; ?>>F2</option>
                                <option value="F3" <?php echo ($obj->tipo_aeat  == "F3") ? 'selected' : ''; ?>>F3</option>
                                <option value="R1" <?php echo ($obj->tipo_aeat  == "R1") ? 'selected' : ''; ?>>Rectificativas</option>
                                <!--<option value="R2" <?php echo ($obj->tipo_aeat  == "R2") ? 'selected' : ''; ?> >R2</option>
                            <option value="R3" <?php echo ($obj->tipo_aeat  == "R3") ? 'selected' : ''; ?> >R3</option>
                            <option value="R4" <?php echo ($obj->tipo_aeat  == "R4") ? 'selected' : ''; ?> >R4</option>
                            <option value="R5" <?php echo ($obj->tipo_aeat  == "R5") ? 'selected' : ''; ?> >R5</option>-->

                            </select>
                        </div>

                    </div>



                    <div class="row mt-3">
                        <div class="col-md-6 plantilla">
                            <label for="ref"><i class="fa fa-fw fa-asterisk"></i> Plantilla PDF </label>
                            <select name="plantilla" id="plantilla" class="form-control" >
                                <option value="">Selecciona</option>
                            </select>
                        </div>
                        
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="ref"><i class="fa fa-fw fa-check-circle"></i> Serie Por defecto</label>
                            <select name="serie_por_defecto" id="serie_por_defecto" class="form-control">
                                <option value="1" <?php echo ((strval($obj->serie_por_defecto) == "1" || strval($obj->serie_por_defecto) == "") ? 'selected' : ''); ?>>Si</option>
                                <option value="0" <?php echo ((strval($obj->serie_por_defecto) == "0") ? 'selected' : ''); ?>>No</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="ref"><i class="fa fa-fw fa-check"></i> Serie se encuentra Activa</label>
                            <select name="serie_activa" id="serie_activa" class="form-control">
                                <option value="1" <?php echo ((strval($obj->serie_activa) == "1" || strval($obj->serie_activa) == "") ? 'selected' : ''); ?>>Activo</option>
                                <option value="0" <?php echo ((strval($obj->serie_activa) == "0") ? 'selected' : ''); ?>>Inactivo</option>

                            </select>
                        </div>
                    </div>



                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label for="ref"><i class="fa fa-fw fa-font "></i> Descripci&oacute;n de La Serie </label>
                            <textarea style="height: 100px;" name="serie_descripcion" id="serie_descripcion" class="form-control" <?php echo $disabled; ?>><?php echo $obj->serie_descripcion; ?></textarea>
                        </div>
                    </div>


                </div>


            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn btn-light-dark" data-bs-dismiss="modal"><i class="flaticon-cancel-12"></i>Cancelar</button>

            <?php if (empty($_REQUEST['fiche'])) { ?>
                <button type="button" class="btn btn-primary" id="agregar_parametro" onclick="crear_serie(event,null)"><i class="fa fa-fw fa-circle" aria-hidden="true"></i> Guardar</button>
            <?php } else { ?>
                <button type="button" class="btn btn-danger" id="borrar_parametro" onclick="confirma_eliminar(<?= $obj->id; ?>)"><i class="fa fa-fw fa-trash" aria-hidden="true"></i> Borrar</button>
                <button type="button" class="btn btn-primary" id="actualizar_parametro" onclick="crear_serie(event,<?= $obj->id; ?>)"><i class="fa fa-fw fa-circle" aria-hidden="true"></i> Actualizar</button>

            <?php
            } ?>

        </div>

    </div>
</div>