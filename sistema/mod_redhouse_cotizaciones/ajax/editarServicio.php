<?php 
	
	if (!defined('ENLACE_SERVIDOR')) {
        session_start(); 
        require_once('../../conf/conf.php');
    }
    
    require(ENLACE_SERVIDOR . 'mod_productos/object/productos.object.php');

    $productos = new Productos($dbh);
    $productos->entidad = $_SESSION['Entidad'];
  //$productos->Impuestos();

    		$rowid = $_POST['rowid'];
    		$fk_cotizacion = $_POST['fk_cotizacion'];
		    // whereERE 
		    $where ='';
		    $where .=' WHERE  sc.fk_cotizacion = '.$fk_cotizacion.' AND sc.rowid = '.$rowid.' ';

		    $sql= "SELECT
		                p.ref                       ,
		                p.label                     ,
                    sc.cantidad                 ,
		                sc.cantidad_dias            ,
                    sc.tipo_duracion            ,
		                sc.precio_unitario          ,
		                sc.precio_tipo_impuesto     ,
		                sc.precio_total,
		                sc.fk_producto,
		                sc.comentario,
		                sc.rowid AS cotizacion_rowid
		                FROM    a_medida_redhouse_cotizaciones_cotizaciones_servicios   sc 
		        INNER   JOIN    fi_productos   p ON p.rowid  = sc.fk_producto ".$where."
		        order by sc.rowid DESC ";

		$db = $dbh->prepare($sql);
		$db->execute();
		$data = $db->fetch(PDO::FETCH_OBJ);

		$tipo_inpuesto = intval($data->precio_tipo_impuesto);

    $productos->Impuestos();

         foreach ( $productos->impuestos as $clave => $valor) {
             $options.= "<option value='".$valor['impuesto']."'>".$valor['impuesto']."% - ".$valor['impuesto_texto']."</option>";
         }

	echo '<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="serviceModalTitle">Editar Servicio</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <div class="form-row">
            <div class="form-group col-md-12">
              <label for="actividad">Servicio:</label>
              <input autocomplete="off" class="form-control ui-autocomplete-input"  id="servicio_descripcion_update" value="'.$data->label.'" readonly name="servicio_descripcion" class="form-control">
              <input type="hidden" value="'.$data->fk_producto.'" id="servicio_fk_producto_update">
              <input type="hidden" value="'.$data->cotizacion_rowid.'" id="servicio_fk_row_id_update">
            </div>
          </div>
 
          <div class="row mt-2">
            <div class="col-md-2">
              <label for="">Cant:</label>
              <input type="number" value="'.$data->cantidad.'" name="servicio_cantidad" id="servicio_cantidad_update" class="form-control">
            </div>
            <div class="col-md-4">
              <label for="">Precio Unitario:</label>
              <input type="input"  value="'.$data->precio_unitario.'" name="servicio_precio_unitario" id="servicio_precio_unitario_update" class="form-control">
            </div>
            <div class="col-md-4">
              <label for="">Impuestos:</label>
              <select  name="servicio_precio_tipo_impuesto" id="servicio_precio_tipo_impuesto_update" class="form-control">
                  '.$options.'
                </select>
            </div>
          </div>

          <div class="form-group col-md-12">
              <label for="">Comentario:</label>
              <textarea class="form-control" name="comentario" id="servicio_comentario_update" cols="30" rows="5">'.$data->comentario.'</textarea>
            </div>

          <div class="row">
              <div class="form-group col-md-6 mt-2">
                <label>Dias: </label>
                <input type="number" value="'.$data->cantidad_dias.'" id="cantidad_dias_update" name="cantidad_dias" class="form-control">
                <small>Cantidad de días que ocupara este Articulo</small>
              </div>
              
              <div class="form-group col-md-6 mt-2">
                <label>Horas: </label>
                <input type="number" name="tipo_duracion_editar" id="tipo_duracion_editar" value="'.$data->tipo_duracion.'" class="form-control">
                
                <small>Cantidad de horas que ocupara por dia en el evento</small>
                
              </div>
            </div>
            <div class="form-group col-md-12 mt-2">
          </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-light-dark _effect--ripple waves-effect waves-light" data-bs-dismiss="modal">Cancelar</button>
        <button onclick="actualizar_servicio();" type="button" class="btn btn-primary _effect--ripple waves-effect waves-light">Actualizar</button>
      </div>
    </div>
  </div>
';



function selected($compare, $value)
{	
	if($compare === $value)
	{
		return 'selected';
	}

}



?>