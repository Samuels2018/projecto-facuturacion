<?php

$Utilidades->obtener_diccionario_transacciones_documentos();
$tipo = $_REQUEST['tipo'];

$fecha = $Utilidades->obtenerRangoTrimestre();

?>



<div class="layout-px-spacing">

  <!-- BREADCRUMB -->
  <div class="page-meta">
    <nav class="breadcrumb-style-one" aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Reportes</a></li>
        <li class="breadcrumb-item active" aria-current="page">Clientes </li>
      </ol>
    </nav>
  </div>
  <!-- /BREADCRUMB -->

  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

  <form role="form" action="<?php echo ENLACE_WEB; ?>mod_reporte_clientes/json/reporte.json.php" method="POST">

    <input type="hidden" value="1" name="exportar">

    <div class="account-settings-container layout-top-spacing">

      <div class="account-content">

        <div class="row">
          <div class="col-md-3">
            <div class="card">
              <div class="card-header bg-white">
                <h5 class="mb-1">Exportar Reporte a Excel</h5>
                <small class="text-muted">Informe General</small>
              </div>
              <div class="card-body">
                <!-- START DATE -->
                <div class="mb-3">

                  <label for="inicio" class="form-label">Documento:</label>

                  <select name="tipo" id="tipo" class="form-control" >
                    <?php
                    foreach ($Utilidades->diccionario_transacciones_documentos as $key => $array) {
                      echo "<option data-actor='{$array['actor']}' value='{$array['tabla']}'  " . (($array['tabla'] == $_REQUEST['tipo']) ? 'selected="selected"' : '') . "  >{$array['descripcion']} </option> ";
                    }
                    ?>
                  </select>

                </div>

                <div class="mb-3">
                  <label for="inicio" class="form-label">Fecha Inicio</label>
                  <input name="inicio" id="inicio" value="<?php echo $fecha['inicio']; ?>" class="form-control">
                </div>

                <!-- END DATE -->
                <div class="mb-3">
                  <label for="fin" class="form-label">Fecha Final</label>
                  <input name="fin" id="fin" class="form-control" value="<?php echo $fecha['fin']; ?>">
                </div>

                <!-- Moneda -->
                <hr>


                <!-- BUTTONS -->
                <div class="d-flex gap-2">
                  <a href="<?php echo ENLACE_WEB."reportes";?>" class="btn btn-default btn-sm">Volver</a>
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
                <small class="text-muted">Se mostr&aacute; los datos relevantes del informe.</small>
              </div>
              <div id="informeVistaPrevia" style="width:100%; overflow:auto;" class="card-body">
              </div>
            </div>
          </div>
        </div>


      </div>
    </div>

  </form>

</div>

<!-- SCRIPT -->
<script>
  $(document).ready(function() {

    // Desactivar todos los elementos del menÃº
    $(".menu").removeClass('active');

    $(".reportes").addClass('active');
    $(".reportes > .submenu").addClass('show');
    $("#reportes_iva").addClass('active');

    $("#tipo").change(function() {
      var actor = $("#tipo option:selected").data("actor");
      console.log("Data-actor seleccionado: ", actor);
      $(".actor").empty().hide().html("de " + actor).fadeIn();
    });

    $("#tipo").trigger('change');

  });


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
      dayNames: ["Domingo", "Lunes", "Martes", "MiÃ©rcoles", "Jueves", "Viernes", "SÃ¡bado"],
      dayNamesShort: ["Dom", "Lun", "Mar", "MiÃ©", "Juv", "Vie", "SÃ¡b"],
      dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "SÃ¡"],
      weekHeader: "Sm",
      firstDay: 1,
      isRTL: false,
      showMonthAfterYear: false,
      yearSuffix: "",
    });
  });

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
      dayNames: ["Domingo", "Lunes", "Martes", "MiÃ©rcoles", "Jueves", "Viernes", "SÃ¡bado"],
      dayNamesShort: ["Dom", "Lun", "Mar", "MiÃ©", "Juv", "Vie", "SÃ¡b"],
      dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "SÃ¡"],
      weekHeader: "Sm",
      firstDay: 1,
      isRTL: false,
      showMonthAfterYear: false,
      yearSuffix: "",
    });
  });

  function cargarVistaPrevia() {

    error = false;
    $("#tipo").removeClass("input_error");
    // Validar que los campos no estén vacíos y añadir la clase de error si es necesario
    if ($("#tipo").val() == '') {
      $("#tipo").addClass("input_error");
      error = true;
    }

    // Si hay errores, mostrar notificación y detener el envío del formulario
    if (error) {
      add_notification({
        text: 'Selecciona el tipo de Documento',
        actionTextColor: '#fff',
        backgroundColor: '#e7515a',
      });
      return true;
    }

    $.ajax({
        method: "POST",
        url: "<?php echo ENLACE_WEB; ?>mod_reporte_clientes/json/reporte.json.php",
        beforeSend: function(xhr) {

          $("#informeVistaPrevia").empty();
          $("#informeVistaPrevia").append('<i class="fas fa-cog fa-spin fa-5x fa-fw" ></i>');
        },
        data: {
          inicio: $('#inicio').val(),
          fin: $('#fin').val(),
          tipo: $("#tipo").val()
        },

      })
      .done(function(msg) {
        console.log("Data Saved: " + msg);
        $("#informeVistaPrevia").empty();
        $("#informeVistaPrevia").append(msg);
      });
  }
</script>