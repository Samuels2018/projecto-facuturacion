

    <!-- Sección de estadísticas inferiores -->
    <div class="row layout-top-spacing">
        <div class="col-xl-8 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-chart-three">
                <div class="widget-heading">
                    <div class="">
                        <h5 class="">Estado de oportunidades por Usuario</h5>
                    </div>

                    <div class="task-action">
                        <div class="dropdown ">
                            <a class="dropdown-toggle" href="#" role="button" id="uniqueVisitors" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal">
                                    <circle cx="12" cy="12" r="1"></circle>
                                    <circle cx="19" cy="12" r="1"></circle>
                                    <circle cx="5" cy="12" r="1"></circle>
                                </svg>
                            </a>

                            <div class="dropdown-menu left" aria-labelledby="uniqueVisitors">
                                <a class="dropdown-item" href="javascript:void(0);">View</a>
                                <a class="dropdown-item" href="javascript:void(0);">Update</a>
                                <a class="dropdown-item" href="javascript:void(0);">Download</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="widget-content">

                    <form id="formulario_filtros" style="padding: 20px; padding-top: 0; padding-bottom: 0px;">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="funnelSelect">Funnel:</label>
                                    <select class="form-control" id="funnelSelect">
                                        <?php
                                        foreach ($listado_funnels as $key => $value) {
                                        ?>
                                            <option value="<?php echo $listado_funnels[$key]->rowid; ?>"><?php echo $listado_funnels[$key]->titulo; ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="fechaDesde">Fecha desde:</label>
                                    <input type="text" class="form-control" id="fechaDesde">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="fechaHasta">Fecha hasta:</label>
                                    <input type="text" class="form-control" id="fechaHasta">
                                </div>
                            </div>
                        </div>
                    </form>

                    <!--<div id="uniqueVisits"></div>-->
                    <div id="contenedor_filtro_chart_ajax">
                        <div id="chart_div" style="width: 100%; height: 400px;"></div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-activity-five">

                <div class="widget-heading">
                    <h5 class="">Cantidad de actividades por estado</h5>

                    <div class="task-action">
                        <div class="dropdown">
                            <a class="dropdown-toggle" href="#" role="button" id="activitylog" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal">
                                    <circle cx="12" cy="12" r="1"></circle>
                                    <circle cx="19" cy="12" r="1"></circle>
                                    <circle cx="5" cy="12" r="1"></circle>
                                </svg>
                            </a>

                            <div class="dropdown-menu left" aria-labelledby="activitylog" style="will-change: transform;">
                                <a class="dropdown-item" href="javascript:void(0);">View All</a>
                                <a class="dropdown-item" href="javascript:void(0);">Mark as Read</a>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="widget-content">
                    <div id="vendedor_chart"></div>
                </div>

            </div>
        </div>

    </div>


    <style>
.loading-message {
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1em;
    color: #555;
    margin: 15px 0;
}

.loading-message i {
    margin-right: 8px;
    font-size: 1.3em;
    color: #888;
}
#columnVisibilityContainer{
    margin-top:30px !important;
}
#style-3_paginate{
    display:none !important;
}
.dataTables_info{
    display:none !important;
}

</style>
<script>
   $(document).ready(function() {
       // Desactivar todos los elementos del menú
       $(".menu").removeClass('active');
       $(".dashboard").addClass('active');
       $(".dashboard > .submenu").addClass('show');
       $("#dashboard").addClass('active');

       // Configuración del datepicker para "Fecha Desde"
       $("#fechaDesde").datepicker({
           dateFormat: "yy-mm-dd",
           onSelect: function(selectedDate) {
               $("#fechaHasta").datepicker("option", "minDate", selectedDate);
               if ($("#fechaHasta").val()) { // Llama a mostrarValores() si ambas fechas están seleccionadas
                   mostrarValores();
               }
           },
           dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sá"],
           monthNames: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
           firstDay: 1
       });

       // Configuración del datepicker para "Fecha Hasta"
       $("#fechaHasta").datepicker({
           dateFormat: "yy-mm-dd",
           onSelect: function(selectedDate) {
               $("#fechaDesde").datepicker("option", "maxDate", selectedDate);
               if ($("#fechaDesde").val()) { // Llama a mostrarValores() si ambas fechas están seleccionadas
                   mostrarValores();
               }
           },
           dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sá"],
           monthNames: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
           firstDay: 1
       });

       // Función para mostrar el gráfico cuando ambas fechas están seleccionadas
       function mostrarValores() {
           var fechaDesde = $("#fechaDesde").val();
           var fechaHasta = $("#fechaHasta").val();
           var fk_funnel = $("#funnelSelect").val();

           // Validar que ambas fechas estén seleccionadas
           if (!fechaDesde || !fechaHasta) {
               alert("Por favor selecciona ambas fechas.");
               return;
           }

           // Mostrar mensaje de carga
           $(".ajax-grafico").remove();
           $("#formulario_filtros").after('<div class="ajax-grafico loading-message"><i class="fas fa-spinner fa-spin"></i> Cargando gráfico...</div>');

           // Enviar solicitud AJAX
           $.post("<?php echo ENLACE_WEB; ?>mod_tpl/ajax/oportunidades.ajax.php", {
               fechaDesde: fechaDesde,
               fechaHasta: fechaHasta,
               fk_funnel: fk_funnel,
           })
           .done(function(data) {
               $(".ajax-grafico").remove(); // Quitar el mensaje de carga
               $("#contenedor_filtro_chart_ajax").html(data); // Mostrar el nuevo gráfico
           });
       }

       // Limpiar fechas y recargar gráfico vacío al borrar ambas fechas
       $("#fechaDesde, #fechaHasta").on("change", function() {
           if (!$("#fechaDesde").val() && !$("#fechaHasta").val()) {
               //$("#contenedor_filtro_chart_ajax").html('<div class="loading-message"><i class="fas fa-spinner fa-spin"></i> Sin datos seleccionados</div>');
               actualizarGraficos();
            }
       });
   });
</script>

<script src="<?php echo ENLACE_WEB; ?>bootstrap/assets/js/dashboard/dash_1.js"></script>




<script type="text/javascript">

google.charts.load('current', {'packages':['corechart', 'timeline']});
google.charts.setOnLoadCallback(drawCharts);


function drawVendedorChart() 
{
            // Creamos el array de datos para Google Charts en PHP
            var data = google.visualization.arrayToDataTable([
                ['Estado', 'Cantidad'],
                <?php
                    foreach ($estados_funnel_detalle as $estado) {
                        // Obtenemos la cantidad dinámica usando `posicion`
                        $cantidad = $Oportunidad->obtener_total_oportunidades_por_tipo($estado['posicion'])[0]->cantidad_oportunidades;
                        echo "['{$estado['etiqueta']}', $cantidad],";
                    }
                ?>
            ]);

            // Configuración de estilo del gráfico
            var options = {
                width: '100%', // Ancho del gráfico
                height: 400, // Alto del gráfico
                chartArea: { left: 10, top: 50, width: '100%', height: '100%' }, // Sin relleno
                legend: { position: 'top', maxLines: 2 } // Leyenda en la parte superior
            };

            // Dibujamos el gráfico de pastel en el elemento con ID 'vendedor_chart'
            var chart = new google.visualization.PieChart(document.getElementById('vendedor_chart'));
            chart.draw(data, options);
}


function drawCharts() 
{
    drawVendedorChart();
}
</script>


<script type="text/javascript">
    google.charts.load('current', {'packages': ['corechart']});
    google.charts.setOnLoadCallback(drawChart2);

    $("#funnelSelect").change(function(){
        fk_funnel = $(this).val();
        actualizarGraficos();
    });
    $("#funnelSelect").trigger("change");
   // actualizarGraficos();

function actualizarGraficos() 
{
    var fk_funnel = $('#funnelSelect').val(); // Obtiene el valor seleccionado del campo "funnel"

    // Obtiene el año actual
    var yearActual = new Date().getFullYear();
    
    // Define el primer y último día del año
    var primerDia = yearActual + "-01-01";
    var ultimoDia = yearActual + "-12-31";

    // Obtiene los valores de fechaDesde y fechaHasta, y asigna los valores por defecto si están vacíos
    var fechaDesde = $("#fechaDesde").val() || primerDia;
    var fechaHasta = $("#fechaHasta").val() || ultimoDia;

    // Asigna los valores a los inputs en caso de que estuvieran vacíos
    $("#fechaDesde").val(fechaDesde);
    $("#fechaHasta").val(fechaHasta);

    // Muestra un mensaje temporal mientras se actualizan los gráficos
    $('#contenedor_filtro_chart_ajax').html('<div class="loading-message"><i class="fas fa-spinner fa-spin"></i> Actualizando gráfico...</div>');
    $("#vendedor_chart").html('<div class="loading-message"><i class="fas fa-spinner fa-spin"></i> Actualizando gráfico...</div>');


    // Solicitud AJAX para obtener los nuevos datos del gráfico según el funnel seleccionado
    $.ajax({
        url: "<?php echo ENLACE_WEB; ?>mod_tpl/ajax/oportunidades.ajax.php",
        type: "POST",
        data: {
            fk_funnel: fk_funnel,
            fechaDesde: fechaDesde,
            fechaHasta: fechaHasta,
        },
        success: function(response) {
            // Procesar la respuesta HTML y actualizar el contenido del gráfico
            $('#contenedor_filtro_chart_ajax').html(response);
        },
        error: function() {
            $('#contenedor_filtro_chart_ajax').html('<p>Error al actualizar el gráfico.</p>');
        }
    });

    // Solicitud AJAX para actualizar el gráfico de vendedor
    $.ajax({
        url: "<?php echo ENLACE_WEB; ?>mod_tpl/ajax/oportunidades_vendedor_chart.ajax.php",
        type: "POST",
        data: { fk_funnel: fk_funnel, fechaDesde: fechaDesde, fechaHasta: fechaHasta },
        success: function(response) {
            console.log(response);
            $('#vendedor_chart').html(response); // Actualizar el gráfico de vendedor
        },
        error: function() {
            $('#vendedor_chart').html('<p>Error al actualizar el gráfico de vendedor.</p>');
        }
    });
}



      // Función para dibujar el gráfico "Estado de oportunidades por Usuario" con datos dinámicos
      function drawChart2Update(chartData) {

        // Convertimos los datos del JSON a un formato que Google Charts pueda entender
        var data = google.visualization.arrayToDataTable(chartData);

        // Opciones del gráfico
        var options = {
            hAxis: {title: 'Usuarios'},
            vAxis: {title: 'Montos'},
            colors: ['#cccccc'],
            title: chartData.length > 1 ? "Estado de Oportunidades por Usuario" : "SIN DATOS DISPONIBLES",
            titleTextStyle: {
                color: chartData.length > 1 ? '#000000' : '#e7515a',
                fontSize: 16,
                bold: true
            }
        };

        // Crear y dibujar el gráfico de columnas
        var chart2 = new google.visualization.ColumnChart(document.getElementById('chart_div'));
        chart2.draw(data, options);
    }



    function drawChart2() {
        let data_empty = '';
        // Verificamos y construimos los datos para el gráfico
        var data = google.visualization.arrayToDataTable([
            <?php 
                if (!empty($datos_chart_funnel['usuarios'])) {
                    echo $datos_chart_funnel['usuarios'] . ",";
                } else {
                    echo "['Usuario', 'Monto'],";
                }
                
                $data_empty = true; // Bandera para verificar si al menos un dato está presente
                foreach ($datos_chart_funnel as $key => $value) {
                    if ($key === 'usuarios') continue;

                    $arreglo_detalles = array();
                    array_push($arreglo_detalles, $key);

                    foreach ($datos_chart_funnel[$key] as $key2 => $value2) {
                        $monto = isset($value2) && $value2 !== '' ? $value2 : 0;
                        array_push($arreglo_detalles, $monto);
                        if ($monto > 0) $data_empty = false;
                    }

                    echo json_encode($arreglo_detalles) . ',';
                }

                if ($data_empty) {
                    echo "['Sin datos', 0],";
                }
            ?>
        ]);
        // Opciones del gráfico
       
        var options = {
            hAxis: { title: 'Usuarios' },
            vAxis: { title: 'Montos' },
            colors: colors, // Asignar los colores generados
            title: <?php echo $data_empty ? "'SIN DATOS DISPONIBLES'" : "'Estado de oportunidades por Usuario'"; ?>,
            titleTextStyle: { fontSize: 16, bold: true, color: <?php echo $data_empty ? "'#e7515a'" : "'#000000'"; ?> }
        };
        // Crear el gráfico de barras
        var chart2 = new google.visualization.ColumnChart(document.getElementById('chart_div'));
        chart2.draw(data, options);
    }
</script>
