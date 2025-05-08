<?php 

require_once(ENLACE_SERVIDOR."mod_proyectos/object/Proyectos.object.php");
$Proyectos = new Proyectos($dbh , $_SESSION['Entidad']);

$texto_informativo = "";
$disabled = "disabled";

if (!empty($_POST) && empty($_REQUEST['fiche'])) {
   $result =  $Proyectos->fetch($_REQUEST['fiche']);
    $_REQUEST['fiche'] = $id;
    $disabled = "";
} elseif (!empty($_POST) && !empty($_REQUEST['fiche'])) {
   $result =  $Proyectos->fetch($_REQUEST['fiche']);
} elseif (empty($_REQUEST['fiche'])) {
    $disabled = "";
} elseif (!empty($_REQUEST['fiche']) && $_REQUEST['action'] == "modify") {
    $disabled = "";
    $result =  $Proyectos->fetch($_REQUEST['fiche']);
} elseif (!empty($_REQUEST['fiche'])) {
   $result = $Proyectos->fetch($_REQUEST['fiche']);
}

   // Si no hay usuario autenticado, cerrar conexión
   if (!isset($_SESSION['usuario']) ) {
       echo acceso_invalido();
       exit(1);
   }
   if(isset($_GET['fiche']))
   {
      if($Proyectos->entidad_proyecto != $_SESSION['Entidad'] || $Proyectos->borrado == 1 || $result['exito'] === 0)
      {
         echo acceso_invalido();
         exit(1);
      }
   }

?>

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<style>

#ubicacion_mapa {position: relative;z-index: 1000; /* Asegura que el contenedor esté en la parte superior */}
.ui-autocomplete {position: absolute !important;z-index: 1050 !important; background: #fff; border: 1px solid #ccc;max-height: 200px;
overflow-y: auto; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);}
.ui-menu-item {padding: 8px 12px;cursor: pointer;font-size: 14px;border-bottom: 1px solid #ddd;}
.ui-menu-item:hover {background-color: #007bff;color: white;}
#address-search {position: relative;z-index: 1100; }



</style>


<div class="middle-content container-fluid p-0">
   <div class="page-meta">
      <nav class="breadcrumb-style-one" aria-label="breadcrumb">
         <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo ENLACE_WEB; ?>proyecto_listado">Proyectos</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo !empty($_REQUEST['fiche']) ? 'Editar '.$texto_informativo : 'Nuevo' ?></li>
         </ol>
      </nav>
   </div>

   <div class="row mt-5">
      <div class="col-md-6">
         <div class="card">
            <div class="card-body">
                <input type="hidden" name="fiche" value="<?php echo $_GET['fiche']; ?>">
                <div class="row mb-3">
                   <div class="col-md-6">
                      <label for="nombre">Nombre</label>
                      <input type="text" id="nombre" name="nombre" placeholder="Nombre del proyecto" class="form-control" value="<?php echo $Proyectos->nombre; ?>" <?php echo $disabled; ?> >
                   </div>
                   <div class="col-md-6">
                      <label for="ref">Referencia</label>
                      <input type="text" id="ref" name="ref" placeholder="Referencia del proyecto" class="form-control" value="<?php echo $Proyectos->referencia; ?>" <?php echo $disabled; ?> >
                   </div>
                </div>

                <?php 
                    $nombre_buscador = isset($Proyectos->fk_tercero) ? $Proyectos->nombre_tercero.' • '.$Proyectos->email_tercero : '';
                ?>
                <div class="row mb-3">
                   <div class="col-md-6">
                      <label for="nombre">Cliente</label>
                        <div id="input_busqueda_tercero">
                              <input type="text" id="birds" value="<?php echo $nombre_buscador; ?>" placeholder="Digite nombre del cliente" class="form-control form-control-sm ui-autocomplete-input" autocomplete="off" <?php echo $disabled; ?> >
                              <input type="hidden" id="fk_tercero" name="fk_tercero" value="<?php echo $Proyectos->fk_tercero; ?>" <?php echo $disabled; ?> >
                        </div>
                   </div>


                   <div class="col-md-6">
                      <label for="estado">Estado</label>
                      <select name="estado" id="estado" class="form-control"  <?php echo $disabled; ?> >
                        <option <?php if($Proyectos->estado === 1){ echo 'selected'; } ?> value="1">Activo</option>
                        <option <?php if($Proyectos->estado === 0){ echo 'selected'; } ?> value="0">Inactivo</option>
                      </select>

                      
                   </div>
                </div>
             
                <div class="row mb-3">
                   <div class="col-md-6">
                      <label for="fecha_inicio">Fecha Inicio</label>
                      <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" value="<?php echo $Proyectos->fecha_inicio; ?>" <?php echo $disabled; ?> >
                   </div>
                   <div class="col-md-6">
                      <label for="fecha_fin">Fecha Fin</label>
                      <input type="date" id="fecha_fin" name="fecha_fin" class="form-control" value="<?php echo $Proyectos->fecha_fin; ?>" <?php echo $disabled; ?> >
                   </div>
                </div>


                <div class="row mb-3">
                    <div class="col-md-6">
                      <label for="monto">Monto</label>
                      <input type="text" id="monto" name="monto" class="form-control" value="<?php echo $Proyectos->monto; ?>" <?php echo $disabled; ?> >
                   </div>

                   <div class="col-md-6">
                      <label for="etiquetas_tags">Etiquetas TAGS</label>
                      <select <?php echo $disabled; ?> class="form-control select2" name="etiquetas_tags" multiple='multiple' id="etiquetas_tags">
                                    <?php
                                    $arrayRecuperadoTags = explode(",", $Proyectos->etiquetas_tags);
                                    foreach ($arrayRecuperadoTags as $item => $value) {
                                        if ($value === '') continue;
                                        echo '<option selected value="' . $value . '">' . $value . '</option>';
                                    }
                                    ?>
                      </select>
                  </div>
                  
                </div>
            </div>
         </div>
      </div>

      <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-map-marker-alt"></i> Ubicación en el Mapa</h5>
            </div>
            <div class="card-body">
                <div id="ubicacion_mapa" class="border border-dashed p-4 text-center" style="display:none; border: 2px dashed #007bff; cursor: pointer;">
                    <input type="text" id="address-search" <?php echo $disabled; ?> value="<?php echo $Proyectos->ubicacion_mapa; ?>" name="ubicacion_mapa" class="form-control mb-2" placeholder="Escribe una dirección...">
                    <input type="text" id="ubicacion_mapa_input" <?php echo $disabled; ?> name="latitud_longitud" class="form-control" value="<?php echo $Proyectos->latitud_longitud; ?>" placeholder="Lat,Lng">
                </div>
                <div id="map" style="height: 400px; width: 100%; margin-top: 15px;"></div>
            </div>
        </div>
    </div>



   </div>

   <div class="card-footer mt-12" style="margin-top:20px;">
      <a href="<?php echo ENLACE_WEB; ?>proyecto_listado" class="btn btn-outline-primary">Volver al Listado</a>
      <?php if(isset($_GET['fiche'])) { ?>

         <?php 
               if(!isset($_REQUEST['action'])){
            ?>
          <a href="<?php echo ENLACE_WEB; ?>dashboard.php?accion=<?php echo $_GET['accion'] ?>&fiche=<?php echo $_REQUEST['fiche'] ?>&action=modify" class="btn btn-success">
              <i class="fa fa-fw fa-edit"></i> Modificar
          </a>
          <?php } ?>

          <?php if(!isset($_REQUEST['action'])){ ?>
          <a href="#" onclick="eliminarProyecto()" class="btn btn-danger">
              <i class="fa fa-fw fa-trash"></i> Eliminar
          </a>
         <?php } ?>

      <?php } else { ?>
          <a href="#" onclick="ActualizarProyecto()" class="btn btn-success">
              <i class="fa fa-fw fa-save"></i> Guardar
          </a>
      <?php } ?>

      <?php if ($_REQUEST['action'] == "modify" && !empty($_REQUEST['fiche'])) { ?>
                                            <button type="button" onclick="ActualizarProyecto()" class="btn btn-primary">
                                                <i class="fa fa-fw fa-circle"></i>Guardar Cambios 
                                            </button>
                    <?php } ?>



   </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>



<!-- Agregar Leaflet CSS y JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<!-- Agregar jQuery y jQuery UI para el autocompletado -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/themes/smoothness/jquery-ui.css" />



<!-- Leaflet Control Geocoder -->
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>


<script>

document.addEventListener("DOMContentLoaded", function () {
    var storedCoords = "<?php echo $Proyectos->latitud_longitud; ?>".trim();
    var initialCoords = storedCoords ? storedCoords.split(',') : [9.7489, -83.7534];
    var searchQuery = "<?php echo $Proyectos->ubicacion_mapa; ?>".trim();

    var lat = parseFloat(initialCoords[0]) || 9.7489;
    var lng = parseFloat(initialCoords[1]) || -83.7534;

    // Inicializar el mapa
    var map = L.map('map').setView([lat, lng], 13);

    // Agregar capa base de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Crear marcador draggable
    var marker = L.marker([lat, lng], { draggable: true }).addTo(map);

    // Actualizar coordenadas al arrastrar marcador
    marker.on('dragend', function () {
        var position = marker.getLatLng();
        document.getElementById('ubicacion_mapa_input').value = position.lat + ',' + position.lng;
    });

    // Inicializar el buscador de Leaflet Control Geocoder
    var geocoderControl = L.Control.geocoder({
        defaultMarkGeocode: false
    }).on('markgeocode', function (e) {
        var center = e.geocode.center;
        map.setView(center, 15);
        marker.setLatLng(center);
        document.getElementById('ubicacion_mapa_input').value = center.lat + ',' + center.lng;

        // Actualizar el valor del input address-search con la ubicación seleccionada
        $("#address-search").val(e.geocode.name);
    }).addTo(map);

    // Abrir automáticamente el buscador al cargar la página
    setTimeout(function () {
        document.querySelector('.leaflet-control-geocoder-icon').click(); // Simular clic para abrir el buscador

        var searchInput = document.querySelector('.leaflet-control-geocoder-form input');
        if (searchInput && searchQuery) {
            searchInput.value = searchQuery;
            $("#address-search").val(searchQuery);  // También actualizar el campo address-search
        }

        // Mostrar el buscador como desplegado
        document.querySelector('.leaflet-control-geocoder-expanded').classList.add('leaflet-control-geocoder-expanded');

        // Sincronizar la entrada del geocoder con #address-search en tiempo real
        searchInput.addEventListener('input', function () {
            $("#address-search").val(this.value);
        });
    }, 1000);
});


</script>


<script>
jQuery(document).ready(function($)
{  

   
   $('#etiquetas_tags').select2({
            tags: true,  // Permite agregar nuevas etiquetas
            tokenSeparators: [','],  // Separador de etiquetas
            placeholder: "Escribe y presiona Enter o usa comas",
            allowClear: true
      });

   $("#birds").autocomplete({
         source: "<?php echo ENLACE_WEB; ?>mod_terceros/json/terceros_facturas.json.php?cliente=1",
         delay: 300,
         minLength: 2,
         search: function() {
            // Muestra la animación de carga cuando inicia la búsqueda
            $("#loading_cliente").empty().fadeOut();
            //$("#icon_edit_client").empty().html(icono_buscando);
         },
         response: function(event, ui) {
           
            // Oculta la animación de carga cuando termina la búsqueda y aparecen los resultados
            if (!ui.content || ui.content.length === 0) {
               // Si no hay resultados, cambia al ícono de "sin resultados"
               $("#loading_cliente").html('<i class="fas fa-exclamation-triangle"></i> No se encontraron resultados.').fadeIn();
              // $("#icon_edit_client").empty().html(icono_no_encontrado);
            } else {
               // Oculta el ícono de carga cuando hay resultados
               $("#loading_cliente").hide();
               //$("#icon_edit_client").empty().html(icono_editar);
            }
         },
         select: function(event, ui) {
            valor = ui.item.id.toString();
            console.log(ui);
            console.log("ticoide");
            $("#fk_tercero").val(ui.item.id);
            if(ui.item.fk_agente>0){
               $('#asesor_comercial_txt').val(ui.item.fk_agente);
                //guardamos el FK tercero
               
            }
 
         }
      }); //FIN AUTOCOMPLETE


});
</script>

<script>

$(".menu").removeClass('active');
$(".mod_proyectos").addClass('active');


function ActualizarProyecto() {
    // Eliminar errores previos
    $('input, select').removeClass("input_error");
debugger
    // Recoger los valores del formulario usando jQuery
    let id = $('input[name="fiche"]').val();
    let nombre = $("#nombre").val();
    let referencia = $("#ref").val();
    let fk_tercero = $("#fk_tercero").val();
    let estado = $("#estado").find('option:selected').val();
    let ubicacion = $("#address-search").val();
    let latitud_longitud = $("#ubicacion_mapa_input").val();
    let etiquetas_tags = $("#etiquetas_tags").val();
    let etiquetas_string = etiquetas_tags.join(",");
    let monto = $("#monto").val();
    let fecha_inicio = $("#fecha_inicio").val();
    let fecha_fin = $("#fecha_fin").val();
    let birds = $("#birds").val();
    let error = false;

    let message_error = 'Faltan Datos Obligatorios';
    // Validaciones de campos y agregado de clase de error si el campo está vacío
    if (birds === '') {
        $("#birds").addClass("input_error");
        error = true;
    }
   
    if (nombre === '') {
        $("#nombre").addClass("input_error");
        error = true;
    }
    if (referencia === '') {
        $("#ref").addClass("input_error");
        error = true;
    }
    if (fk_tercero === '') {
        $("#fk_tercero").prev().addClass("input_error");
        message_error += '<br/>Debe seleccionar un Cliente';
        error = true;
    }
    if (estado === '') {
        $("#estado").addClass("input_error");
        error = true;
    }
    if (monto === '') {
        $("#monto").addClass("input_error");
        error = true;
    }
    if (fecha_inicio === '') {
        $("#fecha_inicio").addClass("input_error");
        error = true;
    }
    if (fecha_fin === '') {
        $("#fecha_fin").addClass("input_error");
        error = true;
    }

    // Mostrar notificación de error si falta algún dato
    if (error) {
        add_notification({
            text: message_error,
            actionTextColor: '#fff',
            backgroundColor: '#e7515a',
        });
        return false;
    }

    // Enviar AJAX con datos recopilados
    $.ajax({
        method: "POST",
        url: "<?php echo ENLACE_WEB; ?>mod_proyectos/class/proyecto.class.php",
        data: {
            action: 'editar_proyecto',
            id: id,
            nombre: nombre,
            referencia: referencia,
            fk_tercero: fk_tercero,
            estado: estado,
            ubicacion_mapa: ubicacion,
            etiquetas_tags: etiquetas_string,
            monto: monto,
            fecha_inicio: fecha_inicio,
            fecha_fin: fecha_fin,
            latitud_longitud: latitud_longitud
        },
    }).done(function(msg) {
        let mensaje = jQuery.parseJSON(msg);
        if (mensaje.exito === 1) {
            add_notification({
                text: mensaje.mensaje,
                actionTextColor: '#fff',
                backgroundColor: '#00ab55',
            });
            setTimeout(function() {
                window.location.href = "<?php echo ENLACE_WEB; ?>proyecto_listado";
            }, 1000);
        } else {
            add_notification({
                text: "Error: " + mensaje.mensaje,
                actionTextColor: '#fff',
                backgroundColor: '#e7515a',
            });
        }
    }).fail(function() {
        add_notification({
            text: 'Error en la comunicación con el servidor.',
            actionTextColor: '#fff',
            backgroundColor: '#e7515a',
        });
    });
}


function eliminarProyecto() {
    let id = $('[name="fiche"]').val(); // Obtener el ID del proyecto desde el formulario
    let message = "¿Deseas eliminar este Proyecto?";
    let actionText = "Confirmar";

    // Mostrar el snackbar de confirmación con botón de acción
    add_notification({
        text: message,
        width: 'auto',
        duration: 30000,
        actionText: actionText,
        dismissText: 'Cerrar',
        onActionClick: function(element) {
            $.ajax({
                method: "POST",
                url: "<?php echo ENLACE_WEB; ?>mod_proyectos/class/proyecto.class.php",
                data: {
                    action: 'eliminar_proyecto',
                    id: id
                },
                cache: false,
            }).done(function(msg) {
                var mensaje = jQuery.parseJSON(msg);

                if (mensaje.exito == 1) {
                    add_notification({
                        text: mensaje.mensaje,
                        actionTextColor: '#fff',
                        backgroundColor: '#00ab55',
                        dismissText: 'Cerrar'
                    });

                    setTimeout(function() {
                        window.location.href = "<?php echo ENLACE_WEB; ?>proyecto_listado";
                    }, 1000); // Redirige después de 1 segundo

                } else {
                    add_notification({
                        text: "Error: " + mensaje.mensaje,
                        actionTextColor: '#fff',
                        backgroundColor: '#e7515a',
                    });
                }

            }).fail(function(jqXHR, textStatus, errorThrown) {
                console.error("Error en la petición AJAX:", textStatus, errorThrown);
                add_notification({
                    text: 'Hubo un error al borrar.',
                    actionTextColor: '#fff',
                    backgroundColor: '#e7515a'
                });
            });
        },
    });
}

</script>
