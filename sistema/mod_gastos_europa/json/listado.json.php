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
    "factura" => "a.fk_tercero",
    "proveedor" => "concat(t.nombre , ' ' , t.apellidos)",
    "fecha" => "a.fecha",
    "usuario" => "concat(u.nombre , ' ' , u.apellidos)",
    "monto" => "a.valor",
    "cuenta_de_gasto" => "g.nombre",
    "estado" => "a.pagado"
);

for ($i = 0; $i < count($columnas); $i++) {
    if ($columnas[$i]['search']['value'] != '') {
        $array_columnas[$columnas[$i]['data']] = substr($columnas[$i]['search']['value'], 1, -1);
    }
}

// Consulta base
$sqlstr = "SELECT 
                a.rowid,
                a.entidad,
                a.recibo_numero as factura, 
                CONCAT(t.nombre , ' ' , t.apellidos) as proveedor, 
                t.rowid AS proveedor_id,
                CASE 
                    WHEN a.fecha IS NULL OR a.fecha = '0000-00-00' THEN 'Sin fecha' 
                    ELSE a.fecha 
                END as fecha, 
                IFNULL(CONCAT(u.nombre , ' ' , u.apellidos), 'Usuario no asignado') as usuario, 
                a.valor as monto, 
                g.nombre as cuenta_de_gasto, 
                IF(a.pagado = 0, 'No pagado', 'Pagado') as estado
            FROM 
                fi_europa_gastos as a 
                LEFT JOIN fi_terceros as t ON a.fk_tercero = t.rowid 
                LEFT JOIN fi_usuarios as u ON a.creado_fk_usuario = u.rowid 
                LEFT JOIN fi_gastos_tipos as g ON a.fk_gasto = g.rowid
            WHERE a.entidad = :entidad";



$sqlstr.= " AND a.borrado = 0 ";
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
$sqlstrFilter = "SELECT COUNT(*) FROM fi_europa_gastos a WHERE a.entidad = :entidad";


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
    $tipo = $row['electronica_tipo']== "tiquete" ? 'Simplificada' : 'Normal';
    $pagado = numero_simple($row['pagado']);
  
    $estado_final = ($row['estado'] == 0) ? 'Borrador' : $row['estado_hacienda'];

     // Obtener ruta del archivo basado en la referencia y tipo
     $date = strtotime($row['fecha']);
     $month = date('m', $date);
     $year = date('Y', $date);
 

    $estado = $row['estado'];
    $badgeClass = ''; // Inicializamos la variable
    
    if ($estado === 'No pagado') {
        $badgeClass = 'danger'; // Clase para "No pagado"
        $row['estado'] = 'Pendiente';
    } elseif ($estado === 'Pagado') {
        $badgeClass = 'success'; // Clase para "Pagado"
    }
    

    $data[] = array(
    "id" => $row['rowid'],
    "entidad" => $row['entidad'],
    "factura" => $row['factura'],
    "proveedor" => $row['proveedor'],
    "proveedor_id" => $row['proveedor_id'],
    "fecha" => $row['fecha'],
    "fecha2" => $row['fecha'],
    "usuario" => $row['usuario'],
    "monto" => $row['monto'],
    "cuenta_de_gasto" => $row['cuenta_de_gasto'],
    "estado" => $row['estado'],
    "color"=>$badgeClass,

    );
}

// Respuesta JSON
$response = array(
    "draw" => intval($_GET['draw']),
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalRecords,
    'sql' => $sqlstr,
    "data" => $data,

);

echo json_encode($response);
?>