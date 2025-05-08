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

// Parámetros necesarios
$rowid = $_GET['fiche']; // ID del proyecto
$entidad = $_SESSION['Entidad'];

// Construcción del WHERE
$where = ' WHERE a.entidad = :entidad AND fk_proyecto = :fk_proyecto';

// Consulta SQL para obtener las facturas
$sql = "SELECT 
            a.rowid,
            CASE WHEN a.tipo = 'tiquete' THEN 'Simplificada' ELSE 'Normal' END AS tipo,
            a.referencia AS factura,
            CASE 
                WHEN t.tipo = 'fisica' THEN CONCAT(t.nombre, ' ', t.apellidos) 
                ELSE t.nombre 
            END AS cliente_tercero,
            a.fecha,
            CONCAT(u.nombre, ' ', u.apellidos) AS usuario_crear,
            a.subtotal_pre_retencion AS base,
            a.impuesto_iva AS impuesto,
            a.total AS total,
            IF(a.pagado = 0, 'No pagado', 'Pagado') AS pagado,
            CASE WHEN a.estado = 0 THEN 'Borrador' ELSE d.etiqueta END AS estado
        FROM fi_europa_facturas a
        LEFT JOIN fi_terceros t ON a.fk_tercero = t.rowid
        LEFT JOIN fi_usuarios u ON a.fk_usuario_crear = u.rowid
        LEFT JOIN ".DB_NAME_UTILIDADES_APOYO.".diccionario_factura_europa_diccionario d ON a.estado_hacienda = d.rowid
        " . $where . "
        ORDER BY a.rowid DESC";

$db = $dbh->prepare($sql);
$db->bindParam(':entidad', $entidad, PDO::PARAM_INT);
$db->bindParam(':fk_proyecto', $rowid, PDO::PARAM_INT);
$db->execute();

$tr = "";
$monto_total = 0;
$moneda_simbolo = '€';
$total_factura = 0;

// Procesar los resultados
while ($data = $db->fetch(PDO::FETCH_OBJ)) {
    $monto_total += floatval($data->total);

    // Construcción de la fila de la tabla
    $tr .= '<tr class="ng-scope" style="cursor:pointer;">
                <td>' . htmlspecialchars($data->tipo) . '</td>
                <td>
                    <a href="' . ENLACE_WEB . 'factura/' . $data->rowid . '" target="_blank">
                        <span class="badge bg-primary">' . htmlspecialchars($data->factura) . '</span>
                    </a>
                </td>
                <td>' . (!empty($data->cliente_tercero) ? htmlspecialchars($data->cliente_tercero) : '<span class="text-muted">Cliente Genérico</span>') . '</td>
                <td>' . date('d-m-Y', strtotime($data->fecha)) . '</td>
                <td>' . htmlspecialchars($data->usuario_crear) . '</td>
                <td><span class="badge bg-info">' . $moneda_simbolo . ' ' . number_format($data->base, 2) . '</span></td>
                <td>' . $moneda_simbolo . ' ' . number_format($data->impuesto, 2) . '</td>
                <td>' . $moneda_simbolo . ' ' . number_format($data->total, 2) . '</td>
                <td>' . htmlspecialchars($data->pagado) . '</td>
                <td>' . htmlspecialchars($data->estado) . '</td>
            </tr>';
}

//Monto total de factura
$total_factura = $monto_total;
// Si no hay registros, muestra un mensaje
if ($tr == '') {
    echo '<tr><td colspan="10" style="text-align:center">No se han encontrado registros de facturas</td></tr>';
} else {
    // Agregar la fila con el total general
    $tr .= '<tr class="" style="cursor:pointer;">
                <td colspan="6"></td>
                <td><strong>Total: </strong></td>
                <td colspan="3">' . $moneda_simbolo . ' ' . number_format($monto_total, 2) . '</td>
            </tr>';
    echo $tr;
}
?>
