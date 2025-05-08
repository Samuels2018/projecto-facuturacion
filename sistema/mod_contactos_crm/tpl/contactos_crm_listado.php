

  <!-- Datatable -->
<style type="text/css">
  #style-3_filter{display: none !important;}
  #style-3_length{
        display: flex;
      }
      #export-buttons-container
      {
        margin-left: 25px;
      }
      #export-buttons-container button+button{
        margin-left: 15px;
      }
      #columnVisibilityContainer{
        margin-top: 40px !important;
    }


</style>
<?php
include_once(ENLACE_SERVIDOR . "mod_documentos_mercantiles/tpl/header_document.php");
?>
  <div class="middle-content container-fluid p-0">

      <!-- BREADCRUMB -->
      <div class="page-meta">
          <nav class="breadcrumb-style-one" aria-label="breadcrumb">
              <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Contactos</a></li>
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
                      <a href="<?php echo ENLACE_WEB ?>contactos_crm_nuevo" class="btn btn-primary">Nuevo Contacto</a>
                  </div>
              
              </div>
              <div class="widget-content widget-content-area br-8">

                  <form id="formulario">
                      <!-- Tabla  -->
                      <div class="table-responsive">
                          <table id="style-3" class="table style-3 dt-table-hover">
                              <thead>
                                  <tr>
                                      <th scope="col">Nombre</th>
                                      <th scope="col">Apellido</th>                                      
                                      <th scope="col">Tercero Asociado</th>
                                      <th scope="col">Cant. Oportunidades</th>
                                      <th scope="col">Email</th>
                                      <th scope="col">WhatsApp</th>
                                      <th scope="col">RRSS</th>
                                      <th scope="col">Fecha Nacimiento</th>
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

  </div>
  <!-- Scripts -->
  <?php include_once(ENLACE_SERVIDOR . "mod_documentos_mercantiles/tpl/footer_document.php"); ?>
  <script>


    function redirigirAOportunidades(contactoId) {
        
        /* guargar en localstorage el nombre del contacto clickeado */
        const dataNombre = vtabla.rows().data().filter(row => row.ID == contactoId)[0].Nombre
        if(localStorage.getItem('nombre_contacto') !== null){
            localStorage.removeItem('nombre_contacto')
        }
        localStorage.setItem('nombre_contacto', dataNombre)          
            $.ajax({
                url: '<?php echo ENLACE_WEB; ?>mod_crm/ajax/listado_oportunidad.ajax.php',
                method: 'POST',
                data: { contacto_id: contactoId },
                success: function(response) {
                    // Redirigir a la vista de oportunidades con el filtro aplicado
                    window.location.href = '<?php echo ENLACE_WEB; ?>oportunidades?contacto_id=' + contactoId;
                },
                error: function(xhr, status, error) {
                    console.error('Error al redirigir a oportunidades:', error);
                }
            });
        }

      function cargar_tabla__crm_terceros() {

        const ajaxoption = {
            url: '<?php echo ENLACE_WEB; ?>mod_contactos_crm/ajax/listado_contactos_crm.ajax.php',
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
                      var headerTextContainer = $('<div>').appendTo(header);
                      $('<span>').text(headerText).appendTo(headerTextContainer);

                      // Lista de índices de columnas donde NO quieres mostrar el input de búsqueda
                      var excludedColumns = [6, 7]; // Por ejemplo, para excluir las columnas 2, 4 y 6

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
                          // Para las columnas excluidas, agrega un div vacío para reservar el espacio
                          $('<div>').css({
                              'width': '100%',
                              'height': '30px', // Aumenta el tamaño del div vacío
                              'visibility': 'hidden' // Oculta el div pero mantiene su espacio
                          }).appendTo(inputContainer);
                      }
                  });
              },
              options.columns = [
                  {
                      data: 'Nombre',
                      searchable: true,
                      createdCell: function(td, cellData, rowData, row, col) {
                          $(td).attr('data-label', 'Nombre');
                      },
                      render: function(data, type, row) {
                          return `<a href="${ENLACE_WEB}/contactos_crm_editar/${row.ID}">${data}</a>`
                      }

                  },
                  {
                      data: 'Apellido',
                      searchable: true,
                      createdCell: function(td, cellData, rowData, row, col) {
                          $(td).attr('data-label', 'Apellido');
                      },
                       render: function(data, type, row) {
                          return `<a href="${ENLACE_WEB}/contactos_crm_editar/${row.ID}">${row.Apellido}</a>`
                      }
                  },
                  {
                      data: 'Nombre Tercero',
                      searchable: true,
                      createdCell: function(td, cellData, rowData, row, col) {
                          $(td).attr('data-label', 'Tercero Asociado');
                      },
                      render: function(data, type, row) {
                        if (row.Tercero == 0) {
                            return `<span class="">-</span>`;
                        } else {
                            if (row.Tipo == 1) {
                                return `<a href="${ENLACE_WEB}clientes_editar/${row.Tercero}" target="_blank"><span class="badge badge-light-secondary">${data}</span></a>`;
                            } else {
                                return `<a href="${ENLACE_WEB}proveedores_editar/${row.Tercero}" target="_blank"><span class="badge badge-light-success">${data}</span></a>`;
                            }
                        }

                      }
                  },
                  
                  {
                    data: 'Cantidad Oportunidades', // Nueva columna para la cantidad de oportunidades
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr('data-label', 'Oportunidades');
                    },
                    render: function(data, type, row) {
                        if(data == 0){
                            /* return `<span class="badge badge-light-danger">Sin Oportunidades</span>`; */
                            return `<a href="javascript:void(0);" onclick="redirigirAOportunidades(${row.ID})">${data}</a>`;
                        }else{
                            return `<a href="javascript:void(0);" onclick="redirigirAOportunidades(${row.ID})">${data}</a>`;
                        }
                    }
                  },
                  {
                      data: 'Email',
                      searchable: true,
                      createdCell: function(td, cellData, rowData, row, col) {
                          $(td).attr('data-label', 'Email');
                      },
                       render: function(data, type, row) {
                          return `<a href="${ENLACE_WEB}/contactos_crm_editar/${row.ID}">${row.Email}</a>`
                      }
                  },
                  {
                      data: 'WhatsApp',
                      searchable: true,
                      createdCell: function(td, cellData, rowData, row, col) {
                          $(td).attr('data-label', 'WhatsApp');
                      },
                      render: function(data, type, row) {
                          return `<a href="${ENLACE_WEB}/contactos_crm_editar/${row.ID}">${row.WhatsApp}</a>`
                      }
                  },
                  {
                      data: 'Facebook',
                      searchable: true,
                      //   render: function(data, type, row) {
                      //       return `<a href="${data}" target="_blank">${data}</a>`;
                      //   },
                      createdCell: function(td, cellData, rowData, row, col) {
                          $(td).attr('data-label', 'RRSS');
                      },
                      render: function(data, type, row) {
                          let rrss = '';
                          console.log(row);
                          if (row.Facebook != '' && row.Facebook != null ) {
                              rrss += `<a href="https://www.facebook.com/${row.Facebook}" target="_blank">
                                        <i class="fa fa-fw fa-facebook"></i>
                                    </a>`
                          } else {
                              //rrss += `<i class="fa fa-fw fa-facebook"></i>`
                              rrss+'';
                          }

                          if (row.Instagram != '' && row.Instagram != null) {
                              rrss += `<a href="https://instagram.com/${row.Instagram}" target="_blank">
                                        <i class="fa fa-fw fa-instagram"></i>
                                    </a>`;
                          } else {
                              //rrss += `<i class="fa fa-fw fa-instagram"></i>`
                             rrss+'';
                          }

                          if (row.Twitter != '' && row.Twitter != null) {
                              rrss += `<a href="https://twitter.com/${row.Twitter}" target="_blank">
                                        <i class="fa fa-fw fa-twitter"></i>
                                    </a>`;
                          } else {
                            //  rrss += `<i class="fa fa-fw fa-twitter"></i>`;
                             rrss+'';
                          }

                          if (row.LinkedIn != '' && row.LinkedIn!=null) {

                              rrss += `<a href="https://www.linkedin.com/${row.LinkedIn}" target="_blank"><i class="fa fa-fw fa-linkedin"></i></a>`;
                          } else {
                             // rrss += `<i class="fa fa-fw fa-linkedin"></i>`
                             rrss+'';
                          }
                          //   anagomez
                          return rrss;
                      }
                  },
                  {
                      data: 'Fecha Nacimiento',
                      searchable: true,
                      createdCell: function(td, cellData, rowData, row, col) {
                          $(td).attr('data-label', 'Fecha Nacimiento');
                      },
                      render: function(data, type, row) {

                        if (data) {
                            return `
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg> ${data}
                          `;
                        }else{
                            return '';
                        }
                         
                      }
                  }
              ]
          

              
        

        vtabla = $('#style-3').DataTable(options)
        
        setting_table(vtabla, []);  
      


    }



  </script>






    <script>
      $(document).ready(function() {

          cargar_tabla__crm_terceros();

          $(".menu").removeClass('active');

          $(".contactos").addClass('active');
        



      });
  </script>
