<!-- Include jQuery Roadmap Plugin -->
<script src="<?= ENLACE_WEB ?>bootstrap/jquery.roadmap.js" type="text/javascript"></script>
<link rel="stylesheet" href="<?= ENLACE_WEB ?>bootstrap/plugins/roadmap.css">

<?php

require_once ENLACE_SERVIDOR . 'mod_lead/object/lead.object.php';

$lead = new Lead($dbh, $_SESSION['Entidad']);



$lead->fetch($_GET['id']);

// var_dump($lead);


$accion = 'oportunidades';

?>

<div class="row mt-3" style="">
  <div class="col-xs-12">
    <div class="card">

      <div class="card-body table-responsive no-padding">


        <div class="row">
          <div class="col-md-12">
            <input type="hidden" name="editar" id="editar" value="1">
            <input type="hidden" name="txtTipoCotizacion" id="txtTipoCotizacion" value="  ">
            <input type="hidden" name="txtShipper" id="txtShipper" value="  ">
            <input type="hidden" name="id" id="id" value="  ">

            <div class="row">
              <div class="col-md-3">
                REFERENCIA :
              </div>
              <div class="col-md-3">

                <label for=""><?=$lead->rowid?></label>
              </div>


             
            


            </div>


            <div class="row">
              <div class="col-md-3">
                NOMBRE CLIENTE :
              </div>
              <div class="col-md-3">

                <label for=""><?=$lead->cliente?></label>
              </div>

              <div class="col-md-3">
                TAGS :
              </div>
              <div class="col-md-3">
               <select class="form-control" name="" id=""></select>

              </div>


            </div>


            <div class="row">
              <div class="col-md-3">
                CONTACTO :
              </div>
              <div class="col-md-3">

                <label for=""><?=$lead->contacto?></label>
              </div>
            </div>


            <div class="row">
              <div class="col-md-3">
                CORREO :
              </div>
              <div class="col-md-3">

                <label for=""><?=$lead->contacto_correo?></label>
              </div>

              <div class="col-md-3">
                    TELEFONO :
                  </div>
                  <div class="col-md-3">
                    <?=$lead->contacto_telefono?> </div>

            </div>



            <div class="row">
              <div class="col-md-3">
                AGENTE :
              </div>
              <div class="col-md-3">

                <label for="">All Logistics PTY</label>
              </div>
            </div>



        

 

            <div class="row">
              <div class="col-md-3">
                <i class="fa fa-user"></i> User :
              </div>
              <div class="col-md-3">
                <label for="">Carlos Martinez</label>

              </div>

              <div class="col-md-3"></div>
              <div class="col-md-3"></div>
            </div>



            <div class="row">
              <div class="col-md-3">
                <i class="fa fa-compass"></i> Status :
              </div>
              <div class="col-md-3">
                <b for="" class="label label-info">Solicitud de Costos</b>

              </div>

              <div class="col-md-3"></div>
              <div class="col-md-3"></div>
            </div>







            <div class="row">
              <div class="col-md-3">


              </div>
              <br>
            </div>

            <div class="col-md-2"></div>

          </div>
        </div>








        <!-- tabs-->
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Timeline</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false" tabindex="-1">Tareas</button>
          </li>

        </ul>

        <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade active show" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">

            <div class="col-md-12">
              <h3 class="text-center"><strong>Timeline</strong></h3>
              <h4 class="text-center"><strong id="cantidadActividades">0</strong> Actividades registradas </h4>
              <div class="text-center mt-5" id="my-roadmap"></div>
            </div>

          </div>



          <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">



            <button type="button" style="cursor:pointer;float:right" class="btn btn-primary pull-right" data-bs-toggle="modal" data-bs-target="#taskModal">
              <i class="fa fa-plus" aria-hidden="true"></i>Agregar tarea
            </button>

            <table id="listing-table" class="table table-striped">
              <thead>
                <tr>


                  <th>Tipo Tarea</th>
                  <th>Fecha Vencimiento</th>
                  <th>Dias Vencimiento</th>
                  <th>Usuario Responsable</th>
                  <th>Estado</th>
                </tr>

              </thead>
              <tbody id="supplierTable">
                <?php # include_once(ENLACE_SERVIDOR . "mod_funnel/ajax/listado_actividades.ajax.php");

                ?>
              </tbody>
            </table>


          </div>

        </div>


      </div>









    </div>



  </div>


</div>






</div>






</div>








<script>
  $.getJSON('<?= ENLACE_WEB ?>/mod_funnel/ajax/timeline.ajax.php?term=<?=$lead->rowid?>', function(data) {
    //data is the JSON string

    console.log(data.length);
    $("#cantidadActividades").text(data.length);
    var the_json = data;
    $(document).ready(function() {
      paginarTareas(0);

      var events = the_json;
      //console.log(events);

      $('#my-roadmap').roadmap(events, {
        eventsPerSlide: 10,
        slide: 1,
        orientation: 'auto',
        prevArrow: '<i class="material-icons">keyboard_arrow_left</i>',
        nextArrow: '<i class="material-icons">keyboard_arrow_right</i>',
        eventTemplate: '<div class="event">' + '<div class="event__date">####DATE###</div>' + '<div class="event__content">####ESTATUS###</div>' + '<div style="cursor:pointer"; onclick="(####ROWID###);" class="event__content">####CONTENT###</div>' + '<div onclick="(####ROWID###);"  class="event__content actividy_####ROWID_A###"><i style="cursor:pointer" class="fa fa-pencil" aria-hidden="true"></i></div>' + '</div>',
        onBuild: function() {
          console.log('onBuild event')
        }
      });
    });
  });

  function guardarTarea() {
    $("#vencimiento_fecha").removeClass('input_error');

    if ($("#vencimiento_fecha").val() == '') {
      $("#vencimiento_fecha").addClass('input_error');
      return false;
    }

    // guardar una actividad
    $.ajax({
      method: "POST",
      url: "<?= ENLACE_WEB ?>mod_funnel/class/funnel.class.php",
      beforeSend: function(xhr) {},
      data: {
        action: 'guardarTarea',
        fk_oportunidad: <?=$lead->rowid?>,
        fk_diccionario_actividad: $("#fk_diccionario_actividad").val(),
        vencimiento_fecha: $("#vencimiento_fecha").val(),
        fk_usuario_asignado: $("#fk_usuario_asignado").val(),
        comentario: $("#comentario").val(),

      },
    }).done(function(data) {
      //  data = JSON.parse(data);
      console.log(data);
      if (data) {
        $("#taskModal").modal('hide');
        paginarTareas(0);
      }

    });



  }

  function actualizarTarea(int) {

    // guardar una actividad
    $.ajax({
      method: "POST",
      url: "<?= ENLACE_WEB ?>mod_funnel/class/clases.php",
      beforeSend: function(xhr) {},
      data: {
        action: 'actualizarTarea',
        rowid: int,
        fk_estado: $("#edit_estado_tarea").val(),
        comentario: $("#edit_comentario_tarea").val(),
        comentario_cierre: $("#edit_comentario_cierre_tarea").val(),


      },
    }).done(function(data) {
      //  data = JSON.parse(data);
      console.log(data);
      if (data) {
        $("#modal_editar_tarea").modal('hide');
        paginarTareas(0);
      }

    });



  }

  function paginarTareas(int) {

    // traer tpl
    $.ajax({
      method: "POST",
      url: "<?= ENLACE_WEB ?>mod_funnel/ajax/listado_actividades.ajax.php?id=<?=$lead->rowid?>",
      beforeSend: function(xhr) {},
      data: {
        pagina: int,
      },
    }).done(function(data) {
      //pintar tpl en modal y mostrarlo
      $("#supplierTable").html(data);


    });

  }

  function editarTarea(int) {

    // traer tpl
    $.ajax({
      method: "POST",
      url: "<?= ENLACE_WEB ?>mod_funnel/ajax/editarTarea.php",
      beforeSend: function(xhr) {},
      data: {
        action: 'editarTarea',
        rowid: int,
      },
    }).done(function(data) {
      //pintar tpl en modal y mostrarlo
      $("#modal_editar_tarea").html(data);
      $("#modal_editar_tarea").modal('show');

    });

  }

  $(document).ready(function() {
    var listaDeOpciones = <?= json_encode($arrayRecuperado) ?>;
    $('#other_services').select2(); // Inicializa Select2 
    $('#other_services').val(listaDeOpciones); // Establece las opciones seleccionadas
    $('#other_services').trigger('change'); // Notifica a Select2 para actualizar el estado
    $('#other_services').prop("disabled", true);
  });


  $(document).ready(function() {
    var listaDeOpciones = <?= json_encode($arrayRecuperado) ?>;
    var listaDeOpcionesTags = <?= json_encode($arrayRecuperadoTags) ?>;


    $('#other_services').select2(); // Inicializa Select2 
    $('#other_services').val(listaDeOpciones); // Establece las opciones seleccionadas
    $('#other_services').trigger('change'); // Notifica a Select2 para actualizar el estado
    $('#other_services').prop("disabled", true);

    $('#tags').select2();
    $('#tags').val(listaDeOpcionesTags); // Establece las opciones seleccionadas
    $('#tags').trigger('change'); // Notifica a Select2 para actualizar el estado
    // Inicializa Select2 tags
    $('#tags').select2({
      tags: true, // Habilitar la entrada de texto
      tokenSeparators: [',', ' '] // Separadores para nuevas etiquetas
    });

    $('#tags').prop("disabled", true);

  });

  $(document).on('change', '#edit_estado_tarea', function() {
    console.log($(this).val())
    if ($(this).val() != 3) {
      $('.div_cierre').attr('style', 'display:none !important');

    } else {
      $('.div_cierre').removeAttr('style');
    }
  })
</script>

<div class="modal fade" id="modal_editar_tarea" tabindex="-1" role="dialog" aria-labelledby="taskTitle" aria-hidden="true">

</div>

<!-- Modal tasks-->
<div class="modal fade" id="taskModal" tabindex="-1" aria-labelledby="taskModalTitle" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="taskModalTitle">Nueva tarea</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        </button>
      </div>
      <div class="modal-body">

        <div class="form-group">


          <div class="form-row">
            <div class="form-group col-md-12">
              <label for="actividad">Tipo actividad:</label>
              <select id="fk_diccionario_actividad" name="fk_diccionario_actividad" class="form-control">
               <?php
               
               foreach ($lead->diccionarioActividades() as $item) {
                echo ' <option value="' . $item->rowid . '">' . $item->nombre . '</option>';
              }
               
               ?>
              </select>
            </div>

            <div class="form-group col-md-12">
              <label for="">Fecha vencimiento:</label>
              <input type="date" name="vencimiento_fecha" id="vencimiento_fecha" class="form-control">
            </div>


            <div class="form-group col-md-12">
              <label for="">Usuario responsable:</label>
              <select class="form-control" id="fk_usuario_asignado" name="fk_usuario_asignado">

             
              <?php

foreach ($lead->usuarios_disponibles() as $item) {
  echo ' <option value="' . $item->rowid . '">' . $item->nombre . '</option>';
}


?>
              </select>
            </div>

            <div class="form-group col-md-12">
              <label for="">Comentario:</label>
              <textarea class="form-control" name="comentario" id="comentario" cols="30" rows="5"></textarea>
            </div>

          </div>




        </div>

      </div>
      <div class="modal-footer">
        <button class="btn btn-light-dark _effect--ripple waves-effect waves-light" data-bs-dismiss="modal">Cancelar</button>
        <button onclick="guardarTarea();" type="button" class="btn btn-primary _effect--ripple waves-effect waves-light">Guardar</button>
      </div>
    </div>
  </div>
</div>