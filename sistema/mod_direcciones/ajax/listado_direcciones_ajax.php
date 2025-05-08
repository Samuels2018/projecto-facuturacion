<?php
session_start();

// Si no hay usuario autenticado, cerrar conexión
if (!isset($_SESSION['usuario'])) {
    echo acceso_invalido();
    exit(1);
}

require_once "../../conf/conf.php";

$inicio = $_GET['start'];
$limite = $_GET['length'];
$buscarArray = $_GET['search'];
$buscar = $buscarArray['value'];
$columnas = $_GET['columns']; // Obtiene las columnas para filtrar
$array_columnas = [];

$tipo_entidad = $_GET["tipo_entidad"];//Viene de otros formularios para listar direcciones de Agentes, Clientes, etc.

$mapaColumnas = array(
    "ID" => "a.rowid",
    "descripcion" => "a.descripcion",
    "poblacion" => "poblacion.nombre",
    "descripcion" => "a.descripcion",
    "activo" => "a.activo"
);

for ($i = 0; $i < count($columnas); $i++) {
    if ($columnas[$i]['search']['value'] != '') {
        $array_columnas[$columnas[$i]['data']] = substr($columnas[$i]['search']['value'], 1, -1);
    }
}

$sqlstr = "SELECT a.*, poblacion.nombre as poblacion, provincia.provincia as provincia 
            FROM diccionario_direccion a
            LEFT JOIN " . $_ENV['DB_NAME_UTILIDADES_APOYO'] . ".diccionario_comunidades_autonomas poblacion ON a.codigo_poblacion = poblacion.id 
            LEFT JOIN " . $_ENV['DB_NAME_UTILIDADES_APOYO'] . ".diccionario_comunidades_autonomas_provincias provincia ON a.codigo_provincia = provincia.id
            WHERE 1 AND a.entidad = :entidad ";

if(isset($tipo_entidad)){
    $sqlstr .= ' AND a.tipo_entidad = '.$tipo_entidad.' ';
}

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

// Maneja el filtrado y calcula el número total de registros filtrados
$sqlstrFilter = str_replace("LIMIT 0, $limite", "", $sqlstr);
$sqlstrFilter = str_replace("OFFSET $inicio", "", $sqlstrFilter);

// Obtiene el número total de registros
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
        "entidad" => $row['entidad'],
        "descripcion" => $row['descripcion'],
        "poblacion" => $row['poblacion'],
        "activo" => intval($row['activo']),
    );
}

$response = array(
    "draw" => intval($_GET['draw']),
    "recordsTotal" => $totalFilteredRecords,
    "recordsFiltered" => $totalFilteredRecords,
    "data" => $data
);

echo json_encode($response);