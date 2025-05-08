<?php
/* Lista de Configuración de Funnels */
/*----------------------------------------------------*/
session_start();
require_once "../../conf/conf.php";

//si no hay usuario autenticado, cerrar conexion
if (!isset($_SESSION['usuario'])) {
    echo acceso_invalido();
    exit(1);
}


//Modulo usuarios global
require_once ENLACE_SERVIDOR . "mod_usuarios/object/usuarios.object.php";
$Usuarios = new usuario($dbh);

$inicio = $_POST['start'];
$limite = $_POST['length'];
$buscarArray = $_POST['search'];
$buscar = $buscarArray['value'];
$columnas = $_POST['columns']; // Obtiene las columnas para filtrar

$fk_usuario_asignado = $_SESSION['usuario']; //example
$entidad = $_SESSION['Entidad'];
$filtro_usuario = isset($_REQUEST['filtro_usuario']) ? $_REQUEST['filtro_usuario'] : '';
$filtro_estado = isset($_REQUEST['filtro_estado']) ? $_REQUEST['filtro_estado'] : '';


$array_columnas = [];

// Ajusta el mapa de columnas para reflejar las columnas de la tabla 
$mapaColumnas = array(
    "ID" => "fa.rowid",
    "vencimiento_fecha" => "fa.vencimiento_fecha",
    "tipo" => "fa.tipo",
    "nombre_usuario_asginado_txt" => "fu.nombre",
    "estado" => " de.etiqueta",

);

// Ajusta la consulta SQL para seleccionar solo las columnas relevantes de la tabla 
$sqlstr = "SELECT 
    fa.rowid, 
    COALESCE(NULLIF(CONCAT_WS(' ', ft.Nombre, NULLIF(ft.apellidos, '')), ''), CONCAT(crm.nombre, ' ', crm.apellidos), 'Sin asignar') AS cliente_txt,
    fa.tipo, 
    fa.vencimiento_fecha, 
    CONCAT_WS(' ', fu.nombre, NULLIF(fu.apellidos, '')) AS nombre_usuario_asginado_txt, 
    CONCAT(crm.nombre, ' ', crm.apellidos) AS contacto,
    crm.rowid AS contacto_id,
    ft.rowid AS tercero_id,
    fu.rowid AS responsable_id,
    de.etiqueta as estado_txt,
    fa.fk_usuario_asignado,
    fa.fk_oportunidad,
    dca.nombre as actividad_nombre,
    dca.icono as icono_actividad,
    fo.consecutivo,    
    fa.consecutivo as consecutivo_actividad,
    fa.fk_estado
FROM 
    fi_oportunidades_actividades fa
    LEFT JOIN fi_oportunidades fo ON fo.rowid = fa.fk_oportunidad
    LEFT JOIN fi_terceros_crm_contactos crm ON fo.fk_contacto = crm.rowid 
    LEFT JOIN diccionario_crm_actividades dca ON dca.rowid = fa.fk_diccionario_actividad
    LEFT JOIN fi_terceros ft ON ft.rowid = fo.fk_tercero
    LEFT JOIN fi_usuarios fu ON fu.rowid = fa.fk_usuario_asignado
    LEFT JOIN diccionario_crm_actividades_estado de ON de.rowid = fa.fk_estado

";

// Verifica si hay un valor de búsqueda
if ($buscar != '') {
    $sqlstr .= " WHERE (";
    $first = true;
    foreach ($mapaColumnas as $nombreColumnaBD) {
        // Construye la condición de búsqueda para esta columna
        $condicion = $nombreColumnaBD . " LIKE '%" . $buscar . "%'";
        if (!$first) {
            $sqlstr .= " OR ";
        }
        $sqlstr .= $condicion;
        $first = false;
    }
    // Añade la condición para verificar si el campo 'borrado' es igual a 0

    $sqlstr .= ")";
} else {
    // Si no hay criterios de búsqueda específicos, añade la condición directamente
    //Filtrar por todos los usuarios
    if ($filtro_usuario === 'all') {
        //la clausula where no se coloca aca
    } else {
        $sqlstr .= " WHERE   fa.fk_usuario_asignado = $fk_usuario_asignado";
    }
}

//Filtrar por todos los usuarios
if ($filtro_usuario === 'all') {

    $sqlstr .= " WHERE fa.entidad = $entidad  AND fa.tipo = 'tarea'";
} else {

    $sqlstr .= " AND fa.entidad = $entidad AND fa.fk_usuario_asignado = $fk_usuario_asignado  
        AND fa.tipo = 'tarea'";
}


//Si existe el filtro estatus
if ($filtro_usuario === 'all' && $filtro_estado !== '') {
    $sqlstr .= ' AND fa.fk_estado = ' . $filtro_estado;
}

if (isset($_REQUEST['filtro_pendiente'])) {
    $sqlstr .= ' AND fa.fk_estado = ' . $_REQUEST['filtro_pendiente'];
}


// Obtiene el número total de registros
$totalRecords = count($dbh->query($sqlstr)->fetchAll());
if ($_POST['action'] == 'conteo_notificaciones_usuario_actividad') {

    $sqlstr .= ' AND fa.fk_estado = 1';
    $totalRecords = count($dbh->query($sqlstr)->fetchAll());
    echo $totalRecords;
    die();
}

// Maneja el filtrado y calcula el número total de registros filtrados
$sqlstrFilter = str_replace("LIMIT 0, $limite", "", $sqlstr);
$sqlstrFilter = str_replace("OFFSET $inicio", "", $sqlstrFilter);
$resultsFilter = $dbh->query($sqlstrFilter);
$totalFilteredRecords = $resultsFilter->rowCount();

// Agrega la cláusula `LIMIT` para la consulta paginada
$sqlstr .= " ORDER BY fa.fk_usuario_asignado, fo.consecutivo, fa.consecutivo  LIMIT $inicio, $limite";

$dataB = $dbh->prepare($sqlstr);
$dataB->execute();
$Records = $dataB->fetchAll();


$data = array();
$array_avatar_repetir = array();


foreach ($Records as $row) {

    // Calcula la diferencia en días
    $fechaHoy = new DateTime();
    $fechaVencimiento = new DateTime($row['vencimiento_fecha']);
    $intervalo = $fechaHoy->diff($fechaVencimiento);
    $diasDiferencia = (int)$intervalo->format('%r%a'); // Usar %r para obtener el signo (+/-) de la diferencia
    $texto_dias = abs($diasDiferencia) . ' días';

    if ($diasDiferencia === 1) {
        $formato_vencimiento = '<span class="badge badge-warning">Mañana</span>';
    } else if ($diasDiferencia === 0) {
        $formato_vencimiento = '<span class="badge badge-warning">Hoy</span>';
    } else if ($diasDiferencia < 0) {
        if ($diasDiferencia < -3) {
            $formato_vencimiento = '<span class="badge badge-danger"><i class="fas fa-exclamation-triangle"></i> Vencido hace ' . abs($diasDiferencia) . ' días </span>';
        } else {
            $formato_vencimiento = '<span class="badge badge-danger">Vencido hace ' . abs($diasDiferencia) . ' días</span>';
        }
    } else {
        $formato_vencimiento = '<span class="badge badge-success">Faltan ' . $texto_dias . '</span>';
    }


    //Vamos a añadir UN CHECK si el estatus es 3 = es decir  REALIZADA para no generar confunsión en la fecha
    if (intval($row['fk_estado']) === 3) {
        $formato_vencimiento = '<span style=" background-color: #4361EE !important;" class="badge badge-success"><i style="font-size:15px;" class="fa fa-check-circle" aria-hidden="true"></i> Completada</span>';
    }
    $icono = '<i style="font-size:15px;" class="fa ' . $row['icono_actividad'] . '" aria-hidden="true"></i>';



    // Formatea la fecha de vencimiento
    $fechaVencimientoFormateada = $fechaVencimiento->format('d-m-y');

    if (isset($array_avatar_repetir[$row['fk_usuario_asignado']])) {
        $avatar = $array_avatar_repetir[$row['fk_usuario_asignado']];
    } else {
        $array_avatar_repetir[$row['fk_usuario_asignado']] = $Usuarios->obtener_url_avatar_encriptada($row['fk_usuario_asignado']);
        $avatar = $array_avatar_repetir[$row['fk_usuario_asignado']];
    }

    if ($row['cliente_txt'] === 'Sin asignar') {
        $enlace = '#';
    } else {
        $enlace = $row['tercero_id'] ? ENLACE_WEB . 'clientes_editar/' . $row['tercero_id'] : ENLACE_WEB . 'contactos_crm_editar/' . $row['contacto_id'];
    }


    $data[] = array(
        "ID" => $row['rowid'],
        "enlace_tercero" =>  $enlace,
        "enlace_responsable" => ENLACE_WEB . 'clientes_editar/' . $row['responsable_id'],
        "cliente_txt" => $row['cliente_txt'],
        "icono_cliente" => $row['cliente_txt'] ? '<i class="fa fa-user" aria-hidden="true"></i>' : '<span class="badge badge-light-danger">',
        'avatar' => $avatar,
        "vencimiento_fecha" => $fechaVencimientoFormateada, // Fecha formateada
        "dias_vencimiento" => $formato_vencimiento, // Diferencia de días
        // "tipo" => $row['tipo'],
        "nombre_usuario_asginado_txt" => $row['nombre_usuario_asginado_txt'],
        "estado" => $row['estado_txt'],
        "enlace_oportunidad" => ENLACE_WEB . 'ver_oportunidad/' . $row['fk_oportunidad'] . '/' . $row['rowid'],
        "consecutivo" => $row['consecutivo'] ? $row['consecutivo'] : '-',
        "consecutivo_actividad" => $row['consecutivo_actividad'] ? $row['consecutivo_actividad'] : '-',
        "actividad_nombre" => $row['actividad_nombre'] ? $icono . ' ' . $row['actividad_nombre'] : '-'
    );
}

$response = array(
    "draw" => intval($_POST['draw']),
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalFilteredRecords,
    "data" => $data
);

echo json_encode($response);
