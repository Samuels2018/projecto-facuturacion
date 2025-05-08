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

 

$sqlstr = " SELECT 
a.cotizacion_fecha AS Fecha,
a.rowid AS ID,
t.tipo as tipo_tercero,
a.cotizacion_referencia AS Referencia,
CONCAT(t.nombre, ' ', t.apellidos) AS cliente_tercero_natural,
(t.apellidos) AS cliente_tercero_juridico,
CONCAT(u.nombre, ' ', u.apellidos) AS agente_txt,


(SELECT GROUP_CONCAT(concat(fu.nombre, ' ', fu.apellidos) SEPARATOR ', ')
FROM a_medida_redhouse_cotizaciones_recurso_humano rh
LEFT JOIN fi_usuarios fu ON fu.rowid = rh.fk_usuario
WHERE rh.fk_cotizacion = a.rowid AND rh.borrado = 0) as nombres_recursos,


(SELECT sum(precio_total) FROM `a_medida_redhouse_cotizaciones_cotizaciones_servicios` where fk_cotizacion = a.rowid) as total,
dm.simbolo as simbolo_moneda,
u.avatar,
categorias.etiqueta AS categoria_txt,
estado.etiqueta AS estado_txt, 
estado.estilo AS estado_estilo,
recursos.nombre_recursos AS recursos_humanos_avatar
FROM 
a_medida_redhouse_cotizaciones a
LEFT JOIN 
fi_usuarios u ON a.fk_usuario_asignado = u.rowid
LEFT JOIN 
fi_terceros t ON a.fk_tercero = t.rowid
LEFT JOIN 
a_medida_redhouse_cotizaciones_estado estado ON estado.rowid = a.fk_estado_a_medida_redhouse_estado_cotizaciones 
LEFT JOIN 
a_medida_redhouse_cotizaciones_diccionario_categorias categorias ON categorias.rowid = a.fk_categoria
LEFT JOIN 
diccionario_monedas dm on dm.rowid = a.fk_moneda

LEFT JOIN 
(SELECT 
     fk_cotizacion,
     GROUP_CONCAT(u3.avatar SEPARATOR ', ') AS nombre_recursos
 FROM 
     a_medida_redhouse_cotizaciones_recurso_humano r
 LEFT JOIN 
     fi_usuarios u3 ON r.fk_usuario = u3.rowid
 GROUP BY 
     fk_cotizacion) AS recursos ON a.rowid = recursos.fk_cotizacion

     where a.borrado = 0
";



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


$sqlstr.=' ORDER BY  a.rowid DESC LIMIT '.$limite.' OFFSET '.$inicio;



$dataB = $dbh->prepare($sqlstr);

$dataB->execute();
/*
if (!empty($parameters)) {
    $dataB->execute($parameters);
} else {
   
}
*/

/*
$truncatedString = truncate_string($originalString, 50);
echo $truncatedString;  // Salida: Este es un texto muy largo que probablemente nec...

*/
$Records = $dataB->fetchAll();




$data = array();
foreach ($Records as $row) {

    if ($row['tipo_tercero'] == 'juridica' && !is_null($row['cliente_tercero_juridico'])) {
   $nombre_cliente = $row['cliente_tercero_juridico'];
    }else{
        $nombre_cliente = $row['cliente_tercero_natural'];
    }

//    $nombre_cliente = $row['cliente_tercero_juridico'];

    $data[] = array(
        "ID" =>$row['ID'],
        "Referencia" =>$row['Referencia'],
        "nombres_recursos" =>$row['nombres_recursos'],
        "cliente_tercero" => $nombre_cliente,
        "Fecha" => date('d-m-Y', strtotime($row['Fecha'])),
        "agente_txt" => $row['agente_txt'],
        "subtotal" => numero_simple($row['subtotal']),
        "Impuesto" => numero_simple($row['Impuesto']),
        "Total" => numero_simple($row['total']).' '.$row['simbolo_moneda'],
        "Pagado" => numero_simple($row['Pagado']),
        "etiqueta" => $row['etiqueta'],
        "Tipo" =>  $row['tipo'] ,
        "categoria_txt" => truncate_string($row['categoria_txt'],20),
        "estado_txt"    => $row['estado_txt']        ,
        "estado_estilo" => $row['estado_estilo']       ,
        "avatar" => verify_url_exists($row['avatar'],$row['agente_txt']),
        "recursos_humanos_avatar" => explode(",", $row['recursos_humanos_avatar'] )
        
    );
}



$sqlCount = "SELECT COUNT(*) as total FROM a_medida_redhouse_cotizaciones";

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
