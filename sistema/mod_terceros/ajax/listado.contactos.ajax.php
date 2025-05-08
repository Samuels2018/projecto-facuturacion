<?php
/* Lista de Terceros*/
/*----------------------------------------------------*/
session_start();
require_once "../../conf/conf.php";
//si no hay usuario autenticado, cerrar conexion
if (!isset($_SESSION['usuario'])) {
    echo acceso_invalido();
         exit(1);
  }

$inicio = $_GET['start'];
$limite = $_GET['length'];
$buscarArray = $_GET['search'];
$buscar = $buscarArray['value'];
$columnas = $_GET['columns']; // Obtiene las columnas para filtrar
$array_columnas = [];

$fk_tercero = $_GET["fiche"];

$mapaColumnas = array(
    "ID" => "a.rowid",
    "nombre" => "a.nombre",
    "apellidos" => "a.apellidos",
    "puesto_t" => "a.puesto_t",
    "email" => "a.email",
    "whatsapp" => "a.whatsapp"
);

for ($i = 0; $i < count($columnas); $i++) {
    if ($columnas[$i]['search']['value'] != '') {
        $array_columnas[$columnas[$i]['data']] = substr($columnas[$i]['search']['value'], 1, -1) ;
    }
}

$sqlstr = "SELECT a.rowid, 
    a.nombre,
    a.apellidos,
    a.pais_c,
    a.puesto_t, 
    a.email, 
    a.facebook, 
    a.instagram, 
    a.x_twitter, 
    a.linkedin, 
    a.whatsapp,
    DATE_FORMAT(a.fecha_nacimiento, '%e %M') as cumpleanos, 
    a.fk_tercero
    FROM fi_terceros_crm_contactos a
    WHERE a.fk_tercero = :fk_tercero AND a.borrado = 0 AND a.entidad = :entidad";


// Verifica si hay criterios de búsqueda específicos para las columnas
if (!empty($array_columnas)) {
    $sqlstr .= " AND (";
    $first = true;
    foreach ($array_columnas as $key => $value) {
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

// Conteo de registros totales y filtrados
$sqlstrFilter = "SELECT COUNT(*) FROM fi_terceros_crm_contactos a
                WHERE a.fk_tercero = :fk_tercero AND a.borrado = 0 AND a.entidad = :entidad";

$totalRecordsStmt = $dbh->prepare($sqlstrFilter);
$totalRecordsStmt->bindParam(':entidad', $_SESSION['Entidad'], PDO::PARAM_INT);
$totalRecordsStmt->bindParam(':fk_tercero', $fk_tercero, PDO::PARAM_INT);
$totalRecordsStmt->execute();
$totalRecords = $totalRecordsStmt->fetchColumn();

// Ejecuta la consulta con los límites
$sqlstr .= "  ORDER BY a.rowid DESC LIMIT :inicio, :limite";

$dataB = $dbh->prepare($sqlstr);
$dataB->bindParam(':entidad', $_SESSION['Entidad'], PDO::PARAM_INT);
$dataB->bindParam(':fk_tercero', $fk_tercero, PDO::PARAM_INT);
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

foreach ($Records as $row) {
    $data[] = array(
        "ID" => $row['rowid'],
        "nombre" => $row['nombre'],
        "apellidos" => $row['apellidos'],
        "puesto_t" => $row['puesto_t'],
        "email" => $row['email'],
        "facebook" => $row['facebook'],
        "whatsapp" => $row['whatsapp'],
        "instagram" => $row['instagram'],
        "x_twitter" => $row['x_twitter'],
        "linkedin" => $row['linkedin'],
    );
}

$response = array(
    "draw" => intval($_GET['draw']),
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalRecords,
    "data" => $data
);

echo json_encode($response);
