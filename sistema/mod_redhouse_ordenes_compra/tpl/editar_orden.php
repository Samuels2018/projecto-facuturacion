<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<style>
.select2-selection__rendered{
    overflow:visible !important;
}
    
</style>

<script src="<?php echo ENLACE_WEB; ?>bootstrap/jquery.maskedinput.js"></script>
<script>
    $(".cotizaciones").addClass('active');
</script>
<?php

//------------------------------------------------------------------------------
//
//   Dbermejo@avancescr.com
//   8 de Abril 2024 - Proyecto Factura electronica España      
//


require_once ENLACE_SERVIDOR . 'mod_redhouse_ordenes_compra/object/order_compra_object.php';
require_once ENLACE_SERVIDOR . 'mod_terceros/object/terceros.object.php';
require_once ENLACE_SERVIDOR . 'mod_cotizaciones/object/pagos.object.php';
require_once ENLACE_SERVIDOR . 'mod_redhouse_proyecto/object/proyecto_object.php';
require(ENLACE_SERVIDOR . 'mod_productos/object/productos.object.php');

$productos = new Productos($dbh);
$productos->entidad = $_SESSION['Entidad'];
$productos->Impuestos();


$orden = new Orden($dbh, $_SESSION['Entidad']);
$proyecto = new redhouse_proyecto($dbh,  $_SESSION['Entidad']);
$listado_proyecto = $proyecto->listar_proyectos();


$tercero = new FiTerceros($dbh);
$pago = new Pago($dbh);
$disabled = "";

if (!empty($_GET['alerta'])) {
    $alerta = $_GET['alerta'];
}
if (empty($_POST['accion'])) {
    $_POST['accion'] = "";
}
if (!empty($_REQUEST['fiche'])) {
    $orden->fetch($_REQUEST['fiche']);

    if (intval($orden->entidad) !== intval($_SESSION['Entidad'])) {
        echo acceso_invalido();
        // ----------------------------------------------------
        // Hernan Castro
        // 03/10/2018
        // Finalizo trackeo
        // ----------------------------------------------------

        exit(1);
    }
}


require_once ENLACE_SERVIDOR . '/mod_terceros/object/terceros.object.php';
$cliente = new FiTerceros($dbh);
$cliente->entidad = $_SESSION['Entidad'];
$cliente->obtener_listado_terceros('proveedores');
$listado_categorias_cliente = $cliente->obtener_listado_categorias_clientes($_SESSION['Entidad']);
$proveedores = $cliente->obtener_listado_terceros;
$fiche = '';


if (empty($_REQUEST['fiche']))
{   
    $_REQUEST['fiche'] = $orden->nuevo();
    $orden->fetch($_REQUEST['fiche']);
} 
else if (!empty($_REQUEST['fiche'])) 
{
    $fiche = 'existe';
    $orden->fetch($_REQUEST['fiche']);
}


$bloqueos_acciones = false;
if (strpos($orden->orden_consecutivo, 'ORD') === 0) {
    $bloqueos_acciones = true;
} 


?>

<style type="text/css">
    .rezisable-item{
        resize: both !important;
    }
</style>


<form  id="formulario_orden" method="POST">

<div class="middle-content container-xxl p-0">
    <div class="">
        <div class="page-meta mb-4">
            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                    <li class="breadcrumb-item" aria-current="page">Orden de compra</li>
                    <?php 
                        if(!$bloqueos_acciones){
                    ?>
                    <li class="breadcrumb-item active" aria-current="page">Nueva Orden</li>
                    <?php }else{ ?>
                        <li class="breadcrumb-item active" aria-current="page">#<?php echo $orden->orden_consecutivo; ?></li>
                    <?php } ?>
                </ol>
            </nav>
        </div>


        <div class="col-md-12">
            <input type="hidden" name="fiche" value="<?php echo $_REQUEST['fiche'];  ?>" />

            <section class="content">

                <div class="row">

                    <div class="col-md-6">

                        <div class="card">
                            <div class="card-body">

                                <div class="form-group row">
                                    <label for="company-name" class="col-sm-3 col-form-label col-form-label-sm">Proveedor</label>
                                    <div class="col-sm-9">
                                        <select name="fk_proveedor"   id="fk_proveedor" class="form-control select2">
                                            <option value="">Seleccione</option>
                                            <?php 
                                                foreach($proveedores as $proveedor){

                                                    $selected = '';
                                                    if($proveedor->rowid == $orden->fk_proveedor)
                                                    {
                                                        $selected = 'selected';
                                                    }

                                            ?>
                                                <option <?php echo $selected; ?>  value="<?php echo $proveedor->rowid; ?>"><?php echo $proveedor->nombre_cliente; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="moneda" class="col-sm-3 col-form-label col-form-label-sm">Moneda</label>
                                    <div class="col-sm-9" id="lugar_campo_moneda">
                                        <?php $orden->monedas();  ?>
                                        <select <?php echo $disabled; ?>  class="form-select form-control-sm select2" name="fk_moneda" id="fk_moneda">
                                            <option value="">Seleccione</option>
                                            <?php foreach ($orden->diccionario_moneda as $moneda_id => $valor) { 
                                                
                                                $selected = '';
                                                if($moneda_id == $orden->fk_moneda)
                                                {
                                                    $selected = 'selected';
                                                }
                                                ?>
                                                
                                                
                                                <option  <?php echo $selected; ?> value="<?php echo $moneda_id; ?>" <?php echo ($orden->moneda == $moneda_id) ? 'selected' : ''; ?>><?php echo $valor['etiqueta']; ?></option>
                                            <?php }; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="forma_pago" class="col-sm-3 col-form-label col-form-label-sm">Forma Pago</label>
                                    <?php $orden->diccionario_pago();  ?>
                                    <div class="col-sm-9" id="lugar_campo_forma_pago">
                                        <select <?php echo $disabled; ?>  class="form-select form-control-sm select2" name="fk_forma_pago" id="fk_forma_pago">
                                            <option value="0">Seleccione</option>
                                            <?php foreach ($orden->diccionario_pago as $pagoid => $valor) {?>
                                                <option value="<?php echo $pagoid; ?>" <?php echo ($orden->fk_forma_pago == $pagoid) ? 'selected' : ''; ?>><?php echo $valor['label']; ?></option>
                                            <?php }; ?>
                                        </select>
                                    </div>
                                </div>

                                
                                <div class="form-group row">
                                    <label for="company-name" class="col-sm-3 col-form-label col-form-label-sm">Proyecto Asociado</label>
                                    <div class="col-sm-9">
                                        <select name="fk_proyecto"  id="fk_proyecto" class="form-control select2">
                                            <option value="">Seleccione</option>
                                            <?php 
                                                foreach($listado_proyecto as $key => $value)
                                                {
                                            ?>
                                                <option  <?php echo ($orden->fk_proyecto == $listado_proyecto[$key]->rowid) ? 'selected' : ''; ?>   value="<?php echo $listado_proyecto[$key]->rowid; ?>"><?php echo "(".$listado_proyecto[$key]->proyecto_consecutivo.") ".$listado_proyecto[$key]->proyecto_descripcion; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            

                            </div>
                        </div>


                    </div>
                    <div class="col-md-6">

                        <div class="card">
                            <div class="card-body">

                                <?php 
                                    if($bloqueos_acciones)
                                    {
                                        $disabled = 'disabled';
                                    }
                                ?>

                                <a href="javascript:void(0)">
                                    <button class="btn btn-danger btn-lg w-100 mb-2 _effect--ripple waves-effect waves-light">
                                        <i class="fa fa-fw fa-paperclip" aria-hidden="true"></i><b><?php echo $orden->orden_consecutivo; ?></b>                                    </button>
                                </a>

                                <div class="form-group row">
                                    <label for="TIPO_CAMBIO" class="col-sm-3 col-form-label col-form-label-sm">Emisión</label>
                                    <div class="col-sm-9" id="lugar_campo_fecha_creacion">
                                        <input autocomplete="off"  <?php echo $disabled; ?>  type="text" class="form-control form-control-sm datepicker-dinamico" name="fecha_creacion" value="<?php echo $orden->fecha_creacion; ?>" >
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="TIPO_CAMBIO" class="col-sm-3 col-form-label col-form-label-sm">Vigencia</label>
                                    <div class="col-sm-9" id="lugar_campo_fecha_vigencia">
                                        <input autocomplete="off" <?php echo $disabled; ?>  type="text" class="form-control form-control-sm datepicker-dinamico" name="fecha_vigencia" value="<?php echo $orden->fecha_vigencia; ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="order_notas" class="col-sm-3 col-form-label col-form-label-sm">Nota de la orden</label>
                                    <div class="col-sm-9">
                                        <textarea rows="5" name="order_notas" id="order_notas" class="form-control"><?php echo $orden->orden_notas; ?></textarea>
                                    </div>
                                </div>

                                <div class="form-group row" style="margin-top:15px;">
                                    <label for="order_estado" class="col-sm-3 col-form-label col-form-label-sm">Estado</label>
                                    <div class="col-sm-9">
                                        <select   class="form-select form-control-sm " name="order_estado" id="order_estado">
                                            <option <?php if($orden->orden_estado === 1){ echo 'selected'; } ?> value="1">Pendiente</option>
                                            <option <?php if($orden->orden_estado === 2){ echo 'selected'; } ?> value="2">Procesado</option>
                                            <option <?php if($orden->orden_estado === 3){ echo 'selected'; } ?> value="3">Completado</option>
                                            <option <?php if($orden->orden_estado === 4){ echo 'selected'; } ?> value="4">Cancelado</option>
                                        </select>
                                    </div>
                                </div>

                            </div>

                    </div>
                </div> <!-- .row --->



                <!--------------------------------------------------------------------------------------------->
                <?php

                if (empty($_REQUEST['fiche'])) {
                    $class = "display:none!important;";
                } else {
                    $class = "";
                }
                ?>

                <div class="row mt-3" style="">
                    <div class="col-xs-12">
                        <div class="card" style="padding:25px;">


        <div class="tab-servicios" id="servicios-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
            <?php 
                if(!$bloqueos_acciones){
            ?>    
            <button type="button" style="cursor:pointer;float:right;margin-bottom:20px;" class="btn btn-primary pull-right serviciomodal" data-bs-toggle="modal" data-bs-target="#serviceModal">
                  <i class="fa fa-plus" aria-hidden="true"></i>Agregar Articulo
                </button>
                <?php } ?>

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
                      <?php 
                if(!$bloqueos_acciones){
                ?>
                      <th></th>
                <?php } ?>
                    </tr>

                  </thead>
                  <tbody id="tabla_servicios">
                    <?php include_once(ENLACE_SERVIDOR . "mod_redhouse_ordenes_compra/ajax/listado_servicios.ajax.php"); ?>
                  </tbody>
                </table>
              </div>
                        
                        </div><!-- /.card -->
                    </div>
                </div>
                <div class="row mt-3">

                    <div class="col-xs-12">
                        <a href="<?php echo ENLACE_WEB; ?>redhouse_ordenes_compra" class="btn btn-outline-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-box">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                <line x1="12" y1="22.08" x2="12" y2="12"></line>
                            </svg>
                            Volver al Listado
                        </a>
                       
                        
                        <div class="btn-group" role="group">
                            <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle _effect--ripple waves-effect waves-light show" data-bs-toggle="dropdown" aria-expanded="true">
                                Opciones Avanzadas
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down">
                                    <polyline points="6 9 12 15 18 9"></polyline>
                                </svg>
                            </button>
                            <ul class="dropdown-menu hide++" aria-labelledby="btnGroupDrop1" data-popper-placement="top-start" data-popper-reference-hidden="" style="position: absolute; inset: auto auto 0px 0px; margin: 0px; transform: translate3d(0px, -41.129px, 0px);">
                            <?php 
                                if(!$bloqueos_acciones)
                                    {
                                ?>     

                                <li><a class="dropdown-item" href="#" onclick="confirmar_eliminar(500)"><i class="fa fa-fw fa-trash-o" aria-hidden="true"></i>Eliminar Borrador</a></li>
                                <li class="divider"></li>
                                <li><a class="dropdown-item" href="#" onclick="guardar_orden()"><i class="fa fa-fw fa-check-circle-o" aria-hidden="true"></i> Grabar Orden de compra.</a></li>
                                <?php } ?>
                                
                                <li><a class="dropdown-item" href="<?php echo ENLACE_WEB; ?>mod_redhouse_ordenes_compra/class/orden_pdf.php?ord=<?php echo $orden->rowid; ?>" download="<?php echo $orden->orden_consecutivo; ?>.pdf">Descargar PDF </a>
                                </li>
                                <li> <a class="dropdown-item" target="_blank" href="<?php echo ENLACE_WEB; ?>mod_redhouse_ordenes_compra/class/orden_pdf.php?ord=<?php echo $orden->rowid; ?>" >Abrir PDF </a></li>
                            </ul>
                            </div>


                      

                    </div><!-- col -->
                </div><!-- row -->


        </div>
    </div>
                            </form>


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

<style>
.ui-autocomplete {
    z-index: 99999999999999 !important; /* O un valor más alto que el de la máscara */
}

</style>

<script>
  $(function() {


    $('.select2').select2({
        placeholder: 'Seleccione una opción',
        allowClear: true,
        width: '100%' // Ajustar el ancho del select2
    });


    $('.datepicker-dinamico').datepicker({
        dateFormat: 'yy-mm-dd', // Formato de año-mes-día
        changeMonth: true,      // Permitir cambiar el mes
        changeYear: true,       // Permitir cambiar el año
        showButtonPanel: true,  // Mostrar botones de navegación
        yearRange: '1900:2100', // Rango de años seleccionables
    });


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


    //Funcion para guardar servicio
    function guardar_servicio()
    {
 
    
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
        url: "<?= ENLACE_WEB ?>mod_redhouse_ordenes_compra/class/class.php",
        beforeSend: function(xhr) {},
        data: {
            action: 'guardar_servicio',
            fk_orden                 : <?php echo $orden->rowid; ?>,
            servicio_precio_tipo_impuesto : $("#servicio_precio_tipo_impuesto").val(),
            servicio_precio_unitario      : $("#servicio_precio_unitario").val(),
            servicio_cantidad             : $("#servicio_cantidad").val(),
            servicio_comentario           : $("#servicio_comentario").val(),
            servicio_fk_producto          : $("#servicio_fk_producto").val(),
            servicio_cantidad_dias        : $("#cantidad_dias").val(),
            servicio_tipo_duracion        : $("#tipo_duracion").val(),

        },
        }).done(function(data) {
        console.log(data);
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


    function listar_servicios(int)
    {

        // traer tpl
        $.ajax({
        method: "POST",
        url: "<?= ENLACE_WEB ?>mod_redhouse_ordenes_compra/ajax/listado_servicios.ajax.php?fiche=<?php echo $orden->rowid; ?>",
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
            url: "<?= ENLACE_WEB ?>mod_redhouse_ordenes_compra/ajax/editarServicio.php",
            beforeSend: function(xhr) {},
            data: {
                action: 'editarServicio',
                rowid: int,
                fk_cotizacion:'<?php echo $orden->rowid; ?>',
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
                    url: "<?= ENLACE_WEB ?>mod_redhouse_ordenes_compra/class/class.php",
                    beforeSend: function(xhr) {},
                    data: {
                    action: 'remover_servicio',
                    fk_orden                 : <?php echo $orden->rowid; ?>,
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



    function actualizar_informacion(){
            error = false;
                $.ajax({
                    method: "POST",
                    url: "<?php echo ENLACE_WEB; ?>mod_redhouse_ordenes_compra/class/class.php",
                    beforeSend: function(xhr) {
                        // Puedes agregar un indicador de carga aquí si lo deseas
                    },
                    data: {
                        "action": "actualizar_informacion_orden",
                        data: $("#formulario_orden").serialize(),
                    },
                }).done(function(data) {
                    console.log(data);  // Para ver la respuesta en la consola
                    const response = JSON.parse(data);

                    add_notification({
                        text: response.mensaje_txt,
                        pos: 'top-right',
                        actionTextColor: '#fff',
                        backgroundColor: response.error === 0 ? '#28A745' : '#DC3545'
                    });

                    if (parseInt(response.id) > 0) {
                        window.location.href = '<?php echo ENLACE_WEB; ?>redhouse_ordenes_compra_detalle/' + response.id;
                    }
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    console.error("Error en la petición AJAX:", textStatus, errorThrown);

                    add_notification({
                        text: 'Hubo un error al agregar la exoneracion.',
                        actionTextColor: '#fff',
                        backgroundColor: '#e7515a'
                    });


                });


    }// fin de la funcion 


    
    function guardar_orden(){
            error = false;
                $.ajax({
                    method: "POST",
                    url: "<?php echo ENLACE_WEB; ?>mod_redhouse_ordenes_compra/class/class.php",
                    beforeSend: function(xhr) {
                        // Puedes agregar un indicador de carga aquí si lo deseas
                    },
                    data: {
                        "action": "guardar_orden",
                        data: $("#formulario_orden").serialize(),
                    },
                }).done(function(data) {
                    console.log(data);  // Para ver la respuesta en la consola
                    const response = JSON.parse(data);

                    add_notification({
                        text: response.mensaje_txt,
                        pos: 'top-right',
                        actionTextColor: '#fff',
                        backgroundColor: response.error === 0 ? '#28A745' : '#DC3545'
                    });

                    if (parseInt(response.id) > 0) {
                        window.location.href = '<?php echo ENLACE_WEB; ?>redhouse_ordenes_compra_detalle/' + response.id;
                    }
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