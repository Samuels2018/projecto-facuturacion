<!-- Include jQuery Roadmap Plugin -->
<script src="<?= ENLACE_WEB ?>bootstrap/jquery.roadmap.js" type="text/javascript"></script>
<link rel="stylesheet" href="<?= ENLACE_WEB ?>bootstrap/plugins/roadmap.css">

<?php
  include_once ENLACE_SERVIDOR . "mod_citas_agenda/object/citas.object.php";
  $actividad = new Citas($dbh);

  include_once ENLACE_SERVIDOR . "mod_productos/object/productos.object.php";
  $productos = new Productos($dbh, $_SESSION['Entidad']);

  $select_estados = $actividad->diccionario_Estados();
  $select_productos = $productos->obtener_lista_productos();


?>

<!-- BEGIN PAGE LEVEL STYLE -->
<link href="<?php echo ENLACE_WEB; ?>bootstrap/plugins/src/fullcalendar/fullcalendar.min.css" rel="stylesheet" type="text/css" />
<!-- END PAGE LEVEL STYLE -->

<div class="row layout-top-spacing layout-spacing" id="cancel-row">
  <div class="col-xl-12 col-lg-12 col-md-12">
    <div class="calendar-container">
      <div class="calendar2" id="calendar"></div>
    </div>
  </div>
</div>

<style>
  .fc-v-event .fc-event-main {
    color: unset;
    color: unset;
  }
  .list-group-item {
    border: unset;
  }
</style>

 
<script src="<?php echo ENLACE_WEB; ?>bootstrap/plugins/src/highlight/highlight.pack.js"></script>

<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="<?php echo ENLACE_WEB; ?>bootstrap/plugins/src/fullcalendar/fullcalendar.min.js"></script>
<script src="<?php echo ENLACE_WEB; ?>bootstrap/plugins/src/uuid/uuid4.min.js"></script>
<!-- END PAGE LEVEL SCRIPTS -->

<!--  BEGIN CUSTOM SCRIPTS FILE  -->
<script src="<?php echo ENLACE_WEB; ?>bootstrap/plugins/src/fullcalendar/custom-fullcalendar.js"></script>
<!--  END CUSTOM SCRIPTS FILE  -->


<script>
  $(document).ready(function() {


      // Desactivar todos los elementos del menú
      $(".menu").removeClass('active');
      $(".crm").addClass('active');


  });


  document.addEventListener('DOMContentLoaded', function() {

    // Date variable
    var newDate = new Date();

    /** 
     * 
     * @getDynamicMonth() fn. is used to validate 2 digit number and act accordingly 
     * 
     */
    function getDynamicMonth() {
      getMonthValue = newDate.getMonth();
      _getUpdatedMonthValue = getMonthValue + 1;
      if (_getUpdatedMonthValue < 10) {
        return `0${_getUpdatedMonthValue}`;
      } else {
        return `${_getUpdatedMonthValue}`;
      }
    }

    // Modal Elements
    /*var getModalTitleEl = document.querySelector('#event-title');
    var getModalStartDateEl = document.querySelector('#event-start-date');
    var getModalEndDateEl = document.querySelector('#event-end-date');
    var getModalAddBtnEl = document.querySelector('.btn-add-event');
    var getModalUpdateBtnEl = document.querySelector('.btn-update-event');*/
   

    // Calendar Elements and options
    var calendarEl = document.querySelector('.calendar2');

    var checkWidowWidth = function() {
      if (window.innerWidth <= 1199) {
        return true;
      } else {
        return false;
      }
    }

    var calendarHeaderToolbar = {
      left: 'prev next addEventButton',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,timeGridDay'
    }


    // Calendar Select fn.
    /*var calendarSelect = function(info) {
      getModalAddBtnEl.style.display = 'block';
      getModalUpdateBtnEl.style.display = 'none';
      myModal.show()
      getModalStartDateEl.value = info.startStr;
      getModalEndDateEl.value = info.endStr;
    }*/


    // Calendar eventClick fn.
    /*var calendarEventClick = function(info) {
      console.log("Click en eventClick: " + info);
      if (!info.event) {
        // El clic fue fuera de un evento, así que simplemente retornar
        return;
      }
      var eventObj = info.event;

      if (eventObj.url) {
        window.open(eventObj.url);

        info.jsEvent.preventDefault(); // prevents browser from following link in current tab.
      } else {
        var getModalEventId = eventObj._def.publicId;
        var getModalEventLevel = eventObj._def.extendedProps['calendar'];
        var getModalCheckedRadioBtnEl = document.querySelector(`input[value="${getModalEventLevel}"]`);

        getModalTitleEl.value = eventObj.title;
        getModalCheckedRadioBtnEl.checked = true;
        getModalUpdateBtnEl.setAttribute('data-fc-event-public-id', getModalEventId)
        getModalAddBtnEl.style.display = 'none';
        getModalUpdateBtnEl.style.display = 'block';
        myModal.show();
      }
    }*/

    window.calendar;
    var tipo = 'general';
    btn = $('.fc-myCustomButton-button');
    var textoBoton = localStorage.getItem('textoBoton') || 'Mis actividades';
    var today = new Date().toISOString().slice(0, 10);
    var start;
    var end;

    // Activate Calender    
    var calendar = new FullCalendar.Calendar(calendarEl, {
      datesSet: function(info) {
        start = info.startStr;
        end = info.endStr;
      },
      

      select: function(info) {
    // Convertir las fechas de inicio y fin en un formato amigable para el usuario
    const start = new Date(info.start);
    const end = new Date(info.end);

    const dayStart = ("00" + start.getFullYear()).slice(-4) + "-" + ("00" + (start.getMonth() + 1)).slice(-2) + "-" + ("00" + start.getDate()).slice(-2);
    const startHour = ("00" + start.getHours()).slice(-2) + ":" + ("00" + start.getMinutes()).slice(-2);

    const dayEnd = ("00" + end.getFullYear()).slice(-4) + "-" + ("00" + (end.getMonth() + 1)).slice(-2) + "-" + ("00" + end.getDate()).slice(-2);
    const endHour = ("00" + end.getHours()).slice(-2) + ":" + ("00" + end.getMinutes()).slice(-2);

    console.log("Inicio Select");
    console.log("Fecha inicio: " + dayStart + " " + startHour);
    console.log("Fecha fin: " + dayEnd + " " + endHour);
    
    $(".pantalla_4").hide();
    $(".pantalla_3").hide();
    $(".pantalla_2").hide();
    $(".pantalla_1").show();
    

    $("#citas_fecha").empty().html( dayStart );
 
            add_notification({
                            text: 'Creando Cita',
                            actionTextColor: '#fff',
                            backgroundColor: '#00ab55',
                            dismissText: 'Cerrar'
            });


    $("#paid-visits").hide();
    $("#form-agenda-cita").modal("show");
    



},

      slotEventOverlap: false,
      allDaySlot: false , 
      slotMinTime: '07:00:00' ,
      slotHeight: 50, // Ajusta la altura de cada intervalo de tiempo en píxeles

      //slotDuration: '00:10:00',

      locale: 'es',
      customButtons: {
        myCustomButton: {
          text: textoBoton,
          click: function() {

            calendar.getEventSources().forEach(function(source) {
              source.remove();
            });
            btn.text(textoBoton);
            btn = $('.fc-myCustomButton-button');
            if (textoBoton == 'Mis actividades') {
              tipo = 'usuario';
              textoBoton = 'Todas';
              btn.text(textoBoton);

            } else {
              tipo = 'general';
              textoBoton = 'Mis actividades';
              btn.text(textoBoton);

            }
            localStorage.setItem('textoBoton', textoBoton);

            calendar.addEventSource({
              url: "<?php echo ENLACE_WEB; ?>mod_citas_agenda/json/citas.json.php",
              method: 'POST',
              extraParams: {
                tipo: tipo,
                action: 'listadoActividadesAgenda',
                start: start, // Añade la fecha de inicio como un parámetro extra
                end: end
              },
              failure: function() {
                alert('Error al cargar los eventos!');
              },
              color: 'blue', // a non-ajax option
            });
          },
        }
      }, //custombuton
      buttonText: {
        today: 'Hoy',
        month: 'Mes',
        week: 'Semana',
        day: 'Dia',
        list: 'Lista'
      },
      headerToolbar: {
        left: 'prev,next today, myCustomButton',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay',
      },
      selectable: true,
      editable: true,
      eventDrop: function(info) {


        actualizar_cita(info.event.id, info.event.start.toISOString());
            
        },
      height: checkWidowWidth() ? 900 : 1052,
      initialView: 'timeGridWeek' ,  // checkWidowWidth() ? 'listWeek' : 'dayGridMonth',
      initialDate: `${newDate.getFullYear()}-${getDynamicMonth()}-07`,
      //headerToolbar: calendarHeaderToolbar,
      //events: calendarEventsList,
      eventSources: [

        // your event source
        {
          url: "<?php echo ENLACE_WEB; ?>mod_citas_agenda/json/citas.json.php",
          method: 'POST',
          extraParams: {
            tipo: 'general',
            action: 'listadoActividadesAgenda',
            start: start, // Añade la fecha de inicio como un parámetro extra
            end: end
          },
          failure: function() {
            alert('Error al cargar los eventos!');
          },
          color: 'blue', // a non-ajax option
          //textColor: 'white' // a non-ajax option
        }

        // any other sources...

      ],
      

      eventContent: function({
        event: calendarEvent
      }) {
        var buttonValue = localStorage.getItem('textoBoton');
        btn.text(buttonValue);

        //console.log("Click en eventContent: " + calendarEvent + ", btn: " + btn + ", buttonValue: " + buttonValue);
        //const getColorValue = calendarsEvents[calendarEvent._def.extendedProps.calendar];
        const getColorValue = calendarEvent._def.ui.backgroundColor;
        /*return {
            html: `<i class="${calendarEvent._def.extendedProps.icon}"></i> <span style="background: ${getColorValue}">${calendarEvent.title}</span>`
            
        };*/
        return {
          html: `<div class="fc-event-main"> &nbsp;<i class="${calendarEvent._def.extendedProps.icon}"></i> ${calendarEvent.title}</div>`
        };
      },
      // Evento de cambio de vista
      viewWillUnmount: function(view) {
        btn.text(textoBoton);
        //console.log('La vista que se va a desmontar es: ' + view.view.type);
      },
      viewDidMount: function(info) {
        //console.log('Vista actual: ' + info.view.type);
      },
      eventDidMount: function(info) {
        const getColorValue = info.event._def.ui.backgroundColor;
        const rgbColor = hexToRgb(getColorValue);
        info.el.style.backgroundColor = `rgba(${rgbColor.r}, ${rgbColor.g}, ${rgbColor.b}, 0.25)`;
        info.el.style.color = getColorValue; // Establece el color del texto
        //info.el.style.whiteSpace = 'normal'; // Hace que el texto se ajuste
        info.el.style.whiteSpace = 'nowrap'; // Evita que el texto se ajuste
        info.el.style.overflow = 'hidden'; // Oculta el texto que se desborde
        info.el.style.textOverflow = 'ellipsis'; // Muestra puntos suspensivos si el texto se desborda
        info.el.title = info.event.title; // Muestra el texto completo como un tooltip al pasar el mouse
        info.el.style.fontWeight = '700'; // Establece el peso de la fuente

      },
      //eventClick: calendarEventClick,
      eventClick: function(info) {
        console.log(info);
        /*const date = new Date(info.date);

        console.log(info.event.start);
        var fechaFin = new Date(info.event.start);
        var fechaFinFormateada = fechaFin.toISOString().slice(0, 16);
        $('#id_actividad').val(info.event.extendedProps.id_actividad);
        $("#cliente_agenda").text(info.event.extendedProps.client);
        $("#actividad_agenda").text(info.event.title);
        $("#estado_actividad").val(info.event.extendedProps.id_estado);
        $("#vencimiento_fecha").html('<br />' + fechaFinFormateada.replace('T', ' '));
        $("#comentario").val(info.event.extendedProps.comentario);
        $("#usuario_agenda").text(info.event.extendedProps.user);
        $("#comentario_cierre").val(info.event.extendedProps.comentario_cierre);

        if (info.event.extendedProps.id_estado != 3) {
          $('.comentario-cierre').attr('style', 'display:none !important');

        } else {
          $('.comentario-cierre').removeAttr('style');
        }

        if ($('#estado_actividad').val() != 1) {
          $("#estado_actividad option[value='1']").attr("disabled", true);
        }*/

        $("#id_oportunidad_actividad").val(info.event.extendedProps.id_oportunidad_actividad);
        $("#exampleModalLabel").text(info.event.title);
        $("#actividad_agenda").text(info.event.title);

        $("#cliente_txt").text(info.event.extendedProps.client);
        $("#usuario_txt").text(info.event.extendedProps.user);

        
        $("#fechas_txt").text(info.event.extendedProps.end_original2);

        $("#comentario_txt").val(info.event.extendedProps.comentario);
        $("#comentario_cierre_txt").val(info.event.extendedProps.comentario_cierre);
        //fk_estado
        $("#estado_txt").val(info.event.extendedProps.fk_estado);

        $("#form-agenda").modal("show");

        info.el.style.borderColor = 'red';
      },
      windowResize: function(arg) {
        if (checkWidowWidth()) {
          calendar.changeView('listWeek');
          calendar.setOption('height', 900);
        } else {
          calendar.changeView('dayGridMonth');
          calendar.setOption('height', 1052);
        }
      },
      datesSet: function(dateInfo) {
        // Aquí puedes agregar la lógica que se ejecutará cuando se cambie la vista de fecha
        var button = document.querySelector('.fc-myCustomButton-button');
        var textoBoton = localStorage.getItem('textoBoton') || 'Mis actividades';
        button.textContent = textoBoton;
      }


    });


    
    // Calendar Renderation
    calendar.render();
    window.calendario=calendar;
    

  });


  function hexToRgb(hex) {
    const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? {
      r: parseInt(result[1], 16),
      g: parseInt(result[2], 16),
      b: parseInt(result[3], 16)
    } : null;
  }

  function Crear_Cita(){

    $.ajax({
        method: "POST",
        url: "<?= ENLACE_WEB ?>mod_citas_agenda/json/cita.json.php",
        beforeSend: function(xhr) {},
        data: {
          action: 'Crear_Cita',
          rowid: $("#id_oportunidad_actividad").val(),
          fk_estado: $("#estado_txt").val(),
          fk_cliente: $("#fk_cliente").val(),
          fk_producto: $("#fk_producto").val(),
          start:'10:00',
          end:'11:00',
          date:'2024-11-25 04:10:00',
          comentario: $("#comentario_txt").val(),
          comentario_cierre: $("#comentario_cierre_txt").val(),


        },
      }).done(function(data) {
        data = JSON.parse(data);
        console.log(data);
       
        add_notification({
                            text: data.respuesta_txt,
                            actionTextColor: '#fff',
                            backgroundColor: '#00ab55',
                            dismissText: 'Cerrar'
                        })
       

       

        if (data.error == false) {
            $("#form-agenda-cita").modal("hide");
            window.calendario.refetchEvents();
        }



      });

  }


  function actualizar_cita(id, nueva_fecha){



$.ajax({
    method: "POST",
    url: "<?= ENLACE_WEB ?>mod_citas_agenda/json/cita.json.php",
    beforeSend: function(xhr) {},
    data: {
      action: 'actualizar_cita',
      rowid: $("#id_oportunidad_actividad").val(),
      start:'10:00',
      end:'11:00',
      date:'2024-11-25 04:10:00',
      comentario: $("#comentario_txt").val(),
      comentario_cierre: $("#comentario_cierre_txt").val(),


    },
  }).done(function(data) {
    //  data = JSON.parse(data);
    console.log(data);
    if (data) {
     
      $("#form-agenda").modal("hide");
      window.calendario.refetchEvents();
      add_notification({
                        text: 'Registro Editado!',
                        actionTextColor: '#fff',
                        backgroundColor: '#00ab55',
                        dismissText: 'Cerrar'
                    })
      //paginarTareas(0);
    }else{
      add_notification({
                        text: 'Error!',
                        actionTextColor: '#fff',
                        backgroundColor: '#e7515a',
                        dismissText: 'Cerrar'
                    })
    }

  });

}
 

  

</script>

<script>

$(document).ready(function() {


$(function() {

  $("#cliente_buscador").autocomplete({
    source: "<?php echo ENLACE_WEB; ?>mod_terceros/json/terceros_facturas.json.php?cliente=1", minLength: 2,
    search  : function(){$(this).addClass('working');},
      open    : function(){$(this).removeClass('working');
              $(".ui-autocomplete").css("z-index", 2050); // Ajusta el z-index

              $("#paid-visits").fadeOut();

      },
      
      select: function( event, ui ) {
        //alert( "You selected:2 " + ui.item.label );

        console.log("Esto devuelve la Busqueda");
        console.log(ui.item);

        $("#patient").val(ui.item.id);
      $("#fk_cliente").val(ui.item.id);
      $("#identification").val(ui.item.identification);
      $("#email").val(ui.item.email);
      $("#para_email").val(ui.item.email);
      $("#paid-visits").fadeIn();
      $("#whatsapp").val(ui.item.telefono);

      $("#whatsapp_div").fadeIn();
      renderiza_grafico();
      
    },
     response: function(event, ui) {
         //console.log(ui.content);
          if (ui.content==null) {
             $(this).removeClass('working');
          }
      }
  });
});


});

</script>

<!-------------------------------------------------------------------------------->
<!-------------------------------------------------------------------------------->
<!------------------------------------ Modal ------------------------------------->
<!------------------------------------ Modal ------------------------------------->
<!-------------------------------------------------------------------------------->
<!-------------------------------------------------------------------------------->
<div class="modal fade" id="form-agenda-cita" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">🚀 Crear Cita </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <!----   Pantalal de Crear Cita  -------->
        <!----   Pantalal de Crear Cita  -------->
        <!----   Pantalal de Crear Cita  -------->
        <div class="row pantalla_1">

          <div class="col-md-12">
            <div class="list-group">
              <div class="list-group-item d-flex justify-content-between align-items-start">
                <div class="me-auto">
                  <div class="fw-bold title">Cliente</div>
                
                </div>
  
              </div>

              <div class="row">
              <div class="col-md-6" >
                  <input 
                        type  = "text" 
                        id    = "cliente_buscador" 
                        placeholder = "Digite nombre del cliente" 
                        class       = "form-control form-control-sm ui-autocomplete-input" 
                        autocomplete="off">
                  </div>

                  <div class="col-md-6" style="display:none" id="whatsapp_div" >
                  <div class="input-group">
          <input type="text" class="form-control" id="whatsapp">
            <button OnClick="whatsapp()"  title="Editar prioridades" class="btn btn-success _effect--ripple waves-effect waves-light" type="button" aria-expanded="false"><i class="fa fa-whatsapp" aria-hidden="true"></i></button>
        </div>
                </div>
              </div>

              <div class="list-group-item d-flex justify-content-between align-items-start">
                
                  <p class="sub-title mb-0" id="paid-visits"></p>
                  <div class="balance-info">
                                                <h6>Facturado</h6>
                                                <p id="total_balance"></p>
                                            </div>
              </div>

              

              <div class="list-group-item d-flex justify-content-between align-items-start">
                <div class="me-auto">
                  <div class="fw-bold title">Fecha</div>
                  <p class="sub-title mb-0" id="citas_fecha">-</p>
                </div>
              </div>

         
              <div class="list-group-item d-flex justify-content-between align-items-start">
                <div class="me-auto">
                  <div class="fw-bold title">Bonos </div>
                  <p class="sub-title mb-0" id="fechas_txt" ><a style='cursor:pointer;' OnClick='Bonos()'> Tratamiento 8/5</a> </p>
                </div>
              </div>




              <div class="list-group-item  justify-content-between align-items-start">
                <div class="me-auto">
                  <div class="fw-bold title">Estado</div>
                  <select class="form-control form-control-sm" id="estado_txt">
                    <option>Seleccionar</option>
                    <?php 
                    foreach ($select_estados as $key => $valor){
                      echo "<option value='{$key}' >{$valor['etiqueta']}</option>";
                    }
                    ?>

                  </select>
                </div>
              </div>

              <div class="list-group-item  justify-content-between align-items-start">
                <div class="me-auto">
                  <div class="fw-bold title">Servicio</div>
                  <select class="form-control form-control-sm" id="fk_producto">

                  <option>Seleccionar</option>
                    <?php 
                    foreach ($select_productos as $key => $valor){
                      echo "<option value='{$valor['rowid']}' >{$valor['ref']}</option>";
                    }
                    ?>
                      
                  </select>
                </div>
              </div>

              <div class="list-group-item  justify-content-between align-items-start">
                <div class="me-auto">
                  <div class="fw-bold title">🗒️ Notas</div>
                  <textarea class="form-control form-control-sm" id="comentario_cierre_txt" style="height: 144px;" placeholder="Notas"></textarea>
                </div>
              </div>
             
            </div>
          </div>
          
        </div>
        <!----   Pantalal de Crear Cita  -------->
        <!----   Pantalal de Crear Cita  -------->
        <!----   Pantalal de Crear Cita  -------->
        <div class="row pantalla_2" style="display:none" id="pantalla_facturacion">
          Aqui Facturamos ** Julio meter capacidad de meter lineas como la factura igual
        </div>


        <div class="row pantalla_3" style="display:none" id="pantalla_bonos">
                    
        </div>

        

      </div>
      <div class="modal-footer">
        <button type="button" class="btn" data-bs-dismiss="modal">Cerrar</button>
        <input type="hidden" id="id_oportunidad_actividad" >
        <div class="btn-group" role="group">
                     <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle _effect--ripple waves-effect waves-light show" data-bs-toggle="dropdown" aria-expanded="true">
                        Opciones Avanzadas
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down">
                           <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                     </button>
                     <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1" data-popper-placement="bottom-start" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(0px, 41.1111px);">
                        <li><a class="dropdown-item" href="#" onclick="confirmar_eliminar(156, 0)"><i class="fa fa-fw fa-trash-o" aria-hidden="true"></i>Eliminar Borrador</a></li>                        <li class="divider"></li>
                        <li class="pantalla_1"><a class="dropdown-item" href="#" onclick="facturar()"><i class="fa fa-fw fa-check-circle-o" aria-hidden="true"></i> Grabar Factura</a></li>                  
                        <li class="pantalla_2"><a class="dropdown-item" href="#" onclick="datos_general()"><i class="fa fa-fw fa-check-circle-o" aria-hidden="true"></i> Datos Cita</a></li>             

                        <li><a class="dropdown-item" href="#" OnClick="generarPdf()" >Descargar Borrador  - PDF</a>
                        </li>
                    </ul>
          </div>

          <button type="button" class="btn btn-success btn-update-event" onclick="Crear_Cita()">Crear Cita</button>

      </div>
    </div>
  </div>
</div>
<!-------------------------------------------------------------------------------->
<!-------------------------------------------------------------------------------->
<!------------------------------------ Modal ------------------------------------->
<!------------------------------------ Modal ------------------------------------->
<!-------------------------------------------------------------------------------->
<!-------------------------------------------------------------------------------->
<script src="https://designreset.com/cork/html/src/plugins/src/apex/apexcharts.min.js"  ></script>
<script>

function generarValoresAleatorios(cantidad, min, max) {
  const valores = [];
  for (let i = 0; i < cantidad; i++) {
    valores.push(Math.floor(Math.random() * (max - min + 1)) + min);
  }
  return valores;
}


function formatearEuros(numero) {
  return new Intl.NumberFormat('es-ES', {
    style: 'currency',
    currency: 'EUR'
  }).format(numero);
}


const mesesDelAno = {
  1: 'Enero',
  2: 'Febrero',
  3: 'Marzo',
  4: 'Abril',
  5: 'Mayo',
  6: 'Junio',
  7: 'Julio',
  8: 'Agosto',
  9: 'Septiembre',
  10: 'Octubre',
  11: 'Noviembre',
  12: 'Diciembre'
};


function renderiza_grafico(){ 
  // Paid Visits
  var Theme = 'light';
      
      Apex.tooltip = {
          theme: Theme
      }
      listado = generarValoresAleatorios(10, 56, 200);

      var sumaTotal = listado.reduce(function(acumulador, valorActual) {
        return acumulador + valorActual;
      }, 0); // El 0 es el valor inicial del acumulador

      $("#total_balance").empty().html(formatearEuros(sumaTotal));

      var spark2 = {
      chart: {
        id: 'total-users',
        group: 'sparks1',
        type: 'line',
        height: 80,
        sparkline: {
          enabled: true
        },
        dropShadow: {
          enabled: true,
          top: 3,
          left: 1,
          blur: 3,
          color: '#009688',
          opacity: 0.7,
        }
      },
      series: [{
        data: listado
      }],
      stroke: {
        curve: 'smooth',
        width: 2,
      },
      markers: {
        size: 0
      },
      grid: {
        padding: {
          top: 35,
          bottom: 0,
          left: 40
        }
      },
      colors: ['#009688'],
      xaxis: {
    categories: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre'], // Meses del año
  },
  tooltip: {
    x: {
      show: true, // Habilitar la visualización de datos del eje X en el tooltip
      formatter: function (val, opts) {
        return " Mes:"+  mesesDelAno[opts.w.globals.labels[opts.dataPointIndex]]; 
      }
    },
    y: {
      title: {
        formatter: function () {
          return 'Ventas : '; // Etiqueta personalizada
        }
      }
    }
  },
      responsive: [{
          breakpoint: 1351,
          options: {
            chart: {
                height: 95,
            },
            grid: {
                padding: {
                  top: 35,
                  bottom: 0,
                  left: 0
                }
            },
          },
      },
      {
          breakpoint: 1200,
          options: {
            chart: {
                height: 80,
            },
            grid: {
                padding: {
                  top: 35,
                  bottom: 0,
                  left: 40
                }
            },
          },
      },
      {
          breakpoint: 576,
          options: {
            chart: {
                height: 95,
            },
            grid: {
                padding: {
                  top: 35,
                  bottom: 0,
                  left: 0
                }
            },
          },
      }
      ]
      }
      
      
       

      // Paid Visits
      d_1C_2 = new ApexCharts(document.querySelector("#paid-visits"), spark2);
      d_1C_2.render();

    }

      </script>




<script>
function Bonos(){

          add_notification({
                            text: '✔️ Mostrando Bonos',
                            actionTextColor: '#fff',
                            backgroundColor: '#00ab55',
                            dismissText: 'Cerrar'
                        })


                        
  
  $.ajax({
    method: "POST",
    url: "<?= ENLACE_WEB ?>mod_citas_agenda/ajax/facturar.bonos.ajax.php",
    beforeSend: function(xhr) {
              
    },
    data: {
      rowid: $("#id_oportunidad_actividad").val(),
      action: "eliminar_producto" ,
      facturar_fk_producto :  facturar_fk_producto
    },
  }).done(function(data) {
            $("#pantalla_bonos").empty().html(data);
            add_notification({
                            text: '✔️ Item Modificado',
                            actionTextColor: '#fff',
                            backgroundColor: '#00ab55',
                            dismissText: 'Cerrar'
                        });
 
      $(".pantalla_1").hide();
      $(".pantalla_2").hide();
      $(".pantalla_3").fadeIn();

   });



  
}


function facturar_fk_producto_eliminar( facturar_fk_producto ){

  
  $.ajax({
    method: "POST",
    url: "<?= ENLACE_WEB ?>mod_citas_agenda/ajax/facturar.cita.ajax.php",
    beforeSend: function(xhr) {
              
    },
    data: {
      rowid: $("#id_oportunidad_actividad").val(),
      action: "eliminar_producto" ,
      facturar_fk_producto :  facturar_fk_producto
    },
  }).done(function(data) {
            $("#pantalla_facturacion").empty().html(data);
            add_notification({
                            text: '✔️ Item Modificado',
                            actionTextColor: '#fff',
                            backgroundColor: '#00ab55',
                            dismissText: 'Cerrar'
                        });


   });

}




function facturar_fk_producto(){
   
$.ajax({
    method: "POST",
    url: "<?= ENLACE_WEB ?>mod_citas_agenda/ajax/facturar.cita.ajax.php",
    beforeSend: function(xhr) {
              
    },
    data: {
      rowid: $("#id_oportunidad_actividad").val(),
      action: "agregar_producto" ,
      facturar_fk_producto : $("#facturar_fk_producto").val()
    },
  }).done(function(data) {
       $("#pantalla_facturacion").empty().html(data);
            add_notification({
                            text: '✔️ Item Modificado',
                            actionTextColor: '#fff',
                            backgroundColor: '#00ab55',
                            dismissText: 'Cerrar'
                        })

   });
 

}

  function facturar( ){ 

                  add_notification({
                            text: '✔️ Facturaci&oacute;n',
                            actionTextColor: '#fff',
                            backgroundColor: '#00ab55',
                            dismissText: 'Cerrar'
                        })

                      

$.ajax({
    method: "POST",
    url: "<?= ENLACE_WEB ?>mod_citas_agenda/ajax/facturar.cita.ajax.php",
    beforeSend: function(xhr) {
              
    },
    data: {
      rowid: $("#id_oportunidad_actividad").val(),
    },
  }).done(function(data) {

                        
      $("#pantalla_facturacion").empty().html(data);
      $(".pantalla_1").hide();
      $(".pantalla_2").fadeIn();


  });





  }


  function datos_general(){
    add_notification({
                            text: '✔️ Datos Generales',
                            actionTextColor: '#fff',
                            backgroundColor: '#00ab55',
                            dismissText: 'Cerrar'
                        })

      $(".pantalla_3").hide();
      $(".pantalla_2").hide();
      $(".pantalla_1").fadeIn();


  }
</script>

<script>

function generarPdf() {
          $.ajax({
                  method: "POST",
                  url: "<?php echo ENLACE_WEB; ?>mod_documentos_mercantiles/ajax/descargar_documento.ajax.php",
                  data: {
                      documento: 158, // Usa el ID pasado como argumento
                      tipo: "factura"
                  },
                  beforeSend: function(xhr) {
                      // Opcional: mostrar un loader o mensaje
                  }
              })
              .done(function(msg) {
                  if(msg != ''){
                      const linkSource = `data:application/pdf;base64,${msg}`;
                      const downloadLink = document.createElement("a");
                      const fileName = "PDF-F-F2024-3.pdf";
      
                      downloadLink.href = linkSource;
                      downloadLink.download = fileName;
                      downloadLink.click();
                  }else{
                      add_notification({
                          text: 'Error: Documento no generado',
                          actionTextColor: '#fff',
                          backgroundColor: '#e7515a',
                      });
                  }
              });
      } 



function whatsapp( ){
 
  $.ajax({
                  method: "POST",
                  url: "<?php echo ENLACE_WEB; ?>mod_citas_agenda/ajax/notificar.whatsapp.php",
                  data: {
                      "cliente": $("#cliente_buscador").val() , // Usa el ID pasado como argumento
                      "telefono": $("#whatsapp").val()
                  },
                  beforeSend: function(xhr) {
                      // Opcional: mostrar un loader o mensaje
                  }
              })
              .done(function(msg) {
                  alert(msg)
              });


}
</script>