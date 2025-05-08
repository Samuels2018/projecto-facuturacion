<?php

if (!defined('ENLACE_SERVIDOR')) {
    session_start();
    require_once "../../conf/conf.php";
}
$error              = false;
$facturar_sin_stock = false;

if ($_SESSION['usuario'] == NULL) {
    exit(1);
}


//--------------------------------------------------------------------------
//
//  Objeto 
//
require_once ENLACE_SERVIDOR . 'mod_europa_compra/object/compras.object.php';
require_once ENLACE_SERVIDOR . 'mod_productos/object/productos.object.php';

$productos  = new Productos($dbh, $_SESSION["Entidad"]);
$document_id_inicial = $_REQUEST['fiche'];
$document_id = $_REQUEST['fiche'];

$Documento2  = new Compra($dbh, $_SESSION['Entidad']);
$datos_tooltip = [];
if ($document_id != '') {
    $Documento2->fetch($document_id);

    $Documento2->obtener_documentos_destino();
    $datos_tooltip = $Documento2->obtener_historial_detalle();
} else {
    $Documento2->moneda = $Documento2->configuracion['sistema_transacciones_fk_moneda'];

    $Documento2->fk_tercero = $_POST["fk_tercero"];
    $Documento2->forma_pago = $_POST["forma_pago"];

    $Documento2->fk_agente = $_POST["asesor_comercial_txt"];
    $Documento2->fecha = $_POST["fecha"];
    $Documento2->fecha_vencimiento = $_POST["fecha_vencimiento"];


    $document_id = $Documento2->Crear($_SESSION['usuario']);

    $serie_proveedor = $_POST["serie_proveedor"];
    if ($serie_proveedor != '') {
        $Documento2->serie_proveedor = $serie_proveedor;
        $Documento2->id = $document_id;
        $Documento2->actualizar_compra();
    }
    $mensaje_javascript[] = "Creando " . $Documento2->documento_txt['singular'];
}

// $disable_input = "disabled=''";
$disable_input = "";

// Recupero la línea antes de Actualizar o Eliminar
$linea_md5 = $_POST['linea'];
if (!empty($linea_md5)) {
    $sqlValida = "SELECT f.* FROM {$Documento2->documento_detalle} f WHERE f.fk_documento = :documento AND md5(f.rowid) = :linea LIMIT 1";
    $dbValida = $dbh->prepare($sqlValida);
    $dbValida->bindValue(":documento", $document_id, PDO::PARAM_INT);
    $dbValida->bindValue(":linea", $linea_md5, PDO::PARAM_STR);
    $dbValida->execute();
    $rowValida = $dbValida->fetch(PDO::FETCH_ASSOC);
    if (!$dbValida) {
        $this->sql     =   $sql;
        $this->error   =   implode(", ", $dbh->errorInfo()) . " " . implode(" , ", $dbh->errorInfo());
        $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
        $this->Error_SQL();
    }
}
// Recupero la línea antes de Actualizar o Eliminar

if (
    !empty($document_id) and
    !empty($_POST['nombre']) and
    !empty($_POST['cantidad']) and
    !empty($_POST['precio_unitario']) or ($_POST['total_linea'] > 0)
) {
    $Documento2->cantidad           =  $_POST['cantidad'];
    $Documento2->precio_unitario    =  $_POST['precio_unitario'];
    $Documento2->label              =   (empty($_POST['nombre'])) ? ' ' : $_POST['nombre'];
    $Documento2->fk_producto        = $_POST['fk_producto'];
    $Documento2->descuento          = $_POST['descuento'];
    $Documento2->descuento_tipo     = $_POST['descuento_tipo'];
    $Documento2->tipo_impuesto          = $_POST['impuesto'];
    $Documento2->recargo_equivalencia   = $_POST['recargo_equivalencia'];
    $Documento2->retencion              = $_POST['retencion'];
    $Documento2->detalle     = $_POST['detalle'];

    if (!empty($linea_md5)) {
        $Documento2->lineaMd5     = $linea_md5;

        // Validar si la linea proviene de un Albaran Y LA LINEA VIENE DE UN ALBARÁN TAMBIEN.
        // Se utilizará la columna origen_documento de la Compra para validar esto        
        if ($rowValida) {
            if ($rowValida["origen_documento"] == '') {
                $validacion_limite_permitido = $Documento2->actualizar_detalle(false);
            } else {
                $validacion_limite_permitido = $Documento2->actualizar_detalle(true);
                if ($validacion_limite_permitido) {
                    $Documento2->usuario = $_SESSION['usuario'];
                    $Documento2->actualiza_documento_ligado();
                } else {
                    http_response_code(500);
                    echo json_encode(['error' => 'La cantidad no corresponde con el total disponible.']);
                    return;
                }
            }
        }
        $Documento2->fetch($document_id);
    } else {
        $Documento2->crear_detalle();
        $Documento2->fetch($document_id);
    }
} else if (!empty($linea_md5)) {
    $Documento2->lineaMd5     = $linea_md5;
    $Documento2->usuario      = $_SESSION['usuario'];
    if ($rowValida) {
        if ($rowValida["origen_documento"] != '') {
            $Documento2->actualiza_documento_ligado(true);
        }
    }
    $Documento2->eliminar_linea($linea_md5);

    $Documento2->fetch($document_id);
}

$_SESSION['permitir_inventario_negativo'];
$_SESSION['utiliza_inventario'];
$iterador = 0; // muestra para numerar cada linea

$sql = "SELECT det.origen_documento, 
                det.destino_cantidad,
	            COALESCE(origen_compras.referencia, origen_ventas.referencia, origen_facturas.referencia) AS mov_referencia, 
                p.stock as stock,
	f.*, f.rowid AS id_item
    FROM   " . $Documento2->documento_detalle . " f
    LEFT JOIN fi_productos p ON p.rowid = f.fk_producto 
    
	 LEFT JOIN fi_europa_documentos_movimientos_detalles det ON det.destino_documento = '" . $Documento2->documento . "' 
	 AND det.destino_fk_documento = f.fk_documento AND det.destino_fk_documento_detalle = f.rowid
	 
	LEFT JOIN fi_europa_albaranes_compras origen_compras ON origen_compras.rowid = det.origen_fk_documento AND det.origen_documento = 'fi_europa_albaranes_compras'
	LEFT JOIN fi_europa_albaranes_ventas origen_ventas ON origen_ventas.rowid = det.origen_fk_documento AND det.origen_documento = 'fi_europa_albaranes_compras'
	LEFT JOIN fi_europa_facturas origen_facturas ON origen_facturas.rowid = det.origen_fk_documento AND det.origen_documento = 'fi_europa_facturas'
	 
    WHERE IFNULL(det.borrado,0) = 0 AND f.fk_documento = ?
    GROUP BY f.rowid
    ORDER BY 
    CASE 
        WHEN num_linea IS NULL THEN 99999
        ELSE 0 
    END, 
    num_linea ASC;
";


$db = $dbh->prepare($sql);
$db->bindValue(1, $document_id, PDO::PARAM_INT);
$db->execute();
// print_r($db->errorInfo());
// print_r($dbh->errorInfo());
$contado = 0;
$tr      = "";

if ($error) {
    $tr .= "<tr><td colspan='6' > $error_txt </td></tr>";
}

$subtotal                 = 0;
$impuesto                 = 0;
$total                    = 0;
$descuentos               = 0;
$subtotal_precio_original = 0;
$eliminar                 = "";
$row_id_anterior = "";


function procesarJson($json, $rowid)
{
    $current_albaran = '';
    foreach ($json as $item) {
        if ($item['rowid'] == $rowid) {
            $current_albaran = $item['referencia_cab'];
            break;
        }
    }
    // Decodificar el JSON
    $data = $json;
    $html = '';

    // Agrupar datos por 'referencia_cab'
    $agrupados = [];
    foreach ($data as $item) {
        $referencia_cab = $item['referencia_cab'];
        if (!isset($agrupados[$referencia_cab])) {
            $agrupados[$referencia_cab] = [
                'cantidad_cab' => floatval(str_replace(",", ".", $item['cantidad_cab'])),
                'detalles' => []
            ];
        }
        $agrupados[$referencia_cab]['detalles'][] = [
            'referencia_det' => $item['referencia_det'],
            'rowid' => $item["rowid"],
            'cantidad_det' => floatval(str_replace(",", ".", $item['cantidad_det']))
        ];
    }
    // echo json_encode($agrupados) . '<br/>';

    // Generar salida
    foreach ($agrupados as $referencia_cab => $info) {
        
        if ($current_albaran == $referencia_cab) {
            // Imprimir referencia cabecera y cantidad cabecera
            $html = "<tr><td><b>$referencia_cab</b></td><td><b>" . number_format($info['cantidad_cab'], 2) . "</b></td></tr>";

            $disponible = $info['cantidad_cab'];

            // Imprimir detalles
            foreach ($info['detalles'] as $detalle) {
                $referencia_det = $detalle['referencia_det'];
                $cantidad_det = $detalle['cantidad_det'];
                $html .= "<tr><td>$referencia_det</td><td> -" . number_format($cantidad_det, 2) . "</td></tr>";
                $disponible -= $cantidad_det;
                // }

            }
            // Imprimir disponible albarán
            $html .= " <tr><td><b>Disponible Albarán</b></td><td><b>" . number_format($disponible, 2) . "</b></td></tr>";
        }
    }
    return '<table>'.$html.'</table>';
}

while ($obj = $db->fetch(PDO::FETCH_ASSOC)) {

    /* Construyo la tabla tooltip para cada linea */
    $filterdata = [];
    $html_tooltip = '';
    if (count($datos_tooltip) > 0) {
        foreach ($datos_tooltip as $dato) {
            if ($dato["fk_producto"] == $obj["fk_producto"]) {
                $filterdata[] = $dato;
            }
        }
        if (count($filterdata) > 0) {
            $html_tooltip = procesarJson($filterdata, $obj["id_item"]);
        }
        $filterdata = [];
    }
    /* Construyo la tabla tooltip para cada linea */

    $iterador++; // contador de Lineas Grafico
    if ($obj['tipo_impuesto'] > 0 and !empty($obj['ExoneradoExiste'])) {
        $obj['impuestot'] = '<span class="label label-info">Exonerado ' . $obj['tipo_impuesto'] . '</span>';
    } else if ($obj['tipo_impuesto'] > 0) {
        $obj['impuestot'] = '<span class="label label-warning">Con Impuesto ' . $obj['porcenatje_exonerado'] . ' </span>';
    } else {
        $obj['impuestot'] = '<span class="label label-warning">Sin Impuesto</span>';
    }

    $movimiento_completa = array('estado_detalle' => $obj["fk_estado_detalle"], 'cantidad_inicial' => $obj["destino_cantidad"]);

    //------------------------------------------------------------------------------------------
    //
    //
    $subtotal += ($obj['subtotal'] * $obj['cantidad']);
    $subtotal_precio_original += ($obj['precio_original'] * $obj['cantidad']);
    $impuesto += $obj['impuesto']; // esta variable puede ser sobre escrita luego con el montoNeto despues dela exoneracion
    $total += $obj['total'];
    $Exonerado += $obj['monto_impuesto_exoneracion'];

    $eliminar = "<button  onclick='restar(\"" . md5($obj['rowid']) . "\", " . $obj['rowid'] . ")'  class='btn btn-success btn-icon mb-1 me-2 btn-rounded _effect--ripple waves-effect waves-light'>
                                    <i class='fa fa-fw fa-trash' aria-hidden='true'></i>
                 </button>";
    $editar = "<button  onclick='editar(this, \"" . md5($obj['rowid']) . "\")'  class='btn btn-warning btn-icon mb-1 me-2 btn-rounded _effect--ripple waves-effect waves-light'>
                                    <i class='fa fa-fw fa-edit' aria-hidden='true'></i>
                 </button>";

    if ($Documento2->estado != '0') {
        $eliminar = "";
        $editar = '';
    }

    if ($obj['fk_producto'] > 0) {
        // $simbolo = '<a id="label_etiqueta_' . $obj['rowid'] . '" targe="_blank" href="' . ENLACE_WEB . 'dashboard.php?accion=productos_editar&fiche=' . $obj['fk_producto'] . '" > <i class="fa fa-fw fa  fa-check-circle"></i>' . nl2br($obj['label']) . ' </a>';
        $simbolo = '<a class="producto_' . $obj['fk_producto'] . '" id="label_etiqueta_' . $obj['rowid'] . '" > <i class="fa fa-fw fa  fa-check-circle"></i>' . nl2br($obj['label']) . ' </a>';
        '  ';
    } else {
        $simbolo = ' <i id="label_etiqueta_' . $obj['rowid'] . '" class="fa fa-fw fa fa-puzzle-piece"></i> <br/>' . nl2br($obj['label']) . '';
    }

    /**********************************Descuento Grafico ******************************************/

    if ($obj['descuento_aplicado'] > 0) {

        $descuentos += $obj['descuento_valor_final'];


        if ($obj['descuento_tipo'] == "porcentual") {
            $inicial = ((100 * $obj['subtotal']) / $obj['descuento_aplicado']);

            $descuento = $Documento2->moneda_simbolo . " " . numero_simple($obj['descuento_valor_final']) . "(" . $obj['descuento_aplicado'] . " % )";
        } else if ($obj['descuento_tipo'] == "absoluto") {
            $descuento = $Documento2->moneda_simbolo . '' . numero_simple($obj['descuento_valor_final']);
        } else {
            $descuento = "Error Desconocido ";
        }
    } else {
        $descuento = "-";
    }

    /**********************************Descuento Grafico ******************************************/

    $style_centrado = "style='vertical-align: middle;text-align:center;'";

    $tipo_es = ($obj['tipo'] == 1) ? "Prod" : "Serv";

    if ($obj['tipo'] == "1") {
        $txt = '<div class="tooltip_item" style="opacity:1!important; cursor:pointer; "> <i class="fa fa-cube" aria-hidden="true"></i>
                    <span class="tooltiptext">
                            <span style="width:400px!important;" > Bodega ' . $obj['bodega_txt'] . ' ' . numero_simple($obj['stock']) . ' </span>                            
                    </span>
                </div>';
    } else {
        $txt = '<div class="tooltip_item" style="opacity:1!important; cursor:pointer; "> <i class="fa fa-cube" aria-hidden="true"></i>
                    <span class="tooltiptext">
                        <table style="min-width:500px;" border="1" class="tooltip_tabla" >
                                <tr><TD>SERVICIO NO TIENE STOCK</TD></tr>
                        </table>
                    </span>
                </div>';
    }

    if ($obj["mov_referencia"]) {
        $txt .= '<div class="tooltip_item" style="opacity:1!important; cursor:pointer; padding-left:5px;"> <i class="fa fa-ticket" aria-hidden="true"></i>
                    <span class="tooltiptext">                    
                        <span style="width:400px!important;" > Origen:  ' . $obj["mov_referencia"] . ' </span>                            
                    </span>
                </div>';
    }


    if ($Documento2->estado == 0 && $obj['tipo'] == 1 && $_SESSION['utiliza_inventario'] && $obj['cantidad'] > $obj['stock']) {
        $facturar_sin_stock = true;
        $alerta_css         = 'background-color:#12bb3459;';
    } else {
        $alerta_css = '';
    }

    //$_SESSION['permitir_inventario_negativo'];
    $input_label = '<input  ' . $disable_input . '  type="text" value="' . $obj["label"] . '" class="form-control oculto_" id="etiqueta_' . $obj['rowid'] . '">';

    //Inicio de la Líneas
    $tr .= "
            <tr rowid='" . $obj['id_item'] . "' class='producto_item' style=' $alerta_css border-style: solid; border-bottom: thick #ff0000;' >
             <td align='center' width='5%'><i> $iterador</i> </td>
             <td width='30%' style='vertical-align:top; text-align:left' colspan='2' > $txt  $enlace_menu $simbolo $input_label<br>";

    $input_label_description = '<textarea   ' . $disable_input . '   class="form-control form-control-sm rezisable-item textarea_detalle_cotizacion"  name="linea_descripcion[]"  id="etiqueta_descripcion_' . $obj['rowid'] . '" s>' . $obj["descripcion"] . '</textarea>';

    $tr .= "</td>";

    //inputs para editar
    $row_id_aux = $obj['rowid'];

    $obj["subtotal_2"] = ($obj['cantidad'] *  $obj['precio_original']) - $obj['descuento_valor_final'];

    $movimiento_detalle = '';

    if ((strlen($html_tooltip) > 0) && !is_null($movimiento_completa["estado_detalle"])) {
        if ($movimiento_completa["estado_detalle"] == 0 || $movimiento_completa["estado_detalle"] == 1) {
            $movimiento_detalle = '<div class="tooltip_item" style="opacity:1!important; cursor:pointer; padding-left:5px;">
                                <i class="fa-solid ' . ($movimiento_completa["estado_detalle"] == 0 ? 'fa-circle-half-stroke' : 'fa-circle') . '" aria-hidden="true"></i>
                                    <span class="tooltiptext">' . $html_tooltip . '</span>
                            </div>';
        } else {
            $movimiento_completa["estado_detalle"] = '';
        }
    }

    $input_cantidad = $movimiento_detalle . "<input '.$disable_input.' name='linea_id[]' value='{$row_id_aux}' type='hidden' />";
    $input_cantidad .= '<input ' . $disable_input . ' type="text"  name="line_cantidad[]" value="' . numero_simple($obj['cantidad']) . '" class="oculto_ form-control " id="cantidad_' . $obj['rowid'] . '">';
    $input_cantidad .= '<label id="cantidad_' . $obj['rowid'] . '">' . numero_simple($obj['cantidad']) . '</label>';

    $input_precio = '<input  ' . $disable_input . ' type="text" name="line_precio_original[]" value="' . numero_simple($obj['precio_original']) . '" class="oculto_ form-control " id="precio_' . $obj['rowid'] . '">';

    $input_descuento = '<input ' . $disable_input . '  type="text" readonly="" name="line_descuento_valor_final[]" value="' . numero_simple($obj['descuento_valor_final']) . '" class="form-control oculto_" id="descuento_' . $obj['rowid'] . '">';
    $input_impuesto = '<input   ' . $disable_input . ' readonly   type="text" name="line_impuesto[]" value="' . numero_simple($obj['impuesto']) . '" class="oculto_ form-control" id="impuesto_' . $obj['rowid'] . '">';

    $input_subtotal = '<input  type="text" class="oculto_ form-control"  ' . $disable_input . '  type="text" readonly  value="' . numero_simple($obj["subtotal_2"]) . '" name="line_subtotal_2[]" id="subtotal_2_' . $obj['rowid'] . '">

   <input ' . $disable_input . ' readonly="" type="hidden"  name="line_subtotal[]" value="' . numero_simple($obj['subtotal']) . '" class="form-control " id="subtotal_' . $obj['rowid'] . '">';
    $input_total = '<input ' . $disable_input . ' name="line_total[]" readonly  style="width:100px;" type="text" value="' . numero_simple($obj['total']) . '" class="form-control oculto_" id="total_' . $obj['rowid'] . '">';

    $$row_id_anterior = $obj['rowid'];

    $input_cantidad .= "<input name='linea_padre_id[]' value='0' type='hidden' />";
    $tr .= "
                <td $style_centrado align='left' width='5%' >" . $input_cantidad . "<label class='oculto_' id='label_cantidad_" . $obj['rowid'] . "'>" . numero_simple($obj['cantidad']) . "</label></td>
                <td $style_centrado width='15%'>$input_precio <label  class='' id='label_precio_" . $obj['rowid'] . "'>" . $Documento2->moneda_simbolo . " " . numero_simple($obj['precio_original']) . " </label></td>";
    // if($Documento2->estado != 0){
    $tr .= "
                <td class='columnas_descuento' $style_centrado width='5%'>$input_descuento <label id='label_descuento_" . $obj['rowid'] . "'><strong>" . ($descuento) . "</strong></label></td> ";
    // }
    $tr .= "
                <td $style_centrado width='10%'>$input_subtotal <label id='label_subtotal_" . $obj['rowid'] . "'>" . $Documento2->moneda_simbolo . " " . numero_simple($obj['subtotal_pre_retencion']) . " </label></td>
                <td $style_centrado align='right' width='10%'><label class='oculto__' id='label_impuesto_" . $obj['rowid'] . "'>" . $Documento2->moneda_simbolo . numero_simple($obj['impuesto_iva_monto']) . "<small class='text-info' >(" . numero_simple($obj['impuesto_iva_porcentaje']) . "%)</small></label></td>";

    // if($Documento2->estado != 0){
    $tr .= "
                <td id='item_equivalencia_" . $obj['rowid'] . "' class='columnas_equivalencia' $style_centrado align='right' width='5%'>" . numero_simple($obj['impuesto_iva_equivalencia_monto']) . "<span class='text-info' >(" . numero_simple($obj['impuesto_iva_equivalencia_porcentaje']) . "%)</span></td>";
    // }

    // if($Documento2->estado != 0){
    $tr .= "
                <td id='item_retencion_" . $obj['rowid'] . "' class='columnas_retencion' $style_centrado align='right' width='5%'>" . numero_simple($obj['impuesto_retencion_monto']) . "<span class='text-info' >(" . numero_simple($obj['impuesto_retencion_porcentaje']) . "%)</span></td>";
    // }

    $tr .= "
                <td $style_centrado  align='right' nowrap='nowrap' >$input_total <label  id='label_total_" . $obj['rowid'] . "' width='10%'><strong>" . $Documento2->moneda_simbolo . " " . numero_simple($obj['total']) . "</strong></label></td>
                <td $style_centrado  align='right' nowrap='nowrap' class='tabla_sin_borde botones_accion'  >  $eliminar $editar </td>
                </tr>
            ";
}

?>

<?php
$trHeader = "<tr>
            <th style='vertical-align:top; text-align:center; background-color:#F2EAFA' width='5%'> Linea </th>
            <th style='vertical-align:top; text-align:left; background-color:#F2EAFA'   colspan='2' width='30%'> Descripción</th>
            <th style='vertical-align:top; text-align:center; background-color:#F2EAFA' width='5%'>Cantidad</th>
            <th style='vertical-align:top; text-align:center; background-color:#F2EAFA' width='15%'>P. Base</th>
            <th style='vertical-align:top; text-align:center; background-color:#F2EAFA' class='columnas_descuento' width='5%'>Descuento</th>
            <th style='vertical-align:top; text-align:center; background-color:#F2EAFA' width='10%'>Subtotal</th>
            <th style='vertical-align:top; text-align:center; background-color:#F2EAFA' width='10%'>IVA</th>
            <th style='vertical-align:top; text-align:center; background-color:#F2EAFA' class='columnas_equivalencia' width='5%'>RE</th>
            <th style='vertical-align:top; text-align:center; background-color:#F2EAFA' class='columnas_retencion' width='5%'>Retención</th>
            <th style='vertical-align:top; text-align:center; background-color:#F2EAFA' width='10%'>Total</th>
            <th style='vertical-align:top; text-align:center; background-color:#F2EAFA' width='10%'>" . ($document_id > 0 ? '' : 'Acciones') . "</th>
        </tr>";
if ($document_id_inicial != '') {
    echo $trHeader . $tr . '<tr class="tabla_sin_borde" ><td colspan="12"> </td></tr>';
} else {
    echo json_encode(array('html' => $trHeader . $tr . '<tr class="tabla_sin_borde" ><td colspan="12"> </td></tr>', 'document_id' => $document_id));
}
