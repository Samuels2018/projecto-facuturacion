<?php

class GuardarCambios{

	function grabar_valor($dbh, $tabla, $id_documento, $campo, $valor){
		$campos_adicionales_update = [];
		$campos_sql_update = [];
		
		switch ($campo) {
				// Mejora para la factura electronica
			case 'condicion_venta':
				$campo = 'tipo';
				$nombre_select = 'condicion_venta';
				$tabla = 'diccionario_condiciones_venta';
				$tipo = "select";
				break;
	
			case 'condicion_pago':
				$campo = 'tipo';
				$nombre_select = 'condicion_pago';
				$tabla = 'diccionario_medio_pago';
				$tipo = "select";
				break;
	
			case 'moneda_tipo_cambio':
				$campo = 'moneda_tipo_cambio';
				$tipo = "text";
				break;
	
			case 'fk_tercero':
				$campo = 'fk_tercero';
				$nombre_select = 'asesor_comercial_txt';
				$tipo = "text";
				if($valor!='' && $valor != 0){
					$campos_adicionales_update = array_merge($campos_adicionales_update, ['fk_tercero_txt', 'fk_tercero_identificacion', 'fk_tercero_telefono', 'fk_tercero_email', 'fk_tercero_direccion']);
					$campos_sql_update = array_merge($campos_sql_update, [
						"(SELECT (CASE tipo WHEN 'fisica' THEN CONCAT(nombre, ' ',apellidos) ELSE nombre END) AS nombre FROM fi_terceros WHERE rowid = ".$valor.")",
						"(SELECT cedula FROM fi_terceros WHERE rowid = ".$valor.")",
						"(SELECT telefono FROM fi_terceros WHERE rowid = ".$valor.")",
						"(SELECT email FROM fi_terceros WHERE rowid = ".$valor.")",
						"(SELECT CONCAT(IFNULL(pais.nombre,''), '-', IFNULL(ccaa.nombre,''), '-', IFNULL(prov.provincia,''), '-', IFNULL(t.codigo_postal,''), '-', IFNULL(t.direccion,'')) 
                            FROM fi_terceros t
                            LEFT JOIN ".$_ENV['DB_NAME_UTILIDADES_APOYO'].".diccionario_paises pais ON pais.rowid = t.fk_pais
                            LEFT JOIN ".$_ENV['DB_NAME_UTILIDADES_APOYO'].".diccionario_comunidades_autonomas_provincias prov ON prov.id = t.direccion_fk_provincia
                            LEFT JOIN ".$_ENV['DB_NAME_UTILIDADES_APOYO'].".diccionario_comunidades_autonomas ccaa ON ccaa.id = t.fk_poblacion WHERE t.rowid = ".$valor.")",
					]);
				}else{
					$campos_adicionales_update = array_merge($campos_adicionales_update, ['fk_tercero_txt', 'fk_tercero_identificacion', 'fk_tercero_telefono', 'fk_tercero_email', 'fk_tercero_direccion']);
					$campos_sql_update = array_merge($campos_sql_update, [
						"NULL",
						"NULL",
						"NULL",
						"NULL",
						"NULL",
					]);
				}
				break;
	
			case 'fecha':
				$campo = 'fecha';
				$tipo = "text_blur";
				break;
			case 'fecha_vencimiento':
				$campo = 'fecha_vencimiento';
				$tipo = "text_blur";
				break;
			case 'fecha_entrega':
				$campo = 'fecha_entrega';
				$tipo = "text";
				break;
			case 'asesor_comercial_txt':
				$campo = 'fk_agente';
				$tipo = "select";
				$campos_adicionales_update = array_merge($campos_adicionales_update, ['asesor_comercial_txt']);
				$campos_sql_update = array_merge($campos_sql_update, [
					"(SELECT nombre FROM fi_agentes WHERE rowid= ".$valor.")",
				]);
				break;
			case 'detalle':
				$campo = 'detalle';
				$tipo = "textarea";
				break;
			case 'notaGeneral':
				$campo = 'notageneral';
				$tipo = "textarea";
				break;
	
				// select desde DB
			case 'forma_pago':
				$campo = 'forma_pago';
				$tipo = "select";
				$campos_adicionales_update = array_merge($campos_adicionales_update, ['forma_pago_txt']);
				$campos_sql_update = array_merge($campos_sql_update, [
					"(SELECT label FROM diccionario_formas_pago WHERE rowid= ".$valor.")",
				]);
				break;
	
			case 'fk_proyecto':
				$campo = 'fk_proyecto';
				$tipo = "select";
				$campos_adicionales_update = array_merge($campos_adicionales_update, ['proyecto_txt']);
				$campos_sql_update = array_merge($campos_sql_update, [
					"(SELECT nombre FROM fi_proyectos WHERE rowid= ".$valor.")",
				]);
				break;
	
				// Select Booleano!\
			case 'tipo':
				$campo = 'tipo';
				$tipo = "select";
				break;
			case 'moneda':
				$campo = 'moneda';
				$tipo = "select";
				break;
			case 'actividad':
				$campo = 'actividad';
				$tipo = "select";
				break;
	
			case 'pago_1':
				$campo = 'pago_1';
				$tipo = "checkbox";
				$valor = (($valor == "true") ? 1 : 0);
				break;
			case 'pago_2':
				$campo = 'pago_2';
				$tipo = "checkbox";
				$valor = (($valor == "true") ? 1 : 0);
				break;
			case 'pago_3':
				$campo = 'pago_3';
				$tipo = "checkbox";
				$valor = (($valor == "true") ? 1 : 0);
				break;
			case 'pago_4':
				$campo = 'pago_4';
				$tipo = "checkbox";
				$valor = (($valor == "true") ? 1 : 0);
				break;
			case 'pago_5':
				$campo = 'pago_5';
				$tipo = "checkbox";
				$valor = (($valor == "true") ? 1 : 0);
				break;
			case 'pago_99':
				$campo = 'pago_99';
				$tipo = "checkbox";
				$valor = (($valor == "true") ? 1 : 0);
				break;
	
			case 'referencia_serie':
				$campo = 'referencia_serie';
				$tipo = "text";
				break;
			case 'serie_proveedor':
				$campo = 'serie_proveedor';
				$tipo = "text";
				break;
		}
	
		//INICIO: Actualiza en BBDD el campo que está siendo modificado
		$where = '';

		// Campos que no incluirán validación del Estado del documento
		$campos_permitidos_sin_validar_estado = ['fk_proyecto', 'fk_agente', 'serie_proveedor'];

		if(in_array($campo, $campos_permitidos_sin_validar_estado)){
			$where = "  rowid = :rowid  and entidad = :entidad ";
		}else{
			$where = "  rowid = :rowid  and  estado = 0 and entidad = :entidad ";
		}
		$sql = "UPDATE " . $tabla . " set $campo  =  :valor where ".$where;
		$db = $dbh->prepare($sql);
		if ($campo == 'fk_tercero' && $valor == 0) {
			$valor = NULL;
		}
		if ($campo == 'fecha' || $campo == 'fecha_vencimiento') {
			if ($valor == '' || $valor == '0000-00-00') {
				$valor = date('Y-m-d');
			}
		}
		$db->bindValue(':valor', $valor, PDO::PARAM_STR);
		$db->bindValue(':entidad', $_SESSION['Entidad'], PDO::PARAM_INT);
		$db->bindValue(':rowid', $id_documento, PDO::PARAM_INT);
		$funciono =  $db->execute();
		
		if(count($campos_adicionales_update) > 0){
			foreach ($campos_adicionales_update as $index => $field) {
				$setPart[] = "$field = {$campos_sql_update[$index]}";
			}
			$setClause = implode(', ', $setPart);
			$sql_update = "UPDATE ".$tabla." SET $setClause WHERE ". $where;
			$dbAdicional = $dbh->prepare($sql_update);
			$dbAdicional->bindValue(':entidad', $_SESSION['Entidad'], PDO::PARAM_INT);
			$dbAdicional->bindValue(':rowid', $id_documento, PDO::PARAM_INT);
			$dbAdicional->execute();
		}
		return array(
			'tipo' => $tipo,
			'funciono' => $funciono
		);
	}
}


session_start();

if (empty($_SESSION['Entidad'])) {
	exit(1);
}

require("../../conf/conf.php");

$tabla 			= $_POST['tipo'];
$id_documento 	= $_POST['documento'];
$campovalor = $_POST['campovalor'];
$campo = $_POST['campo'];
$valor = $_POST['valor'];

if($campovalor){ $campovalor = json_decode($campovalor); }else{$campovalor = [];}


switch ($tabla) {
	case 'fi_europa_albaranes_compras':
		break;
	case 'fi_europa_compras':
		break;
	case 'fi_europa_presupuestos':
		break;
	case 'fi_europa_facturas':
		break;
	case 'fi_europa_albaranes_ventas':
		break;
	case 'fi_europa_pedidos':
		break;
	default:
		$tabla = "";
		break;
}

if ($id_documento == '') {
	$respuesta['mensaje_txt_actualizado'] .= "Id Documento No recibido";
	$respuesta['error']	      = 1;
	echo json_encode($respuesta);
	exit(1);
}

$sql_get = "select * from {$tabla}  where rowid=:rowid and entidad = :entidad";
$db = $dbh->prepare($sql_get);
$db->bindValue(':entidad', $_SESSION['Entidad'], PDO::PARAM_INT);
$db->bindValue(':rowid', $id_documento, PDO::PARAM_INT);
$db->execute();
$row = $db->fetch(PDO::FETCH_ASSOC);



if ($campo != 'fk_tercero' && floatval($row['total']) == 0) {
	$respuesta['mensaje_txt_actualizado'] .= "ERROR:Importe del Documento debe ser > 0 <br/> No se ha actualizado El Documento" . $sql_get;
	$respuesta['error']	      = 1;
	echo json_encode($respuesta);
	exit(1);
} else {

	$grabar = new GuardarCambios();
	$resultado_grabar = $grabar->grabar_valor($dbh, $tabla, $id_documento, $campo, $valor);
	if(count($campovalor) > 0 ){
		foreach ($campovalor as $index => $field) {
			$grabar->grabar_valor($dbh, $tabla, $id_documento, $field->campo, $field->valor);	
		}
	}

	$funciono = $resultado_grabar['funciono'];
	$tipo = $resultado_grabar['tipo'];
		
	$callback_change = "actualizar_detalle_documento(this,'" . trim($tabla) . "'," . $id_documento . ")";
	
	$respuesta['mensaje_txt_actualizado'] = ($funciono) ? "✔ Dato Actualizado" : "Error Actualizando";
	$respuesta['error'] = ($funciono) ? 0 : 1;

	if ($funciono  > 0) {
		switch ($tipo) {
			case 'checkbox':
				$sql = " select $campo  from  " . $tabla . "  where rowid = :rowid  ";
				$db = $dbh->prepare($sql);
				$db->bindValue(':rowid', $id_documento, PDO::PARAM_INT);
				$db->execute();
				$datos = $db->fetch(PDO::FETCH_ASSOC);


				$respuesta['mensaje_txt'] .= '<input 
							style = "cursor:pointer;"
							type  = "' . $tipo . '"  
							name  = "' . $campo . '"  
							value = "1"
							' . (($datos[$campo] == 1)  ? 'checked="checked"' : '')
					. '"   onchange="' . $callback_change . '"  > '
					. $datos[$campo];
				break;
			case 'text':
			case "date":
				$respuesta['mensaje_txt'] .= '<input type="' . $tipo . '"  class="form-control-sm "  name="' . $campo . '" value="' . $valor . '"  onchange="' . $callback_change . '"  >';
				break;
			case 'textarea':
				$respuesta['mensaje_txt'] .= '<textarea type="' . $tipo . '"  class="form-control-sm " name="' . $campo . '"  onchange="' . $callback_change . '"   > ' . $valor . ' </textarea  >';
				break;
			case 'text_blur':
				$respuesta['mensaje_txt'] = '<input type="' . $tipo . '"  class="form-control-sm "  name="' . $campo . '" value="' . $valor . '"  onblur="' . $callback_change . '"  >';
			case 'select':
				if ($campo == 'moneda') {
					$respuesta['mensaje_txt'] .= '<select   class="form-select form-control-sm "  name="' . $campo . '" onchange="' . $callback_change . '"  >'; ?>
					<option value="1" <?php $respuesta['mensaje_txt'] .= ($valor == 1) ? 'selected="selected"' : '' ?>>Colones</option>
					<option value="2" <?php $respuesta['mensaje_txt'] .= ($valor == 2) ? 'selected="selected"' : '' ?>>Dolares</option>
					<?php $respuesta['mensaje_txt'] .= '</select>';
				} elseif ($campo == "forma_pago") {
					$sql = " select $campo  from  " . $tabla . "  where rowid = :rowid  ";
					$db = $dbh->prepare($sql);
					$db->bindValue(':rowid', $id_documento, PDO::PARAM_INT);
					$db->execute();
					$datos = $db->fetch(PDO::FETCH_OBJ);

					$sql = "SELECT * FROM diccionario_formas_pago p WHERE entidad = " . $_SESSION['Entidad'] . " AND activo = 1 AND borrado = 0";

					$db2 = $dbh->prepare($sql);
					$db2->execute();
					$opcionActividad = "";
					while ($data = $db2->fetch(PDO::FETCH_OBJ)) {

						$selected = ($datos->forma_pago  == $data->rowid) ? 'selected="selected"'  :  '';
						$opcionActividad .= "<option $selected   value='" . $data->rowid . "' >" . $data->label . "</option>";
					}
					$respuesta['mensaje_txt'] = '<select  class="form-select form-control-sm "  id="' . $campo . '" name="' . $campo . '" onchange="' . $callback_change . '"  >';
					$respuesta['mensaje_txt'] .= "<option value='0'></option>";
					$respuesta['mensaje_txt'] .= $opcionActividad;
					$respuesta['mensaje_txt'] .= '</select>';
				} elseif ($campo == "fk_agente") {
					$sql = "SELECT * FROM fi_agentes a WHERE a.entidad = " . $_SESSION['Entidad'] . " AND a.borrado = 0";
					$db2 = $dbh->prepare($sql);
					$db2->execute();
					$opcionAgente = "";
					while ($data = $db2->fetch(PDO::FETCH_OBJ)) {

						$selected = ($valor  == $data->rowid) ? 'selected="selected"'  :  '';
						$opcionAgente .= "<option $selected   value='" . $data->rowid . "' >" . $data->nombre . "</option>";
					}
					$respuesta['mensaje_txt'] .= '<select  class="form-select form-control-sm "  id="asesor_comercial_txt" name="asesor_comercial_txt" onchange="' . $callback_change . '"  >';
					$respuesta['mensaje_txt'] .= "<option value='0'></option>";
					$respuesta['mensaje_txt'] .= $opcionAgente;
					$respuesta['mensaje_txt'] .= '</select>';
				} else if ($campo == 'fk_proyecto') {

					$sql = "SELECT * FROM fi_proyectos a WHERE a.entidad = " . $_SESSION['Entidad'] . " AND a.borrado = 0 AND a.estado = 1";
					$db2 = $dbh->prepare($sql);
					$db2->execute();
					$optionproyecto = "";
					while ($data = $db2->fetch(PDO::FETCH_OBJ)) {

						$selected = ($valor  == $data->rowid) ? 'selected="selected"'  :  '';
						$optionproyecto .= "<option $selected   value='" . $data->rowid . "' >" . $data->nombre . "</option>";
					}
					$respuesta['mensaje_txt'] .= '<select class="form-select form-control-sm" id="fk_proyecto" name="fk_proyecto" onchange="actualizar_detalle_documento(this, \'' . addslashes($tabla) . '\', \'' . addslashes($id_documento) . '\')">';

					$respuesta['mensaje_txt'] .= "<option value='0'></option>";
					$respuesta['mensaje_txt'] .= $optionproyecto;
					$respuesta['mensaje_txt'] .= '</select>';
				} 
				// else {
				// 	$respuesta['mensaje_txt'] .= '<select  class="form-control"  name="' . $nombre_select . '" onchange="' . $callback_change . '"  >';
				// 	$respuesta['mensaje_txt'] .= muestra_select($tabla, $_POST['valor']);
				// 	$respuesta['mensaje_txt'] .= '</select>';
				// }
				break;
			default:
				break;
		}
		echo json_encode($respuesta);
		exit(1);
	} else {
		// $respuesta['mensaje_txt'].="Ocurrio Un Error  <span style='cursor:pointer;' onClick='window.location=\"" . ENLACE_WEB . "dashboard.php?accion=ver_factura&fiche=" . $id_documento . "\"' class='badge bg-red'><i class='fa fa-fw fa-times-circle-o'></i></span>";
		$respuesta['mensaje_txt_actualizado'] .= "ERROR:Importe de la factura debe ser > 0 <br/> No se ha actualizado la Factura";
		$respuesta['error'] = 1;
		echo json_encode($respuesta);
		exit(1);
	}
}

function muestra_select($campo, $seleccionado)
{

	global $dbh;
	if ($campo = "diccionario_condiciones_venta") {
		$sql = "select * from diccionario_condiciones_venta   ";
	} else if ($campo == "diccionario_medio_pago") {
		$sql = "select * from diccionario_medio_pago ";
	}
	//$respuesta['mensaje_txt'].=$sql;

	$db = $dbh->prepare($sql);
	$db->execute();

	while ($salarios = $db->fetch(PDO::FETCH_ASSOC)) {

		if ($seleccionado == $salarios['rowid']) {
			$s = 'selected="selected"';
		} else {
			$s = "";
		}

		print '<option value="' . $salarios['rowid'] . '" ' . $s . ' >' . $salarios['label'] . ' ' . $salarios['etiqueta'] . ' </option>';
	}
}