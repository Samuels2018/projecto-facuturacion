<?php
  session_start();

  //si no hay usuario autenticado, cerrar conexion
  if (!isset($_SESSION['usuario'])) {
    echo acceso_invalido();
         exit(1);
  }

  include_once("../../conf/conf.php");
include ENLACE_SERVIDOR . 'mod_diccionario_prioridades/object/diccionario_prioridad_object.php';
$obj = new Diccionario_prioridad($dbh);


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
}else{
    $titulo = 'Crear';
}
?>


<div class="modal-dialog" role="document">
          <div class="modal-content">
           
     

<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel"><?=$titulo?> Prioridad</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

    </button>
</div>
<div class="modal-body">
    <form role="form" method="POST" action="" id="formulario">
        <!-- left column -->

        <!-- general form elements -->


        <div class="card-body">

            <div class="row">
                <div class="col-md-6">
                    <label for="ref"><i class="fa fa-fw fa-asterisk"></i> Etiqueta</label>
                    <input required="required" placeholder="Nombre Prioridad" type="text" name="etiqueta" id="etiqueta" class="form-control" value="<?php echo $obj->etiqueta; ?>" <?php echo $disabled; ?>>
                </div>

                <div class="col-md-6">
                    <label for="ref"><i class="fa fa-fw fa-asterisk"></i> Prioridad</label>
                    <input required="required" placeholder="Orden numerico" type="number" name="orden_prioridad" id="orden_prioridad" class="form-control" value="<?php echo $obj->prioridad; ?>" <?php echo $disabled; ?>>
                </div>

                <div class="col-md-6">
                    <label for="ref"><i class="fa fa-fw fa-asterisk"></i> Estilo</label>
                    <input required="required" placeholder="Clase bootstrap" type="text" name="estilo_prioridad" id="estilo_prioridad" class="form-control" value="<?php echo $obj->estilo; ?>" <?php echo $disabled; ?>>
                </div>



                <div class="col-md-6">
                    <label for="ref"><i class="fa fa-fw fa-asterisk"></i> Activo</label>
                    <select name="estado_prioridad" id="estado_prioridad" class="form-control">
                    <option value="1" <?php if ($obj->activo == 1) echo 'selected'; ?>>Activo</option>
                    <option value="0" <?php if ($obj->activo == 0) echo 'selected'; ?>>Inactivo</option>

                    </select>
                </div>
            </div>


        </div>


    </form>
</div>
<div class="modal-footer">
    <button class="btn btn btn-light-dark" data-bs-dismiss="modal"><i class="flaticon-cancel-12"></i>Cancelar</button>

    <?php if (empty($_REQUEST['fiche'])) { ?>
        <button type="button" class="btn btn-primary" id="agregar_prioridad" onclick="crear_prioridad(event)"><i class="fa fa-fw fa-circle" aria-hidden="true"></i> Guardar</button> 
     <?php }else{ ?>
        <button type="button" class="btn btn-danger" id="borrar_prioridad" onclick="borrar_prioridad(<?=$obj->id; ?>)"><i class="fa fa-fw fa-trash" aria-hidden="true"></i> Borrar</button>
        <button type="button" class="btn btn-primary" id="agregar_prioridad" onclick="actualizar_prioridad(<?=$obj->id; ?>)"><i class="fa fa-fw fa-circle" aria-hidden="true"></i> Actualizar</button>
      
        <?php
     } ?>
 
</div>

</div>
</div>