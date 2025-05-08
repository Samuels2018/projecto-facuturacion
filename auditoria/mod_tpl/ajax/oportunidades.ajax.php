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


?>



<div id="chart_div" style="width: 100%; height: 400px;"></div>
<script type="text/javascript">
    google.charts.load('current', {
        'packages': ['corechart']
    });
    google.charts.setOnLoadCallback(drawChart2);

    function drawChart2() {
        // Datos del gráfico (ajústalos según tus necesidades)
        var data = google.visualization.arrayToDataTable([
            <?php echo $datos_chart_funnel['usuarios']; ?>,
            <?php
            foreach ($datos_chart_funnel as $key => $value) {
                if ($key === 'usuarios') continue;
                $arreglo_detalles = array();
                array_push($arreglo_detalles, $key);
                //subforeach donde vamos a buscar los Montos de esta key
                foreach ($datos_chart_funnel[$key] as $key2 => $value2) {
                    array_push($arreglo_detalles, $datos_chart_funnel[$key][$key2]);
                }
                echo json_encode($arreglo_detalles) . ',';
            ?>
            <?php } ?>
        ]);
        // Opciones del gráfico
        var options = {
            hAxis: {
                title: 'Usuarios'
            },
            vAxis: {
                title: 'Montos'
            },
            colors: ['#800080', '#FFA500'], // Colores para las barras
        };
        // Crea el gráfico de barras
        var chart2 = new google.visualization.ColumnChart(document.getElementById('chart_div'));
        chart2.draw(data, options);
    }
</script>