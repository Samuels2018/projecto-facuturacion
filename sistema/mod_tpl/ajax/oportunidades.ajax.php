<?php
SESSION_START();

include_once "../../conf/conf.php";
require_once ENLACE_SERVIDOR . 'mod_crm/object/oportunidad.object.php';
$Oportunidad = new Oportunidad($dbh, $_SESSION['Entidad']);
$data_oportunidad = $Oportunidad->obtener_oportunidades_futuras_monto();
$listado_funnels = $Oportunidad->obtener_listado_funnels();
//EJECUTAMOS UN FUNNEL POR DEFECTO
$Oportunidad->funnel_por_defecto();
//obtenemos los estados de un funnel
$fk_funnel = $_POST['fk_funnel'];
//el funnel obtenido por ajax
$Oportunidad->fk_funnel = $fk_funnel;
$fechaDesde = $_POST['fechaDesde'];
$fechaHasta = $_POST['fechaHasta'];
//oBTENER LOS estados del funnel
$estados_funnel_detalle = $Oportunidad->obtener_listado_estados_funnel_detalle();
$datos_chart_funnel = $Oportunidad->obtener_lista_usuarios_por_funnel($fechaDesde, $fechaHasta);



// Verificar si hay datos no vacíos en el gráfico
$data_empty = true;
foreach ($datos_chart_funnel as $key => $value) {
    if ($key !== 'usuarios' && is_array($value) && array_sum($value) > 0) {
        $data_empty = false;
        break;
    }
}


?>



<div id="chart_div" style="width: 100%; height: 400px;"></div>
<script type="text/javascript">
 
 google.charts.load('current', {'packages': ['corechart']});
    google.charts.setOnLoadCallback(drawChart2);

    function drawChart2() {
        // Datos del gráfico
        var data = google.visualization.arrayToDataTable([
            <?php 
            if ($data_empty) {
                // Datos estáticos si no hay valores
                echo "['Usuario', 'Monto'],";
                echo "['Sin datos', 0]";
            } else {
                // Datos dinámicos obtenidos de PHP
                echo $datos_chart_funnel['usuarios'] . ",";
                foreach ($datos_chart_funnel as $key => $value) {
                    if ($key === 'usuarios') continue;
                    $arreglo_detalles = array($key);
                    foreach ($value as $monto) {
                        $arreglo_detalles[] = $monto ?: 0; // Asigna 0 si no hay datos
                    }
                    echo json_encode($arreglo_detalles) . ",";
                }
            }


            ?>
        ]);


        // Generar colores dinámicos en función del número de usuarios
        var userCount = data.getNumberOfRows();
        var colors = generateColorPalette(userCount);
        
        // Opciones del gráfico
        var options = {
            hAxis: { title: 'Usuarios' },
            vAxis: { title: 'Montos' },
            colors: colors, // Asignar los colores generados
            title: <?php echo $data_empty ? "'SIN DATOS DISPONIBLES'" : "'Estado de oportunidades por Usuario'"; ?>,
            titleTextStyle: { fontSize: 16, bold: true, color: <?php echo $data_empty ? "'#e7515a'" : "'#000000'"; ?> }
        };
        // Crear y dibujar el gráfico
        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
        chart.draw(data, options);
    }

    // Generar colores dinámicos en función del número de usuarios
    function generateColorPalette(count) {
        var colors = [];
        for (var i = 0; i < count; i++) {
            // Generar un color aleatorio en formato hexadecimal
            var color = '#' + Math.floor(Math.random() * 16777215).toString(16).padStart(6, '0');
            colors.push(color);
        }
        return colors;
    }
 

</script>