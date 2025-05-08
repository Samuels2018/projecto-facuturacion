<?php 

// Variables dinámicas (reemplaza estos valores con tus consultas reales)
$monto_proyecto = floatval($Proyectos->monto);  // Monto del proyecto
      // Total acumulado de gastos

// Cálculos
$total_ingresos = $total_factura;
$total_egresos = $total_compras + $total_gasto;
$utilidad = $total_ingresos - $total_egresos;

// Calcular rentabilidad (%)
$rentabilidad = ($monto_proyecto > 0) ? (($utilidad / $monto_proyecto) * 100) : 0;

// Verifica si hay un usuario autenticado
if (!isset($_SESSION['usuario'])) {
    echo json_encode(['success' => false, 'message' => 'Acceso inválido']);
    exit;
}

?>

<div class="container mt-4">
    <div class="row">
        <!-- Columna de Ingresos y Egresos -->
        <div class="col-md-4">
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary d-flex align-items-center">
                    <i class="fas fa-chart-line me-2"></i>
                    <h5 class="card-title text-white mb-0">Resumen del Proyecto</h5>
                </div>
                <div class="card-body">
                    <p class="d-flex justify-content-between">
                        <span><i class="fas fa-coins me-1"></i><strong>Total del Proyecto:</strong></span>
                        <span class="badge bg-info text-end" style="font-size: 1.3em;"><?php echo number_format($monto_proyecto, 2); ?></span>
                    </p>
                    <p class="d-flex justify-content-between">
                        <span><i class="fas fa-file-invoice-dollar me-1"></i><strong>Total de Ingresos (Facturas):</strong></span>
                        <span class="badge bg-success text-end" style="font-size: 1.3em;"><?php echo number_format($total_ingresos, 2); ?></span>
                    </p>
                    <p class="d-flex justify-content-between">
                        <span><i class="fas fa-shopping-cart me-1"></i><strong>Total de Compras:</strong></span>
                        <span class="badge bg-danger text-end" style="font-size: 1.3em;"><?php echo number_format($total_compras, 2); ?></span>
                    </p>
                    <p class="d-flex justify-content-between">
                        <span><i class="fas fa-money-bill me-1"></i><strong>Total de Gastos:</strong></span>
                        <span class="badge bg-warning text-end" style="font-size: 1.3em;"><?php echo number_format($total_gasto, 2); ?></span>
                    </p>
                    <p class="d-flex justify-content-between">
                        <span><i class="fas fa-balance-scale me-1"></i><strong>Utilidad del Proyecto:</strong></span>
                        <span class="badge bg-primary text-end" style="font-size: 1.3em;"><?php echo number_format($utilidad, 2); ?></span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Columna de Rentabilidad -->
        <div class="col-md-4">
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-secondary d-flex align-items-center">
                    <i class="fas fa-percentage me-2"></i>
                    <h5 class="card-title text-white mb-0">Rentabilidad del Proyecto</h5>
                </div>
                <div class="card-body">
                    <p class="d-flex justify-content-between">
                        <span><i class="fas fa-chart-pie me-1"></i><strong>Rentabilidad (%):</strong></span>
                        <span class="badge bg-warning text-end" style="font-size: 1.3em;"><?php echo number_format($rentabilidad, 2); ?>%</span>
                    </p>
                    <p class="text-muted" style="font-size: 0.9em;">
                        <i class="fas fa-info-circle me-1"></i>La rentabilidad se calcula utilizando la fórmula:
                    </p>
                    <code style="font-size: 0.85em;">((Utilidad del Proyecto) / Total del Proyecto) * 100</code>
                </div>
            </div>
        </div>

        <!-- Columna de Gráfico -->
        <div class="col-md-4">
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-success d-flex align-items-center">
                    <i class="fas fa-chart-pie me-2"></i>
                    <h5 class="card-title text-white mb-0">Gráfico de Rentabilidad</h5>
                </div>
                <div class="card-body">
                    <div id="rentabilidadChart"></div>
                    <p class="text-center mt-3">
                        <strong><?php echo number_format($rentabilidad, 2); ?>%</strong> Rentabilidad
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Función para inicializar el gráfico de rentabilidad
    function cargarGraficoRentabilidad() {
        // Convertir rentabilidad a número flotante
        const rentabilidad = parseFloat('<?php echo number_format($rentabilidad, 2); ?>');

        // Datos para el gráfico
        const options = {
            chart: {
                type: 'donut',
                height: 300
            },
            series: [rentabilidad, 100 - rentabilidad],
            labels: ['Rentabilidad (%)', 'Resto (%)'],
            colors: ['#28a745', '#e9ecef'],
            legend: {
                position: 'bottom'
            }
        };

        // Renderizar el gráfico en el div con ID 'rentabilidadChart'
        const chart = new ApexCharts(document.querySelector("#rentabilidadChart"), options);
        chart.render();
    }

    // Llamar a la función para inicializar el gráfico
    cargarGraficoRentabilidad();
</script>
