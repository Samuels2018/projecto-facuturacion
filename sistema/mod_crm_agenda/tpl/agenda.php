<!-- Include jQuery Roadmap Plugin -->
<script src="<?= ENLACE_WEB ?>bootstrap/jquery.roadmap.js" type="text/javascript"></script>
<link rel="stylesheet" href="<?= ENLACE_WEB ?>bootstrap/plugins/roadmap.css">

<?php
include_once ENLACE_SERVIDOR . "mod_crm_agenda/object/actividades.object.php";
$actividad = new Actividades_($dbh);
$select_estados = $actividad->select_estados();

//echo $_SESSION['Entidad'] ;
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

<!-- Modal -->
<div class="modal fade" id="form-agenda" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Detalle Tarea</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <div class="row">
          <div class="col-md-12">
            <div class="list-group">
              <div class="list-group-item d-flex justify-content-between align-items-start">
                <div class="me-auto">
                  <div class="fw-bold title">Cliente</div>
                  <p class="sub-title mb-0" id="cliente_txt"></p>
                </div>
              </div>

              <div class="list-group-item d-flex justify-content-between align-items-start">
                <div class="me-auto">
                  <div class="fw-bold title">Usuario Asignado</div>
                  <p class="sub-title mb-0" id="usuario_txt">-</p>
                </div>
              </div>

              <div class="list-group-item d-flex justify-content-between align-items-start">
                <div class="me-auto">
                  <div class="fw-bold title">Tarea</div>
                  <p class="sub-title mb-0" id="actividad_agenda">-</p>
                </div>
              </div>

              <div class="list-group-item d-flex justify-content-between align-items-start">
                <div class="me-auto">
                  <div class="fw-bold title">Fecha</div>
                  <p class="sub-title mb-0" id="fechas_txt">-</p>
                </div>
              </div>

              <div class="list-group-item  justify-content-between align-items-start">
                <div class="me-auto">
                  <div class="fw-bold title">Estado</div>
                  <select class="form-control form-control-sm" id="estado_txt">
                    <option>Seleccionar</option>
                    <?php
                    echo $select_estados;
                    ?>
                  </select>
                </div>
              </div>

              <div class="list-group-item  justify-content-between align-items-start">
                <div class="me-auto">
                  <div class="fw-bold title">Comentario</div>
                  <input class="form-control form-control-sm" id="comentario_txt"/>
                </div>
              </div>

              <div class="list-group-item  justify-content-between align-items-start">
                <div class="me-auto">
                  <div class="fw-bold title">Comentario Cierre</div>
                  <textarea class="form-control form-control-sm" id="comentario_cierre_txt"></textarea>
                </div>
              </div>
             
            </div>
          </div>
          
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn" data-bs-dismiss="modal">Cerrar</button>
        <input type="hidden" id="id_oportunidad_actividad" >
        <button type="button" class="btn btn-success btn-update-event" onclick="actualizarActividad()">Actualizar</button>
       
      </div>
    </div>
  </div>
</div>

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
    //cargarCalendario();


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
    var calendarsEvents = {
      Work: 'primary',
      Personal: 'success',
      Important: 'danger',
      Travel: 'warning',
    }

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
      dateClick: function(info) {
        start = info.startStr;
        end = info.endStr;
      },
      slotEventOverlap: false,
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
              url: "<?php echo ENLACE_WEB; ?>mod_crm_agenda/class/clases.php",
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
      height: checkWidowWidth() ? 900 : 1052,
      initialView: checkWidowWidth() ? 'listWeek' : 'dayGridMonth',
      initialDate: `${newDate.getFullYear()}-${getDynamicMonth()}-07`,
      //headerToolbar: calendarHeaderToolbar,
      //events: calendarEventsList,
      eventSources: [

        // your event source
        {
          url: "<?php echo ENLACE_WEB; ?>mod_crm_agenda/class/clases.php",
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
      /*select: calendarSelect,
      unselect: function() {
        console.log('unselected')
      },*/

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

  function actualizarActividad(){

    $.ajax({
        method: "POST",
        url: "<?= ENLACE_WEB ?>mod_crm_agenda/class/clases.php",
        beforeSend: function(xhr) {},
        data: {
          action: 'actualizarTarea',
          rowid: $("#id_oportunidad_actividad").val(),
          fk_estado: $("#estado_txt").val(),
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

  function cargarCalendario() {
    var tipo = 'general';
    btn = $('.fc-myCustomButton-button');
    var textoBoton = localStorage.getItem('textoBoton') || 'Mis actividades';


    var today = new Date().toISOString().slice(0, 10);
    var calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      locale: 'es',
      //creacion de boton personalizado
      customButtons: {
        myCustomButton: {
          text: textoBoton,
          click: function() {

            calendar.getEventSources().forEach(function(source) {
              source.remove();
            });

            btn = $('.fc-myCustomButton-button');
            if (textoBoton == 'Mis actividades') {
              tipo = 'usuario';
              // btn.text('');
              // btn.text('Todas');
              textoBoton = 'Todas';
              btn.text('Todas');

            } else {
              //
              tipo = 'general';
              // btn.text('');
              // btn.text('Mis actividades');
              textoBoton = 'Mis actividades';
              btn.text('Mis actividades');

            }
            localStorage.setItem('textoBoton', textoBoton);

            calendar.addEventSource({
              url: "<?php echo ENLACE_WEB; ?>mod_crm_agenda/class/clases.php",
              method: 'POST',
              extraParams: {
                tipo: tipo,
                action: 'listadoActividadesAgenda'
              },
              failure: function() {
                alert('Error al cargar los eventos!');
              },
              color: 'blue', // a non-ajax option
              textColor: 'white' // a non-ajax option
            });
          },
        }
      },
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
      eventClick: function(info) {

        $('#form-agenda').modal('show');
      },
      eventSources: [

        // your event source
        {
          url: "<?php echo ENLACE_WEB; ?>mod_crm_agenda/class/clases.php",
          method: 'POST',
          extraParams: {
            tipo: 'general',
            action: 'listadoActividadesAgenda'
          },
          failure: function() {
            alert('Error al cargar los eventos!');
          },
          color: 'blue', // a non-ajax option
          textColor: 'white' // a non-ajax option
        }

        // any other sources...

      ],
      eventClick: function(info) {
        const date = new Date(info.date);

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
        }



        $("#form-agenda").modal("show");

        info.el.style.borderColor = 'red';
      },

      //se personaliza la apariencia de los eventos, se agrega el icono al elemento
      eventContent: function(arg) {

        let html = `
                <div class="event">
                  <i class="${arg.event.extendedProps.icon}"></i>
                  <span class="title" style="background:${arg.event.backgroundColor}; color:#fff;">${arg.event.title}</span>
                </div>
              `;
        let html2 = `<div class="fc-event-main"  style="background:${arg.event.backgroundColor}; color:#fff;">
                      <div class="fc-event-main-frame">
                        <div class="fc-event-title-container">
                          <div class="fc-event-title fc-sticky">${arg.event.title}</div>
                        </div>
                      </div>
                    </div>
              `;
        return {
          html
        };
      },
      eventTimeFormat: {
        hour: '2-digit',
        minute: '2-digit',
        meridiem: true
      },

      //
      // Evento de cambio de vista
      viewWillUnmount: function(view) {
        btn.text(textoBoton);
        console.log('La vista que se va a desmontar es: ' + view.view.type);
      },

      viewDidMount: function(info) {
        //alert('Vista actual: ' + info.view.type);
        //console.log('hi');
      },
      dateClick: function(info) {
        btn.text(textoBoton);

        //alert('Fecha: ' + info.dateStr);
      },

    });

    calendar.render();

    window.calendario = calendar;
    $('#estado_actividad').change(function() {
      if ($(this).val() != 3) {
        $('.comentario-cierre').attr('style', 'display:none !important');

      } else {
        $('.comentario-cierre').removeAttr('style');
      }
    });

  }
  
</script>