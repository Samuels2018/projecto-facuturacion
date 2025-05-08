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
        $diccionario_estado = "LEFT JOIN ".DB_NAME_UTILIDADES_APOYO.".diccionario_albarenes_compra_europa_diccionario d ON f.estado = d.rowid";
		break;
	case 'fi_europa_compras':
        $diccionario_estado = "LEFT JOIN ".DB_NAME_UTILIDADES_APOYO.".diccionario_compra_europa_diccionario d ON f.estado = d.rowid";
		break;
	case 'fi_europa_presupuestos':
        $diccionario_estado = "LEFT JOIN ".DB_NAME_UTILIDADES_APOYO.".diccionario_presupuesto_europa_diccionario d ON f.estado = d.rowid";
		break;
	case 'fi_europa_facturas':
        $diccionario_estado = "LEFT JOIN ".DB_NAME_UTILIDADES_APOYO.".diccionario_factura_europa_diccionario d ON f.estado = d.rowid";
		break;
	case 'fi_europa_albaranes_ventas':
        $diccionario_estado = "LEFT JOIN ".DB_NAME_UTILIDADES_APOYO.".diccionario_albarenes_venta_europa_diccionario d ON f.estado = d.rowid";
		break;
	case 'fi_europa_pedidos':
        $diccionario_estado = "LEFT JOIN ".DB_NAME_UTILIDADES_APOYO.".diccionario_pedidos_europa_diccionario d ON f.estado = d.rowid";
		break;
	default:
		$tabla = "";
        echo json_encode(['error' => 'Tabla No definida']);
        exit;
		break;
}
include ENLACE_SERVIDOR . 'mod_reporte/object/reporte.object.php';
$reporte = new Reporte($dbh, $_SESSION["Entidad"]);

$mostrarInfoUsuario = isset($_REQUEST['info_usuario']) && $_REQUEST['info_usuario'] == 1;
 
// **Consulta SQL para exportar TODOS los registros**
  $sql = "SELECT 
            f.referencia, 
            IFNULL(f.fk_tercero_txt, 'Cliente Genérico') as fk_tercero_txt,
            IFNULL(f.fk_tercero_identificacion, ' -- ') as fk_tercero_identificacion,
            IFNULL(f.fk_tercero_telefono, ' -- ') as fk_tercero_telefono,
            IFNULL(f.fk_tercero_email, ' -- ') as fk_tercero_email,
            IFNULL(f.fk_tercero_direccion, ' -- ') as fk_tercero_direccion,
            f.fecha, f.fecha_vencimiento, f.referencia_serie, f.tipo, f.forma_pago_txt, 
            f.estado_hacienda, f.estado_pagada, f.creado_fecha, f.asesor_comercial_txt, 
            f.entidad_razonsocial, f.entidad_fantasia, f.entidad_identificacion, 
            f.entidad_email, f.entidad_direccion, f.entidad_telefonofijo, f.agente_txt,
            f.subtotal_pre_retencion, f.impuesto_iva, f.impuesto_iva_equivalencia, 
            f.impuesto_retencion_irpf, f.total, f.IVA_0, f.IVA_10, f.IVA_4, f.IVA_21,
            f.RE_5_2, f.RE_1_4, f.RE_0_5, f.RE_0_75, f.estado, d.etiqueta as estado_etiqueta
        FROM ".$tabla." f
        {$diccionario_estado}
        WHERE f.entidad = :entidad  
        {$reporte->condicion_documentos[$tabla]}
        $rango     $limit ";
 
$db = $dbh->prepare($sql);
$db->bindValue(":entidad" , $_SESSION['Entidad'], PDO::PARAM_INT);
$db->execute();
$facturasExportar = $db->fetchAll(PDO::FETCH_OBJ);


 ?>
 <style>
        table td:nth-child(<?php echo $mostrarInfoUsuario?3:0 ?>),
        table th:nth-child(<?php echo $mostrarInfoUsuario?3:0 ?>) {
            background-color: #f0f0f0;
        }
        table td:nth-child(<?php echo $mostrarInfoUsuario?4:0 ?>),
        table th:nth-child(<?php echo $mostrarInfoUsuario?4:0 ?>) {
            background-color: #f0f0f0;
        }
        table td:nth-child(<?php echo $mostrarInfoUsuario?5:0 ?>),
        table th:nth-child(<?php echo $mostrarInfoUsuario?5:0 ?>) {
            background-color: #f0f0f0;
        }
        table td:nth-child(<?php echo $mostrarInfoUsuario?6:0 ?>),
        table th:nth-child(<?php echo $mostrarInfoUsuario?6:0 ?>) {
            background-color: #f0f0f0;
        }
        #tablaVentasContainer {
            width: 100%;
            overflow-x: auto; /* Permite el desplazamiento horizontal */
        }

        table {
            width: 100%; /* Mantiene la tabla al 100% del contenedor */
            border-collapse: collapse; /* Para un diseño más compacto */
        }

        table th, table td {
            border: 1px solid #ddd; /* Agrega un borde a las celdas */
            padding: 8px; /* Espaciado interno */
            text-align: left; /* Alineación del texto */
        }

        table th {
            background-color: #f2f2f2; /* Color de fondo de los encabezados */
        }

    </style>
 <?php

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
            <strong>Informe Ventas Totales por Fecha <?php echo date('d-m-Y', strtotime($inicio)) ?> al <?php echo date('d-m-Y', strtotime($fin)) ?></strong>
            <br><br>

            <div id="tablaVentasContainer" style="width:100%; overflow-x:auto;">    
                <table border="1" width="90%" id="tablaVentas">
                    <tr style="background-color:#3c8dbc; color:white;">
                        <th>Factura</th>
                        <th>Cliente</th>
                        <th>Identificación</th>
                        <?php if ($mostrarInfoUsuario): ?>
                            <th>Teléfono</th>
                            <th>Email</th>
                            <th>Dirección</th>
                        <?php endif; ?>
                        <th>Impuesto IVA</th>
                        <th>Impuesto Equivalente</th>
                        <th>IRPF</th>
                        <th>IVA 0%</th>
                        <th>IVA 10%</th>
                        <th>IVA 4%</th>
                        <th>IVA 21%</th>
                        <th>RE 5.2%</th>
                        <th>RE 1.4%</th>
                        <th>RE 0.5%</th>
                        <th>RE 0.75%</th>
                        <th>Estado</th>
                        <th>Subtotal</th>
                        <th>Total</th>

                    </tr>

                    <?php
                    foreach ($facturasExportar as $registro) {
                        $estadoTexto = ($registro->estado == 0) ? "Borrador" : (($registro->estado == 1) ? "Validada" : "Abandonada");

                        echo "<tr>
                            <td>".htmlspecialchars($registro->referencia, ENT_QUOTES, 'UTF-8')."</td>
                            <td>".htmlspecialchars($registro->fk_tercero_txt, ENT_QUOTES, 'UTF-8')."</td>
                            <td>".htmlspecialchars($registro->fk_tercero_identificacion, ENT_QUOTES, 'UTF-8')."</td>";
                            
                        if ($mostrarInfoUsuario) {
                            echo "<td>".htmlspecialchars($registro->fk_tercero_telefono, ENT_QUOTES, 'UTF-8')."</td>
                                <td>".htmlspecialchars($registro->fk_tercero_email, ENT_QUOTES, 'UTF-8')."</td>
                                <td>".htmlspecialchars($registro->fk_tercero_direccion, ENT_QUOTES, 'UTF-8')."</td>";
                        }

                        echo "
                            <td>".htmlspecialchars($registro->impuesto_iva, ENT_QUOTES, 'UTF-8')."</td>
                            <td>".htmlspecialchars($registro->impuesto_iva_equivalencia, ENT_QUOTES, 'UTF-8')."</td>
                            <td>".htmlspecialchars($registro->impuesto_retencion_irpf, ENT_QUOTES, 'UTF-8')."</td>
                            <td>".htmlspecialchars($registro->IVA_0, ENT_QUOTES, 'UTF-8')."</td>
                            <td>".htmlspecialchars($registro->IVA_10, ENT_QUOTES, 'UTF-8')."</td>
                            <td>".htmlspecialchars($registro->IVA_4, ENT_QUOTES, 'UTF-8')."</td>
                            <td>".htmlspecialchars($registro->IVA_21, ENT_QUOTES, 'UTF-8')."</td>
                            <td>".htmlspecialchars($registro->RE_5_2, ENT_QUOTES, 'UTF-8')."</td>
                            <td>".htmlspecialchars($registro->RE_1_4, ENT_QUOTES, 'UTF-8')."</td>
                            <td>".htmlspecialchars($registro->RE_0_5, ENT_QUOTES, 'UTF-8')."</td>
                            <td>".htmlspecialchars($registro->RE_0_75, ENT_QUOTES, 'UTF-8')."</td>
                            <td>".htmlspecialchars($registro->estado_etiqueta, ENT_QUOTES, 'UTF-8')."</td>
                            <td>".htmlspecialchars($registro->subtotal_pre_retencion, ENT_QUOTES, 'UTF-8')."</td>
                            <td>".htmlspecialchars($registro->total, ENT_QUOTES, 'UTF-8')."</td>
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
                <?php if ($mostrarInfoUsuario): ?>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Dirección</th>
                <?php endif; ?>
                <th>Subtotal</th>
                <th>Impuesto IVA</th>
                <th>Total</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($facturasExportar as $registro): ?>
                <tr>
                    <td><?= $registro->referencia ?></td>
                    <td><?= $registro->fk_tercero_txt ?></td>
                    <td><?= $registro->fk_tercero_identificacion ?></td>
                    <?php if ($mostrarInfoUsuario): ?>
                        <td><?= $registro->fk_tercero_telefono ?></td>
                        <td><?= $registro->fk_tercero_email ?></td>
                        <td><?= $registro->fk_tercero_direccion ?></td>
                    <?php endif; ?>
                    <td><?= $registro->subtotal_pre_retencion ?></td>
                    <td><?= $registro->impuesto_iva ?></td>
                    <td><?= $registro->total ?></td>
                    <td><?= $registro->estado_etiqueta ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>