<?php

if (!defined('ENLACE_SERVIDOR')) {
    session_start(); 
    require_once('../../conf/conf.php');
}


// Verifica si hay un usuario autenticado
if (!isset($_SESSION['usuario'])) {
    echo json_encode(['success' => false, 'message' => 'Acceso inválido']);
    exit;
}

$rowid = $_GET['fiche'];
$Entidad = $_SESSION['Entidad'];

// WHERE 
$where ='';
$where .='';

$sql= "SELECT 
            a.rowid,
            a.entidad,
            a.recibo_numero as factura, 
            CONCAT(t.nombre , ' ' , t.apellidos) as proveedor, 
            t.rowid AS proveedor_id,
            CASE 
                WHEN a.fecha IS NULL OR a.fecha = '0000-00-00' THEN 'Sin fecha' 
                ELSE a.fecha 
            END as fecha, 
            IFNULL(CONCAT(u.nombre , ' ' , u.apellidos), 'Usuario no asignado') as usuario, 
            a.valor as monto, 
            g.nombre as cuenta_de_gasto, 
            IF(a.pagado = 0, 'No pagado', 'Pagado') as estado
        FROM 
            fi_europa_gastos as a 
            LEFT JOIN fi_terceros as t ON a.fk_tercero = t.rowid 
            LEFT JOIN fi_usuarios as u ON a.creado_fk_usuario = u.rowid 
            LEFT JOIN fi_gastos_tipos as g ON a.fk_gasto = g.rowid
         WHERE a.entidad = $Entidad AND fk_proyecto = $rowid 
        ORDER BY a.rowid DESC";



$db = $dbh->prepare($sql);
$db->execute();
$tr = "";
$montos_totales = 0;
$moneda_simbolo = '€';

$total_gasto = 0; 

while ($data = $db->fetch(PDO::FETCH_OBJ)):

    $montos_totales += floatval($data->monto);

    $tr .= '<tr class="ng-scope" style="cursor:pointer;">
                <td>'.htmlspecialchars($data->factura).'</td>
                <td>'.htmlspecialchars($data->proveedor).'</td>
                <td>'.htmlspecialchars($data->fecha).'</td>
                <td>'.htmlspecialchars($data->usuario).'</td>
                <td><span class="badge bg-info">'. $moneda_simbolo.' '.number_format($data->monto, 2).'</span></td>
                <td>'.htmlspecialchars($data->cuenta_de_gasto).'</td>
                <td>'.htmlspecialchars($data->estado).'</td>
            </tr>';

endwhile;

//aqui obtenemos el monto total de gasto
$total_gasto = $montos_totales;

if ($tr == '') {
    echo '<tr><td colspan="7" style="text-align:center">No se han encontrado registros de gastos</td></tr>';
} else {
    $tr .= '<tr class="" style="cursor:pointer;">
                <td colspan="4"></td>
                <td><strong>Total: </strong></td>
                <td colspan="2">'. $moneda_simbolo.' '.number_format($montos_totales, 2).'</td>
            </tr>';
    echo $tr;
}
