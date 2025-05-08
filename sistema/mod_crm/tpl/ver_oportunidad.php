

<style>
 /* Estilo base para la fila */
.animacion_tr td {
  background-color: yellow !important;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3) !important;
  transform: translateX(0px) !important;
  opacity: 1 !important;
  transition: all 0.5s ease-in-out !important, opacity 2s ease-in !important;
}
/* Desaparecer desvaneciéndose */
.animacion_tr.fade-out td {
  transform: translateX(20px) !important;
  opacity: 0 !important;
}
</style>

<style>
  .ui-autocomplete {
    z-index: 99999999999999999999;
    /* Ajusta el z-index para que esté por encima del modal */
  }
</style>
<style type="text/css">
  .tab-servicios.fade {
    display: none;
  }

  .tab-servicios.fade.active {
    display: block;
  }

  label.strong {
    font-weight: 600;
  }

  .tab-adjuntos.fade {
    display: none;
  }

  .tab-adjuntos.fade.active {
    display: block;
  }
</style>
<style>
  #loading {
    display: none;
    position: fixed;
    z-index: 9999;
    height: 100%;
    width: 100%;
    top: 0;
    left: 0;
    background: rgba(0, 0, 0, 0.5);
    text-align: center;
    color: white;
    font-size: 2em;
  }

  #loading-text {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
  }
</style>
<?php

require_once ENLACE_SERVIDOR . 'mod_crm/object/oportunidad.object.php';
require_once ENLACE_SERVIDOR . 'mod_productos/object/productos.object.php';

$Documento = new Oportunidad($dbh, $_SESSION['Entidad']);
$Documento->fetch($_GET['id']);


//si no hay rowid, cerrar conexion
if (!$Documento->rowid > 0) {
  echo acceso_invalido();
  exit(1);
}


if (intval($Documento->entidad) !== intval($_SESSION['Entidad'])) {

  echo acceso_invalido();
  exit();
}

//totalizamos
$Documento->totalizar_oportunidad();



$accion = 'mod_crm';

$fiche_tarea = isset($_GET['fiche_tarea']) ? $_GET['fiche_tarea'] : '';

//Listado de cotizaciones  
$listado_cotizaciones = $Documento->obtener_cotizaciones_de_oportunidad($_GET['id']);


//Listado de productos
$Productos = new Productos($dbh, $_SESSION['Entidad']);
$Productos->entidad =
  $Productos->Impuestos(); // OBTENEMOS LOS IMPUESTOS

$listado_impuestos = $Productos->impuestos;




?>


<div id="loading">
  <div id="loading-text"><i class="fa fa-id-card-o" aria-hidden="true"></i> Generando Cotización...</div>
</div>


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
                <label class="strong"><i class="fa fa-map-signs" aria-hidden="true"></i> REFERENCIA :</label class="strong">
              </div>
              <div class="col-md-3">
                <label for=""><?= $Documento->consecutivo ?></label>
              </div>

            
           

              <div class="col-md-3">
                <label class="strong"><i class="fa fa-tags" aria-hidden="true"></i> TAGS :</label class="strong">
              </div>
              <div class="col-md-3">
                <select class="form-control select2" name="tags" multiple='multiple' id="tags">
                  <?php
                  $arrayRecuperadoTags = explode(",", $Documento->tags);

                  foreach ($arrayRecuperadoTags as $item => $value) {
                    echo ' <option value="' . $value . '">' . $value . '</option>';
                  }

                  ?>

                </select>


              </div>


            </div>
                  
            <div class="row mt-2">

            <div class="col-md-3">
                <label class="strong"><i class="fa fa-user" aria-hidden="true"></i> Etiqueta :</label class="strong">
              </div>
              <div class="col-md-3">
                <label for=""><?= $Documento->etiqueta ?></label>
              </div>

            </div>
       
            <div class="row mt-2">
                  
       


            <div class="col-md-3">
                <label class="strong"><i class="fa fa-user" aria-hidden="true"></i> NOMBRE CLIENTE :</label class="strong">
              </div>
              <div class="col-md-3">
                <label for=""><?= $Documento->cliente_txt ?></label>
              </div>

              <div class="col-md-3">
                <label class="strong"><i class="fa fa-address-book" aria-hidden="true"></i> CONTACTO :</label class="strong">
              </div>
              <div class="col-md-3">
                  <?php 
                      if(intval($Documento->fk_tercero_contacto)>0){
                  ?>
                <label for="">
                    <a target="_blank" href="<?php echo ENLACE_WEB; ?>/contactos_crm_editar/<?php echo $Documento->fk_tercero_contacto; ?>">
                        <?php echo $Documento->contacto; ?>
                    </a>
                    <a target="_blank" href="<?php echo ENLACE_WEB; ?>/contactos_crm_editar/<?php echo $Documento->fk_tercero_contacto; ?>">
                        <i class="fa fa-external-link" aria-hidden="true"></i>
                    </a>
                </label>
                <?php }else{ ?>
                    <label  for=""><?=$Documento->contacto?></label>
                  <?php } ?>
              </div>


            </div>





            <div class="row mt-2">
              <div class="col-md-3">
                <label class="strong"><i class="fa fa-envelope-o" aria-hidden="true"></i> CORREO :</label class="strong">
              </div>
              <div class="col-md-3">

                <label for=""><?= $Documento->contacto_correo ?></label>
              </div>

              <div class="col-md-3">
                <label class="strong"><i class="fa fa-phone" aria-hidden="true"></i> TELEFONO :</label class="strong">
              </div>
              <div class="col-md-3">
                <?= $Documento->contacto_telefono ?> </div>

            </div>





            <div class="row mt-2">
              <div class="col-md-3">
                <label class="strong"><i class="fa fa-calendar" aria-hidden="true"></i> TIEMPO ENTREGA :</label class="strong">
              </div>
              <div class="col-md-3">

                <label for=""><?= $Documento->cotizacion_tiempo_entrega ?> Días</label>
              </div>

              <div class="col-md-3">
                <label class="strong"><i class="fa fa-cart-arrow-down" aria-hidden="true"></i> VALIDEZ OFERTA :</label class="strong">
              </div>
              <div class="col-md-3">

                <label for=""><?= $Documento->validez_oferta ?> Días</label>
              </div>

            </div>

            
            <div class="row mt-2">
              <div class="col-md-3">
                <label class="strong"><i class="fa fa-calendar" aria-hidden="true"></i> FECHA DE OPORTUNIDAD :</label class="strong">
              </div>
              <div class="col-md-3">

                <label for=""><?php echo $Documento->fecha != null? date('d-m-Y',strtotime($Documento->fecha)):'Sin fecha oportunidad'; ?></label>
              </div>
            </div>

            <div class="row mt-2">
              <div class="col-md-3">
                <label class="strong"><i class="fa fa-calendar" aria-hidden="true"></i> FECHA DE CIERRE :</label class="strong">
              </div>
              <div class="col-md-3">

                <label for=""><?php echo $Documento->fecha_cierre != null? date('d-m-Y',strtotime($Documento->fecha_cierre)):'Sin fecha cierre'; ?></label>
              </div>
            </div>


            <div class="row mt-2">
              <div class="col-md-3">
                <label class="strong"><i class="fa fa-tag" aria-hidden="true"></i> ETIQUETA :</label class="strong">
              </div>
              <div class="col-md-3">

                <label for=""><?= $Documento->etiqueta ?></label>
              </div>

              <div class="col-md-3">
                <label class="strong"><i class="fa fa-sticky-note" aria-hidden="true"></i> NOTA :</label class="strong">
              </div>
              <div class="col-md-3">
                <label for=""><?= $Documento->nota ?></label>
              </div>

            </div>


            <div class="row mt-2">
              <div class="col-md-3">
                <label class="strong"><i class="fa fa-folder-open" aria-hidden="true"></i> CATEGORIA :</label class="strong">
              </div>
              <div class="col-md-3">

                <label for=""><?= $Documento->categorias_etiqueta ?></label>
              </div>


              <div class="col-md-3">
                <label class="strong"><span style="font-size:15px; font-weight: bold;"><?php echo $Documento->moneda_simbolo; ?></span> IMPORTE :</label class="strong">
              </div>
              <div class="col-md-3">

                <label for="" id="importe_real_refresh"><?php echo numero_euro($Documento->total); ?></label>
              </div>

            </div>




            <div class="col-md-3">

            </div>



            <div class="row mt-2">
              <div class="col-md-3">
                <label class="strong"><i class="fa fa-user"></i> USUARIO ASIGNADO :</label class="strong">
              </div>
              <div class="col-md-3">
                <span class="avatar-chip bg-danger mb-2 me-4 position-relative">
                  <img src="https://ui-avatars.com/api/?name=<?php echo $Documento->usuario_asignado; ?>&background=3F5BE0&color=fff" alt="<?php echo $Documento->usuario_asignado; ?>" width="96" height="96">
                  <span class="text"><?php echo $Documento->usuario_asignado; ?></span>
                </span>
              </div>

              <div class="col-md-6">
                <div class="form-group row mt-2">
                  <div class="form-group d-flex align-items-center">
                    <label class="col-sm-3 col-form-label col-form-label-sm">Configuraci&oacute;n</label>
                    <button OnClick="muestra_opciones_descuento()" class="btn btn-success btn-icon mb-2 me-4 btn-rounded _effect--ripple waves-effect waves-light">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings">
                        <circle cx="12" cy="12" r="3"></circle>
                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 .85 1.65 1.65 0 0 1-3.1 0 1.65 1.65 0 0 0-1-.85 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-.85-1 1.65 1.65 0 0 1 0-3.1 1.65 1.65 0 0 0 .85-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33h.1a1.65 1.65 0 0 0 1-.85 1.65 1.65 0 0 1 3.1 0 1.65 1.65 0 0 0 1 .85h.1a1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82v.1a1.65 1.65 0 0 0 .85 1 1.65 1.65 0 0 1 0 3.1 1.65 1.65 0 0 0-.85 1v.1z"></path>
                      </svg>
                    </button>
                  </div>
                </div>
                <!-- Parte izquierda de la cabecera (Descuentos) -->
                <div class="row alert alert-outline-primary alert-dismissible fade show mb-4" role="alert" id="muestra_opciones" style="display:none">
                  <button type="button" class="btn-close" onclick="muestra_opciones_descuento()" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close">
                      <line x1="18" y1="6" x2="6" y2="18"></line>
                      <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                  </button>
                  <div class="col-md-4">
                    <div class="form-check form-check-primary form-check-inline">
                      <input class="form-check-input" type="checkbox" id="aplicar_descuento" onchange="aplicar_descuento()" <?php echo ($Documento->estado != 0 ? 'disabled readonly' : ''); ?>>
                      <label class="form-check-label" for="aplicar_descuento">
                        Utilizar Descuentos
                      </label>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-check form-check-primary form-check-inline">
                      <input class="form-check-input" type="checkbox" id="aplicar_RE" onchange="aplicar_RE()" <?php echo ($Documento->estado != 0 ? 'disabled readonly' : ''); ?>>
                      <label class="form-check-label" for="aplicar_RE">
                        Recargo Equivalencia
                      </label>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-check form-check-primary form-check-inline">
                      <input class="form-check-input" type="checkbox" id="aplicar_irp" onchange="aplicar_irp()" <?php echo ($Documento->estado != 0 ? 'disabled readonly' : ''); ?>>
                      <label class="form-check-label" for="aplicar_irp">
                        Retenci&oacute;n IRPF <?php echo $Documento->Entidad->retencion_porcentaje ?>
                    </div>
                  </div>

                </div>
              </div>
            </div>


            <div class="row mt-2">
              <div class="col-md-3">
                <label class="strong"><i class="fa fa-id-card-o" aria-hidden="true"></i> COTIZACIONES :</label class="strong">
              </div>
              <div class="col-md-3 listado_cotizaciones_ajax">
                <?php
                foreach ($listado_cotizaciones as $key => $value) {
                ?>
                  <a style="font-size: 20px; margin-top: 10px;" href="<?php echo ENLACE_WEB . 'ver_cotizacion/' . $listado_cotizaciones[$key]->fk_cotizacion; ?>" class="badge badge-primary">#<?php echo $listado_cotizaciones[$key]->referencia; ?></a>
                <?php
                }
                ?>
              </div>

              <div class="col-md-3"></div>
              <div class="col-md-3"></div>
            </div>






            <div class="row mt-3">
              <div class="col-md-3">
                <i class="fa fa-users" aria-hidden="true"></i> <label class="strong">RECURSOS HUMANOS :</label>
              </div>
              <div class="col-md-6">
                <?php
                //<?php echo $Usuarios->obtener_url_avatar_encriptada($_SESSION['usuario']); 
                foreach ($Documento->obtener_recurso_humano($Documento->id)  as $valor) {  ?>
                  <span class="avatar-chip bg-primary mb-2 me-4 position-relative">
                    <img src="<?php echo $Usuarios->obtener_url_avatar_encriptada($valor['fk_usuario']); ?>" alt="<?php echo $valor['usuario_txt']; ?>" width="96" height="96">
                    <span class="text"><?php echo $valor['usuario_txt']; ?></span>
                  </span>
                <?php  }                                                                  ?>


              </div>
            </div>

            <div class="row mt-2">
              <div class="col-md-3">
                <i class="fa fa-compass"></i> <label class="strong">ESTADO: </label>
              </div>
              <?php
              $badge = $Documento->funnel_detalle_estilo;

              if ($badge === '' || empty($badge)) {
                $badge = 'info';
              }
              ?>

              <div class="col-md-3">
                <span class="badge badge-<?php echo $badge; ?>"><?php echo $Documento->funnel_detalle_txt; ?></span>

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

          <!-- <li class="nav-item" role="presentation">
            <button class="nav-link" id="servicios-tab" data-bs-toggle="tab" data-bs-target="#servicios-tab-pane" type="button" role="tab" aria-controls="servicios-tab-pane" aria-selected="false">Servicios</button>
          </li> -->

          <li class="nav-item" role="presentation">
            <button class="nav-link" id="detalle-tab" data-bs-toggle="tab" data-bs-target="#detalle-tab-pane" type="button" role="tab" aria-controls="detalle-tab-pane" aria-selected="false">Artículos/Servicios</button>
          </li>


          <li class="nav-item" role="presentation">
            <button class="nav-link" id="adjuntos-tab" data-bs-toggle="tab" data-bs-target="#adjuntos-tab-pane" type="button" role="tab" aria-controls="adjuntos-tab-pane" aria-selected="false">Adjuntos <span id="contador_adjuntos"></span></button>
          </li>


        </ul>


        <!-- TABS PANEL  ADJUNTOS AQUI -->
        <div class="tab-adjuntos fade" id="adjuntos-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
          <!-- EL LISTADO DE ADJUNTOS-->
          <div class="tab-content" id="pills-adjuntos">

          </div>

        </div>



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
                <?php include_once(ENLACE_SERVIDOR . "mod_crm/ajax/listado_actividades.ajax.php");

                ?>
              </tbody>
            </table>


          </div>

          <div class="tab-pane fade" id="detalle-tab-pane" role="tabpanel" aria-labelledby="detalle-tab" tabindex="0">
            <?php
            include_once(ENLACE_SERVIDOR . "mod_crm/tpl/documento_linea.php");
            ?>
          </div>


        </div>

        <div class="row mt-3">
          <div class="col-md-12   botonera-footer">
            <a href="<?php echo ENLACE_WEB; ?>oportunidades" class="btn btn-outline-primary"> Volver Al Listado </a>

            <button type="button" onclick="eliminar_oportunidad(event)" class="btn btn-danger">
              <i class="fa fa-fw fa-trash"></i>
              Eliminar
            </button>
            

            <a href="<?php echo ENLACE_WEB; ?>modificar_oportunidad/<?php echo $Documento->id; ?>" class="btn btn-primary _effect--ripple waves-effect waves-light">Modificar Oportunidad</a>






          </div>
        </div>



      </div>



    </div>



  </div>


</div>

</div>


</div>


<!-- Include jQuery Roadmap Plugin -->
<script src="<?= ENLACE_WEB ?>bootstrap/jquery.roadmap.js" type="text/javascript"></script>
<link rel="stylesheet" href="<?= ENLACE_WEB ?>bootstrap/plugins/roadmap.css">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">


<?php



/*********************************************************
 * 
 *        Funcion para el manejo Base Javascript 
 *   
 * 
 * 
 * 
 * 
 *******************************************************************/ ?>
<script>
  $(document).ready(function() {

    // Desactivar todos los elementos del menú
    $(".menu").removeClass('active');
    $(".mod_crm").addClass('active');
    $(".mod_crm > .submenu").addClass('show');

    roadMap();

    paginarTareas(0);

    // Obtén la fecha de hoy en formato YYYY-MM-DD
    var today = new Date().toISOString().split('T')[0];

    // Asigna la fecha de hoy al atributo min del input
    document.getElementById('vencimiento_fecha').setAttribute('min', today);

    cargar_configuracion_cliente()

    cargar_boton_oportunidad_presupuesto('<?php echo $Documento->total; ?>')

  });

  function cargar_boton_oportunidad_presupuesto(total_oportunidad = 0){
    if( parseInt(total_oportunidad) >0){
      const html = `
      <button id="btnOportunidadPresupuesto" onclick="oportunidad_presupuesto()" type="button" class="btn btn-primary _effect--ripple waves-effect waves-light">
            Convertir a Presupuesto
      </button>
      `
      $('.botonera-footer').append(html);
    }else{
      $('#btnOportunidadPresupuesto').remove();
    }
  }


  function generar_cotizacion() {

    $("#loading").show(0);

    items_servicios = parseInt($(".boton-servicio").length);
    if (items_servicios <= 0) {
      $("#loading").hide(0);
      add_notification({
        text: 'Debe tener al menos 1 servicio para generar la cotizacion',
        pos: 'top-right',
        actionTextColor: '#fff',
        backgroundColor: '#E6515A',
      });
      return false;
    }

    // guardar una actividad
    $.ajax({
      method: "POST",
      url: "<?= ENLACE_WEB ?>mod_crm/json/crear.json.php",
      beforeSend: function(xhr) {},
      data: {
        action: 'crear',
        fk_oportunidad: <?= $Documento->id ?>,
        fk_moneda: '<?php echo $Documento->fk_moneda; ?>'
      },
    }).done(function(data) {

      $("#loading").hide(0);
      add_notification({
        text: 'Cotización Generada',
        pos: 'top-right',
        actionTextColor: '#fff',
        backgroundColor: '#00ab55',
      });


      roadMap();

      $(".listado_cotizaciones_ajax").html(data);
      console.log(data);
      $(window).scrollTop(5000);

    });



  }

  function eliminar_oportunidad(event) {
    event.preventDefault();
    let rowid = $('[name="fiche"]').val(); // Asegúrate de que este campo exista en tu formulario
    let message = "¿Deseas eliminar esta oportunidad?";
    let actionText = "Confirmar";
    // Mostrar el snackbar y definir el callback para el botón de acción
    add_notification({
      text: message,
      width: 'auto',
      duration: 30000,
      actionText: actionText,
      dismissText: 'Cerrar',
      onActionClick: function(element) {
        $.ajax({
          method: "POST",
          url: "<?php echo ENLACE_WEB; ?>mod_crm/class/class.php",
          data: {
            action: 'eliminar_oportunidad',
            rowid: rowid
          },
          cache: false,
        }).done(function(msg) {
          const response = JSON.parse(msg);
          if (response.error == 1) {
            add_notification({
              text: response.error_txt,
              pos: 'top-right',
              actionTextColor: '#fff',
              backgroundColor: '#e7515a'
            });
          } else {
            add_notification({
              text: 'Oportunidad eliminado exitosamente!',
              actionTextColor: '#fff',
              backgroundColor: '#00ab55',
              dismissText: 'Cerrar'
            });
            setTimeout(() => {
              window.location.href = "<?= ENLACE_WEB ?>oportunidades";
            }, 3000);
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

  function roadMap() {
    $.getJSON('<?= ENLACE_WEB ?>/mod_crm/ajax/timeline.ajax.php?term=<?= $Documento->id ?>', function(data) {
      //data is the JSON string

      console.log(data.length);
      $("#cantidadActividades").text(data.length);
      var the_json = data;



      var events = the_json;
      console.log(events);

      $('#my-roadmap').roadmap(events, {
        eventsPerSlide: 10,
        slide: 1,
        orientation: 'auto',
        prevArrow: '<i class="material-icons">keyboard_arrow_left</i>',
        nextArrow: '<i class="material-icons">keyboard_arrow_right</i>',
        eventTemplate: '<div class="event">' + '<div class="event__date">####DATE###</div>' + '<div class="event__content">####ESTATUS###</div>' + '<div style="cursor:pointer" fi_cotizacion="" id="target_####ROWID####"    class="event__content"><a href="####FK_COTIZACION###">####CONTENT###</a></div>' + '<div class="event__content actividy_####ROWID_A###"> </div>' + '</div>',
        onBuild: function() {
          console.log('onBuild event')
        }

      });
    });

  }

  function cargar_configuracion_cliente() {
    $.getJSON("<?php echo ENLACE_WEB; ?>mod_terceros/json/terceros_client.json.php?cliente=<?php echo $Documento->fk_tercero ?>")
      .done(function(data) {
        //setear valor en checkboxs
        $("#aplicar_descuento").prop('checked', false);
        $("#aplicar_irp").prop('checked', false);
        $("#aplicar_RE").prop('checked', false);
        if (data.aplicar_descuento_por_articulo == 1) {
          $("#aplicar_descuento").prop('checked', true);
        }
        if (data.impuesto_cliente_aplica_recargo_equivalencia == 1) {
          $("#aplicar_RE").prop('checked', true);
        }
        if (data.impuesto_cliente_lleva_retencion == 1) {
          $("#aplicar_irp").prop('checked', true);
        }

        $("#aplicar_descuento").prop('checked', false);
        $("#aplicar_irp").prop('checked', false);
        $("#aplicar_RE").prop('checked', false);

        if (data.impuesto_cliente_aplica_recargo_equivalencia == 1 || data.impuesto_cliente_aplica_recargo_equivalencia == 'true') {
          $("#aplicar_RE").prop('checked', true);
        }

        if (data.impuesto_cliente_lleva_retencion == 1 || data.impuesto_cliente_lleva_retencion == 'true') {
          $("#aplicar_irp").prop('checked', true);
        }

        aplicar_descuento();
        aplicar_irp();
        aplicar_RE();
        ajustar_referencia();
      });

    //setear valor en checkboxs
  }

  function aplicar_irp() {
    const irps_en_fila = $("td[id^='item_retencion']").text()
    const contiene_valores = dejar_solo_numero(irps_en_fila)

    $("#tabla_facturacion").addClass("borroso");
    if (contiene_valores == '') {
      $(".columnas_retencion").fadeOut();
    }
    if ($('#aplicar_irp').is(':checked')) {
      $(".columnas_retencion_1").fadeIn();
    } else {
      $(".columnas_retencion_1").fadeOut();
    }
    $("#tabla_facturacion").removeClass("borroso");
    $('#_retencion').removeAttr('checked')
    if ($('#aplicar_irp').is(':checked')) {
      $('#_retencion').attr('checked', 'checked')
    }
  }

  function aplicar_RE() {
    const res_en_fila = $("td[id^='item_equivalencia']").text()
    const contiene_valores = dejar_solo_numero(res_en_fila)

    $("#tabla_facturacion").addClass("borroso");
    if (contiene_valores == '') {
      $(".columnas_equivalencia").fadeOut();
    }
    if ($('#aplicar_RE').is(':checked')) {
      $(".columnas_equivalencia_1").fadeIn();
    } else {
      $(".columnas_equivalencia_1").fadeOut();
    }
    $("#tabla_facturacion").removeClass("borroso");
    $('#_recargo_equivalencia').removeAttr('checked')
    if ($('#aplicar_RE').is(':checked')) {
      $('#_recargo_equivalencia').attr('checked', 'checked')
    }
  }

  function aplicar_descuento() {
    const descuentos_en_fila = $("label[id^='label_descuento']").text()
    const contiene_valores = dejar_solo_numero(descuentos_en_fila)

    $("#tabla_facturacion").addClass("borroso");
    if (contiene_valores == '') {
      $(".columnas_descuento").fadeOut();
    }
    if ($('#aplicar_descuento').is(':checked')) {
      $(".columnas_descuento_1").fadeIn();
    } else {
      $(".columnas_descuento_1").fadeOut();
    }
    $("#tabla_facturacion").removeClass("borroso");
  }

  function ajustar_referencia() {
    let num_columns_left = 0
    if (!$('#aplicar_descuento').is(':checked')) {
      num_columns_left++
    }
    if (!$('#aplicar_irp').is(':checked')) {
      num_columns_left++
    }
    if (!$('#aplicar_RE').is(':checked')) {
      num_columns_left++
    }
    $('#columna_referencia').attr('colspan', 7 - num_columns_left);
  }

  function muestra_opciones_descuento() {
    if ($("#muestra_opciones").css('display') == 'none') {
      $("#muestra_opciones").show();
    } else {
      $("#muestra_opciones").fadeOut(200);
    }
  }
</script>


<?php
/*********************************************************
 * 
 *    Funciones para manejo de Tareas
 * 
 * 
 *******************************************************************/ ?>

<script>
  function actualizarOportunidad() {

    // actualizar una oportunidad
    $.ajax({
      method: "POST",
      url: "<?= ENLACE_WEB ?>mod_crm/class/class.php",
      beforeSend: function(xhr) {},
      data: {
        action: 'actualizarOportunidad',

      },
    }).done(function(data) {


    });



  }
  
  function actualizarTarea(int) {
    // Guardar una actividad
    $.ajax({
        method: "POST",
        url: "<?= ENLACE_WEB ?>mod_crm/class/class.php",
        beforeSend: function(xhr) {},
        data: {
            action: 'actualizarTarea',
            rowid: int,
            fk_estado: $("#edit_estado_tarea").val(),
            fecha_cierre: $("#edit_fecha_cierre").val(),
            comentario: $("#edit_comentario_tarea").val(),
            comentario_cierre: $("#edit_comentario_cierre_tarea").val(),
        },
    }).done(function(response) {
        try {
            response = JSON.parse(response); // Convertimos la respuesta en JSON
        } catch (e) {
            console.error("Error al parsear JSON:", response);
            add_notification({
                text: "Error inesperado en el servidor.",
                pos: 'top-right',
                actionTextColor: '#fff',
                backgroundColor: '#e7515a'
            });
            return;
        }

        // Si hubo un error (exito = 0)
        if (response.exito == 0) {
            add_notification({
                text: response.mensaje, // Muestra el mensaje de error del servidor
                pos: 'top-right',
                actionTextColor: '#fff',
                backgroundColor: '#e7515a'
            });
        } else {
            // Si la tarea se actualiza con éxito
            add_notification({
                text: 'Actividad actualizada exitosamente!',
                actionTextColor: '#fff',
                backgroundColor: '#00ab55',
                actionText: 'Cerrar'
            });

            // Cerrar el modal y actualizar la lista de tareas
            $("#modal_editar_tarea").modal('hide');
            paginarTareas(0);
        }
    }).fail(function(jqXHR, textStatus, errorThrown) {
        // Manejo de errores en la petición AJAX
        console.error("Error AJAX:", textStatus, errorThrown);
        add_notification({
            text: "Error en la conexión con el servidor.",
            pos: 'top-right',
            actionTextColor: '#fff',
            backgroundColor: '#e7515a'
        });
    });
}


function guardarTarea() {
    $("#vencimiento_fecha").removeClass('input_error');

    if ($("#vencimiento_fecha").val() == '') {
        $("#vencimiento_fecha").addClass('input_error');
        return false;
    }

    // Guardar una actividad
    $.ajax({
        method: "POST",
        url: "<?= ENLACE_WEB ?>mod_crm/class/class.php",
        beforeSend: function(xhr) {},
       
        data: {
            action: 'guardarTarea',
            fk_oportunidad: <?= $Documento->id?>,
            fk_diccionario_actividad: $("#fk_diccionario_actividad").val(),
            vencimiento_fecha: $("#vencimiento_fecha").val(),
            fk_usuario_asignado: $("#fk_usuario_asignado").val(),
            comentario: $("#comentario").val(),
        },
    }).done(function(response) {
        try {
            response = JSON.parse(response); // Convertimos la respuesta en JSON
        } catch (e) {
            console.error("Error al parsear JSON:", response);
            add_notification({
                text: "Error inesperado en el servidor.",
                pos: 'top-right',
                actionTextColor: '#fff',
                backgroundColor: '#e7515a'
            });
            return;
        }

        // Si hubo un error (exito = 0)
        if (response.exito == 0) {
            add_notification({
                text: response.mensaje, // Muestra el mensaje de error del servidor
                pos: 'top-right',
                actionTextColor: '#fff',
                backgroundColor: '#e7515a'
            });
        } else {
            // Si la tarea se guarda con éxito
            add_notification({
                text: 'Tarea guardada exitosamente!',
                actionTextColor: '#fff',
                backgroundColor: '#00ab55',
                actionText: 'Cerrar'
            });

            // Limpiar los campos después de guardar
            $("#vencimiento_fecha").val("").attr("value", "");
            $("#comentario").val("").attr("value", "");

            // Cerrar el modal y actualizar la lista de tareas
            $("#taskModal").modal('hide');
            paginarTareas(0);
            roadMap();
        }
    }).fail(function(jqXHR, textStatus, errorThrown) {
        // Manejo de errores en la petición AJAX
        console.error("Error AJAX:", textStatus, errorThrown);
        add_notification({
            text: "Error en la conexión con el servidor.",
            pos: 'top-right',
            actionTextColor: '#fff',
            backgroundColor: '#e7515a'
        });
    });
}



  function paginarTareas(int) {

    // traer tpl
    $.ajax({
      method: "POST",
      url: "<?= ENLACE_WEB ?>mod_crm/ajax/listado_actividades.ajax.php?id=<?= $Documento->id ?>",
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
      url: "<?= ENLACE_WEB ?>mod_crm/ajax/editarTarea.php",
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


  $(document).on('change', '#edit_estado_tarea', function() {
    console.log($(this).val())
    if ($(this).val() != 3) {
      $('.div_cierre').attr('style', 'display:none !important');

    } else {
      $('.div_cierre').removeAttr('style');
    }
  })
</script>


<!------------  ------------------------------------------->
<!------------  ------------------------------------------->
<!------------  ------------------------------------------->
<!---------     Javascript de los Servicios  -------------->
<!------------  ------------------------------------------->
<!------------  ------------------------------------------->
<!------------  ------------------------------------------->

<script type="text/javascript">
  function tab_imagenes() {
    $.ajax({
      method: "POST",
      url: "<?php echo  "" . ENLACE_WEB ?>mod_crm/ajax/oportunidades.adjuntos.ajax.php",
      data: {
        // pagina: int,
        fiche: '<?php echo $_GET["id"]; ?>',
      },
    }).done(function(result) {
      // console.log(result)
      $("#pills-adjuntos").html('');
      $("#pills-adjuntos").html(result);
    });
  }
  tab_imagenes();


  function subirArchivo(event) {
    event.preventDefault();

    // Recoger los valores del formulario usando jQuery
    const formData = new FormData($('#MyUploadForm')[0]);

    // Añadir los parámetros adicionales
    formData.append('tipo', 'oportunidad');
    formData.append('fk_documento', "<?php echo $_GET['id']; ?>");

    // Preparar la petición AJAX
    $.ajax({
      method: "POST",
      url: "<?php echo ENLACE_WEB; ?>mod_adjuntos/ajax/subir.adjuntos.ajax.php",
      data: formData,
      cache: false,
      contentType: false,
      processData: false,
    }).done(function(msg) {
      console.log(msg);
      const response = JSON.parse(msg);

      if (response.error == 1) {
        add_notification({
          text: response.datos,
          pos: 'top-right',
          actionTextColor: '#fff',
          backgroundColor: '#e7515a'
        });
      } else {
        add_notification({
          text: 'Archivo subido exitosamente!',
          actionTextColor: '#fff',
          backgroundColor: '#00ab55',
          ActionText: 'Cerrar'
        });

        tab_imagenes();
      }

      // Aquí puedes añadir código para actualizar la interfaz de usuario si es necesario
    }).fail(function(jqXHR, textStatus, errorThrown) {
      console.error("Error en la petición AJAX:", textStatus, errorThrown);
      add_notification({
        text: 'Hubo un error al subir el archivo.',
        actionTextColor: '#fff',
        backgroundColor: '#e7515a'
      });
    });
  }

  function eliminar_imagen(x, label) {
    let fk_documento = '<?php echo $_GET["id"]; ?>';
    var message = "Deseas eliminar este adjunto?";
    var actionText = "Confirmar";

    // Mostrar el snackbar y definir el callback para el botón de acción
    add_notification({
      text: message,
      width: 'auto',
      duration: 30000,
      actionText: actionText,
      dismissText: 'Cerrar',
      onActionClick: function(element) {

        $.ajax({
          method: "POST",
          url: "<?php echo ENLACE_WEB; ?>mod_adjuntos/class/class.php",
          data: {
            action: 'BorrarAdjunto',
            id: x,
            fk_documento: fk_documento,
            label: label,
            tipo_documento: 'oportunidad',

          },
          cache: false,
          //contentType: false,
          //processData: false,
        }).done(function(msg) {
          console.log(msg);
          // const response = JSON.parse(msg);
          if (msg.error == 1) {

            add_notification({
              text: msg.datos,
              pos: 'top-right',
              actionTextColor: '#fff',
              backgroundColor: '#e7515a'
            });

          } else {

            add_notification({
              text: 'Imagen eliminada exitosamente!',
              actionTextColor: '#fff',
              backgroundColor: '#00ab55',
              dismissText: 'Cerrar'
            })


            tab_imagenes();
          }

          // Aquí puedes añadir código para actualizar la interfaz de usuario si es necesario
        }).fail(function(jqXHR, textStatus, errorThrown) {
          console.error("Error en la petición AJAX:", textStatus, errorThrown);

          add_notification({
            text: 'Hubo un error al eliminar el archivo.',
            actionTextColor: '#fff',
            backgroundColor: '#e7515a'
          })

        });


      },
    });

  }
</script>



<script>
  $(function() {

    $("#servicio_descripcion").autocomplete({
      source: function(request, response) {

        $.ajax({
          url: "<?php echo ENLACE_WEB; ?>mod_productos/json/productos.json.php?codigo_moneda_oportunidad=<?php echo $Documento->moneda_codigo; ?>&tipo=servicios&tipo_cambio_oportunidad=<?php echo $Documento->tipo_cambio; ?>&cliente=",
          dataType: "json",
          data: {
            term: request.term
          },
          success: function(data) {
            console.log(data);
            response(data);
          },
          error: function(jqXHR, textStatus, errorThrown) {
            console.error("Error en la petición AJAX:", textStatus, errorThrown);
            // Aquí puedes manejar los errores, como mostrar un mensaje al usuario
            if (jqXHR.status === 404) {
              alert("No se encontró el recurso (404).");
            } else {
              alert("Ocurrió un error al realizar la solicitud: " + textStatus);
            }
          }
        });
      },
      minLength: 2,
      select: function(event, ui) {
        console.log(ui);


        $("#servicio_descripcion").val(ui.item.value);
        $("#servicio_precio_unitario").val(ui.item.subtotal);
        //si hubo un cambio
        if (ui.item.hubo_cambio_valor === 'si') {
          $(".precio_real_small").remove();
          $("#servicio_precio_unitario").after("<small class='precio_real_small' style='color:red;'>Monto Real: " + ui.item.precio_original + ui.item.moneda_simbolo + "</small>");
        }
        $("#precio_real").remove();
        $("#servicio_precio_unitario").after("<input type='hidden' name='precio_real' id='precio_real' value='" + ui.item.precio_original + "'>");


        $("#servicio_cantidad").val(1);

        $("#servicio_precio_tipo_impuesto").find('option').each(function() {

          impuesto = parseInt($(this).val());
          impuesto_autocomplete = parseInt(ui.item.impuesto);

          if (impuesto === impuesto_autocomplete) {
            $(this).attr("selected", true);
          }

        });

        //$("#servicio_precio_tipo_impuesto").val(ui.item.impuesto);

        $('#servicios_imagenes').html("");
        $.each(ui.item.imagenes, function(index, imagen) {
          console.log(imagen);
          if (imagen) { // Verifica si la variable 'imagen' tiene valor
            var img = $('<img style="max-width:100px;">').attr('src', '<?php echo ENLACE_WEB; ?>' + 'servir_imagenes_productos?img=' + imagen).attr('alt', 'Imagen ' + (index + 1));
            $('#servicios_imagenes').append(img);
          }

        });

        $("#servicio_fk_producto").val(ui.item.id);
        $(".validacion_servicio").show(0);
        $(".guardar-tarea").show(0);

      }
    });

    //servicio
    $("#servicio_descripcion").keyup(function() {
      $("#servicio_fk_producto").val("").attr("value", "");
      $(".validacion_servicio").hide(0);
      $(".guardar-tarea").hide(0);
    });


  });



  //function para actualizar el servicio
  function actualizar_servicio() {

    error = false;
    $("#servicio_descripcion_update").removeClass('input_error');
    $("#servicio_cantidad_update").removeClass('input_error');
    $("#servicio_precio_unitario_update").removeClass('input_error');
    $("#servicio_precio_tipo_impuesto_update").removeClass('input_error');

    if ($("#servicio_descripcion_update").val() == '') {
      $("#servicio_descripcion_update").addClass('input_error');
      error = true;
    }

    if ($("#servicio_cantidad_update").val() == '') {
      $("#servicio_cantidad_update").addClass('input_error');
      error = true;
    }

    if ($("#servicio_precio_unitario_update").val() == '') {
      $("#servicio_precio_unitario_update").addClass('input_error');
      error = true;
    }


    if ($("#monto_descuento_editar").val() === '') {
      $("#monto_descuento_editar").addClass('input_error');
      error = true;
    }


    if (error) {
      return false;
    }


    tipo_descuento = $("#tipo_descuento_editar").val();
    monto_descuento = $("#monto_descuento_editar").val();

    //Validacion de elementos tipo de descuento
    if (tipo_descuento === 'porcentual' && parseFloat(monto_descuento) > 100) {
      add_notification({
        text: 'El monto no debe ser mayor a 100%',
        actionTextColor: '#fff',
        backgroundColor: '#E7515A',
        dismissText: 'Cerrar'
      });
      return false;
    }
    if (tipo_descuento === 'absoluto') {
      servicio_precio_unitario_update = parseFloat($("#servicio_precio_unitario_update").val());
      //Si es mayor 
      if (parseFloat(monto_descuento) > servicio_precio_unitario_update) {

        add_notification({
          text: 'El monto de descuento debe ser menor o igual al costo del servicio',
          actionTextColor: '#fff',
          backgroundColor: '#E7515A',
          dismissText: 'Cerrar'
        });

        return false;
      }
    }



    // guardar una actividad
    $.ajax({
      method: "POST",
      url: "<?= ENLACE_WEB ?>mod_crm/class/class.php",
      beforeSend: function(xhr) {},
      data: {
        action: 'actualizar_servicio',
        cotizacion_id: <?= $Documento->id ?>,
        servicio_precio_tipo_impuesto: $("#servicio_precio_tipo_impuesto_update").val(),
        servicio_precio_unitario: $("#servicio_precio_unitario_update").val(),
        servicio_cantidad: $("#servicio_cantidad_update").val(),
        servicio_comentario: $("#servicio_comentario_update").val(),
        servicio_fk_producto: $("#servicio_fk_producto_update").val(),
        tipo_descuento: tipo_descuento,
        monto_descuento: monto_descuento,
        rowid: $("#servicio_fk_row_id_update").val(),
      },
    }).done(function(data) {

      data = JSON.parse(data);
      console.log("Resultado de guardar_servicio()  ");
      console.log(data);

      add_notification({
        text: data.respuesta,
        actionTextColor: '#fff',
        backgroundColor: '#00ab55',
        dismissText: 'Cerrar'
      });




      importe_dolarizado_actual = data.importe_dolarizado_actual;
      importe_real = data.importe;
      //vamos a actualizar en tiempo REAL el importe dolarizado actual
      $("#importe_dolarizado_refresh").text(importe_dolarizado_actual);
      $("#importe_real_refresh").text(importe_real);


      $("#modal_editar_servicio").modal('hide');



    });

  }


  //Funcion para guardar servicio
  function guardar_servicio() {

    error = false;

    $("#servicio_descripcion").removeClass('input_error');
    $("#servicio_cantidad").removeClass('input_error');
    $("#servicio_precio_unitario").removeClass('input_error');
    $("#servicio_precio_tipo_impuesto").removeClass('input_error');

    if ($("#servicio_descripcion").val() == '') {
      $("#servicio_descripcion").addClass('input_error');
      error = true;
    }

    if ($("#servicio_cantidad").val() == '') {
      $("#servicio_cantidad").addClass('input_error');
      error = true;
    }

    if ($("#servicio_precio_unitario").val() == '') {
      $("#servicio_precio_unitario").addClass('input_error');
      error = true;
    }

    if ($("#monto_descuento").val() === '') {
      $("#monto_descuento").addClass('input_error');
      error = true;
    }



    precio_real = $("#precio_real").val();
    tipo_descuento = $("#tipo_descuento").val();
    monto_descuento = $("#monto_descuento").val();

    //Validacion de elementos tipo de descuento
    if (tipo_descuento === 'porcentual' && parseFloat(monto_descuento) > 100) {
      add_notification({
        text: 'El monto no debe ser mayor a 100%',
        actionTextColor: '#fff',
        backgroundColor: '#E7515A',
        dismissText: 'Cerrar'
      });
      return false;
    }
    if (tipo_descuento === 'absoluto') {
      servicio_precio_unitario_update = parseFloat($("#servicio_precio_unitario").val());
      //Si es mayor 
      if (parseFloat(monto_descuento) > servicio_precio_unitario_update) {

        add_notification({
          text: 'El monto de descuento debe ser menor o igual al costo del servicio',
          actionTextColor: '#fff',
          backgroundColor: '#E7515A',
          dismissText: 'Cerrar'
        });

        return false;
      }
    }


    if ($("#servicio_fk_producto").val() === '') {
      add_notification({
        text: 'Debe seleccionar un producto Valido',
        actionTextColor: '#fff',
        backgroundColor: '#E7515A',
        dismissText: 'Cerrar'
      });

      return false;
    }


    if (error) {
      return false;
    }

    // guardar una actividad
    $.ajax({
      method: "POST",
      url: "<?= ENLACE_WEB ?>mod_crm/class/class.php",
      beforeSend: function(xhr) {},
      data: {
        action: 'guardar_servicio',
        cotizacion_id: <?= $Documento->id ?>,
        servicio_precio_tipo_impuesto: parseInt($("#servicio_precio_tipo_impuesto").val()),
        servicio_precio_unitario: $("#servicio_precio_unitario").val(),
        servicio_cantidad: $("#servicio_cantidad").val(),
        servicio_comentario: $("#servicio_comentario").val(),
        servicio_fk_producto: $("#servicio_fk_producto").val(),
        tipo_descuento: tipo_descuento,
        monto_descuento: monto_descuento,
        precio_real: precio_real,
        codigo_moneda: '<?php echo $Documento->moneda_codigo; ?>'


      },
    }).done(function(data) {

      console.log(data);

      data = JSON.parse(data);
      console.log("Resultado de guardar_servicio()  ");
      console.log(data);

      add_notification({
        text: data.respuesta,
        actionTextColor: '#fff',
        backgroundColor: '#00ab55',
        dismissText: 'Cerrar'
      });


      importe_dolarizado_actual = data.importe_dolarizado_actual;
      importe_real = data.importe;
      //vamos a actualizar en tiempo REAL el importe dolarizado actual
      $("#importe_dolarizado_refresh").text(importe_dolarizado_actual);
      $("#importe_real_refresh").text(importe_real);

      $("#serviceModal").modal('hide');

    });
  }




  function editarServicio(int) {

    // traer tpl
    $.ajax({
      method: "POST",
      url: "<?= ENLACE_WEB ?>mod_crm/ajax/editarServicio.php",
      beforeSend: function(xhr) {},
      data: {
        action: 'editarServicio',
        rowid: int,
        fk_cotizacion: '<?= $Documento->id ?>',

      },
    }).done(function(data) {

      console.log(data);
      //pintar tpl en modal y mostrarlo
      $("#modal_editar_servicio").html(data);
      $("#modal_editar_servicio").modal('show');

    });

  }



  //funcion para eliminar servicio
  function eliminarServicio(rowid) {

    result = confirm("¿Deseas remover el servicio?");
    if (result) {
      // guardar una actividad
      $.ajax({
        method: "POST",
        url: "<?= ENLACE_WEB ?>mod_crm/class/class.php",
        beforeSend: function(xhr) {},
        data: {
          action: 'remover_servicio',
          oportunidad_id: <?= $Documento->id ?>,
          rowid: rowid,
        },
      }).done(function(data) {
        data = JSON.parse(data);
        console.log("Resultado de guardar_servicio()  ");
        console.log(data);
        add_notification({
          text: data.respuesta,
          actionTextColor: '#fff',
          backgroundColor: '#00ab55',
          dismissText: 'Cerrar'
        });

        importe_dolarizado_actual = data.importe_dolarizado_actual;
        importe_real = data.importe;
        //vamos a actualizar en tiempo REAL el importe dolarizado actual
        $("#importe_dolarizado_refresh").text(importe_dolarizado_actual);
        $("#importe_real_refresh").text(importe_real);


        $("#modal_editar_servicio").modal('hide');
      });
    }

  }
</script>






<!------------  ------------------------------------------->
<!------------  ------------------------------------------->
<!------------  ------------------------------------------->
<!---------     Javascript de los select 2----------------->
<!------------  ------------------------------------------->
<!------------  ------------------------------------------->
<!------------  ------------------------------------------->

<?php
$coma = "";
foreach (explode(",", $Documento->tags) as $array) {
  $tags_en_formato .= $coma . '"' . $array . '"';
  $coma = ",";
}

?>

<script>
  $(document).ready(function() {


    var listaDeOpcionesTags = [<?php echo $tags_en_formato; ?>];


    $('#tags').select2();
    $('#tags').val(listaDeOpcionesTags); // Establece las opciones seleccionadas
    $('#tags').trigger('change'); // Notifica a Select2 para actualizar el estado
    // Inicializa Select2 tags
    $('#tags').select2({
      tags: true, // Habilitar la entrada de texto
      tokenSeparators: [',', ' '] // Separadores para nuevas etiquetas
    });

    $('#tags').prop("disabled", true);


    $(".solonumerodecimal").blur(function() {
      if ($(this).val() === '') {
        $(this).val(0);
      }
    });

    $(".solonumerodecimal").on("input", function() {
      var value = $(this).val();
      // Eliminar cualquier carácter que no sea un dígito o un punto
      value = value.replace(/[^0-9.]/g, '');
      // Asegurarse de que solo haya un punto
      if (value.split('.').length > 2) {
        value = value.replace(/\.+$/, "");
      }
      $(this).val(value);
    });

    // Evitar el espacio en blanco
    $(".solonumerodecimal").on("keydown", function(e) {
      if (e.key === " ") {
        e.preventDefault();
      }
    });



  });
</script>
<div class="modal fade" id="modal_editar_tarea" tabindex="-1" role="dialog" aria-labelledby="taskTitle" aria-hidden="true">

</div>

<!-- Modal tasks-->
<div class="modal fade" id="taskModal" tabindex="-1" aria-labelledby="taskModalTitle" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="taskModalTitle">Nueva tarea</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <div class="form-row">
            <div class="form-group col-md-12">
              <label for="actividad">Tipo actividad:</label>
              <select id="fk_diccionario_actividad" name="fk_diccionario_actividad" class="form-control">
                <?php
                foreach ($Documento->diccionarioActividades() as $item) {
                  echo '<option value="' . $item->rowid . '">' . $item->nombre . '</option>';
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
                $usuarioSesion = $_SESSION['usuario']; // Obtiene el usuario en sesión
                foreach ($Documento->usuarios_disponibles() as $item) {
                  $selected = ($item->rowid == $usuarioSesion) ? "selected" : "";
                  echo '<option value="' . $item->rowid . '" ' . $selected . '>' . $item->nombre . ' ' . $item->apellidos . '</option>';
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
        <button class="cancelar_tarea btn btn-light-dark _effect--ripple waves-effect waves-light" data-bs-dismiss="modal">Cancelar</button>
        <button onclick="guardarTarea();" type="button" class="btn btn-primary _effect--ripple waves-effect waves-light">Guardar</button>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function () {
      // Obtener la fecha de hoy en formato YYYY-MM-DD
      var today = new Date().toISOString().split('T')[0];
      document.getElementById("vencimiento_fecha").value = today;
  });
</script>









<!-- Modal services-->
<div class="modal fade" id="serviceModal" tabindex="-1" aria-labelledby="serviceModalTitle" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="serviceModalTitle">Nuevo servicio</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        </button>
      </div>
      <div class="modal-body">

        <div class="form-group">


          <div class="form-row">
            <div class="form-group col-md-12">
              <label for="actividad">Servicio:</label>
              <input autocomplete="off" class="form-control ui-autocomplete-input" id="servicio_descripcion" name="servicio_descripcion" class="form-control">
            </div>
          </div>

          <div class="validacion_servicio" style="display:none;">
            <div class="row mt-2">
              <div class="col-md-4">
                <label for="">Cant:</label>
                <input type="input" name="servicio_cantidad" id="servicio_cantidad" class="form-control">
              </div>

              <div class="col-md-4">
                <label for="">Precio Unitario:</label>
                <input type="input" name="servicio_precio_unitario" id="servicio_precio_unitario" class="form-control">
              </div>

              <div class="col-md-4">
                <label for="">Impuestos:</label>
                <select name="servicio_precio_tipo_impuesto" id="servicio_precio_tipo_impuesto" class="form-control">
                  <option value="">Seleccione</option>
                  <?php
                  foreach ($listado_impuestos as $key => $value) {
                  ?>
                    <option value="<?php echo $listado_impuestos[$key]["impuesto"]; ?>"><?php echo $listado_impuestos[$key]["impuesto_texto"]; ?></option>
                  <?php
                  }
                  ?>
                </select>
              </div>
            </div>


            <div class="form-group col-md-12 mt-2">

              <!-- DESCUENTOS-->
              <div class="row mt-2">
                <div class="col-md-6">
                  <label for="">Tipo de descuento:</label>
                  <br>
                  <select name="tipo_descuento" id="tipo_descuento" class="form-control">
                    <option value="absoluto">Neto</option>
                    <option value="porcentual">%</option>
                  </select>
                </div>

                <div class="col-md-6">
                  <label for="">Monto Descuento:</label>
                  <input type="input" name="monto_descuento" id="monto_descuento" class="form-control solonumerodecimal" value="0">
                </div>

              </div>
            </div>

            <div class="form-group col-md-12 mt-2">
              <label for="">Comentario:</label>
              <textarea class="form-control" name="servicio_comentario" id="servicio_comentario" cols="30" rows="5"></textarea>
            </div>
            <div class="form-group col-md-12 mt-2" id="servicios_imagenes">
            </div>
          </div><!--cierre validacion servicio-->

        </div>
      </div>


      <div class="modal-footer guardar-tarea" style="display: none;">
        <button class="btn btn-light-dark _effect--ripple waves-effect waves-light" data-bs-dismiss="modal">Cancelar</button>
        <button onclick="guardar_servicio();" type="button" class="btn btn-primary _effect--ripple waves-effect waves-light">Guardar</button>
      </div>
    </div>
  </div>
</div>



<div class="modal fade" id="modal_editar_tarea" tabindex="-1" role="dialog" aria-labelledby="taskTitle" aria-hidden="true">
</div>


<!-- MODAL PARA EDITR EL SERVICIIO-->

<!-- Modal -->
<div class="modal" id="modal_editar_servicio" tabindex="-1" role="dialog2" aria-labelledby="editarServicioLabel" aria-hidden="true">


</div>




<?php
//lo mandamos al tab y hacemos la animacion
if ($fiche_tarea != '') {
?>

  <style type="text/css">
    table#listing-table {
      width: 100%;
      border-collapse: collapse;
    }

    #listing-table tr td {
      border: 1px solid #ddd;
      padding: 8px;
      position: relative;
    }

    #listing-table tr td span {
      display: inline-block;
      position: relative;
    }

    #tarea_<?php echo $fiche_tarea; ?>td {
      background-color: #8997a4;
      color: white;
      transition: background-color 1s ease-in-out, color 1s ease-in-out;
      /* Añadido para animar el cambio de color */

    }

    #tarea_<?php echo $fiche_tarea; ?>td {
      border: 1px solid #ddd;
      padding: 8px;
      position: relative;
      overflow: hidden;
      /* Asegura que el texto no salga del td */
    }

    #tarea_<?php echo $fiche_tarea; ?>td span {
      display: inline-block;
      position: relative;
    }

    @keyframes moveText {
      0% {
        transform: translateX(0);
      }

      25% {
        transform: translateX(-30px);
      }

      50% {
        transform: translateX(30px);
      }

      75% {
        transform: translateX(-15px);
      }

      100% {
        transform: translateX(0);
      }
    }

    #tarea_<?php echo $fiche_tarea; ?>td span {
      animation: moveText 2s ease-in-out;
    }
  </style>

  <script type="text/javascript">
    jQuery(document).ready(function($) {

      // $(window).on("load", function() {
      $("#profile-tab").addClass("animacion_tab");
      $(".animacion_tab").click(function() {
        $("html, body").scrollTop(500);
        $(this).removeClass('animacion_tab');

        setTimeout(function() {
          $("#tarea_<?php echo $fiche_tarea; ?>").addClass("animacion_tr");
        }, 1000);


        $("#tarea_<?php echo $fiche_tarea; ?>").addClass("animacion_tr");
      });
      $(".animacion_tab").trigger("click");
    });

    // jQuery(document).ready(function($) {
    //   // $(window).on("load", function() {
    //   $("#profile-tab").addClass("animacion_tab");
    //   $("html, body").scrollTop(500);
    //   $(this).removeClass('animacion_tab');
    //   $("#tarea_<?php echo $fiche_tarea; ?>").addClass("animacion_tr");
    // });


    // jQuery(document).ready(function($) {
    //   setTimeout(function() {
    //     var tareaElement = $("#tarea_<?php echo $fiche_tarea; ?>");
    //     console.log('aplica estilo')
    //     tareaElement.css({
    //       "background-color": "yellow",
    //       "transform": "translateX(20px)",
    //       "opacity": "1",
    //       "transition": "all 0.5s ease-in-out, opacity 2s ease-in"
    //     });

    //     // Para asegurarte de que se note la transición de desaparecer
    //     setTimeout(function() {
    //       console.log('aplica estilo opacity')
    //       tareaElement.css({
    //         "opacity": "0"
    //       });
    //     }, 1000); // Después de 1 segundo, empieza a desvanecer
    //   }, 3000); // 3 segundos después de cargar la página, aplica el efecto
    // });
  </script>



<?php
}
?>