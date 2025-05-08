<?php

if (!defined('ENLACE_SERVIDOR')) {
    session_start();
    require_once "../../conf/conf.php";
}
$error              = false;
$facturar_sin_stock = false;

require_once(ENLACE_SERVIDOR . "mod_crm/object/oportunidad.object.php");

if ($_SESSION['usuario'] == NULL) {
    exit(1);
}

$document_id = $Documento->id;
if(!$document_id > 0){
    $document_id = $_REQUEST['fiche'];
}

$Documento = new Oportunidad($dbh, $_SESSION["Entidad"]);
$Documento->fetch($document_id);

$contado = 0;
$tr      = "";

if ($error) {
    $tr .= "<tr><td colspan='12' > $error_txt </td></tr>";
}


print "<tr>
        <td id='columna_referencia' colspan='6' rowspan='7' valign='top' class='tabla_sin_borde' style='width:50%'>
            
        </td >";

if ($descuentos > 0) {
    print "<td colspan='5' align='left'  class='tabla_sin_borde' style='text-align:right!important;'  ><b>Base:</b></td>
            <td align='right'  class='tabla_sin_borde'        nowrap='nowrap' ><b>€ " . numero_decimal($Documento->subtotal_pre_retencion + $descuentos) . "</b></td>
            </tr>";
    print "<td colspan='5' align='left' class='tabla_sin_borde' style='text-align:right!important;'  ><b>Descuentos:</b></td>
            <td align='right'  class='tabla_sin_borde'        nowrap='nowrap' ><b>€ " . numero_decimal($descuentos) . "</b></td>
            </tr>";
}

print "<td colspan='5' align='left' class='tabla_sin_borde' style='text-align:right!important;'  ><b>Base Imponible:</b></td>
        <td align='right'  class='tabla_sin_borde'        nowrap='nowrap' ><b>€ " . numero_decimal($Documento->subtotal_pre_retencion) . "</b></td>
        </tr>";
print "<tr  class='tabla_sin_borde'>
        <td  colspan='5' class='tabla_sin_borde' style='text-align:right!important;'  ><b>Total IVA :</b></td>
        <td  align='right' class='tabla_sin_borde'       nowrap='nowrap' ><b>€ " . numero_decimal($Documento->impuesto_iva) . "</b></td>
        </tr>";

if ($Documento->impuesto_iva_equivalencia > 0) {
    print "<tr>
            <td  colspan='5' class='tabla_sin_borde' style='text-align:right!important;'><b>Total RE :</b></td>
            <td  align='right'  nowrap='nowrap' ><b>€ " . numero_decimal($Documento->impuesto_iva_equivalencia) . "</b></td>
            </tr>";
}

print "<tr>
        <td  colspan='5' class='tabla_sin_borde'   style='text-align:right!important;'  ><b>Retenci&oacute;n :</b></td>
        <td  align='right'     class='tabla_sin_borde'        nowrap='nowrap' ><b>€ " . numero_decimal($Documento->impuesto_retencion_irpf) . "</b></td>
        </tr>";

print "<tr>
        <td colspan='6' class='tabla_sin_borde' align='right' align='right'   nowrap='nowrap' >
            <h3  class='monto_total_unico tabla_sin_borde ' style='color:#2196F3'  total='" . $Documento->total . "' ><i class=\"fa fa-fw fa-credit-card\"></i> Total: € " . numero_decimal($Documento->total) . "</h3>
        </td>
        </tr>";