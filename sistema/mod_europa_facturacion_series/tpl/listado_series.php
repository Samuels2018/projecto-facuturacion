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
      button.action {
    width: 40% !important;
}
  </style>
  <div class="middle-content container-xxl p-0">

      <!-- BREADCRUMB -->
      <div class="page-meta">
          <nav class="breadcrumb-style-one" aria-label="breadcrumb">
              <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Configuracion Series</a></li>
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
                      <a href="#" onclick="ver_serie();" class="btn btn-primary">Nueva Serie</a>
                  </div>

              </div>
              <div class="widget-content widget-content-area br-8">

                  <form id="formulario">
                      <!-- Tabla  -->
                      <div class="table-responsive">
                          <table id="style-3" class="table style-3 dt-table-hover">
                              <thead>
                                  <tr>
                                    <th scope="col" style="width: 15%;" >Tipo</th>
                                      <th scope="col" style="width: 5%;" >Tipo AEAT</th>
                                      <th scope="col" style="width: 5%;" >Siguiente </th>
                                      <th scope="col"  style="width: 5%;"  >Modelo</th>
                                      <th scope="col"  style="width: 5%;"  >Documentos</th>
                                      <th scope="col">Descripci&oacute;n</th>
                                       <th scope="col"  style="width: 10%;"  >Defecto</th>
                                      <th style="width: 15%;" scope="col">Estado</th>
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
  <div class="modal fade" id="nueva_configuracion" tabindex="-1" role="dialog" aria-labelledby="nueva_configuracion_label" aria-hidden="true">
      <!-- MODAL  -->

  </div>
  <!-- Scripts -->

  <script>
      function cargar_tabla_configuracion() {
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
                  'url': '<?php echo ENLACE_WEB; ?>mod_europa_facturacion_series/json/listado_series.json.php',
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
                      var headerText = header.text(); // Guarda el texto original del encabezado
                      header.empty(); // Limpia el encabezado
                      // Crea un contenedor div para el texto del encabezado
                      var headerTextContainer = $('<div class="text-center" >').appendTo(header);
                      $('<span>').text(headerText).appendTo(headerTextContainer);

                      // Lista de índices de columnas donde NO quieres mostrar el input de búsqueda
                      var excludedColumns = []; // Por ejemplo, para excluir las columnas 2, 4 y 6

                      // Crea un contenedor div para el input, independientemente de si está oculto o visible
                      var inputContainer = $('<div>').css({
                          'width': '100%', // Asegura que el contenedor ocupe todo el espacio disponible
                          'height': '100%', // Asegura que el contenedor ocupe todo el espacio disponible
                          'position': 'relative' // Posiciona el contenedor de manera relativa para que el input se posicione correctamente dentro de él
                      }).appendTo(header);


 

                      // Verifica si la columna actual está en la lista de excluidas
                      if ($.inArray(col, excludedColumns) === -1) {
                          
                        if (column.dataSrc() === 'estado') {
                              let select = generarSelectEstatus('estado');

                              $(select).appendTo(header)
                              .attr('titulo', headerText)
                              .on('change', function() {
                                  var val = ($(this).val());

                                  column.search(val ? '^' + val + '$' : '', true, false).draw();
                              });
                           

                      } else { 

                        
                        // Para los campos de texto
                          var input = $('<input type="text" class="form-control">')
                            .appendTo(inputContainer)
                            .attr('placeholder', headerText) // Usar el texto del encabezado como placeholder
                            .attr('titulo', headerText)
                            .on('input', function() { // Cambiado de 'change' a 'input'
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );
                                column
                                    .search(val ? '^' + val + '$' : '', true, false)
                                    .draw();
                            });

                            }
                        }   
                  });
              },
              'columns': [
                {
                      data: 'tipo',
                      searchable: true,
                      createdCell: function(td, cellData, rowData, row, col) {
                          $(td).addClass('text-left').attr('data-label', 'valor');
                      },
                      render: function(data, type, row) {
                          return `<a onclick="ver_serie(${row.ID});" href="#"><b>${data}</b></a>`
                      }

                  },
                  {
                      data: 'tipo_aeat',
                      searchable: true,
                      createdCell: function(td, cellData, rowData, row, col) {
                          $(td).addClass('text-center').attr('data-label', 'valor');
                      },
                      render: function(data, type, row) {
                          return `<a onclick="ver_serie(${row.ID});" href="#">${data}</a>`
                      }

                  },

                  {
                      data: 'siguiente_documento',
                      searchable: true,
                      createdCell: function(td, cellData, rowData, row, col) {
                          $(td).addClass('text-center').attr('data-label', 'valor');
                      },
                      render: function(data, type, row) {
                          return `<a onclick="ver_serie(${row.ID});" href="#">${data}</a>`
                      }

                  },


                  {
                      data: 'fk_serie_modelo',
                      searchable: true,
                      createdCell: function(td, cellData, rowData, row, col) {
                          $(td).addClass('text-left').attr('data-label', 'valor');
                      },
                      render: function(data, type, row) {
                          return `<a onclick="ver_serie(${row.ID});" href="#"><span class="badge outline-badge-secondary mb-2 me-4">${data}</span></a>`
                      }

                  },


                  
                 
  
                  {
                      data: 'series_cuantos_documentos',
                      searchable: true,
                      createdCell: function(td, cellData, rowData, row, col) {
                          $(td).addClass('text-center').attr('data-label', 'valor');
                      },
                      render: function(data, type, row) {
                          return `<a onclick="ver_serie(${row.ID});" href="#">${data}</a>`
                      }

                  },

                  {
                      data: 'serie_descripcion',
                      searchable: true,
                      createdCell: function(td, cellData, rowData, row, col) {
                          $(td).addClass('text-left').attr('data-label', 'valor');
                      },
                      render: function(data, type, row) {
                          return `<a onclick="ver_serie(${row.ID});" href="#">${data}</a>`
                      }

                  },

                  

                  {
                      data: 'serie_por_defecto',
                      searchable: true,
                      createdCell: function(td, cellData, rowData, row, col) {
                          $(td).addClass('text-center').attr('data-label', 'valor');
                      },
                      

                      render: function(data, type, row) {
                        console.warn(data)
                        if (data == 1) {
                            return `<i class="fa fa-fw fa-check-circle"></i>`;
                        } else {
                            return '-';
                        }

                    }


                  },



                  {
                      data: 'estado',
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


          // Cargar configuración guardada de columnas
          loadColumnVisibility(vtabla);

       
          // Crear el div que contendrá los checkboxes
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

          // Crear checkboxes para cada columna dentro del div
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

          // Añadir el icono y el div al DOM, justo antes de la tabla
       
          $('#style-3_wrapper').prepend(columnVisibilityContainer);

     



      }


      // Guardar la visibilidad de las columnas en localStorage
      function saveColumnVisibility(table) {
          var columnVisibility = [];
          table.columns().every(function(index) {
              columnVisibility.push(this.visible());
          });
          localStorage.setItem('columnVisibilityContacto', JSON.stringify(columnVisibility));
      }

      // Cargar la visibilidad de las columnas desde localStorage
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

          cargar_tabla_configuracion();

          // Desactivar todos los elementos del menú
          $(".menu").removeClass('active');

          $(".configuracion_general").addClass('active');
          $(".configuracion_general > .submenu").addClass('show');
          $("#configuracion_parametros").addClass('active');






          // Función para limpiar los encabezados de la tabla
          function cleanTableForExport(tableID) {
              // Clona la tabla para no modificar la original
              var tableClone = $(`#${tableID}`).clone();

              // Remueve inputs y selects de los encabezados
              tableClone.find('th').each(function() {
                  $(this).find('input, select').remove(); // Elimina inputs y selects
              });

              return tableClone;
          }
          // Función para exportar a Excel usando SheetJS
          function exportTableToExcel(tableID, filename = '') {
              // Limpia la tabla antes de exportar
              var tableClone = cleanTableForExport(tableID);
              // Convierte la tabla limpia a Excel
              var wb = XLSX.utils.table_to_book(tableClone.get(0), {
                  sheet: "Sheet1"
              });
              XLSX.writeFile(wb, filename);
          }
          // Función para exportar a PDF usando jsPDF
          function exportTableToPDF(tableID, filename = '') {
              // Limpia la tabla antes de exportar
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

          cargar_plantillas()
      });
  </script>




  <script type="text/javascript">
      function crear_serie(event, id) {
         // event.preventDefault();

          let error = false;

          // Eliminar la clase de error de los campos antes de validar
          $('#configuracion_input').removeClass("input_error");
          $('#valor_input').removeClass("input_error");


          if ($("#configuracion_input").val() == '') {
              $('#configuracion_input').addClass("input_error");
              error = true;
          }

 


          // Si hay errores, mostrar notificación y detener el envío del formulario
          if (error) {
              add_notification({
                  text: 'Faltan Datos Obligatorios',
                  actionTextColor: '#fff',
                  backgroundColor: '#e7515a',
              });
              return true;
          }

          // Preparar la petición AJAX
          $.ajax({
              method: "POST",
              url: "<?php echo ENLACE_WEB; ?>mod_europa_facturacion_series/ajax/configuracion_series_ajax.php",
              beforeSend: function(xhr) {

              },
              data: {
                    id: id     ,
                    action: 'crear_serie',
                    tipo: $("#tipo").val(),
                    tipo_aeat: $("#tipo_aeat").val(),
                    siguiente_documento: $("#siguiente_documento").val(),
                    siguiente_borrador: $("#siguiente_borrador").val(),
                    fk_serie_modelo: $("#fk_serie_modelo").val(),
                    serie_reinicio_anual: $("#serie_reinicio_anual").val(),
                    serie_por_defecto: $("#serie_por_defecto").val(),
                    serie_activa: $("#serie_activa").val(),
                    serie_descripcion: $("#serie_descripcion").val(),
                    plantilla_fk: $("#plantilla").val()
              },

              


          }).done(function(msg) {
             
              console.log(msg);

              var mensaje = jQuery.parseJSON(msg);

              if (mensaje.exito == 1) {
                  $("#nueva_configuracion").modal('hide');

                  $('#style-3').DataTable().ajax.reload();

                  add_notification({
                      text: mensaje.mensaje,
                      actionTextColor: '#fff',
                      backgroundColor: '#00ab55',
                      dismissText: 'Cerrar'     ,
                      duration: 50000 // Duración en milisegundos (5 segundos)

                  });

              } else {

                  add_notification({
                      text: "Error:" + mensaje.mensaje,
                      actionTextColor: '#fff',
                      backgroundColor: '#e7515a',
                      duration: 50000 // Duración en milisegundos (5 segundos)

                  });
              }

              if (Array.isArray(mensaje.mensaje_extraordinario)) {
                                mensaje.mensaje_extraordinario.forEach(function(valor) {
            

                                  Snackbar.show({
                                        text: valor, // Texto del mensaje
                                        actionText: 'Cerrar', // Botón de acción opcional
                                        duration: 50000, // Duración en milisegundos (5 segundos)
                                        pos: 'bottom-center' // Posición de la notificación
                                    });
                    });
                }  
          });




      }



      function ver_serie(int = null) {
          $.ajax({
              method: "POST",
              url: "<?php echo ENLACE_WEB; ?>mod_europa_facturacion_series/ajax/modal_series.php",
              beforeSend: function(xhr) {
                  // aqui deberia ocurrir una carga
              },
              data: {
                  action: 'ver_serie',
                  fiche: int,
              },
          }).done(function(html) {
               $("#nueva_configuracion").html(html).modal('show');
               cargar_plantillas()
          });
      }

 




      function confirma_eliminar($id) {


          document.getElementById('actualizar_parametro').disabled = true;
          document.getElementById('borrar_parametro').disabled = true;

          // Preparar el mensaje para el snackbar
          var message = "Está seguro(a) que desea eliminar el parametro?";
          var actionText = "<strong onclick='ocultar_snackbar();' id='cancelar_borrado'>Cancelar</strong> <button onclick='borrar_serie(" + $id + ")' style='margin-left:5px;' id='confirmar_borrado' class='btn btn-danger'>Confirmar</button>";

          console.log("Mostrando snackbar"); // Depuración

          // Mostrar el snackbar y definir el callback para el botón de acción
          var snackbar = add_notification({
              text: message,
              width: 'auto',
              duration: 300000,
              actionText: actionText,
          });

      }
      // Función que se ejecuta al hacer clic en el botón "confirmar_borrado"
      function mostrarSnackbar() {
          // Cambiar el estilo del snackbar para hacerlo visible y colocarlo en la parte inferior
          $(".snackbar-container").attr('style', 'opacity:1 !important; bottom:0px !important');
      }
      // Asignar el evento de clic al botón "confirmar_borrado"
      $(document).on('click', '#confirmar_borrado', function() {
          mostrarSnackbar();
      });

      function ocultar_snackbar() {
          $(".snackbar-container").fadeOut(); // 500 milisegundos para la animación

          document.getElementById('actualizar_parametro').disabled = false;
          document.getElementById('borrar_parametro').disabled = false;
      }


      document.getElementById('nueva_configuracion').addEventListener('hidden.bs.modal', function() {
          // Llamar a la función ocultar_snackbar cuando el modal se oculte
          ocultar_snackbar();
      });


      // FUNCTION DELETE INFO PRODUCT
      function borrar_serie($id) {
   
        $.ajax({
                      method: "POST",
                      url: "<?php echo ENLACE_WEB; ?>mod_europa_facturacion_series/ajax/configuracion_series_ajax.php",
                      beforeSend: function(xhr) {},
                      data: {
                          action: 'borrar_serie',
                          id: $id
                      },
                  }).done(function(msg) {
                      console.log(msg);

                      var data = JSON.parse(msg);
                      // VALID RESULT
                      if (data.exito == 1) {
                          add_notification({
                              text: 'Parametro Eliminado exitosamente',
                              pos: 'top-right',
                              actionTextColor: '#fff',
                              backgroundColor: '#00ab55'
                          });
                          $("#nueva_configuracion").modal('hide');

                          $('#style-3').DataTable().ajax.reload();
                      }
                  });
      }
  </script>

  <script>
    function selector_tipo_aeat( x ){
        if (x =="fi_europa_facturas") { 
            $(".tipo_aeat").fadeIn();  
        }else { 
            $(".tipo_aeat").fadeOut(); 
        }
        cargar_plantillas()
    }
    function cargar_plantillas(){
        const id_plantilla = $('#plantilla_fk').val()
        if($('#tipo').val()!=''){
            const plantillas = $.ajax({
                url: "<?php echo ENLACE_WEB; ?>mod_documento_pdf/ajax/plantilla_configuracion.ajax.php",
                type: 'POST',
                dataType: 'json',
                data:{
                    action: 'plantilla_tipo_documento',
                    tipo: $('#tipo').val()
                }
            });
            plantillas.done(function(data) {
                $('#plantilla').empty()
                $('#plantilla').append($('<option>').val("").text("Selecciona"));
                data.forEach(function(tipo) {
                    if(tipo.rowid == id_plantilla){
                        $('#plantilla').append($('<option selected>').val(tipo.rowid).text(tipo.titulo));
                    }else{
                        $('#plantilla').append($('<option>').val(tipo.rowid).text(tipo.titulo));
                    }
                });
                $(".plantilla").fadeIn();
            })
        }
    }
  </script>