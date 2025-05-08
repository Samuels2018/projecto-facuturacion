<!-- Include jQuery Roadmap Plugin -->
<script src="<?= ENLACE_WEB ?>bootstrap/jquery.roadmap.js" type="text/javascript"></script>
<link rel="stylesheet" href="<?= ENLACE_WEB ?>bootstrap/plugins/roadmap.css">


<?php

require_once ENLACE_SERVIDOR . 'mod_redhouse_cotizaciones/object/redhouse.cotizaciones.object.php';
require_once ENLACE_SERVIDOR . 'mod_terceros/object/terceros.object.php';

$Cotizacion = new redhouse_Cotizacion($dbh, $_SESSION['Entidad']);
$Terceros   = new FiTerceros($dbh, $_SESSION['Entidad']);
$Terceros->obtener_listado_terceros();


$Utilidades = new Utilidades($dbh);

$Cotizacion->fetch($_GET['fiche']);



$accion = ($Cotizacion->id > 0 ) ?  $Cotizacion->cotizacion_referencia :'Nueva Cotización' ;
$accion_boton = ($Cotizacion->id > 0 ) ?  "Guardar Cambios ".$Cotizacion->cotizacion_referencia : 'Crear Nueva Cotización' ;


//vamos a obtener los diseñadores de una entidad
$diseñadores = $Usuarios->obtener_disenadores($_SESSION['Entidad']);
    
    

//Tasa del dia 
$tasa_colones = $Entidad->obtener_tipo_cambio_actual_entidad();
$tipo_cambio = $tasa_colones[0]['venta'];





$html_select_contacto = '';
   if(isset($_GET['id']))
   {
      $cliente = new FiTerceros($dbh,$_SESSION['Entidad']);
      $cliente->rowid = $Cotizacion->fk_tercero; 
      $result = $cliente->obtener_listado_contactos();
        foreach($result as $key => $value){
            $selected = '';
            if(intval($result[$key]['rowid']) === intval($Cotizacion->fk_tercero_contacto))
            {
               $selected = 'selected=selected';
            }
            $html_select_contacto.='<option  '.$selected.' value="'.$result[$key]["rowid"].'">'.$result[$key]["nombre"].' '.$result[$key]["apellidos"].'</option>';
        }
   }


?>

<input type="hidden" id="cotizacion_id" value="<?php echo $Cotizacion->id;?>" >

<div class="page-meta">
    <nav class="breadcrumb-style-one" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Cotizaciones</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $accion; ?></li>
        </ol>
    </nav>
</div>


<div class="row mt-3">
  <div class="col-xs-12">

    <!-- ESTA ES LA PRIMERA SECCIÓN-->
     <div class="card">
      <div class="card-body table-responsive no-padding">
        <div class="row">
          <div class="col-md-6">
            <?php if ($Cotizacion->id > 0 ){  ?>  
            <div class="form-group row mt-4">
              <div class="col-md-12">
                <h4>Referencia:</h4>
              </div>
              <div class="col-md-12">
                <label for=""><strong><?php echo $Cotizacion->cotizacion_referencia; ?></strong></label>
              </div>
            </div>
            <?php }  ?>
            
            <div class="form-group row mt-4">
              <div class="col-md-12">
                <label for="nombre"><i class="fa fa-fw fa-calendar" aria-hidden="true"></i> Fecha</label>
                <input required="required" type="date" name="fecha" class="form-control" id="cotizacion_fecha" value="<?php echo $Cotizacion->cotizacion_fecha ; ?>">
              </div>
            </div>
            
            <div class="form-group row">
              <div class="col-md-12">
                <label for="apellidos"><i class="fa fa-fw fa-user" aria-hidden="true"></i> Cliente</label>
                <select class="form-control" id="fk_tercero">
                  <option value="0">Selecciona Cliente</option>
                  <?php foreach ($Terceros->obtener_listado_terceros as $cliente ){
                    echo "<option ". ( ($Cotizacion->fk_tercero == $cliente->rowid) ? "selected='selected'" :"" ) ." value='".$cliente->rowid."' >".$cliente->nombre_cliente."</option>";
                  } ?>
                </select>
              </div>


              <div class="col-md-12">
                    <label for="validez"><i class="far fa-file-code"></i> Contacto</label>
                    <select class="form-control" id="fk_contacto" name="fk_contacto">
                    <option value="0">Seleccione</option>
                        <?php echo $html_select_contacto; ?>
                    </select>
              </div>
    
            </div>
            
            <div class="form-group row">
              
              <!--<input type="hidden" name="fk_categoria" id="fk_categoria" value="0">-->

              <div class="col-md-12" style="display: none;">
                <label for="fk_categoria"><i class="fa fa-fw fa-square" aria-hidden="true"></i> Categorías</label>
                <select class="form-control" id="fk_categoria">
                  <option value="0">Selecciona Categoría</option>
                  <?php foreach ($Cotizacion->obtener_listado_categorias() as $categorias ){
                    echo "<option ". ( ($Cotizacion->fk_categoria == $categorias['rowid']) ? "selected='selected'" :"" ) ." value='".$categorias['rowid']."' >".$categorias['etiqueta']."</option>";
                  } ?>
                </select>
              </div>
              <div class="col-md-12">
                <label for="fk_moneda"><i class="fa fa-fw fa-square" aria-hidden="true"></i> Moneda a ofertar</label>
                <select class="form-control" id="fk_moneda">
                  <option value="0">Selecciona Moneda</option>
                  <?php foreach ($Utilidades->obtener_monedas() as $key =>  $monedas ){
                    echo "<option ". ( ($Cotizacion->fk_moneda == $key ) ? "selected='selected'" :"" ) ." value='".$key."' >".$monedas['etiqueta']."</option>";
                  } ?>
                </select>
              </div>
            </div>
            
            <div class="form-group row">
              <div class="col-md-12">
                <label for="cotizacion_tiempo_entrega"><i class="far fa-file-code"></i> Tiempo de Entrega (Días hábiles)</label>
                <select class="form-control" id="cotizacion_tiempo_entrega">
                  <option value=""> </option>
                  <option value="1" <?php echo ($Cotizacion->cotizacion_tiempo_entrega ==1) ? 'selected = "selected" ':"" ; ?>>1</option>
                  <option value="7" <?php echo ($Cotizacion->cotizacion_tiempo_entrega ==7) ? 'selected = "selected" ':"" ; ?>>7</option>
                  <option value="15" <?php echo ($Cotizacion->cotizacion_tiempo_entrega ==15) ? 'selected = "selected" ':"" ; ?>>15</option>
                  <option value="30" <?php echo ($Cotizacion->cotizacion_tiempo_entrega ==30) ? 'selected = "selected" ':"" ; ?>>30</option>
                  <option value="45" <?php echo ($Cotizacion->cotizacion_tiempo_entrega ==45) ? 'selected = "selected" ':"" ; ?>>45</option>
                  <option value="60" <?php echo ($Cotizacion->cotizacion_tiempo_entrega ==60) ? 'selected = "selected" ':"" ; ?>>60</option>
                </select>
              </div>
              <div class="col-md-12">
                <label for="cotizacion_validez_oferta"><i class="far fa-file-code"></i> Validez de la Oferta (Días hábiles)</label>
                <select class="form-control" id="cotizacion_validez_oferta">
                  <option value=""> </option>
                  <option value="1" <?php echo ($Cotizacion->cotizacion_validez_oferta ==1) ? 'selected = "selected" ':"" ; ?>>1</option>
                  <option value="7" <?php echo ($Cotizacion->cotizacion_validez_oferta ==7) ? 'selected = "selected" ':"" ; ?>>7</option>
                  <option value="15" <?php echo ($Cotizacion->cotizacion_validez_oferta ==15) ? 'selected = "selected" ':"" ; ?>>15</option>
                  <option value="30" <?php echo ($Cotizacion->cotizacion_validez_oferta ==30) ? 'selected = "selected" ':"" ; ?>>30</option>
                  <option value="45" <?php echo ($Cotizacion->cotizacion_validez_oferta ==45) ? 'selected = "selected" ':"" ; ?>>45</option>
                  <option value="60" <?php echo ($Cotizacion->cotizacion_validez_oferta ==60) ? 'selected = "selected" ':"" ; ?>>60</option>
                </select>
              </div>
            </div>
            
            <div class="form-group row">
              <div class="col-md-12">
                <label for="fk_usuario_asignado"><i class="far fa-file-code"></i> Usuario Asignado a Cotización</label>
                <select class="form-control" id="fk_usuario_asignado">
                  <option value=""> </option>
                  <?php 
                    $Cotizacion->usuarios_disponibles();
                    foreach($Cotizacion->usuarios_disponibles as $valor){
                      echo "<option " . (($Cotizacion->fk_usuario_asignado == $valor->rowid) ? 'selected = "selected"' : "") . " value='" . $valor->rowid . "'>" . $valor->nombre . "</option>";
                    } 
                  ?>
                </select>
              </div>
              <div class="col-md-12">
                <label for="a_medida_redhouse_cotizaciones_recurso_humano"><i class="far fa-user"></i> Diseñadores Asignados</label>
                <select class="form-control select2" name="a_medida_redhouse_cotizaciones_recurso_humano" multiple='multiple' id="a_medida_redhouse_cotizaciones_recurso_humano">
                  <?php         
                      // Supongamos que $diseñadores es el resultado de la función obtener_disenadores()
                      $todos_usuarios_disponibles = $diseñadores;
                      
                      // Obtener los usuarios seleccionados para la cotización específica
                      $seleccionados_usuarios_disponibles = $Cotizacion->obtener_recurso_humano($Cotizacion->id);

                      // Recorrer todos los usuarios disponibles (diseñadores)
                      foreach ($todos_usuarios_disponibles as $usuario) {
                          $rowid = $usuario->rowid;
                          $nombre_completo = $usuario->nombre . ' ' . $usuario->apellidos;

                          // Verificar si el usuario está seleccionado
                          $selected = isset($seleccionados_usuarios_disponibles[$rowid]) ? 'selected="selected"' : '';

                          // Imprimir la opción del select
                          echo "<option $selected value='$rowid'>$nombre_completo</option>";
                      } 
                  ?>
                  </select>
              </div>
            </div>
            
            <div class="form-group row">
              <div class="col-md-12">
                <label for="cotizacion_nota">Nota</label>
                <textarea id="cotizacion_nota" class="form-control" rows="4" placeholder="✔ Notas"><?php echo $Cotizacion->cotizacion_nota; ?></textarea>
              </div>
            </div>

            
            <div class="form-group row mt-4">
              <div class="col-md-12">
                <i class="fa fa-compass"></i> Status:
              </div>
              <div class="col-md-12">
                <select id="fk_estado_a_medida_redhouse_estado_cotizaciones" class="form-control">
                  <?php 
                    foreach ($Cotizacion->obtener_listado_estados() as $id => $valor){
                      echo "<option value='".$id."' ". ( ($Cotizacion->fk_estado_a_medida_redhouse_estado_cotizaciones == $id) ? "selected='selected'" :"" ) ."  >".$valor['etiqueta']."</option>";
                    }
                  ?>
                </select>
              </div>
            </div>
            
          </div>
          
          <div class="col-md-6">

             <div class="col-md-12 mt-4">
                <label for="tags">TAGS :</label>
                <select class="form-control select2" name="tags" multiple='multiple' id="tags">
                  <?php
                    $arrayRecuperadoTags = explode(",", $Cotizacion->cotizacion_tags);
                    foreach ($arrayRecuperadoTags as $item => $value) {
                      if($value === '') continue; 
                      echo ' <option value="' . $value . '">' . $value . '</option>';
                    }
                  ?>
                </select>
              </div>
            
              <div class="col-md-12 mt-4">
                                                          <label for="validez">
                                                          <i class="far fa-file-code" aria-hidden="true"></i>
                                                          Tipo Cotización
                                                          </label>
                                                    <select class="form-control" id="cotizacion_tipo_oferta">
                                                          <option value="1"> Normal </option>
                                                          <option value="2"> SICOP u otras plataformas </option>
                                                  </select>
                                                </div>

              <div class="col-md-12 mt-4">
                <label for="cotizacion_proyecto"><i class="far fa-file-code"></i> Proyecto:</label>
                <input class="form-control" value="<?php echo $Cotizacion->cotizacion_proyecto; ?>" type="text" name="cotizacion_proyecto" id="cotizacion_proyecto">
              </div>


              <div class="col-md-12 mt-4">
                <label for="cotizacion_descripcion_proyecto"><i class="far fa-file-code"></i> Descripción del proyecto:</label>
                <textarea   id="cotizacion_descripcion_proyecto" name="cotizacion_descripcion_proyecto" class="form-control" rows="4" placeholder="Introduce la descripción del proyecto"><?php echo $Cotizacion->cotizacion_descripcion_proyecto; ?></textarea>
              </div>
              


              <div class="col-md-12 mt-4">
                <label for="cotizacion_lugar_proyecto"><i class="far fa-file-code"></i>Lugar:</label>
                <input class="form-control" value="<?php echo $Cotizacion->cotizacion_lugar_proyecto; ?>" type="text" name="cotizacion_lugar_proyecto" id="cotizacion_lugar_proyecto">
              </div>


              <div class="col-md-12 mt-4">
                <label for="cotizacion_fecha_proyecto"><i class="far fa-file-code"></i>Fecha :</label>
                <input class="form-control" value="<?php echo $Cotizacion->cotizacion_fecha_proyecto; ?>" type="datetime-local" name="cotizacion_fecha_proyecto" id="cotizacion_fecha_proyecto">
              </div>

               <!-- 
              <div class="col-md-12 mt-4">
                <label for="cotizacion_contacto_proyecto"><i class="far fa-file-code"></i>Contacto:</label>
                <input class="form-control" value="<?php echo $Cotizacion->cotizacion_contacto_proyecto; ?>" type="text" name="cotizacion_contacto_proyecto" id="cotizacion_contacto_proyecto">
              </div>-->


          </div>

        </div>
        
        <div class="row mt-3">
          <div class="col-md-12">
            <?php $href= ($Cotizacion->id > 0 ) ? 'redhouse_cotizaciones_detalle/'. $Cotizacion->id : "redhouse_cotizaciones" ;  ?>
            <a href="<?php echo ENLACE_WEB.$href; ?>" class="btn btn-outline-primary">Volver</a>
            <button href="<?php echo ENLACE_WEB; ?>redhouse_cotizaciones_detalle_modificar/<?php echo $Cotizacion->id; ?>" class="btn btn-primary _effect--ripple waves-effect waves-light" onClick="guardar_cotizacion()"><?php echo $accion_boton; ?></button>
          </div>
        </div>
      </div>
      </div>
    <!-- seccion 1 Cierre-->


  </div>
</div>




 <script>


     //var listaDeOpciones = [<?php echo $listaDeOpciones; ?>];
       $("#fk_tercero").change(function()
       {
            fk_tercero = $(this).val();


             $.ajax({
                   method: "POST",
                   url: "<?php echo ENLACE_WEB; ?>mod_terceros/class/terceros.class.php",
                   beforeSend: function(xhr) {
                   },
                   
                   data: {
                       "action"        : "buscar_contactos_cliente"         ,
                       fk_tercero   : fk_tercero,
                   },
               }).done(function(data) {
                    $("#fk_contacto").html(data);
                   // Aquí puedes añadir código para actualizar la interfaz de usuario si es necesario
               }).fail(function(jqXHR, textStatus, errorThrown) {
                   console.error("Error en la petición AJAX:", textStatus, errorThrown);
   
                   add_notification({
                       text: 'Error con la Peticion - Vuelve a Intentarlo',
                       actionTextColor: '#fff',
                       backgroundColor: '#e7515a'
                   });
               });

       });



      function busqueda_contactos_defecto()
      {

             $.ajax({
                   method: "POST",
                   url: "<?php echo ENLACE_WEB; ?>mod_terceros/class/terceros.class.php",
                   beforeSend: function(xhr) {
                   },
                   
                   data: {
                       "action"        : "buscar_contactos_cliente"         ,
                       fk_tercero   :  $("#fk_tercero").val(),
                       fk_tercero_selected: "<?php echo $Cotizacion->fk_tercero_contacto; ?>"
                   },
               }).done(function(data) {
                    $("#fk_contacto").html(data);
                   // Aquí puedes añadir código para actualizar la interfaz de usuario si es necesario
               }).fail(function(jqXHR, textStatus, errorThrown) {
                   console.error("Error en la petición AJAX:", textStatus, errorThrown);
   
                   add_notification({
                       text: 'Error con la Peticion - Vuelve a Intentarlo',
                       actionTextColor: '#fff',
                       backgroundColor: '#e7515a'
                   });
               });

      }
      busqueda_contactos_defecto();


    function guardar_cotizacion(){
            error = false;


            var fk_moneda                     = $('#fk_moneda'   ).val();
            var cotizacion_fecha              = $('#cotizacion_fecha'   ).val();
            var cotizacion_id                 = $('#cotizacion_id'      ).val();
            var fk_tercero                    = $('#fk_tercero'      ).val();
            var fk_contacto                   = $("#fk_contacto").val();
            var fk_estado_a_medida_redhouse_estado_cotizaciones    = $("#fk_estado_a_medida_redhouse_estado_cotizaciones ").val();
            var tags                          = $('#tags'      ).val();
            var cotizacion_validez_oferta     = $("#cotizacion_validez_oferta").val();
            var cotizacion_tiempo_entrega     = $("#cotizacion_tiempo_entrega").val();
            var cotizacion_nota               = $("#cotizacion_nota").val();
            var fk_categoria                  = $("#fk_categoria").val();
            var fk_usuario_asignado           = $("#fk_usuario_asignado").val();
            var a_medida_redhouse_cotizaciones_recurso_humano = $("#a_medida_redhouse_cotizaciones_recurso_humano").val();
            var cotizacion_tipo_oferta        = $("#cotizacion_tipo_oferta").val();
            
            var cotizacion_proyecto = $("#cotizacion_proyecto").val();
            var cotizacion_descripcion_proyecto = $("#cotizacion_descripcion_proyecto").val();

            var  cotizacion_lugar_proyecto = $("#cotizacion_lugar_proyecto").val();
            var  cotizacion_fecha_proyecto = $("#cotizacion_fecha_proyecto").val();
            var  cotizacion_contacto_proyecto = $("#cotizacion_contacto_proyecto").val();


            $("#fk_estado_a_medida_redhouse_estado_cotizaciones").removeClass("input_error");
            
            $("#cotizacion_fecha").removeClass("input_error");
            $("#fk_tercero").removeClass("input_error");
          
            
            if (cotizacion_fecha == ''             ){$("#cotizacion_fecha").addClass("input_error");  error=true;  }
            if (fk_tercero       == 0              ){$("#fk_tercero").addClass("input_error");  error=true;  }
            if (fk_estado_a_medida_redhouse_estado_cotizaciones    == 0 ){
              $("#fk_estado_a_medida_redhouse_estado_cotizaciones").addClass("input_error");  error=true;  
            }
          



            if(fk_moneda == 0 )
            {
              $("#fk_moneda").addClass("input_error");  error=true;  
            }


            if(cotizacion_proyecto=='')
            {
                $("#cotizacion_proyecto").addClass("input_error");  
                error=true;   
            }
            if($("#cotizacion_fecha_proyecto").val()=='')
            {
                $("#cotizacion_fecha_proyecto").addClass("input_error");
                error=true;   
            }
            if($("#cotizacion_lugar_proyecto").val() == '')
            {   
                $("#cotizacion_lugar_proyecto").addClass('input_error');
                error = true;
            }

            if (error){
                        add_notification({
                            text: 'Corrigue los datos faltantes para continuar',
                            actionTextColor: '#fff',
                            backgroundColor: '#e7515a',
                            dismissText: 'Cerrar'
                        });

                        return false;
            }




            $.ajax({
                    method: "POST",
                    url: "<?php echo ENLACE_WEB; ?>mod_redhouse_cotizaciones/class/class.php",
                    beforeSend: function(xhr) {
                
                    },
                    
                    data: {
                        "action"        : "guardar"         ,
                        cotizacion_id   : cotizacion_id     ,
                        cotizacion_fecha: cotizacion_fecha  ,
                        fk_tercero      : fk_tercero        ,
                        fk_contacto     : fk_contacto ,
                        fk_estado_a_medida_redhouse_estado_cotizaciones :fk_estado_a_medida_redhouse_estado_cotizaciones ,
                        tags            :tags ,
                        cotizacion_tiempo_entrega : cotizacion_tiempo_entrega ,
                        cotizacion_validez_oferta :cotizacion_validez_oferta  ,
                        cotizacion_nota           : cotizacion_nota           ,
                        fk_categoria              : fk_categoria              ,
                        fk_usuario_asignado       : fk_usuario_asignado       ,
                        a_medida_redhouse_cotizaciones_recurso_humano  :a_medida_redhouse_cotizaciones_recurso_humano ,
                        cotizacion_tipo_oferta    : cotizacion_tipo_oferta ,
                        fk_moneda                 : fk_moneda,
                        cotizacion_proyecto : cotizacion_proyecto,
                        cotizacion_descripcion_proyecto: cotizacion_descripcion_proyecto,
                        cotizacion_lugar_proyecto: cotizacion_lugar_proyecto,
                        cotizacion_fecha_proyecto: cotizacion_fecha_proyecto,
                        cotizacion_contacto_proyecto: cotizacion_contacto_proyecto,
                        cotizacion_tipo_cambio: '<?php echo $tipo_cambio;  ?>',
                        
                    },

                }).done(function(data) {

                
                    console.log(data);
                    const response = JSON.parse(data);
                    console.log('data es: ' + response.error);
                    
                    

                        add_notification({
                            text: response.mensaje_txt,
                            pos: 'top-right',
                            actionTextColor: '#fff',
                            backgroundColor: '#28A745'
                        });


                        if (parseInt(response.id) > 0) {
                            window.location.href = '<?php echo ENLACE_WEB; ?>redhouse_cotizaciones_detalle/' + response.id;
                        }
                          

                    // Aquí puedes añadir código para actualizar la interfaz de usuario si es necesario
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    console.error("Error en la petición AJAX:", textStatus, errorThrown);

                    add_notification({
                        text: 'Hubo un error al agregar la exoneracion.',
                        actionTextColor: '#fff',
                        backgroundColor: '#e7515a'
                    });


                });



    }// fin de la funcion 
</script>
  
<!------------  ------------------------------------------->
<!------------  ------------------------------------------->
<!------------  ------------------------------------------->
<!---------     Javascript de los select 2----------------->
<!------------  ------------------------------------------->
<!------------  ------------------------------------------->
<!------------  ------------------------------------------->
<?php 
$coma ="";
foreach (explode(",", $Cotizacion->cotizacion_tags) as $array){
    $tags_en_formato.= $coma.'"'.$array.'"';
    $coma=",";
  } 
  $coma           ="";
  $listaDeOpciones="";
  foreach($Cotizacion->usuarios_disponibles as  $valor){
    $listaDeOpciones.= $coma.'"' . $valor->nombre . '"';
    $coma=",";
 
  } 

?>


<script>     
        $(document).ready(function() {
 
 
      let selectedOptions=[];

            //var listaDeOpciones = [<?php echo $listaDeOpciones; ?>];

              $('#a_medida_redhouse_cotizaciones_recurso_humano').select2({
                    //  data: listaDeOpciones,
                      multiple: true,
                      tags: true,
                      tokenSeparators: [',', ' '] ,

                      createTag: function (params) {
                // No permite la creación de nuevas etiquetas
                return null;
            },
            maximumInputLength: 0 // No permite la entrada de texto


                    });

                    // Set selected options
                    $('#tags').val(selectedOptions);
                    $('#tags').trigger('change');
        

         });
            </script>




<script>

function close_modal_edit(){
  $('#modal_editar_tarea').modal('hide');
}
 
 
$(document).ready(function() {

    var listaDeOpcionesTags = [<?php echo $tags_en_formato; ?>];


    $('#tags').val(listaDeOpcionesTags); // Establece las opciones seleccionadas


    $('#tags').select2();
        $('#tags').val(listaDeOpcionesTags); // Establece las opciones seleccionadas
        $('#tags').trigger('change'); // Notifica a Select2 para actualizar el estado
        // Inicializa Select2 tags
        $('#tags').select2({
          tags: true, // Habilitar la entrada de texto
          tokenSeparators: [',', ' '] // Separadores para nuevas etiquetas
        });
});
</script>

