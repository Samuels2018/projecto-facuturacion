<?php
  session_start();

  //si no hay usuario autenticado, cerrar conexion
  if (!isset($_SESSION['usuario'])) {
    echo acceso_invalido();
         exit(1);
  }

  include_once("../../conf/conf.php");
include ENLACE_SERVIDOR . 'mod_rutas/object/rutas.object.php';
$obj = new Diccionario_ruta($dbh);


    //bloque validacion entidad
    if (!empty($_REQUEST['fiche'])) {
        $obj->fetch($_REQUEST['fiche']);
        if ($obj->entidad !=  $_SESSION['Entidad']) {
            echo acceso_invalido();
            exit(1);
        }
    }


?>


<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">
                <?php echo (empty($obj->id)) ? "Crear":"Editar" ;     ?>
                Ruta</h5>
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
                            <label for="label"><i class="fa fa-fw fa-tag"></i>Nombre de la Ruta </label>
                            <input required="required" placeholder="Nombre Ruta" type="text" name="label" id="label" class="form-control" value="<?php echo $obj->label; ?>" <?php echo $disabled; ?>>
                        </div>  

                        <div class="col-md-6">
                            <label for="estado_categoria"><i class="fa fa-fw fa-toggle-on"></i> Estado</label>
                            <select name="estado_categoria" id="estado_categoria" class="form-control">
                            <option value="1" <?php if (!isset($obj->activo) || $obj->activo == 1) echo 'selected'; ?>>Activo</option>
                            <option value="0" <?php if (isset($obj->activo) && $obj->activo == 0) echo 'selected'; ?>>Inactivo</option>

                            </select>
                        </div>
                </div>

            </form>
</div>
<div class="modal-footer">
    <button class="btn btn btn-light-dark" data-bs-dismiss="modal"><i class="flaticon-cancel-12"></i>Cancelar</button>

    <?php if (empty($_REQUEST['fiche'])) { ?>
        <button type="button" class="btn btn-primary" id="crear_ruta" onclick="crear_ruta(event)"><i class="fa fa-fw fa-circle" aria-hidden="true"></i> Crear </button> 
     <?php }else{ ?>
        <button type="button" class="btn btn-danger" id="borrar_ruta" onclick="borrar_ruta(<?=$obj->id; ?>)"><i class="fa fa-fw fa-trash" aria-hidden="true"></i> Borrar</button>
        <button type="button" class="btn btn-primary" id="actualizar_ruta" onclick="actualizar_ruta(<?=$obj->id; ?>)"><i class="fa fa-fw fa-circle" aria-hidden="true"></i> Actualizar</button>
      
        <?php
     } ?>
 
</div>

</div>
</div>