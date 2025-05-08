<?php
session_start();

// Si no hay usuario autenticado, cerrar conexión
if (!isset($_SESSION['usuario'])) {
 //   echo acceso_invalido();
  //  exit(1);
}

$_SESSION['Entidad'] = 1;

require_once "../../conf/conf.php";

$inicio = $_GET['start'];
$limite = $_GET['length'];
$buscarArray = $_GET['search'];
$buscar = $buscarArray['value'];
$columnas = $_GET['columns']; // Obtiene las columnas para filtrar
$array_columnas = [];

$mapaColumnas = array(
    "ID" => "a.rowid",
    "Nombre" => "a.nombre_banco",
    "Estatus" => "a.activo"
);

for ($i = 0; $i < count($columnas); $i++) {
    if ($columnas[$i]['search']['value'] != '') {
        $array_columnas[$columnas[$i]['data']] = substr($columnas[$i]['search']['value'], 1, -1);
    }
}

$sqlstr = "SELECT * FROM dev_factuguay_log.sql_logerrores a WHERE 1";

// Verifica si hay criterios de búsqueda específicos para las columnas
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

// Agrega filtro por entidad
 $sqlstr .= '  ORDER BY a.rowid DESC ';

// Maneja el filtrado y calcula el número total de registros filtrados
$resultsFilter = $dbh->prepare($sqlstr);
//$resultsFilter->bindParam(':entidad', $_SESSION['Entidad'], PDO::PARAM_INT);
foreach ($array_columnas as $key => $value) {
    $resultsFilter->bindValue(":$key", "%$value%", PDO::PARAM_STR);
}
$resultsFilter->execute();

$totalFilteredRecords = $resultsFilter->rowCount();

$sqlstr .= " LIMIT :inicio, :limite";
$dataB = $dbh->prepare($sqlstr);

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
        "fecha" => $row['fecha'],
        "proceso" => $row['proceso'],
        "sql_consulta" => $row['sql_consulta'],
        "error" => $row['error'],
        "file" => $row['file'],   
        "Estatus" => intval($row['activo']),
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
