<?php
session_start();
require_once "../../conf/conf.php";

// Verificar si hay usuario autenticado
if (!isset($_SESSION['usuario'])) {
    echo json_encode(["error" => "Acceso inválido"]);
    exit;
}

// Recibimos parámetros de paginación y búsqueda
// Establecer valores predeterminados si no están definidos en la solicitud
$inicio = isset($_REQUEST['start']) ? (int)$_REQUEST['start'] : 0;
$limite = isset($_REQUEST['length']) ? (int)$_REQUEST['length'] : 10;
$buscarArray = $_REQUEST['search'];
$buscar = $buscarArray['value'];


$columnas = $_REQUEST['columns'];
$array_columnas = [];

// Mapa de columnas para la búsqueda
$mapaColumnas = array(
    "nombre"          => "a.nombre",
    "referencia"      => "a.referencia",
    "ubicacion_mapa"  => "a.ubicacion_mapa",
    "cliente"         => "CONCAT(t.nombre, ' ', IFNULL(t.apellidos, ''))",
    "estado"          => "a.estado",
    "etiquetas_tags"  => "a.etiquetas_tags",
    "monto"           => "a.monto",
    "fecha_inicio"    => "a.fecha_inicio",
    "fecha_fin"       => "a.fecha_fin"
);

// Procesar columnas para búsqueda
for ($i = 0; $i < count($columnas); $i++) {
   /*  if ($columnas[$i]['search']['value'] != '') {
        $patron = "/^[^a-zA-Z0-9]+|[^a-zA-Z0-9]+$/";
        $cadenaLimpia = preg_replace($patron, '', $columnas[$i]['search']['value']);
        $array_columnas[$columnas[$i]['data']] = $cadenaLimpia;
    } */

    if ($columnas[$i]['search']['value'] != '') {
        if($columnas[$i]['data'] != 'fecha_inicio' || $columnas[$i]['data'] != 'fecha_fin'){
            $array_columnas[$columnas[$i]['data']] = substr($columnas[$i]['search']['value'], 1, -1);
        }else{
            $array_columnas[$columnas[$i]['data']] = $columnas[$i]['search']['value'];
        }
    }
}

// Consulta base con JOIN para obtener el cliente de la tabla fi_terceros
$sqlstr = "SELECT 
                a.rowid,
                a.nombre,
                a.referencia,
                a.ubicacion_mapa,
                IFNULL(CONCAT(t.nombre, ' ', IFNULL(t.apellidos, '')), 'Sin cliente') AS cliente,
                a.estado,
                a.etiquetas_tags,
                IFNULL(a.monto, 'No especificado') AS monto,
                a.fecha_inicio,
                a.fecha_fin,
                t.rowid AS client_id
            FROM 
                fi_proyectos AS a
            LEFT JOIN 
                fi_terceros AS t 
            ON 
                a.fk_tercero = t.rowid
            WHERE 
                a.borrado = 0 AND a.entidad = :entidad";

// Verifica si hay criterios de búsqueda específicos para las columnas
if (!empty($array_columnas)) {
    $sqlstr .= " AND (";
    $first = true;
    foreach ($array_columnas as $key => $value) {
        
        if ($key != 'fecha_inicio' && $key != 'fecha_fin') {
          
            $nombreColumnaBD = $mapaColumnas[$key];
            $condicion = $nombreColumnaBD . " LIKE :$key";
            
        }else{        
            $fecha = $Utilidades->formatDateRange($value);
            $fechaInicio = date('Y-m-d', strtotime($fecha[0]));
            $fechaFin = date('Y-m-d', strtotime($fecha[1]));
            $condicion = "(a.fecha_inicio BETWEEN :fechaInicio AND :fechaFin OR a.fecha_fin BETWEEN :fechaInicio AND :fechaFin)";               
           
        }

        if (!$first) {
            $sqlstr .= " AND ";
        }
        $sqlstr .= $condicion;
        $first = false;
    }
    $sqlstr .= ")";
}

// Consulta para obtener el total de registros
$sqlstrFilter = "SELECT COUNT(*) FROM fi_proyectos a WHERE a.entidad = :entidad AND a.borrado = 0";

$totalRecordsStmt = $dbh->prepare($sqlstrFilter);
$totalRecordsStmt->bindParam(':entidad', $_SESSION['Entidad'], PDO::PARAM_INT);
$totalRecordsStmt->execute();
$totalRecords = $totalRecordsStmt->fetchColumn();

// Ejecuta la consulta con los límites
//$sqlstr .= " ORDER BY a.fecha DESC LIMIT :inicio, :limite";
$sqlstr .= " ORDER BY a.rowid DESC LIMIT :inicio, :limite";

$dataB = $dbh->prepare($sqlstr);
$dataB->bindParam(':entidad', $_SESSION['Entidad'], PDO::PARAM_INT);
$dataB->bindParam(':inicio', $inicio, PDO::PARAM_INT);
$dataB->bindParam(':limite', $limite, PDO::PARAM_INT);

foreach ($array_columnas as $key => $value) {
    if ($key == 'fecha_inicio' || $key == 'fecha_fin') {        
        $dataB->bindValue(":fechaInicio", $fechaInicio, PDO::PARAM_STR);
        $dataB->bindValue(":fechaFin", $fechaFin, PDO::PARAM_STR);
    } else {
        $dataB->bindValue(":$key", "%$value%", PDO::PARAM_STR);
    }
}

$dataB->execute();

// Procesar los registros obtenidos
$Records = $dataB->fetchAll();
$data = array();

// Procesar los registros para la respuesta

/* 

*/

foreach ($Records as $row) {
    $fechaInicioFormateada = date('Y-m-d', strtotime($row['fecha_inicio']));
    $fechaFinFormateada = date('Y-m-d', strtotime($row['fecha_fin']));
    $data[] = array(
        "rowid"         => $row['rowid'],
        "nombre"         => $row['nombre'],
        "referencia"     => $row['referencia'],
        "ubicacion_mapa" => $row['ubicacion_mapa'],
        "cliente"        => $row['cliente'],
        "estado"         => $row['estado'],
        "etiquetas_tags" => $row['etiquetas_tags'],
        "monto"          => $row['monto'],
        "fecha_inicio"   => $fechaInicioFormateada,
        "fecha_fin"      => $fechaFinFormateada,        
        "client_id" => $row['client_id']
    );
}

// Respuesta JSON para DataTables
$response = array(
    "draw" => intval($_GET['draw']),
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalRecords,
    "data" => $data,
    "consulta"=>$sqlstr,
);

echo json_encode($response);
?>
