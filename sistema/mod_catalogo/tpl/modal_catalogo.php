<?php
  session_start();

  // Si no hay usuario autenticado, cerrar conexión
  if (!isset($_SESSION['usuario'])) {
    echo acceso_invalido();
    exit(1);
  }

  include_once("../../conf/conf.php");
  include ENLACE_SERVIDOR . 'mod_catalogo/object/catalogo_object.php';
  $obj = new DiccionarioCatalogo($dbh);

  // Bloque validación entidad
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
            <h5 class="modal-title" id="exampleModalLabel"><?= $titulo ?> Unidad</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form role="form" method="POST" action="" id="formulario">
                <input type="hidden" name="fiche" value="<?php echo $_REQUEST['fiche'] ?>">
                <input type="hidden" name="correo_existe" id="correo_existe">

                <div class="card-body">
                    <div class="row">

                    <div class="col-md-12">
                            <label for="unidad_codigo"><i class="fa fa-fw fa-asterisk"></i> Código Unidad</label>
                            <input required="required" maxlength="7" placeholder="Código de la unidad" type="text" name="unidad_codigo" id="unidad_codigo" class="form-control" value="<?php echo $obj->codigo; ?>" <?php echo $disabled; ?>>
                        </div>
                        
                        <div class="col-md-12">
                            <label for="unidad_detalle"><i class="fa fa-fw fa-asterisk"></i> Detalle Unidad</label>
                            <input required="required" placeholder="Detalle de la unidad" type="text" name="unidad_detalle" id="unidad_detalle" class="form-control" value="<?php echo $obj->detalle; ?>" <?php echo $disabled; ?>>
                        </div>

                      

                        <div class="col-md-6">
                            <label for="unidad_tipo"><i class="fa fa-fw fa-asterisk"></i> Tipo</label>
                            <select name="unidad_tipo" id="unidad_tipo" class="form-control">
                                <option value="1" <?php if ($obj->tipo == 1) echo 'selected'; ?>>Producto</option>
                                <option value="2" <?php if ($obj->tipo == 2) echo 'selected'; ?>>Servicio</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="estado_unidad"><i class="fa fa-fw fa-asterisk"></i> Estado</label>
                            <select name="estado_unidad" id="estado_unidad" class="form-control">
                            <option value="1" <?php if (!isset($obj->activo) || $obj->activo == 1) echo 'selected'; ?>>Activo</option>
                            <option value="0" <?php if (isset($obj->activo) && $obj->activo == 0) echo 'selected'; ?>>Inactivo</option>
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn btn-light-dark" data-bs-dismiss="modal"><i class="flaticon-cancel-12"></i>Cancelar</button>

            <?php if (empty($_REQUEST['fiche'])) { ?>
                <button type="button" class="btn btn-primary" id="agregar_unidad" onclick="crear_unidad(event)"><i class="fa fa-fw fa-circle" aria-hidden="true"></i> Guardar</button> 
            <?php } else { ?>

                

                <button type="button" class="btn btn-danger" id="borrar_unidad" onclick="confirma_eliminar(<?= $obj->id; ?>)"><i class="fa fa-fw fa-trash" aria-hidden="true"></i> Borrar</button>
                

                <button type="button" class="btn btn-primary" id="actualizar_unidad" onclick="actualizar_unidad(<?= $obj->id; ?>)"><i class="fa fa-fw fa-circle" aria-hidden="true"></i> Actualizar</button>
            <?php } ?>
        </div>
    </div>
</div>
