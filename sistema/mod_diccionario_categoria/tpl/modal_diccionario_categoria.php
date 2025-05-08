<?php
  session_start();

  //si no hay usuario autenticado, cerrar conexion
  if (!isset($_SESSION['usuario'])) {
    echo acceso_invalido();
         exit(1);
  }

  include_once("../../conf/conf.php");
include ENLACE_SERVIDOR . 'mod_diccionario_categoria/object/diccionario_categoria_object.php';
$obj = new Diccionario_categoria($dbh);


//bloque validacion entidad
if (!empty($_REQUEST['fiche'])) {
    $obj->fetch($_REQUEST['fiche']);
    if (intval($obj->entidad) != intval($_SESSION['Entidad'])) {
         echo acceso_invalido();
         exit(1);
    }
}

if ($obj->id > 0) {
   $titulo = 'Modificar';
}else{
    $titulo = 'Crear';
}


//obtenemos las categorias padres
$obj->entidad = $_SESSION['Entidad'];
$categorias_padre = $obj->obtener_categorias_padre();


?>


<div class="modal-dialog" role="document">
          <div class="modal-content">
           
     

<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel"><?=$titulo?> categoría Producto</h5>
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

           

                <div class="col-md-9">
                    <label for="ref"><i class="fa fa-fw fa-asterisk"></i> Descripción</label>
                    <input required="required" placeholder="Nombre de categoría o subcategoría" type="text" name="label" id="label" class="form-control" value="<?php echo $obj->label; ?>" <?php echo $disabled; ?>>
                </div>


                <div class="col-md-3">
                    <label for="ref"><i class="fa fa-fw fa-asterisk"></i> Estado</label>
                    <select name="estado_diccionario_categoria" id="estado_diccionario_categoria" class="form-control">
                    <option value="1" <?php if (!isset($obj->activo) || $obj->activo == 1) echo 'selected'; ?>>Activo</option>
                    <option value="0" <?php if (isset($obj->activo) && $obj->activo == 0) echo 'selected'; ?>>Inactivo</option>

                    </select>
                </div>


                <div class="col-md-12">
                    <label for="ref"><i class="fa fa-fw fa-asterisk"></i> Categoría padre</label>
                


                  <select class="form-control" name="categoria_padre" id="categoria_padre" >
                                                                    <option value="">No aplica</option>
                                                                    <?php

                                                                    foreach ($categorias_padre as $categoria) {
                                                                        $selected = ($obj->fk_parent == $categoria->rowid) ? 'selected="selected"' : '';
                                                                        echo '<option value="' . $categoria->rowid . '" ' . $selected . '>' . $categoria->label . '</option>';
                                                                    }
                                                                    ?>
                                                                </select>

                </div>

            </div>


        </div>


    </form>
</div>
<div class="modal-footer">
    <button class="btn btn btn-light-dark" data-bs-dismiss="modal"><i class="flaticon-cancel-12"></i>Cancelar</button>

    <?php if (empty($_REQUEST['fiche'])) { ?>
        <button type="button" class="btn btn-primary" id="agregar_diccionario_categoria" onclick="crear_diccionario_categoria(event)"><i class="fa fa-fw fa-circle" aria-hidden="true"></i> Guardar</button> 
     <?php }else{ ?>
        <button type="button" class="btn btn-danger" id="borrar_diccionario_categoria" onclick="confirma_eliminar(<?=$obj->id; ?>)"><i class="fa fa-fw fa-trash" aria-hidden="true"></i> Borrar</button>
        <button type="button" class="btn btn-primary" id="agregar_diccionario_categoria" onclick="actualizar_diccionario_categoria(<?=$obj->id; ?>)"><i class="fa fa-fw fa-circle" aria-hidden="true"></i> Actualizar</button>
      
        <?php
     } ?>
 
</div>

</div>
</div>