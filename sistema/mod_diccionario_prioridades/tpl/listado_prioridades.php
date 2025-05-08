<?php
include_once(ENLACE_SERVIDOR . "mod_documentos_mercantiles/tpl/header_document.php");
?>
  <div class="middle-content container-xxl p-0">

      <!-- BREADCRUMB -->
      <div class="page-meta">
          <nav class="breadcrumb-style-one" aria-label="breadcrumb">
              <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Prioridad</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Listado</li>
              </ol>
          </nav>
      </div>
      <!-- /BREADCRUMB -->


      <!-- CONTENT AREA -->
      <div class="row layout-top-spacing">

          <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
              <div class="row my-4">


                 <!--  <div class="col-md-3">
                      <a href="#" onclick="ver_prioridad();"  class="btn btn-primary">Nueva Prioridad</a>
                  </div> -->

              </div>
              <div class="widget-content widget-content-area br-8">

                  <form id="formulario">
                      <!-- Tabla  -->
                      <div class="table-responsive">
                          <table id="style-3" class="table style-3 dt-table-hover">
                              <thead>
                                  <tr>
                                  <th style="width: 10%;" scope="col" >ID</th>
                                      <th scope="col">etiqueta</th>
                                      <th scope="col">Prioridad</th>
                                      <th scope="col">Estilo</th>
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
  <div class="modal fade" id="nueva_prioridad" tabindex="-1" role="dialog" aria-labelledby="nueva_prioridad_label" aria-hidden="true">
     <!-- MODAL  -->
    
  </div>
  <!-- Scripts -->
  <?php include_once(ENLACE_SERVIDOR . "mod_documentos_mercantiles/tpl/footer_document.php"); ?>
  <script>
      function cargar_tabla_prioridad() {

        const ajaxoption = {
            
             url: '<?php echo ENLACE_WEB; ?>mod_diccionario_prioridades/ajax/listado_prioridad_ajax.php',
            type: 'GET'
        }
        let options = config_datatable(ajaxoption);

        options.initComplete = function() {
                  this.api().columns().every(function(col) {
                      var column = this;
                      var header = $(column.header());
                      var headerText = header.text(); // Guarda el texto original del encabezado
                      header.empty(); // Limpia el encabezado
                      // Crea un contenedor div para el texto del encabezado
                      var headerTextContainer = $('<div class="text-center" >').appendTo(header);
                      $('<span>').text(headerText).appendTo(headerTextContainer);

                      // Lista de índices de columnas donde NO quieres mostrar el input de búsqueda
                      var excludedColumns = [4]; // Por ejemplo, para excluir las columnas 2, 4 y 6

                      // Crea un contenedor div para el input, independientemente de si está oculto o visible
                      var inputContainer = $('<div>').css({
                          'width': '100%', // Asegura que el contenedor ocupe todo el espacio disponible
                          'height': '100%', // Asegura que el contenedor ocupe todo el espacio disponible
                          'position': 'relative' // Posiciona el contenedor de manera relativa para que el input se posicione correctamente dentro de él
                      }).appendTo(header);

                      // Verifica si la columna actual está en la lista de excluidas
                      if ($.inArray(col, excludedColumns) === -1) {
                          // Para los campos de texto
                          var input = $('<input type="text" class="form-control">')
                              .appendTo(inputContainer)
                              .on('input', function() { // Cambiado de 'change' a 'input'
                                  var val = $.fn.dataTable.util.escapeRegex(
                                      $(this).val()
                                  );
                                  column
                                      .search(val ? '^' + val + '$' : '', true, false)
                                      .draw();
                              });
                      } else {
                       

                          if (column.dataSrc() === 'estado') {
                              let select = generarSelectEstatus('estado');

                              $(select).appendTo(header).on('change', function() {
                                  var val = ($(this).val());

                                  column.search(val ? '^' + val + '$' : '', true, false).draw();
                              });
                          }

                      }
                  });
              },
              options.columns = [{
                      data: 'ID',
                      searchable: true,
                      createdCell: function(td, cellData, rowData, row, col) {
                          $(td).addClass('text-center').attr('data-label', 'ID');
                      },
                      render: function(data, type, row) {

                          return `<a onclick="ver_prioridad(${row.ID});" href="#">${row.ID}</a>`
                      }
                  },
                  {
                      data: 'etiqueta',
                      searchable: true,
                      createdCell: function(td, cellData, rowData, row, col) {
                          $(td).addClass('text-center').attr('data-label', 'etiqueta');
                      },
                      render: function(data, type, row) {
                          return `<a onclick="ver_prioridad(${row.ID});" href="#">${data}</a>`
                      }

                  },
                  {
                      data: 'prioridad',
                      searchable: true,
                      createdCell: function(td, cellData, rowData, row, col) {
                          $(td).addClass('text-center').attr('data-label', 'prioridad');
                      },
                      render: function(data, type, row) {
                          return `<a onclick="ver_prioridad(${row.ID});" href="#">${data}</a>`
                      }

                  },

                  {
                      data: 'estilo',
                      searchable: true,
                      createdCell: function(td, cellData, rowData, row, col) {
                          $(td).addClass('text-center').attr('data-label', 'estilo');
                      },
                      render: function(data, type, row) {
                          return `<a onclick="ver_prioridad(${row.ID});" href="#">${data}</a>`
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

          

           vtabla = $('#style-3').DataTable(options);

            let newButton = $('<button>')
                .html('<i class="fa-solid fa-plus"></i> Nueva Prioridad')
                .addClass('btn btn-primary')
                .attr("type", "button")
                .on('click', function() {
                    ver_prioridad();
                });
            setting_table(vtabla, [newButton])
           
          }


          
      


  </script>

  <script>
      $(document).ready(function() {

          cargar_tabla_prioridad();

             // Desactivar todos los elementos del menú
             $(".menu").removeClass('active');

            $(".configuracion_general").addClass('active');
            $(".configuracion_general > .submenu").addClass('show');
            $("#prioridades_listado").addClass('active');



      });
  </script>




  <script type="text/javascript">
      function crear_prioridad(event) {
          event.preventDefault();

          let error = false;

          // Eliminar la clase de error de los campos antes de validar
          $('#etiqueta').removeClass("input_error");

          // Recoger los valores del formulario usando jQuery
          const label = $('#etiqueta').val();

          // Validar que los campos no estén vacíos y añadir la clase de error si es necesario
          if (label == '') {
              $('#etiqueta').addClass("input_error");
              error = true;
          }

          if ($("#orden_prioridad").val() == '') {
              $('#orden_prioridad').addClass("input_error");
              error = true;
          }


          if ($("#estilo_prioridad").val() == '') {
              $('#estilo_prioridad').addClass("input_error");
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
              url: "<?php echo ENLACE_WEB; ?>mod_diccionario_prioridades/ajax/diccionario_prioridad_ajax.php",
              beforeSend: function(xhr) {

              },
              data: {
                  action: 'crear_prioridad',
                  etiqueta: label,
                  prioridad: $("#orden_prioridad").val(),
                  estilo: $("#estilo_prioridad").val(),
                  estado: $("#estado_prioridad").val(),
              },
          }).done(function(msg) {
              //    console.log("Actualizando");
              console.log(msg);

              var mensaje = jQuery.parseJSON(msg);

              if (mensaje.exito == 1) {
                  $("#nueva_prioridad").modal('hide');

                  $('#style-3').DataTable().ajax.reload();

                  add_notification({
                      text: 'Prioridad creado exitosamente',
                      actionTextColor: '#fff',
                      backgroundColor: '#00ab55',
                      dismissText: 'Cerrar'
                  });

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



      function ver_prioridad(int = null) {

    
          // Preparar la petición AJAX
          $.ajax({
              method: "POST",
              url: "<?php echo ENLACE_WEB; ?>mod_diccionario_prioridades/tpl/modal_diccionario_prioridad.php",
              beforeSend: function(xhr) {
                  // aqui deberia ocurrir una carga
              },
              data: {
                  action: 'ver_prioridad',
                  fiche: int,
              },
          }).done(function(html) {

              //print html en el modal cargado
              $("#nueva_prioridad").html(html).modal('show');


          });

      }


      function actualizar_prioridad(int) {
      

          let error = false;

          // Eliminar la clase de error de los campos antes de validar
          $('#etiqueta').removeClass("input_error");

          // Recoger los valores del formulario usando jQuery
          const label = $('#etiqueta').val();

          // Validar que los campos no estén vacíos y añadir la clase de error si es necesario
          if (label == '') {
              $('#etiqueta').addClass("input_error");
              error = true;
          }


          if ($("#orden_prioridad").val() == '') {
              $('#orden_prioridad').addClass("input_error");
              error = true;
          }


          if ($("#estilo_prioridad").val() == '') {
              $('#estilo_prioridad').addClass("input_error");
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
              url: "<?php echo ENLACE_WEB; ?>mod_diccionario_prioridades/ajax/diccionario_prioridad_ajax.php",
              beforeSend: function(xhr) {

              },
              data: {
                  action: 'actualizar_prioridad',
                  etiqueta: label,
                  estado: $("#estado_prioridad").val(),
                  prioridad: $("#orden_prioridad").val(),
                  estilo: $("#estilo_prioridad").val(),
                  id: int,
              },
          }).done(function(msg) {
              var mensaje = JSON.parse(msg);
              
              if (mensaje.exito === 1) {
                  add_notification({
                      text: 'Prioridad actualizado exitosamente',
                      actionTextColor: '#fff',
                      backgroundColor: '#00ab55',
                      dismissText: 'Cerrar'
                  });
              
                  $("#nueva_prioridad").modal('hide');

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



      // FUNCTION DELETE INFO PRODUCT
      function borrar_prioridad($id) {
          // Preparar el mensaje para el snackbar
          var message = "Está seguro(a) que desea eliminar la Prioridad? ";
          var actionText = "Confirmar";

          // Mostrar el snackbar y definir el callback para el botón de acción
          add_notification({
              text: message,
              width: 'auto',
              duration: 30000,
              actionText: actionText,
              dismissText: 'Cerrar',
              onActionClick: function(element) {
                  // Aquí va el código que se ejecutará cuando el usuario confirme
                  $.ajax({
                      method: "POST",
                      url: "<?php echo ENLACE_WEB; ?>mod_diccionario_prioridades/ajax/diccionario_prioridad_ajax.php",
                      beforeSend: function(xhr) {},
                      data: {
                          action: 'borrar_prioridad',
                          id: $id
                      },
                  }).done(function(msg) {
                      console.log(msg);

                      var data = JSON.parse(msg);
                      // VALID RESULT
                      if (data.exito == 1) {
                          add_notification({
                              text: 'Prioridad Eliminado exitosamente',
                              pos: 'top-right',
                              actionTextColor: '#fff',
                              backgroundColor: '#00ab55'
                          });
                          $("#nueva_prioridad").modal('hide');

                            $('#style-3').DataTable().ajax.reload();
                      }
                  });
              }
          });
      }


  </script>