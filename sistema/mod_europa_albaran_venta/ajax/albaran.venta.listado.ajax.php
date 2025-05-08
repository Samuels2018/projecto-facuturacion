<?php
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
$entidad = $_SESSION['Entidad'];
 
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


    "usuario_crear" => "CONCAT(u.nombre, ' ', u.apellidos)",
    "usuario_validar" => "CONCAT(t.nombre, ' ', t.apellidos)",
    "fecha" => "a.fecha",
    "fecha_vencimiento" => "a.fecha_vencimiento",
    "referencia" => "a.referencia",
    "tipo" => "a.tipo",
    "forma_pago" => "a.forma_pago",
    "detalle" => "a.detalle",
    "tercero" => "a.fk_tercero",
    "total" => "a.total",
    "subtotal_pre_retencion" => "a.subtotal_pre_retencion",
    "impuesto_iva" => "a.impuesto_iva",
    "estado" => "a.estado",
    "estado_txt" => "d.rowid", // Ajuste aquí
    "pagado" => "a.pagado",
    "estado_pagada" => "a.estado_pagada",
    "fecha_creacion_server" => "a.fecha_creacion_server",
    "envio_correo_compra" => "a.envio_correo_compra",
    "envio_correo_compra_fecha" => "a.envio_correo_compra_fecha",
    "envio_correo_compra_correo" => "a.envio_correo_compra_correo",
    "cliente_tercero" => "CONCAT(t.nombre,t.apellidos)",
    "estado_hacienda" => "d.etiqueta",
    "color" => "d.class", // Cambiado para apuntar a la etiqueta de diccionario_factura_europa_diccionario
);

// Procesamiento de columnas con manejo especial para fechas
for ($i = 0; $i < count($columnas); $i++) {
    if (!empty($columnas[$i]['search']['value'])) {
        $columnName = $columnas[$i]['data'];
        $searchValue = $columnas[$i]['search']['value'];
        
        // Limpiar valor (remover ^ y $ de DataTables)
        $cleanValue = trim(str_replace(['^', '$'], '', $searchValue));
        
        if ($columnName === 'fecha') {
            $array_columnas[$columnName] = $cleanValue;
        } elseif (in_array($columnName, ['total', 'impuesto_iva', 'subtotal_pre_retencion'])) {
            $array_columnas[$columnName] = processNumericFilter($cleanValue);
        } else {
            $array_columnas[$columnName] = $cleanValue;
        }
    }
}


// movere esta funcion pero por face de prueba se queda de momento

function processNumericFilter($value) {
    //error_log("Valor original recibido: " . $value);

    $value = trim($value);
    $value = str_replace(['\-', '\\-'], '-', $value);
    
    //error_log("Valor después de limpieza: " . $value);
    
    // Rango modificado (incluye enteros y decimales)
    if (preg_match('/^(\d+\.?\d*)\s*-\s*(\d+\.?\d*)$/', $value, $matches)) {
        error_log("Rango detectado: " . $matches[1] . " a " . $matches[2]);
        return [
            'type' => 'range',
            'min' => (float)$matches[1],
            'max' => (float)$matches[2],
            'raw' => $value
        ];
    }
    // Mayor que
    else if (preg_match('/^>\s*(\d+\.?\d*)$/', $value, $matches)) {
        return [
            'type' => 'gt',
            'value' => (float)$matches[1],
            'raw' => $value
        ];
    }
    // Menor que
    else if (preg_match('/^<\s*(\d+\.?\d*)$/', $value, $matches)) {
        return [
            'type' => 'lt',
            'value' => (float)$matches[1],
            'raw' => $value
        ];
    }
    // Valor exacto
    else if (is_numeric($value)) {
        return [
            'type' => 'eq',
            'value' => (float)$value,
            'raw' => $value
        ];
    }
    // Búsqueda por texto
    //error_log("Valor no numérico fuera: ");
    //error_log($value);
    return [
        'type' => 'like',
        'value' => $value,
        'raw' => $value
    ];
}

// Consulta base
$sqlstr = "SELECT 
    a.rowid, 
    a.fk_usuario_crear, 
    a.fk_usuario_validar,
    a.fecha, 
    a.fecha_vencimiento, 
    a.referencia, 
    a.tipo, 
    a.detalle, 
    a.fk_tercero, 
    a.impuesto_iva, 
    a.subtotal_pre_retencion,
    a.total, 
    a.estado, 
    a.pagado, 
    a.estado_pagada, 
    CONCAT(u.nombre, ' ', u.apellidos) AS usuario_crear,
    d.etiqueta AS estado_txt, 
    d.class AS estado_class,
    CASE 
        WHEN t.tipo = 'fisica' THEN CONCAT(t.nombre, ' ', t.apellidos) 
        ELSE t.nombre  
    END AS cliente_tercero 
FROM fi_europa_albaranes_ventas a
LEFT JOIN fi_usuarios u ON a.fk_usuario_crear = u.rowid
LEFT JOIN fi_terceros t ON a.fk_tercero = t.rowid
LEFT JOIN ".DB_NAME_UTILIDADES_APOYO.".diccionario_albarenes_venta_europa_diccionario d ON a.estado = d.rowid
WHERE a.entidad = :entidad";

// Manejo de filtros por rango de fechas
$params = [':entidad' => [$_SESSION['Entidad'], PDO::PARAM_INT]];

// Manejo de filtros por rango de fechas
if (isset($array_columnas['fecha']) && $array_columnas['fecha'] != '') {
    $datafecha = explode('|', $array_columnas['fecha']);
    $fecha_inicio = date('Y-m-d 00:00:00', strtotime($datafecha[0]));
    $fecha_fin = date('Y-m-d 23:59:59', strtotime($datafecha[1]));
    $sqlstr .= " AND a.fecha BETWEEN :fecha_inicio AND :fecha_fin";
    $params[':fecha_inicio'] = [$fecha_inicio, PDO::PARAM_STR];
    $params[':fecha_fin'] = [$fecha_fin, PDO::PARAM_STR];
}

if (isset($array_columnas['fecha_vencimiento']) && $array_columnas['fecha_vencimiento'] != '') {
    $datafecha2 = explode('|', $array_columnas['fecha_vencimiento']);
    $fecha_inicio_venc = date('Y-m-d', strtotime($datafecha2[0]));
    $fecha_fin_venc = date('Y-m-d', strtotime($datafecha2[1]));
    $sqlstr .= " AND a.fecha_vencimiento BETWEEN :fecha_inicio_venc AND :fecha_fin_venc";
    $params[':fecha_inicio_venc'] = [$fecha_inicio_venc, PDO::PARAM_STR];
    $params[':fecha_fin_venc'] = [$fecha_fin_venc, PDO::PARAM_STR];
}

// Filtros para otras columnas
$otherFilters = [];
foreach ($array_columnas as $key => $value) {
    if ($key === 'fecha' || $key === 'fecha_vencimiento') continue;
    
    if (isset($mapaColumnas[$key])) {
        $nombreColumnaBD = $mapaColumnas[$key];
        
        // Manejo especial para campos numéricos
        if (in_array($key, ['total', 'impuesto_iva', 'subtotal_pre_retencion']) && is_array($value)) {
            switch ($value['type']) {
                case 'range':
                    $otherFilters[] = "$nombreColumnaBD BETWEEN :{$key}_min AND :{$key}_max";
                    $params[":{$key}_min"] = [$value['min'], PDO::PARAM_STR];
                    $params[":{$key}_max"] = [$value['max'], PDO::PARAM_STR];
                    break;
                case 'gt':
                    $otherFilters[] = "$nombreColumnaBD > :{$key}_gt";
                    $params[":{$key}_gt"] = [$value['value'], PDO::PARAM_STR];
                    break;
                case 'lt':
                    $otherFilters[] = "$nombreColumnaBD < :{$key}_lt";
                    $params[":{$key}_lt"] = [$value['value'], PDO::PARAM_STR];
                    break;
                case 'eq':
                    $otherFilters[] = "$nombreColumnaBD = :{$key}_eq";
                    $params[":{$key}_eq"] = [$value['value'], PDO::PARAM_STR];
                    break;
                case 'like':
                default:
                    $otherFilters[] = "$nombreColumnaBD LIKE :{$key}_like";
                    $params[":{$key}_like"] = ["%{$value['value']}%", PDO::PARAM_STR];
                    break;
            }
        } else {
            // Filtro normal para campos no numéricos
            $otherFilters[] = "$nombreColumnaBD LIKE :$key";
            $params[":$key"] = ["%$value%", PDO::PARAM_STR];
        }
    }
}

/*foreach ($array_columnas as $key => $value) {
    if ($key === 'fecha' || $key === 'fecha_vencimiento') continue;
    
    
    if (isset($mapaColumnas[$key])) {
        $nombreColumnaBD = $mapaColumnas[$key];
        $otherFilters[] = "$nombreColumnaBD LIKE :$key";
        $params[":$key"] = ["%$value%", PDO::PARAM_STR];
    }
}*/

if (!empty($otherFilters)) {
    $sqlstr .= " AND (" . implode(" AND ", $otherFilters) . ")";
}

// Conteo de registros totales (sin filtros)
$sqlstrFilter = "SELECT COUNT(*) FROM fi_europa_albaranes_ventas a WHERE a.entidad = :entidad";
$totalRecordsStmt = $dbh->prepare($sqlstrFilter);
$totalRecordsStmt->bindParam(':entidad', $_SESSION['Entidad'], PDO::PARAM_INT);
$totalRecordsStmt->execute();
$totalRecords = $totalRecordsStmt->fetchColumn();

// Conteo de registros filtrados (misma consulta pero sin LIMIT)
$sqlstrCountFiltered = "SELECT COUNT(*) FROM ($sqlstr) as subquery";
$countFilteredStmt = $dbh->prepare($sqlstrCountFiltered);

// Bind de todos los parámetros
foreach ($params as $key => $value) {
    $countFilteredStmt->bindValue($key, $value[0], $value[1]);
}

$countFilteredStmt->execute();
$totalFilteredRecords = $countFilteredStmt->fetchColumn();

// Consulta principal con LIMIT
$sqlstr .= " ORDER BY a.rowid DESC LIMIT :inicio, :limite";
$dataB = $dbh->prepare($sqlstr);

// Bind de todos los parámetros
foreach ($params as $key => $value) {
    $dataB->bindValue($key, $value[0], $value[1]);
}

$dataB->bindValue(':inicio', (int)$inicio, PDO::PARAM_INT);
$dataB->bindValue(':limite', (int)$limite, PDO::PARAM_INT);

$c = $dataB->execute();

if (!$c) {
    $errorSQL = implode(", ", $dataB->errorInfo()) . " " . implode(" , ", $dbh->errorInfo());
}

// Procesa los registros obtenidos
$Records = $dataB->fetchAll();
$data = array();
$array_avatar_repetir = array();

foreach ($Records as $row) {
    $tipo = $row['electronica_tipo'] == "tiquete" ? 'Simplificada' : 'Normal';
    $pagado = numero_simple($row['pagado']);

    if ($row['moneda'] == 1) { 
        $montoTotal = numero($row['total']);
    } else if ($row['moneda'] == 2) { 
        $montoTotal = numero_dolar($row['total']); 
    }

    if(isset($array_avatar_repetir[$row['fk_usuario_crear']])) {
        $avatar = $array_avatar_repetir[$row['fk_usuario_crear']];
    } else {
        $array_avatar_repetir[$row['fk_usuario_crear']] = $Usuarios->obtener_url_avatar_encriptada($row['fk_usuario_crear']);
        $avatar = $array_avatar_repetir[$row['fk_usuario_crear']];
    }

    $datos = explode("***", $row['factura_compra_unica'] );
    
    $data[] = array(
        "id" => $row['rowid'],
        "usuario_crear" => $row['fk_usuario_crear'],
        "usuario_validar" => $row['fk_usuario_validar'],
        "fecha" => date('d-m-Y', strtotime($row['fecha'])),
        "fecha_vencimiento" => $row['fecha_vencimiento'],
        "referencia" => $row['referencia'],
        "tipo" => $row['tipo'],
        "detalle" => $row['detalle'],
        "tercero" => $row['fk_tercero'],
        "total" => $montoTotal,
        "subtotal_pre_retencion" => $row['subtotal_pre_retencion'],
        "impuesto_iva" => $row['impuesto_iva'],
        "total" => $row['total'],
        "usuario_crear" => $row['usuario_crear'],
        "cliente_tercero" => $row['cliente_tercero'],
        "etiqueta" => $row['estado_txt'],
        "color" => $row['estado_class'],
        "avatar" => $avatar,
        "estado_class" => $row['estado_class'], 
        "estado_txt" => $row['estado_txt'],    
    );
}


// Respuesta JSON
$response = array(
    "draw" => intval($_GET['draw']),
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalFilteredRecords,
    'sql' => $sqlstr,
    "data" => $data,
    "archivo_existente" => isset($fileExists) ? $fileExists : false,
    "errorSQL" => isset($errorSQL) ? $errorSQL : null
);

echo json_encode($response);

?>