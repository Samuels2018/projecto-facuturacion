<?php
session_start();

// Si no hay usuario autenticado, cerrar conexión
if (!isset($_SESSION['usuario'])) {
    echo acceso_invalido();
    exit(1);
}

require_once "../../conf/conf.php";
$Utilidades->obtener_diccionario_transacciones_documentos();

$inicio = $_GET['start'];
$limite = $_GET['length'];
$buscarArray = $_GET['search'];
$buscar = $buscarArray['value'];
$columnas = $_GET['columns']; // Obtiene las columnas para filtrar
$array_columnas = [];

$mapaColumnas = array(
    "ID"                    => "a.rowid"                    ,
    "tipo"                  => "a.tipo"                     ,
    "tipo_aeat"             => "a.tipo_aeat"                , 
    "siguiente_documento"   => "a.siguiente_documento"      ,
    "fk_serie_modelo"       => "a.fk_serie_modelo"          ,
    "serie_por_defecto"     => "a.serie_por_defecto"        ,
    "estado" => "a.serie_activa"
);

for ($i = 0; $i < count($columnas); $i++) {
    if ($columnas[$i]['search']['value'] != '') {
        $array_columnas[$columnas[$i]['data']] = substr($columnas[$i]['search']['value'], 1, -1);
    }
}

$sqlstr = "SELECT * FROM fi_europa_facturas_configuracion  a WHERE 1  ";

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

$sqlstr .= ' AND a.entidad = :entidad';

// Agrega la cláusula `LIMIT` para la consulta paginada
if (!isset($array_columnas['estado'])) {
    $sqlstr .= " AND a.borrado = 0 ORDER BY a.tipo_aeat ";
} else {
    $sqlstr .= " AND a.borrado = 0 ORDER BY a.tipo_aeat ";
}



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
        "ID"                    => $row['rowid']                    ,
        "tipo"                  => $Utilidades->diccionario_transacciones_documentos_traductor[$row['tipo']]['descripcion']                     ,
        "tipo_aeat"             => ($row['tipo_aeat']=="otros_no_aeat")? "-" :  $row['tipo_aeat']              ,
        "siguiente_documento"   => $row['siguiente_documento']      ,
        "serie_por_defecto"     => $row['serie_por_defecto']        ,
        "fk_serie_modelo"       => $row['fk_serie_modelo']          ,
        "fk_serie_modelo_txt"   => enmascarar_($row['siguiente_documento'] , $row['fk_serie_modelo']  )        ,
        "serie_descripcion"     =>   (strlen( $row['serie_descripcion']   ) > 35) ? substr($row['serie_descripcion']  , 0, 35) . '...' : $row['serie_descripcion']       ,
        "estado"                => intval($row['serie_activa'])         ,
        "series_cuantos_documentos" => rand(0,30)
    );
}



$response = array(
    "draw" => intval($_GET['draw']),
    "recordsTotal" => $totalFilteredRecords,
    "recordsFiltered" => $totalFilteredRecords,
    "data" => $data
);

echo json_encode($response);


function enmascarar_( $siguiente_documento , $mascara ){

$mascara = str_replace("_Y_", date("Y")                 , $mascara);
$mascara = str_replace("#"  , $siguiente_documento      , $mascara);

return $mascara;

}
?>
