<!-- Include jQuery Roadmap Plugin -->
<script src="<?= ENLACE_WEB ?>bootstrap/jquery.roadmap.js" type="text/javascript"></script>
<link rel="stylesheet" href="<?= ENLACE_WEB ?>bootstrap/plugins/roadmap.css">

<?php

require_once ENLACE_SERVIDOR . 'mod_redhouse_cotizaciones/object/redhouse.cotizaciones.object.php';
require_once ENLACE_SERVIDOR . 'mod_redhouse_proyecto/object/proyecto_object.php';
require(ENLACE_SERVIDOR . 'mod_productos/object/productos.object.php');

$productos = new Productos($dbh);
$productos->entidad = $_SESSION['Entidad'];
$productos->Impuestos();
$Cotizacion = new redhouse_Cotizacion($dbh, $_SESSION['Entidad']);
$Cotizacion->fetch($_GET['fiche']);

//Vamos a ver si posee proyectos asociado la cotización
$Proyecto = new redhouse_proyecto($dbh,$_SESSION['Entidad']);
$data_proyecto_cotizacion = $Proyecto->buscar_cotizacion_proyecto($_GET['fiche']);


 //si no hay id, cerrar conexion
 if (!$Cotizacion->id > 0) {
  echo acceso_invalido();
       exit(1);
}

$accion = $Cotizacion->cotizacion_referencia;
 
$listado_adjuntos = $Cotizacion->obtener_adjuntos_cotizacion($_GET['fiche']);

$tipo_cambio = $Cotizacion->cotizacion_tipo_cambio;
$moneda_codigo = $Cotizacion->moneda_codigo;


?>

<style type="text/css">
  .tab-servicios.fade
  {
    display: none;
  }
  .tab-servicios.fade.active{
    display: block;
  }
  label.strong{
    font-weight: 600;
  }
  .tab-adjuntos.fade{
    display: none;
  }
  .tab-adjuntos.fade.active{
    display: block;
  }
</style>


<div class="page-meta">
  <nav class="breadcrumb-style-one" aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="#">Cotizaciones</a></li>
      <li class="breadcrumb-item active" aria-current="page"><?php echo $accion; ?></li>
    </ol>
  </nav>
</div>




<div class="row mt-3" style="">
  <div class="col-xs-12">
    <div class="card">

      <div class="card-body table-responsive no-padding">


        <div class="row">
          <div class="col-md-12">
            <div class="row">
              <div class="col-md-3">
                <h4> Rerefencia :</h4>
              </div>
              <div class="col-md-3">

                <label for="">
                  <stong><?php echo $Cotizacion->cotizacion_referencia; ?></strong>
                </label>
              </div>
            </div>


            <div class="row">
              <div class="col-md-3">
                <label for="">Fecha Cotizaci&oacute;n :</label>
              </div>
              <div class="col-md-3">

                <label for=""><?php echo date("d-m-Y", strtotime($Cotizacion->cotizacion_fecha)); ?></label>
              </div>

              <div class="col-md-3">
                TAGS :
              </div>
              <div class="col-md-3">
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

            </div>


            <div class="row">
              <div class="col-md-3">
                Cliente :
              </div>
              <div class="col-md-6">
                <label for=""><?php echo $Cotizacion->nombre_cliente; ?></label>
              </div>
            </div>  


            <div class="row">
              <div class="col-md-3">
                Contacto:
              </div>
              <div class="col-md-6">
                <label for=""><?php echo  $Cotizacion->contacto_txt; ?></label>
              </div>
            </div>

            <div class="row">
              <div class="col-md-3">
                Tipo :
              </div>
              <div class="col-md-6">
                <label for=""><?php echo (($Cotizacion->cotizacion_tipo_oferta == 1) ? "Normal" : "<img width='95px' src='https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT3eD7ab1N9VTbYj1_8jf6UqB9mhXdG3P5rYLdFOE5_&s'>Sicop o similar"); ?></label>
              </div>
            </div>

            <!--<div class="row">
              <div class="col-md-3">
                Categoria :
              </div>
              <div class="col-md-6">
                <span class="badge badge-<?php echo $Cotizacion->estilo_categoria; ?>"><?php echo returnSplitNameClient($Cotizacion->nombre_categoria_txt,25); ?></span>
              </div>
            </div>-->


            <div class="row mt-1">
              <div class="col-md-3">
                Moneda :
              </div>
              <div class="col-md-6">
               <?php echo $Cotizacion->moneda_txt; ?> 
              </div>
            </div>



            <div class="row mt-1">
              <div class="col-md-3">
                Validez de la Oferta :
              </div>
              <div class="col-md-6">
                <label for=""><?php echo ($Cotizacion->cotizacion_validez_oferta == 0) ? "Sin Validez definida" : $Cotizacion->cotizacion_validez_oferta . " dias naturales"; ?> </label>

              </div>
            </div>

            <div class="row">
              <div class="col-md-3">
                Tiempo de Entrega :
              </div>
              <div class="col-md-6">
                <label for=""><?php echo ($Cotizacion->cotizacion_tiempo_entrega == 0) ? "Sin tiempo de Entrega" : $Cotizacion->cotizacion_tiempo_entrega . " dias naturales"; ?> </label>
              </div>
            </div>


            <div class="row">
              <div class="col-md-3">
                Proyecto:
              </div>
              <div class="col-md-6">
                <label for=""><?php echo  $Cotizacion->cotizacion_proyecto; ?></label>
              </div>
            </div>

            <div class="row">
              <div class="col-md-3">
                Otro:
              </div>
              <div class="col-md-6">
                <label for=""><?php echo  $Cotizacion->cotizacion_descripcion_proyecto; ?></label>
              </div>
            </div>

          <div class="row">
              <div class="col-md-3">
                Lugar:
              </div>
              <div class="col-md-6">
                <label for=""><?php echo  $Cotizacion->cotizacion_lugar_proyecto; ?></label>
              </div>
            </div>


          <div class="row">
              <div class="col-md-3">
                Fecha:
              </div>
              <div class="col-md-6">
                <label for=""><?php echo  $Cotizacion->cotizacion_fecha_proyecto; ?></label>
              </div>
            </div>


            <div class="row">
              <div class="col-md-3">
                Notas :</div>

              <?php echo (!empty($Cotizacion->cotizacion_nota)) ? "<div class='col-md-12'>" . $Cotizacion->cotizacion_nota . "</div>" : "<div class='col-md-6'> Sin notas</div>"; ?>


            </div>



            <div class="row mt-3">
              <div class="col-md-3">
                Diseñadores Asignados :
              </div>
              <div class="col-md-6">
                <?php
                foreach ($Cotizacion->obtener_recurso_humano($Cotizacion->id)  as $valor) {  ?>
                  <span class="avatar-chip bg-primary mb-2 me-4 position-relative">
                    <img src="<?php echo $Usuarios->obtener_url_avatar_encriptada($valor['fk_usuario']); ?>" alt="<?php echo $valor['usuario_txt']." ".$valor['rowid']; ?>" width="96" height="96">
                    <span class="text"><?php echo $valor['usuario_txt']; ?></span>
                  </span>
                <?php  }                                                                  ?>


              </div>
            </div>



            <div class="row mt-4">
              <div class="col-md-12">
                <i class="fa fa-map"></i> Oferta creada el <?php echo date("d-m-Y H:i", strtotime($Cotizacion->creado_fecha)) . " por el usuario " . $Cotizacion->usuario_creador; ?>
              </div>
            </div>




            <div class="row mt-4">
              <div class="col-md-3">
                <i class="fa fa-user"></i> Agente :
              </div>
            
              <div class="col-md-3">
                <span class="avatar-chip bg-danger mb-2 me-4 position-relative">
                <!--  <img src="<?php echo $Usuarios->obtener_url_avatar_encriptada($Cotizacion->fk_usuario_asignado); ?>" alt="<?php echo $Cotizacion->fk_usuario_asignado; ?>" width="96" height="96">-->
                  <span class="text"><?php echo $Cotizacion->usuario_txt; ?></span>
                </span>




              </div>

              <div class="col-md-3"></div>
              <div class="col-md-3"></div>
            </div>



            <div class="row mt-3">
              <div class="col-md-3">
                <i class="fa fa-compass"></i> Status :
              </div>
              <div class="col-md-3">
                <span class="badge badge-<?php echo $Cotizacion->estado_estilo; ?>"><?php echo $Cotizacion->estado_etiqueta; ?></span>

              </div>

              <div class="col-md-3"></div>
              <div class="col-md-3"></div>
            </div>

         

            <div class="row mt-3">
              <div class="col-md-3">
                <i class="fa fa-compass"></i> Usuario Creador :
              </div>
              <div class="col-md-3">
                <span class="badge badge-info"  id="user-log" > </span>
              </div>

              <div class="col-md-3"></div>
              <div class="col-md-3"></div>
            </div>


            <?php
            //Proyecto / Presupuesto Asociado
              if(isset($data_proyecto_cotizacion->rowid)){
            ?>

            <div class="row mt-3">
              <div class="col-md-3">
               <strong style="color:#34AC55;"><i class="fa fa-code-fork" aria-hidden="true"></i> Proyecto Veríficado</strong>
              </div>
              <div class="col-md-3">
                <a href="<?php echo ENLACE_WEB; ?>redhouse_proyecto_detalle/<?php echo $data_proyecto_cotizacion->rowid; ?>"><span class="badge badge-success"  id="user-log"><i class="fa fa-certificate" aria-hidden="true"></i>                 <?php echo $data_proyecto_cotizacion->proyecto_consecutivo; ?></span></a>
              </div>
              <div class="col-md-3"></div>
              <div class="col-md-3"></div>
            </div>

            <?php 
              }
            ?>

            
           <div class="row mt-3" >
              <div class="col-md-3">
                  <i class="fa fa-mouse-pointer"></i> Adjuntos:
              </div>
              <div class="col-md-9" id="refrescar_adjuntos">
                     <?php 
                  foreach($listado_adjuntos as $adjunto){

                   $url_enlace =  ENLACE_WEB .'servir_adjuntos_cotizaciones?img=' . $_SESSION['Entidad'] . '/cotizacion/' . $adjunto->label;
                ?>
                  <span class="badge badge-info">
                     <a download="<?php echo $adjunto->descripcion; ?>" href="<?php echo $url_enlace; ?>" style="color: white;"><i class="fa fa-paperclip"></i><?php echo $adjunto->descripcion; ?></a>
                  </span>
                <?php } ?>    

              </div>
          </div>













            <!-- tabs-->
            <ul class="nav nav-tabs mt-3" id="myTab" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Timeline</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false" tabindex="-1">Tareas</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="servicios-tab" data-bs-toggle="tab" data-bs-target="#servicios-tab-pane" type="button" role="tab" aria-controls="servicios-tab-pane" aria-selected="false">Articulos</button>
              </li>

               <li class="nav-item" role="presentation">
                    <button class="nav-link" id="adjuntos-tab" data-bs-toggle="tab" data-bs-target="#adjuntos-tab-pane" type="button" role="tab" aria-controls="adjuntos-tab-pane" aria-selected="false">Adjuntos</button>
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
                <h4 class="mt-4"><strong id="cantidadActividades">
                    <div class="spinner-border text-success align-self-center "></div>
                  </strong> Actividades registradas para esta cotizacion </h4>
                <div class="text-center mt-5" id="my-roadmap"></div>
              </div>


              <div class="tab-servicios fade" id="servicios-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                <button type="button" style="cursor:pointer;float:right;margin-bottom:20px;" class="btn btn-primary pull-right serviciomodal" data-bs-toggle="modal" data-bs-target="#serviceModal">
                  <i class="fa fa-plus" aria-hidden="true"></i>Agregar Articulo
                </button>
                <input type="hidden" id="servicio_fk_producto">
                <table id="service-table" class="table table-striped">
                  <thead>
                    <tr>
                      <th></th>
                      <th>Ref</th>
                      <th>Servicios</th>
                      <th>Cant</th>
                      <th>Días</th>
                      <th>Horas</th>
                      <th>P Unitario</th>
                      <th>Impuesto</th>
                      <th>Total</th>
                      <th></th>
                    </tr>

                  </thead>
                  <tbody id="tabla_servicios">
                    <?php include_once(ENLACE_SERVIDOR . "mod_redhouse_cotizaciones/ajax/listado_servicios.ajax.php"); ?>
                  </tbody>
                </table>
              </div>

              <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">

                <button type="button" style="cursor:pointer;float:right;margin-bottom:20px;" class="btn btn-primary pull-right" data-bs-toggle="modal" data-bs-target="#taskModal">
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
                    <?php include_once(ENLACE_SERVIDOR . "mod_redhouse_cotizaciones/ajax/listado_actividades.ajax.php"); ?>
                  </tbody>
                </table>


              </div> <!--- tab ---->

            </div> <!-- tab global -->

          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<div class="row mt-3">
  <div class="col-md-12">
    <a href="<?php echo ENLACE_WEB; ?>redhouse_cotizaciones" class="btn btn-outline-primary"> Volver Al Listado </a>
    <button type="button" onclick="eliminar_cotizacion(event)" class="btn btn-danger">
                                                <i class="fa fa-fw fa-trash"></i>
                                                Eliminar
                                            </button>
    
    <a style="background-color: #E43E30 !important; color:white; border:1px solid #E43E30 !important;" href="<?php echo ENLACE_WEB; ?>mod_redhouse_cotizaciones/class/generar_pdf.php?id=<?php echo $Cotizacion->id; ?>" target="_blank" class="btn btn-primary _effect--ripple waves-effect waves-light"><i class="fa fa-file-pdf-o" aria-hidden="true"></i>PDF</a>
      

    <a style="color: white; background-color: #1D9E74 !important; border: 1px solid #1D9E74 !important;"  href="<?php echo ENLACE_WEB; ?>mod_redhouse_cotizaciones/class/generar_excel.php?id=<?php echo $Cotizacion->id; ?>" target="_blank" class="btn btn-primary _effect--ripple waves-effect waves-light"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar a Excel</a>


    <a href="<?php echo ENLACE_WEB; ?>redhouse_cotizaciones_detalle_modificar/<?php echo $Cotizacion->id; ?>" class="btn btn-primary _effect--ripple waves-effect waves-light">Modificar Cotización</a>


    <a class="btn btn-primary" href="#" onclick="abrir_email()"><i class="fa  fa-stack-exchange"></i> Enviar Por Email</a>

    <?php
            //Proyecto / Presupuesto Asociado
        if(isset($data_proyecto_cotizacion->rowid)){
    ?>  
      <button type="button" style="margin-top:15px;" disabled class="btn btn-primary _effect--ripple waves-effect waves-light">
      <i class="fa fa-product-hunt" aria-hidden="true"></i> Generar Proyecto</button>
    <?php }else{ ?>

    <button type="button" style="margin-top:15px;" onclick="generar_proyecto(<?php echo $_GET['fiche']; ?>)" class="btn btn-primary _effect--ripple waves-effect waves-light">
    <i class="fa fa-product-hunt" aria-hidden="true"></i> Generar Proyecto</button>
    
    <?php } ?>

  </div>
</div>

<!------------------------------------------------->
<!-------------------------------------------------->
<!---------------javascript de los adjuntos---------->
<!--------------------------------------------------->
<script type="text/javascript">
      
      function refrescar_adjuntos()
      {

        $.ajax({
              method: "POST",
              url: "<?php echo  "".ENLACE_WEB ?>mod_redhouse_cotizaciones/class/class.php",
              data: {
                  action:'RefrescarAdjuntos',
                  fk_cotizacion: '<?php echo $_GET["fiche"]; ?>',
              },
          }).done(function(result) {
              // console.log(result)
              $("#refrescar_adjuntos").html(result);
          });
      }

      function tab_imagenes() {
          $.ajax({
              method: "POST",
              url: "<?php echo  "" . ENLACE_WEB ?>mod_redhouse_cotizaciones/ajax/cotizacion_imagenes_ajax.php",
              data: {
                  // pagina: int,
                  fiche: '<?php echo $_GET["fiche"]; ?>',
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

            // Preparar la petición AJAX
            $.ajax({
                method: "POST",
                url: "<?php echo ENLACE_WEB; ?>mod_redhouse_cotizaciones/ajax/subir_adjuntos.ajax.php",
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
                    })

                    tab_imagenes();
                    refrescar_adjuntos();
                }

                // Aquí puedes añadir código para actualizar la interfaz de usuario si es necesario
            }).fail(function(jqXHR, textStatus, errorThrown) {
                console.error("Error en la petición AJAX:", textStatus, errorThrown);
                add_notification({
                    text: 'Hubo un error al subir el archivo.',
                    actionTextColor: '#fff',
                    backgroundColor: '#e7515a'
                })
            });
        }


        function eliminar_cotizacion(event) {
        event.preventDefault();

        let rowid = $('[name="fiche"]').val(); // Asegúrate de que este campo exista en tu formulario
        let message = "¿Deseas eliminar esta cotizacion?";
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
                    url: "<?php echo ENLACE_WEB; ?>mod_redhouse_cotizaciones/class/class.php",
                    data: {
                        action: 'eliminar_cotizacion',
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
                            text: 'Cotizacion eliminado exitosamente!',
                            actionTextColor: '#fff',
                            backgroundColor: '#00ab55',
                            dismissText: 'Cerrar'
                        });

                        setTimeout(() => {
                            window.location.href = "<?=ENLACE_WEB?>redhouse_cotizaciones";
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


        function eliminar_imagen(x, label) {
            let fk_cotizacion = '<?php echo $_GET["fiche"]; ?>';

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
                        url: "<?php echo ENLACE_WEB; ?>mod_redhouse_cotizaciones/class/class.php",
                        data: {
                            action: 'BorrarAdjunto',
                            id: x,
                            fk_cotizacion: fk_cotizacion,
                            label: label
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
                            refrescar_adjuntos();
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






<!------------  ------------------------------------------->
<!------------  ------------------------------------------->
<!------------  ------------------------------------------->
<!---------     Javascript del TIMELINE  ------------------>
<!------------  ------------------------------------------->
<!------------  ------------------------------------------->
<!------------  ------------------------------------------->




<script>
function abrir_email()
{

    largeur = 600;
    hauteur = 550;
    opt = 'width='+largeur+', height='+hauteur+', left='+(screen.width - largeur)/2+', top='+(screen.height-hauteur)/2+'';
    window.open ('<?php echo ENLACE_WEB ?>mail_sys/mail/enviar_email_cotizacion_redhouse.tpl.php?id=<?php echo $_REQUEST['fiche'] ?>&opcion=cotizacion', 'Enviar Email Cotización', opt);
 
}

//Vamos a generar la cotizacion
function generar_proyecto(cotizacion_id)
{
  
          var message = "Deseas Generar el Proyecto de esta cotización?";
          var actionText = "Confirmar";

            // Mostrar el snackbar y definir el callback para el botón de acción
            add_notification({
                text: message,
                width: 'auto',
                duration: 30000,
                actionText: actionText,
                dismissText: 'Cerrar',
                onActionClick: function(element)
                {
                    $.ajax({
                        method: "POST",
                        url: "<?php echo ENLACE_WEB; ?>mod_redhouse_proyecto/class/class.php",
                        data: {
                            action: 'generar_proyecto',
                            fk_cotizacion: cotizacion_id,
                        },
                        cache: false,
                    }).done(function(msg) {
                        console.log(msg);
                       
                         const response = JSON.parse(msg);
                        if (response.error == 1) {

                            add_notification({
                                text: msg.mensaje_txt,
                                pos: 'top-right',
                                actionTextColor: '#fff',
                                backgroundColor: '#e7515a'
                            });

                        } else {

                            add_notification({
                                text: 'Proyecto Generado exitosamente!',
                                actionTextColor: '#fff',
                                backgroundColor: '#00ab55',
                                dismissText: 'Cerrar'
                            });

                          // Guardamos el enlace de redireccionamiento
                          var enlace = "<?php echo ENLACE_WEB; ?>redhouse_proyecto_detalle/" + response.id;

                          // Esperamos 3 segundos (3000 ms) antes de redireccionar
                          setTimeout(function() {
                              window.location.href = enlace;
                          }, 1500); // 3000 ms = 3 segundos

                        }
                        // Aquí puedes añadir código para actualizar la interfaz de usuario si es necesario
                    }).fail(function(jqXHR, textStatus, errorThrown) {
                        console.error("Error en la petición AJAX:", textStatus, errorThrown);
                        add_notification({
                            text: 'Hubo un error al intentar Crear el proyecto.',
                            actionTextColor: '#fff',
                            backgroundColor: '#e7515a'
                        })
                    });
                },
            });

}


function close_modal_edit(){
     $('#modal_editar_tarea').modal('hide');
}

  $.getJSON('<?= ENLACE_WEB ?>mod_redhouse_crm/json/timeline.json.php?term=<?= $Cotizacion->id ?>', function(data) {
    //data is the JSON string
    console.log(data.length);
    $("#cantidadActividades").text(data.length);
    var the_json = data;
    $(document).ready(function() {


      var events = the_json;
      //console.log(events);

      $('#my-roadmap').roadmap(events, {
        eventsPerSlide: 10,
        slide: 1,
        orientation: 'auto',
        prevArrow: '<i class="material-icons">keyboard_arrow_left</i>',
        nextArrow: '<i class="material-icons">keyboard_arrow_right</i>',
        eventTemplate: '<div class="event">' + '<div class="event__date">####DATE###</div>' + '<div class="event__content">####ESTATUS###</div>' + '<div style="cursor:pointer"; onclick="(####ROWID###);" class="event__content">####CONTENT###</div>' + '<div onclick="(####ROWID###);"  class="event__content actividy_####ROWID_A###"></div>' + '</div>',
        onBuild: function() {
          console.log('onBuild event')
        }
      });
    });
  });


    document.addEventListener("DOMContentLoaded", function() {
            // Obtén la fecha de hoy en formato YYYY-MM-DD
            var today = new Date().toISOString().split('T')[0];
            
            // Asigna la fecha de hoy al atributo min del input
            document.getElementById('vencimiento_fecha').setAttribute('min', today);
  });



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
foreach (explode(",", $Cotizacion->cotizacion_tags) as $array) {
  $tags_en_formato .= $coma . '"' . $array . '"';
  $coma = ",";
}

?>
<script>
  $(document).ready(function() {

    // Desactivar todos los elementos del menú
    $(".menu").removeClass('active');

    $(".redhouse_cotizaciones").addClass('active');
    $(".redhouse_cotizaciones > .submenu").addClass('show');

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
  });
</script>

<!------------  ------------------------------------------->
<!------------  ------------------------------------------->
<!------------  ------------------------------------------->
<!---------     Javascript de las tareas ------------------>
<!------------  ------------------------------------------->
<!------------  ------------------------------------------->
<!------------  ------------------------------------------->

<script>

  function clear_task_inputs(){

    $("#comentario").val('')
    $("#vencimiento_fecha").val('')
    $("#fk_diccionario_actividad").val('')

  }

  function guardarTarea() {
    $("#vencimiento_fecha").removeClass('input_error');

    if ($("#vencimiento_fecha").val() == '') {
      $("#vencimiento_fecha").addClass('input_error');
      return false;
    }

    // guardar una actividad
    $.ajax({
      method: "POST",
      url: "<?= ENLACE_WEB ?>mod_redhouse_cotizaciones/class/class.php",
      beforeSend: function(xhr) {},
      data: {
        action: 'guardarTarea',
        fk_cotizacion: <?= $Cotizacion->id ?>,
        fk_diccionario_actividad: $("#fk_diccionario_actividad").val(),
        vencimiento_fecha: $("#vencimiento_fecha").val(),
        fk_usuario_asignado: $("#fk_usuario_asignado").val(),
        email_usuario_asignado: $("#fk_usuario_asignado").find('option:selected').attr("email_usuario"),
        nombre_usuario_asignado: $("#fk_usuario_asignado").find('option:selected').text(),
        nombre_actividad: $("#fk_diccionario_actividad").find('option:selected').text(),
        comentario: $("#comentario").val(),

      },
    }).done(function(data) {
      //  data = JSON.parse(data);
      console.log(data);
      if (data) {
        $("#taskModal").modal('hide');
        clear_task_inputs()
        paginarTareas(0);
       
      }
    });
  }

  function actualizarTarea(int) {

    // guardar una actividad
    $.ajax({
      method: "POST",
      url: "<?= ENLACE_WEB ?>mod_redhouse_cotizaciones/class/class.php",
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
      url: "<?= ENLACE_WEB ?>mod_redhouse_cotizaciones/ajax/listado_actividades.ajax.php?fiche=<?= $Cotizacion->id ?>",
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
      url: "<?= ENLACE_WEB ?>mod_redhouse_cotizaciones/ajax/editarTarea.php",
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




</script>
<!------------  ------------------------------------------->
<!------------  ------------------------------------------->
<!------------  ------------------------------------------->
<!---------     Javascript de los servicios --------------->
<!------------  ------------------------------------------->
<!------------  ------------------------------------------->
<!------------  ------------------------------------------->
<style>
    .ui-autocomplete {
        z-index: 99999999999999999999; /* Ajusta el z-index para que esté por encima del modal */
    }
</style>


<script>
  $(function() {


    $(document).on("click",".serviciomodal",function()
    {
      //borramos todo
      clear_service_inputs();
    });

    $("#servicio_descripcion").autocomplete({
      source: "<?php echo ENLACE_WEB; ?>mod_productos/json/productos.json.php?codigo_moneda_oportunidad=<?php echo $moneda_codigo; ?>&tipo=servicios&tipo_cambio_oportunidad=<?php echo $tipo_cambio; ?>&cliente=",
      minLength: 2,
      select: function(event, ui) {
        console.log(ui);

        $("#servicio_descripcion"       ).val(ui.item.value);
        $("#servicio_precio_unitario  " ).val(ui.item.subtotal);
        $("#servicio_cantidad"          ).val(1);
        $('#servicios_imagenes').html("");
        $("#servicio_precio_tipo_impuesto").find('option').each(function(){
          val = parseInt($(this).val());
          if(parseInt(val) === parseInt(ui.item.impuesto))
          {
            $(this).attr('selected','selected');
            $("#servicio_precio_tipo_impuesto").val($(this).val());
          }


        });
        //servir_imagenes_productos?img=

        //servicio_precio_tipo_impuesto



        $.each(ui.item.imagenes, function(index, imagen) { 
          if(imagen!='')
          {
            var img = $('<img>').attr('src', ENLACE_WEB + 'servir_imagenes_productos?img=' + imagen)
                          .attr('alt', 'Imagen ' + (index + 1))
                          .addClass('img-fluid'); // Agrega la clase img-fluid
             $('#servicios_imagenes').append(img);
          }
        });
       
        $("#servicio_fk_producto").val(ui.item.id);

      }
    });

  });


//  
  //function para actualizar el servicio
  function actualizar_servicio()
  {

     error = false;

      $("#servicio_descripcion_update"     ).removeClass('input_error');
      $("#servicio_cantidad_update"        ).removeClass('input_error');
      $("#servicio_precio_unitario_update" ).removeClass('input_error');
      $("#servicio_precio_tipo_impuesto_update").removeClass('input_error');
      $("#cantidad_dias_update").removeClass('input_error');

      if ($("#servicio_descripcion_update").val() == '') {
          $("#servicio_descripcion_update").addClass('input_error');
          error = true ;
      }

      if ($("#servicio_cantidad_update").val() == '') {
          $("#servicio_cantidad_update").addClass('input_error');
          error = true ;
      }

      if ($("#servicio_precio_unitario_update").val() == '') {
          $("#servicio_precio_unitario_update").addClass('input_error');
          error = true ;
      }

      if ($("#cantidad_dias_update").val() == '') {
          $("#cantidad_dias_update").addClass('input_error');
          error = true ;
      }

      if (error){
        return false;
      }

      // guardar una actividad
      $.ajax({
        method: "POST",
        url: "<?= ENLACE_WEB ?>mod_redhouse_cotizaciones/class/class.php",
        beforeSend: function(xhr) {},
        data: {
          action: 'actualizar_servicio',
          cotizacion_id                 : <?= $Cotizacion->id ?>,
          servicio_precio_tipo_impuesto : $("#servicio_precio_tipo_impuesto_update").val(),
          servicio_precio_unitario      : $("#servicio_precio_unitario_update").val(),
          servicio_cantidad             : $("#servicio_cantidad_update").val(),
          servicio_comentario           : $("#servicio_comentario_update").val(),
          servicio_fk_producto          : $("#servicio_fk_producto_update").val(),
          servicio_cantidad_dias       : $("#cantidad_dias_update").val(),
          rowid: $("#servicio_fk_row_id_update").val(),
          servicio_tipo_duracion: $("#tipo_duracion_editar").val(),

        },
      }).done(function(data) {
        
       data = JSON.parse(data);
       console.log("Resultado de guardar_servicio()  ") ;
       console.log(data);
       
       add_notification({
                              text: data.respuesta   ,
                              actionTextColor: '#fff',
                              backgroundColor: '#00ab55',
                              dismissText: 'Cerrar'
                          });
       $("#modal_editar_servicio").modal('hide');
       listar_servicios();

      });

  }


  function clear_service_inputs()
  {

    $("#servicio_descripcion" ).val('');
    $("#servicio_cantidad" ).val('');
    $("#servicio_precio_unitario").val('');
    $("#servicio_precio_tipo_impuesto").val('');
    $("#servicio_comentario").val('');
    $("#servicios_imagenes").html('');
    $("#cantidad_dias").val("");
    $("#tipo_duracion").val("");
  }


  //Funcion para guardar servicio
  function guardar_servicio() {
    
    error = false;

    $("#servicio_descripcion"     ).removeClass('input_error');
    $("#servicio_cantidad"        ).removeClass('input_error');
    $("#servicio_precio_unitario" ).removeClass('input_error');
    $("#servicio_precio_tipo_impuesto").removeClass('input_error');
    $("#cantidad_dias").removeClass("input_error");
  

    if ($("#servicio_descripcion").val() == '') {
        $("#servicio_descripcion").addClass('input_error');
        error = true ;
    }

    if ($("#servicio_cantidad").val() == '') {
        $("#servicio_cantidad").addClass('input_error');
        error = true ;
    }

    if ($("#servicio_precio_unitario").val() == '') {
        $("#servicio_precio_unitario").addClass('input_error');
        error = true ;
    }

    if ($("#servicio_precio_unitario").val() == '') {
        $("#servicio_precio_unitario").addClass('input_error');
        error = true ;
    }

    if ($("#servicio_precio_tipo_impuesto").val() == '') {
        $("#servicio_precio_tipo_impuesto").addClass('input_error');
        error = true ;
    }
    if($("#cantidad_dias").val() === '')
    {
      $("#cantidad_dias").addClass('input_error');
      error = true;
    }




    if (error){
      return false;
    }

    // guardar una actividad
    $.ajax({
      method: "POST",
      url: "<?= ENLACE_WEB ?>mod_redhouse_cotizaciones/class/class.php",
      beforeSend: function(xhr) {},
      data: {
        action: 'guardar_servicio',
        cotizacion_id                 : <?= $Cotizacion->id ?>,
        servicio_precio_tipo_impuesto : $("#servicio_precio_tipo_impuesto").val(),
        servicio_precio_unitario      : $("#servicio_precio_unitario").val(),
        servicio_cantidad             : $("#servicio_cantidad").val(),
        servicio_comentario           : $("#servicio_comentario").val(),
        servicio_fk_producto          : $("#servicio_fk_producto").val(),
        servicio_cantidad_dias        : $("#cantidad_dias").val(),
        servicio_tipo_duracion        : $("#tipo_duracion").val(),

      },
    }).done(function(data) {
      
     data = JSON.parse(data);
     console.log("Resultado de guardar_servicio()  ") ;
     console.log(data);
     
     add_notification({
                            text: data.respuesta   ,
                            actionTextColor: '#fff',
                            backgroundColor: '#00ab55',
                            dismissText: 'Cerrar'
                        });
     $("#serviceModal").modal('hide');
     listar_servicios();
     clear_service_inputs();

    });
  }





  function listar_servicios(int) {

// traer tpl
$.ajax({
  method: "POST",
  url: "<?= ENLACE_WEB ?>mod_redhouse_cotizaciones/ajax/listado_servicios.ajax.php?fiche=<?= $Cotizacion->id ?>",
  beforeSend: function(xhr) {},
 
}).done(function(data) {
  //pintar tpl en modal y mostrarlo
  $("#tabla_servicios").html(data);


});

}



  function editarServicio(int) {
    // traer tpl
    $.ajax({
      method: "POST",
      url: "<?= ENLACE_WEB ?>mod_redhouse_cotizaciones/ajax/editarServicio.php",
      beforeSend: function(xhr) {},
      data: {
        action: 'editarServicio',
        rowid: int,
        fk_cotizacion:'<?php echo $_GET["fiche"]; ?>',
      },
    }).done(function(data)
    {
      //pintar tpl en modal y mostrarlo
       $("#modal_editar_servicio").html(data);
      $("#modal_editar_servicio").modal('show');

    });

  }



  //funcion para eliminar servicio
  function eliminarServicio(rowid)
  {

      result = confirm("¿Deseas remover el servicio?");
      if(result)
      {
           // guardar una actividad
          $.ajax({
            method: "POST",
            url: "<?= ENLACE_WEB ?>mod_redhouse_cotizaciones/class/class.php",
            beforeSend: function(xhr) {},
            data: {
              action: 'remover_servicio',
              cotizacion_id                 : <?= $Cotizacion->id ?>,
              rowid: rowid,
            },
          }).done(function(data) {
           data = JSON.parse(data);
           console.log("Resultado de guardar_servicio()  ") ;
           console.log(data);
           add_notification({
                                  text: data.respuesta   ,
                                  actionTextColor: '#fff',
                                  backgroundColor: '#00ab55',
                                  dismissText: 'Cerrar'
                              });
           $("#modal_editar_servicio").modal('hide');
           listar_servicios();
      });
  }

}


function mostrarComentario(){

  if ($("#edit_estado_tarea").val() == 3) {
    $(".div_cierre").show();
  }else{
    $(".div_cierre").hide();
  }
  
}


</script>






<!------------  ------------------------------------------->
<!------------  ------------------------------------------->
<!------------  ------------------------------------------->
<!---------     MODALES - --------------------------------->
<!------------  ------------------------------------------->
<!------------  ------------------------------------------->
<!------------  ------------------------------------------->

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

                foreach ($Cotizacion->diccionarioActividades() as $item) {
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
                foreach ($Cotizacion->usuarios_disponibles() as $item) {
                  echo ' <option email_usuario="'.$item->email.'" value="' . $item->rowid . '">' . $item->nombre . '</option>';
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








<!-- Modal services-->
<div class="modal fade " id="serviceModal" tabindex="-1" aria-labelledby="serviceModalTitle" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="serviceModalTitle">Nuevo Articulo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        </button>
      </div>
      <div class="modal-body">

        <div class="form-group">


          <div class="form-row">
            <div class="form-group col-md-12">
              <label for="actividad">Articulo:</label>
              <input autocomplete="off" class="form-control ui-autocomplete-input"  id="servicio_descripcion" name="servicio_descripcion" class="form-control">
            </div>
          </div>
 
          <div class="row mt-2">
            <div class="col-md-2">
              <label for="">Cant:</label>
              <input type="input" name="servicio_cantidad" id="servicio_cantidad" class="form-control">
            </div>

            <div class="col-md-4">
              <label for="">Precio Unitario:</label>
              <input type="input" name="servicio_precio_unitario" id="servicio_precio_unitario" class="form-control">
            </div>

            <div class="col-md-4">
              <label for="">Impuestos:</label>
              <select   name="servicio_precio_tipo_impuesto" id="servicio_precio_tipo_impuesto" class="form-control">
              <?php 
                                           
                                            foreach ( $productos->impuestos as $clave => $valor) {
                                                echo "<option value='".$valor['impuesto']."'>".$valor['impuesto']."% - ".$valor['impuesto_texto']."</option>";
                                            }
                                                ?>   
                </select>
            </div>


          </div>
 
 
            <div class="form-group col-md-12 mt-2">
              <label for="">Comentario:</label>
              <textarea class="form-control" name="servicio_comentario" id="servicio_comentario" cols="30" rows="5"></textarea>
            </div>

            <div class="row">
              <div class="form-group col-md-6 mt-2">
                <label>Dias: </label>
                <input type="number" id="cantidad_dias" name="cantidad_dias" class="form-control">
                <small>Cantidad de días que ocupara este Articulo</small>
              </div>
              <div class="form-group col-md-6 mt-2">
                <label>Horas: </label>
                <input type="number" name="tipo_duracion" id="tipo_duracion" class="form-control">
                <small>Cantidad de horas que ocupara por dia en el evento</small>
              </div>
            </div>
            <div class="form-group col-md-12 mt-2" id="servicios_imagenes">
            </div>
          </div>
      </div>
      <div class="modal-footer">
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

<script>
    var rowid = '<?= $_REQUEST['fiche'] ?>';



function get_create_user_quotation(customer_id) {

  $.ajax({
    method: "GET",
    url: `<?= ENLACE_WEB ?>mod_redhouse_cotizaciones/ajax/obtener_usuario_log.ajax.php`,
    data: {
      rowid
    },
    cache: false,
  }).done(function(response) {



    if (!response.data) {

      add_notification({
        text: 'Hubo un error al consultar el usuario.',
        actionTextColor: '#fff',
        backgroundColor: '#e7515a'
      });
      console.error(response)

      return;
    }
    let user = response.data

    document.querySelector("#user-log").innerHTML = user.name

    document.querySelector("#user-log").title = user.date
 

  }).fail(function(jqXHR, textStatus, errorThrown) {
    console.error("Error en la petición:", textStatus, errorThrown);
    add_notification({
      text: 'Hubo un error al consultar el usuario.',
      actionTextColor: '#fff',
      backgroundColor: '#e7515a'
    });
  });


}

if (rowid) {
  get_create_user_quotation(rowid)

}else{
  console.log("no")
}


</script>