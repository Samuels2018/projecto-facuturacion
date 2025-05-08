<?php 

require_once(ENLACE_SERVIDOR."mod_gastos_europa/object/Gastos.object.php");
require_once(ENLACE_SERVIDOR."mod_proyectos/object/Proyectos.object.php");

$Gastos = new Gastos($dbh , $_SESSION['Entidad']);
$Proyectos = new Proyectos($dbh , $_SESSION['Entidad']);


 $texto_informativo = "";
 $disabled = "disabled";
 
 if (!empty($_POST) && empty($_REQUEST['fiche'])) {
     // Crear nuevo gasto
     $Gastos->fetch($_REQUEST['fiche']);
     $_REQUEST['fiche'] = $id;
     $disabled = "";
 } elseif (!empty($_POST) && !empty($_REQUEST['fiche'])) {
     // Actualizar gasto existente
     $Gastos->fetch($_REQUEST['fiche']);
 } elseif (empty($_REQUEST['fiche'])) {
     // No hay ficha, formulario habilitado
     $disabled = "";
 } elseif (!empty($_REQUEST['fiche']) && $_REQUEST['action'] == "modify") {
     // Modificar ficha existente
     $disabled = "";
     $Gastos->fetch($_REQUEST['fiche']);
 } elseif (!empty($_REQUEST['fiche'])) {
     // Ver ficha existente
     $Gastos->fetch($_REQUEST['fiche']);
 }

// var_dump($Gastos);

?>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

  <div  class="middle-content container-fluid p-0" >
   <!-- BREADCRUMB -->
   <div class="page-meta">
      <nav class="breadcrumb-style-one" aria-label="breadcrumb">
         <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo ENLACE_WEB; ?>gastos_listado">Gastos</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo !empty($_REQUEST['fiche']) ? 'Editar '.$texto_informativo : 'Nuevo' ?></li>
             
         </ol>
      </nav>
   </div>
   <!-- /BREADCRUMB -->
   <!-- CONTENT AREA -->


   <div class="row mt-5">
    <!-- left column -->
    <div class="col-md-6">



        <!-- general form elements -->
        <div class="card">

        <?php if (!empty($_REQUEST['fiche']))
                    { 
                        if($Gastos->pagado == 1){
                        ?>
                   <div class="alert alert-success d-flex align-items-center" role="alert" style="font-size: 1rem; margin: 0.5rem;">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>Cuenta Pagada!</strong>
                    </div>
                    <div class="d-flex justify-content-between mt-2" style="font-size: 0.9rem; padding-left:20px; padding-right:20px;">
    <div>
        <strong>Pagado Por:</strong> 
        <span class="badge bg-primary">
            <?php echo htmlspecialchars($Gastos->pagado_por); ?>
        </span>
    </div>
    <div>
        <strong>Fecha:</strong> 
        <span class="badge bg-secondary">
            <?php 
                echo htmlspecialchars(date('d-m-Y H:i', strtotime($Gastos->fecha_pago))); 
            ?>
        </span>
    </div>
</div>




                    <?php } 
                        }
                    ?>

            <div class="card-body">

                <input type="hidden" name="fiche" value="<?php echo $_GET['fiche']; ?>">

                <input type="text" class="form-control form-control-sm" style="display:none" id="company-name" placeholder="" value="" <?php echo $disabled; ?> >
                <?php 
                    $nombre_buscador = isset($Gastos->fk_tercero) ? $Gastos->nombre_proveedor.' • '.$Gastos->email : '';
                ?>
                <div class="row mb-3">
                <div class="col-md-6">
                    <label for="ref"><i class="fas fa-user" aria-hidden="true"></i> Proveedor</label>
                    <div id="input_busqueda_tercero">
                        <input type="text" id="birds" value="<?php echo $nombre_buscador; ?>" placeholder="Digite nombre del proveedor" class="form-control form-control-sm ui-autocomplete-input" autocomplete="off" <?php echo $disabled; ?> >
                        <input type="hidden" id="fk_tercero" name="fk_tercero" value="<?php echo $Gastos->fk_tercero; ?>" <?php echo $disabled; ?> >
                    </div>
                    <div id="mostrar_nombre" class="row">
                        <a href="<?php echo (!empty($Gastos->fk_tercero) ? ENLACE_WEB.'proveedores_editar/'.$Gastos->fk_tercero : '#'); ?>"
                        <?php echo (!empty($Gastos->fk_tercero) ? 'target="blank"' : ''); ?> >
                           <!-- <span id="basic-cliente" style="<?php echo (!empty($Gastos->fk_tercero) ? 'color:#00c0ef' : ''); ?>">
                                <?php echo (!empty($Gastos->nombre_proveedor) ? $Gastos->nombre_proveedor : 'Proveedor Genérico'); ?>
                            </span>-->
                        </a>
                    </div>
                </div>

                    <div class="col-md-6">
                        <label for="recibo_numero"><i class="fas fa-file-invoice" aria-hidden="true"></i> Número Factura</label>
                        <input required="required" type="text" name="recibo_numero" id="recibo_numero" class="form-control" value="<?php echo $Gastos->recibo_numero; ?>" <?php echo $disabled; ?> >
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="fecha"><i class="fa fa-calendar-alt" aria-hidden="true"></i> Fecha Gasto</label>
                        <input required="required" type="text" name="fecha" id="fecha" class="form-control datepicker" value="<?php echo $Gastos->fecha; ?>" <?php echo $disabled; ?> >
                    </div>

                    <div class="col-md-6">
                        <label for="valor"><i class="fas fa-dollar-sign" aria-hidden="true"></i> Monto</label>
                        <input required="required" type="text" name="valor" id="valor" class="form-control" value="<?php echo $Gastos->valor; ?>" <?php echo $disabled; ?> >
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="fk_gasto"><i class="fas fa-list-alt" aria-hidden="true"></i> Tipo de Gasto</label>
                        <select required="required" type="text" name="fk_gasto" id="fk_gasto" class="form-control" value="<?php echo $Gastos->fk_gasto; ?>" <?php echo $disabled; ?> >
                            <?php echo $Gastos->cargar_selector($Gastos->fk_gasto); ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="fk_proyecto"><i class="fas fa-project-diagram" aria-hidden="true"></i> Proyecto</label>
                        <select name="fk_proyecto" id="fk_proyecto" class="form-control" <?php echo $disabled; ?> required>
                            <option value="">Seleccione un proyecto</option>
                            <?php
                            // Asumiendo que $Proyectos->listar_proyectos() devuelve un array de proyectos
                            $proyectos = $Proyectos->listar_proyectos();
                            if ($proyectos['exito']) {
                                foreach ($proyectos['data'] as $proyecto) {
                                    // Verifica si este proyecto es el seleccionado actualmente
                                    $selected = ($proyecto['rowid'] == $Gastos->fk_proyecto) ? 'selected' : '';
                                    echo "<option value=\"{$proyecto['rowid']}\" $selected>{$proyecto['nombre']}</option>";
                                }
                            } else {
                                echo "<option value=\"\">No hay proyectos disponibles</option>";
                            }
                            ?>
                        </select>
                    </div>

                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="detalle"><i class="fa fa-pencil-alt" aria-hidden="true"></i> Nota Interna</label>
                        <textarea class="form-control" name="detalle" id="detalle" rows="6" placeholder="Detalle" <?php echo $disabled; ?> ><?php echo $Gastos->detalle; ?></textarea>
                    </div>
                </div>

            </div>
        </div>
    </div>
                        
    <div class="col-md-6">
            <label for="archivo"><i class="fas fa-upload" aria-hidden="true"></i> Subir o arrastrar imagen aquí</label>
            <div id="drop-area" class="border border-dashed p-4 text-center" style="border: 2px dashed #007bff; cursor: pointer;">
                <p>Arrastra y suelta la imagen aquí o haz clic para seleccionarla</p>
                <input type="file" id="archivo" name="archivo" class="form-control-file d-none" accept="image/*">
            </div>
            <div id="preview-area" class="mt-3 text-center">
                <?php if (!empty($Gastos->url_recibo)) { 
                        $destination_dir = ENLACE_WEB_FILES_EMPRESAS . 'entidad_'. $_SESSION['Entidad'].'/gastos/'; 
                    ?>
                    <img src="<?php echo $destination_dir.'/'.$Gastos->url_recibo; ?>" class="img-fluid rounded shadow-sm preview-image" style="max-width: 300px; cursor: pointer;">
                <?php } ?>
            </div>
    </div>


</div>




               <div class="card-footer mt-12" style="margin-top:20px;">
                    <a href="<?php echo ENLACE_WEB; ?>gastos_listado" class="btn btn-outline-primary _effect--ripple waves-effect waves-light">
                                Volver al Listado
                    </a>


                    <?php 
                        if(isset($_GET['fiche'])){

                        if($Gastos->pagado != 1  && $_GET['action']!='modify'){
                    ?>
                    <a href="<?php echo ENLACE_WEB; ?>dashboard.php?accion=<?php echo $_GET['accion'] ?>&fiche=<?php echo $_REQUEST['fiche'] ?>&action=modify" class="btn btn-success">
                        <i class="fa fa-fw fa-edit"></i> Modificar
                    </a>
                    <?php } ?>

                    <a href="#" onclick="confirma_eliminar_gasto()" class="btn btn-danger bs-tooltip _effect--ripple waves-effect waves-light" data-bs-placement="left" data-bs-original-title="Tooltip on left"><i class="fa fa-fw fa-trash" aria-hidden="true"></i> Eliminar </a>
                  
                    <?php if (!empty($_REQUEST['fiche']))
                    { 
                        if($Gastos->pagado != 1  && $_GET['action']!='modify'){
                        ?>
                    <a title="Marcar como pagado" href="#"  OnClick="pagar()"  class="btn btn-success _effect--ripple waves-effect waves-light">
                        <i class="fa fa-credit-card" aria-hidden="true"></i> Pagar 
                    </a>
                    <?php } 
                        }
                    ?>


                    <?php }else{ ?>
                        
                        <a href="#"  OnClick="Actualizar()"  class="btn btn-success _effect--ripple waves-effect waves-light">
                            <i class="fa fa-fw fa-edit" aria-hidden="true"></i> Guardar 
                        </a>

                    <?php } ?>

                    <?php if ($_REQUEST['action'] == "modify" && !empty($_REQUEST['fiche'])) { ?>
                                            <button type="button" onclick="Actualizar()" class="btn btn-primary">
                                                <i class="fa fa-fw fa-circle"></i>Guardar Cambios Gastos
                                            </button>
                    <?php } ?>

                </div>
                
                
               </div>
               </div>
               
                             
    </div>


<!-- Modal para previsualización en pantalla completa -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Vista previa de la imagen</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
    <?php 
        $destination_dir = ENLACE_WEB_FILES_EMPRESAS . 'entidad_'. $_SESSION['Entidad'].'/gastos/'; 
    ?>
      <div class="modal-body text-center">
        <img id="modalImage" class="img-fluid" style="max-width: 100%;" src="<?php echo !empty($Gastos->url_recibo) ? $destination_dir.''.$Gastos->url_recibo : ''; ?>">
      </div>
    </div>
  </div>
</div>


   
<script>
    $(function() {
        $("#fecha").datepicker({
            dateFormat: "yy-mm-dd",
            changeMonth: true,
            changeYear: true
        });
    });
</script>


<script>
    $(document).ready(function () {
        
        var dropArea = $("#drop-area");
        var fileInput = $("#archivo");
        var previewArea = $("#preview-area");
        var modalImage = $("#modalImage");

        // Mostrar imagen previa si existe
        $(".preview-image").on("click", function () {
            var imgSrc = $(this).attr("src");
            modalImage.attr("src", imgSrc);
            $("#imageModal").modal("show");
        });

        // Evento para abrir el input file al hacer clic en el área
        dropArea.on("click", function () {
            fileInput.click();
        });

        // Evento para manejar la selección de archivos
        fileInput.on("change", function (e) {
            var file = e.target.files[0];
            handleFile(file);
        });

        // Evento de arrastrar sobre el área
        dropArea.on("dragover", function (e) {
            e.preventDefault();
            dropArea.css("border-color", "#28a745");
        });

        // Evento de salir del área de arrastre
        dropArea.on("dragleave", function () {
            dropArea.css("border-color", "#007bff");
        });

        // Evento de soltar el archivo
        dropArea.on("drop", function (e) {
            e.preventDefault();
            dropArea.css("border-color", "#007bff");
            var file = e.originalEvent.dataTransfer.files[0];
            handleFile(file);
        });

        function handleFile(file) {
            if (file && file.type.startsWith("image/")) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    previewArea.html(`<img src="${e.target.result}" class="img-fluid rounded shadow-sm preview-image" style="max-width: 300px; cursor: pointer;">`);
                    modalImage.attr("src", e.target.result);
                };
                reader.readAsDataURL(file);
            } else {
                alert("Por favor, selecciona un archivo de imagen válido.");
            }
        }


        // Evento para mostrar la imagen en pantalla completa al hacer clic en la miniatura
        $(document).on("click", ".preview-image", function () {
            $("#imageModal").modal("show");
        });
    });
</script>


<script>


    function pagar() {
        Swal.fire({
            title: '¿Está seguro?',
            text: "¿Desea realizar el pago?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, realizar pago',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Deshabilitar el botón de pago
                //let botonPago = document.querySelector('button[onclick="pagar()"]');
                //botonPago.disabled = true;

                // Recoger los valores del formulario usando jQuery
                let id = $('input[name="fiche"]').val();
                let recibo_numero = $("#recibo_numero").val();
                let fecha = $("#fecha").val();
                let valor = $("#valor").val();
                let detalle = $("#detalle").val();
                let fk_gasto = $("#fk_gasto").val();
                let fk_tercero = $("#fk_tercero").val();

                // Verificar campos obligatorios
                let error = false;

                $('input[name="recibo_numero"]').removeClass("input_error");
                $('input[name="valor"]').removeClass("input_error");

                if (recibo_numero === '') {
                    $('input[name="recibo_numero"]').addClass("input_error");
                    error = true;
                }
                if (valor === '') {
                    $('input[name="valor"]').addClass("input_error");
                    error = true;
                }

                if (error) {
                    add_notification({
                        text: 'Faltan Datos Obligatorios',
                        actionTextColor: '#fff',
                        backgroundColor: '#e7515a'
                    });
                    //botonPago.disabled = false; // Habilitar el botón si hay error
                    return true;
                }

                // Enviar la petición AJAX
                $.ajax({
                    method: "POST",
                    url: "<?php echo ENLACE_WEB; ?>mod_gastos_europa/class/gastos.class.php",
                    beforeSend: function(xhr) {},
                    data: {
                        action: 'ActualizarPagoGasto',
                        id: id,
                        recibo_numero: recibo_numero,
                        fecha: fecha,
                        valor: valor,
                        fk_gasto: fk_gasto,
                        detalle: detalle,
                        fk_tercero: fk_tercero
                    },
                }).done(function(msg) {
                    console.log("Realizando pago");
                    console.log(msg);

                    let mensaje = jQuery.parseJSON(msg);

                    if (mensaje.exito === 1)
                    {
                        add_notification({
                            text: mensaje.mensaje,
                            actionTextColor: '#fff',
                            backgroundColor: '#00ab55',
                            dismissText: 'Cerrar'
                        });

                        setTimeout(function() {
                            window.location.href = "<?php echo ENLACE_WEB; ?>gastos_listado";
                        }, 1000); // Redirige después de 1 segundo


                    } else {
                        add_notification({
                            text: "Error:" + mensaje.mensaje,
                            actionTextColor: '#fff',
                            backgroundColor: '#e7515a'
                        });
                    }
                }).fail(function() {
                    add_notification({
                        text: "Error al comunicarse con el servidor.",
                        actionTextColor: '#fff',
                        backgroundColor: '#e7515a'
                    });
                }).always(function() {
                    // Habilitar el botón de pago al finalizar la petición
                    botonPago.disabled = false;
                });
            }
        });
    }



    //crud via jax
    function Actualizar()
    {
        error = false;

        $('input[name="recibo_numero"]').removeClass("input_error");
        $('input[name="valor"]').removeClass("input_error");
        $('input[name="fecha"]').removeClass("input_error");
        $('input[name="ref"]').removeClass("input_error");
        $('select[name="fk_gasto"]').removeClass("input_error");
        $('select[name="fk_proyecto"]').removeClass("input_error");

        // Recoger los valores del formulario usando jQuery
        var id             = $('input[name="fiche"]').val();
        var recibo_numero  = $("#recibo_numero").val();
        var fecha          = $("#fecha").val();
        var valor          = $("#valor").val();
        var detalle        = $("#detalle").val();
        var fk_gasto       = $("#fk_gasto").val();
        var fk_tercero     = $("#fk_tercero").val();
        var ref            = $("#ref").val();
        var fk_proyecto            = $("#fk_proyecto").val();
        var archivo        = $("#archivo")[0].files[0]; // Obtener el archivo seleccionado

        // Validaciones de campos
        if (recibo_numero == '') {
            $('input[name="recibo_numero"]').addClass("input_error");
            error = true;
        } 
        if (valor == '') {
            $('input[name="valor"]').addClass("input_error");
            error = true;
        } 
        if (fecha == '') {
            $('input[name="fecha"]').addClass("input_error");
            error = true;
        } 
        if (ref == '') {
            $('input[name="ref"]').addClass("input_error");
            error = true;
        } 
        if (fk_gasto == '') {
            $('select[name="fk_gasto"]').addClass("input_error");
            error = true;
        } 

        if($("#birds").val()!='' && fk_tercero=='') {
            add_notification({
                text: 'El proveedor no existe',
                actionTextColor: '#fff',
                backgroundColor: '#e7515a',
            });
            return false;
        }

        if (error) {
            add_notification({
                text: 'Faltan Datos Obligatorios',
                actionTextColor: '#fff',
                backgroundColor: '#e7515a',
            });
            return true;
        }

        // Crear objeto FormData para enviar datos con archivo
        var formData = new FormData();
        formData.append('action', 'editar_gasto');
        formData.append('id', id);
        formData.append('recibo_numero', recibo_numero);
        formData.append('fecha', fecha);
        formData.append('valor', valor);
        formData.append('fk_gasto', fk_gasto);
        formData.append('detalle', detalle);
        formData.append('fk_tercero', fk_tercero);
        formData.append('fk_proyecto', fk_proyecto);
        formData.append('archivo', archivo);  // Añadir archivo al FormData

        // Enviar AJAX con FormData
        $.ajax({
            method: "POST",
            url: "<?php echo ENLACE_WEB; ?>mod_gastos_europa/class/gastos.class.php",
            data: formData,
            contentType: false,  // Importante para enviar archivos
            processData: false,  // Importante para evitar la conversión automática
            beforeSend: function(xhr) {
                // Puedes mostrar un loader aquí si es necesario
            },
            success: function(msg) {
                console.log("Actualizando");
                console.log(msg);

                var mensaje = jQuery.parseJSON(msg);

                if (mensaje.exito == 1) {
                    add_notification({
                        text: mensaje.mensaje,
                        actionTextColor: '#fff',
                        backgroundColor: '#00ab55',
                        dismissText: 'Cerrar'
                    });

                    if (mensaje.accion == "crear") { 
                        window.location.href = "<?php echo ENLACE_WEB; ?>ver_gasto/" + mensaje.id; 
                    } else {
                        window.location.href = "<?php echo ENLACE_WEB; ?>ver_gasto/" + id; 
                    } 
                } else {
                    add_notification({
                        text: "Error:" + mensaje.mensaje,
                        actionTextColor: '#fff',
                        backgroundColor: '#e7515a',
                    });
                }
            },
            error: function() {
                alert("Error en la petición AJAX.");
            }
        });
}



    function confirma_eliminar_gasto () {
        let rowid = $('[name="fiche"]').val(); // Asegúrate de que este campo exista en tu formulario
        let message = "¿Deseas eliminar esta Cuenta?";
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
                    url: "<?php echo ENLACE_WEB; ?>mod_gastos_europa/class/gastos.class.php",
                    data: {
                        action: 'eliminar_gasto',
                        id: rowid
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
                            window.location.href = "<?php echo ENLACE_WEB; ?>gastos_listado";
                        }, 1000); // 

                    } else {

                        add_notification({
                            text: "Error:" + mensaje.mensaje,
                            actionTextColor: '#fff',
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


<script>

      /* AUTOCOMPLETE Para la Busqueda de Productos */
      icono_buscador = '<i class="fas fa-search"></i>';
      icono_buscando = '<i class="fas fa-gear fa-spin"></i>';
      icono_encontrado = '<i class="fas fa-check "></i>';
      icono_no_encontrado = '<i class="fas fa-search-minus"></i>';
      icono_editar = '<i class="fa-solid fa-rotate-left"></i>';



$(function() {

      // Desactivar todos los elementos del menú
      $(".menu").removeClass('active');
      $(".gastos").addClass('active');
      $(".gastos > .submenu").addClass('show');
      $("#gastos_crear_nuevo").addClass('active');
      

      $("#birds").keyup(function() {
         valor = $(this).val();
         if (valor === "") {
            $("#loading_cliente").hide();
            $("#icon_edit_client").empty().html(icono_editar);
         }
      });
      $("#birds").autocomplete({
         source: "<?php echo ENLACE_WEB; ?>mod_terceros/json/terceros_facturas.json.php?proveedor=1",
         delay: 300,
         minLength: 2,
         search: function() {
            // Muestra la animación de carga cuando inicia la búsqueda
            $("#loading_cliente").empty().fadeOut();
            $("#icon_edit_client").empty().html(icono_buscando);
         },
         response: function(event, ui) {
           
            // Oculta la animación de carga cuando termina la búsqueda y aparecen los resultados
            if (!ui.content || ui.content.length === 0) {
               // Si no hay resultados, cambia al ícono de "sin resultados"
               $("#loading_cliente").html('<i class="fas fa-exclamation-triangle"></i> No se encontraron resultados.').fadeIn();
               $("#icon_edit_client").empty().html(icono_no_encontrado);
            } else {
               // Oculta el ícono de carga cuando hay resultados
               $("#loading_cliente").hide();
               $("#icon_edit_client").empty().html(icono_editar);
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

<style>
   .ui-autocomplete {
      list-style-type: none;
      padding: 0;
      margin: 0;
   }

   .ui-menu-item {
      width: 100% !important;
   }

   .fixed-width {
      width: 100px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
   }

   .tabla_sin_borde {
      border-collapse: collapse;
      border: none;
   }
</style>