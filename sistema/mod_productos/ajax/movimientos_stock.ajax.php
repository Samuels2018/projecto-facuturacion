
<?php
session_start();
require_once "../../conf/conf.php";

$inicio = $_GET['start'];
$limite = $_GET['length'];
$buscarArray = $_GET['search'];
$buscar = $buscarArray['value'];
$columnas = $_GET['columns'];
$array_columnas = [];

$mapaColumnas = array(
    "ID" => "rowid",
    "Bodega" => "fk_bodega",
    "Producto" => "fk_producto",
    "Tipo" => "tipo",
    "Valor" => "valor",
    "stock_actual" => "stock_actual",
    "Motivo" => "motivo",
    "Fecha" => "fecha",
    "Usuario" => "usuario",
    "Creado_Fecha" => "creado_fecha",
    "Creado_FK_Usuario" => "creado_fk_usuario",
    "Borrado" => "borrado",
    "Borrado_Fecha" => "borrado_fecha",
    "Borrado_FK_Usuario" => "borrado_fk_usuario"
);

for ($i = 0; $i < count($columnas); $i++) {
    if ($columnas[$i]['search']['value'] != '') {
        $array_columnas[$columnas[$i]['data']] = substr($columnas[$i]['search']['value'], 1, -1) ;
    }
}

$id_producto = $_GET['id_producto'];
//var_dump($id_producto);die();

$sqlstr = "SELECT m.rowid, m.fk_bodega, m.fk_producto, m.tipo, m.valor, m.stock_actual, m.motivo, m.fecha, m.usuario, m.creado_fecha,
            m.creado_fk_usuario, m.borrado, m.borrado_fecha, m.borrado_fk_usuario, b.label AS bodega_label, p.label AS producto_label
            FROM fi_bodegas_movimientos m
            LEFT JOIN fi_bodegas b ON m.fk_bodega = b.rowid
            LEFT JOIN fi_productos p ON m.fk_producto = p.rowid
            WHERE m.borrado = 0 AND m.fk_producto = $id_producto";
            //var_dump($sqlstr);die();

//echo $sqlstr;die();

//$db->bindValue(':id_producto', $_GET['id_producto'], PDO::PARAM_INT);

/*
if (!empty($array_columnas)) {
    $sqlstr .= " WHERE (";
    $first = true;
    foreach ($array_columnas as $key => $value) {
        $nombreColumnaBD = $mapaColumnas[$key];
        $condicion = $nombreColumnaBD . " LIKE '%" . $value . "%'";
        if (!$first) {
            $sqlstr .= " AND ";
        }
        $sqlstr .= $condicion;
        $first = false;
    }
    $sqlstr .= ")";
}
*/
$totalRecords = count($dbh->query($sqlstr)->fetchAll());

$sqlstrFilter = str_replace("LIMIT 0, $limite", "", $sqlstr);
$sqlstrFilter = str_replace("OFFSET $inicio", "", $sqlstrFilter);
$resultsFilter = $dbh->query($sqlstrFilter);
$totalFilteredRecords = $resultsFilter->rowCount();

$sqlstr .= " ORDER BY m.rowid LIMIT $inicio, $limite";

$dataB = $dbh->prepare($sqlstr);
$dataB->execute();
$Records = $dataB->fetchAll();
$data = array();
foreach ($Records as $row) {
    //$montoSubtotal = numero_simple($row['subtotal']);
    //$tipo = $row['tipo']== 0 ? 'Normal' : 'Simplificada';

    $data[] = array(
        "ID" => $row['rowid'],
        "Bodega" => $row['bodega_label'],
        "Producto" => $row['producto_label'],
        "Tipo" => $row['tipo'],
        "Valor" => $row['valor'],
        "stock_actual" => $row['stock_actual'],
        "Motivo" => $row['motivo'],
        "Fecha" => date('d-m-Y', strtotime($row['fecha'])),
        "Usuario" => $row['usuario'],
        "Creado Fecha" => $row['creado_fecha'],
        "Creado FK Usuario" => $row['creado_fk_usuario'],
        "Borrado" => $row['borrado'],
        "Borrado Fecha" => $row['borrado_fecha'],
        "Borrado FK Usuario" => $row['borrado_fk_usuario']
    );
}

$response = array(
    "draw" => intval($_GET['draw']),
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalFilteredRecords,
    "data" => $data
);

echo json_encode($response);

