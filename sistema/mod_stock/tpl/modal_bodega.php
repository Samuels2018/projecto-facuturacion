<?php
  session_start();

  //si no hay usuario autenticado, cerrar conexion
  if (!isset($_SESSION['usuario'])) {
    echo acceso_invalido();
         exit(1);
  }

    include_once("../../conf/conf.php");
    include ENLACE_SERVIDOR . 'mod_stock/object/bodegas.object.php';
$obj = new Bodegas($dbh , $_SESSION['Entidad']) ;
 
 
if (!empty($_REQUEST['fiche'])) {
    $obj->fetch($_REQUEST['fiche']);
    /*if ($obj->entidad !== $_SESSION['Entidad']) {
         echo acceso_invalido();
         exit(1);
    }*/
    
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
    <h5 class="modal-title" id="exampleModalLabel"><?=$titulo?> Almacén</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

    </button>
</div>
<div class="modal-body">
    <form role="form" method="POST" action="" id="formulario">
        <input type="hidden" name="fiche" id="fiche" value="<?=$obj->id; ?>">

        <!-- left column -->

        <!-- general form elements -->


        <div class="card-body">
            
            <div class="row">
                <div class="col-md-6">
                    <label for="ref"><i class="fa fa-fw fa-asterisk"></i> Código</label>
                    <input required="required" maxlength="3" placeholder="Código del Almacén" type="text" name="label" id="label" class="form-control" value="<?php echo $obj->label; ?>" <?php echo $disabled; ?>>
                </div>

                <div class="col-md-6">
                    <label for="ref"><i class="fa fa-fw fa-asterisk"></i> Estado</label>
                    <select name="estado_bodega" id="estado_bodega" class="form-control">
                    <option value="1" <?php if (!isset($obj->activo) || $obj->activo == 1) echo 'selected'; ?>>Activo</option>
                    <option value="0" <?php if (isset($obj->activo) && $obj->activo == 0) echo 'selected'; ?>>Inactivo</option>


                    </select>
                </div>

                <div class="col-md-12">
                    <label for="ref"><i class="fa fa-fw fa-asterisk"></i> Descripción</label>
                    <textarea class="form-control" name="nota_bodega" id="nota_bodega" placeholder="Descripción del almacén" <?php echo $disabled; ?>><?php echo $obj->nota; ?></textarea>
                </div>

                <div class="col-md-12 mt-3">
                    <label>
                        <input type="checkbox" name="bodega_defecto" id="bodega_defecto" <?php echo ($obj->bodega_defecto == 1) ? 'checked' : ''; ?> <?php echo $disabled; ?>>
                        Bodega por defecto
                    </label>
                    <br>
                    <small class="form-text text-muted">Bodega Por Defecto para Facturación</small>
                </div>
            </div>

        </div>

    </form>
</div>
<div class="modal-footer">
    <button class="btn btn btn-light-dark" data-bs-dismiss="modal"><i class="flaticon-cancel-12"></i>Cancelar</button>

    <?php if (empty($_REQUEST['fiche'])) { ?>
        <button type="button" class="btn btn-primary" id="agregar_bodega" onclick="guardar(event)"><i class="fa fa-fw fa-circle" aria-hidden="true"></i> Guardar</button> 
     <?php }else{ ?>
        <button type="button" class="btn btn-danger" id="borrar_bodega" onclick="confirma_eliminar(<?=$obj->id; ?>)"><i class="fa fa-fw fa-trash" aria-hidden="true"></i> Borrar</button>
        <button type="button" class="btn btn-primary" id="agregar_bodega" onclick="guardar(event)"><i class="fa fa-fw fa-circle" aria-hidden="true"></i> Actualizar</button>
      
        <?php
     } ?>
 
</div>

</div>
</div>