<?php

require_once(ENLACE_SERVIDOR . "mod_reporte/object/reporte.object.php");
$Reporte = new Reporte($dbh, $_SESSION['Entidad']);
$Reporte->reporte_general_ivas('fi_europa_facturas');

$Reporte2 = new Reporte($dbh, $_SESSION['Entidad']);
$Reporte2->reporte_general_ivas('fi_europa_compras');

?>

<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">


<div class="layout-px-spacing">

  <!-- BREADCRUMB -->
  <div class="page-meta">
    <nav class="breadcrumb-style-one" aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Reportes</a></li>
        <li class="breadcrumb-item"><a href="#">IVA</a></li>
        <li class="breadcrumb-item active" aria-current="page"> General </li>
      </ol>
    </nav>
  </div>
  <!-- /BREADCRUMB -->

  <?php










  foreach ($Reporte->YEARS as $year) {

    $tabla .= "<thead>";
    $tabla .= "<Tr><td>🚀IVA Repercutido (Ventas) </td>";
    foreach ($Utilidades->meses as $mes_id => $mes_txt) {
      $tabla .= '<td width="6%">
                                                ' . $mes_txt
        . '</td>';
    } // Iva Porcentaje

    $tabla .= "<td>Total IVA</td>";
    $tabla .= "</tr>";
    $tabla .= "</thead>";

    foreach ($Reporte->IVAS_PORCENTAJES as $iva_id) {
      $tabla .= "<tr>
                                      <td>{$iva_id} </td>";
      $total_linea = 0;

      foreach ($Utilidades->meses as $mes_id => $mes_txt) {
        $tabla .= "<td>{$Reporte->IVAS[$year][$mes_id][$iva_id]}</td>";
        $total_linea += $Reporte->IVAS[$year][$mes_id][$iva_id];
      } // Iva Porcentaje

      $tabla .= "<td>{$total_linea}</td>";

      $tabla .= "</tr>";
    } // fin del mes   





    $tabla .= "<tr>";
    $tabla .= "<td>Resumen</td>";

    $total_linea = 0;

    foreach ($Utilidades->meses as $mes_id => $mes_txt) {
      $tabla .= "<td>{$Reporte->IVAS_TOTALES_POR_MES[$year][$mes_id]['IVA']}</td>";
      $total_linea += $Reporte->IVAS_TOTALES_POR_MES[$year][$mes_id]['IVA'];
    } // Iva Porcentaje


    $tabla .= "<td>{$total_linea}</td>";

    $tabla .= "</tr>";
  } // YEAR 




  /*************************************************
   * 
   * 
   * 
   *   Soportado
   * 
   * 
   *
   ******************************************************/

  $tabla2 = "";

  foreach ($Reporte2->YEARS as $year) {

    $tabla2 .= "<thead>";
    $tabla2 .= "<Tr><td>🚀 IVa Soportado (Compras) </td>";
    foreach ($Utilidades->meses as $mes_id => $mes_txt) {
      $tabla2 .= '<td width="6%">
                                                ' . $mes_txt
        . '</td>';
    } // Iva Porcentaje

    $tabla2 .= "<td>Total IVA</td>";
    $tabla2 .= "</tr>";
    $tabla2 .= "</thead>";

    foreach ($Reporte2->IVAS_PORCENTAJES as $iva_id) {
      $tabla2 .= "<tr>
                                      <td>{$iva_id} </td>";
      $total_linea = 0;

      foreach ($Utilidades->meses as $mes_id => $mes_txt) {
        $tabla2 .= "<td>{$Reporte2->IVAS[$year][$mes_id][$iva_id]}</td>";
        $total_linea += $Reporte2->IVAS[$year][$mes_id][$iva_id];
      } // Iva Porcentaje

      $tabla2 .= "<td>{$total_linea}</td>";

      $tabla2 .= "</tr>";
    } // fin del mes   





    $tabla2 .= "<tr>";
    $tabla2 .= "<td>Resumen</td>";

    $total_linea = 0;

    foreach ($Utilidades->meses as $mes_id => $mes_txt) {
      $tabla2 .= "<td>{$Reporte2->IVAS_TOTALES_POR_MES[$year][$mes_id]['IVA']}</td>";
      $total_linea += $Reporte2->IVAS_TOTALES_POR_MES[$year][$mes_id]['IVA'];
    } // Iva Porcentaje


    $tabla2 .= "<td>{$total_linea}</td>";

    $tabla2 .= "</tr>";
  } // YEAR 







  ?>


  <div class="row mt-3">
    <div id="tableCustomBasic" class="col-lg-12 col-12 layout-spacing">
      <div class="statbox widget box box-shadow">
        <div class="widget-content widget-content-area">
          <div class="table-responsive">
            <table class="table table-bordered">
              <tr><?php echo $tabla; ?></tr>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>








  <div class="row mt-3">

    <div id="tableCustomBasic" class="col-lg-12 col-12 layout-spacing">
      <div class="statbox widget box box-shadow">
        <div class="widget-content widget-content-area">
          <div class="table-responsive">
            <table class="table table-bordered">
              <tr><?php echo $tabla2; ?></tr>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>





  <div id="tableCustomBasic" class="col-lg-12 col-12 layout-spacing">
    <div id="columnchart_material" style="width: 100%; height: 500px;"></div>
  </div>

  <div id="detalle" class="col-lg-12 col-12 layout-spacing">

  </div>


</div>
<?php
foreach ($Utilidades->meses as $mes_id => $mes_txt) {
  $repercutido           = (int) $Reporte->IVAS_TOTALES_POR_MES[date("Y")][$mes_id]['IVA'];
  $soportado             = (int) $Reporte2->IVAS_TOTALES_POR_MES[date("Y")][$mes_id]['IVA'];
  $diferencia            = (int) $Reporte2->IVAS_TOTALES_POR_MES[date("Y")][$mes_id]['IVA'] - $Reporte->IVAS_TOTALES_POR_MES[date("Y")][$mes_id]['IVA'];

  $grafico .= ", ['{$mes_txt}' , {$repercutido}  ,  {$soportado} , {$diferencia} ]";
} // Iva Porcentaje
?>



<!-- JS para los botones -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>



<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
  $(document).ready(function() {

    // Desactivar todos los elementos del menÃº
    $(".menu").removeClass('active');
    $(".iva").addClass('active');
    $(".iva > .submenu").addClass('show');
    $("#submenu_iva").addClass('active');

    cargarGrilla()
  })

  function cargarGrilla() {
    $.ajax({
      method: "POST",
      url: "<?= ENLACE_WEB ?>mod_reporte_4_descarga_documentos/ajax/detalle.ajax.php",
      data: {
        column: 1,
        value: 325,
        mes: 'Marzo',
        category: 'Repercutido'
      },
      beforeSend: function() {
        $("#detalle").empty().html('<i class="fa-solid fa-cog fa-spin"></i>'); // Limpia el contenido anterior
      }
    }).done(function(data) {
      $("#detalle").empty().html(data);

debugger
      if ($('#miTabla').length) { // Verifica si #miTabla existe
        if ($.fn.DataTable.isDataTable('#miTabla')) {
          $('#miTabla').DataTable().destroy(); // Destruir instancia previa
        }

        $('#miTabla').DataTable({
          dom: 'Bfrtip',
          buttons: [
              'colvis',
              'excel',
              'print'
          ],
          // buttons: [{
          //     extend: 'excelHtml5',
          //     text: 'Exportar a Excel',
          //     className: 'btn btn-success'
          //   },
          //   {
          //     extend: 'pdfHtml5',
          //     text: 'Exportar a PDF',
          //     className: 'btn btn-danger',
          //     orientation: 'landscape', // Orientación horizontal
          //     pageSize: 'A4', // Tamaño de página
          //     customize: function(doc) {
          //       doc.styles.tableHeader = {
          //         bold: true,
          //         fontSize: 12,
          //         color: 'white',
          //         fillColor: '#4CAF50', // Color de encabezado
          //         alignment: 'center'
          //       };
          //       doc.content[1].table.widths = ['10%', '20%', '20%', '15%', '15%', '10%', '10%'];
          //     }
          //   }
          // ],
          pageLength: 25, // Número de filas por página por defecto
          lengthMenu: [
            [25, 50, 100, -1],
            [25, 50, 100, "Todos"]
          ], // Opciones de paginación

          footerCallback: function(row, data, start, end, display) {
            var api = this.api();

            // Función para sumar columnas
            var sumColumn = function(index) {
              return api.column(index, {
                  page: 'current'
                }).data()
                .reduce(function(a, b) {
                  return (parseFloat(a) || 0) + (parseFloat(b) || 0);
                }, 0).toFixed(2); // Redondeamos a 2 decimales
            };

            // Obtener las sumas de cada columna
            var totalIVA0 = sumColumn(6);
            var totalIVA4 = sumColumn(7);
            var totalIVA10 = sumColumn(8);
            var totalIVA21 = sumColumn(9);
            var totalFactura = sumColumn(10);

            // Insertar los totales en el footer
            $(api.column(6).footer()).html(totalIVA0);
            $(api.column(7).footer()).html(totalIVA4);
            $(api.column(8).footer()).html(totalIVA10);
            $(api.column(9).footer()).html(totalIVA21);
            $(api.column(10).footer()).html(totalFactura);



          }
        });

      }
    })

  }




  google.charts.load('current', {
    'packages': ['bar']
  });
  google.charts.setOnLoadCallback(drawChart);
  var chart, data; // Variables globales para el gráfico y los datos

  function drawChart() {
    var data = google.visualization.arrayToDataTable([
      ['Mes', 'Repercutido', 'Soporte', 'Diferencia']
      <?php echo $grafico; ?>
    ]);

    var options = {
      chart: {
        title: 'Comportamiento IVA',
        subtitle: 'IVA <?php echo date("Y"); ?>',
      },
      backgroundColor: 'transparent' // Quita el fondo del gráfico
        ,
      colors: ['<?php echo $Reporte->color_soportado; ?>', '<?php echo $Reporte->color_repercutido; ?>', '<?php echo $Reporte->color_a_pagar; ?>'] // Define los colores para cada serie en orden

    };

    var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

    chart.draw(data, google.charts.Bar.convertOptions(options));


    // Capturar evento de clic en la barra
    google.visualization.events.addListener(chart, 'select', function() {
      var selection = chart.getSelection();
      if (selection.length > 0) {
        var row = selection[0].row; // Obtiene el índice de la fila seleccionada
        var column = selection[0].column; // Obtiene la columna seleccionada (1=Sales, 2=Expenses, etc.)
        var value = data.getValue(row, column); // Obtiene el valor de la celda
        var category = data.getColumnLabel(column); // Obtiene el nombre de la serie

        //alert('Has hecho clic en: ' + category + '\nAño: ' + data.getValue(row, 0) + '\nValor: ' + value);



        $.ajax({
          method: "POST",
          url: "<?= ENLACE_WEB ?>mod_reporte_4_descarga_documentos/ajax/detalle.ajax.php",
          data: {
            column: column,
            value: value,
            mes: data.getValue(row, 0),
            category: category
          },
          beforeSend: function() {
            $("#detalle").empty().html('<i class="fa-solid fa-cog fa-spin"></i>'); // Limpia el contenido anterior
          }
        }).done(function(data) {
          $("#detalle").empty().html(data);


          if ($('#miTabla').length) { // Verifica si #miTabla existe
            if ($.fn.DataTable.isDataTable('#miTabla')) {
              $('#miTabla').DataTable().destroy(); // Destruir instancia previa
            }

            $('#miTabla').DataTable({
              dom: 'Bfrtip',
              layout: {
                topStart: {
                  buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
                }
              },
              buttons: [{
                  extend: 'excelHtml5',
                  text: 'Exportar a Excel',
                  className: 'btn btn-success'
                },
                {
                  extend: 'pdfHtml5',
                  text: 'Exportar a PDF',
                  className: 'btn btn-danger',
                  orientation: 'landscape', // Orientación horizontal
                  pageSize: 'A4', // Tamaño de página
                  customize: function(doc) {
                    doc.styles.tableHeader = {
                      bold: true,
                      fontSize: 12,
                      color: 'white',
                      fillColor: '#4CAF50', // Color de encabezado
                      alignment: 'center'
                    };
                    doc.content[1].table.widths = ['10%', '20%', '20%', '15%', '15%', '10%', '10%'];
                  }
                }
              ],
              pageLength: 25, // Número de filas por página por defecto
              lengthMenu: [
                [25, 50, 100, -1],
                [25, 50, 100, "Todos"]
              ], // Opciones de paginación

              footerCallback: function(row, data, start, end, display) {
                var api = this.api();

                // Función para sumar columnas
                var sumColumn = function(index) {
                  return api.column(index, {
                      page: 'current'
                    }).data()
                    .reduce(function(a, b) {
                      return (parseFloat(a) || 0) + (parseFloat(b) || 0);
                    }, 0).toFixed(2); // Redondeamos a 2 decimales
                };

                // Obtener las sumas de cada columna
                var totalIVA0 = sumColumn(6);
                var totalIVA4 = sumColumn(7);
                var totalIVA10 = sumColumn(8);
                var totalIVA21 = sumColumn(9);
                var totalFactura = sumColumn(10);

                // Insertar los totales en el footer
                $(api.column(6).footer()).html(totalIVA0);
                $(api.column(7).footer()).html(totalIVA4);
                $(api.column(8).footer()).html(totalIVA10);
                $(api.column(9).footer()).html(totalIVA21);
                $(api.column(10).footer()).html(totalFactura);



              }
            });



          } else {
            console.error("Error: La tabla #miTabla no existe en el HTML.");
          }




        }).fail(function(jqXHR, textStatus, errorThrown) {
          $("#detalle").html("<p>Error al cargar los datos</p>");
          console.error("Error en AJAX: " + textStatus, errorThrown);
        });




      }
    });
  }


  // Función para seleccionar una columna específica
  function selectColumn(columnIndex) {
    var selection = [];
    for (var i = 0; i < data.getNumberOfRows(); i++) {
      selection.push({
        row: i,
        column: columnIndex
      });
    }
    chart.setSelection(selection);
  }
</script>