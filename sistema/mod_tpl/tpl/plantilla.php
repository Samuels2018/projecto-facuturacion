<!--  BEGIN CONTENT AREA  -->
<!--
<div id="content" class="main-content">
            <div class="layout-px-spacing"> 
-->
<?php


    require_once ENLACE_SERVIDOR . 'mod_crm/object/oportunidad.object.php';
    $Oportunidad = new Oportunidad($dbh, $_SESSION['Entidad']);

    $listado_funnels = $Oportunidad->obtener_listado_funnels();
    //EJECUTAMOS UN FUNNEL POR DEFECTO
    $Oportunidad->funnel_por_defecto();
    //obtenemos los estados de un funnel
    $estados_funnel_detalle = $Oportunidad->obtener_listado_estados_funnel_detalle();
    $datos_chart_funnel = $Oportunidad->obtener_lista_usuarios_por_funnel();

    $data_oportunidad = $Oportunidad->obtener_oportunidades_futuras_monto();


    // Verificar si $estados_funnel_detalle es un array antes de llamar a usort
    if (is_array($estados_funnel_detalle)) {
        usort($estados_funnel_detalle, function ($a, $b) {
            return $a['posicion'] - $b['posicion'];
        });
    } else {
        // Si no es un array válido, retornar un array vacío
        $estados_funnel_detalle = [];
    }


?>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.3/themes/base/jquery-ui.min.css">
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ENLACE_WEB; ?>bootstrap/assets/css/dark/dashboard/dash_1.css">
<link rel="stylesheet" type="text/css" href="<?php echo ENLACE_WEB; ?>bootstrap/assets/css/light/widgets/modules-widgets.css">

<style>
    .widget {
        position: relative;
        padding: 20px;
        border-radius: 6px;
        border: none;
        background: #fff;
        border: 1px solid #e0e6ed;
        box-shadow: 0 0 40px 0 rgba(94, 92, 154, 0.06);
    }

    .widget-heading {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
    }

    .card-content {
        height: 120px;
        /* Altura fija para todas las tarjetas */
        font-size: 12px;
    }

    .container_cards_dashboard {
        height: 250px;
    }

    /* Ajustes de tamaño para pantallas pequeñas */
    @media (max-width: 768px) {
        .col-md-2 {
            flex: 0 0 100%;
            /* Cada tarjeta ocupará el 100% del ancho en pantallas pequeñas */
            max-width: 100%;
            margin-bottom: 20px;
            /* Espacio extra abajo de cada tarjeta */
        }

        .container_cards_dashboard {
            height: auto;
        }
    }
</style>
<div class="middle-content container-xxl p-0">

    <!-- Sección de Facturación: Ventas vs Ingresos
    Sección de Verifactum -->
    <div class="row layout-top-spacing">

        <div class="col-xl-8 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-chart-one" style="height: 450px;">
                <div class="widget-heading">
                    <h5 class="">Ventas vs Ingresos</h5>
                    <div class="task-action">
                        <div class="dropdown">
                            <span id="facturacion_periodo">MENSUAL</span>
                            <a class="dropdown-toggle" href="#" role="button" id="filtro_serie1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal">
                                    <circle cx="12" cy="12" r="1"></circle>
                                    <circle cx="19" cy="12" r="1"></circle>
                                    <circle cx="5" cy="12" r="1"></circle>
                                </svg>
                            </a>
                            <div class="dropdown-menu left" aria-labelledby="filtro_serie1" style="will-change: transform;">
                                <a class="dropdown-item" href="javascript:void(0);" onclick="drawFacturacion('diario')">Diario</a>
                                <a class="dropdown-item" href="javascript:void(0);" onclick="drawFacturacion('semanal')">Semanal</a>
                                <a class="dropdown-item" href="javascript:void(0);" onclick="drawFacturacion('mensual')">Mensual</a>
                                <a class="dropdown-item" href="javascript:void(0);" onclick="drawFacturacion('anual')">Anual</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="widget-content">
                    <div id="chart_facturacion" style="min-height: 380px;"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-chart-two" style="height: 450px;">
                <div class="widget-heading">
                    <h5 class="">Verifactum</h5>
                </div>
                <div class="widget-content">
                    <!-- <div id="chart_verifactum" class="" style="min-height: 371.05px;"></div> -->
                    <div id="chart_estados" class="" style="min-height: 371.05px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección de Log de Actividades 
    Sección de Facturación: Base vs IVA's -->
    <div class="row layout-top-spacing">

        <div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-activity-five">
                <div class="widget-heading">
                    <h5 class="">Log de Actividad</h5>
                    <div class="text-primary">Últimos 10 registros</div>
                </div>

                <div class="widget-content">
                    <div class="mt-container mx-auto ps ps--active-y">
                        <div id="timeline-line-log" class="timeline-line" style="overflow-y: scroll;max-height:100%;">
                            <!-- El contenido se rellena por Ajax -->
                        </div>
                    </div>



                    <div class="w-shadow-bottom"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-chart-one">
                <div class="widget-heading">
                    <h5 class="">Base vs IVA's</h5>
                    <div class="task-action">
                        <div class="dropdown">
                            <span id="facturacion_periodo_base">MENSUAL</span>
                            <a class="dropdown-toggle" href="#" role="button" id="filtro_serie_base" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal">
                                    <circle cx="12" cy="12" r="1"></circle>
                                    <circle cx="19" cy="12" r="1"></circle>
                                    <circle cx="5" cy="12" r="1"></circle>
                                </svg>
                            </a>
                            <div class="dropdown-menu left" aria-labelledby="filtro_serie_base" style="will-change: transform;">
                                <a class="dropdown-item" href="javascript:void(0);" onclick="drawFacturacionBase('diario')">Diario</a>
                                <a class="dropdown-item" href="javascript:void(0);" onclick="drawFacturacionBase('semanal')">Semanal</a>
                                <a class="dropdown-item" href="javascript:void(0);" onclick="drawFacturacionBase('mensual')">Mensual</a>
                                <a class="dropdown-item" href="javascript:void(0);" onclick="drawFacturacionBase('anual')">Anual</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="widget-content">
                    <div id="chart_facturacion_base" style="min-height: 380px;"></div>
                </div>
            </div>
        </div>

    </div>

    <!-- Sección de Top Selling
    Sección de Ultimas Facturas -->
    <div class="row layout-top-spacing">
        <div class="col-xl-8 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-table-three" style="height: 450px;overflow: hidden;">
        
                <div class="widget-heading">
                    <h5 class="">Productos más vendidos</h5>
                </div>

                <div class="widget-content" style="overflow-y: scroll;max-height:90%;">
                    <div class="table-responsive">
                        <table class="table table-scroll" id="draw_productos_ventas">
                            <thead>
                                <tr>
                                    <th><div class="th-content">Producto</div></th>
                                    <th><div class="th-content th-heading">Stock</div></th>
                                    <th><div class="th-content th-heading">Precio</div></th>
                                    <th><div class="th-content">Cantidad</div></th>
                                    <th><div class="th-content">Ventas</div></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">

            <div class="widget widget-activity-five" style="height: 450px;overflow: hidden;">
                <div class="widget-heading">
                    <h5 class="">Últimas facturas</h5>
                    <div class="text-primary">Últimos 10 registros</div>
                </div>

                <div class="widget-content">
                    <div class="mt-container mx-auto ps ps--active-y">
                        <div id="timeline-line-factura" class="timeline-line" style="overflow-y: scroll;max-height:100%;">
                            <!-- El contenido se rellena por Ajax -->
                        </div>
                    </div>

                    <div class="w-shadow-bottom"></div>
                </div>
            </div>
        </div>

    </div>
    

    
    <?php 
        require_once ENLACE_SERVIDOR.'mod_tpl/tpl/graficos_oportunidad.php';
    ?>


 
    <script>

        function drawVerifactumChart() {
            $.ajax({
                method: "POST",
                url: "<?php echo ENLACE_WEB; ?>mod_tpl/ajax/dashboard.ajax.php",
                beforeSend: function(xhr) {
                    $('#verifactum_chart').html('Cargando...')
                    $('#borrador_chart').html('Cargando...')
                },
                data: {
                    action: 'devolver_datos_dashboard_verifactum'
                },
            }).done(function(data) {
                const response = JSON.parse(data)

                let optionsVerifactum = {
                    chart: {
                        type: 'donut',
                        width: 370,
                        height: 430
                    },
                    colors: ['#622bd7', '#e2a03f', '#e7515a', '#e2a03f'],
                    dataLabels: {
                        enabled: false
                    },
                    legend: {
                        position: 'bottom',
                        horizontalAlign: 'center',
                        fontSize: '14px',
                        markers: {
                            width: 10,
                            height: 10,
                            offsetX: -5,
                            offsetY: 0
                        },
                        itemMargin: {
                            horizontal: 10,
                            vertical: 30
                        }
                    },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '75%',
                                background: 'transparent',
                                labels: {
                                    show: true,
                                    name: {
                                        show: true,
                                        fontSize: '29px',
                                        fontFamily: 'Nunito, sans-serif',
                                        color: undefined,
                                        offsetY: -10
                                    },
                                    value: {
                                        show: true,
                                        fontSize: '26px',
                                        fontFamily: 'Nunito, sans-serif',
                                        color: '#bfc9d4',
                                        offsetY: 16,
                                        formatter: function(val) {
                                            return val
                                        }
                                    },
                                    total: {
                                        show: true,
                                        showAlways: true,
                                        label: 'Total',
                                        color: '#888ea8',
                                        fontSize: '30px',
                                        formatter: function(w) {
                                            return w.globals.seriesTotals.reduce(function(a, b) {
                                                return a + b
                                            }, 0)
                                        }
                                    }
                                }
                            }
                        }
                    },
                    stroke: {
                        show: true,
                        width: 15,
                        // colors: '#0e1726'
                        colors: 'transparent'
                    },
                    series: [parseInt(response.mensaje.verifactum_sin), parseInt(response.mensaje.verifactum_con)],
                    labels: ['Sin Verifactum', 'Con Verifactum'],

                    responsive: [{
                            breakpoint: 1440,
                            options: {
                                chart: {
                                    width: 325
                                },
                            }
                        },
                        {
                            breakpoint: 1199,
                            options: {
                                chart: {
                                    width: 380
                                },
                            }
                        },
                        {
                            breakpoint: 575,
                            options: {
                                chart: {
                                    width: 320
                                },
                            }
                        },
                    ],
                };
                let chartVerifactum = new ApexCharts(
                    document.querySelector("#chart_verifactum"),
                    optionsVerifactum
                );
                chartVerifactum.render();
            });

        }
        function drawEstadosChart() {
            $.ajax({
                method: "POST",
                url: "<?php echo ENLACE_WEB; ?>mod_tpl/ajax/dashboard.ajax.php",
                beforeSend: function(xhr) {
                    $('#verifactum_chart').html('Cargando...')
                    $('#borrador_chart').html('Cargando...')
                },
                data: {
                    action: 'devolver_datos_dashboard_estados'
                },
            }).done(function(data) {
                const response = JSON.parse(data)

                let optionsEstados = {
                    chart: {
                        type: 'donut',
                        width: 370,
                        height: 430
                    },
                    colors: ['#622bd7', '#e2a03f', '#83a03f', '#e7515a'],
                    dataLabels: {
                        enabled: false
                    },
                    legend: {
                        position: 'bottom',
                        horizontalAlign: 'center',
                        fontSize: '14px',
                        markers: {
                            width: 10,
                            height: 10,
                            offsetX: -5,
                            offsetY: 0
                        },
                        itemMargin: {
                            horizontal: 10,
                            vertical: 30
                        }
                    },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '75%',
                                background: 'transparent',
                                labels: {
                                    show: true,
                                    name: {
                                        show: true,
                                        fontSize: '29px',
                                        fontFamily: 'Nunito, sans-serif',
                                        color: undefined,
                                        offsetY: -10
                                    },
                                    value: {
                                        show: true,
                                        fontSize: '26px',
                                        fontFamily: 'Nunito, sans-serif',
                                        color: '#bfc9d4',
                                        offsetY: 16,
                                        formatter: function(val) {
                                            return val
                                        }
                                    },
                                    total: {
                                        show: true,
                                        showAlways: true,
                                        label: 'Total',
                                        color: '#888ea8',
                                        fontSize: '30px',
                                        formatter: function(w) {
                                            return w.globals.seriesTotals.reduce(function(a, b) {
                                                return a + b
                                            }, 0)
                                        }
                                    }
                                }
                            }
                        }
                    },
                    stroke: {
                        show: true,
                        width: 15,
                        // colors: '#0e1726'
                        colors: 'transparent'
                    },
                    series: [parseInt(response.mensaje.borrador), parseInt(response.mensaje.pendiente), parseInt(response.mensaje.correcto), parseInt(response.mensaje.incorrecto)],
                    labels: ['Borrador', 'Pendiente', 'Correcto', 'Incorrecto'],

                    responsive: [{
                            breakpoint: 1440,
                            options: {
                                chart: {
                                    width: 325
                                },
                            }
                        },
                        {
                            breakpoint: 1199,
                            options: {
                                chart: {
                                    width: 380
                                },
                            }
                        },
                        {
                            breakpoint: 575,
                            options: {
                                chart: {
                                    width: 320
                                },
                            }
                        },
                    ],
                };
                let chartEstados = new ApexCharts(
                    document.querySelector("#chart_estados"),
                    optionsEstados
                );
                chartEstados.render();
            });

        }
        let chartSerie; let chartSerie_Base;
        function drawFacturacion(filtro){
            $.ajax({
                method: "POST",
                url: "<?php echo ENLACE_WEB; ?>mod_tpl/ajax/dashboard.ajax.php",
                beforeSend: function(xhr) {
                    $('#verifactum_chart').html('Cargando...')
                    $('#borrador_chart').html('Cargando...')
                },
                data: {
                    action: 'devolver_datos_dashboard_series',
                    filtro:(filtro && filtro!=''?filtro:'mensual')
                },
            }).done(function(data) {
                $('#facturacion_periodo').text( (filtro && filtro!=''?filtro.toUpperCase():'MENSUAL'))

                const response = JSON.parse(data)

                const periodos = Object.keys(response.mensaje.totales_ventas);
                // Obtener los valores de ventas y a_cobrar en arrays
                const ventasSerie = periodos.map(periodo => parseInt(response.mensaje.totales_ventas[periodo].ventas.toString().replace(/,/g, '')) || 0);
                const aCobrarSerie = periodos.map(periodo => parseInt(response.mensaje.totales_ventas[periodo].a_cobrar.toString().replace(/,/g, '')) || 0);

                // Calcular la diferencia total
                let diferenciaNeta = Object.keys(response.mensaje.totales_ventas).reduce((total, year) => {
                    const ventas = parseInt(response.mensaje.totales_ventas[year].ventas.toString().replace(/,/g, '')) || 0;
                    const aCobrar = parseInt(response.mensaje.totales_ventas[year].a_cobrar.toString().replace(/,/g, '')) || 0;
                    // Sumar la diferencia entre ventas y a_cobrar al total
                    return total + (ventas - aCobrar);
                }, 0);


                const optionsSerie = {
                    chart: {
                        fontFamily: 'Nunito, sans-serif',
                        height: 365,
                        type: 'area',
                        zoom: {
                            enabled: true
                        },
                        dropShadow: {
                            enabled: true,
                            opacity: 0.2,
                            blur: 10,
                            left: -7,
                            top: 22
                        },
                        toolbar: {
                            show: false
                        },
                    },
                    colors: ['#e7515a', '#2196f3'],
                    dataLabels: {
                        enabled: false
                    },
                    markers: {
                        discrete: [{
                            seriesIndex: 0,
                            dataPointIndex: 7,
                            fillColor: '#000',
                            strokeColor: '#000',
                            size: 5
                        }, {
                            seriesIndex: 2,
                            dataPointIndex: 11,
                            fillColor: '#000',
                            strokeColor: '#000',
                            size: 4
                        }]
                    },
                    subtitle: {
                        text: '€ ' + diferenciaNeta.toLocaleString('es-PE'),
                        align: 'left',
                        margin: 0,
                        offsetX: 200,
                        offsetY: 20,
                        floating: false,
                        style: {
                            fontSize: '18px',
                            color: '#00ab55'
                        }
                    },
                    title: {
                        text: 'Total Ventas - Ingresos',
                        align: 'left',
                        margin: 0,
                        offsetX: -10,
                        offsetY: 20,
                        floating: false,
                        style: {
                            fontSize: '18px',
                            color: '#bfc9d4'
                        },
                    },
                    stroke: {
                        show: true,
                        curve: 'smooth',
                        width: 2,
                        lineCap: 'square'
                    },
                    series: [{
                        name: 'Ingresos',
                        data: aCobrarSerie
                    },{
                        name: 'Ventas',
                        data: ventasSerie
                    }],
                    labels: periodos,
                    xaxis: {
                        axisBorder: {
                            show: false
                        },
                        axisTicks: {
                            show: false
                        },
                        crosshairs: {
                            show: true
                        },
                        labels: {
                            offsetX: 0,
                            offsetY: 5,                            
                            rotate: -45, // Añadido para rotar las etiquetas 45°
                            rotateAlways: true, // Asegurar que siempre estén rotadas
                            style: {
                                fontSize: '12px',
                                fontFamily: 'Nunito, sans-serif',
                                cssClass: 'apexcharts-xaxis-title',
                            },
                        }
                    },
                    yaxis: {
                        labels: {
                            formatter: function(value, index) {
                                return (value / 1000) + 'K'
                            },
                            offsetX: -15,
                            offsetY: 0,
                            style: {
                                fontSize: '12px',
                                fontFamily: 'Nunito, sans-serif',
                                cssClass: 'apexcharts-yaxis-title',
                            },
                        }
                    },
                    grid: {
                        borderColor: '#191e3a',
                        strokeDashArray: 5,
                        xaxis: {
                            lines: {
                                show: true
                            }
                        },
                        yaxis: {
                            lines: {
                                show: false,
                            }
                        },
                        padding: {
                            top: -50,
                            right: 0,
                            bottom: 0,
                            left: 5
                        },
                    },
                    legend: {
                        position: 'top',
                        horizontalAlign: 'right',
                        offsetY: -50,
                        fontSize: '16px',
                        fontFamily: 'Quicksand, sans-serif',
                        markers: {
                            width: 10,
                            height: 10,
                            strokeWidth: 0,
                            strokeColor: '#fff',
                            fillColors: undefined,
                            radius: 12,
                            onClick: undefined,
                            offsetX: -5,
                            offsetY: 0
                        },
                        itemMargin: {
                            horizontal: 10,
                            vertical: 20
                        }

                    },
                    tooltip: {
                        theme: 'dark',
                        marker: {
                            show: true,
                        },
                        x: {
                            show: false,
                        },
                        y: {
                            formatter: function(value, series) {
                                // use series argument to pull original string from chart data
                                return '€ ' + value.toLocaleString('es-PE');
                            }
                        }
                    },
                    fill: {
                        type: "gradient",
                        gradient: {
                            type: "vertical",
                            shadeIntensity: 1,
                            inverseColors: !1,
                            opacityFrom: .19,
                            opacityTo: .05,
                            stops: [100, 100]
                        }
                    },
                    responsive: [{
                        breakpoint: 575,
                        options: {
                            legend: {
                                offsetY: -50,
                            },
                        },
                    }]
                }

                if(chartSerie){
                    // Actualizar gráfico existente
                    chartSerie.updateOptions({
                        series: [{
                            name: 'Ingresos',
                            data: aCobrarSerie
                        },{
                        name: 'Ventas',
                        data: ventasSerie
                        }],
                        xaxis: {
                            categories: periodos
                        },
                        labels: periodos,
                        subtitle: {
                            text: '€ ' + diferenciaNeta.toLocaleString('es-PE'),
                        }
                    });
                }else{
                    chartSerie = new ApexCharts(
                        document.querySelector("#chart_facturacion"),
                        optionsSerie
                    );
                    chartSerie.render();
                }
            })
        }
        function drawFacturacionBase(filtro){
            $.ajax({
                method: "POST",
                url: "<?php echo ENLACE_WEB; ?>mod_tpl/ajax/dashboard.ajax.php",
                beforeSend: function(xhr) {
                    $('#verifactum_chart').html('Cargando...')
                    $('#borrador_chart').html('Cargando...')
                },
                data: {
                    action: 'devolver_datos_dashboard_series_base_iva',
                    filtro:(filtro && filtro!=''?filtro:'mensual')
                },
            }).done(function(data) {
                $('#facturacion_periodo_base').text( (filtro && filtro!=''?filtro.toUpperCase():'MENSUAL'))

                const response = JSON.parse(data)
                
                const periodos = Object.keys(response.mensaje.totales_ventas);
                // Obtener los valores de ventas y a_cobrar en arrays
                const ventasSerie = periodos.map(periodo => parseInt(response.mensaje.totales_ventas[periodo].ventas.toString().replace(/,/g, '')) || 0);
                const ivaSerie = periodos.map(periodo => parseInt(response.mensaje.totales_ventas[periodo].iva.toString().replace(/,/g, '')) || 0);

                // Calcular la diferencia total
                let diferenciaNeta = Object.keys(response.mensaje.totales_ventas).reduce((total, year) => {
                    const ventas = parseFloat(response.mensaje.totales_ventas[year].ventas.toString().replace(/,/g, '')) || 0;
                    const iva = parseFloat(response.mensaje.totales_ventas[year].iva.toString().replace(/,/g, '')) || 0;
                    console.log(ventas, iva)
                    // Verificar que las ventas no sean cero antes de realizar la división
                    if (ventas !== 0) {
                        return total + (iva * 100 / ventas);
                    } else {
                        return total; // Si las ventas son cero, simplemente sumar el total sin cambios
                    }
                }, 0);



                const optionsSerie = {
                    chart: {
                        fontFamily: 'Nunito, sans-serif',
                        height: 365,
                        type: 'area',
                        zoom: {
                            enabled: true
                        },
                        dropShadow: {
                            enabled: true,
                            opacity: 0.2,
                            blur: 10,
                            left: -7,
                            top: 22
                        },
                        toolbar: {
                            show: false
                        },
                    },
                    colors: ['#e7515a', '#2196f3'],
                    dataLabels: {
                        enabled: false
                    },
                    markers: {
                        discrete: [{
                            seriesIndex: 0,
                            dataPointIndex: 7,
                            fillColor: '#000',
                            strokeColor: '#000',
                            size: 5
                        }, {
                            seriesIndex: 2,
                            dataPointIndex: 11,
                            fillColor: '#000',
                            strokeColor: '#000',
                            size: 4
                        }]
                    },
                    subtitle: {
                        text: diferenciaNeta.toLocaleString('es-PE')+' %',
                        align: 'left',
                        margin: 0,
                        offsetX: 160,
                        offsetY: 20,
                        floating: false,
                        style: {
                            fontSize: '18px',
                            color: '#00ab55'
                        }
                    },
                    title: {
                        text: 'Total IVA vs Base',
                        align: 'left',
                        margin: 0,
                        offsetX: -10,
                        offsetY: 20,
                        floating: false,
                        style: {
                            fontSize: '18px',
                            color: '#bfc9d4'
                        },
                    },
                    stroke: {
                        show: true,
                        curve: 'smooth',
                        width: 2,
                        lineCap: 'square'
                    },
                    series: [{
                        name: 'IVAs',
                        data: ivaSerie
                    },{
                        name: 'Base',
                        data: ventasSerie
                    }],
                    labels: periodos,
                    xaxis: {
                        axisBorder: {
                            show: false
                        },
                        axisTicks: {
                            show: false
                        },
                        crosshairs: {
                            show: true
                        },
                        labels: {
                            offsetX: 0,
                            offsetY: 5,
                            rotate: -45, // Añadido para rotar las etiquetas 45°
                            rotateAlways: true, // Asegurar que siempre estén rotadas
                            style: {
                                fontSize: '12px',
                                fontFamily: 'Nunito, sans-serif',
                                cssClass: 'apexcharts-xaxis-title',
                            },
                        }
                    },
                    yaxis: {
                        labels: {
                            formatter: function(value, index) {
                                return (value / 1000) + 'K'
                            },
                            offsetX: -15,
                            offsetY: 0,
                            style: {
                                fontSize: '12px',
                                fontFamily: 'Nunito, sans-serif',
                                cssClass: 'apexcharts-yaxis-title',
                            },
                        }
                    },
                    grid: {
                        borderColor: '#191e3a',
                        strokeDashArray: 5,
                        xaxis: {
                            lines: {
                                show: true
                            }
                        },
                        yaxis: {
                            lines: {
                                show: false,
                            }
                        },
                        padding: {
                            top: -50,
                            right: 0,
                            bottom: 0,
                            left: 5
                        },
                    },
                    legend: {
                        position: 'top',
                        horizontalAlign: 'right',
                        offsetY: -50,
                        fontSize: '16px',
                        fontFamily: 'Quicksand, sans-serif',
                        markers: {
                            width: 10,
                            height: 10,
                            strokeWidth: 0,
                            strokeColor: '#fff',
                            fillColors: undefined,
                            radius: 12,
                            onClick: undefined,
                            offsetX: -5,
                            offsetY: 0
                        },
                        itemMargin: {
                            horizontal: 10,
                            vertical: 20
                        }

                    },
                    tooltip: {
                        theme: 'dark',
                        marker: {
                            show: true,
                        },
                        x: {
                            show: false,
                        },
                        y: {
                            formatter: function(value, series) {
                                // use series argument to pull original string from chart data
                                return '€ ' + value.toLocaleString('es-PE');
                            }
                        }
                    },
                    fill: {
                        type: "gradient",
                        gradient: {
                            type: "vertical",
                            shadeIntensity: 1,
                            inverseColors: !1,
                            opacityFrom: .19,
                            opacityTo: .05,
                            stops: [100, 100]
                        }
                    },
                    responsive: [{
                        breakpoint: 575,
                        options: {
                            legend: {
                                offsetY: -50,
                            },
                        },
                    }]
                }

                if(chartSerie_Base){
                    // Actualizar gráfico existente
                    chartSerie_Base.updateOptions({
                        series: [{
                            name: 'IVAs',
                            data: ivaSerie
                        },{
                        name: 'Base',
                        data: ventasSerie
                        }],
                        xaxis: {
                            categories: periodos
                        },
                        labels: periodos,
                        subtitle: {
                            text: diferenciaNeta.toFixed(2) +' %'
                        }
                    });
                }else{
                    chartSerie_Base = new ApexCharts(
                        document.querySelector("#chart_facturacion_base"),
                        optionsSerie
                    );
                    chartSerie_Base.render();
                }
            })
        }
        function drawLogActividad(){
            $.ajax({
                method: "POST",
                url: "<?php echo ENLACE_WEB; ?>mod_tpl/ajax/dashboard.ajax.php",
                beforeSend: function(xhr) {
                },
                data: {
                    action: 'devolver_datos_dashboard_log'
                },
            }).done(function(data) {
                const response = JSON.parse(data)
                let html_log_complete = '';
                response.mensaje.forEach(element => {
                    const html_log_item = `
                        <div class="item-timeline timeline-new">
                            <div class="t-dot">
                                <div class="t-${element.clase=='login'?'success':(element.clase=='logout'?'warning':'danger')}">
                                    <i class="fa-solid fa-${element.clase=='login'?'link':(element.clase=='logout'?'link-slash':'trash-can')}" style="color: white;padding: 4px;"></i>
                                </div>
                            </div>
                            <div class="t-content">
                                <div class="t-uppercontent">
                                    <h5>${element.usuario} : <a href="javscript:void(0);"><span style="color:#009688">${element.clase=='login'?'inició sesión':(element.clase=='logout'?'cerró sesión':element.mensaje)}</span></a></h5>
                                </div>
                                <p>${element.fecha}</p>
                            </div>
                        </div>
                    `
                    html_log_complete += html_log_item;                    
                });
                $('#timeline-line-log').html(html_log_complete)
            })
        }
        function drawSellingProducts(){
            $.ajax({
                method: "POST",
                url: "<?php echo ENLACE_WEB; ?>mod_tpl/ajax/dashboard.ajax.php",
                beforeSend: function(xhr) {
                },
                data: {
                    action: 'devolver_productos_mas_vendidos'
                },
            }).done(function(data) {
                const response = JSON.parse(data)

                let html_productos_complete = '';
                response.mensaje.forEach(element => {
                    const html_item_venta = `
                        <tr onclick="window.location ='<?php echo ENLACE_WEB; ?>productos_editar/${element.rowid}';">
                            <td>
                                <div class="td-content product-name">
                                    <img src="<?php echo ENLACE_WEB; ?>bootstrap/assets/img/${element.tipo=='Producto'?'product':'service'}.svg" alt="${element.tipo=='Producto'?'product':'service'}">
                                    <div class="align-self-center">
                                        <p class="prd-name">${element.nombre}</p>
                                        <p class="prd-category text-${element.tipo=='Producto'?'primary':'secondary'}">${element.tipo}</p>
                                    </div>
                                </div>
                            </td>                            
                            <td>
                                <div class="td-content">${element.stock??0}</div>
                            </td>
                            <td>
                                <div class="td-content">
                                    <span class="pricing">${parseInt(element.producto_precio)}</span>
                                </div>
                            </td>
                            <td>
                                <div class="td-content">
                                    <span class="discount-pricing">${element.producto_cantidad}</span>
                                </div>
                            </td>
                            <td>
                                <div class="td-content">${parseInt(element.producto_venta)}</div>
                            </td>
                        </tr>
                    `
                    html_productos_complete += html_item_venta;
                })

                $('#draw_productos_ventas tbody').html(html_productos_complete)
            })
        }
        function drawUltimasFacturas(){
            $.ajax({
                method: "POST",
                url: "<?php echo ENLACE_WEB; ?>mod_tpl/ajax/dashboard.ajax.php",
                beforeSend: function(xhr) {
                },
                data: {
                    action: 'devolver_datos_dashboard_ultimas_facturas'
                },
            }).done(function(data) {
                const response = JSON.parse(data)

                let html_lastfactures_complete = '';
                response.mensaje.forEach(element => {
                    const html_last_item = `

                        <div class="item-timeline timeline-new" style="cursor:pointer;" onclick="window.location='<?php echo ENLACE_WEB; ?>factura/${element.factura_id}';">
                            <div class="t-dot">
                                <div class="w-img">
                                    <i class="fa-solid fa-${element.numero_serie.includes('S')?'s':'f' }" style="padding: 4px;" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="t-content">
                                <div class="t-uppercontent">
                                    <h5>${element.numero_serie} : <a href="javscript:void(0);"><span style="color:#009688">${element.tiene_descuento==1?'Con descuento':'Sin descuento'}</span></a></h5>
                                </div>
                                <p>${element.cliente_nombre}</p>
                                <p>${element.fecha}</p>
                            </div>
                        </div>
                    `
                    html_lastfactures_complete += html_last_item;
                });
                $('#timeline-line-factura').html(html_lastfactures_complete)
            })
        }

        $(document).ready(function() {
            // Se quita para después: drawVerifactumChart();
            drawFacturacion();
            drawEstadosChart();
            drawFacturacionBase();
            drawUltimasFacturas();
            drawLogActividad();
            drawSellingProducts();
        })
    </script>