<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    echo acceso_invalido();
    exit(1);
}


 
require_once "../../conf/conf.php";

$inicio         = $_GET['start'];
$limite         = $_GET['length'];
$buscarArray    = $_GET['search'];
$buscar         = $buscarArray['value'];
$columnas = $_GET['columns']; 
$array_columnas = [];

$mapaColumnas = array(
    "ID" => "a.rowid",
    "label" => "a.label",
    "activo" => "a.activo" 

);

for ($i = 0; $i < count($columnas); $i++) {
    if ($columnas[$i]['search']['value'] != '') {
        $array_columnas[$columnas[$i]['data']] = substr($columnas[$i]['search']['value'], 1, -1);
    }
}

$sqlstr = "SELECT * FROM diccionario_rutas a WHERE 1";

if (!empty($array_columnas)) {
    $sqlstr .= " AND (";
    $first = true;
    foreach ($array_columnas as $key => $value) {
        if (isset($mapaColumnas[$key])) {
            $nombreColumnaBD = $mapaColumnas[$key];
            $condicion = $nombreColumnaBD . " LIKE :$key";
            if (!$first) {
                $sqlstr .= " AND ";
            }
            $sqlstr .= $condicion;
            $first = false;
        }
    }
    $sqlstr .= ")";
}

$sqlstrFilter = str_replace("LIMIT 0, $limite", "", $sqlstr);
$sqlstrFilter = str_replace("OFFSET $inicio", "", $sqlstrFilter);

$sqlstr .= ' AND a.entidad = :entidad';

$sqlstr .= " AND a.borrado = 0 ORDER BY a.label ";
 
$resultsFilter = $dbh->prepare($sqlstr);
$resultsFilter->bindParam(':entidad', $_SESSION['Entidad'], PDO::PARAM_INT);
foreach ($array_columnas as $key => $value) {
    $resultsFilter->bindValue(":$key", "%$value%", PDO::PARAM_STR);
}
$resultsFilter->execute();

$totalFilteredRecords = $resultsFilter->rowCount();

$sqlstr .= " LIMIT :inicio, :limite";
$dataB = $dbh->prepare($sqlstr);
$dataB->bindParam(':entidad', $_SESSION['Entidad'], PDO::PARAM_INT);
$dataB->bindParam(':inicio', $inicio, PDO::PARAM_INT);
$dataB->bindParam(':limite', $limite, PDO::PARAM_INT);
foreach ($array_columnas as $key => $value) {
    $dataB->bindValue(":$key", "%$value%", PDO::PARAM_STR);
}
$dataB->execute();

$Records = $dataB->fetchAll();
$data = array();
foreach ($Records as $row) {
    $data[] = array(
        "ID" => $row['rowid'],
        "label"  => $row['label'],
        "activo"    => intval($row['activo'])
    );

}

$response = array(
    "draw" => intval($_GET['draw']),
    "recordsTotal" => $totalFilteredRecords,
    "recordsFiltered" => $totalFilteredRecords,
    "data" => $data
);

echo json_encode($response);
?>
