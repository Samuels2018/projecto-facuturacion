<?php
/* Lista de Usuarios */
/*----------------------------------------------------*/
session_start();
require_once "../../conf/conf.php";

$inicio = $_GET['start'];
$limite = $_GET['length'];
$buscarArray = $_GET['search'];
$buscar = $buscarArray['value'];
$columnas = $_GET['columns']; // Obtiene las columnas para filtrar
$array_columnas = [];

$mapaColumnas = array(
    "ID" => "a.rowid",
    "Etiqueta" => "a.etiqueta",
    "Estatus" => "a.borrado"
);

for ($i = 0; $i < count($columnas); $i++) {
    if ($columnas[$i]['search']['value'] != '') {
        $array_columnas[$columnas[$i]['data']] = substr($columnas[$i]['search']['value'], 1, -1);
    }
}

$sqlstr = "SELECT a.*
            FROM fi_usuarios_perfiles a
         WHERE 1";

// Verifica si hay criterios de búsqueda específicos para las columnas
if (!empty($array_columnas)) {
    $sqlstr .= " AND (";
    $first = true;
    foreach ($array_columnas as $key => $value) {
        // Usa el mapa de columnas para obtener el nombre de la columna en la base de datos
        $nombreColumnaBD = $mapaColumnas[$key];
        // Construye la condición de búsqueda para esta columna
        $condicion = $nombreColumnaBD . " LIKE '%" . $value . "%'";
        if (!$first) {
            $sqlstr .= " AND "; // Cambiado de "OR" a "AND"
        }
        $sqlstr .= $condicion;
        $first = false;
    }
    $sqlstr .= ")";
}


function truncateString($string, $maxLength, $append = "...") {
    // Verificar si el string es más largo que la longitud máxima
    if (strlen($string) > $maxLength) {
        // Cortar el string al máximo y quitar cualquier espacio en blanco al final
        $truncated = rtrim(substr($string, 0, $maxLength));
        // Añadir los puntos suspensivos al final del string recortado
        return $truncated . $append;
    }
    // Devolver el string original si no es más largo que el máximo
    return $string;
}





// Maneja el filtrado y calcula el número total de registros filtrados
$sqlstrFilter = str_replace("LIMIT 0, $limite", "", $sqlstr);
$sqlstrFilter = str_replace("OFFSET $inicio", "", $sqlstrFilter);


$sqlstr.= ' AND a.entidad =  '.$_SESSION['Entidad'];
// Agrega la cláusula `LIMIT` para la consulta paginada

if(!isset($array_columnas['Estatus']))
{
    $sqlstr .= " AND a.borrado = 0 ORDER BY a.etiqueta LIMIT $inicio, $limite";
}else{
    $sqlstr .= " ORDER BY a.etiqueta LIMIT $inicio, $limite";
}

// Obtiene el número total de registros
$resultsFilter = $dbh->query($sqlstr);
$totalFilteredRecords = $resultsFilter->rowCount();
$totalRecords = count($dbh->query($sqlstr)->fetchAll());


$dataB = $dbh->prepare($sqlstr);
$dataB->execute();
$Records = $dataB->fetchAll();
$data = array();
foreach ($Records as $row){

    $data[] = array(
        "ID" => $row['rowid'],
        "Etiqueta" => $row['etiqueta'],
        "Estatus" => intval($row['borrado']),
    );
}

$response = array(
    "draw" => intval($_GET['draw']),
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalFilteredRecords,
    "data" => $data
);

echo json_encode($response);
