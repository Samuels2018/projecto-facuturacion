<div class="row mt-2">
         <div class="col-md-10">
            <div class="">

                     <div class="alert alert-outline-warning alert-dismissible fade show mb-4 zona_trabajo_factura card" role="alert">
                        <div class="col-md-12">
                           <div class="alert alert-arrow-left alert-icon-left alert-light-light--<?php echo  $Utilidades->diccionario_estados_hacienda[$Documento->estado_hacienda]['class'];  ?>  alert-dismissible fade show mb-4" role="alert">
                              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

                              <?php if ($Documento->estado_hacienda == 3) { ?>
                                 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon-success">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <path d="M9 12l2 2l4-4"></path>
                                 </svg>

                              <?php } else if ($Documento->estado_hacienda == 2) { ?>
                                 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon-error">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="8" x2="12" y2="12"></line>
                                    <line x1="12" y1="16" x2="12" y2="16"></line>
                                 </svg>
                              <?php } else if ($Documento->estado_hacienda == 1) { ?>
                                 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon-wait">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <path d="M12 6v6l4 2"></path>
                                 </svg>

                              <?php } ?>


                              <strong><?php echo $Documento->estado_verifactum_registro; ?></strong>
                              <span class="verifactum_mensaje_hacienda"><?php $respuesta =  $Documento->recupera_huella_de_factura($Documento->id);
                                                                        echo $respuesta['respuesta_descripcion_registro_descripcion'];
                                                                        ?>
                              </span>

                           </div>
                        </div>
                        <div class="col-md-12">
                           <div
                              class="alert alert-arrow-left alert-icon-left alert-light-info  alert-dismissible fade show mb-4" role="alert">
                              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon-fingerprint">
                                 <path d="M12 12c-2 0-4.2.3-6 1-2.2 1-3.5 3-3.8 5.3"></path>
                                 <path d="M12 12c2 0 4.2-.3 6-1 2.2-1 3.5-3 3.8-5.3"></path>
                                 <path d="M12 12c0-2-1.5-4.2-3-6-1.2-1.4-3-2-4.5-2"></path>
                                 <path d="M12 12c0 2 1.5 4.2 3 6 1.2 1.4 3 2 4.5 2"></path>
                                 <path d="M12 12c-1 1-2.5 2.5-2 4"></path>
                                 <path d="M12 12c1-1 2.5-2.5 2-4"></path>
                              </svg>
                              <strong>Huella Verifactum</strong>
                                 <?php if ($Documento->estado_hacienda==0){ ?>
                                       <span class="Huella_sha256">En Espera de <a href="#"  onclick="enviar_Verifactu()"><b><i class="fas fa-paper-plane"></i> Enviar Verifactu</b></a></span>
                                 <?php } else { ?>
                                       <span class="Huella_sha256"><?php echo $Documento->xml_huella_sha256; ?></span>
                                 <?php }        ?>                                       

                           </div>
                        </div>
                     </div>
          </div>
          </div>
                                 
          <div class="col-md-2">
                     <?php  echo $Documento->QR();   ?>
          </div>


      </div>




      
<script>



function mostrar_detalle_fe() {
      cargarTimeline(<?php echo $Documento->id; ?>);
      $(".zona_trabajo_factura").hide();
      $(".zona_trabajo_fe").fadeIn(1000);
   }

   function mostrar_detalle_factura() {
      $(".zona_trabajo_factura").fadeIn(1000);
      $(".zona_trabajo_fe").hide();

   }

   
   function cargarTimeline(fk_documento) {
      $.ajax({
         url: "<?php echo ENLACE_WEB; ?>mod_europa_facturacion/ajax/generar_timeline.php",
         type: 'POST',
         data: {
            fk_documento
         },
         success: function(response) {
            
            const data = JSON.parse(response);
            console.log("Respuesta AEAT ");
            console.log(response);

            if (data.success) {
               const timelineContainer = $('.timeline-line');
               timelineContainer.empty(); // Limpia el contenedor antes de llenarlo

               data.data.forEach(registro => {
                  const time = new Date(registro.FechaHoraHusoGenRegistro);
                  const timeFormatted = `${time.getHours()}:${time.getMinutes()}`;
                  const dateFormatted = time.toLocaleString('es-ES', {
                     day: '2-digit',
                     month: '2-digit',
                     year: 'numeric',
                     hour: '2-digit',
                     minute: '2-digit',
                     second: '2-digit',
                  });
                  $('.counter').text(data.count);


                  // Determinar la clase según el estado
                  let statusClass = 'status-default'; // Clase predeterminada
                  if (registro.respuesta_estado_registro === 'Correcto') {
                     statusClass = 'status-correcto';
                  } else if (registro.respuesta_estado_registro === 'Incorrecto') {
                     statusClass = 'status-incorrecto';
                  } else if (registro.respuesta_estado_registro === 'AceptadoConErrores') {
                     statusClass = 'status-advertencia';
                  }

                  const item = `
                        <div class="item-timeline">
                            <p class="t-time">${timeFormatted}</p>
                            <div class="t-dot t-dot-primary ${statusClass}"></div>
                            <div class="t-text">
                                <p><strong>Env&iacute;o</strong> ${registro.respuesta_estado_envio}</p>
                                <p><strong>Registro</strong> ${registro.respuesta_estado_registro}</p>
                                <p>Respuesta: ${registro.respuesta_descripcion_registro_descripcion}</p>
                                <p class="t-meta-time">${dateFormatted}</p>
                                <a href="<?php echo ENLACE_WEB; ?>mod_europa_facturacion/ajax/descargar.timeline.php?id=${registro.rowid}" style="color: blue; cursor: pointer;" title="Descargar XML">Descargar Respuesta <i class="fa fa-fw fa-download" style="opacity: 0.3;" aria-hidden="true"></i></a>
                            </div>
                        </div>
                    `;
                  timelineContainer.append(item);
               });
            }
         },
         error: function(error) {
            console.error('Error en la solicitud:', error);
         }
      });
   }




   
   function enviar_Verifactu( ){


   $.ajax({
         method: "POST",
         url: "<?php echo ENLACE_WEB_VERIFACTU; ?>servicio.php",
         data: {
            id       : "<?php echo md5($Documento->id); ?>", // Usa el ID pasado como argumento
            Entidad  : "<?php echo md5($_SESSION['Entidad']); ?>", // Usa el ID pasado como argumento
         },
         beforeSend: function(xhr) {
            $(".boton_enviar_verifactum").empty().html('<i class="fas fa-gear fa-spin"></i> Verifactu ');
         }
      })
      .done(function(response) {
        
         $(".boton_enviar_verifactum").empty().html('Verifactu');

         data = JSON.parse(response);
         console.log(data);

         if (data.error == 0 ){
            $(".Huella_sha256").empty().hide().html(data.Huella_sha256).fadeIn();
            $(".Verifactum").empty().hide().html(data.mensaje_estado).fadeIn();
            $(".verifactum_mensaje_hacienda").empty().hide().html(data.mensaje_hacienda).fadeIn();

            add_notification({
                  text: data.mensaje_hacienda ,
                  actionTextColor: '#fff',
                  backgroundColor: data.mensaje_estado_error_o_no == "error" ? '#e7515a' : '#00AB55'
               });

         } else {

            var mensaje_hacienda = data.mensaje_hacienda ? data.mensaje_hacienda : '';
            var mensaje_estado = data.mensaje_estado ? data.mensaje_estado : '';
            var mensaje = data.mensaje ? data.mensaje : '';

            var mensaje_final = "Respuesta " + mensaje_hacienda + mensaje_estado + mensaje;

            add_notification({
               text: mensaje_final,
               actionTextColor: '#fff',
                  backgroundColor: '#e7515a',
               });


         }

      });

}
</script>


