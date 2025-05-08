<?php
session_start();
require_once "../../conf/conf.php";

// Obtener parámetros de la petición
$inicio = $_POST['start'];
$limite = $_POST['length'];
$buscarArray = $_POST['search'];
$buscar = $buscarArray['value'];
$columnas = $_POST['columns'];
$subquery_impuesto = "(SELECT impuesto FROM fi_productos_precios_clientes ppc
WHERE fk_producto = p.rowid ORDER BY rowid DESC LIMIT 0,1)";
$subquery_stock = "(SELECT SUM(fs.stock) FROM fi_bodegas_stock fs
INNER JOIN fi_bodegas fb ON fb.rowid = fs.fk_bodega WHERE fs.fk_producto = p.rowid AND fb.activo = 1)";
$array_columnas = [];

// Mapa de columnas para mapear los nombres de las columnas del DataTable a los nombres de las columnas en la base de datos
$mapaColumnas = [
    "ref" => "ref",
    "label" => "label",
    "tosell" => "tosell",
    "tobuy" => "tobuy",
    "CABYS" => "cabys_codigo",
    "impuesto" => $subquery_impuesto,
    "stock_actual_txt" => $subquery_stock
];

// Limpiar los caracteres de exponentes y $ de los valores de búsqueda
for ($i = 0; $i < count($columnas); $i++) {
    if ($columnas[$i]['search']['value'] != '') {
        $valorLimpio = preg_replace('/[\^$]/', '', $columnas[$i]['search']['value']);
        $array_columnas[$columnas[$i]['data']] = $valorLimpio;
    }
}

// Construir condiciones de búsqueda para $sqlTotal
$condicionesTotal = [];
foreach ($array_columnas as $key => $value) {
    if (array_key_exists($key, $mapaColumnas)) {
        $nombreColumnaBD = $mapaColumnas[$key];
        if (strpos($nombreColumnaBD, 'SELECT') !== false) {
            // Es una subconsulta, construir la condición de búsqueda de manera especial
            $condicionesTotal[] = $nombreColumnaBD . " = " . $dbh->quote($value);
        } else {
            // Es una columna normal, construir la condición de búsqueda como lo harías normalmente
            $valor = $dbh->quote('%' . $value . '%');
            $condicionesTotal[] = "$nombreColumnaBD LIKE $valor";
        }
    }
}

$whereTotal = implode(' AND ', $condicionesTotal);
if (!empty($whereTotal)) {
    $whereTotal = "AND ($whereTotal)";
} else {
    $whereTotal = "";
}

// Construir condiciones de búsqueda para $sql
$condiciones = [];
foreach ($array_columnas as $key => $value) {
    if (array_key_exists($key, $mapaColumnas)) {
        $nombreColumnaBD = $mapaColumnas[$key];
        if (strpos($nombreColumnaBD, 'SELECT') !== false) {
            // Es una subconsulta, construir la condición de búsqueda de manera especial
            $condiciones[] = $nombreColumnaBD . " = " . $dbh->quote($value);
        } else {
            // Es una columna normal, construir la condición de búsqueda como lo harías normalmente
            $valor = $dbh->quote('%' . $value . '%');
            $condiciones[] = "$nombreColumnaBD LIKE $valor";
        }
    }
}

$where = !empty($condiciones) ? ' AND (' . implode(' AND ', $condiciones) . ') ' : '';

$totalRecords = $dbh->query("
    SELECT COUNT(p.rowid) AS total
    FROM fi_productos p
    LEFT JOIN (
        SELECT fk_producto, total, impuesto
        FROM fi_productos_precios_clientes
        WHERE rowid IN (
            SELECT MAX(rowid)
            FROM fi_productos_precios_clientes
            GROUP BY fk_producto
        )
    ) ppc ON p.rowid = ppc.fk_producto
    LEFT JOIN (
        SELECT fk_producto, SUM(fs.stock) AS stock_actual_txt
        FROM fi_bodegas_stock fs
        INNER JOIN fi_bodegas fb ON fb.rowid = fs.fk_bodega
        WHERE fb.activo = 1
        GROUP BY fk_producto
    ) fs ON p.rowid = fs.fk_producto
    WHERE p.entidad = " . $_SESSION['Entidad'] . "
      AND p.eliminado = 0
      $whereTotal
")->fetchColumn();

// Consulta para obtener los registros filtrados
$sql = "SELECT p.*,
        REPLACE(p.label, '\"', '') as label_limpio,
        ppc.total AS precio,
        ppc.impuesto,
        fs.stock_actual_txt
        FROM fi_productos p
        LEFT JOIN (
            SELECT fk_producto, total, impuesto
            FROM fi_productos_precios_clientes
            WHERE rowid IN (
                SELECT MAX(rowid)
                FROM fi_productos_precios_clientes
                GROUP BY fk_producto
            )
        ) ppc ON p.rowid = ppc.fk_producto
        LEFT JOIN (
            SELECT fk_producto, SUM(fs.stock) AS stock_actual_txt
            FROM fi_bodegas_stock fs
            INNER JOIN fi_bodegas fb ON fb.rowid = fs.fk_bodega
            WHERE fb.activo = 1
            GROUP BY fk_producto
        ) fs ON p.rowid = fs.fk_producto
        WHERE p.entidad = " . $_SESSION['Entidad'] . "
          AND p.eliminado = 0
          $where
        ORDER BY p.label ASC
        LIMIT $inicio, $limite";

// Preparar la respuesta
$data = array();

$dataB = $dbh->prepare($sql);
$dataB->execute();
$Records = $dataB->fetchAll();

foreach ($Records as $row) {
    $data[] = array(
        "ref" => $row['ref'],
        "label" => $row['label'],
        "tosell" => $row['tosell'],
        "tobuy" => $row['tobuy'],
        "CABYS" => $row['cabys_codigo'],
        "impuesto" => $row['impuesto'],
        "total" => $row['total'],
        "label_limpio" => $row['label_limpio'],
        "precio" => $row['precio'],
        "stock_actual_txt" => $row['stock_actual_txt'],
        "tipo" => $row['tipo'],
        "rowid" => $row['rowid']
    );
}

$response = array(
    "draw" => intval($_POST['draw']),
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalRecords, 
    "data" => $data
);

echo json_encode($response);
