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

$mapaColumnas = array(
    "ID" => "a.rowid",
    "Nombre" => "a.nombre",
    "Apellido" => "a.apellidos",
    "Email" => "a.email",
    "Estatus" => "u.fk_estado",
    "EstatusEmpresa" => "se.activo",
    "relacion" => "se.fk_tipo_relacion"
);

for ($i = 0; $i < count($columnas); $i++) {
    if ($columnas[$i]['search']['value'] != '') {
        $array_columnas[$columnas[$i]['data']] = substr($columnas[$i]['search']['value'], 1, -1);
    }
}

// Consulta base
$sqlstr = "SELECT u.rowid, u.nombre, u.apellidos, u.acceso_usuario as email, u.fk_estado as activo, se.activo as activoempresa
            , GROUP_CONCAT(p.etiqueta SEPARATOR ', ') AS perfiles,
            IFNULL(t.etiqueta, '') relacion
            FROM fi_usuarios a 
            INNER JOIN ".$_ENV['DB_NAME_PLATAFORMA'].".sistema_empresa_usuarios se ON se.fk_usuario = a.rowid 
            INNER JOIN ".$_ENV['DB_NAME_PLATAFORMA'].".usuarios u ON se.fk_usuario = u.rowid

            LEFT JOIN ".$_ENV['DB_NAME_PLATAFORMA'].".diccionario_tipo_relacion t ON se.fk_tipo_relacion = t.rowid

            LEFT JOIN fi_usuarios_perfiles_relacion up ON a.rowid = up.fk_usuario
            LEFT JOIN fi_usuarios_perfiles p ON up.fk_usuario_perfil = p.rowid

            WHERE se.fk_empresa = ".($_SESSION['Entidad'])."
            ";

// Verifica si hay criterios de búsqueda específicos para las columnas
if (!empty($array_columnas)) {
    $sqlstr .= " AND (";
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

// Get the total number of records
$totalRecords = count($dbh->query($sqlstr)->fetchAll());

// Handle the filtering and calculate the total number of filtered records
$sqlstrFilter = str_replace("LIMIT 0, $limite", "", $sqlstr);
$sqlstrFilter = str_replace("OFFSET $inicio", "", $sqlstrFilter);
$resultsFilter = $dbh->query($sqlstrFilter);
$totalFilteredRecords = $resultsFilter->rowCount();

// Ejecuta la consulta con los límites
$sqlstr .= "  GROUP BY a.rowid, a.nombre
ORDER BY u.rowid DESC LIMIT $inicio, $limite";

$dataB = $dbh->prepare($sqlstr);
$dataB->execute();
$Records = $dataB->fetchAll();
$data = array();
foreach ($Records as $row) {
    $data[] = array(
        "ID" => $row['rowid'],
        "Nombre" => $row['nombre'],
        "Apellido" => $row['apellidos'],
        "Email" => $row['email'] == null ? 'No configurado' :  $row['email'],
        "Estatus" => $row['activo'],
        "EstatusEmpresa" => $row['activoempresa'],
        "perfiles" => $row['perfiles'],
        "relacion" => $row['relacion']
    );    
}

// Respuesta JSON
$response = array(
    "draw" => intval($_GET['draw']),
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalRecords,
    "data" => $data,
    "sql" => $sqlstr
);
echo json_encode($response);