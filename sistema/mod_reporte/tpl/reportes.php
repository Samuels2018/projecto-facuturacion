<div class="middle-content container-xxl p-0">
     <!-- BREADCRUMB -->
    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo ENLACE_WEB; ?>">Inicio </a></li>
                <li class="breadcrumb-item active" aria-current="page">Reportes</li>
            </ol>
        </nav>
    </div>
    <!-- /BREADCRUMB -->
    

    <div class="content"  >
        <div class="row mt-5">
            <div class="col-6 col-md-4 mb-4">
                <div class="card card-dashboard card-reports" style="height: 250px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); overflow: hidden;">
                    <div class="card-body">
                        
                        <h6 class="card-title"><i class="fas fa-cogs"></i> General</h6>
                        <ul class="card-report-links" style="max-height: 150px; overflow-y: auto;">
                            <li><a href="<?php echo ENLACE_WEB; ?>dashboard.php?accion=mod_reporte_1_descarga_documentos">🚀 Descarga documentos</a></li>
                            
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-4 mb-4">
                <div class="card card-dashboard card-reports" style="height: 250px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); overflow: hidden;">
                    <div class="card-body">
                        <h6 class="card-title"><i class="fas fa-chart-line" ></i> Ventas</h6>
                        <ul class="card-report-links" style="max-height: 150px; overflow-y: auto;">
                            <li><a href="<?php echo ENLACE_WEB; ?>dashboard.php?accion=mod_reporte_1_descarga_documentos&tipo=fi_europa_facturas">🚀 Facturas</a></li>
                            <li><a href="<?php echo ENLACE_WEB; ?>dashboard.php?accion=mod_reporte_clientes">🚀 Clientes</a></li>
                            
                        </ul>
                    </div>
                </div>
            </div>


            <div class="col-6 col-md-4 mb-4">
                <div class="card card-dashboard card-reports" style="height: 250px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); overflow: hidden;">
                    <div class="card-body">
                        <h6 class="card-title"><i class="fas fa-shopping-cart"  ></i>  Compras</h6>
                        <ul class="card-report-links" style="max-height: 150px; overflow-y: auto;">
                        <li><a href="<?php echo ENLACE_WEB; ?>dashboard.php?accion=mod_reporte_1_descarga_documentos&tipo=fi_europa_compras">🚀 Compras</a></li>
                        </ul>
                    </div>
                </div>
            </div>
          
            <div class="col-6 col-md-4 mb-4">
                <div class="card card-dashboard card-reports" style="height: 250px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); overflow: hidden;">
                    <div class="card-body">
                        <i class="fas fa-dollar-sign" ></i>
                        <h6 class="card-title">Ventas/Comisiones</h6>
                        <ul class="card-report-links" style="max-height: 150px; overflow-y: auto;">
                            <li><a href="#">🔜 Utilidad ventas</a></li>
                            <li><a href="#">🔜 pÃ©rdidas y ganancias </a></li>
                            <li><a href="#">🔜 Libros de registro 
                                <small>
                                🔜 https://sede.agenciatributaria.gob.es/Sede/iva/facturacion-registro/ejemplos-libros-registro-iva-irpf-2023.html

                                </small>
                            </a></li>
                            
                         
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 mb-4">
                <div class="card card-dashboard card-reports" style="height: 250px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); overflow: hidden;">
                    <div class="card-body">
                        
                        <h6 class="card-title"><i class="fas fa-box" ></i> Impuestos</h6>
                        <ul class="card-report-links" style="max-height: 150px; overflow-y: auto;">
                        <li><a href="<?php echo ENLACE_WEB; ?>dashboard.php?accion=mod_reporte_3_descarga_documentos&tipo=fi_europa_facturas">🚀 General IVA  </a></li>
                        <li><a href="<?php echo ENLACE_WEB; ?>dashboard.php?accion=mod_reporte_2_descarga_documentos&tipo=fi_europa_facturas">🚀 Retenciones </a></li>
                            </ul>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 mb-4">
                <div class="card card-dashboard card-reports" style="height: 250px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); overflow: hidden;">
                    <div class="card-body">
                        <h6 class="card-title"><i class="fas fa-truck" style="color:#53C68C;" ></i>Otras Transacciones</h6>
                        <ul class="card-report-links" style="max-height: 150px; overflow-y: auto;">
                        <li><a href="<?php echo ENLACE_WEB; ?>dashboard.php?accion=mod_reporte_1_descarga_documentos&tipo=fi_europa_albaranes_compras">🚀 Albarenes Compra</a></li>
                        <li><a href="<?php echo ENLACE_WEB; ?>dashboard.php?accion=mod_reporte_1_descarga_documentos&tipo=fi_europa_cotizaciones">🚀 Presupuestos</a></li>
                        <li><a href="<?php echo ENLACE_WEB; ?>dashboard.php?accion=mod_reporte_1_descarga_documentos&tipo=fi_europa_pedidos">🚀 Pedidos</a></li>
                        <li><a href="<?php echo ENLACE_WEB; ?>dashboard.php?accion=mod_reporte_1_descarga_documentos&tipo=fi_europa_albaranes_ventas">🚀 Albarenes de Venta</a></li>

                    </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
     $(document).ready(function() {
        // Desactivar todos los elementos del menÃº
        $(".menu").removeClass('active');

        $(".reportes").addClass('active');
        $(".reportes > .submenu").addClass('show');
        $("#reportes_iva").addClass('active');
     })
</script>