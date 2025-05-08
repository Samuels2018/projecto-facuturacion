<?php
SESSION_START();
require_once "../../conf/conf.php";

  //LA ENTIDAD DEL USUARIO EN SESION
  if(empty( $_SESSION['usuario']) or empty($_SESSION['Entidad']))
  {
    echo  acceso_invalido() ;
    exit(1);
  }




require(ENLACE_SERVIDOR . 'mod_productos/object/productos.object.php');
require(ENLACE_SERVIDOR . 'mod_usuarios/object/usuarios.object.php');



$productos = new Productos($dbh, $_SESSION['Entidad']);
$productos->fetch($_REQUEST['fiche']);
$precioVenta = $productos->venta($_REQUEST['fiche']);

// var_dump($productos->impuestos);

$monedas = $productos->diccionario_moneda();

foreach ($monedas as $item) {
   $options.= "<option value='$item->rowid'>$item->etiqueta</option>";
}


$productos_lista_precio = $productos->obtener_resumen_lista_precio($_REQUEST['fiche']);

$producto_listas_politicas = $productos->obtener_lista_politicas($_REQUEST['fiche']);



foreach ($productos_lista_precio as $lista) {

    $listas_precios_resumen .= "<tr>
            <td data-label='Precio'>" . (!empty($lista->total) ? numero_simple($lista->total) : "No configurado") . "</td>
            <td data-label='% Utilidad'>" . (isset($lista->porcentaje_utilidad) ? $lista->porcentaje_utilidad."%" : "No configurado") . "</td>
            <td data-label='% Descuento'>" . (isset($lista->porcentaje_descuento) ? $lista->porcentaje_descuento."%" : "No configurado") . "</td>
                        <td data-label='Historico'>
                        <button type='button' onclick='ver_log_precios(" . (isset($lista->fk_lista) ? $lista->fk_lista : 0) . ")' class='btn btn-primary'><i class='fa fa-book' aria-hidden='true'></button></i>
                        </td>
            
        </tr>";
}


foreach ($producto_listas_politicas as $politica) {

    $producto_politica_descuento .= "<tr onclick='ver_politica(" .$politica->rowid. ")'>
            <td data-label='Cantidad'>" .($politica->cantidad) . " " .($politica->tipo) . "</td>
            <td data-label='Porcentaje'>" .$politica->porcentaje_descuento. "%</td>
            
         
        </tr>";
}

?>


<form role="form" method="POST" action="" id="form_precio">

    <input type="hidden" value="<?php echo $_REQUEST['fiche'] ?>" name="fiche" />

    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <form action="tu_archivo_de_procesamiento.php" method="post">
                <div class="card">
                    <!-- form start -->

                    <div class="card-body">
                      
                            <div class="row mb-3">
                               
                                <div class="col-md-6">
                                    <label for="ref"><b>Referencia:</b> <?php echo $productos->ref; ?></label><br>
                                    <label for="etiqueta"><b>Etiqueta:</b> <?php echo $productos->label; ?></label>

                                    <input type="text" name="ref" id="ref" class="form-control d-none" value="<?php echo $productos->ref; ?>">
                                </div>
                            </div>

                            <div class="row mb-3 d-none">
                                <div class="col-md-6">
                                    <input type="text" name="etiqueta" id="etiqueta" class="form-control" value="<?php echo $productos->label; ?>">
                                </div>
                             
                            </div>

                            <div class="row mb-3">

                            <div class="col-md-3 d-none" style="margin-top:-6px;">
                             <label for="moneda" class="col-sm-12 col-form-label">Moneda</label>
                                <div id="unidad_moneda"   class="input-group">
                                    <select name="moneda" id="moneda" class="form-control">
                                      <?=$options?>
                                    </select>
                                    <button onclick="ver_moneda();" class="btn btn-success" type="button" aria-expanded="false">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings">
                                            <circle cx="12" cy="12" r="3"></circle>
                                            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                                <div class="col-md-3 d-none">
                                    <label for="precioBase">% Porcentaje utilidad</label>
                                    <input class="form-control" type="number" step="any" name="porcentaje_utilidad" id="porcentaje_utilidad" value="">
                                </div>

                                <div class="col-md-3 d-none">
                                    <label for="precioBase"> Porcentaje descuento</label>
                                    <input class="form-control" type="number" step="any" name="porcentaje_descuento" id="porcentaje_descuento" value="">
                                </div>


                                <div class="col-md-3">
                                    <label for="precioBase"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg> Precio Base</label>
                                    <input class="form-control" type="number" step="any" name="precio_base" value="<?php echo numero_simple_coma($precioVenta->subtotal); ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="tipo">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-columns"><path d="M12 3h7a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-7m0-18H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h7m0-18v18"></path></svg>
                                    Tipo de Precio</label>
                                    <select class="form-control" name="tipo_impuesto">
                                        <option value="restar" <?php if ($precioVenta->impuesto == 1) {
                                                                    echo "selected";
                                                                } ?>>Precio IVA Incluido</option>
                                        <option value="sumar" <?php if ($precioVenta->impuesto == 2) {
                                                                    echo "selected";
                                                                } ?>>Precio sin IVA incluido</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="impuesto"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg> 
                                         Impuesto a Aplicar</label>
                                            <span></span>
                                            <select class="form-control" name="impuesto" id="impuesto"  >
                                            <?php 
                                            $productos->Impuestos();
                                       //     print_r($productos->impuestos);
                                            foreach ( $productos->impuestos as $clave => $valor) {
                                                echo "<option value='".$valor['impuesto']."' ". (($productos->impuesto_fk==$valor['impuesto'])? 'selected="selected"':"")." >".$valor['impuesto']."% - ".$valor['impuesto_texto']."</option>";
                                            }
                                                ?>    
                                        </select>
                                </div>


                                <div class="col-md-3 d-none" style="margin-top:-9px;">
                                    <label for="impuesto" class="col-sm-12 col-form-label">Lista de precio</label>
                                    <div id="lista_precios_div"   class="input-group" >
                                        
                                                    <select class="form-control" name="listas_precios" id="listas_precios">
                                                    <option value=''>Seleccione</option>;
                                                    <?php 
                                                    $productos->listas_precios();
                                            //     print_r($productos->lista_precio);
                                                    foreach ( $productos->lista_precio as $clave => $valor) {
                                                        echo "<option value='".$valor['rowid']."'>".$valor['etiqueta']."</option>";
                                                    }
                                                        ?>    
                                                </select>
                                                
                                                <button onclick="ver_lista_precios();" class="btn btn-success" type="button" aria-expanded="false">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings">
                                                        <circle cx="12" cy="12" r="3"></circle>
                                                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                                                    </svg>
                                                </button>
                                        </div>

                                </div>


                            </div>

                         

                        
                       
                    </div><!-- /.card-body -->
                    <div class="card-footer mt-12" >
                                <a href="<?php echo ENLACE_WEB ?>productos_listado"  class="btn btn-outline-primary" >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-box"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                                    Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary" id="boton-precio" onclick="actualizarPrecio(event);"><i class="fa fa-fw fa-circle"></i>Guardar Cambios</button>
                            </div>

                </div><!-- /.card -->
                
            </form>

            </div> <!-- /.row -->
        </div>


        <div class="row mt-3">


        <div class="col-md-7">
                <div class="card">
                    <div class="card-header">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-pie-chart"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path><path d="M22 12A10 10 0 0 0 12 2v10z"></path></svg> 
                    Lista de precios
                    </div><!-- /.card-header -->
                    <!-- form start -->

                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Precio</th>
                                        <th>% Utilidad</th>
                                        <th>% Descuento</th>
                                        <th>Historico</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  <?=$listas_precios_resumen?> 
                                </tbody>
                            </table>
                        </div>


                    </div>

                </div>
            </div>
<?php /*
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-pie-chart"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path><path d="M22 12A10 10 0 0 0 12 2v10z"></path></svg> 
                    Politicas de descuento
                    </div><!-- /.card-header -->
                    <!-- form start -->

                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Monto o cantidad vendida </th>
                                     
                                        <th>% Descuento</th>
                                   
                                    </tr>
                                </thead>
                                <tbody>
                                <?=$producto_politica_descuento?> 
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer mt-12" >
                              
                                <button type="button" class="btn btn-primary" id="boton-precio" onclick="ver_politica();"><i class="fa fa-fw fa-circle"></i>Agregar politica</button>
                            </div>


                    </div>

                </div>
            </div>

            */
?>
       


    
         
        </div>

        <div class="row mt-3" id="div_historico" style="display: none;">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-pie-chart"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path><path d="M22 12A10 10 0 0 0 12 2v10z"></path></svg> 
                     Hist&oacute;rico de Precios
                    </div><!-- /.card-header -->
                    <!-- form start -->

                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Fecha </th>
                                         <th>Precio Base</th>
                                        <th>Impuesto</th>
                                        <th>Total</th>
                                        <th>Usuario Creo Precio</th>

                                    </tr>
                                </thead>
                                <tbody id="tbody_historico_precio">

                              
                                </tbody>
                            </table>
                        </div>


                    </div>

                </div>
            </div>
        </div>


       


    </section>


</form>

