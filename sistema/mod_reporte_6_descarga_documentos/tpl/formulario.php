<?php

require_once(ENLACE_SERVIDOR . "mod_reporte/object/reporte.object.php");
$Reporte = new Reporte($dbh, $_SESSION['Entidad']);
$Reporte->reporte_general_ivas('fi_europa_facturas');

$Reporte2 = new Reporte($dbh, $_SESSION['Entidad']);
$Reporte2->reporte_general_ivas('fi_europa_compras');

include_once(ENLACE_SERVIDOR . "mod_documentos_mercantiles/tpl/header_document.php");

?>


<div class="layout-px-spacing">

  <!-- BREADCRUMB -->
  <div class="page-meta">
    <nav class="breadcrumb-style-one" aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Reportes</a></li>
        <li class="breadcrumb-item active" aria-current="page"> IVA Repercutido </li>
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








  <div id="tableCustomBasic" class="col-lg-12 col-12 layout-spacing">
    <div id="columnchart_material" style="width: 100%; height: 500px;"></div>
  </div>

  <div id="detalle" class="col-lg-12 col-12 layout-spacing">

  </div>


</div>

<?php include_once(ENLACE_SERVIDOR . "mod_documentos_mercantiles/tpl/footer_document.php"); ?>
<?php
foreach ($Utilidades->meses as $mes_id => $mes_txt) {
  $repercutido           = (int) $Reporte->IVAS_TOTALES_POR_MES[date("Y")][$mes_id]['IVA'];

  $grafico .= ", ['{$mes_txt}' , {$repercutido}  ]";
} // Iva Porcentaje
?>


<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
  $(document).ready(function() {

    $(".menu").removeClass('active');
    $(".mod_iva").addClass('active');
    $(".mod_iva > .submenu").addClass('show');
    $("#submenu_iva").addClass('active');
  })

  function cargarGrilla(column, value, mes, category) {
    const ajaxoption = {
      url: '<?php echo ENLACE_WEB; ?>mod_reporte_6_descarga_documentos/ajax/detalle.ajax.php',
      type: 'POST',
      data: {
        column: column,
        value: value,
        mes: mes,
        category: category
      }
    }
    const option_array = [{
        key: 'Processing',
        value: false
      },
      {
        key: 'serverSide',
        value: false
      },
      {
        key: 'footerCallback',
        value: function(row, data, start, end, display) {
          var api = this.api();

          if (api.rows().count() > 0) {
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
            var totalIVA0 = sumColumn(5);
            var totalIVA4 = sumColumn(6);
            var totalIVA10 = sumColumn(7);
            var totalIVA21 = sumColumn(8);
            var totalFactura = sumColumn(9);

            // Insertar los totales en el footer
            $(api.column(5).footer()).html(totalIVA0);
            $(api.column(6).footer()).html(totalIVA4);
            $(api.column(7).footer()).html(totalIVA10);
            $(api.column(8).footer()).html(totalIVA21);
            $(api.column(9).footer()).html(totalFactura);
          }
        }
      }
    ]
    let options = config_datatable(ajaxoption, option_array);

    options.columns = [{
        data: 'referencia',
        searchable: true,
        createdCell: function(td, cellData, rowData, row, col) {
          $(td).attr('data-label', 'referencia');
          $(td).addClass('text-left').addClass('py-0 px-3');
        },
        render: function(data, type, row) {
          return data;
        }
      },
      {
        data: 'fecha',
        searchable: true,
        createdCell: function(td, cellData, rowData, row, col) {
          $(td).attr('data-label', 'fecha');
          $(td).addClass('text-left').addClass('py-0 px-3');
        },
        render: function(data, type, row) {
          return data;
        }
      },
      {
        data: 'fk_tercero_txt',
        searchable: true,
        createdCell: function(td, cellData, rowData, row, col) {
          $(td).attr('data-label', 'fk_tercero_txt');
          $(td).addClass('text-left').addClass('py-0 px-3');
        },
        render: function(data, type, row) {
          return data;
        }
      },
      {
        data: 'fk_tercero_identificacion',
        searchable: true,
        createdCell: function(td, cellData, rowData, row, col) {
          $(td).attr('data-label', 'fk_tercero_identificacion');
          $(td).addClass('text-left').addClass('py-0 px-3');
        },
        render: function(data, type, row) {
          return data;
        }
      },
      {
        data: 'fk_tercero_telefono',
        searchable: true,
        createdCell: function(td, cellData, rowData, row, col) {
          $(td).attr('data-label', 'fk_tercero_telefono');
          $(td).addClass('text-left').addClass('py-0 px-3');
        },
        render: function(data, type, row) {
          return data;
        }
      },
      {
        data: 'IVA_0',
        searchable: true,
        createdCell: function(td, cellData, rowData, row, col) {
          $(td).attr('data-label', 'IVA_0');
          $(td).addClass('text-left').addClass('py-0 px-3');
        },
        render: function(data, type, row) {
          return data;
        }
      },
      {
        data: 'IVA_4',
        searchable: true,
        createdCell: function(td, cellData, rowData, row, col) {
          $(td).attr('data-label', 'IVA_4');
          $(td).addClass('text-left').addClass('py-0 px-3');
        },
        render: function(data, type, row) {
          return data;
        }
      },
      {
        data: 'IVA_10',
        searchable: true,
        createdCell: function(td, cellData, rowData, row, col) {
          $(td).attr('data-label', 'IVA_10');
          $(td).addClass('text-left').addClass('py-0 px-3');
        },
        render: function(data, type, row) {
          return data;
        }
      },
      {
        data: 'IVA_21',
        searchable: true,
        createdCell: function(td, cellData, rowData, row, col) {
          $(td).attr('data-label', 'IVA_21');
          $(td).addClass('text-left').addClass('py-0 px-3');
        },
        render: function(data, type, row) {
          return data;
        }
      },
      {
        data: 'Total_Factura',
        searchable: true,
        createdCell: function(td, cellData, rowData, row, col) {
          $(td).attr('data-label', 'Total_Factura');
          $(td).addClass('text-left').addClass('py-0 px-3');
        },
        render: function(data, type, row) {
          return data;
        }
      }

      // Agrega aquí las columnas adicionales que necesites
    ]
    vtabla = $('#style-3').DataTable(options)
    setting_table(vtabla, [])


    $("#style-3_filter").appendTo("#datatable_header");
    $("#style-3_filter").css('display', 'block');
    $('input[type="search"]').attr('placeholder', 'Buscar...');
  }


  google.charts.load('current', {
    'packages': ['bar']
  });
  google.charts.setOnLoadCallback(drawChart);
  var chart, data; // Variables globales para el gráfico y los datos

  function drawChart() {
    var data = google.visualization.arrayToDataTable([
      ['Mes', 'Repercutido']
      <?php echo $grafico; ?>
    ]);

    var options = {
      chart: {
        title: 'Comportamiento IVA',
        subtitle: 'IVA <?php echo date("Y"); ?>',
      },
      backgroundColor: 'transparent',
      colors: ['<?php echo $Reporte->color_repercutido; ?>' ]

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

        const table = `<table id="style-3" class="table style-3 dt-table-hover p-0">
          <thead>
            <tr>
              <th class="text-center" scope="col">Referencia</th>
              <th class="text-center" scope="col">Fecha</th>
              <th class="text-center" scope="col">Nombre</th>
              <th class="text-center" scope="col">CIF</th>
              <th class="text-center" scope="col">Tel</th>
              <th class="text-center" scope="col">IVA 0</th>
              <th class="text-center" scope="col">IVA 4</th>
              <th class="text-center" scope="col">IVA 10</th>
              <th class="text-center" scope="col">IVA 21</th>
              <th class="text-center" scope="col">Total Factura</th>
            </tr>
          </thead>
          <tbody id="tbody" role="alert" aria-live="polite" aria-relevant="all" id="tbody" style="font-size:small;">
          </tbody>
          <tfoot>
            <tr>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
            </tr>
          </tfoot>
        </table>`
        $("#detalle").empty().html(table);

        cargarGrilla(column, value, data.getValue(row, 0), category)

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