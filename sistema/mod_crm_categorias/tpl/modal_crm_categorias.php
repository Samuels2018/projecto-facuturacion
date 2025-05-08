<?php
  session_start();

  //si no hay usuario autenticado, cerrar conexion
  if (!isset($_SESSION['usuario'])) {
    echo acceso_invalido();
         exit(1);
  }

  include_once("../../conf/conf.php");
include ENLACE_SERVIDOR . 'mod_crm_categorias/object/crm_categorias.object.php';
$obj = new Categoria_crm($dbh);


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
        Categoria CRM Oportunidad</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

    </button>
</div>
<div class="modal-body">
    <form role="form" method="POST" action="" id="formulario">
        <input type="hidden" name="fiche" value="<?php echo $_REQUEST['fiche'] ?>">
        <input type="hidden" name="correo_existe" id="correo_existe">

        <!-- left column -->

        <!-- general form elements -->


        <div class="card-body">

        <div class="row">
    <div class="col-md-6">
        <label for="label"><i class="fa fa-fw fa-tag"></i> Etiqueta</label>
        <input required="required" placeholder="Nombre categoría" type="text" name="label" id="label" class="form-control" value="<?php echo $obj->label; ?>" <?php echo $disabled; ?>>
    </div>

    <div class="col-md-6">
        <label for="estado_categoria"><i class="fa fa-fw fa-toggle-on"></i> Activo</label>
        <select name="estado_categoria" id="estado_categoria" class="form-control">
        <option value="1" <?php if (!isset($obj->activo) || $obj->activo == 1) echo 'selected'; ?>>Activo</option>
        <option value="0" <?php if (isset($obj->activo) && $obj->activo == 0) echo 'selected'; ?>>Inactivo</option>

        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <label for="prioridad"><i class="fa fa-fw fa-sort-numeric-up"></i> Prioridad</label>
        <input required="required" placeholder="1" type="text" name="prioridad" id="prioridad" class="form-control" value="<?php echo $obj->prioridad; ?>" <?php echo $disabled; ?>>
    </div>

    <?php 
    $opciones="";
    foreach ($Utilidades->obtener_estilos_bootstrap() as $key => $valor){
        $opciones.= "<option value='{$valor['estilo']}'  ". ( ($obj->estilo==$valor['estilo']) ? 'selected="selected" ' :'') ." > ".$valor['estilo']."</option>";

    }

    ?>


    <div class="col-md-6">
        <label for="estilo"><i class="fa fa-fw fa-paint-brush"></i> Estilo</label>
        <select name="estilo" id="estilo" class="form-control" style="cursor:pointer;">
            <option value="">Selecciona Opcion</option>
                <?php echo $opciones; ?>
        </select>
    </div>
</div>




        </div>


    </form>
</div>
<div class="modal-footer">
    <button class="btn btn btn-light-dark" data-bs-dismiss="modal"><i class="flaticon-cancel-12"></i>Cancelar</button>

    <?php if (empty($_REQUEST['fiche'])) { ?>
        <button type="button" class="btn btn-primary" id="crear_categoria_crm" onclick="crear_categoria_crm(event)"><i class="fa fa-fw fa-circle" aria-hidden="true"></i> Crear </button> 
     <?php }else{ ?>
        <button type="button" class="btn btn-danger" id="borrar_categoria_crm" onclick="confirma_eliminar(<?=$obj->id; ?>)"><i class="fa fa-fw fa-trash" aria-hidden="true"></i> Borrar</button>
        <button type="button" class="btn btn-primary" id="actualizar_categoria_crm" onclick="actualizar_categoria_crm(<?=$obj->id; ?>)"><i class="fa fa-fw fa-circle" aria-hidden="true"></i> Actualizar</button>
      
        <?php
     } ?>
 
</div>

</div>
</div>