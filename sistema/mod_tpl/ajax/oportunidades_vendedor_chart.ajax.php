<?php

SESSION_START();

include_once "../../conf/conf.php";

// Incluir el archivo que maneja la clase `Oportunidad`
require_once ENLACE_SERVIDOR . 'mod_crm/object/oportunidad.object.php';
$Oportunidad = new Oportunidad($dbh, $_SESSION['Entidad']);

// Obtener el valor `fk_funnel` desde la solicitud AJAX
$fk_funnel = $_POST['fk_funnel'];
$Oportunidad->fk_funnel = $fk_funnel; // Actualizar el funnel en el objeto

// Obtener los estados y datos del funnel actualizado
$estados_funnel_detalle = $Oportunidad->obtener_listado_estados_funnel_detalle();


?>

<script type="text/javascript">
    function drawVendedorChart() {
        
        var data = google.visualization.arrayToDataTable([
            ['Estado', 'Cantidad'],
            <?php
          
            foreach ($estados_funnel_detalle as $estado) {
                    $cantidad = $Oportunidad->obtener_total_oportunidades_por_tipo($estado['posicion'])[0]->cantidad_oportunidades;
                    echo "['{$estado['etiqueta']}', $cantidad],";
                }
            ?>
        ]);

        var options = {
            width: '100%',
            height: 400,
            chartArea: { left: 10, top: 50, width: '100%', height: '100%' },
            legend: { position: 'top', maxLines: 2 }
        };

        var chart = new google.visualization.PieChart(document.getElementById('vendedor_chart'));
        chart.draw(data, options);
    }

    // Llamar a la función para redibujar el gráfico
    drawVendedorChart();
</script>
