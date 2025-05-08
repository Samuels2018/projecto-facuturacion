<?php
session_start();

//si no hay usuario autenticado, cerrar conexion
if (!isset($_SESSION['usuario'])) {
    echo acceso_invalido();
    exit(1);
}

include_once("../../conf/conf.php");
include_once ENLACE_SERVIDOR . 'mod_direcciones/object/Direcciones.object.php';
$obj = new Direccion($dbh, $_SESSION['Entidad']);

require_once ENLACE_SERVIDOR . 'mod_utilidad/object/utilidades.object.php';

//$obj = new Direccion($dbh);
$Utilidades = new Utilidades($dbh);

//bloque validacion entidad
if (!empty($_REQUEST['fiche'])) {
    $obj->fetch($_REQUEST['fiche']);
}

if ($obj->id > 0) {
    $titulo = 'Modificar';
} else {
    $titulo = 'Crear';
}

$paises = $Utilidades->obtener_paises();
$municipios =  $Utilidades->obtener_municipios($obj->codigo_provincia);
$ubigeo_seleccionado = $Utilidades->obtener_ubigeo_seleccionado($obj->codigo_provincia);

$tipo_entidad_externo = $_REQUEST["tipo"];
if($tipo_entidad_externo != ''){
    $obj->tipo_entidad = $tipo_entidad_externo;
    $obj->activo = 1;
}

$fk_entidad =  $_REQUEST["fk_entidad"];
if($fk_entidad != ''){
    $obj->fk_entidad = $fk_entidad;
}

?>


<link rel="stylesheet" type="text/css" href="https://unpkg.com/leaflet@1.3.3/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">



        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"><?= $titulo ?> dirección</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

            </button>
        </div>
        <div class="modal-body">
            <form role="form" method="POST" action="" id="formulario">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="">
                                <label for="ref"><i class="fa-solid fa-font"></i> Descripción</label>
                                <input required placeholder="Descripción de la dirección" type="text" name="descripcion" id="descripcion" class="form-control" value="<?php echo $obj->descripcion; ?>" <?php echo $disabled; ?>>
                            </div>
                            <div class="">
                                <label for="ref"><i class="fa-solid fa-inbox"></i> Código Postal</label>
                                <input required placeholder="Código postal" type="text" name="codigo_postal_modal" id="codigo_postal_modal" class="form-control" value="<?php echo $obj->codigo_postal; ?>" <?php echo $disabled; ?>>
                            </div>
                            <?php if($tipo_entidad_externo == '') { ?>
                            <div class="">
                                <label for="ref"><i class="fa-solid fa-t"></i>ipo Entidad</label>
                                <select name="tipo_entidad" id="tipo_entidad" class="form-control">
                                    <option value="1" <?php if ($obj->tipo_entidad == 1) echo 'selected'; ?>>Clientes</option>
                                    <option value="2" <?php if ($obj->tipo_entidad == 2) echo 'selected'; ?>>Proveedores</option>
                                    <option value="3" <?php if ($obj->tipo_entidad == 3) echo 'selected'; ?>>Agentes</option>
                                </select>
                            </div>
                            <?php } else{ ?>
                                <div class="">
                                <label for="ref"><i class="fa-solid fa-t"></i> Tipo Entidad</label>
                                <input type="text" class="form-control" value="<?php echo ($tipo_entidad_externo == 1?'Cliente':( ($tipo_entidad_externo == 2)?'Proveedor':'Agente') ) ?>" disabled>
                                <input type="hidden" name="tipo_entidad" id="tipo_entidad" value="<?php $tipo_entidad_externo ?>">
                            </div>
                            <?php } ?>
                        </div>
                        <div class="col-6">
                            <div class="">
                                <label for="ref"><i class="fa-solid fa-flag"></i> País</label>
                                <?php
                                $pais_selected = isset($obj->codigo_pais) ? $obj->codigo_pais : '';
                                ?>
                                <select name="codigo_pais" id="codigo_pais" class="form-control" required>
                                    <option value="">Seleccione el país</option>
                                    <?php foreach ($paises as $pais) { ?>
                                        <option value="<?php echo $pais->rowid; ?>" <?php echo (intval($pais_selected) === intval($pais->rowid)) ? 'selected' : ''; ?>><?php echo $pais->nombre; ?></option>
                                    <?php } ?>
                                </select>

                            </div>

                            <div class="">
                                <label for="ref"><i class="fa-regular fa-building"></i> CCAA</label>
                                <select required="required" name="codigo_poblacion" id="codigo_poblacion" class="form-control">
                                    <option value="">Seleccionar CCAA</option>
                                    <?php foreach ($ubigeo_seleccionado as  $key => $value) {
                                        if ($key === 0) continue;
                                        if ($ubigeo_seleccionado[$key]->nombre_comunidad_autonoma === NULL) continue;
                                    ?>
                                        <option value="<?php echo $ubigeo_seleccionado[$key]->comunidad_autonoma_id; ?>" <?php echo (intval($obj->codigo_poblacion) === intval($ubigeo_seleccionado[$key]->comunidad_autonoma_id)) ? 'selected' : ''; ?>>
                                            <?php echo $ubigeo_seleccionado[$key]->nombre_comunidad_autonoma; ?>

                                        </option>
                                    <?php } ?>
                                </select>
                                
                            </div>

                            <div class="">
                                <label for="ref"><i class="fa-solid fa-location-dot"></i> Provincia</label>
                                <select name="codigo_provincia" id="codigo_provincia" class="form-control" required>
                                    <option value="">Seleccione provincia</option>
                                    <?php foreach ($ubigeo_seleccionado as $key => $value) {
                                        if (intval($key) === 0) continue;
                                        if ($ubigeo_seleccionado[$key]->provincia_id === NULL) continue;

                                    ?>
                                        <option value="<?php echo $ubigeo_seleccionado[$key]->provincia_id; ?>" <?php echo (intval($obj->codigo_provincia) === intval($ubigeo_seleccionado[$key]->provincia_id)) ? 'selected' : ''; ?>><?php echo $ubigeo_seleccionado[$key]->nombre_provincia;  ?></option>
                                    <?php } ?>
                                </select>
                                
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label for="ref"><i class="fa-solid fa-map-location-dot"></i> Dirección</label>
                            <input required="required" placeholder="Dirección" type="text" name="direccion_modal" id="direccion_modal" class="form-control" value="<?php echo $obj->direccion; ?>" <?php echo $disabled; ?>>
                            <label for="ref"><i class="fa-solid fa-map-location"></i> Otros datos de la dirección</label>
                            <textarea placeholder="Ingrese otros datos" rows="4" name="otros_datos" id="otros_datos" class="form-control" <?php echo $disabled; ?>><?php echo $obj->otros_datos; ?></textarea>
                        </div>
                    </div>
                    </div>
                    <div class="row" style="padding: 20px;">
                        <div class="div basic-map" style="height: 300px; width: 100%; margin-right: 30px" id="live-location"></div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="">
                                <input readonly disabled type="hidden" name="latitude" id="latitude" class="form-control" value="<?php echo $obj->latitud; ?>" <?php echo $disabled; ?>>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="">
                                <input readonly disabled type="hidden" name="longitud" id="longitud" class="form-control" value="<?php echo $obj->longitud; ?>" <?php echo $disabled; ?>>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn btn-light-dark" onclick="cancelar()"><i class="flaticon-cancel-12"></i>Cancelar</button>
    
                <?php if (empty($_REQUEST['fiche'])) { ?>
                    <button type="button" class="btn btn-primary" id="agregar_direccion" onclick="crear_direccion(event)"><i class="fa fa-fw fa-circle" aria-hidden="true"></i> Guardar</button>
                <?php } else { ?>
                    <button type="button" class="btn btn-danger" id="borrar_direccion" onclick="estado_accion = 2; limpiar_campos()"><i class="fa fa-fw fa-trash" aria-hidden="true"></i> Borrar</button>
                    <button type="button" class="btn btn-primary" id="agregar_direccion" onclick="estado_accion = 1; actualizar_direccion(<?= $obj->id; ?>)"><i class="fa fa-fw fa-circle" aria-hidden="true"></i> Actualizar</button>
                <?php
                } ?>
    
            </div>
        </div>

    </div>
</div>

<script>
    function cancelar(){
        $("#nueva_direccion").modal('hide'); 
    }
    function limpiar_campos(){
        $.ajax({
              method: "POST",
              url: "<?php echo ENLACE_WEB; ?>mod_direcciones/ajax/direcciones_ajax.php",
              beforeSend: function(xhr) {

              },
              data: {
                action: 'borrar_direccion',
                id: '<?= $obj->id; ?>'
              },
          }).done(function(msg) {
              var mensaje = jQuery.parseJSON(msg);            

              if (mensaje.exito == 1) {
                  $("#nueva_direccion").modal('hide');                
                  $('#style-3').DataTable().ajax.reload();

                  add_notification({
                      text: mensaje.mensaje,
                      actionTextColor: '#fff',
                      backgroundColor: '#00ab55',
                      dismissText: 'Cerrar'
                  });

                  if(tipo_entidad == 3){
                    $('#direccion').val('');
                    $('#direccion_fk').val('');
                  }

              } else {

                  add_notification({
                      text: "Error:" + mensaje.mensaje,
                      actionTextColor: '#fff',
                      actionTextColor: '#fff',
                      backgroundColor: '#e7515a',
                  });
              }
          });
        let tipo_entidad = '<?php echo $tipo_entidad_externo; ?>';
        if(tipo_entidad == 3){
            $('#direccion').val('');
            $('#direccion_fk').val('');
        }
    }
    function crear_direccion(event){
        event.preventDefault();
        let error = false;
        let tipo_entidad = '<?php echo $tipo_entidad_externo; ?>';
        // Eliminar la clase de error de los campos antes de validar
        $('#label').removeClass("input_error");

        /* Valida los inputs requeridos */
        if ($("#descripcion").val() == '') {
            $('#descripcion').addClass("input_error");
            error = true;
        }
        if ($("#codigo_pais").val() == '') {
            $('#codigo_pais').addClass("input_error");
            error = true;
        }
        if ($("#codigo_poblacion").val() == '') {
            $('#codigo_poblacion').addClass("input_error");
            error = true;
        }
        if ($("#codigo_provincia").val() == '') {
            $('#codigo_provincia').addClass("input_error");
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
        /* Valida los inputs requeridos */
        //const data = inputTypes.reduce((acc, item) => { acc[item.name] = item.value; return acc; }, { action: 'crear_direccion' });
        var data = {
            action: 'crear_direccion',
            activo: 1,
            tipo_entidad: '<?php echo $tipo_entidad_externo; ?>',
            fk_entidad:'<?php echo $obj->fk_entidad; ?>',
            descripcion: $('#descripcion').val(),
            codigo_postal: $('#codigo_postal_modal').val(),
            codigo_pais: $('#codigo_pais').val(),
            codigo_poblacion: $('#codigo_poblacion').val(),
            codigo_provincia: $('#codigo_provincia').val(),
            direccion: $('#direccion_modal').val(),
            otros_datos: $('#otros_datos').val(),
            latitud: $("#latitude").val(),
            longitud: $("#longitud").val()        
        };
        // Preparar la petición AJAX
        $.ajax({
              method: "POST",
              url: "<?php echo ENLACE_WEB; ?>mod_direcciones/ajax/direcciones_ajax.php",
              beforeSend: function(xhr) {

              },
              data: data,
          }).done(function(msg) {
              var mensaje = jQuery.parseJSON(msg);            

              if (mensaje.exito == 1) {
                  $("#nueva_direccion").modal('hide');                  

                  $('#style-3').DataTable().ajax.reload();

                  add_notification({
                      text: 'Dirección creado exitosamente',
                      actionTextColor: '#fff',
                      backgroundColor: '#00ab55',
                      dismissText: 'Cerrar'
                  });

                  if(tipo_entidad == 1){
                    listarDirecciones(mensaje.data);
                    actualizar_detalle_direccion(mensaje.data);
                  }

                  if(tipo_entidad == 3){
                    $('#direccion').val(data.descripcion);
                    $('#direccion_fk').val(mensaje.data);
                  }

              } else {

                  add_notification({
                      text: "Error:" + mensaje.mensaje,
                      actionTextColor: '#fff',
                      actionTextColor: '#fff',
                      backgroundColor: '#e7515a',
                  });
              }
          });
        console.log(data);
        
        
    }
    
    function resetLocation() {
        var defaultLat = 40.39683882656069;
        var defaultLng = -3.704789850382989;
        window.mymap.setView([defaultLat, defaultLng], 10);
        window.marker.setLatLng([defaultLat, defaultLng]);
        $("#latitude").val(defaultLat);
        $("#longitud").val(defaultLng);
    }

    var defaultLat = 40.39683882656069;
    var defaultLng = -3.704789850382989;

    $("#codigo_pais").change(function() {
        $codigo_pais = $(this).val();
   
        $.ajax({
            method: "POST",
            url: '<?= ENLACE_WEB ?>/mod_empresa/class/clases.php',
            beforeSend: function(xhr) {},

            data: {
                "action": "BuscarComunidadesAutonomas",
                fk_pais: $codigo_pais,
            },
        }).done(function(data) {
            console.log(data);
            $("#codigo_poblacion").html(data);
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.error("Error en la petición AJAX:", textStatus, errorThrown);

            add_notification({
                text: 'Error con la Peticion - Vuelve a Intentarlo',
                actionTextColor: '#fff',
                backgroundColor: '#e7515a'
            });
        });

    });

     //Seleccionar Población / Comunidad Autonoma
     $("#codigo_poblacion").change(function() {
            $poblacion = $(this).val();
            console.log($poblacion);
            $.ajax({
                method: "POST",
                url: '<?= ENLACE_WEB ?>/mod_empresa/class/clases.php',
                beforeSend: function(xhr) {},

                data: {
                    "action": "BuscarProvincias",
                    fk_comunidad_autonoma: $poblacion,
                },
            }).done(function(data) {
                console.log(data);
                $("#codigo_provincia").html(data);
            }).fail(function(jqXHR, textStatus, errorThrown) {
                console.error("Error en la petición AJAX:", textStatus, errorThrown);

                add_notification({
                    text: 'Error con la Peticion - Vuelve a Intentarlo',
                    actionTextColor: '#fff',
                    backgroundColor: '#e7515a'
                });
            });

        });

        //Seleccionar Provincia / Comunidad Autonoma
        /*$("#codigo_provincia").change(function() {
            $provincia = $(this).val();

            $.ajax({
                method: "POST",
                url: '<?= ENLACE_WEB ?>/mod_empresa/class/clases.php',
                beforeSend: function(xhr) {},

                data: {
                    "action": "BuscarMunicipios",
                    fk_provincia: $provincia,
                },
            }).done(function(data) {
                console.log(data);
                $("#codigo_municipio").html(data);
            }).fail(function(jqXHR, textStatus, errorThrown) {
                console.error("Error en la petición AJAX:", textStatus, errorThrown);

                add_notification({
                    text: 'Error con la Peticion - Vuelve a Intentarlo',
                    actionTextColor: '#fff',
                    backgroundColor: '#e7515a'
                });
            });

        });*/

    $(document).ready(function() {
        if($("#latitude").val() == '' &&  $("#longitud").val() == '')
        {
            $("#longitud").val(defaultLng);
            $("#latitude").val(defaultLat);
        }   

        var mymap = L.map('live-location').setView([$("#latitude").val(), $("#longitud").val()], 10); // Madrid
        window.mymap = mymap;
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(mymap);


        // **Solución**: Usar `setInterval` para forzar el recalculo del tamaño del mapa
        let intervalCount = 0;
        const interval = setInterval(() => {
            mymap.invalidateSize();  // Recalcular el tamaño del mapa
            intervalCount++;

            // Detener el intervalo después de 2 segundos (5 repeticiones)
            if (intervalCount >= 5) {
                clearInterval(interval);
            }
        }, 400);  // Ejecutar cada 400ms

        var marker = L.marker([$("#latitude").val(), $("#longitud").val()], {
            draggable: 'true'
        }).addTo(mymap);

        marker.on('dragend', function(event) {
            var position = marker.getLatLng();
            console.log(position);
            //actualizmos la ubicacion en el form
            $("#latitude").val(position.lat);
            $("#longitud").val(position.lng);

        });

        window.marker = marker;

        // Escuchamos los eventos de cambio en los campos de latitud y longitud
        $("#latitude, #longitud").on('change', function() {
            if ($("#latitude").val() === '' || $("#longitud").val() === '') {
                resetLocation();
            }
        });

        L.Control.geocoder({ 
            geocoder: new L.Control.Geocoder.nominatim({ geocodingQueryParams: { countrycodes: 'es' } }) 
        })
        .on('markgeocode', function(e) {
            mymap.fitBounds(e.geocode.bbox);
            if (marker) {
                mymap.removeLayer(marker);
            }
            marker = L.marker(e.geocode.center, {
                draggable: 'true'
            }).addTo(window.mymap);
            marker.on('dragend', function(event) {
                var position = marker.getLatLng();
                window.position = position;
                console.log(position);
                //actualizamos la ubicacion en el form
                $("#latitude").val(position.lat);
                $("#longitud").val(position.lng);

            });
            console.log(e.geocode.center);
            $("#latitude").val(e.geocode.center.lat);
            $("#longitud").val(e.geocode.center.lng);
        })
        .addTo(mymap);


       

       

        $('#nueva_direccion').on('hidden.bs.modal', function () { 
            // if (marker) { 
            //     map.removeLayer(marker); 
            //     marker = null; 
            // } 
        });
    })
</script>