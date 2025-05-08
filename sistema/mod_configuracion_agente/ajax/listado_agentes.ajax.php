<?php
/* Lista de Configuración de Agentes */
/*----------------------------------------------------*/
session_start();
require_once "../../conf/conf.php";

$inicio = $_GET['start'];
$limite = $_GET['length'];
$buscarArray = $_GET['search'];
$buscar = $buscarArray['value'];
$columnas = $_GET['columns']; // Obtiene las columnas para filtrar
$array_columnas = [];

// Ajusta el mapa de columnas para reflejar las columnas de la tabla fi_agentes
$mapaColumnas = array(
    "ID" => "rowid",
    "nombre" => "nombre",
    "email" => "email",
    "meta" => "meta",
    "comision" => "comision",
    "persona_contacto" => "persona_contacto",
    "movil" => "movil",
    "telefono" => "telefono",
    "web" => "web",
    "activo"=>"activo",
);

for ($i = 0; $i < count($columnas); $i++) {
    if ($columnas[$i]['search']['value'] != '') {
        $array_columnas[$columnas[$i]['data']] = substr($columnas[$i]['search']['value'], 1, -1);
    }
}

// Ajusta la consulta SQL para seleccionar solo las columnas relevantes de la tabla fi_agentes
$sqlstr = "SELECT rowid, nombre, email, comision, meta, persona_contacto, movil, telefono, web , activo
            FROM fi_agentes WHERE borrado = 0 AND entidad = :entidad ";

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
        "ID" => $row["rowid"],
        "nombre" => $row["nombre"],
        "email" => $row["email"],
        "comision" => $row["comision"],
        "meta" => $row["meta"],
        "persona_contacto" => $row["persona_contacto"],
        "movil" => $row["movil"],
        "telefono" => $row["telefono"],
        "web" => $row["web"],
        "activo"=>$row['activo']
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
