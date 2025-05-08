<?php
session_start();

// Si no hay usuario autenticado, cerrar conexión
if (!isset($_SESSION['usuario'])) {
    echo acceso_invalido();
    exit(1);
}

include_once("../../conf/conf.php");
include ENLACE_SERVIDOR . 'mod_productos/object/productos.object.php';
$obj = new Productos($dbh);

$data = $obj->fetch_politica($_POST['fiche']);
if ($data['exito'] == 0) {
    echo $data['mensaje'];
    die();
}

$obj = $data['data'];


if ($obj->rowid > 0) {
    $titulo = 'Modificar';
} else {
    $titulo = 'Crear';
}
?>

<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"><?= $titulo ?> política descuento</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form role="form" method="POST" action="" id="formulario">
                <input type="hidden" name="fiche" value="<?php echo $_REQUEST['fiche'] ?>">
                <input type="hidden" name="correo_existe" id="correo_existe">

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="fecha_inicial"><i class="fa fa-fw fa-asterisk"></i> Fecha Inicial</label>
                            <input required="required" placeholder="" type="date" name="fecha_inicial" id="fecha_inicial" class="form-control" value="<?php echo $obj->fecha_inicial; ?>" <?php echo $disabled; ?>>
                        </div>

                        <div class="col-md-6">
                            <label for="fecha_final"><i class="fa fa-fw fa-asterisk"></i> Fecha Final</label>
                            <input required="required" placeholder="" type="date" name="fecha_final" id="fecha_final" class="form-control" value="<?php echo $obj->fecha_final; ?>" <?php echo $disabled; ?>>
                        </div>

                    </div>
                    <div class="row mt-2 mb-4">
                        <div class="col-md-7">
                            <label for="tipo_politica"><i class="fa fa-fw fa-asterisk"></i> Politica de descuento por: </label>
                        </div>
                        <div class="col-md-5">
                            <select name="tipo_politica" id="tipo_politica" class="form-control">
                                <option value="cantidad">Cantidad</option>
                                <option value="monto">Monto</option>
                            </select>
                        </div>
                    </div>



                    <!-- aqui mostrar el detalle de politicas -->

                    <div class="row" id="detalle_politica" style="display: none;">
                        <hr>
                        <div class="col-md-4">
                            <label for="cantidad_politica"><i class="fa fa-fw fa-asterisk"></i> Valor</label>
                            <input required="required" placeholder="Valor" type="text" name="cantidad_politica" id="cantidad_politica" class="form-control" value="<?php echo $obj->cantidad_politica; ?>" <?php echo $disabled; ?>>
                        </div>

                        <div class="col-md-6">
                            <label for="porcentaje_politica"><i class="fa fa-fw fa-asterisk"></i> Porcentaje descuento</label>
                            <input required="required" placeholder="Porcentaje" type="text" name="porcentaje_politica" id="porcentaje_politica" class="form-control" value="<?php echo $obj->porcentaje_politica; ?>" <?php echo $disabled; ?>>
                        </div>


                        <div class="col-md-2">

                            <button style="display: none;" type="button" class="btn btn-primary mt-4" id="actualizar_politica" onclick="crear_politica_detalle(<?= $obj->id; ?>)"><i class="fa fa-fw fa-add" aria-hidden="true"></i></button>
                        </div>



                        <br>
                        <table class="table table-bordered mt-2">
                            <thead>
                                <tr>
                                    <th>Monto o cantidad vendida </th>

                                    <th>% Descuento</th>
                                    <th></th>

                                </tr>
                            </thead>
                            <tbody id="detalle_politicas">
                        
                               
                            </tbody>
                        </table>


                    </div>

                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn btn-light-dark" data-bs-dismiss="modal"><i class="flaticon-cancel-12"></i>Cancelar</button>

            <button type="button" class="btn btn-primary" id="agregar_politica" onclick="crear_politica(event)"><i class="fa fa-fw fa-circle" aria-hidden="true"></i> Guardar</button>



            <button style="display: none;" type="button" class="btn btn-danger" id="borrar_politica" onclick="confirma_eliminar_politica(<?= $obj->id; ?>)"><i class="fa fa-fw fa-trash" aria-hidden="true"></i> Borrar</button>




        </div>
    </div>
</div>