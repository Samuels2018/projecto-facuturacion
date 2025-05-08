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

try {

    $disable_input = "";

    $_SESSION['permitir_inventario_negativo'];
    $_SESSION['utiliza_inventario'];
    $iterador = 0; // muestra para numerar cada linea

    $document_id = $Documento->id;
    if (!$document_id > 0) {
        $document_id = $_REQUEST['fiche'];
    }

    $sql = "SELECT f.*, f.rowid AS id_item    
    FROM  fi_oportunidades_servicio   f
    LEFT JOIN fi_productos p ON p.rowid = f.fk_producto 
    WHERE f.fk_documento = ?
    GROUP BY f.rowid
    ORDER BY 
    CASE 
        WHEN num_linea IS NULL THEN 99999
        ELSE 0 
    END, 
    num_linea ASC;";

    $db = $dbh->prepare($sql);
    $db->bindValue(1, $document_id, PDO::PARAM_INT);
    $db->execute();

    $contado = 0;
    $tr      = "";

    if ($error) {
        $tr .= "<tr><td colspan='6' > $error_txt </td></tr>";
    }

    //---------------------------
    //
    //  Limpiamos Totales Men
    //
    //

    $subtotal                 = 0;
    $impuesto                 = 0;
    $total                    = 0;
    $descuentos               = 0;
    $subtotal_precio_original = 0;
    $eliminar                 = "";
    $row_id_anterior = "";

    while ($obj = $db->fetch(PDO::FETCH_ASSOC)) {
        $iterador++; // contador de Lineas Grafico
        if ($obj['tipo_impuesto'] > 0 and !empty($obj['ExoneradoExiste'])) {
            $obj['impuestot'] = '<span class="label label-info">Exonerado ' . $obj['tipo_impuesto'] . '</span>';
        } else if ($obj['tipo_impuesto'] > 0) {
            $obj['impuestot'] = '<span class="label label-warning">Con Impuesto ' . $obj['porcenatje_exonerado'] . ' </span>';
        } else {
            $obj['impuestot'] = '<span class="label label-warning">Sin Impuesto</span>';
        }
    
        //------------------------------------------------------------------------------------------
        //
        //
        $subtotal += ($obj['subtotal'] * $obj['cantidad']);
        $subtotal_precio_original += ($obj['precio_original'] * $obj['cantidad']);
        $impuesto += $obj['impuesto']; // esta variable puede ser sobre escrita luego con el montoNeto despues dela exoneracion
        $total += $obj['total'];
        $Exonerado += $obj['monto_impuesto_exoneracion'];
    
        $eliminar = "<button  onclick='restar(\"" . md5($obj['rowid']) . "\")'  class='btn btn-success btn-icon mb-1 me-2 btn-rounded _effect--ripple waves-effect waves-light'>
                                        <i class='fa fa-fw fa-trash-o' aria-hidden='true'></i>
                     </button>";
        $editar = "<button  onclick='editar(this, \"" . md5($obj['rowid']) . "\")'  class='btn btn-warning btn-icon mb-1 me-2 btn-rounded _effect--ripple waves-effect waves-light'>
                                        <i class='fa fa-fw fa-edit' aria-hidden='true'></i>
                     </button>";
    
        
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
    
        if ($Documento2->estado == 0 && $obj['tipo'] == 1 && $_SESSION['utiliza_inventario'] && $obj['cantidad'] > $obj['disponible']) {
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
    
        $input_cantidad = "<input '.$disable_input.' name='linea_id[]' value='{$row_id_aux}' type='hidden' />";
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
} catch (Exception $ex) {
    echo json_encode($ex);
    return;
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
            <th style='vertical-align:top; text-align:center; background-color:#F2EAFA' width='10%'>Acciones</th>
        </tr>";

echo $trHeader . $tr . '<tr class="tabla_sin_borde" ><td colspan="12"> </td></tr>';

?>