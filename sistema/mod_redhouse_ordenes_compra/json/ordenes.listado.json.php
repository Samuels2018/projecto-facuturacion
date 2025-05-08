<?php
session_start();
require_once "../../conf/conf.php";

function truncate_string($string, $max_length = 100) {
    if (strlen($string) > $max_length) {
        // Cortar la cadena y añadir puntos suspensivos
        return substr($string, 0, $max_length - 3) . "...";
    } else {
        // Devolver la cadena original si no supera la longitud máxima
        return $string;
    }
}

// Verificar si la URL existe para mostrar la imagen AVATAR o Generar un AVATAR IMAGEN con el nombre y apellido
function verify_url_exists($url, $text) {
    if (!empty($url)) {
        $data = file_get_contents($url);
        if ($data !== false) {
            return $url;
        } else {
            return 'https://ui-avatars.com/api/?name=' . $text . '&background=E7515A&color=fff';
        }   
    } else {
        return 'https://ui-avatars.com/api/?name=' . $text . '&background=E7515A&color=fff';
    }
}

$inicio = isset($_GET['start']) ? $_GET['start'] : 0;
$limite = isset($_GET['length']) ? $_GET['length'] : 10;
$buscar = $_GET['search']['value'];
$columnas = $_GET['columns'];
$array_columnas = [];

$mapaColumnas = array(
    "ID" => "a.rowid",
    "Referencia" => "a.orden_consecutivo",
    "Proveedor" => "CONCAT(t.nombre, ' ', t.apellidos)", // Proveedor con nombre y apellidos
    "Proyecto" => "p.proyecto_descripcion", // Descripción del proyecto
    "Fecha Creación" => "a.fecha_creacion",
    "Fecha Vigencia" => "a.fecha_vigencia",
    "Estado" => "a.orden_estado"
);

// Consulta base con uniones a las tablas `fi_terceros` y `a_medida_redhouse_proyecto`
$sqlstr = "SELECT a.rowid, a.orden_consecutivo, a.fecha_creacion, a.fecha_vigencia, 
       a.orden_estado, a.orden_notas, t.nombre, t.apellidos, 
       p.proyecto_descripcion  
FROM a_medida_redhouse_orden_compra a
LEFT JOIN fi_terceros t ON a.fk_proveedor = t.rowid
LEFT JOIN a_medida_redhouse_proyecto p ON a.fk_proyecto = p.rowid
WHERE a.borrado = 0
AND EXISTS (
    SELECT 1
    FROM a_medida_redhouse_orden_compra_servicios s
    WHERE s.fk_orden = a.rowid
)";

if ($buscar != '') {
    $conditions = [];
    foreach ($mapaColumnas as $nombreColumnaBD => $columna) {
        $conditions[] = $columna . " LIKE '%$buscar%'";
    }
    if (!empty($conditions)) {
        $sqlstr .= " AND (" . implode(" OR ", $conditions) . ")";
    }
}

$sqlstr .= ' ORDER BY a.rowid DESC LIMIT ' . $limite . ' OFFSET ' . $inicio;

$dataB = $dbh->prepare($sqlstr);
$dataB->execute();

$Records = $dataB->fetchAll();

// Función para traducir el estado
function traducirEstado($estado) {
    switch ($estado) {
        case 1: return 'Pendiente';
        case 2: return 'Procesado';
        case 3: return 'Completado';
        case 4: return 'Cancelado';
        default: return 'Desconocido';
    }
}

$data = array();
foreach ($Records as $row) {
    $data[] = array(
        "ID" => $row['rowid'],
        "Referencia" => $row['orden_consecutivo'],
        "Fecha Creación" => date('d-m-Y', strtotime($row['fecha_creacion'])), // Formato día/mes/año
        "Fecha Vigencia" => date('d-m-Y', strtotime($row['fecha_vigencia'])), // Formato día/mes/año
        "Proveedor" => $row['nombre'] . ' ' . $row['apellidos'],
        "Proyecto" => truncate_string($row['proyecto_descripcion'], 50), // Descripción truncada si es necesario
        "Estado" => traducirEstado($row['orden_estado']), // Estado traducido
        "Notas" => truncate_string($row['orden_notas'], 50) // Truncar notas si es necesario
    );
}

// Contar el total de registros
$sqlCount = "SELECT COUNT(*) as total FROM a_medida_redhouse_orden_compra WHERE borrado = 0";
$dataB = $dbh->prepare($sqlCount);
$dataB->execute();

$datos = $dataB->fetch(PDO::FETCH_OBJ);
$recordsTotal = $datos->total;

// Respuesta en formato JSON para DataTables
$response = array(
    "draw" => intval($_GET['draw']),
    "recordsTotal" => $recordsTotal,
    "recordsFiltered" => $recordsTotal,
    "data" => $data
);

echo json_encode($response);
?>
