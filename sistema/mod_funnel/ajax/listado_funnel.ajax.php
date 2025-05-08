<?php
/* Lista de Configuración de Funnels */
/*----------------------------------------------------*/
session_start();
require_once "../../conf/conf.php";

$inicio = $_POST['start'];
$limite = $_POST['length'];
$buscarArray = $_POST['search'];
$buscar = $buscarArray['value'];
$columnas = $_POST['columns']; // Obtiene las columnas para filtrar
$array_columnas = [];

// Ajusta el mapa de columnas para reflejar las columnas de la tabla fi_funnel
$mapaColumnas = array(
    "ID" => "rowid",
    "Título" => "titulo",
    "Descripción" => "descripcion",
    "Color" => "color",
    "Icono" => "icono"
);

// Ajusta la consulta SQL para seleccionar solo las columnas relevantes de la tabla fi_funnel
$sqlstr = "SELECT rowid, titulo, descripcion, color, icono
            FROM fi_funnel";

// Inicializa la condición de búsqueda
$whereClauses = array();

// Verifica si hay un valor de búsqueda
if ($buscar != '') {
    $searchConditions = array();
    foreach ($mapaColumnas as $nombreColumnaBD) {
        // Construye la condición de búsqueda para esta columna
        $searchConditions[] = $nombreColumnaBD . " LIKE '%" . $buscar . "%'";
    }
    if (!empty($searchConditions)) {
        $whereClauses[] = "(" . implode(" OR ", $searchConditions) . ")";
    }
}
// Añade la condición para verificar si el campo 'borrado' es igual a 0
$whereClauses[] = "borrado = 0";
// Construye la cláusula WHERE
if (!empty($whereClauses)) {
    $sqlstr .= " WHERE " . implode(" AND ", $whereClauses);
}
// Obtiene el número total de registros
$totalRecords = count($dbh->query($sqlstr)->fetchAll());
// Maneja el filtrado y calcula el número total de registros filtrados
$sqlstrFilter = $sqlstr;
$resultsFilter = $dbh->query($sqlstrFilter);
$totalFilteredRecords = $resultsFilter->rowCount();

//Entidad en sesion
$sqlstr.= ' AND fi_funnel.entidad =  '.$_SESSION["Entidad"].' ';
// Agrega la cláusula `ORDER BY` y `LIMIT` para la consulta paginada
$sqlstr .= " ORDER BY rowid ASC LIMIT $inicio, $limite";



$dataB = $dbh->prepare($sqlstr);
$dataB->execute();
$Records = $dataB->fetchAll();
$data = array();
foreach ($Records as $row) {
    $data[] = array(
        "ID" => $row['rowid'],
        "Título" => $row['titulo'],
        "Descripción" => $row['descripcion'],
        "Color" => $row['color'],
        "Icono" => $row['icono']
    );
}

$response = array(
    "draw" => intval($_POST['draw']),
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalFilteredRecords,
    "data" => $data
);

echo json_encode($response);
?>
