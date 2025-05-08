<?php
/* Lista de Terceros*/
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
    "Nombre" => "CONCAT(a.nombre, a.apellidos)",
    "Apellidos" => "CONCAT(a.nombre, a.apellidos)",
    "Cedula" => "a.cedula",
    "Telefono" => "a.telefono",
    "Correo" => "a.email",
    "Tipo Persona" => "a.tipo",
    "Categoria" => "dcc.rowid",
    "Estatus" => "a.activo",
    "Fantasia" => "a.electronica_nombre_comercial"
);

for ($i = 0; $i < count($columnas); $i++) {
    if ($columnas[$i]['search']['value'] != '') {
        $array_columnas[$columnas[$i]['data']] = substr($columnas[$i]['search']['value'], 1, -1) ;
    }
}

$sqlstr = "
    SELECT 
        a.rowid, 
        a.nombre, 
        a.apellidos, 
        a.fk_categoria_cliente,  
        a.cedula, 
        a.telefono, 
        a.email, 
        a.tipo, 
        a.activo,
        COALESCE(dcc.label, 'Sin categoría') AS categoria, 
        dcc.rowid AS categoria_id,
        a.electronica_nombre_comercial
    FROM 
        fi_terceros a
    LEFT JOIN 
        diccionario_clientes_categorias dcc 
    ON 
        dcc.rowid = a.fk_categoria_cliente
    WHERE 
        a.proveedor = 1 and a.borrado = 0
        AND a.entidad = '".($_SESSION['Entidad'])."'
";


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

// Get the total number of records
$totalRecords = count($dbh->query($sqlstr)->fetchAll());

// Handle the filtering and calculate the total number of filtered records
$sqlstrFilter = str_replace("LIMIT 0, $limite", "", $sqlstr);
$sqlstrFilter = str_replace("OFFSET $inicio", "", $sqlstrFilter);
$resultsFilter = $dbh->query($sqlstrFilter);
$totalFilteredRecords = $resultsFilter->rowCount();

// Add the `LIMIT` clause for the paged query
$sqlstr .= " ORDER BY a.nombre LIMIT $inicio, $limite";

$dataB = $dbh->prepare($sqlstr);
$dataB->execute();
$Records = $dataB->fetchAll();
$data = array();
foreach ($Records as $row) {

     // Subconsulta quickbooks 
     $sqlstrSync = "SELECT id, error_autocreacion, sistema_externo_id, procesado FROM sistema_externo_sync WHERE sistema_id = ? AND tabla_sistema = 'fi_terceros'  ";
     $dataBSync = $dbh->prepare($sqlstrSync);
     $dataBSync->execute([$row['rowid']]);
     $RecordSync = $dataBSync->fetch();

     

     if ($RecordSync['sistema_externo_id'] != NULL ) {
        $sincronizado = 1;
        $title = 'Sincronizado correctamente';
     }else {
        $sincronizado = 0;
        $title = $RecordSync['error_autocreacion'];
         //si no tiene mensaje de error entonces
        if ($title == '') {
            $title = 'Aun sin procesar'; 
        }
     }


    $data[] = array(
	"ID"=> $row['rowid'],
	"Nombre"=> $row['nombre'].' '.$row['apellidos'],
	"Fantasia"=> $row['electronica_nombre_comercial'],
	"Telefono"=> $row['telefono'],
	"Correo"=>  $row['email'],
	"Detalle" => $row['rowid'],
	"Estatus" => $row['activo'],
	"sincronizado" => $sincronizado,
	"title" => $title,
	"rowid"   => $row['rowid']
    );
       
}

$response = array(
    "draw" => intval($_GET['draw']),
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalFilteredRecords,
    "data" => $data,
    'sql' => $sqlstr
);

echo json_encode($response);