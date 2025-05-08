
<?php
/* Lista de Productos*/
/*----------------------------------------------------*/
SESSION_START();

setlocale(LC_MONETARY, 'es_ES.UTF-8');

require_once "../../conf/conf.php";

$inicio =  $_GET['start'];
$limite =  $_GET['length'];
$buscarArray =  $_GET['search'];
$buscar =  $buscarArray['value'];
$columnas = $_GET['columns']; // Obtiene las columnas para filtrar
$array_columnas = [];

$mapaColumnas = array(
    "ID" => "a.rowid",
    "ref" => "a.ref",
    "nombre" => "a.label",
    "stock" => "a.stock",
    "ventas" => "a.tosell",
    "compras" => "a.tobuy",
    "subtotal" => "( select subtotal from fi_productos_precios_clientes pcc where pcc.fk_producto = a.rowid order by pcc.rowid DESC limit 1  )",
    "impuesto" => "(select impuesto from fi_productos_precios_clientes pcc where pcc.fk_producto = a.rowid order by pcc.rowid DESC limit 1  )",
    "total" => "(select total from fi_productos_precios_clientes pcc where pcc.fk_producto = a.rowid order by pcc.rowid DESC limit 1  )"
);

for ($i = 0; $i < count($columnas); $i++) {
    if ($columnas[$i]['search']['value'] != '') {
        $array_columnas[$columnas[$i]['data']] = substr($columnas[$i]['search']['value'], 1, -1) ;
    }
}

$sqlstr = "
    	SELECT 
		a.rowid 
		,   a.ref   
		,   a.label as nombre
		,   a.stock
		,   a.tosell as ventas
		,   a.tobuy as compras
		,   (select subtotal from fi_productos_precios_clientes pcc where pcc.fk_producto = a.rowid order by pcc.rowid DESC limit 1  ) subtotal 
		,   (select impuesto from fi_productos_precios_clientes pcc where pcc.fk_producto = a.rowid order by pcc.rowid DESC limit 1  ) impuesto 
		,   (select total from fi_productos_precios_clientes pcc where pcc.fk_producto = a.rowid order by pcc.rowid DESC limit 1  ) total
	FROM 	fi_productos a
    	WHERE 
        a.borrado = 0 and  a.entidad = ".($_SESSION['Entidad'])." ";

	
// Verifica si hay criterios de búsqueda específicos para las columnas
if (!empty($array_columnas)) {
    $sqlstr .= " AND (";
    $first = true;
    foreach ($array_columnas as $key => $value) {
        // Usa el mapa de columnas para obtener el nombre de la columna en la base de datos
        $nombreColumnaBD = $mapaColumnas[$key];	   
        // Se asegura de que el valor sea un número y se convierte a formato decimal
        $valorDecimal = number_format((float)$value, 2, '.', '');
        
        if ($key == 'subtotal' || $key == 'total') {
            $condicion = $nombreColumnaBD . " >= " . $valorDecimal;
        } else {
            $condicion = $nombreColumnaBD . " LIKE '%" . $value . "%'";
        }

        if (!$first) {
            $sqlstr .= " AND "; // Cambiado de "OR" a "AND"
        }
        $sqlstr .= $condicion;
        $first = false;
    }
    $sqlstr .= ")";
}


// Get the total number of records

$totalRecordsStmt = $dbh->prepare($sqlstr);
$totalRecordsStmt->execute();
$totalRecords = count($totalRecordsStmt->fetchAll());

// Add the `LIMIT` clause for the paged query
$sqlstr .= " ORDER BY a.rowid LIMIT :inicio , :limite ";

$dataB = $dbh->prepare($sqlstr);
$dataB->bindParam(':inicio', $inicio, PDO::PARAM_INT);
$dataB->bindParam(':limite', $limite, PDO::PARAM_INT);
$dataB->execute();

$Records = $dataB->fetchAll();
$data = array();

 

 foreach ($Records as $row) {
 

 
    $data[] = array(
		"ref" => $row['ref'],
		"nombre" => returnSplitNameClient($row['nombre'],70),
		"stock" => $row['stock'],
		"ventas" => $row['ventas'],
		"compras" =>  $row['compras'],
		"cabys" => $row['cabys'],
		"imp" => $row['imp'],
		"venta" => $row['venta'],
		"rowid" => $row['rowid'],
		"impuesto" => number_format($row['impuesto'], 2, '.', ''),
		"subtotal" => number_format($row['subtotal'], 2, '.', ''),
		"total" => number_format($row['total'], 2, '.', ''),
		"sincronizado" => $sincronizado,
		"title" => $row['nombre'],
    );
       
}

$response = array(
    "draw" => intval($_GET['draw']),
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalRecords,
    "data" => $data , 
	"sql" => $sqlstr
);

echo json_encode($response);



return;