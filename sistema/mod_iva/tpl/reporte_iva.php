<?php

 
?>



<div class="middle-content container-xxl p-0">

  <!-- BREADCRUMB -->
  <div class="page-meta">
    <nav class="breadcrumb-style-one" aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Reportes</a></li>
        <li class="breadcrumb-item active" aria-current="page">Informes IVA</li>
      </ol>
    </nav>
  </div>
  <!-- /BREADCRUMB -->

  <div class="account-settings-container layout-top-spacing">

    <div class="account-content">

      <div class="row">
        <div class="col-md-3">
          <div class="card">
            <div class="card-header bg-white">
              <h5 class="mb-1">Exportar Reporte a Excel</h5>
              <small class="text-muted">Informe de Facturas por Fechas.</small>
            </div>
            <div class="card-body">
              <!-- START DATE -->
              <div class="mb-3">
                <label for="inicio" class="form-label">Fecha Inicio</label>
                <input name="inicio" id="inicio" value="15-11-2024" class="form-control">
              </div>
              <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
              <!-- SCRIPT -->
              <script>
                $(function() {
                  $("#inicio").datepicker({
                    dateFormat: "dd-mm-yy",
                    showButtonPanel: true,
                    closeText: "Cerrar",
                    prevText: "<Ant",
                    nextText: "Sig>",
                    currentText: "Hoy",
                    monthNames: [
                      "Enero",
                      "Febrero",
                      "Marzo",
                      "Abril",
                      "Mayo",
                      "Junio",
                      "Julio",
                      "Agosto",
                      "Septiembre",
                      "Octubre",
                      "Noviembre",
                      "Diciembre",
                    ],
                    monthNamesShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
                    dayNames: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
                    dayNamesShort: ["Dom", "Lun", "Mar", "Mié", "Juv", "Vie", "Sáb"],
                    dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sá"],
                    weekHeader: "Sm",
                    firstDay: 1,
                    isRTL: false,
                    showMonthAfterYear: false,
                    yearSuffix: "",
                  });
                });
              </script>
              <!-- END DATE -->
              <div class="mb-3">
                <label for="fin" class="form-label">Fecha Final</label>
                <input name="fin" id="fin" class="form-control" value="15-11-2024">
              </div>
              <!-- SCRIPT -->
              <script>
                $(function() {
                  $("#fin").datepicker({
                    dateFormat: "dd-mm-yy",
                    showButtonPanel: true,
                    closeText: "Cerrar",
                    prevText: "<Ant",
                    nextText: "Sig>",
                    currentText: "Hoy",
                    monthNames: [
                      "Enero",
                      "Febrero",
                      "Marzo",
                      "Abril",
                      "Mayo",
                      "Junio",
                      "Julio",
                      "Agosto",
                      "Septiembre",
                      "Octubre",
                      "Noviembre",
                      "Diciembre",
                    ],
                    monthNamesShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
                    dayNames: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
                    dayNamesShort: ["Dom", "Lun", "Mar", "Mié", "Juv", "Vie", "Sáb"],
                    dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sá"],
                    weekHeader: "Sm",
                    firstDay: 1,
                    isRTL: false,
                    showMonthAfterYear: false,
                    yearSuffix: "",
                  });
                });
              </script>

              <!-- Moneda -->
              <hr>
           

              <!-- BUTTONS -->
              <div class="d-flex gap-2">
                <input type="submit" class="btn btn-info btn-sm" value="Exportar Excel">
                <a href="#" onclick="cargarVistaPrevia()" class="btn btn-secondary btn-sm">Vista Previa</a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-9" style="overflow:auto;">
          <div class="card">
            <div class="card-header bg-white">
              <h5 class="mb-1">Vista Preliminar</h5>
              <small class="text-muted">Se mostrará los datos relevantes del informe.</small>
            </div>
            <div id="informeVistaPrevia" style="width:100%; overflow:auto;" class="card-body">
            </div>
          </div>
        </div>
      </div>


    </div>
  </div>
</div>



<script>
  $(document).ready(function() {

    // Desactivar todos los elementos del menú
    $(".menu").removeClass('active');

    $(".reportes").addClass('active');
    $(".reportes > .submenu").addClass('show');
    $("#reportes_iva").addClass('active');




  });
</script>