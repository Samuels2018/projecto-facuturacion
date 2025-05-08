

  <!-- Datatable -->
  <style type="text/css">
      #style-3_filter {
          display: none !important;
      }

      #style-3_length {
          display: flex;
      }

      #export-buttons-container {
          margin-left: 25px;
      }

      #export-buttons-container button+button {
          margin-left: 15px;
      }

      #columnVisibilityContainer {
          margin-top: 40px !important;
      }
  </style>
  <div class="middle-content container-xxl p-0">

      <!-- BREADCRUMB -->
      <div class="page-meta">
          <nav class="breadcrumb-style-one" aria-label="breadcrumb">
              <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Agentes Rutas</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Listado</li>
              </ol>
          </nav>
      </div>
      <!-- /BREADCRUMB -->


      <!-- CONTENT AREA -->
      <div class="row layout-top-spacing">

          <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
              <div class="row my-4">


                  <div class="col-md-3">
                      <a href="#" onclick="ver_agente_rutas();"  class="btn btn-primary">Nueva Ruta de Agente</a>
                  </div>

              </div>
              <div class="widget-content widget-content-area br-8">

                  <form id="formulario">
                      <!-- Tabla  -->
                      <div class="table-responsive">
                          <table id="style-3" class="table style-3 dt-table-hover">
                              <thead>
                                  <tr>
                                    <th style="width:5%;" scope="col"></th>
                                    <th style="width:40%;" scope="col">Agente</th>
                                    <th style="width:40%;" scope="col">Ruta</th>
                                    <th style="width:15%;" scope="col">Estado</th>
                                  </tr>
                              </thead>
                              <tbody id="tbody" role="alert" aria-live="polite" aria-relevant="all" id="tbody" style="font-size:small;">
                              </tbody>
                          </table>
                  </form>
              </div>
              <!--Fin Tabla  -->
          </div>
      </div>
  </div>
  <!-- CONTENT AREA -->

  <!-- MODAL  -->
  <div class="modal fade" id="nuevo_agente_ruta" tabindex="-1" role="dialog" aria-labelledby="nuevo_agente_ruta_label" aria-hidden="true">
     <!-- MODAL  -->
    
  </div>
  <!-- Scripts -->

  <script>
      function cargar_tabla_rutas_agentes() {
          vtabla = $('#style-3').DataTable({
              'Processing': true,
              'serverSide': true,
              "bSort": false,
              "pageLength": 20,
              "order": [
                  [0, "desc"]
              ],
              select: {
                  style: 'multi'
              },
              "dom": estiloPaginado.dom,
              "oLanguage": estiloPaginado.oLanguage,
              "stripeClasses": [],
              'ajax': {
                  'url': '<?php echo ENLACE_WEB; ?>mod_agente_rutas/ajax/listado_agente_rutas.ajax.php',
                  'Method': 'POST'
              },
              retrieve: true,
              deferRender: true,
              scroller: true,
              responsive: true,
              "initComplete": function() {
                  this.api().columns().every(function(col) {
                      var column = this;
                      var header = $(column.header());
                      var headerText = header.text();
                      header.empty();
                     
                      var headerTextContainer = $('<div class="text-center" >').appendTo(header);
                      $('<span>').text(headerText).appendTo(headerTextContainer);

                      var excludedColumns = []; 

                      var inputContainer = $('<div>').css({
                          'width': '100%',
                          'height': '100%',
                          'position': 'relative'
                      }).appendTo(header);

                      if ($.inArray(col, excludedColumns) === -1) {

                        if (column.dataSrc() !== 'activo') {
                        
                          var input = $('<input type="text" class="form-control">')
                              .appendTo(inputContainer)
                              .on('input', function() {
                                  var val = $.fn.dataTable.util.escapeRegex(
                                      $(this).val()
                                  );
                                  column
                                      .search(val ? '^' + val + '$' : '', true, false)
                                      .draw();
                              });
                        }else{
                                let select = generarSelectEstatus('activo');
                                $(select).appendTo(header).on('change', function() {
                                    var val = ($(this).val());

                                    column.search(val ? '^' + val + '$' : '', true, false).draw();
                                });
                            }   

                      } else {
                       

                          if (column.dataSrc() === 'activo') {
                              let select = generarSelectEstatus('activo');

                              $(select).appendTo(header).on('change', function() {
                                  var val = ($(this).val());

                                  column.search(val ? '^' + val + '$' : '', true, false).draw();
                              });
                          }

                      }
                  });
              },
              'columns': [
                 {
                      data: 'ID',
                      searchable: true,
                      createdCell: function(td, cellData, rowData, row, col) {
                          $(td).addClass('text-left').attr('data-label', 'label');
                      },
                      render: function(data, type, row) {
                          return `<a onclick="ver_agente_rutas(${row.ID});" href="#">${data}</a>`
                      }
                  },
                  {
                      data: 'fk_agente',
                      searchable: true,
                      createdCell: function(td, cellData, rowData, row, col) {
                          $(td).addClass('text-left').attr('data-label', 'label');
                      },
                      render: function(data, type, row) {
                          return `<a onclick="ver_agente_rutas(${row.ID});" href="#">${row.fk_agente}</a>`
                      }

                  },
                  {
                      data: 'fk_ruta',
                      searchable: true,
                      createdCell: function(td, cellData, rowData, row, col) {
                          $(td).addClass('text-left').attr('data-label', 'label');
                      },
                      render: function(data, type, row) {
                          return `<a onclick="ver_agente_rutas(${row.ID});" href="#">${data}</a>`
                      }

                  },
                  {
                      data: 'activo',
                      searchable: true,
                      createdCell: function(td, cellData, rowData, row, col) {
                          $(td).addClass('text-center').attr('data-label', 'estado');
                      },
                      render: function(data, type, row) {
                          if (data == 1) {
                              return '<span class="shadow-none badge badge-primary">Activo</span>';
                          } else if (data != 1) {
                              return '<span class="shadow-none badge badge-danger">Inactivo</span>';
                          }
                      }
                  }
              ]
          });

          loadColumnVisibility(vtabla);

          var configIcon = $('<i class="fa fa-bars" style="cursor: pointer;margin-right: 10px;font-size: 30px;float-right;float: right;"></i>');

          var columnVisibilityContainer = $('<div>')
              .attr('id', 'columnVisibilityContainer')
              .css({
                  display: 'none',
                  position: 'absolute',
                  right: '0',
                  backgroundColor: '#f9f9f9',
                  border: '1px solid #ccc',
                  padding: '10px',
                  zIndex: '1000',
              });

          vtabla.columns().every(function(index) {
              var column = this;
              var checkbox = $('<input type="checkbox">')
                  .val(index)
                  .prop('checked', column.visible())
                  .on('change', function() {
                      var columnIndex = $(this).val();
                      var column = vtabla.column(columnIndex);
                      column.visible(!column.visible());
                      saveColumnVisibility(vtabla);
                  });

              var label = $('<label>')
                  .css('display', 'block')
                  .text($(column.header()).text())
                  .prepend(checkbox);

              columnVisibilityContainer.append(label);
          });

          $('#style-3_wrapper').prepend(configIcon);
          $('#style-3_wrapper').prepend(columnVisibilityContainer);

          configIcon.on('click', function() {
              columnVisibilityContainer.toggle();
          });



      }

      function saveColumnVisibility(table) {
          var columnVisibility = [];
          table.columns().every(function(index) {
              columnVisibility.push(this.visible());
          });
          localStorage.setItem('columnVisibilityContacto', JSON.stringify(columnVisibility));
      }

     
      function loadColumnVisibility(table) {
          var columnVisibility = JSON.parse(localStorage.getItem('columnVisibilityContacto'));
          if (columnVisibility) {
              table.columns().every(function(index) {
                  this.visible(columnVisibility[index]);
              });
          }
      }
  </script>

  <script>
      $(document).ready(function() {

          cargar_tabla_rutas_agentes();

            $(".menu").removeClass('active');
            $(".configuracion_general").addClass('active');
            $(".configuracion_general > .submenu").addClass('show');
            $("#cliente_categorias").addClass('active');

          var buttonContainer = $('<div>').attr("id", "export-buttons-container").addClass('ml-2');

          var excelButton = $('<button>')
              .html('<i class="fas fa-file-excel"></i> Exportar Excel')
              .addClass('btn btn-success')
              .attr("type", "button")
              .on('click', function() {
                  exportTableToExcel('style-3', 'table_export.xlsx');
              });

          var pdfButton = $('<button>')
              .html('<i class="fas fa-file-pdf"></i> Exportar PDF')
              .addClass('btn btn-danger')
              .attr("type", "button")
              .on('click', function() {
                  exportTableToPDF('style-3', 'table_export.pdf');
              });

          buttonContainer.append(excelButton, pdfButton);

          buttonContainer.appendTo("#style-3_length");

          function cleanTableForExport(tableID) {
            
              var tableClone = $(`#${tableID}`).clone();

              tableClone.find('th').each(function() {
                  $(this).find('input, select').remove();
              });

              return tableClone;
          }

          function exportTableToExcel(tableID, filename = '') {
              
              var tableClone = cleanTableForExport(tableID);
             
              var wb = XLSX.utils.table_to_book(tableClone.get(0), {
                  sheet: "Sheet1"
              });
              XLSX.writeFile(wb, filename);
          }
          
          function exportTableToPDF(tableID, filename = '') {
             
              var tableClone = cleanTableForExport(tableID);
              var {
                  jsPDF
              } = window.jspdf;
              var doc = new jsPDF();
              doc.autoTable({
                  html: tableClone.get(0)
              });
              doc.save(filename);
          }

      });
  </script>

  <script type="text/javascript">
     

     function crear_agente_rutas(event)
     {
        event.preventDefault();

        let error = false;

        $('#activo_data, #fk_ruta, #fk_agente').removeClass("input_error");

        const activo = $('#activo_data').val();
        const fk_ruta = $('#fk_ruta').val();  
        const fk_agente = $('#fk_agente').val();  

        if (activo == '') {
            $('#activo_data').addClass("input_error");
            error = true;
        }
        if (fk_ruta == '') {
            $('#fk_ruta').addClass("input_error");
            error = true;
        }
        if (fk_agente == '') {
            $('#fk_agente').addClass("input_error");
            error = true;
        }

        if (error) {
            add_notification({
                text: 'Faltan Datos Obligatorios',
                actionTextColor: '#fff',
                backgroundColor: '#e7515a',
            });
            return true;
        }

        $.ajax({
            method: "POST",
            url: "<?php echo ENLACE_WEB; ?>mod_agente_rutas/ajax/agente_rutas_ajax.php",
            beforeSend: function(xhr) {},
            data: {
                action: 'crear_agente_rutas',
                activo: activo,
                fk_ruta: fk_ruta,  
                fk_agente: fk_agente, 
            },
        }).done(function(msg) {
            console.log(msg);

            var mensaje = jQuery.parseJSON(msg);

            if (mensaje.exito == 1) {
                $("#nuevo_agente_ruta").modal('hide');
                $('#style-3').DataTable().ajax.reload();

                add_notification({
                    text: 'Ruta de agente creada exitosamente',
                    actionTextColor: '#fff',
                    backgroundColor: '#00ab55',
                    dismissText: 'Cerrar'
                });
            } else {
                add_notification({
                    text: "Error:" + mensaje.mensaje,
                    actionTextColor: '#fff',
                    backgroundColor: '#e7515a',
                });
            }
        });
    }

    function ver_agente_rutas(int = null) {

          $.ajax({
              method: "POST",
              url: "<?php echo ENLACE_WEB; ?>mod_agente_rutas/tpl/modal_agente_rutas.php",
              beforeSend: function(xhr) {
                 
              },
              data: {
                  action: 'ver_agente_ruta_cliente',
                  fiche: int,
              },
          }).done(function(html) {

              $("#nuevo_agente_ruta").html(html).modal('show');

          });

    }


    function actualizar_agente_rutas(int) {
    
          let error = false;

            $('#activo_data, #fk_ruta, #fk_agente').removeClass("input_error");

            const activo = $('#activo_data').val();
            const fk_ruta = $('#fk_ruta').val();  
            const fk_agente = $('#fk_agente').val();  

            if (activo == '') {
                $('#activo_data').addClass("input_error");
                error = true;
            }
            if (fk_ruta == '') {
                $('#fk_ruta').addClass("input_error");
                error = true;
            }
            if (fk_agente == '') {
                $('#fk_agente').addClass("input_error");
                error = true;
            }

            if (error) {
                add_notification({
                    text: 'Faltan Datos Obligatorios',
                    actionTextColor: '#fff',
                    backgroundColor: '#e7515a',
                });
                return true;
            }

          $.ajax({
              method: "POST",
              url: "<?php echo ENLACE_WEB; ?>mod_agente_rutas/ajax/agente_rutas_ajax.php",
              beforeSend: function(xhr) {

              },
              data: {
                action: 'actualizar_agente_rutas',
                activo: activo,
                fk_ruta: fk_ruta,  
                fk_agente: fk_agente, 
                id: int,
              },
          }).done(function(msg) {
              var mensaje = JSON.parse(msg);
              
              if (mensaje.exito === 1) {
                  add_notification({
                      text: 'Categoría Cliente actualizado exitosamente',
                      actionTextColor: '#fff',
                      backgroundColor: '#00ab55',
                      dismissText: 'Cerrar'
                  });
              
                  $("#nuevo_agente_ruta").modal('hide');

                  $('#style-3').DataTable().ajax.reload();

              } else {

                  add_notification({
                      text: "Error:" + mensaje.mensaje,
                      actionTextColor: '#fff',
                      actionTextColor: '#fff',
                      backgroundColor: '#e7515a',
                  });
              }
          });
    }

    function confirma_eliminar($id) {


        document.getElementById('actualizar_agente_ruta').disabled = true;
        document.getElementById('borrar_agente_ruta').disabled = true;

        // Preparar el mensaje para el snackbar
        var message = "Está seguro(a) que desea eliminar la categoría CRM?";
        var actionText = "<strong onclick='ocultar_snackbar();' id='cancelar_borrado'>Cancelar</strong> <button onclick='borrar_agente_rutas("+$id+")' style='margin-left:5px;' id='confirmar_borrado' class='btn btn-danger'>Confirmar</button>";

        // Mostrar el snackbar y definir el callback para el botón de acción
        var snackbar = add_notification({
        text: message,
        width: 'auto',
        duration: 300000,
        actionText: actionText,
        });
    }

    function ocultar_snackbar() {
        $(".snackbar-container").fadeOut(500); // 500 milisegundos para la animación

            document.getElementById('actualizar_agente_ruta').disabled = false;
        document.getElementById('borrar_agente_ruta').disabled = false;
    }

    document.getElementById('nuevo_agente_ruta').addEventListener('hidden.bs.modal', function () {
    // Llamar a la función ocultar_snackbar cuando el modal se oculte
    ocultar_snackbar();
    });
      

    function borrar_agente_rutas($id) {
        
          var message = "Está seguro(a) que desea eliminar la ruta del agente? ";
          var actionText = "Confirmar";

          add_notification({
              text: message,
              width: 'auto',
              duration: 30000,
              actionText: actionText,
              dismissText: 'Cerrar',
              onActionClick: function(element) {
                  
                  $.ajax({
                      method: "POST",
                      url: "<?php echo ENLACE_WEB; ?>mod_agente_rutas/ajax/agente_rutas_ajax.php",
                      beforeSend: function(xhr) {},
                      data: {
                          action: 'borrar_agente_rutas',
                          id: $id
                      },
                  }).done(function(msg) {
                      console.log(msg);

                      var data = JSON.parse(msg);
                
                      if (data.exito == 1) {
                          add_notification({
                              text: 'Categoría cliente Eliminado exitosamente',
                              pos: 'top-right',
                              actionTextColor: '#fff',
                              backgroundColor: '#00ab55'
                          });
                          $("#nuevo_agente_ruta").modal('hide');

                            $('#style-3').DataTable().ajax.reload();
                      }
                  });
              }
          });
    }


  </script>