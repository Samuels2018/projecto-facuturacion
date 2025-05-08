<?php

SESSION_START();
require_once "../../conf/conf.php";

require_once ENLACE_SERVIDOR . 'mod_productos/object/productos.object.php';
require_once ENLACE_SERVIDOR . 'mod_stock/object/bodegas.object.php';

$disabled = "disabled='disabled'";
if ($_GET['accion'] == "modificar_productos_stock") {
    $disabled = "";
}

$productos = new Productos($dbh, $_SESSION['Entidad']);
$productos->fetch($_REQUEST['fiche']);

$bodega = new Bodegas($dbh , $_SESSION['Entidad'] );
$bodega->usuario = $_SESSION["usuario"];
$bodega->fetch($_SESSION['entidad'], 'fk_empresa', 1);

$bodegas = $bodega->obtener_bodegas($_SESSION['Entidad']);

$stock_bodegas = $bodega->obtener_stock_bodegas($_REQUEST['fiche']);

if (!empty($_POST['bodega']) and !empty($_REQUEST['fiche']) and !empty($_POST['valor']) and (
    ($_POST['tipo'] == 0 or $_POST['tipo'] == 1))) {

    require_once ENLACE_SERVIDOR . 'mod_productos/class/productos.funciones.php';
    movimiento_stock($usuario->id, $_POST, $productos);
}

?>

<input type="hidden" value="<?php echo $_REQUEST['fiche'] ?>" name="fiche" />

<?php

if ($productos->tipo == 1) { ?>

    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="card">
                    <!-- form start -->

                    <div class="card-body">
                        <form action="" method="post">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="bodega"><i class="fa fa-fw fa-shopping-cart"></i> Bodega</label>
                                    <select class="form-control" name="bodega" id="bodega">
                                        <?php

                                        foreach ($bodegas as $bodega) {
                              
                                            $selected = ($datos->fk_ubicacion_1 == $bodega->rowid) ? 'selected="selected"' : '';
                                            echo '<option value="' . $bodega->rowid . '" ' . $selected . '>' . $bodega->label . ' ' . $bodega->nota . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="tipo"><i class="fa fa-fw fa-user"></i> Movimiento</label>
                                    <select class="form-control" name="tipo_movimiento" id="tipo_movimiento">
                                        <option value="agregar">Agregar Inventario</option>
                                        <option value="eliminar">Eliminar Inventario</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="valor">Cantidad a Ajustar</label>
                                    <input class="form-control" type="number" name="valor" id="valor">
                                </div>
                                <div class="col-md-6">
                                    <label for="motivo">Motivo del Ajuste</label>
                                    <input class="form-control" name="motivo" id="motivo">
                                </div>
                            </div>

                            <div class="card-footer">
                                <a href="<?php echo ENLACE_WEB ?>productos_listado" class="btn btn-primary"><i class="fa fa-fw fa-circle"></i>Cancelar</a>
                                <button type="button" class="btn btn-primary" onclick="actualizarStock(event)"><i class="fa fa-fw fa-circle"></i>Guardar Cambios</button>
                            </div>
                        </form>
                    </div>
                </div><!-- /.card -->
            </div><!--/.col (left) -->
        </div> <!-- /.row -->

        </div>
        <!-- Grafico estatico -->
        
        <!-- -----------------------------------------------------------------------------------------   -->
        <div class="row mt-3">
            <div class="col-xs-12">
                <div class="card-body">
                    <div class="table-responsive">
                    <table class="table table-bordered">
                        <div class="mt-3 d-flex align-items-top">
                            <h5 class="mx-3">Stock por Bodega de: <?php echo $productos->label; ?> </h5>
                            <a id="boton_ver_mov" href="<?php echo ENLACE_WEB; ?>dashboard.php?accion=productos_movimientos&id_producto=<?php echo $productos->id; ?>&prod_label=<?php echo urlencode($productos->label); ?>" class="btn btn-outline-primary mx-3">
                                <i class="fas fa-list mr-2"></i> Ver Movimientos
                            </a>
                        </div>
                        <thead>
                        <tr>
                                <th>Nombre Bodega </th>
                                <th align="center">Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            foreach ($stock_bodegas as $stock) {
                                
                                echo "<tr>
        <td data-label='Nombre Bodega'>
            <A href='".ENLACE_WEB."dashboard.php?accion=ver_historico_stock&fiche=" . $stock->fk_producto . "&bodega=" . $stock->bodega_id . "'> 
                <i class=\"fas fa-store\"></i> <strong>" . $stock->label . "</strong> " . $stock->nota . "
            </A>
        </td>
        <td data-label='Stock' align='left'>
                <A href='".ENLACE_WEB."dashboard.php?accion=ver_historico_stock&fiche=" . $stock->fk_producto . "&bodega=" . $stock->bodega_id . "'> <strong>" . $stock->stock . " Unidades</strong></a>
        </td>
        </tr>";
                            }


                            ?>
                        </tbody>
                    </table>

                    </div>
                </div>
            </div>
        </div>
    
        </form>
    </section>

<?php } elseif ($productos->tipo == 2) { ?>

    <section class="content">

        <div class="row">
            <div class="col-md-12">
                <div class="card card-warning">
                    <div class="card-header">
                        <div class="alert alert-danger" role="alert">
                            No existe STOCK en SERVICIOS
                        </div>
                    </div>
                </div>
            </div>
        </div>



    </section>


<?php } ?>


 