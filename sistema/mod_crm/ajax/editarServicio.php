<?php 
	
	if (!defined('ENLACE_SERVIDOR')) {
        session_start(); 
        require_once('../../conf/conf.php');
    }

    require_once ENLACE_SERVIDOR . 'mod_productos/object/productos.object.php';

    //Listado de productos
    $Productos = new Productos($dbh);
    $Productos->entidad = $_SESSION['Entidad'];
    $Productos->Impuestos(); // OBTENEMOS LOS IMPUESTOS

    $listado_impuestos = $Productos->impuestos;



    

    		$rowid = $_POST['rowid'];
    		$fk_oportunidad = $_POST['fk_cotizacion'];
		    // whereERE 
		    $where ='';
		    $where .=' WHERE  sc.fk_oportunidad  = '.$fk_oportunidad.' AND sc.rowid = '.$rowid.' ';

		    $sql= "SELECT
		                p.ref                       ,
		                p.label                     ,
		                sc.cantidad                 ,
		                sc.precio_unitario          ,
		                sc.precio_tipo_impuesto     ,
		                sc.precio_total,
		                sc.fk_producto,
                    sc.comentario,
                    sc.tipo_descuento,
		                sc.monto_descuento,
                    sc.precio_real,
		                sc.rowid AS cotizacion_rowid
		                FROM    fi_oportunidades_servicios   sc 
		        INNER   JOIN    fi_productos   p ON p.rowid  = sc.fk_producto ".$where."
		        order by sc.rowid DESC ";

		$db = $dbh->prepare($sql);
		$db->execute();
		$data = $db->fetch(PDO::FETCH_OBJ);

		$tipo_inpuesto = intval($data->precio_tipo_impuesto);

	$html =  '<div class="modal-dialog modal-dialog-centered" role="document">
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
              <input type="number" value="'.$data->cantidad.'" name="servicio_cantidad" id="servicio_cantidad_update" class="form-control ">
            </div>
            <div class="col-md-4">
              <label for="">Precio Unitario:</label>
              <input type="input" value="'.$data->precio_unitario.'" name="servicio_precio_unitario" id="servicio_precio_unitario_update" class="form-control">
              
              <input type="hidden" name="precio_real" id="precio_real_editar" value="'.$data->precio_real.'">
            ';

            if(intval($data->precio_real)>0)
            {
              $html.='<small class="precio_real_small" style="color:red;">Monto Real: '.$data->precio_real.'</small>';
            }

             
  $html.='
            </div>
            <div class="col-md-4">
              <label for="">Impuestos:</label>
              <select  name="servicio_precio_tipo_impuesto" id="servicio_precio_tipo_impuesto_update" class="form-control">';
                //obtenemos todos los impuestos y el seleccionado ya con el diccionario de impuestos
              foreach($listado_impuestos as $key => $value)
              {
                $html.='<option '.selected($listado_impuestos[$key]["impuesto"],$tipo_inpuesto).'  value="'.$listado_impuestos[$key]["impuesto"].'">'.$listado_impuestos[$key]["impuesto_texto"].'</option>';

              }

   $html.='</select>
            </div>
          </div>


             <div class="form-group col-md-12 mt-2">
              <div class="row mt-2">
                <div class="col-md-6">
                  <label for="">Tipo de descuento: '.$data->tipo_descuento.'</label>
                  <br>
                  <select name="tipo_descuento_editar" id="tipo_descuento_editar" class="form-control"> 
                      <option '.selectedString($data->tipo_descuento,"absoluto").'  value="absoluto">Neto</option>
                      <option '.selectedString($data->tipo_descuento,"porcentual").'  value="porcentual">%</option>
                  </select>
                </div>
                 <div class="col-md-6">
                  <label for="">Monto Descuento:</label>
                  <input type="input" value="'.$data->monto_descuento.'" name="monto_descuento_editar" id="monto_descuento_editar" class="form-control solonumerodecimal">
                </div>
              </div>
            </div>


            <div class="form-group col-md-12">
              <label for="">Comentario:</label>
              <textarea class="form-control" name="comentario" id="servicio_comentario_update" cols="30" rows="5">'.$data->comentario.'</textarea>
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

echo $html;

function selected($compare, $value)
{	
	if(intval($compare) === intval($value))
	{
		return 'selected';
	}

}

function selectedString($compare, $value)
{ 
  if($compare === $value)
  {
    return 'selected';
  }

}

?>