<?php
session_start();

//si no hay usuario autenticado, cerrar conexion
if (!isset($_SESSION['usuario'])) {
    echo acceso_invalido();
    exit(1);
}

include_once("../../conf/conf.php");
include ENLACE_SERVIDOR . 'mod_formas_pago/object/forma_pago_object.php';
$obj = new Forma_pago($dbh);
$obj->entidad = $_SESSION['Entidad'];

//bloque validacion entidad
if (!empty($_REQUEST['fiche'])) {
    $obj->fetch($_REQUEST['fiche']);
    if (intval($obj->entidad) != intval($_SESSION['Entidad'])) {
        echo acceso_invalido();
        exit(1);
    }
}

if ($obj->id > 0) {
    $titulo = 'Modificar';
} else {
    $titulo = 'Crear';
}
?>


<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">



        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"><?= $titulo ?> Forma de pago</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

            </button>
        </div>
        <div class="modal-body">
            <form role="form" method="POST" action="" id="forma_formulario">


                <!-- left column -->

                <!-- general form elements -->


                <div class="card-body">

                    <div class="row">
                        <div class="col-md-6">
                            <label for="ref"><i class="fa fa-fw fa-asterisk"></i> Descripción</label>
                            <input required="required" placeholder="Nombre forma de pago" type="text" name="forma_label" id="forma_label" class="form-control" value="<?php echo $obj->label; ?>" <?php echo $disabled; ?>>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="ref"><i class="fa fa-fw fa-asterisk"></i> Estado</label>
                            <select name="forma_estado" id="forma_estado" class="form-control">
                                <option value="1" <?php echo ( (strval($obj->activo) == "1" || strval($obj->activo) == "")? 'selected': ''); ?>>Activo</option>
                                <option value="0" <?php echo ( (strval($obj->activo) == "0")? 'selected': ''); ?>>Inactivo</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="switch form-switch-custom switch-inline form-switch-primary">
                                <input class="switch-input" type="checkbox" role="switch" id="forma_iguales" name="forma_iguales" <?php echo ($cliente->cliente == 1) ? 'checked' : ''; ?> <?php echo $disabled; ?>>
                                <label class="switch-label" for="tosell">Importes iguales</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="switch form-switch-custom switch-inline form-switch-primary">
                                <input class="switch-input" type="checkbox" role="switch" id="forma_ultimo" name="forma_ultimo" <?php echo ($cliente->cliente == 1) ? 'checked' : ''; ?> <?php echo $disabled; ?>>
                                <label class="switch-label" for="tosell">Ajustar el pago al último mes</label>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="flex col-4">
                            <button type="button" id="forma_addRow" class="btn btn-primary pull-right add-row"><i class="fa fa-plus"></i>&nbsp;&nbsp; Agregar pago</button>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">

                            <table id="forma_detail" class="table style-3 dt-table-hover" style="width:100%">
                                <thead>
                                    <tr>
                                        <th scope="col" >ID</th>
                                        <th style="width: 20%;" scope="col" >% PAGO</th>
                                        <th scope="col" >DIAS A VENCER</th>
                                        <th scope="col" ></th>
                                    </tr>
                                </thead>
                            </table>

                        </div>
                    </div>

                </div>

                <textarea id="forma_detalle_json" style="display:none;" ><?php echo json_encode($obj->detalle); ?></textarea>

            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn btn-light-dark" data-bs-dismiss="modal"><i class="flaticon-cancel-12"></i>Cancelar</button>

            <?php if (empty($_REQUEST['fiche'])) { ?>
                <button type="button" class="btn btn-primary" id="agregar_forma_pago" onclick="guardar_modal_forma_pago(event)"><i class="fa fa-fw fa-circle" aria-hidden="true"></i> Guardar</button>
            <?php } else { ?>
                <button type="button" class="btn btn-danger" id="borrar_forma_pago" onclick="borrar_forma_pago(<?= $obj->id; ?>)"><i class="fa fa-fw fa-trash" aria-hidden="true"></i> Borrar</button>
                <button type="button" class="btn btn-primary" id="agregar_forma_pago" onclick="guardar_modal_actualiza_formapago(<?= $obj->id; ?>)" ><i class="fa fa-fw fa-circle" aria-hidden="true"></i> Actualizar</button>

            <?php
            } ?>

        </div>

    </div>
</div>

<script src="./mod_formas_pago/tpl/forma_pago_detail.js?v=4"></script>
<style> 
.form-control { width: 100%; } 
</style>