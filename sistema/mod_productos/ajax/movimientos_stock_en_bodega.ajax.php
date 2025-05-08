<?php

session_start();
require_once "../../conf/conf.php";

$inicio         = $_GET['start'];
$limite         = $_GET['length'];
$buscarArray    = $_GET['search'];
$buscar         = $buscarArray['value'];
$columnas       = $_GET['columns'];
$array_columnas = [];


$mapaColumnas = array(
    "ID" => "m.rowid",
    "Producto" => "m.fk_producto",
    "Tipo" => "m.tipo",
    "Valor" => "m.valor",
    "stock_actual" => "m.stock_actual",
    "Motivo" => "m.motivo",
    "documento_tipo"=>"m.documento_tipo",
    "documento_fk"=>"(CASE 
            WHEN m.documento_tipo = 'factura' THEN 
                (SELECT referencia 
                FROM fi_europa_facturas 
                WHERE rowid = m.documento_fk)
            WHEN m.documento_tipo = 'compra' THEN 
                (SELECT referencia 
                FROM fi_europa_compras 
                WHERE rowid = m.documento_fk)
            WHEN m.documento_tipo = 'venta_albaran' THEN 
                (SELECT referencia 
                FROM fi_europa_albaranes_ventas
                WHERE rowid = m.documento_fk)
            WHEN m.documento_tipo = 'albaran_compra' THEN 
                (SELECT referencia 
                FROM fi_europa_albaranes_compras 
                WHERE rowid = m.documento_fk)
            ELSE NULL
        END)",
    "Usuario" => " (select concat(nombre,' ', apellidos)  from fi_usuarios where rowid =  m.usuario  )",
    "Creado_Fecha" => "m.creado_fecha",
    "Creado_FK_Usuario" => "m.creado_fk_usuario",
    "Borrado" => "m.borrado",
    "Borrado_Fecha" => "m.borrado_fecha",
    "Borrado_FK_Usuario" => "m.borrado_fk_usuario"
);

for ($i = 0; $i < count($columnas); $i++) {
    if ($columnas[$i]['search']['value'] != '') {
        if($columnas[$i]['data'] != 'fecha' ){
            $array_columnas[$columnas[$i]['data']] = substr($columnas[$i]['search']['value'], 1, -1);
        }else{
            $array_columnas[$columnas[$i]['data']] = $columnas[$i]['search']['value'];
        }
    }
}

$id_producto    = $_GET['id_producto'];
$fk_bodega      = $_GET['fk_bodega'];


$sqlstr = "SELECT 
            m.rowid , 
            m.fk_bodega , 
            m.fk_producto   , 
            m.tipo  , 
            m.valor, m.stock_actual, m.motivo, m.fecha, m.usuario, m.creado_fecha, m.documento_tipo , m.documento_fk
            ,  p.label AS producto_label ,

        (select concat(nombre,' ', apellidos)  from fi_usuarios where rowid =  m.usuario  ) as usuario_txt  ,


        CASE 
            WHEN m.documento_tipo = 'factura' THEN 
                (SELECT referencia 
                FROM fi_europa_facturas 
                WHERE rowid = m.documento_fk)
            WHEN m.documento_tipo = 'compra' THEN 
                (SELECT referencia 
                FROM fi_europa_compras 
                WHERE rowid = m.documento_fk)
            WHEN m.documento_tipo = 'venta_albaran' THEN 
                (SELECT referencia 
                FROM fi_europa_albaranes_ventas
                WHERE rowid = m.documento_fk)
            WHEN m.documento_tipo = 'albaran_compra' THEN 
                (SELECT referencia 
                FROM fi_europa_albaranes_compras 
                WHERE rowid = m.documento_fk)
            ELSE NULL
        END AS detalle_documento



        FROM fi_bodegas_movimientos m
        LEFT JOIN fi_bodegas    b ON m.fk_bodega    = b.rowid
        LEFT JOIN fi_productos  p ON m.fk_producto  = p.rowid
        WHERE m.borrado = 0 AND m.fk_producto = $id_producto and m.fk_bodega = $fk_bodega  
";

if (isset($array_columnas['fecha']) && $array_columnas['fecha'] != '') {
    // Separar el rango de fechas en fecha_inicio y fecha_fin
    $datafecha = explode('|', $array_columnas['fecha']);
    // Asegurarse de que ambas fechas estén en el formato correcto
    $fecha_inicio = date('Y-m-d 00:00:00', strtotime($datafecha[0]));
    $fecha_fin = date('Y-m-d  23:59:59', strtotime($datafecha[1]));

    // Agregar la cláusula WHERE para el rango de fechas
    $sqlstr .= " AND m.fecha BETWEEN '$fecha_inicio' AND '$fecha_fin' ";
}

if (!empty($array_columnas)) {

    $distinto_fecha = 0;
    foreach ($array_columnas as $key => $value) {
        if($key !='fecha')
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
            if (isset($mapaColumnas[$key])) {
                $nombreColumnaBD = $mapaColumnas[$key];
                $condicion = $nombreColumnaBD . " LIKE '%" . $value . "%'";
                if (!$first) {
                    $sqlstr .= " AND ";
                }
                $sqlstr .= $condicion;
                $first = false;
            }
        }
        $sqlstr .= ")";
    }
}

$sqlstrFilter = str_replace("LIMIT 0, $limite", "", $sqlstr);
$sqlstrFilter = str_replace("OFFSET $inicio", "", $sqlstrFilter);
$resultsFilter = $dbh->query($sqlstrFilter);
$totalFilteredRecords = $resultsFilter->rowCount();

$totalRecordsStmt = $dbh->prepare($sqlstrFilter);
$totalRecordsStmt->execute();
$totalRecords = $totalRecordsStmt->fetchColumn();

// Ejecuta la consulta con los límites
$sqlstr .= " ORDER BY m.rowid DESC LIMIT $inicio, $limite";

$dataB = $dbh->prepare($sqlstr);
$dataB->execute();
$Records = $dataB->fetchAll(PDO::FETCH_ASSOC);
$data = array();


// $totalRecords = count($dbh->query($sqlstr)->fetchAll());

// $sqlstrFilter = str_replace("LIMIT 0, $limite", "", $sqlstr);
// $sqlstrFilter = str_replace("OFFSET $inicio", "", $sqlstrFilter);
// $resultsFilter = $dbh->query($sqlstrFilter);
// $totalFilteredRecords = $resultsFilter->rowCount();

// $sqlstr .= " ORDER BY m.rowid DESC LIMIT $inicio, $limite";

// $dataB = $dbh->prepare($sqlstr);
// $dataB->bindParam(':entidad', $_SESSION['Entidad'], PDO::PARAM_INT);
// $dataB->bindParam(':inicio', $inicio, PDO::PARAM_INT);
// $dataB->bindParam(':limite', $limite, PDO::PARAM_INT);
// foreach ($array_columnas as $key => $value) {
//     $dataB->bindValue(":$key", "%$value%", PDO::PARAM_STR);
// }
// $dataB->execute();
// $Records = $dataB->fetchAll();
// $data = array();

foreach ($Records as $row) {
    //$montoSubtotal = numero_simple($row['subtotal']);
    //$tipo = $row['tipo']== 0 ? 'Normal' : 'Simplificada';

    $data[] = array(
        "ID" => $row['rowid'],
        "Producto" => $row['producto_label'],
        "Tipo" => ($row['tipo'] == 0) ? "Aumentar" : "Disminuir",
        "Valor" => $row['valor'],
        "stock_actual" => $row['stock_actual'],
        "Motivo" => $row['motivo'],
        "fecha" => date('d-m-Y', strtotime($row['fecha'])),
        "Hora" => date('H:i', strtotime($row['fecha'])),
        "Usuario" => $row['usuario_txt'],
        "Creado Fecha" => $row['creado_fecha'],
        "documento_fk" => $row['documento_fk'],
        "documento_tipo" => $row['documento_tipo'],
        "detalle_documento"  => $row['detalle_documento']
    );
}

$response = array(
    "draw" => intval($_GET['draw']),
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalFilteredRecords,
    "data" => $data,
    "sql" => $sqlstr
);

echo json_encode($response);
