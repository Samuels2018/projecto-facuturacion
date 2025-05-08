<?php
  session_start();

  // Si no hay usuario autenticado, cerrar conexión
  if (!isset($_SESSION['usuario'])) {
    echo acceso_invalido();
    exit(1);
  }

  include_once("../../conf/conf.php");
  include ENLACE_SERVIDOR . 'mod_agente_rutas/object/agente_rutas_object.php';
  $obj = new Agente_rutas($dbh);
  $obj->entidad = $_SESSION['Entidad'];
  $lista_agentes = $obj->obtener_agentes();

  
 // Llamar a las funciones de obtener agentes y rutas
 $lista_agentes = $obj->obtener_agentes();
 $lista_rutas = $obj->obtener_rutas();  // Llama a obtener_rutas() y almacena el resultado


  // Bloque validación entidad
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
      <h5 class="modal-title" id="exampleModalLabel">Crea Nueva Ruta de Agente</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
      <form role="form" method="POST" action="" id="formulario">
        <input type="hidden" name="fiche" value="<?php echo $_REQUEST['fiche'] ?>">
        <input type="hidden" name="correo_existe" id="correo_existe">

        <!-- general form elements -->
        <div class="card-body">
          <div class="row">
            <!-- Campo FK Ruta -->
            <div class="col-md-6">
              <label for="descripcion"><i class="fa fa-car" aria-hidden="true"></i> Ruta</label>
                <select name="fk_ruta" id="fk_ruta" class="form-control">
                  <?php 
                      foreach($lista_rutas as $key => $value){
                  ?>  
                      <option <?php if($obj->fk_ruta == $lista_rutas[$key]['rowid']){ echo 'selected'; } ?> 
                              value="<?php echo $lista_rutas[$key]['rowid']; ?>">
                        <?php echo $lista_rutas[$key]['label']; ?>
                      </option>
                  <?php } ?>
                </select>
            </div>

                  <!-- Estado de la Ruta -->
            <div class="col-md-6">
            <label for="estado_ruta_agente"><i class="fa fa-fw fa-toggle-on" aria-hidden="true"></i> Activo</label>
              <select name="activo" id="activo_data" class="form-control">
                <option value="1" <?php if (!isset($obj->activo) || $obj->activo == 1) echo 'selected'; ?>>Activo</option>
                <option value="0" <?php if (isset($obj->activo) && $obj->activo == 0) echo 'selected'; ?>>Inactivo</option>
              </select>
            </div>

          </div>

          <div class="row">
            <!-- Campo FK Agente -->
            <div class="col-md-12 mt-2">
              <label for="fk_agente"><i class="fa fa-user" aria-hidden="true"></i>Agente</label>
              <input required="required" placeholder="Agente" type="text" name="fk_agente" id="fk_agente" class="form-control" value="<?php echo $obj->fk_ruta; ?>" <?php echo $disabled; ?>>
            </div>

          </div>
          <div class="row">

                        <?php 
                        $opciones="";
                        foreach ($Utilidades->obtener_estilos_bootstrap() as $key => $valor){
                            $opciones.= "<option value='{$valor['estilo']}'  ". ( ($obj->estilo==$valor['estilo']) ? 'selected="selected" ' :'') ." > ".$valor['estilo']."</option>";

                        }

                        ?>
                    </div>
        </div>
      </form>
    </div>

    <div class="modal-footer">
      <button class="btn btn-light-dark" data-bs-dismiss="modal"><i class="flaticon-cancel-12"></i> Cancelar</button>

      <?php if (empty($_REQUEST['fiche'])) { ?>
        <button type="button" class="btn btn-primary" id="agregar_agente_ruta" onclick="crear_agente_rutas(event)"><i class="fa fa-fw fa-circle" aria-hidden="true"></i> Guardar</button>
      <?php } else { ?>
        <button type="button" class="btn btn-danger" id="borrar_agente_ruta" onclick="confirma_eliminar(<?=$obj->id; ?>)"><i class="fa fa-fw fa-trash" aria-hidden="true"></i> Borrar</button>
        <button type="button" class="btn btn-primary" id="actualizar_agente_ruta" onclick="actualizar_agente_rutas(<?=$obj->id; ?>)"><i class="fa fa-fw fa-circle" aria-hidden="true"></i> Actualizar</button>
      <?php } ?>
    </div>
  </div>
</div>
