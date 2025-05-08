<?php
session_start();
require_once "../../conf/conf.php";

//si no hay usuario autenticado, cerrar conexion
if (!isset($_SESSION['usuario'])) {
    echo acceso_invalido();
    exit(1);
}

require_once(ENLACE_SERVIDOR . "mod_europa_presupuestos/object/presupuestos.object.php");

//Modulo usuarios global
require_once ENLACE_SERVIDOR . "mod_usuarios/object/usuarios.object.php";
$Usuarios = new usuario($dbh);
$entidad = $_SESSION['Entidad'];
// Instancia la clase de factura
$Factura = new Presupuesto($dbh, $entidad);


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
    "moneda" => "a.moneda",
    "moneda_tipo_cambio" => "a.moneda_tipo_cambio",
    "usuario_crear" => "CONCAT(u.nombre, ' ', u.apellidos)",
    "usuario_validar" => "CONCAT(t.nombre, ' ', t.apellidos)",
    "fecha" => "a.fecha",
    "fecha_vencimiento" => "a.fecha_vencimiento",
    "referencia" => "a.referencia",
    "tipo" => "a.tipo",
    "forma_pago" => "a.forma_pago",
    "detalle" => "a.detalle",
    "tercero" => "a.fk_tercero",
    "total" => "CAST(a.total AS DECIMAL(10,2))",
    "subtotal_pre_retencion" => "CAST(a.subtotal_pre_retencion AS DECIMAL(10,2))",
    "impuesto_iva" => "CAST(a.impuesto_iva AS DECIMAL(10,2))",
    "estado" => "a.estado",
    "estado_final" => "d.rowid",
    "fecha_creacion_server" => "a.fecha_creacion_server",
    "envio_correo_presupuesto" => "a.envio_correo_presupuesto",
    "envio_correo_presupuesto_fecha" => "a.envio_correo_presupuesto_fecha",
    "envio_correo_presupuesto_correo" => "a.envio_correo_presupuesto_correo",
    "cliente_tercero" => "CONCAT(t.nombre,t.apellidos)",
    "estado_hacienda" => "d.etiqueta",
    "color" => "d.class"
);


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
//WHERE CAST(a.total AS DECIMAL(10,2)) BETWEEN 9 AND 46

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
    a.entidad, 
    a.moneda, 
    a.moneda_tipo_cambio, 
    a.fk_usuario_crear, 
    a.fk_usuario_validar,
    a.fecha, 
    a.fecha_vencimiento, 
    a.referencia, 
    a.tipo, 
    a.forma_pago,
    a.detalle, 
    a.fk_tercero, 
    a.impuesto_iva,
    a.subtotal_pre_retencion,
    a.total,   
    a.estado, 
    a.pagado, 
    a.estado_pagada, 
    a.fecha_creacion_server, 
    a.envio_correo_presupuesto, 
    a.envio_correo_presupuesto_fecha,
    a.envio_correo_presupuesto_correo,  
    CONCAT(u.nombre, ' ', u.apellidos) AS usuario_crear,
    d.etiqueta AS estado_final ,  /* Aquí calculamos estado_final */
    CASE 
        WHEN t.tipo = 'fisica' THEN CONCAT(t.nombre, ' ', t.apellidos) 
        ELSE  t.nombre  END AS cliente_tercero,

        d.class
FROM fi_europa_presupuestos a
LEFT JOIN fi_usuarios u ON a.fk_usuario_crear = u.rowid
LEFT JOIN fi_terceros t ON a.fk_tercero = t.rowid
LEFT JOIN " . DB_NAME_UTILIDADES_APOYO . ".diccionario_presupuesto_europa_diccionario d ON a.estado = d.rowid
WHERE a.entidad = :entidad";


if (isset($array_columnas['fecha']) && $array_columnas['fecha'] != '') {
    $datafecha = explode('|', $array_columnas['fecha']);
    $fecha_inicio = date('Y-m-d 00:00:00', strtotime($datafecha[0]));
    $fecha_fin = date('Y-m-d 23:59:59', strtotime($datafecha[1]));
    $sqlstr .= " AND a.fecha BETWEEN :fecha_inicio AND :fecha_fin";
}

// Verifica si hay otros criterios de búsqueda
$otherFilters = false;
foreach ($array_columnas as $key => $value) {
    if ($key !== 'fecha' && isset($mapaColumnas[$key])) {
        $otherFilters = true;
        break;
    }
}

if ($otherFilters) {
    $sqlstr .= " AND (";
    $first = true;
    foreach ($array_columnas as $key => $value) {
        if ($key === 'fecha') continue;
        
        if (isset($mapaColumnas[$key])) {
            $nombreColumnaBD = $mapaColumnas[$key];
            
            if (!$first) {
                $sqlstr .= " AND ";
            }
            
            if (in_array($key, ['total', 'impuesto_iva', 'subtotal_pre_retencion']) && is_array($value)) {
                switch ($value['type']) {
                    case 'range':
                        $sqlstr .= "$nombreColumnaBD BETWEEN :{$key}_min AND :{$key}_max";
                        break;
                    case 'gt':
                        $sqlstr .= "$nombreColumnaBD > :{$key}_gt";
                        break;
                    case 'lt':
                        $sqlstr .= "$nombreColumnaBD < :{$key}_lt";
                        break;
                    case 'eq':
                        $sqlstr .= "$nombreColumnaBD = :{$key}_eq";
                        break;
                    case 'like':
                    default:
                        $sqlstr .= "$nombreColumnaBD LIKE :{$key}_like";
                        break;
                }
            } else {
                $sqlstr .= "$nombreColumnaBD LIKE :{$key}";
            }
            
            $first = false;
        }
    }
    $sqlstr .= ")";
}

// Conteo de registros totales (sin filtros)
$sqlstrTotal = "SELECT COUNT(*) FROM fi_europa_presupuestos a WHERE a.entidad = :entidad";
$totalRecordsStmt = $dbh->prepare($sqlstrTotal);
$totalRecordsStmt->bindParam(':entidad', $_SESSION['Entidad'], PDO::PARAM_INT);
$totalRecordsStmt->execute();
$totalRecords = $totalRecordsStmt->fetchColumn();

// Consulta COUNT filtrada optimizada
$sqlstrCountFiltered = "SELECT COUNT(DISTINCT a.rowid) as total 
    FROM fi_europa_presupuestos a
    LEFT JOIN fi_usuarios u ON a.fk_usuario_crear = u.rowid
    LEFT JOIN fi_terceros t ON a.fk_tercero = t.rowid
    LEFT JOIN ".DB_NAME_UTILIDADES_APOYO.".diccionario_presupuesto_europa_diccionario d ON a.estado = d.rowid
    WHERE a.entidad = :entidad";

if (isset($array_columnas['fecha']) && $array_columnas['fecha'] != '') {
    $sqlstrCountFiltered .= " AND a.fecha BETWEEN :fecha_inicio AND :fecha_fin";
}

if ($otherFilters) {
    $sqlstrCountFiltered .= " AND (";
    $first = true;
    foreach ($array_columnas as $key => $value) {
        if ($key === 'fecha') continue;
        
        if (isset($mapaColumnas[$key])) {
            $nombreColumnaBD = $mapaColumnas[$key];
            
            if (!$first) {
                $sqlstrCountFiltered .= " AND ";
            }
            
            if (in_array($key, ['total', 'impuesto_iva', 'subtotal_pre_retencion']) && is_array($value)) {
                switch ($value['type']) {
                    case 'range':
                        $sqlstrCountFiltered .= "$nombreColumnaBD BETWEEN :{$key}_min AND :{$key}_max";
                        break;
                    case 'gt':
                        $sqlstrCountFiltered .= "$nombreColumnaBD > :{$key}_gt";
                        break;
                    case 'lt':
                        $sqlstrCountFiltered .= "$nombreColumnaBD < :{$key}_lt";
                        break;
                    case 'eq':
                        $sqlstrCountFiltered .= "$nombreColumnaBD = :{$key}_eq";
                        break;
                    case 'like':
                    default:
                        $sqlstrCountFiltered .= "$nombreColumnaBD LIKE :{$key}_like";
                        break;
                }
            } else {
                $sqlstrCountFiltered .= "$nombreColumnaBD LIKE :{$key}";
            }
            
            $first = false;
        }
    }
    $sqlstrCountFiltered .= ")";
}

$countFilteredStmt = $dbh->prepare($sqlstrCountFiltered);
$countFilteredStmt->bindParam(':entidad', $_SESSION['Entidad'], PDO::PARAM_INT);

if (isset($fecha_inicio) && isset($fecha_fin)) {
    $countFilteredStmt->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
    $countFilteredStmt->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
}

foreach ($array_columnas as $key => $value) {
    if ($key === 'fecha') continue;
    if (isset($mapaColumnas[$key])) {
        if (in_array($key, ['total', 'impuesto_iva', 'subtotal_pre_retencion']) && is_array($value)) {
            switch ($value['type']) {
                case 'range':
                    $countFilteredStmt->bindValue(":{$key}_min", $value['min'], PDO::PARAM_STR);
                    $countFilteredStmt->bindValue(":{$key}_max", $value['max'], PDO::PARAM_STR);
                    break;
                case 'gt':
                    $countFilteredStmt->bindValue(":{$key}_gt", $value['value'], PDO::PARAM_STR);
                    break;
                case 'lt':
                    $countFilteredStmt->bindValue(":{$key}_lt", $value['value'], PDO::PARAM_STR);
                    break;
                case 'eq':
                    $countFilteredStmt->bindValue(":{$key}_eq", $value['value'], PDO::PARAM_STR);
                    break;
                case 'like':
                default:
                    $countFilteredStmt->bindValue(":{$key}_like", "%{$value['value']}%", PDO::PARAM_STR);
                    break;
            }
        } else {
            $countFilteredStmt->bindValue(":{$key}", "%{$value}%", PDO::PARAM_STR);
        }
    }
}

$countFilteredStmt->execute();
$totalFilteredRecords = $countFilteredStmt->fetchColumn();

// Ejecuta la consulta con los límites
$sqlstr .= " ORDER BY a.rowid DESC LIMIT :inicio, :limite";

$dataB = $dbh->prepare($sqlstr);
$dataB->bindParam(':entidad', $_SESSION['Entidad'], PDO::PARAM_INT);

if (isset($fecha_inicio) && isset($fecha_fin)) {
    $dataB->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
    $dataB->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
}

foreach ($array_columnas as $key => $value) {
    if ($key === 'fecha') continue;
    if (isset($mapaColumnas[$key])) {
        if (in_array($key, ['total', 'impuesto_iva', 'subtotal_pre_retencion']) && is_array($value)) {
            switch ($value['type']) {
                case 'range':
                    $dataB->bindValue(":{$key}_min", $value['min'], PDO::PARAM_STR);
                    $dataB->bindValue(":{$key}_max", $value['max'], PDO::PARAM_STR);
                    break;
                case 'gt':
                    $dataB->bindValue(":{$key}_gt", $value['value'], PDO::PARAM_STR);
                    break;
                case 'lt':
                    $dataB->bindValue(":{$key}_lt", $value['value'], PDO::PARAM_STR);
                    break;
                case 'eq':
                    $dataB->bindValue(":{$key}_eq", $value['value'], PDO::PARAM_STR);
                    break;
                case 'like':
                default:
                    $dataB->bindValue(":{$key}_like", "%{$value['value']}%", PDO::PARAM_STR);
                    break;
            }
        } else {
            $dataB->bindValue(":{$key}", "%{$value}%", PDO::PARAM_STR);
        }
    }
}


$dataB->bindParam(':inicio', $inicio, PDO::PARAM_INT);
$dataB->bindParam(':limite', $limite, PDO::PARAM_INT);
$dataB->execute();

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

    $fecha_formateada = date('Y-m-d', strtotime($row['fecha']));

    $data[] = array(
        "id" => $row['rowid'],
        "entidad" => $row['entidad'],
        "moneda" => $row['moneda'],
        "moneda_tipo_cambio" => $row['moneda_tipo_cambio'],
        "usuario_crear" => $row['fk_usuario_crear'],
        "usuario_validar" => $row['fk_usuario_validar'],
        "fecha" => $fecha_formateada,
        "fecha_vencimiento" => $row['fecha_vencimiento'],
        "referencia" => $row['referencia'],
        "tipo" => $row['tipo'],
        "forma_pago" => $row['forma_pago'],
        "detalle" => $row['detalle'],
        "tercero" => $row['fk_tercero'],
        "total" => $montoTotal,
        "subtotal_pre_retencion" => $row['subtotal_pre_retencion'],
        "impuesto_iva" => $row['impuesto_iva'],
        "estado" => $row['estado'],
        "fecha_creacion_server" => $row['fecha_creacion_server'],
        "envio_correo_presupuesto" => $row['envio_correo_presupuesto'],
        "envio_correo_presupuesto_fecha" => $row['envio_correo_presupuesto_fecha'],
        "envio_correo_presupuesto_correo" => $row['envio_correo_presupuesto_correo'],
        "total" => $row['total'],
        "usuario_crear" => $row['usuario_crear'],
        "cliente_tercero" => $row['cliente_tercero'],
        "etiqueta" => $row['estado_final'],
        "color" => $row['class'],
        "avatar" => $avatar,
        "estado_final" => $row['estado_final']
    );
}
            

// Respuesta JSON
$response = array(
    "draw" => intval($_GET['draw']),
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalFilteredRecords,
    'sql' => $sqlstr,
    "data" => $data,
    "archivo_existente" => $fileExists ? true : false, // Agregar estatus para la vista
    "xmlexists" => $xmlexists // Agrega esta variable al objet

);

echo json_encode($response);
