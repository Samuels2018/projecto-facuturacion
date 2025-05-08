<?php
/* Lista de Oportunidades CRM */
/*----------------------------------------------------*/
session_start();
require_once "../../conf/conf.php";


//Modulo usuarios global
require_once ENLACE_SERVIDOR . "mod_usuarios/object/usuarios.object.php";
$Usuarios = new usuario($dbh);

// Parámetros de paginación y búsqueda
$inicio = $_GET['start'];
$limite = $_GET['length'];
$buscarArray = $_GET['search'];
$buscar = $buscarArray['value'];
$columnas = $_GET['columns'];
$array_columnas = [];


$mapaColumnas = array(
    "Referencia" => "a.etiqueta",
    "Oportunidad" => "a.consecutivo",
    "Nombre Contacto" => "CONCAT(b.nombre, ' ', b.apellidos)",
    "Nombre Tercero" => "CONCAT(c.nombre, ' ', c.apellidos)",
    "Importe"=>"a.total",
    "Agente Nombre" => "CONCAT(u.nombre, ' ', u.apellidos)",
    "Estado"=>"a.fk_estado",
    "Nombre Funnel"=>"f.titulo",
    "estatus_detalle_funnel"=>"d.etiqueta"
);


for ($i = 0; $i < count($columnas); $i++) {
    if ($columnas[$i]['search']['value'] != '') {
        $array_columnas[$columnas[$i]['data']] = $columnas[$i]['search']['value'];
    }
}

$sqlstr = "SELECT a.rowid, a.entidad, a.fecha FROM fi_europa_presupuestos a WHERE a.entidad = :entidad";

if (isset($array_columnas['fecha']) && strpos($array_columnas['fecha'], '|') !== false) {
    list($fecha_inicio, $fecha_fin) = explode('|', $array_columnas['fecha']);
    $fecha_inicio = date('Y-m-d 00:00:00', strtotime($fecha_inicio));
    $fecha_fin = date('Y-m-d 23:59:59', strtotime($fecha_fin));
    $sqlstr .= " AND a.fecha BETWEEN :fecha_inicio AND :fecha_fin";
}

if (!empty($array_columnas)) {
    foreach ($array_columnas as $key => $value) {
        if ($key !== 'fecha' && isset($mapaColumnas[$key])) {
            $sqlstr .= " AND " . $mapaColumnas[$key] . " LIKE :$key";
        }
    }
}

$sqlstr = "SELECT a.rowid, a.fk_funnel, 

        CASE
            WHEN a.fecha_cierre NOT REGEXP '^[0-9]{4}-[0-9]{2}-[0-9]{2}$'
            OR STR_TO_DATE(a.fecha_cierre, '%Y-%m-%d') IS NULL
            OR YEAR(a.fecha_cierre) < 1000
            THEN NULL
            ELSE a.fecha_cierre
        END AS fecha_cierre,
a.creado_fecha,a.fk_contacto, a.fk_tercero, a.fk_estado, a.fk_funnel_detalle, a.etiqueta as Referencia, a.nota, a.fk_usuario_asignado, a.tags, a.posicion_funnel, b.nombre as  nombre_contacto, b.apellidos as apellido_contacto, c.nombre as nombre_tercero, c.apellidos as apellido_tercero, d.etiqueta as funnel_detalle, concat(u.nombre, ' ', u.apellidos) as usuario_asignado, f.titulo as funnel, a.total, a.consecutivo , d.etiqueta AS estatus_detalle_funnel , d.estilo AS estilo_detalle_funnel, '€'  AS simbolo_moneda
            FROM fi_oportunidades a 
            left JOIN fi_terceros_crm_contactos b ON b.rowid = a.fk_contacto
            left JOIN fi_terceros c ON c.rowid = a.fk_tercero
            LEFT JOIN fi_funnel f ON f.rowid = a.fk_funnel
            LEFT JOIN fi_funnel_detalle d ON d.rowid = a.fk_funnel_detalle
            LEFT JOIN fi_usuarios u ON u.rowid = a.fk_usuario_asignado
            WHERE 1 ";

$sqlstr .= " AND a.borrado = 0 ";
$sqlstr .= ' AND a.entidad = '.$_SESSION['Entidad'];








if (isset($array_columnas['fecha']) && $array_columnas['fecha'] != '') {
    // Separar el rango de fechas en fecha_inicio y fecha_fin
    $datafecha = explode('|', $array_columnas['fecha']);
    // Asegurarse de que ambas fechas estén en el formato correcto
    $fecha_inicio = date('Y-m-d 00:00:00', strtotime($datafecha[0]));
    $fecha_fin = date('Y-m-d  23:59:59', strtotime($datafecha[1]));

    // Agregar la cláusula WHERE para el rango de fechas
    $sqlstr .= " AND a.creado_fecha BETWEEN '$fecha_inicio' AND '$fecha_fin' ";
}



if (isset($array_columnas['fecha_cierre']) && $array_columnas['fecha_cierre'] != '')
{
    // Separar el rango de fechas en fecha_inicio y fecha_fin
    $datafecha2 = explode('|', $array_columnas['fecha_cierre']);
    // Asegurarse de que ambas fechas estén en el formato correcto
    $fecha_inicio = date('Y-m-d', strtotime($datafecha2[0]));
    $fecha_fin = date('Y-m-d', strtotime($datafecha2[1]));

    // Agregar la cláusula WHERE para el rango de fechas
    $sqlstr .= " AND a.fecha_cierre BETWEEN '$fecha_inicio' AND '$fecha_fin' ";
}



// Verifica si hay criterios de búsqueda específicos para las columnas
if (!empty($array_columnas)) {
    
    $distinto_fecha = 0;
    foreach ($array_columnas as $key => $value) {
        if($key !='fecha' && $key!='fecha_cierre')
        {
            $distinto_fecha++;
            break;
        }
       
    }

    if($distinto_fecha>0)
    {
        $sqlstr .= " AND (";
        $first = true;

        foreach ($array_columnas as $key => $value) {
            if($key === 'fecha') continue;
            if($key === 'fecha_cierre') continue;
            
            // Usa el mapa de columnas para obtener el nombre de la columna en la base de datos
            $nombreColumnaBD = $mapaColumnas[$key];
            // Construye la condición de búsqueda para esta columna
            $condicion = $nombreColumnaBD . " LIKE '%" . $value . "%'";
            if (!$first) {
                $sqlstr .= " AND "; // Cambia de OR a AND si quieres que todas las condiciones se cumplan
            }
            $sqlstr .= $condicion;
            $first = false;
        }

        $sqlstr .= ")";
    }
}


// Obtiene el número total de registros
$totalRecords = count($dbh->query($sqlstr)->fetchAll());

// Maneja el filtrado y calcula el número total de registros filtrados
$sqlstrFilter = str_replace("LIMIT 0, $limite", "", $sqlstr);
$sqlstrFilter = str_replace("OFFSET $inicio", "", $sqlstrFilter);
$resultsFilter = $dbh->query($sqlstrFilter);
$totalFilteredRecords = $resultsFilter->rowCount();

// Agrega la cláusula `LIMIT` para la consulta paginada
$sqlstr .= " ORDER BY a.rowid DESC LIMIT $inicio, $limite";

$dataB = $dbh->prepare($sqlstr);
$dataB->execute();
$Records = $dataB->fetchAll(PDO::FETCH_ASSOC);
$data = array();

$array_avatar_repetir = array();


foreach ($Records as $row) {


    if(isset($array_avatar_repetir[$row['fk_usuario_asignado']]))
    {
        $avatar = $array_avatar_repetir[$row['fk_usuario_asignado']];
    }else{
        $array_avatar_repetir[$row['fk_usuario_asignado']] = $Usuarios->obtener_url_avatar_encriptada($row['fk_usuario_asignado']);
         $avatar = $array_avatar_repetir[$row['fk_usuario_asignado']];
    }

    if ($row['Referencia'] == null) {
        $row['Referencia'] = '-';
    }

    if ($row['nombre_contacto'] == null) {
        $row['nombre_contacto'] = '-';
    }


    
    
    
    $data[] = array(
        "ID" => $row['rowid'],
        "fecha"=>date('d-m-Y',strtotime($row['creado_fecha'])),
        "Funnel" => $row['fk_funnel'],
        "Nombre Funnel" => $row['funnel'],
        "fk_contacto" => $row['fk_contacto'],
        "Contacto" => $row['nombre_contacto'],
        "Tercero" => $row['fk_tercero'],
        "Estado" => $row['fk_estado'],
        "Detalle Funnel" => $row['fk_funnel_detalle'],
        "Referencia" => $row['Referencia'],
        "Nota" => $row['nota'],
        "Agente" => $row['fk_usuario_asignado'], 
        "Agente Nombre" => $row['usuario_asignado'], 
        "Nombre Contacto" => $row['nombre_contacto'] . ' ' . $row['apellido_contacto'],
        "Nombre Tercero" => $row['nombre_tercero'] . ' ' . $row['apellido_tercero'],
        "Nombre Funnel Detalle" => $row['funnel_detalle'],
        "Oportunidad" => $row['consecutivo'] ? $row['consecutivo'] : '-',
        "Importe" => $row['simbolo_moneda'].' '.numero_simple($row['total']),
        "avatar" => $avatar,
        "fecha_cierre" => isset($row['fecha_cierre']) && !is_null($row['fecha_cierre']) ? date('d-m-Y',strtotime($row['fecha_cierre'])) : '',
        "estatus_detalle_funnel" => $row['estatus_detalle_funnel'],
        "estilo_detalle_funnel" => !empty($row['estilo_detalle_funnel']) ? $row['estilo_detalle_funnel'] : 'info'
    );
}

$response = array(
    "draw" => intval($_GET['draw']),
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalFilteredRecords,
    "data" => $data
);

echo json_encode($response);
?>
