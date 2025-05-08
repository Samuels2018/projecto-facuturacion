<?php

if (!defined('ENLACE_SERVIDOR')) {
    session_start();
    require_once "../../conf/conf.php";
}
$error              = false;

if ($_SESSION['usuario'] == NULL) { echo acceso_invalido(); exit(1); }

include_once(ENLACE_SERVIDOR . "mod_europa_facturacion/object/facturas.object.php");
include_once(ENLACE_SERVIDOR . "mod_europa_albaran_compra/object/Albaran_compra.object.php");
include_once(ENLACE_SERVIDOR . "mod_europa_albaran_venta/object/Albaran_venta.object.php");
include_once(ENLACE_SERVIDOR . "mod_europa_compra/object/compras.object.php");
include_once(ENLACE_SERVIDOR . "mod_europa_presupuestos/object/presupuestos.object.php");
include_once(ENLACE_SERVIDOR . "mod_europa_pedido/object/pedido.object.php");

$document_id_inicial = $_REQUEST['fiche'];
$document_id = $_REQUEST['fiche'];

$tipo_documento = $_POST["tipo"];
if($document_id != ''){
        if($Documento == null){
                if($tipo_documento != ''){
                        $Documento = new $tipo_documento($dbh, $_SESSION["Entidad"]);
                        $Documento->nombre_clase = $tipo_documento;
                }
        }
        $Documento->fetch($document_id);
}else{
        $Documento = new $tipo_documento($dbh, $_SESSION["Entidad"]);
        $Documento->nombre_clase = $tipo_documento;
}

$contado = 0;
$tr      = "";

if ($error) {
    $tr .= "<tr><td colspan='12' > $error_txt </td></tr>";
}

$detalle = 'actualizar_nota("detalle")';
print "<tr>
        <td id='columna_referencia' colspan='6' rowspan='7' valign='top' class='tabla_sin_borde'>
            <i id='icono_detalle'  class='fa fa-pencil-square-o'></i>Referencia  <strong id='alerta_detalle'></strong>
            <div id='lugar_campo_detalle'>
                <textarea rows='5' placeholder='Referencia de la cotización'  type='text' name='detalle'  id ='textarea_detalle'  class='form-control' maxlength='300' onblur='" . $detalle . "' >" . trim($Documento->detalle) . "</textarea>
            </div>
        </td >";

$descuentos = $Documento->descuento_valor_final;

if ($descuentos > 0) {
    print "<td colspan='5' align='left'  class='tabla_sin_borde' style='text-align:right!important;'  ><b>Base:</b></td>
            <td align='right'  class='tabla_sin_borde'        nowrap='nowrap' ><b>" . $Documento->moneda_simbolo . " " . numero_decimal($Documento->subtotal_pre_retencion + $descuentos) . "</b></td>
            </tr>";
    print "<td colspan='5' align='left' class='tabla_sin_borde' style='text-align:right!important;'  ><b>Descuentos:</b></td>
            <td align='right'  class='tabla_sin_borde'        nowrap='nowrap' ><b>" . $Documento->moneda_simbolo . " " . numero_decimal($descuentos) . "</b></td>
            </tr>";
}

print "<td colspan='5' align='left' class='tabla_sin_borde' style='text-align:right!important;'  ><b>Base Imponible:</b></td>
        <td align='right'  class='tabla_sin_borde'        nowrap='nowrap' ><b>" . $Documento->moneda_simbolo . " " . numero_decimal($Documento->subtotal_pre_retencion) . "</b></td>
        </tr>";
print "<tr  class='tabla_sin_borde'>
        <td  colspan='5' class='tabla_sin_borde' style='text-align:right!important;'  ><b>Total IVA :</b></td>
        <td  align='right' class='tabla_sin_borde'       nowrap='nowrap' ><b>" . $Documento->moneda_simbolo . " " . numero_decimal($Documento->impuesto_iva) . "</b></td>
        </tr>";

if ($Documento->impuesto_iva_equivalencia > 0) {
    print "<tr>
            <td  colspan='5' class='tabla_sin_borde' style='text-align:right!important;'><b>Total RE :</b></td>
            <td  align='right'  nowrap='nowrap' ><b>" . $Documento->moneda_simbolo . " " . numero_decimal($Documento->impuesto_iva_equivalencia) . "</b></td>
            </tr>";
}

print "<tr>
        <td  colspan='5' class='tabla_sin_borde'   style='text-align:right!important;'  ><b>Retenci&oacute;n :</b></td>
        <td  align='right'     class='tabla_sin_borde'        nowrap='nowrap' ><b>" . $Documento->moneda_simbolo . " " . numero_decimal($Documento->impuesto_retencion_irpf) . "</b></td>
        </tr>";

print "<tr>
        <td colspan='6' class='tabla_sin_borde' align='right' align='right'   nowrap='nowrap' >
            <h3  class='monto_total_unico tabla_sin_borde ' style='color:#2196F3'  total='" . $Documento->total . "' ><i class=\"fa fa-fw fa-credit-card\"></i> Total: " . $Documento->moneda_simbolo . " " . numero_decimal($Documento->total) . "</h3>
        </td>
        </tr>";
