  <!-- Datatable -->
  <?php include_once(ENLACE_SERVIDOR . "mod_documentos_mercantiles/tpl/header_document.php"); ?>


  <div class="middle-content container-xxl p-0">

      <!-- BREADCRUMB -->
      <div class="page-meta">
          <nav class="breadcrumb-style-one" aria-label="breadcrumb">
              <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Categoría CRM</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Listado</li>
              </ol>
          </nav>
      </div>
      <!-- /BREADCRUMB -->


      <!-- CONTENT AREA -->
      <div class="row layout-top-spacing">

          <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
              <div class="row my-4">


                  <!-- <div class="col-md-3">
                      <a href="#" onclick="ver_categoria_crm();" class="btn btn-primary">Nueva Categoría CRM</a>
                  </div> -->

              </div>
              <div class="widget-content widget-content-area br-8">

                  <form id="formulario">
                      <!-- Tabla  -->
                      <div class="table-responsive">
                          <table id="style-3" class="table style-3 dt-table-hover">
                              <thead>
                                  <tr>
                                      <th scope="col">Etiqueta</th>
                                      <th style="width: 15%;" scope="col">Prioridad</th>
                                      <th style="width: 15%;" scope="col">Estilo</th>
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
    
<?php include_once(ENLACE_SERVIDOR . "mod_documentos_mercantiles/tpl/footer_document.php"); ?>
  <!-- CONTENT AREA -->

  <!-- MODAL  -->
  <div class="modal fade" id="nuevo_categoria" tabindex="-1" role="dialog" aria-labelledby="nuevo_categoria_label" aria-hidden="true">
      <!-- MODAL  -->

  </div>
  <!-- Scripts -->

  <script>
    function cargar_tabla__crm_categorias() {
        const ajaxoption = {
            url: '<?php echo ENLACE_WEB; ?>mod_crm_categorias/ajax/listado_crm_categorias.ajax.php',
            type: 'GET'
        }
    
    let options = config_datatable(ajaxoption);
    
    options.initComplete = function() {
        var api = this.api();
        
        api.columns().every(function() {
            var column = this;
            var header = $(column.header());
            var headerText = header.text();
            
            // Limpiar el encabezado y eliminar el <span>
            header.empty();
            
            // Crear contenedor del texto del encabezado
            var headerTextContainer = $('<div class="text-center">').appendTo(header);
            $('<span>').text(headerText).appendTo(headerTextContainer);
            
            // Lista de índices de columnas donde NO quieres mostrar el input de búsqueda
            var excludedColumns = [3];
            
            // Crear contenedor div para el input
            var inputContainer = $('<div>')
                .css({
                    'width': '100%',
                    'height': '100%',
                    'position': 'relative'
                })
                .appendTo(header);
            
            // Verificar si la columna actual está en la lista de excluidas
            if ($.inArray(column.index(), excludedColumns) === -1) {
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
                if (column.dataSrc() === 'activo') {
                    let select = generarSelectEstatus('estado');
                    $(select).appendTo(header).on('change', function() {
                        var val = ($(this).val());
                        column.search(val ? '^' + val + '$' : '', true, false).draw();
                    });
                }
            }
        });
    }

    options.columns = [
        {
            data: 'etiqueta',
            searchable: true,
            createdCell: function(td, cellData, rowData, row, col) {
                $(td).addClass('text-left').attr('data-label', 'label');
            },
            render: function(data, type, row) {
                return `<a onclick="ver_categoria_crm(${row.ID});" href="#">${data}</a>`
            }
        },
        {
            data: 'prioridad',
            searchable: true,
            createdCell: function(td, cellData, rowData, row, col) {
                $(td).addClass('text-left').attr('data-label', 'label');
            },
            render: function(data, type, row) {
                return `<a onclick="ver_categoria_crm(${row.ID});" href="#">${data}</a>`
            }
        },
        {
            data: 'estilo',
            createdCell: function(td, cellData, rowData, row, col) {
                $(td).addClass('text-left').attr('data-label', 'label');
            },
            render: function(data, type, row) {
                return `<a class="shadow-none badge badge-${data}" onclick="ver_categoria_crm(${row.ID});" href="#">${data}</a>`
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

    vtabla = $('#style-3').DataTable(options);

    let newButton = $('<button>')
        .html('<i class="fa-solid fa-plus"></i> Nueva Categoria')
        .addClass('btn btn-primary')
        .attr("type", "button")
        .on('click', function() {
            ver_categoria_crm();
        });
    setting_table(vtabla, [newButton])
}
  </script>

<script type="text/javascript">
      function crear_categoria_crm(event) {
          event.preventDefault();


          $('#label').removeClass("input_error");
          $('#prioridad').removeClass("input_error");

          // Recoger los valores del formulario usando jQuery
          const label = $('#label').val();
          const prioridad = $('#prioridad').val();

          let error = false; // Variable para rastrear si hay errores

          // Validar que los campos no estén vacíos y añadir la clase de error si es necesario
          if (label == '') {
              $('#label').addClass("input_error");
              error = true;
          }

          if (prioridad == '') {
              $('#prioridad').addClass("input_error");
              error = true;
          }

          // Si hay errores, mostrar notificación y detener el envío del formulario
          if (error) {
              add_notification({
                  text: 'Faltan Datos Obligatorios',
                  actionTextColor: '#fff',
                  backgroundColor: '#e7515a',
              });
              return true; // Evitar el envío del formulario
          }



          // Preparar la petición AJAX
          $.ajax({
              method: "POST",
              url: "<?php echo ENLACE_WEB; ?>mod_crm_categorias/ajax/crm_categorias.ajax.php",
              beforeSend: function(xhr) {

              },
              data: {
                  action: 'crear_categoria_crm',
                  label: label,
                  prioridad: prioridad,
                  estado: $("#estado_categoria").val(),
                  estilo: $("#estilo").val(),
              },
          }).done(function(msg) {
              console.log(msg);

              var mensaje = jQuery.parseJSON(msg);

              if (mensaje.exito == 1) {
                  $("#nuevo_categoria").modal('hide');

                  $('#style-3').DataTable().ajax.reload();

                  add_notification({
                      text: 'Categoría CRM creada exitosamente',
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

      function ver_categoria_crm(int = null) {
          // Preparar la petición AJAX
          $.ajax({
              method: "POST",
              url: "<?php echo ENLACE_WEB; ?>mod_crm_categorias/tpl/modal_crm_categorias.php",
              beforeSend: function(xhr) {
                  // aqui deberia ocurrir una carga
              },
              data: {
                  action: 'ver_categoria_crm',
                  fiche: int,
              },
          }).done(function(html) {
              //print html en el modal cargado
              $("#nuevo_categoria").html(html).modal('show');
          });
      }

      function actualizar_categoria_crm(int) {

          $('#label').removeClass("input_error");
          $('#prioridad').removeClass("input_error");

          // Recoger los valores del formulario usando jQuery
          const label = $('#label').val();
          const prioridad = $('#prioridad').val();

          let error = false; // Variable para rastrear si hay errores

          // Validar que los campos no estén vacíos y añadir la clase de error si es necesario
          if (label == '') {
              $('#label').addClass("input_error");
              error = true;
          }

          if (prioridad == '') {
              $('#prioridad').addClass("input_error");
              error = true;
          }

          // Si hay errores, mostrar notificación y detener el envío del formulario
          if (error) {
              add_notification({
                  text: 'Faltan Datos Obligatorios',
                  actionTextColor: '#fff',
                  backgroundColor: '#e7515a',
              });
              return true; // Evitar el envío del formulario
          }




          // Preparar la petición AJAX
          $.ajax({
              method: "POST",
              url: "<?php echo ENLACE_WEB; ?>mod_crm_categorias/ajax/crm_categorias.ajax.php",
              beforeSend: function(xhr) {

              },
              data: {
                  action: 'actualizar_categoria_crm',
                  label: label,
                  prioridad: prioridad,
                  estado: $("#estado_categoria").val(),
                  estilo: $("#estilo").val(),
                  id: int,
              },
          }).done(function(msg) {
              var mensaje = JSON.parse(msg);

              if (mensaje.exito === 1) {
                  add_notification({
                      text: 'Categoría CRM actualizada exitosamente',
                      actionTextColor: '#fff',
                      backgroundColor: '#00ab55',
                      dismissText: 'Cerrar'
                  });

                  $("#nuevo_categoria").modal('hide');

                  $('#style-3').DataTable().ajax.reload();

              } else {
                  add_notification({
                      text: "Error:" + mensaje.mensaje,
                      actionTextColor: '#fff',
                      backgroundColor: '#e7515a',
                  });
              }
          });
      }

      function confirma_eliminar($id) {


          document.getElementById('actualizar_categoria_crm').disabled = true;
          document.getElementById('borrar_categoria_crm').disabled = true;


          // Preparar el mensaje para el snackbar
          var message = "Está seguro(a) que desea eliminar la categoría CRM?";
          var actionText = "<strong onclick='ocultar_snackbar();' id='cancelar_borrado'>Cancelar</strong> <button onclick='aplicar_borrado(" + $id + ")' style='margin-left:5px;' id='confirmar_borrado' class='btn btn-danger'>Confirmar</button>";

          // Mostrar el snackbar y definir el callback para el botón de acción
          var snackbar = add_notification({
              text: message,
              width: 'auto',
              duration: 300000,
              actionText: actionText,
          });
      }



      function aplicar_borrado($id) {
          // Aquí va el código que se ejecutará cuando el usuario confirme
          $.ajax({
              method: "POST",
              url: "<?php echo ENLACE_WEB; ?>mod_crm_categorias/ajax/crm_categorias.ajax.php",
              beforeSend: function(xhr) {},
              data: {
                  action: 'borrar_categoria_crm',
                  id: $id
              },
          }).done(function(msg) {
              console.log(msg);

              var data = JSON.parse(msg);
              // VALID RESULT
              if (data.exito == 1) {
                  add_notification({
                      text: 'Categoría CRM eliminada exitosamente',
                      pos: 'top-right',
                      actionTextColor: '#fff',
                      backgroundColor: '#00ab55'
                  });

                  document.getElementById('actualizar_categoria_crm').disabled = false;
                  document.getElementById('borrar_categoria_crm').disabled = false;

                  $("#nuevo_categoria").modal('hide');
                  $('#style-3').DataTable().ajax.reload();
              }
          });
      }
      $(document).ready(function() {

            cargar_tabla__crm_categorias();


            // Desactivar todos los elementos del menú
            $(".menu").removeClass('active');

            $(".configuracion_general").addClass('active');
            $(".configuracion_general > .submenu").addClass('show');
            $("#crm_categoriass").addClass('active');




        });
  </script>

 


 