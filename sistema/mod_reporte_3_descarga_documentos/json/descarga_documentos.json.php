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
    $rango = "AND fecha >= '" . $inicio . "' AND fecha <= '" . $fin . "'   ";
}


if ($_POST['exportar'] == "1") {
    $limit = " limit 100";
}

$tabla = $_REQUEST['tipo'];
$diccionario_estado = "";
switch ($tabla) {
    case 'fi_europa_facturas':
        $diccionario_estado = "LEFT JOIN " . DB_NAME_UTILIDADES_APOYO . ".diccionario_factura_europa_diccionario d ON f.estado = d.rowid";
        break;
    case 'fi_europa_compras':
        $diccionario_estado = "LEFT JOIN " . DB_NAME_UTILIDADES_APOYO . ".diccionario_compra_europa_diccionario d ON f.estado = d.rowid";
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
    f.fecha AS Fecha_Factura,
    f.referencia AS Numero_Factura,
    IFNULL(f.fk_tercero_txt, 'Cliente Genérico') AS Cliente,
    IFNULL(f.fk_tercero_identificacion, ' -- ') AS NIF_CIF,
    IFNULL(f.fk_tercero_telefono, ' -- ') as telefono,
    IFNULL(f.fk_tercero_email, ' -- ') as email,
    IFNULL(f.fk_tercero_direccion, ' -- ') as direccion,
    f.subtotal_pre_retencion AS Base_Imponible,
    f.impuesto_iva AS IVA,
    f.impuesto_retencion_irpf AS Retencion_IRPF,
    f.total AS Total_Factura ,
    f.IVA_21 ,
    f.IVA_4  ,
    f.IVA_0  ,
    f.IVA_10 , d.etiqueta as estado_etiqueta
    
FROM " . $tabla . "  f
{$diccionario_estado}
WHERE entidad = :entidad   
{$reporte->condicion_documentos[$tabla]}
ORDER BY f.fecha DESC  $limit; ";

$db = $dbh->prepare($sql);
$db->bindValue(":entidad", $_SESSION['Entidad'], PDO::PARAM_INT);
$db->execute();
$facturasExportar = $db->fetchAll(PDO::FETCH_OBJ);

?>
<style>
    table td:nth-child(<?php echo $mostrarInfoUsuario ? 3 : 0 ?>),
    table th:nth-child(<?php echo $mostrarInfoUsuario ? 3 : 0 ?>) {
        background-color: #f0f0f0;
    }

    table td:nth-child(<?php echo $mostrarInfoUsuario ? 4 : 0 ?>),
    table th:nth-child(<?php echo $mostrarInfoUsuario ? 4 : 0 ?>) {
        background-color: #f0f0f0;
    }

    table td:nth-child(<?php echo $mostrarInfoUsuario ? 5 : 0 ?>),
    table th:nth-child(<?php echo $mostrarInfoUsuario ? 5 : 0 ?>) {
        background-color: #f0f0f0;
    }

    table td:nth-child(<?php echo $mostrarInfoUsuario ? 6 : 0 ?>),
    table th:nth-child(<?php echo $mostrarInfoUsuario ? 6 : 0 ?>) {
        background-color: #f0f0f0;
    }

    #tablaVentasContainer {
        width: 100%;
        overflow-x: auto;
        /* Permite el desplazamiento horizontal */
    }

    table {
        width: 100%;
        /* Mantiene la tabla al 100% del contenedor */
        border-collapse: collapse;
        /* Para un diseño más compacto */
    }

    table th,
    table td {
        border: 1px solid #ddd;
        /* Agrega un borde a las celdas */
        padding: 8px;
        /* Espaciado interno */
        text-align: left;
        /* Alineación del texto */
    }

    table th {
        background-color: #f2f2f2;
        /* Color de fondo de los encabezados */
    }
</style>
<?php

// **Si el usuario quiere exportar a Excel**
if ($_POST['exportar'] == "1") {
    header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
    header("Pragma: no-cache");
    header("Expires: 0");

    $filename = "Informe_{$Utilidades->nombre_publico_tabla($tabla)}_{$_SESSION['Entidad']}.xls";
    header('Content-Disposition: attachment; filename=' . $filename);
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

            <table border="1" width="90%">
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
                    echo "<tr>
                        <td>{$registro->Numero_Factura}</td>
                        <td>{$registro->Cliente}</td>
                        <td>{$registro->NIF_CIF}</td>";

                    if ($mostrarInfoUsuario) {
                        echo "<td>{$registro->telefono}</td>
                              <td>{$registro->email}</td>
                              <td>{$registro->direccion}</td>";
                    }

                    echo "
                          <td>{$registro->IVA}</td>
                          <td>{$registro->Impuesto_Equivalente}</td>
                          <td>{$registro->Retencion_IRPF}</td>
                          <td>{$registro->IVA_0}</td>
                          <td>{$registro->IVA_10}</td>
                          <td>{$registro->IVA_4}</td>
                          <td>{$registro->IVA_21}</td>
                          <td>{$registro->RE_5_2}</td>
                          <td>{$registro->RE_1_4}</td>
                          <td>{$registro->RE_0_5}</td>
                          <td>{$registro->RE_0_75}</td>
                          <td>{$registro->estado_etiqueta}</td>
                          <td>{$registro->Base_Imponible}</td>
                          <td>{$registro->Total_Factura}</td>
                    </tr>";
                }
                ?>
            </table>
        </center>
    </body>

    </html>

<?php
    echo ob_get_clean();
    exit();
}

?>

<!-- **Tabla con Paginación en Pantalla** -->
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
    </thead>
    <tbody>
        <?php
        foreach ($facturasExportar as $registro) {

            echo "<tr>
                        <td>{$registro->Numero_Factura}</td>
                        <td>{$registro->Cliente}</td>
                        <td>{$registro->NIF_CIF}</td>";

            if ($mostrarInfoUsuario) {
                echo "<td>{$registro->telefono}</td>
                    <td>{$registro->email}</td>
                    <td>{$registro->direccion}</td>";
            }

            echo "
                          <td>{$registro->IVA}</td>
                          <td>{$registro->Impuesto_Equivalente}</td>
                          <td>{$registro->Retencion_IRPF}</td>
                          <td>{$registro->IVA_0}</td>
                          <td>{$registro->IVA_10}</td>
                          <td>{$registro->IVA_4}</td>
                          <td>{$registro->IVA_21}</td>
                          <td>{$registro->RE_5_2}</td>
                          <td>{$registro->RE_1_4}</td>
                          <td>{$registro->RE_0_5}</td>
                          <td>{$registro->RE_0_75}</td>
                          <td>{$registro->estado_etiqueta}</td>
                          <td>{$registro->Base_Imponible}</td>
                          <td>{$registro->Total_Factura}</td>
                    </tr>";
        }
        ?>
    </tbody>
</table>