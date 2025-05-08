<?php
session_start();

//si no hay usuario autenticado, cerrar conexion
if (!isset($_SESSION['usuario'])) {
    echo acceso_invalido();
    exit(1);
}

include_once("../../conf/conf.php");
include ENLACE_SERVIDOR . 'mod_diccionario_moneda/object/moneda_object.php';
$obj = new Moneda($dbh);


//bloque validacion entidad
if (!empty($_REQUEST['fiche'])) {
    $obj->fetch($_REQUEST['fiche']);
    if ($obj->entidad != $_SESSION['Entidad']) {
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


<div class="modal-dialog" role="document">
    <div class="modal-content">

        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"><?= $titulo ?> Moneda</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

            </button>
        </div>
        <div class="modal-body">
            <form role="form" method="POST" action="" id="formulario">
                <input type="hidden" name="fiche" value="<?php echo $_REQUEST['fiche'] ?>">
                <input type="hidden" name="correo_existe" id="correo_existe">

                <div class="card-body">

                    <div class="row">
                        <div class="col-md-6">
                            <label for="ref"><i class="fa fa-fw fa-asterisk"></i> Etiqueta</label>
                            <input required="required" placeholder="Nombre Moneda" type="text" name="label" id="label" class="form-control" value="<?php echo $obj->etiqueta; ?>" <?php echo $disabled; ?>>
                        </div>

                        <div class="col-md-6">
                            <label for="ref"><i class="fa fa-fw fa-asterisk"></i> Símbolo</label>
                            <input required="required" placeholder="Símbolo" type="text" name="simbolo" id="simbolo" class="form-control" value="<?php echo $obj->simbolo; ?>" <?php echo $disabled; ?>>
                        </div>

                        <div class="col-md-6">
                            <label for="ref"><i class="fa fa-fw fa-asterisk"></i> Código</label>
                            <input required="required" placeholder="Código" type="text" name="codigo" id="codigo" class="form-control" value="<?php echo $obj->codigo; ?>" <?php echo $disabled; ?>>
                        </div>

                        <div class="col-md-6">
                            <label for="ref"><i class="fa fa-fw fa-asterisk"></i> Activo</label>
                            <select name="estado_moneda" id="estado_moneda" class="form-control">
                                <option value="1" <?php if ($obj->id==0){ echo 'selected'; } elseif ($obj->activo == 1) { echo 'selected'; } ?>>Activo</option>
                                <option value="0" <?php if ($obj->id!=0 && $obj->activo == 0) { echo 'selected'; } ?>>Inactivo</option>

                            </select>
                        </div>
                    </div>


                </div>


            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn btn-light-dark" data-bs-dismiss="modal"><i class="flaticon-cancel-12"></i>Cancelar</button>

            <?php if (empty($_REQUEST['fiche'])) { ?>
                <button type="button" class="btn btn-primary" id="agregar_moneda" onclick="crear_moneda(event)"><i class="fa fa-fw fa-circle" aria-hidden="true"></i> Guardar</button>
            <?php } else { ?>
                <button type="button" class="btn btn-danger" id="borrar_moneda" onclick="borrar_moneda(<?= $obj->id; ?>)"><i class="fa fa-fw fa-trash" aria-hidden="true"></i> Borrar</button>
                <button type="button" class="btn btn-primary" id="agregar_moneda" onclick="actualizar_moneda(<?= $obj->id; ?>)"><i class="fa fa-fw fa-circle" aria-hidden="true"></i> Actualizar</button>

            <?php
            } ?>

        </div>

    </div>
</div>