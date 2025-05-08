<?php
// VALID DEFINIITON SESSION

if (!defined('ENLACE_WEB')) :
    session_start();
    require_once "../../conf/conf.php";
endif;

require_once ENLACE_SERVIDOR . 'mod_cotizaciones/object/cotizaciones.object.php';
require_once ENLACE_SERVIDOR . 'mod_terceros/object/terceros.object.php';
require_once ENLACE_SERVIDOR . "/mod_configuracion_agente/object/agente.object.php";
require_once ENLACE_SERVIDOR . 'mod_redhouse_cotizaciones/object/redhouse.cotizaciones.object.php';
require_once ENLACE_SERVIDOR . 'mod_redhouse_ordenes_compra/object/order_compra_object.php';





$orden = new Orden($dbh, $_SESSION['Entidad']);
$orden->fetch($_REQUEST['ord']);


$Factura = new redhouse_Cotizacion($dbh, $_SESSION['Entidad']);
$Factura->fetch($_REQUEST['id']);



$Tercero = new FiTerceros($dbh);
$Tercero->fetch($Factura->fk_tercero);

$agente = new Agente($dbh);
$agente_actual = $agente->obtener_agente_actual($Tercero->rowid);

/*$Factura->diccionario_pago();
$factura_forma_pago = $Factura->diccionario_pago[$Factura->forma_pago]['label'];*/

include "../../include/mpdf/vendor/autoload.php";
$mpdf = new \Mpdf\Mpdf([
    'format' => [280, 420], // Ancho y alto en milímetros, un poco menos ancho que A3
    'margin_left' => 10,
    'margin_right' => 10,
    'margin_top' => 10,
    'margin_bottom' => 10,
    'margin_header' => 5,
    'margin_footer' => 5
]);

$mpdf->SetFont('nimbus');

// Estilos CSS para la factura
$css = "
<style>
    body {
        font-family: 'Arial', sans-serif;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    table tr td, table tr th {
        font-size: 16px;
        padding: 5px; /* Reduce el padding aquí */
    }
    #tabla_articulos tr th {
        background-color: #D9D9D9;
        padding: 5px; /* Reduce el padding aquí */
    }
    #tabla_articulos tr td {
        text-align: center;
        border: 1px solid #000000;
    }
    .border-black {
        border: 1px solid black;
    }
    #tabla_cliente tr td {
        padding: 5px; /* Reduce el padding aquí */
    }
    #tabla_evento tr td {
        padding: 5px; /* Reduce el padding aquí */
    }
    #tabla_cotizacion tr td {
        padding: 5px; /* Reduce el padding aquí */
    }
</style>
";

$mpdf->WriteHTML($css);

$footerText = '';

$fecha_creacion = date("d/m/Y", strtotime($orden->fecha_creacion));
//$vigencia = ($Factura->cotizacion_validez_oferta == 0) ? "Sin Validez definida" : $Factura->cotizacion_validez_oferta . " Días";
$vigencia = date("d/m/Y", strtotime($orden->fecha_vigencia));

// Contenido de la factura (puedes cambiarlo según tus necesidades)

$html = '<div class="pdf-redhouse"  style="">
    <div style="padding: 15px;">
        <div style="float:left; width:30%;">
            <div style="background-color:#643A8D; color:white; border-top-right-radius:40px;  padding: 10px;">
                <b style="font-size:16px;">Información </b><span style="font-size:16px; font-weight:100;">Orden de compra:</span>
            </div>
            <div style="border-bottom:2px dotted #BEC1F2; padding:5px 10px;">
                Consecutivo: <span style="">'.$orden->orden_consecutivo.'</span>
            </div>
            <div style="border-bottom:2px dotted #BEC1F2; padding:5px 10px;">
                Fecha: <span style="">'.$fecha_creacion.'</span>
            </div>
            <div style="border-bottom:2px dotted #BEC1F2; padding:5px 10px;">
                Vigencia: <span style="">'.$vigencia.'</span>
            </div>
        </div>
        <div style="float:right; width:68%;">
           <img  src="' . ENLACE_WEB . '/bootstrap/img/redhouse-logopdf.png" style="width:100%;" >
        </div>
    <div style="clear:both;"></div>
    <br>
    <table style="width:100%;" id="tabla_cliente" cellpadding="0" cellspacing="0">
        <tr>
            <td style="background-color: #643A8D; color:white;  border-bottom: 1px solid #60417D; padding: 5px;">Información del proveedor</td>
        </tr>
        <tr>
            <td class="border-black">Nombre del Proveedor: <strong>'.$orden->proveedor_nombre_completo.'</strong></td>
        </tr>
        <tr>
            <td class="border-black">Contacto: <strong></strong></td>
        </tr>
    </table>
    
    <br>

    <table style="width:100%; border-left:1px solid black; border-right: 1px solid black;" id="tabla_evento" cellpadding="0" cellspacing="0">   

        <tr>
            <td style="background-color: #643A8D; color:white;  border-bottom: 1px solid #643A8D; padding: 5px;">Detalles del Evento</td>
        </tr>
        <tr>
            <td style="border:1px solid black; border-top:none;">
                <table style="width:100%;"> 
                    <tr>
                        <td>Proyecto: <span>'.$orden->proyecto_descripcion.'</span></td>
                        <td>Otros:  <span></span></td>
                    </tr>
                </table>                
            </td>
        </tr>
        <tr>
            <td class="border-black">   
                Lugar: <span>'.$orden->proyecto_lugar.'</span>
            </td>
        </tr>
        <tr>
            <td class="border-black">   
                Fecha: <span>'.date("d-m-Y",strtotime($orden->proyecto_fecha)).'</span>
            </td>
        </tr>
        <tr>
            <td class="border-black">Hora: <span>'.obtenerHoraConFormato($orden->proyecto_hora).'</span></td>
        </tr>

    </table>
    </div>
<!-- descripcion de productos-->
<br>
<table style="width:100%;" id="tabla_articulos" cellspacing="0" cellpadding="0">
    <tr>
        <th style="background-color:#643A8D; color:white;">Descripción</th>
        <th style="background-color:#643A8D; color:white;">Días</th>
        <th style="background-color:#643A8D; color:white;">Horas</th>
        <th style="background-color:#643A8D; color:white;">Cantidad</th>
        <th style="background-color:#643A8D; color:white;">P/U</th>
        <th style="background-color:#643A8D; color:white;">Subtotal</th>
    </tr>';




$sqldetalle  = "SELECT 
    f.*, 
    f.cantidad, 
    f.precio_subtotal, 
    p.label AS titulo_producto, 
    f.precio_tipo_impuesto, 
    f.fk_producto, 
    (SELECT label FROM fi_productos_imagenes WHERE fk_producto = p.rowid AND borrado = 0 ORDER BY rowid DESC LIMIT 1) AS imagen, 
    p.ref, 
    p.rowid AS id_producto,
    (SELECT SUM(f2.precio_subtotal) FROM a_medida_redhouse_orden_compra_servicios f2 WHERE f2.fk_orden = f.fk_orden) AS suma_precio_subtotal,
    (SELECT SUM(f2.precio_tipo_impuesto) FROM a_medida_redhouse_orden_compra_servicios f2 WHERE f2.fk_orden = f.fk_orden) AS suma_precio_tipo_impuesto,
    (SELECT SUM(f2.precio_total) FROM a_medida_redhouse_orden_compra_servicios f2 WHERE f2.fk_orden = f.fk_orden) AS suma_precio_total
FROM 
    a_medida_redhouse_orden_compra_servicios f 
LEFT JOIN 
    fi_productos p ON p.rowid = f.fk_producto 
WHERE 
    f.fk_orden = ? 
GROUP BY 
    f.rowid;
";


$db = $dbh->prepare($sqldetalle);
$db->bindValue(1, $_REQUEST['ord'], PDO::PARAM_INT);
$db->execute();

$suma_precio_subtotal = '';
$suma_precio_tipo_impuesto = '';
$suma_precio_total = '';


//RECORRIDO DE LOS PRODUCTOS
while ($obj = $db->fetch(PDO::FETCH_ASSOC))
{
    $duracion = !empty($obj['tipo_duracion']) ? $obj['tipo_duracion'] : 'Días';


    $suma_precio_subtotal = numero_simple($obj['suma_precio_subtotal']);
    $suma_precio_tipo_impuesto = numero_simple($obj['suma_precio_tipo_impuesto']);
    $suma_precio_total = numero_simple($obj['suma_precio_total']);


    $html.='<tr>
        <td style="width:450px; text-align: left;">
            <span class="titulo">'.$obj["titulo_producto"].'</span>
            <p>'.$obj["comentario"].'</p>
        </td>
        <td>'.intval($obj["cantidad_dias"]).' Días</td>
        <td>'.intval($obj["tipo_duracion"]).' Horas</td>
        <td>'.$obj["cantidad"].'</td>
        <td><span>'.$orden->moneda_simbolo.'</span> <span>'.numero_simple($obj["precio_unitario"]).'</span></td>
        <td><span>'.$orden->moneda_simbolo.'</span> <span>'.numero_simple($obj["precio_subtotal"]).'</span></td>
    </tr>';
}




$html.='<tr>
        <td></td>
        <td></td> 
        <td colspan="4" style="padding:5px; background-color: #F2F2F2; border:1px solid black;">
            <span style="float:left;">Subtotal</span>
            <span style="float: right;">'.$orden->moneda_simbolo.' '.$suma_precio_subtotal.'</span>
        </td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td colspan="4" style="padding:5px; background-color: #D9D9D9; border:1px solid black;">
            <span style="float:left;">IVA</span>
            <span style="float: right;">'.$orden->moneda_simbolo.' '.$suma_precio_tipo_impuesto.'</span>
        </td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td colspan="4" style="padding:5px; background-color: #BFBFBF; border:1px solid black;">
            <span style="float:left; font-size: 16px; font-weight: bold;">TOTAL</span>
            <span style="float: right;">'.$orden->moneda_simbolo.' '.$suma_precio_total.'</span>
        </td>
    </tr>
</table>

</div>
';

$html.='<div style="border-bottom:2px dotted black; width:100%;"><div>';
$html.='<div>

<div><p style="font-size:18px;"><strong>Nota: </strong>'.$orden->orden_notas.'</p></div>
</div>';

$html .= $footerText;

// $mpdf->debug = true;
// $mpdf->showImageErrors = true;
$mpdf->curlAllowUnsafeSslRequests = true;

$mpdf->WriteHTML($html);

$file = $orden->orden_consecutivo."_.pdf";

if ($_GET['d'] > 0) {
    $content = $mpdf->Output('', 'S'); // Para adjuntarlo en un correo
} else {
    $content = $mpdf->Output($file, 'I'); // Cambia 'D' a 'I' para mostrar en el navegador
}

function image_exists($path) {
    // Check if it's a URL
    if (filter_var($path, FILTER_VALIDATE_URL)) {
        if (url_exists($path)) {
            return $path;
        } else {
            return false;
        }
    } else {
        // Check if it's a local file
        if (file_exists($path)) {
            return $path;
        } else {
            return false;
        }
    }
}

// Function to check if the URL exists
function url_exists($url)
{
    $headers = @get_headers($url);
    return $headers && strpos($headers[0], '200') !== false;
}

function obtenerHoraConFormato($fecha) {
    // Convertir la fecha a timestamp
    $timestamp = strtotime($fecha);

    // Obtener la hora en formato de 12 horas con AM/PM
    $hora = date('g:i A', $timestamp);

    // Retornar la hora
    return $hora;
}


?>
