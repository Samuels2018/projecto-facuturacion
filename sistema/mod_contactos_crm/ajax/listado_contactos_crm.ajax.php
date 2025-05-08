<?php
/* Lista de Contactos CRM */
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
    "Nombre" => "a.nombre",
    "Apellido" => "a.apellidos",
    "Pais" => "a.pais_c",
    "Puesto" => "a.puesto_t",
    "Email" => "a.email",
    "Telefono" => "a.telefono",
    "Facebook" => "a.facebook",
    "LinkedIn" => "a.linkedin",
    "Fecha Nacimiento" => "a.fecha_nacimiento",
    "Extension" => "a.extension",
    "WhatsApp" => "a.whatsapp",
    "Instagram" => "a.instagram",
    "Twitter" => "a.x_twitter",
    "Tercero Asociado" => "ft.electronica_nombre_comercial",
    "Nombre Tercero" => "concat(ft.nombre,' ',ft.apellidos)" // Añadido para mostrar el nombre comercial
);

for ($i = 0; $i < count($columnas); $i++) {
    if ($columnas[$i]['search']['value'] != '') {
        $array_columnas[$columnas[$i]['data']] = substr($columnas[$i]['search']['value'], 1, -1);
    }
}

$sqlstr = "SELECT a.rowid, a.nombre, a.apellidos, a.pais_c, a.puesto_t, a.email, a.telefono, a.facebook, a.linkedin, a.fecha_nacimiento, a.extension, a.whatsapp, a.instagram, a.x_twitter, ft.electronica_nombre_comercial, ft.nombre as nombre_tercero, ft.apellidos as apellido_tercero, ft.cliente, a.fk_tercero, COUNT(fi_oportunidades_actividades.rowid) as cantidad_oportunidades
           FROM fi_terceros_crm_contactos a
           LEFT JOIN fi_terceros ft ON ft.rowid = a.fk_tercero
           LEFT JOIN fi_oportunidades ON fi_oportunidades.fk_contacto = a.rowid
           LEFT JOIN fi_oportunidades_actividades ON fi_oportunidades.rowid = fi_oportunidades_actividades.fk_oportunidad AND fi_oportunidades_actividades.tipo = 'tarea'
           and fi_oportunidades_actividades.fk_estado = 1
           WHERE a.borrado = 0
           GROUP BY a.rowid, a.nombre, a.apellidos, a.pais_c, a.puesto_t, a.email, a.telefono, a.facebook, a.linkedin, a.fecha_nacimiento, a.extension, a.whatsapp, a.instagram, a.x_twitter, ft.electronica_nombre_comercial, ft.nombre, ft.apellidos, ft.cliente, a.fk_tercero";
            

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


function truncateString($string, $maxLength, $append = "...") {
    // Verificar si el string es más largo que la longitud máxima
    if (strlen($string) > $maxLength) {
        // Cortar el string al máximo y quitar cualquier espacio en blanco al final
        $truncated = rtrim(substr($string, 0, $maxLength));
        // Añadir los puntos suspensivos al final del string recortado
        return $truncated . $append;
    }
    // Devolver el string original si no es más largo que el máximo
    return $string;
}





// Maneja el filtrado y calcula el número total de registros filtrados
$sqlstrFilter = str_replace("LIMIT 0, $limite", "", $sqlstr);
$sqlstrFilter = str_replace("OFFSET $inicio", "", $sqlstrFilter);


$sqlstr.= ' AND a.entidad =  '.$_SESSION['Entidad'];
// Agrega la cláusula `LIMIT` para la consulta paginada
$sqlstr .= " ORDER BY a.nombre LIMIT $inicio, $limite";


// Obtiene el número total de registros
$resultsFilter = $dbh->query($sqlstr);
$totalFilteredRecords = $resultsFilter->rowCount();
$totalRecords = count($dbh->query($sqlstr)->fetchAll());


$dataB = $dbh->prepare($sqlstr);
$dataB->execute();
$Records = $dataB->fetchAll();
$data = array();
foreach ($Records as $row) {

    $nombre_tercero = strlen($row['nombre_tercero'] . ' ' . $row['apellido_tercero']) > 50 ? substr($row['nombre_tercero'] . ' ' . $row['apellido_tercero'], 0, 50) . '...' : $row['nombre_tercero'] . ' ' . $row['apellido_tercero'];

    $data[] = array(
        "ID" => $row['rowid'],
        "Nombre" => $row['nombre'],
        "Apellido" => $row['apellidos'],
        "Pais" => $row['pais_c'],
        "Puesto" => $row['puesto_t'],
        "Email" => $row['email'],
        "Telefono" => $row['telefono'],
        "Facebook" => $row['facebook'],
        "LinkedIn" => $row['linkedin'],
        "Fecha Nacimiento" => $row['fecha_nacimiento'] == null ? null :  date('d F', strtotime($row['fecha_nacimiento'])),
        "Extension" => $row['extension'],
        "WhatsApp" => $row['whatsapp'],
        "Instagram" => $row['instagram'],
        "Twitter" => $row['x_twitter'],
        "Tercero Asociado" => $row['electronica_nombre_comercial'],
        "Tipo" => $row['cliente'],
        "Tercero" => $row['fk_tercero'],
        "Nombre Tercero" => truncateString($nombre_tercero,15),
        "Cantidad Oportunidades" => $row['cantidad_oportunidades']
    );
}

$response = array(
    "draw" => intval($_GET['draw']),
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalFilteredRecords,
    "data" => $data
);

echo json_encode($response);
