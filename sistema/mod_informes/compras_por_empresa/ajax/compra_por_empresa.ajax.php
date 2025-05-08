<?php
/* Lista de Compras Proveedor */
/*----------------------------------------------------*/
session_start();

require_once "../../../conf/conf.php";

$inicio = $_GET['start'];
$limite = $_GET['length'];
$buscarArray = $_GET['search'];
$buscar = $buscarArray['value'];

if ($buscar == '') {
    $totalRecords = 0;
    $sqlstr = "SELECT COUNT(*) as TotalReg FROM fi_compras WHERE fk_tercero = :fk_tercero AND entidad = :entidad";
    $dataB = $dbh->prepare($sqlstr);
    $dataB->bindValue(':fk_tercero', $_GET['id'], PDO::PARAM_INT);
    $dataB->bindValue(':entidad', $_SESSION['Entidad'], PDO::PARAM_INT);
    $dataB->execute();
    $Records = $dataB->fetchAll();
   
    foreach ($Records as $row) {
        $totalRecords = $row['TotalReg'];
    }

    $sqlstr = "SELECT
     c.rowid
    ,c.referencia
    ,c.referencia_proveedor
    ,c.fecha
    ,c.total
    ,c.pagado
    ,c.estado 
     FROM fi_compras c WHERE fk_tercero = :fk_tercero AND entidad = :entidad ORDER BY rowid DESC LIMIT :inicio, :limite";
    $dataB = $dbh->prepare($sqlstr);
    $dataB->bindValue(':fk_tercero', $_GET['id'], PDO::PARAM_INT);
    $dataB->bindValue(':entidad', $_SESSION['Entidad'], PDO::PARAM_INT);
    $dataB->bindValue(':inicio', $inicio, PDO::PARAM_INT);
    $dataB->bindValue(':limite', $limite, PDO::PARAM_INT);
   
    $dataB->execute();
    $Records = $dataB->fetchAll();
  
    $data = array();
    foreach ($Records as $row) {
       
        $data[] = array(
            "NumeroCompra" => ['rowid' =>$row['rowid'], 'referencia' => $row['referencia']],
            "Factura" => $row['referencia_proveedor'],
            "Fecha" => date('d-m-Y', strtotime($row['fecha'])),
            "Monto" => numero($row['total']),
            "Pagado" => numero($row['pagado']),
            "Estado" => $row['estado']
        );
    }
   
    $response = array(
        "draw" => intval($_GET['draw']),
        "recordsTotal" => intval($totalRecords),
        "recordsFiltered" => intval($totalRecords),
        "data" => $data
    );
} else {
    // Aquí puedes implementar la lógica para buscar en base al valor de $buscar si es necesario.
    // El código original no incluye una búsqueda dinámica, por lo que este bloque es opcional.
    $totalRecords = 0; 
    $sqlstr2 = "SELECT
    c.rowid
   ,c.referencia
   ,c.referencia_proveedor
   ,c.fecha
   ,c.total
   ,c.pagado
   ,c.estado 
    FROM fi_compras c";


$sqlstr2 .= " WHERE fk_tercero = :fk_tercero AND entidad = :entidad
		AND  (
            c.rowid LIKE '%".$buscar."%'
            OR c.referencia LIKE '%".$buscar."%'
            OR c.referencia_proveedor LIKE '%".$buscar."%'
            OR c.fecha LIKE '%".$buscar."%'
            OR c.total LIKE '%".$buscar."%'
            OR c.pagado LIKE '%".$buscar."%'
        )";
;
    $sqlstr3 = $sqlstr2 ." order by c.rowid desc  limit $inicio,$limite";


    $dataB = $dbh->prepare($sqlstr3);
    $dataB->bindValue(':fk_tercero', $_GET['id'], PDO::PARAM_INT);
    $dataB->bindValue(':entidad', $_SESSION['Entidad'], PDO::PARAM_INT);
	$dataB->execute();
	$Records= $dataB->fetchAll();
	$data = array();
	foreach($Records as $row){
        $data[] = array(
            "NumeroCompra" => ['rowid' =>$row['rowid'], 'referencia' => $row['referencia']],
            "Factura" => $row['referencia_proveedor'],
            "Fecha" => date('d-m-Y', strtotime($row['fecha'])),
            "Monto" => numero($row['total']),
            "Pagado" => numero($row['pagado']),
            "Estado" => $row['estado']
        );
	           $totalRecords = $totalRecords + 1;
	}
	$response = array(
	  "draw"            =>  intval( $_GET['draw'] ),  
      "recordsTotal"    => intval($totalRecords),  
      "recordsFiltered" => intval($totalRecords),
      "data" => $data
    );
}

echo json_encode($response);
?>
