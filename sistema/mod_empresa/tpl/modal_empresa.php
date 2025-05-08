<?php
  session_start();

  // Si no hay usuario autenticado, cerrar conexión
  if (!isset($_SESSION['usuario'])) {
    echo acceso_invalido();
    exit(1);
  }

  include_once("../../conf/conf.php");
  include ENLACE_SERVIDOR . 'mod_entidad/object/Entidad.object.php';
  $obj = new Entidad($dbh_plataforma);

  // Bloque validación entidad
  /*if (!empty($_REQUEST['fiche'])) {
   
      if ($obj->entidad != $_SESSION['Entidad']) {
          echo acceso_invalido();
          exit(1);
      }
  }*/

  $obj->fetch($_REQUEST['fiche']);
  if ($obj->id > 0) {
     $titulo = 'Modificar';
  } else {
      $titulo = 'Crear';
  }
?>

<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"><?= $titulo ?> Empresa</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form role="form" method="POST" action="" id="formulario">
                <input type="hidden" name="fiche" value="<?php echo $_REQUEST['fiche'] ?>">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="nombre_empresa"><i class="fa fa-fw fa-asterisk"></i> Nombre Empresa</label>
                            <input required="required" placeholder="Nombre de la empresa" type="text" name="nombre_empresa" id="nombre_empresa" class="form-control" value="<?php echo $obj->nombre; ?>" <?php echo $disabled; ?>>
                        </div>

                        <div class="col-md-6">
                            <label for="estado_empresa"><i class="fa fa-fw fa-asterisk"></i> Activo</label>
                            <select name="estado_empresa" id="estado_empresa" class="form-control">
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
                <button type="button" class="btn btn-primary" id="crearEmpresa" onclick="crearEmpresa(event)"><i class="fa fa-fw fa-circle" aria-hidden="true"></i> Guardar</button> 
            <?php } else { ?>


                <button type="button" class="btn btn-primary" id="actualizar_banco" onclick="actualizarEmpresa(<?= $obj->id; ?>)"><i class="fa fa-fw fa-circle" aria-hidden="true"></i> Actualizar</button>
            <?php } ?>
        </div>
    </div>
</div>
