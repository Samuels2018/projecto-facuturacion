<?php
  session_start();

  //si no hay usuario autenticado, cerrar conexion
  if (!isset($_SESSION['usuario'])) {
    echo acceso_invalido();
         exit(1);
  }

  include_once("../../conf/conf.php");
include ENLACE_SERVIDOR . 'mod_tiempo_entrega/object/tiempo_entrega_object.php';
$obj = new Tiempo_entrega($dbh);
$obj->entidad = $_SESSION['Entidad'];

//bloque validacion entidad
if (!empty($_REQUEST['fiche'])) {
    $obj->fetch($_REQUEST['fiche']);
    if (intval($obj->entidad) !== intval($_SESSION['Entidad'])) {
         echo acceso_invalido();
         exit(1);
    }
}

if ($obj->id > 0) {
   $titulo = 'Modificar';
}else{
    $titulo = 'Crear';
}
?>


<div class="modal-dialog" role="document">
          <div class="modal-content">
           
     

<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel"><?=$titulo?> Tiempo de entrega</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
    </button>
</div>
<div class="modal-body">
    <form role="form" method="POST" action="" id="formulario">
        <input type="hidden" name="fiche" value="<?php echo $obj->id; ?>">

        <!-- left column -->

        <!-- general form elements -->


        <div class="card-body">

            <div class="row">
                <div class="col-md-6">
                    <label for="ref"><i class="fa fa-fw fa-asterisk"></i> Descripción</label>
                    <input required="required" placeholder="Nombre Tiempo de entrega" type="text" name="label" id="label" class="form-control" value="<?php echo $obj->label; ?>" <?php echo $disabled; ?>>
                </div>

                <div class="col-md-6">
                    <label for="ref"><i class="fa fa-fw fa-asterisk"></i> Estado</label>
                    <select name="estado_tiempo_entrega" id="estado_tiempo_entrega" class="form-control">
                        <option value="1" <?php echo ( (strval($obj->activo) == "1" || strval($obj->activo) == "")? 'selected': ''); ?>>Activo</option>
                        <option value="0" <?php echo ( (strval($obj->activo) == "0")? 'selected': ''); ?>>Inactivo</option>
                    </select>
                </div>
            </div>


        </div>


    </form>
</div>
<div class="modal-footer">
    <button class="btn btn btn-light-dark" data-bs-dismiss="modal"><i class="flaticon-cancel-12"></i>Cancelar</button>

    <?php if (empty($_REQUEST['fiche'])) { ?>
        <button type="button" class="btn btn-primary" id="agregar" onclick="estado_accion = 1; guardar(event)"><i class="fa fa-fw fa-circle" aria-hidden="true"></i> Guardar</button> 
     <?php }else{ ?>
        <button type="button" class="btn btn-danger" id="borrar" onclick="estado_accion = 2; borrar(<?=$obj->id; ?>)"><i class="fa fa-fw fa-trash" aria-hidden="true"></i> Borrar</button>
        <button type="button" class="btn btn-primary" id="agregar" onclick="estado_accion = 1; guardar(event)"><i class="fa fa-fw fa-circle" aria-hidden="true"></i> Actualizar</button>
      
        <?php
     } ?>
 
</div>

</div>
</div>