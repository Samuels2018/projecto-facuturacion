<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<!-- Agregar Leaflet CSS y JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<!-- Agregar jQuery y jQuery UI para el autocompletado -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/themes/smoothness/jquery-ui.css" />

<script>
  document.addEventListener("DOMContentLoaded", function() {
    var today = new Date().toISOString().split('T')[0];
    document.getElementById('editar').setAttribute('min', today);
  });

  function generar_cotizacion() {
    document.getElementById("loading").style.display = "block";
    setTimeout(function() {
      document.getElementById("loading").style.display = "none";
      alert('Cotización Generada');
    }, 2000);
  }
</script>



<script>

document.addEventListener("DOMContentLoaded", function() {
    // Obtener las coordenadas almacenadas o usar valores por defecto
    var storedCoords = "<?php echo $Proyectos->latitud_longitud; ?>".trim();
    var initialCoords = storedCoords ? storedCoords.split(',') : [9.7489, -83.7534];
    
    var lat = parseFloat(initialCoords[0]) || 9.7489;
    var lng = parseFloat(initialCoords[1]) || -83.7534;

    // Inicialización del mapa
    var map = L.map('map').setView([lat, lng], 13);

    // Agregar capa de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Crear marcador en la ubicación inicial
    var marker = L.marker([lat, lng], { draggable: true }).addTo(map);

    // Rellenar el input con las coordenadas guardadas
    $('#ubicacion_mapa_input').val(lat + ',' + lng);

    // Actualizar coordenadas en el input al arrastrar el marcador
    marker.on('dragend', function() {
        var position = marker.getLatLng();
        $('#ubicacion_mapa_input').val(position.lat + ',' + position.lng);
    });

    // Autocompletado usando la API de Nominatim
    $("#address-search").autocomplete({
        source: function(request, response) {
            $.ajax({
                url: "https://nominatim.openstreetmap.org/search",
                dataType: "json",
                data: {
                    format: "json",
                    q: request.term
                },
                success: function(data) {
                    response($.map(data, function(item) {
                        return {
                            label: item.display_name,
                            value: item.display_name,
                            lat: item.lat,
                            lon: item.lon
                        };
                    }));
                }
            });
        },
        select: function(event, ui) {
            var lat = parseFloat(ui.item.lat);
            var lon = parseFloat(ui.item.lon);
            
            // Mover el marcador a la nueva ubicación
            marker.setLatLng([lat, lon]);
            map.setView([lat, lon], 13);

            // Actualizar input con latitud y longitud
            $('#ubicacion_mapa_input').val(lat + ',' + lon);
        }
    });

    // Actualizar marcador al cambiar manualmente el input de coordenadas
    document.getElementById('ubicacion_mapa_input').addEventListener('change', function() {
        var coords = this.value.split(',');
        if (coords.length === 2) {
            var lat = parseFloat(coords[0].trim());
            var lng = parseFloat(coords[1].trim());
            if (!isNaN(lat) && !isNaN(lng)) {
                marker.setLatLng([lat, lng]);
                map.setView([lat, lng], 13);
            }
        }
    });
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
        $("#fk_tercero").addClass("input_error");
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
            text: 'Faltan Datos Obligatorios',
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
