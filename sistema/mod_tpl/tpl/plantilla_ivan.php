<!--  BEGIN CONTENT AREA  -->
<!--
<div id="content" class="main-content">
            <div class="layout-px-spacing"> 
-->
<?php

require_once ENLACE_SERVIDOR . 'mod_crm/object/oportunidad.object.php';
$Oportunidad = new Oportunidad($dbh, $_SESSION['Entidad']);
$data_oportunidad = $Oportunidad->obtener_oportunidades_futuras_monto();
$listado_funnels = $Oportunidad->obtener_listado_funnels();
//EJECUTAMOS UN FUNNEL POR DEFECTO
$Oportunidad->funnel_por_defecto();
//obtenemos los estados de un funnel
$estados_funnel_detalle = $Oportunidad->obtener_listado_estados_funnel_detalle();
$datos_chart_funnel = $Oportunidad->obtener_lista_usuarios_por_funnel();


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
            <div class="widget widget-chart-one">
                <div class="widget-heading">
                    <h5 class="">Facturación</h5>
                    <h6>Ventas vs Ingresos</h6>
                    <span id="facturacion_periodo">MENSUAL</span>
                    <div class="task-action">
                        <div class="dropdown">
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
            <div class="widget widget-chart-two">
                <div class="widget-heading">
                    <h5 class="">Verifactum</h5>
                </div>
                <div class="widget-content">
                    <div id="chart_verifactum" class="" style="min-height: 371.05px;"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Sección de Facturación: Base vs IVA's
    Sección de Verifactum -->
    <div class="row layout-top-spacing">

        <div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-activity-five">
                <div class="widget-heading">
                    <h5 class="">Log de Actividad</h5>
                    <div class="text-primary">Últimos 10 registros</div>
                </div>

                <div class="widget-content">
                    <div class="mt-container mx-auto ps ps--active-y">
                        <div id="timeline-line-log" class="timeline-line">
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
                    <h5 class="">Facturación</h5>
                    <h6>Base vs IVA's</h6>
                    <span id="facturacion_periodo_base">MENSUAL</span>
                    <div class="task-action">
                        <div class="dropdown">
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
            <div class="widget widget-table-three">
        
                <div class="widget-heading">
                    <h5 class="">Productos más vendidos</h5>
                </div>
<a href="#" onclick="drawLogSellingProducts()">Ver vendidos</a>
                <div class="widget-content">
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
                                <!-- <tr>
                                    <td>
                                        <div class="td-content product-name">
                                            
                                            <div class="align-self-center">
                                                <p class="prd-name">Headphone</p>
                                                <p class="prd-category text-primary">Digital</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="td-content">
                                            <span class="pricing">$168.09</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="td-content">
                                            <span class="discount-pricing">$60.09</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="td-content">170</div>
                                    </td>
                                    <td>
                                        <div class="td-content">
                                            <a href="javascript:void(0);" class="text-danger"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-danger feather feather-chevrons-right"><polyline points="13 17 18 12 13 7"></polyline><polyline points="6 17 11 12 6 7"></polyline></svg> Direct</a>
                                        </div>
                                    </td>
                                </tr> -->
                                <!-- <tr>
                                    <td><div class="td-content product-name"><img src="<?php echo ENLACE_WEB; ?>bootstrap/assets/img/service.svg" alt="product"><div class="align-self-center"><p class="prd-name">Shoes</p><p class="prd-category text-warning">Faishon</p></div></div></td>
                                    <td><div class="td-content"><span class="pricing">$108.09</span></div></td>
                                    <td><div class="td-content"><span class="discount-pricing">$47.09</span></div></td>
                                    <td><div class="td-content">130</div></td>
                                    <td><div class="td-content"><a href="javascript:void(0);" class="text-primary"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary feather feather-chevrons-right"><polyline points="13 17 18 12 13 7"></polyline><polyline points="6 17 11 12 6 7"></polyline></svg> Google</a></div></td>
                                </tr> -->
                                
                                
                                


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-activity-five">
                <div class="widget-heading">
                    <h5 class="">Últimas facturas</h5>
                    <div class="text-primary">Últimos 10 registros</div>
                </div>

                <div class="widget-content">
                    <div class="mt-container mx-auto ps ps--active-y">
                        <div id="timeline-factura" class="timeline-line">
                            <!-- El contenido se rellena por Ajax -->
                        </div>
                    </div>



                    <div class="w-shadow-bottom"></div>
                </div>
            </div>
        </div>

        

    </div>











    <!-- Sección de cuadros superiores -->
    <div class="row layout-top-spacing">

        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-card-five container_cards_dashboard">
                <div class="widget-content">
                    <div class="account-box">

                        <div class="info-box">
                            <div class="icon">
                                <span>
                                    <img src="<?php echo ENLACE_WEB; ?>bootstrap/assets/img/money-bag.png" alt="money-bag">
                                </span>
                            </div>

                            <div class="balance-info" style="z-index:999">
                                <div class="row text-center">
                                    <div class="col-md-3">
                                        <div class="row">
                                            <label for="funnelSelect">Periodo:</label>
                                            <select class="form-control" onchange="selectorPeriodo(this)">
                                                <option value="dia">Por Dias</option>
                                                <option value="mes" selected>Por Mes</option>
                                                <option value="anio">Por Año</option>
                                            </select>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="col-md-6" id="filtro_dia" style="display:none">
                                        <div class="row">
                                            <div class="col-6">
                                                <label for="fechaDesde">Fecha desde:</label>
                                                <input type="date" class="form-control hasDatepicker" id="select_filtro_fecha_desde">
                                            </div>
                                            <div class="col-6">
                                                <label for="fechaHasta">Fecha hasta:</label>
                                                <input type="date" class="form-control hasDatepicker" id="select_filtro_fecha_hasta">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6" id="filtro_mes" style="display:block">
                                        <div class="row">
                                            <div class="col-6">
                                                <label for="filtro_mes">Mes:</label>
                                                <select class="form-control" id="select_filtro_mes">
                                                    <option value="1" <?php echo (date('m') == 1 ? 'selected' : ''); ?>>Enero</option>
                                                    <option value="2" <?php echo (date('m') == 2 ? 'selected' : ''); ?>>Febrero</option>
                                                    <option value="3" <?php echo (date('m') == 3 ? 'selected' : ''); ?>>Marzo</option>
                                                    <option value="4" <?php echo (date('m') == 4 ? 'selected' : ''); ?>>Abril</option>
                                                    <option value="5" <?php echo (date('m') == 5 ? 'selected' : ''); ?>>Mayo</option>
                                                    <option value="6" <?php echo (date('m') == 6 ? 'selected' : ''); ?>>Junio</option>
                                                    <option value="7" <?php echo (date('m') == 7 ? 'selected' : ''); ?>>Julio</option>
                                                    <option value="8" <?php echo (date('m') == 8 ? 'selected' : ''); ?>>Agosto</option>
                                                    <option value="9" <?php echo (date('m') == 9 ? 'selected' : ''); ?>>Setiembre</option>
                                                    <option value="10" <?php echo (date('m') == 10 ? 'selected' : ''); ?>>Octubre</option>
                                                    <option value="11" <?php echo (date('m') == 11 ? 'selected' : ''); ?>>Noviembre</option>
                                                    <option value="12" <?php echo (date('m') == 12 ? 'selected' : ''); ?>>Diciembre</option>
                                                </select>
                                            </div>
                                            <div class="col-6">
                                                <label for="">Año:</label>
                                                <input type="number" class="form-control" id="select_filtro_anio" value="<?php echo date("Y"); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6" id="filtro_anio" style="display:none">
                                        <div class="row">
                                            <div class="col-12">
                                                <label for="fechaDesde">Año:</label>
                                                <input type="number" class="form-control" id="select_filtro_anio_solo" value="<?php echo date("Y"); ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <button class="btn btn-success" type="button" onclick="cargar_datos_dashboard()"><i class="fa-solid fa-rotate-right"></i></button>
                                    </div>

                                </div>
                                <div class="row text-center">
                                    <div class="col-xl-3 col-md-2">
                                        <div class="card card-dashboard">
                                            <div class="card-body widget widget-card-five card-content">
                                                <div class="card-title">VENTAS</div>
                                                <span id="filtro_ventas" class="font-weight-bold">0</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-2">
                                        <div class="card card-dashboard">
                                            <div class="card-body widget widget-card-five card-content">
                                                <div class="card-title">A COBRAR</div>
                                                <span id="filtro_a_cobrar" class="font-weight-bold">0</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-2">
                                        <div class="card card-dashboard">
                                            <div class="card-body widget widget-card-five card-content">
                                                <div class="card-title">VENCIDO</div>
                                                <span id="filtro_vencido" class="font-weight-bold">0</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-2">
                                        <div class="card card-dashboard">
                                            <div class="card-body widget widget-card-five card-content">
                                                <div class="card-title">% VENCIDO</div>
                                                <span id="filtro_ventas_porcentaje" class="font-weight-bold">0</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-card-five container_cards_dashboard">
                <div class="widget-content">
                    <div class="account-box">

                        <div class="info-box">
                            <div class="icon">
                                <span>
                                    <img src="<?php echo ENLACE_WEB; ?>bootstrap/assets/img/product-18.jpg" alt="verifactum-bag">
                                </span>
                            </div>
                        </div>
                        <div class="balance-info" style="z-index:999">
                            <div class="row text-center">
                                <div class="col-md-3">
                                    <button class="btn btn-success" type="button" onclick="drawVerifactumChart()"><i class="fa-solid fa-rotate-right"></i></button>
                                </div>

                            </div>
                            <div class="row text-center">
                                <div class="col-xl-6 col-md-2">
                                    <div class="card card-dashboard" style="height:180px;">
                                        <div class="card-body widget widget-card-five card-content" style="padding-top:5px;">
                                            <span><b>Facturas con y sin Verifactum</b></span>
                                            <div id="verifactum_chart"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-2">
                                    <div class="card card-dashboard" style="height:180px;">
                                        <div class="card-body widget widget-card-five card-content" style="padding-top:5px;">
                                            <span><b>Estado de las Facturas</b></span>
                                            <div id="borrador_chart"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>


                    </div>
                </div>
            </div>
        </div>

    </div>

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
    
    <script>
        function cargar_datos_dashboard() {
            if ($('#filtro_mes').css('display') == 'block') {
                $.post("<?php echo ENLACE_WEB; ?>mod_tpl/ajax/dashboard.ajax.php", {
                        action: 'devolver_datos_dashboard',
                        filtro_mes: $('#select_filtro_mes').val(),
                        filtro_anio: 2024
                    })
                    .done(function(data) {
                        const response = JSON.parse(data)
                        $('#filtro_vencido').text('0')
                        $('#filtro_ventas').text('0')
                        $('#filtro_ventas_porcentaje').text('0')
                        $('#filtro_a_cobrar').text('0')
                        if (response.exito) {
                            $('#filtro_vencido').text(response.mensaje.totales_ventas.vencido)
                            $('#filtro_ventas').text(response.mensaje.totales_ventas.ventas)
                            $('#filtro_ventas_porcentaje').text(response.mensaje.totales_ventas.vencido_porcentaje)
                            $('#filtro_a_cobrar').text(response.mensaje.totales_ventas.a_cobrar)
                        }
                    })
                    .catch((error) => {
                        console.log('error', error)
                    });
            }
            if ($('#filtro_dia').css('display') == 'block') {
                const fecha_desde = $('#select_filtro_fecha_desde').val()
                const fecha_hasta = $('#select_filtro_fecha_hasta').val()

                $('#select_filtro_fecha_desde').css('border-color', '#bfc9d4')
                $('#select_filtro_fecha_hasta').css('border-color', '#bfc9d4')

                if (fecha_desde == '') {
                    $('#select_filtro_fecha_desde').css('border-color', 'red');
                    return;
                }
                if (fecha_hasta == '') {
                    $('#select_filtro_fecha_hasta').css('border-color', 'red');
                    return;
                }

                $.post("<?php echo ENLACE_WEB; ?>mod_tpl/ajax/dashboard.ajax.php", {
                        action: 'devolver_datos_dashboard_fecha',
                        filtro_desde: $('#select_filtro_fecha_desde').val(),
                        filtro_hasta: $('#select_filtro_fecha_hasta').val()
                    })
                    .done(function(data) {
                        const response = JSON.parse(data)
                        $('#filtro_vencido').text('0')
                        $('#filtro_ventas').text('0')
                        $('#filtro_ventas_porcentaje').text('0')
                        $('#filtro_a_cobrar').text('0')
                        if (response.exito) {
                            $('#filtro_vencido').text(response.mensaje.totales_ventas.vencido)
                            $('#filtro_ventas').text(response.mensaje.totales_ventas.ventas)
                            $('#filtro_ventas_porcentaje').text(response.mensaje.totales_ventas.vencido_porcentaje)
                            $('#filtro_a_cobrar').text(response.mensaje.totales_ventas.a_cobrar)
                        }
                    })
                    .catch((error) => {
                        console.log('error', error)
                    });
            }
            if ($('#filtro_anio').css('display') == 'block') {
                const anio = $('#select_filtro_anio_solo').val()
                $.post("<?php echo ENLACE_WEB; ?>mod_tpl/ajax/dashboard.ajax.php", {
                        action: 'devolver_datos_dashboard_anio',
                        filtro_anio: $('#select_filtro_anio_solo').val(),
                    })
                    .done(function(data) {
                        const response = JSON.parse(data)
                        $('#filtro_vencido').text('0')
                        $('#filtro_ventas').text('0')
                        $('#filtro_ventas_porcentaje').text('0')
                        $('#filtro_a_cobrar').text('0')
                        if (response.exito) {
                            $('#filtro_vencido').text(response.mensaje.totales_ventas.vencido)
                            $('#filtro_ventas').text(response.mensaje.totales_ventas.ventas)
                            $('#filtro_ventas_porcentaje').text(response.mensaje.totales_ventas.vencido_porcentaje)
                            $('#filtro_a_cobrar').text(response.mensaje.totales_ventas.a_cobrar)
                        }
                    })
                    .catch((error) => {
                        console.log('error', error)
                    });
            }




        }

        function selectorPeriodo(element) {
            const value = $(element).val()
            $('#filtro_dia').css('display', 'none')
            $('#filtro_mes').css('display', 'none')
            $('#filtro_anio').css('display', 'none')
            $('#filtro_' + value).css('display', 'block')
        }

        $(document).ready(function() {
            // Desactivar todos los elementos del menú
            $(".menu").removeClass('active');
            $(".dashboard").addClass('active');
            $(".dashboard > .submenu").addClass('show');
            $("#dashboard").addClass('active');

            $("#fechaDesde").datepicker({
                dateFormat: "yy-mm-dd",
                onSelect: function(selectedDate) {
                    $("#fechaHasta").datepicker("option", "minDate", selectedDate);
                    mostrarValores();
                },
                // Configuración en español
                dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sá"],
                monthNames: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
                firstDay: 1
            });

            $("#fechaHasta").datepicker({
                dateFormat: "yy-mm-dd",
                onSelect: function(selectedDate) {
                    $("#fechaDesde").datepicker("option", "maxDate", selectedDate);
                    mostrarValores();
                },
                // Configuración en español
                dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sá"],
                monthNames: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
                firstDay: 1
            });

            function mostrarValores() {
                $(".ajax-grafico").remove();
                $("#formulario_filtros").after('<div class="ajax-grafico alert alert-success" role="alert">Refrescando Grafico</div>');

                var fechaDesde = $("#fechaDesde").val();
                var fechaHasta = $("#fechaHasta").val();
                var fk_funnel = $("#funnelSelect").val();


                $.post("<?php echo ENLACE_WEB; ?>mod_tpl/ajax/oportunidades.ajax.php", {
                        fechaDesde: fechaDesde,
                        fechaHasta: fechaHasta,
                        fk_funnel: fk_funnel,
                    })
                    .done(function(data) {
                        $(".ajax-grafico").remove();
                        $("#contenedor_filtro_chart_ajax").html(data);
                    });
            }

            // cargar_datos_dashboard()
        });
    </script>
    
    <script src="<?php echo ENLACE_WEB; ?>bootstrap/assets/js/dashboard/dash_1.js"></script>



    <script type="text/javascript">
        // google.charts.load('current', {
        //     'packages': ['corechart', 'timeline']
        // });
        // google.charts.setOnLoadCallback(drawCharts);

        function drawVendedorChart() {
            var data = google.visualization.arrayToDataTable([
                ['Prospectos', 'Cotizaciones'],
                ['Prospectos', <?php echo $Oportunidad->obtener_total_oportunidades_por_tipo(1)[0]->cantidad_oportunidades; ?>],
                ['Cotizado', <?php echo $Oportunidad->obtener_total_oportunidades_por_tipo(2)[0]->cantidad_oportunidades; ?>],
                ['Orden de compra', <?php echo $Oportunidad->obtener_total_oportunidades_por_tipo(3)[0]->cantidad_oportunidades; ?>],
                ['Facturados', <?php echo $Oportunidad->obtener_total_oportunidades_por_tipo(4)[0]->cantidad_oportunidades; ?>]
            ]);

            var options = {
                width: '100%', // Ancho del gráfico (ajústalo según tus preferencias)
                height: 400, // Alto del gráfico (ajústalo según tus preferencias)
                chartArea: {
                    left: 10,
                    top: 50,
                    width: '100%',
                    height: '100%'
                }, // Sin relleno
                legend: {
                    position: 'top',
                    maxLines: 2
                }, // Mueve la leyenda arriba y muestra en 2 columna
            };

            var chart = new google.visualization.PieChart(document.getElementById('vendedor_chart'));
            chart.draw(data, options);
        }

        function drawCharts() {
            drawVendedorChart();
        }
    </script>


    <script type="text/javascript">
        // google.charts.load('current', {
        //     'packages': ['corechart']
        // });
        // google.charts.setOnLoadCallback(drawChart2);

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

                const periodos = Object.keys(response.mensaje.totales_ventas).map(Number).sort((a, b) => b - a);
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

                console.log(ventasSerie)
                console.log(aCobrarSerie)

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
                        text: diferenciaNeta.toLocaleString('es-PE'),
                        align: 'left',
                        margin: 0,
                        offsetX: 100,
                        offsetY: 20,
                        floating: false,
                        style: {
                            fontSize: '18px',
                            color: '#00ab55'
                        }
                    },
                    title: {
                        text: 'Total Neto ',
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
                        name: 'Ventas',
                        data: aCobrarSerie
                    },{
                        name: 'Ingresos',
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
                                return value.toLocaleString('es-PE');
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
                            name: 'Ventas',
                            data: aCobrarSerie
                        },{
                        name: 'Ingresos',
                        data: ventasSerie
                        }],
                        xaxis: {
                            categories: periodos
                        },
                        labels: periodos,
                        subtitle: {
                            text: diferenciaNeta.toFixed(2)
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
                console.log(response.mensaje)
                const periodos = Object.keys(response.mensaje.totales_ventas).map(Number).sort((a, b) => b - a);
                // Obtener los valores de ventas y a_cobrar en arrays
                const ventasSerie = periodos.map(periodo => parseInt(response.mensaje.totales_ventas[periodo].ventas.toString().replace(/,/g, '')) || 0);
                const ivaSerie = periodos.map(periodo => parseInt(response.mensaje.totales_ventas[periodo].iva.toString().replace(/,/g, '')) || 0);

                // Calcular la diferencia total
                let diferenciaNeta = Object.keys(response.mensaje.totales_ventas).reduce((total, year) => {
                    const ventas = parseInt(response.mensaje.totales_ventas[year].ventas.toString().replace(/,/g, '')) || 0;
                    const iva = parseInt(response.mensaje.totales_ventas[year].iva.toString().replace(/,/g, '')) || 0;
                    // Sumar la diferencia entre ventas y a_cobrar al total
                    return total + (ventas - iva);
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
                        text: diferenciaNeta.toLocaleString('es-PE'),
                        align: 'left',
                        margin: 0,
                        offsetX: 100,
                        offsetY: 20,
                        floating: false,
                        style: {
                            fontSize: '18px',
                            color: '#00ab55'
                        }
                    },
                    title: {
                        text: 'Total Neto ',
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
                        name: 'Base',
                        data: ivaSerie
                    },{
                        name: 'IVAs',
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
                                return value.toLocaleString('es-PE');
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
                            name: 'Base',
                            data: ivaSerie
                        },{
                        name: 'IVAs',
                        data: ventasSerie
                        }],
                        xaxis: {
                            categories: periodos
                        },
                        labels: periodos,
                        subtitle: {
                            text: diferenciaNeta.toFixed(2)
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
                                    <h5>${element.clase=='login'?'Usuario':(element.clase=='logout'?'Usuario':'Registro')} : <a href="javscript:void(0);"><span style="color:#009688">${element.clase=='login'?'Conectado':(element.clase=='logout'?'Desconectado':'Eliminado')}</span></a></h5>
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
                        <tr>
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
                            <td>
                                <div class="td-content">
                                    <a href="javascript:void(0);" class="text-danger"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-danger feather feather-chevrons-right"><polyline points="13 17 18 12 13 7"></polyline><polyline points="6 17 11 12 6 7"></polyline></svg> Direct</a>
                                </div>
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

                let html_log_complete = '';
                response.mensaje.forEach(element => {
                    const html_log_item = `
                        <div class="item-timeline timeline-new">
                            <div class="t-dot">
                                <div class="t-primary">
                                    <img src="<?php echo ENLACE_WEB; ?>/assets/img/${element.cliente_avatar}" alt="avatar">
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
                    html_log_complete += html_log_item;                    
                });
                $('#timeline-factura').html(html_log_complete)
            })
        }

        $(document).ready(function() {
            // drawFacturacion();
            // drawVerifactumChart();
            // drawFacturacionBase();
            // drawLogActividad();
            // drawSellingProducts();
            drawUltimasFacturas();
        })
    </script>