<?php
SESSION_START();
// USER
if (empty($_SESSION['usuario'])) {
    header("location: " . ENLACE_WEB . "inicio/");
    exit(1);
}

include_once "../../conf/conf.php";

if (isset($_POST['rowid'])) {
    require_once ENLACE_SERVIDOR . "mod_redhouse_crm/object/actividades.object.php";
    //recibe un id
   $actividad = new Actividades($dbh);
   $actividad->rowid = $_POST['rowid'];
  $data =  $actividad->obtenerActividad();


//  var_dump($data);
//formatear fecha
$fecha = date("d-m-Y", strtotime($data->vencimiento_fecha));

//armar las opciones del select para pintarlo en el estado
foreach ($actividad->listaEstadoActividades() as $item) {
  if ($item['rowid'] == $data->fk_estado) {
    $options.='<option selected value="'.$item['rowid'].'">'.$item['etiqueta'].'</option>';
  }else {
    $options.='<option value="'.$item['rowid'].'">'.$item['etiqueta'].'</option>';
  }

}

if ($data->fk_estado != 3) {
  $hidden = 'style="display:none"';
}else{
  $hidden = '';
}


echo ' <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
<div class="modal-content">
  <div class="modal-header">
    <h5 class="modal-title" id="taskTitle">Editar actividad</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="close_modal_edit()"  >
      <span aria-hidden="true">&times;</span> 
    </button>
  </div>
  <div class="modal-body">
    <div class="form-group">


      <div class="form-row">

        <div class="form-group col-md-12">
            <label for="txtCedula"><i class="fa fa-calendar" aria-hidden="true"></i> Fecha vencimiento: <strong>'.$fecha.'</strong></label>
        
          </div>
        <div class="form-group col-md-12">
          <label for="actividad"><i class="fa fa-list" aria-hidden="true"></i> Tipo actividad: <strong>'.$data->nombre_actividad.'</strong></label>
         
        </div>

        <div class="form-group col-md-12">
            <label for="txtCedula"><i class="fa fa-user" aria-hidden="true"></i> Usuario responsable: <strong>'.$data->usuario.'</strong></label>
         
          </div>

          <div class="form-group col-md-12">
            <label for="txtCedula"><i class="fa fa-user" aria-hidden="true"></i> Estado Actividad:</label>
            <select onchange="mostrarComentario();" id="edit_estado_tarea" class="form-control">
            '.$options.'
            </select>
         
          </div>

    

        <div class="form-group col-md-12">
          <label for="txtCedula"><i class="fa fa-comment-o" aria-hidden="true"></i> Comentario:</label>
         <textarea class="form-control" name="" id="edit_comentario_tarea" cols="30" rows="10">'.$data->comentario.'</textarea>
        </div>

      </div>

      
      <div class="form-group col-md-12 div_cierre"'.$hidden.'>
      <label for="txtCedula"><i class="fa fa-comment-o" aria-hidden="true"></i> Comentario Cierre:</label>
     <input type="text" class="form-control" name="" id="edit_comentario_cierre_tarea" value="'.$data->comentario_cierre.'" >
    </div>

  </div>


      <div class="modal-footer">
        <button type="submit" class="btn btn-success" onclick="actualizarTarea('.$actividad->rowid.');">Guardar</button>
        <button type="button" class="btn btn-dark" data-dismiss="modal" onclick="close_modal_edit()"   >Cerrar</button>
      </div>


    </div>
  </div>


</div>
</div>';

}

