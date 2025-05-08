<?php

SESSION_START();
require_once "../../conf/conf.php";

require_once(ENLACE_SERVIDOR . 'mod_productos/object/productos.object.php');
require_once(ENLACE_SERVIDOR . 'mod_productos/object/bodegas.object.php');

$disabled = "disabled='disabled'";
if ($_GET['accion'] == "modificar_productos_stock") {
    $disabled = "";
}

$productos = new Productos($dbh);
$productos->fetch($_REQUEST['fiche']);
$precioCosto = $productos->obtener_precio_costo($_REQUEST['fiche']);
$historico_costos = $productos->obtener_precios_costo($_REQUEST['fiche']);

$productos->fetch($_REQUEST['fiche']);

?>
<input type="hidden" value="<?php echo $_REQUEST['fiche'] ?>" name="fiche" />

<section class="content">

    <div class="row">
        <!-- left column -->
        <div class="col-md-6">
            <!-- general form elements -->
            <div class="card">
                <!-- form start -->
 <form method="POST" action="">
                <div class="card-body">

                   
                        <input type="hidden" value="<?php echo $productos->id; ?>" name="fk_producto" />

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="producto">Producto</label>
                                <input type="text" name="producto" id="producto" class="form-control" value="<?php echo $productos->label; ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="precio_costo">Precio Costo</label>
                                <input type="text" name="precio_costo" id="precio_costo" class="form-control" placeholder="Precio Costo" value="<?php echo $precioCosto->precio ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="impuesto_costo">Impuesto</label>
                                <select name="impuesto_costo" id="impuesto_costo" class="form-control">
                            
                                             <?php 
                                            $productos->Impuestos();
                                            print_r($productos->impuestos);
                                            foreach ( $productos->impuestos as $clave => $valor) {
                                                echo "<option value='".$valor['impuesto']."'>".$valor['impuesto']."% Incluido </option>";
                                            }
                                                ?>    
                                        </select>


                            </div>
                            <div class="col-md-6">
                                <label for="nota">Nota</label>
                                <input type="text" name="nota" id="nota" class="form-control" placeholder="Motivo o Nota Precio Costo" value="<?php echo $precioCosto->nota ?>">
                            </div>
                        </div>

                        <!-- Aquí puedes agregar más campos si es necesario -->

                        
                  
                </div><!-- /.card-body -->  
            
                <div class="card-footer mt-12" >

                      

                        <a href="<?php echo ENLACE_WEB ?>productos_listado"  class="btn btn-outline-primary" >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-box"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                                    Cancelar
                                </a>

                            
                                <button type="button" class="btn btn-primary" onclick="actualizarCosto(event)">Guardar Cambios</button>
                            
                        
                </div>
            </form>
            </div><!-- /.card -->
        </div><!--/.col (left) -->
    
    <?php if($precioCosto->precio): ?>
   
        <div class="col-md-6 mt-6">
            <?php require(ENLACE_SERVIDOR . "mod_utilidad/tpl/utilidad_producto.php"); ?>

        </div>
    </div>
    <?php endif; ?>

    </div>

    <div class="row mt-3">
        <div class="col-md-12">


            <div class="card">
                <div class="card-header">
                     Costos Historicos 
                </div><!-- /.card-header -->
                <div class="card-body">
                    <div class="table-responsive">
                    <table class="table table-bordered" role="alert" aria-live="polite" aria-relevant="all">
                        <thead>
                        <tr>
                                <th style="width: 10px">#</th>
                                <th>Fecha</th>
                                <th>Costo</th>
                                <th>Costo + Imp </th>
                                <th>Tipo</th>
                                <th>Nota</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                           
                            $i = 1;
                            foreach ($historico_costos as $historico) {
                                // Ahora puedes usar $historico como un objeto
                                if ($i == 1) {
                                    $actual = '<span class="badge bg-red">Costo Actual</span>';
                                } else {
                                    $actual = "";
                                }
                            
                                if ($historico->impuesto == "E") {
                                    $subtotal = $historico->precio;
                                    $impuesto = "Excento";
                                } else {
                                    $impuesto = "Impuesto Incluido";
                                    $subtotal = ($historico->precio * 100) / 113;
                                }
                            
                                echo "<tr><td data-label='#'>$i</td><td data-label='Fecha'>" . date('d-m-Y H:i', strtotime($historico->fecha)) . "</td>";
                                echo "<td data-label='Costo'>" . number_format(($subtotal), 2, ',', ' ') . "</td>";
                                echo "<td data-label='Costo + Imp'>" . number_format(($historico->precio), 2, ',', ' ') . "</td>";
                                echo "<td data-label='Tipo'>" . $impuesto . "</td>";
                                echo "<td data-label='Nota'>" . $historico->nota . "</td>";
                                echo "<td>" . $actual . "</td>";
                                echo "</tr>";
                            
                                $i++;
                            }

                            ?>
                        </tbody>
                    </table>
                    </div>
                   
                </div><!-- /.card-body -->
            </div>




        </div><!-- ./col -->
    </div><!-- /.row -->

</section>