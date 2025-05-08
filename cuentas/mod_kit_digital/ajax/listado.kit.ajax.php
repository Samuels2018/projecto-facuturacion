<?php
session_start();
require_once "../../conf/conf.php";

$inicio = $_GET['start'];
$limite = $_GET['length'];
$buscarArray = $_GET['search'];
$buscar = $buscarArray['value'];
$columnas = $_GET['columns'];
$array_columnas = [];

$mapaColumnas = array(
    "ID" => "a.rowid",
    "Nombre Completo" => "a.nombre",
    "Tipo" => "a.tipo",
    "Cedula" => "a.cedula",
    "Telefono" => "a.telefono_fijo",
    "Población" => "m.municipio",
    "Aplica KitDigital" => "a.kit_aplica_kit_digital",
    "Estado KitDigital" => "a.fk_kit_digital_estado", // Cambiado a `etiqueta` de `diccionario_kit_digital_estado`
    "Monto" => "a.kit_monto_aprobado",
    "Cobrado" => "a.kit_monto_comision_pagada"
);

// Manejar el filtrado por columnas específicas
for ($i = 0; $i < count($columnas); $i++) {
    if ($columnas[$i]['search']['value'] != '') {
        if ($columnas[$i]['data'] === "Aplica KitDigital" || $columnas[$i]['data'] === "Estado KitDigital") {
            $array_columnas[$columnas[$i]['data']] = $columnas[$i]['search']['value'];
        } else {
            $array_columnas[$columnas[$i]['data']] = substr($columnas[$i]['search']['value'], 1, -1);
        }
    }
}

// Construcción de la consulta SQL
$sqlstr = "SELECT 
            a.rowid AS ID,
            CASE 
                WHEN a.tipo = 'juridica' THEN a.nombre
                ELSE CONCAT(a.nombre, ' ', COALESCE(a.nombre_comercial, ''))
            END AS `Nombre Completo`,
            COALESCE(a.tipo, 'Sin tipo') AS Tipo,
            COALESCE(a.cedula, 'Sin cédula') AS Cedula,
            COALESCE(a.telefono_fijo, 'Sin teléfono') AS `Teléfono`,
            COALESCE(m.municipio, 'Población desconocida') AS Población, 
            CASE WHEN a.kit_aplica_kit_digital = 1 THEN 'Sí' ELSE 'No' END AS `Aplica KitDigital`,
            COALESCE(e.etiqueta, 'Estado desconocido') AS `Estado KitDigital`,  -- Obtenemos la etiqueta del estado
            COALESCE(a.kit_monto_aprobado, 0) AS Monto,
            COALESCE(a.kit_monto_comision_pagada, 0) AS Cobrado
          FROM sistema_empresa a
          LEFT JOIN ".DB_NAME_UTILIDADES_APOYO.".diccionario_comunidades_autonomas_provincias_municipios m
          ON a.direccion_fk_municipio = m.id
          LEFT JOIN diccionario_kit_digital_estado e  -- Se une a la tabla de estados de Kit Digital
          ON a.fk_kit_digital_estado = e.rowid
          WHERE 1";

// Aplicación de los filtros basados en las columnas
if (!empty($array_columnas)) {
    $sqlstr .= " AND (";
    $first = true;

    foreach ($array_columnas as $key => $value) {
        $nombreColumnaBD = $mapaColumnas[$key];

        if (!$first) {
            $sqlstr .= " AND ";
        }

        if ($key === "Aplica KitDigital") {
            $estado = intval($value);
            $condicion = $nombreColumnaBD . " = '$estado'";
        } else {
            $condicion = $nombreColumnaBD . " LIKE '%" . $value . "%'";
        }

        $sqlstr .= $condicion;
        $first = false;
    }
    $sqlstr .= ")";
}

// Conteo de registros totales y filtrados
$sqlstrFilter = "SELECT COUNT(*) FROM sistema_empresa a 
    LEFT JOIN ".DB_NAME_UTILIDADES_APOYO.".diccionario_comunidades_autonomas_provincias_municipios m 
    ON a.direccion_fk_municipio = m.id 
    LEFT JOIN diccionario_kit_digital_estado e 
    ON a.fk_estado = e.rowid 
    WHERE 1";

$totalRecordsStmt = $dbh->query($sqlstrFilter);
$totalRecords = $totalRecordsStmt->fetchColumn();

$sqlFiltered = $sqlstr;
$resultsFilter = $dbh->query($sqlFiltered);
$totalFilteredRecords = $resultsFilter->rowCount();

$sqlstr .= " LIMIT $inicio, $limite";
$dataB = $dbh->prepare($sqlstr);
$dataB->execute();
$Records = $dataB->fetchAll();
$data = [];

foreach ($Records as $row) {
    $data[] = array(
        "ID" => $row['ID'],
        "Nombre Completo" => $row['Nombre Completo'],
        "Tipo" => $row['Tipo'],
        "Cedula" => $row['Cedula'],
        "Telefono" => $row['Teléfono'],
        "Poblacion" => $row['Población'],
        "Aplica KitDigital" => $row['Aplica KitDigital'],
        "Estado KitDigital" => $row['Estado KitDigital'],
        "Monto" => $row['Monto'],
        "Cobrado" => $row['Cobrado']
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
