<?php 


session_start();
include '../../conf/conf.php';

if (!isset($_SESSION['usuario'])) {
    echo json_encode(['error' => 'Acceso no válido']);
    exit;
  }

// $tabla = "fi_europa_facturas";
 if ($_POST['category']=="Repercutido"){   $tabla = "fi_europa_facturas"; $analizando_txt="Analizando Facturas Ventas ";  }
  else if ($_POST['category']=="Soporte"){ $tabla = "fi_europa_compras"; $analizando_txt="Analizando Facturas Compras ";  }
    else   { echo $_POST['category']." No Entra en Arbol Decisiones (Linea 15) ";  }


    $key = array_search($_POST['mes'], $Utilidades->meses);

        if ($key !== false) {
            $analizando_txt.=" Mostrando el detalle de  {$_POST['mes']} Numero $key";

            $fechas = $Utilidades->obtenerRangoMes($key);


        } else {
            $analizando_txt.= "Mes no encontrado.";
        }




  require_once(ENLACE_SERVIDOR."mod_reporte/object/reporte.object.php");
  $Reporte = new Reporte($dbh, $_SESSION['Entidad']);
  
  $Reporte->reporte_general_ivas($tabla , $fechas['primer_dia'] , $fechas['ultimo_dia'] );
  $conteo = 0 ;
  $total = [
    'IVA_0' => 0,
    'IVA_4' => 0,
    'IVA_10' => 0,
    'IVA_21' => 0,
    'Total_Factura' => 0
];

  foreach ($Reporte->DOCUMENTOS_DETALLES as $detalle){

    
    $conteo++;
    $tr.="<Tr>  <td>$conteo</td>
                <td>{$detalle->referencia}</td>
                <td>{$detalle->fecha}</td>
                <td>{$detalle->fk_tercero_txt}</td>
                <td>{$detalle->fk_tercero_identificacion}</td>
                <td>{$detalle->fk_tercero_telefono}</td>
                <td>{$detalle->IVA_0}</td>
                <td>{$detalle->IVA_4}</td>
                <td>{$detalle->IVA_10}</td>
                <td>{$detalle->IVA_21}</td>
                <td>{$detalle->Total_Factura}</td>
                ";
    $tr.="</tr>";


    // Acumulamos los totales
    $total['IVA_0'] += $detalle->IVA_0;
    $total['IVA_4'] += $detalle->IVA_4;
    $total['IVA_10'] += $detalle->IVA_10;
    $total['IVA_21'] += $detalle->IVA_21;
    $total['Total_Factura'] += $detalle->Total_Factura;

  }
  // Agregamos la fila de totales al final de la tabla
$tfoot = " <tfoot><tr style='font-weight:bold; background-color:#f2f2f2;'>
<td colspan='6' align='right'>Total:</td>
<td>{$total['IVA_0']}</td>
<td>{$total['IVA_4']}</td>
<td>{$total['IVA_10']}</td>
<td>{$total['IVA_21']}</td>
<td>{$total['Total_Factura']}</td>
</tr></tfoot>";





   echo $analizando_txt;

   if ($conteo>0){
    echo "<table id='miTabla'>
    
            <thead>
            <tr>
                <th></th>
                <th>Referencia</th>
                <th>Fecha</th>
                <th>Nombre</th>
                <th>CIF</th>
                <th>Tel</th>
                <th>IVA 0</th>
                <th>IVA 4</th>
                <th>IVA 10</th>
                <th>IVA 21 </th>
                <th>Total Factura</th>
                </tr>
            </thead>";

    echo "<tbody> $tr  </tbody>  $tfoot </table> ";


   }

