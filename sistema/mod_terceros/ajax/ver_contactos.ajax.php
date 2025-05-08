<?php
/* Lista de Contactos de Terceros */
/*----------------------------------------------------*/

SESSION_START();

include_once "../../conf/conf.php";

$inicio = $_REQUEST['start'];
$limite = $_REQUEST['length'];
$buscarArray = $_GET['search'];
$buscar = $buscarArray['value'];
$terceroRowid = $_GET['id'];
$columnas = $_GET['columns']; // Obtiene las columnas para filtrar
$array_columnas = [];

// Mapa de columnas
$mapaColumnas = array(
    "Dato" => "tc.dato",
    "Detalle" => "tc.detalle"
);

for ($i = 0; $i < count($columnas); $i++) {
    if ($columnas[$i]['search']['value'] != '') {
        $array_columnas[$columnas[$i]['data']] = substr($columnas[$i]['search']['value'], 1, -1);
    }
}
// Preparar el query base
$sqlstr = "SELECT
                tc.rowid,
                tc.dato,
                tc.detalle,
                dic.label,
                tc.fk_diccionario_contacto
            FROM fi_terceros_contactos tc
            LEFT JOIN diccionario_contacto dic ON dic.rowid = tc.fk_diccionario_contacto
            WHERE fk_tercero = :fk_tercero AND tc.borrado = 0"; // Agrega la condición WHERE aquí

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

// Agregar la cláusula LIMIT para la paginación
$sqlstr .= " LIMIT :inicio, :limite";

// Preparar el statement
$dataB = $dbh->prepare($sqlstr);

// Vincular los parámetros
$dataB->bindValue(':fk_tercero', $terceroRowid, PDO::PARAM_INT);
foreach ($array_columnas as $key => $value) {
    $dataB->bindValue(":$key", "%$value%", PDO::PARAM_STR);
}
$dataB->bindValue(':inicio', $inicio, PDO::PARAM_INT);
$dataB->bindValue(':limite', $limite, PDO::PARAM_INT);

// Ejecutar el query
$dataB->execute();
$Records = $dataB->fetchAll();

// Preparar la respuesta
$data = array();
foreach ($Records as $row) {
    $data[] = array(
        "ID" => $row['rowid'],
        "Dato" => $row['dato'],
        "Detalle" => $row['detalle'],
        "Label" => $row['label'],
        "Icono" => $row['fk_diccionario_contacto'],
    );
}

$response = array(
    "draw" => intval($_GET['draw']),
    "recordsTotal" => count($data), 
    "recordsFiltered" => count($data),
    "data" => $data,
);

echo json_encode($response);
