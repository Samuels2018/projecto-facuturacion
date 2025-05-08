<?php
session_start();
require_once "../../conf/conf.php";

//si no hay usuario autenticado, cerrar conexion
if (!isset($_SESSION['usuario'])) {
    echo acceso_invalido();
    exit(1);
}

// Recibimos parámetros de paginación y búsqueda
$inicio = $_GET['start'];
$limite = $_GET['length'];
$buscarArray = $_GET['search'];
$buscar = $buscarArray['value'];
$columnas = $_GET['columns']; // Obtiene las columnas para filtrar
$array_columnas = [];



// Mapa de columnas
$mapaColumnas = array(
    "id" => "a.rowid",
    "entidad" => "a.entidad",
    "titulo" => "a.titulo",
    "orden" => "a.orden",
    "tipo" => "a.tipo",
    "plantilla_html" => "a.plantilla_html",
    "plantilla_css" => "a.plantilla_css",
    "estado_final" => "a.activo",
);

for ($i = 0; $i < count($columnas); $i++) {
    if ($columnas[$i]['search']['value'] != '') {
        $array_columnas[$columnas[$i]['data']] = substr($columnas[$i]['search']['value'], 1, -1);
    }
}

// Consulta base
$sqlstr = "SELECT a.rowid, a.entidad, a.plantilla_html, a.plantilla_css, a.activo, a.creado_fk_usuario, a.titulo, a.orden,
                CONCAT(u.nombre, ' ', u.apellidos) AS usuario_crear,
                CASE WHEN a.activo = 0 THEN 'Inactivo' ELSE 'Activo' END AS estado,
                a.defecto, a.tipo
            FROM fi_europa_documento_plantilla a
            LEFT JOIN fi_usuarios u ON a.creado_fk_usuario = u.rowid
            WHERE a.entidad = :entidad AND a.borrado = 0 ";

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
// echo 'QUERY: '.$sqlstr;
// Conteo de registros totales y filtrados
$sqlstrFilter = "SELECT COUNT(*) FROM fi_europa_documento_plantilla a
                WHERE a.entidad = :entidad";


$totalRecordsStmt = $dbh->prepare($sqlstrFilter);
$totalRecordsStmt->bindParam(':entidad', $_SESSION['Entidad'], PDO::PARAM_INT);
$totalRecordsStmt->execute();
$totalRecords = $totalRecordsStmt->fetchColumn();

// Ejecuta la consulta con los límites
$sqlstr .= "  ORDER BY a.rowid DESC LIMIT :inicio, :limite";

$dataB = $dbh->prepare($sqlstr);
$dataB->bindParam(':entidad', $_SESSION['Entidad'], PDO::PARAM_INT);
$dataB->bindParam(':inicio', $inicio, PDO::PARAM_INT);
$dataB->bindParam(':limite', $limite, PDO::PARAM_INT);
foreach ($array_columnas as $key => $value) {
    $dataB->bindValue(":$key", "%$value%", PDO::PARAM_STR);
}
$dataB->execute();

// Procesa los registros obtenidos
$Records = $dataB->fetchAll();
$data = array();
$array_avatar_repetir = array();

// Procesar los registros
foreach ($Records as $row) {
    $data[] = array(
        "id" => $row['rowid'],
        "entidad" => $row['entidad'],
        "plantilla_html" => $row['plantilla_html'],
        "plantilla_css" => $row['plantilla_css'],
        "usuario_crear" => $row['usuario_crear'],
        "activo" => $row['activo'],
        "estado_final" => $row['estado'],
        "titulo" => $row['titulo'],
        "tipo" => $row['tipo'],
        "orden" => $row['orden'],
        "defecto" => $row['defecto']
    );
}

// Respuesta JSON
$response = array(
    "draw" => intval($_GET['draw']),
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalRecords,
    "data" => $data
);

echo json_encode($response);
