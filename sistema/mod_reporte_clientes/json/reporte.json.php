<?php
session_start();
include '../../conf/conf.php';

if (!isset($_SESSION['usuario'])) {
    echo json_encode(['error' => 'Acceso no válido']);
    exit;
  }



if (isset($_POST['inicio']) and isset($_POST['fin'])) {
    $inicio = date('Y-m-d', strtotime($_POST['inicio']));
    $fin = date('Y-m-d', strtotime($_POST['fin']));
    $rango = "AND f.fecha >= '".$inicio."' AND f.fecha <= '".$fin."'   ";
}


if ($_POST['exportar'] == "1") {
    $limit=" limit 100";
}   

$tabla = $_REQUEST['tipo'];
$diccionario_estado = "";
switch ($tabla) {
	case 'fi_europa_albaranes_compras':
		break;
	case 'fi_europa_compras':
		break;
	case 'fi_europa_presupuestos':
		break;
	case 'fi_europa_facturas':
		break;
	case 'fi_europa_albaranes_ventas':
		break;
	case 'fi_europa_pedidos':
		break;
	default:
		$tabla = "";
        echo json_encode(['error' => 'Tabla No definida']);
        exit;
		break;
}
include ENLACE_SERVIDOR . 'mod_reporte/object/reporte.object.php';
$reporte = new Reporte($dbh, $_SESSION["Entidad"]);
 
// **Consulta SQL para exportar TODOS los registros**
  $sql = "SELECT 
            f.referencia, 
            IFNULL(f.fk_tercero_txt, 'Cliente Genérico') as fk_tercero_txt,
            IFNULL(f.fk_tercero_identificacion, ' -- ') as fk_tercero_identificacion,
            IFNULL(f.fk_tercero_telefono, ' -- ') as fk_tercero_telefono,
            IFNULL(f.fk_tercero_email, ' -- ') as fk_tercero_email,
            IFNULL(f.fk_tercero_direccion, ' -- ') as fk_tercero_direccion
        FROM ".$tabla." f
        WHERE f.entidad = :entidad  
        {$reporte->condicion_documentos[$tabla]}
        $rango     $limit ";
 
$db = $dbh->prepare($sql);
$db->bindValue(":entidad" , $_SESSION['Entidad'], PDO::PARAM_INT);
$db->execute();
$facturasExportar = $db->fetchAll(PDO::FETCH_OBJ);


// **Si el usuario quiere exportar a Excel**
if ($_POST['exportar'] == "1") {
    header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
    header("Pragma: no-cache");
    header("Expires: 0");

    $filename = "Informe_{$Utilidades->nombre_publico_tabla($tabla)}_{$_SESSION['Entidad']}.xls";
    header('Content-Disposition: attachment; filename='.$filename);
    echo "\xEF\xBB\xBF";
    echo pack("CCC", 0xef, 0xbb, 0xbf);
    ob_start();


    ?>
    <html xmlns:x="urn:schemas-microsoft-com:office:excel">
    <head>
    </head>
    <body>
        <center>
            <strong>Informe Clientes por Fecha <?php echo date('d-m-Y', strtotime($inicio)) ?> al <?php echo date('d-m-Y', strtotime($fin)) ?></strong>
            <br><br>

            <div id="tablaVentasContainer" style="width:100%; overflow-x:auto;">    
                <table border="1" width="90%" id="tablaVentas">
                    <tr style="background-color:#3c8dbc; color:white;">
                        <th>Factura</th>
                        <th>Cliente</th>
                        <th>Identificación</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Dirección</th>
                    </tr>

                    <?php
                    foreach ($facturasExportar as $registro) {
                        echo "<tr>
                            <td>".htmlspecialchars($registro->referencia, ENT_QUOTES, 'UTF-8')."</td>
                            <td>".htmlspecialchars($registro->fk_tercero_txt, ENT_QUOTES, 'UTF-8')."</td>
                            <td>".htmlspecialchars($registro->fk_tercero_identificacion, ENT_QUOTES, 'UTF-8')."</td>
                            <td>".htmlspecialchars($registro->fk_tercero_telefono, ENT_QUOTES, 'UTF-8')."</td>
                            <td>".htmlspecialchars($registro->fk_tercero_email, ENT_QUOTES, 'UTF-8')."</td>
                            <td>".htmlspecialchars($registro->fk_tercero_direccion, ENT_QUOTES, 'UTF-8')."</td>
                        </tr>";
                    }
                    ?>
                </table>
            </div>
        </center>
    </body>
    </html>

    <?php
    echo ob_get_clean();
    exit();
}

 
?>

<!-- **Tabla con Paginación en Pantalla** -->
<div id="tablaVentasContainer" style="width:100%; overflow-x:auto;">
    <table id="tablaVentas">
        <thead>
            <tr>
                <th>Factura</th>
                <th>Cliente</th>
                <th>Identificación</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th>Dirección</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($facturasExportar as $registro): ?>
                <tr>
                    <td><?= $registro->referencia ?></td>
                    <td><?= $registro->fk_tercero_txt ?></td>
                    <td><?= $registro->fk_tercero_identificacion ?></td>
                    <td><?= $registro->fk_tercero_telefono ?></td>
                    <td><?= $registro->fk_tercero_email ?></td>
                    <td><?= $registro->fk_tercero_direccion ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>