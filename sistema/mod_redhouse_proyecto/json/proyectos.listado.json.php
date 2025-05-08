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

//Verificar si la URL existe para mostrar la imagen AVATAR o Generar un AVATAR IMAGEN con  el nombre y Apellido
function verify_url_exists($url,$text)
{
    if(!empty($url)){
        $data = file_get_contents($url);
        if($data !== false)
        {
          return $url;
        }else{
          return 'https://ui-avatars.com/api/?name='.$text.'&background=E7515A&color=fff';
        }   
    }else{
          return 'https://ui-avatars.com/api/?name='.$text.'&background=E7515A&color=fff';
    }
}


$inicio = isset($_GET['start']) ? $_GET['start'] : 0;
$limite = isset($_GET['length']) ? $_GET['length'] : 10;
$buscar = $_GET['search']['value'];
$columnas = $_GET['columns'];
$array_columnas = [];

$mapaColumnas = array(
    "ID" => "a.rowid",
    "Referencia" => "a.cotizacion_referencia",
    "cliente_tercero" => "CONCAT(t.nombre, ' ', t.apellidos)",
    "Fecha" => "a.cotizacion_fecha",
    "agente_txt" => "CONCAT(u.nombre, ' ', u.apellidos)"
);


$sqlstr = " SELECT * FROM a_medida_redhouse_proyecto where borrado = 0 ";



if ($buscar != '') {
    $conditions = [];
    $parameters = [];
    foreach ($mapaColumnas as $nombreColumnaBD => $columna) {
        $conditions[] = $columna . " LIKE '%$buscar%'";
        $parameters[] = '%' . $buscar . '%';
    }
    if (!empty($conditions)) {
        $sqlstr .= " WHERE ";
        $sqlstr .= implode(" OR ", $conditions);
    }
}


$sqlstr.=' ORDER BY rowid DESC LIMIT '.$limite.' OFFSET '.$inicio;

$dataB = $dbh->prepare($sqlstr);
$dataB->execute();

$Records = $dataB->fetchAll();




$data = array();
foreach ($Records as $row) {

    $data[] = array(
        "ID" => $row['rowid'],
        "Referencia" => $row['proyecto_consecutivo'],
        "proyecto_fecha" => date('d/m/Y', strtotime($row['proyecto_fecha'])), // Formato día/mes/año
        "proyecto_descripcion" => $row['proyecto_descripcion'],
        "proyecto_lugar" => $row['proyecto_lugar'],
        "proyecto_contacto" => $row['proyecto_contacto'],
        "proyecto_estado" => $row['proyecto_estado'],
        "proyecto_fecha_creacion" => date('d/m/Y', strtotime($row['creado_fecha'])), // Formato día/mes/año
    );
    
}



$sqlCount = "SELECT COUNT(*) as total FROM a_medida_redhouse_proyecto";

$dataB = $dbh->prepare($sqlCount);

$dataB->execute();

$datos = $dataB->fetch(PDO::FETCH_OBJ); 
$recordsTotal = $datos->total;



$response = array(
    "draw" => intval($_GET['draw']),
    "recordsTotal" => $recordsTotal,
    "recordsFiltered" => $recordsTotal,
    "data" => $data
);

echo json_encode($response);
?>
