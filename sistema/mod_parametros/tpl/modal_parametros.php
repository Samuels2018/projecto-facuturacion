<?php
  session_start();

  //si no hay usuario autenticado, cerrar conexion
  if (!isset($_SESSION['usuario'])) {
    echo acceso_invalido();
         exit(1);
  }

  include_once("../../conf/conf.php");
include ENLACE_SERVIDOR . 'mod_parametros/object/configuracion_parametros_object.php';
$obj = new Configuracion_parametros($dbh);


//bloque validacion entidad
/*
if (!empty($_REQUEST['fiche'])) {
   
    if ($obj->entidad != $_SESSION['Entidad']) {
         echo acceso_invalido();
         exit(1);
    }
}
    */
    $obj->fetch($_REQUEST['fiche']);

if ($obj->id > 0) {
   $titulo = 'Modificar';
}else{
    $titulo = 'Crear';
}
?>


<div class="modal-dialog" role="document">
          <div class="modal-content">
           
     

<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel"><?=$titulo?> Configuracion</h5>
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
                    <label for="ref"><i class="fa fa-fw fa-asterisk"></i> Configuracion</label>
                    <input required="required" placeholder="Nombre configuracion" type="text" name="configuracion_input" id="configuracion_input" class="form-control" value="<?php echo $obj->configuracion; ?>" <?php echo $disabled; ?>>
                </div>

                <div class="col-md-6">
                    <label for="ref"><i class="fa fa-fw fa-asterisk"></i> Activo</label>
                    <select name="activo_configuracion" id="activo_configuracion" class="form-control">
                    <option value="1" <?php echo ( (strval($obj->activo) == "1" || strval($obj->activo) == "")? 'selected': ''); ?>>Activo</option>
                    <option value="0" <?php echo ( (strval($obj->activo) == "0")? 'selected': ''); ?>>Inactivo</option>

                    </select>
                </div>

                <div class="col-md-12">
                    <label for="ref"><i class="fa fa-fw fa-asterisk"></i> Valor</label>
                    <textarea name="valor_input" id="valor_input" class="form-control" <?php echo $disabled; ?>><?php echo $obj->valor; ?></textarea>
                  
                </div>




            </div>


        </div>


    </form>
</div>
<div class="modal-footer">
    <button class="btn btn btn-light-dark" data-bs-dismiss="modal"><i class="flaticon-cancel-12"></i>Cancelar</button>

    <?php if (empty($_REQUEST['fiche'])) { ?>
        <button type="button" class="btn btn-primary" id="agregar_parametro" onclick="crear_configuracion(event)"><i class="fa fa-fw fa-circle" aria-hidden="true"></i> Guardar</button> 
     <?php }else{ ?>
        <button type="button" class="btn btn-danger" id="borrar_parametro" onclick="confirma_eliminar(<?=$obj->id; ?>)"><i class="fa fa-fw fa-trash" aria-hidden="true"></i> Borrar</button>
        <button type="button" class="btn btn-primary" id="actualizar_parametro" onclick="actualizar_configuracion(<?=$obj->id; ?>)"><i class="fa fa-fw fa-circle" aria-hidden="true"></i> Actualizar</button>
      
        <?php
     } ?>
 
</div>

</div>
</div>