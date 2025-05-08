<?php
require ENLACE_SERVIDOR . 'mod_productos/object/productos.object.php';
$productos = new Productos($dbh, $_SESSION['Entidad']);
$disabled = 'disabled="disabled"';
$texto_informativo = "Nuevo Producto U Servicio";
$editar = false;


if (!empty($_REQUEST['fiche'])) {
   $productos->fetch($_REQUEST['fiche']);
   if (intval($productos->entidad) !== intval($_SESSION['Entidad'])) {
      echo acceso_invalido();
      exit(1);
   }
}

if (!empty($_POST) and (!empty($_REQUEST['fiche'])) and ($_POST['editar'] == "true")) {
} else if (!empty($_REQUEST['fiche']) and $_REQUEST['action'] == "modificar") {
   $disabled = "";
   $productos->fetch($_REQUEST['fiche']);
   $editar = true;
} else if (!empty($_REQUEST['fiche'])) {
   $productos->fetch($_REQUEST['fiche']);
   $texto_informativo =  $productos->ref;
} else {
   // supongo que simplemente estoy creando esto!
   $disabled = "";
}
$categorias = $productos->obtener_categorias();



?>
<div class="middle-content container-xxl p-0">
   <!-- BREADCRUMB -->
   <div class="page-meta">
      <nav class="breadcrumb-style-one" aria-label="breadcrumb">
         <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo ENLACE_WEB; ?>productos_listado">Productos</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo !empty($_REQUEST['fiche']) ? 'Editar ' . $texto_informativo : 'Nuevo' ?></li>

         </ol>
      </nav>
   </div>
   <!-- /BREADCRUMB -->
   <!-- CONTENT AREA -->
   <div class="row layout-top-spacing">
      <div class="col-md-12">
         <!-- Contenido -->
         <section class="content">
            <div>
               <form role="form" method="POST" action="" id="formulario">
                  <input type="hidden" name="fiche" value="<?php echo $_REQUEST['fiche'] ?>">
                  <div class="simple-pill">
                     <?php if (!empty($_REQUEST['fiche'])) : ?>
                        <ul class="nav nav-pills mb-3" id="pills-tab-1" role="tablist">
                           <li class="nav-item" role="presentation">
                              <button class="nav-link <?php echo $_GET['tab'] != 'stock' ? 'active' : '' ?>" id="active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-producto" type="button" role="tab" aria-controls="pills-home" aria-selected="<?php echo $_GET['tab'] != 'stock' ? 'true' : 'false' ?>">Ficha Producto Servicio</button>
                           </li>
                           <li class="nav-item" role="presentation">
                              <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-clientes" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Precio Clientes</button>
                           </li>
                           <!--  <li class="nav-item" role="presentation">
                           <button class="nav-link" id="pills-costo-tab" data-bs-toggle="pill" data-bs-target="#pills-costo" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">Costo</button>
                        </li> -->

                           <li class="nav-item" role="presentation">
                              <button class="nav-link" id="pills-imagenes-tab" data-bs-toggle="pill" data-bs-target="#pills-imagenes" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">Imagenes</button>
                           </li>

                           <li class="nav-item" role="presentation">
                              <button class="nav-link <?php echo $_GET['tab'] == 'stock' ? 'active' : '' ?>" id="pills-stock-tab" data-bs-toggle="pill" data-bs-target="#pills-stock" type="button" role="tab" aria-controls="pills-contact" aria-selected="<?php echo $_GET['tab'] == 'stock' ? 'true' : 'false' ?>">Stock</button>
                           </li>

                        </ul>
                     <?php endif; ?>
                     <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade  <?php echo $_GET['tab'] != 'stock' ? 'show active' : '' ?>" id="pills-producto" role="tabpanel" aria-labelledby="pills-producto-tab" tabindex="0">
                           <!------------------Ventana Aviso de Edicion ---------------------------->
                           <?php if ($editar) { ?>
                              <div class="alert alert-arrow-right alert-icon-right alert-light-primary mb-4" role="alert">
                                 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-triangle">
                                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                                    <line x1="12" y1="9" x2="12" y2="13"></line>
                                    <line x1="12" y1="17" x2="12" y2="17"></line>
                                 </svg>
                                 <h4>Atención!</h4>
                                 <p>Se encuentra editando los datos del producto, esta sección te permite editar los nombres tal y como apareceran en la factura, cambiar su stock y las bodegas en que se encuentra.</p>
                              </div>
                           <?php } ?>
                           <!----------------------------fin ventana edicion ----------------------->
                           <div class="row">
                              <!-- left column -->
                              <div class="col-md-10">
                                 <!-- general form elements -->
                                 <div class="card">
                                    <div class="card-body">
                                       <div>

                                          <div class="row mb-3">
                                             <div class="col-md-6">
                                                <label for="ref"><i class="fas fa-code"></i> Referencia Interna</label>
                                                <input required="required" type="text" name="ref" id="ref" class="form-control" value="<?php echo $productos->ref; ?>" <?php echo $disabled; ?>>
                                             </div>
                                             <div class="col-md-6">
                                                <label for="label"><i class="fa fa-fw fa-asterisk"></i> Nombre o Etiqueta <small>(Dato Visible en la Factura)</small></label>
                                                <input required type="text" name="label" id="label" class="form-control" value="<?php echo $productos->label; ?>" <?php echo $disabled; ?>>
                                             </div>
                                          </div>
                                          <div class="row mb-3">
                                             <div class="col-md-6">
                                                <label for="label"><i class="fa fa-fw fa-circle-info"></i> Detalle Producto</label>
                                                <textarea class="form-control" name="descripcion" id="descripcion" rows="5" <?= $disabled ?> placeholder="Detalle"><?= $productos->descripcion ?></textarea>
                                             </div>
                                             <div class="col-md-6">
                                                <label for="codigo_barras"><i class="fa fa-fw fa-barcode"></i> Código Barras</label>
                                                <input type="text" name="codigo_barras" id="codigo_barras" class="form-control" value="<?php echo $productos->codigo_barras; ?>" <?php echo $disabled; ?>>
                                             </div>
                                             <div class="col-md-6 d-none">
                                                <label for="label"><i class="fa fa-fw fa-hashtag"></i> CONART</label>
                                                <input type="text" name="conart" id="conart" class="form-control" value="<?php echo $productos->conart; ?>" <?php echo $disabled; ?>>
                                             </div>
                                          </div>
                                          <div class="row mb-3">
                                             <div class="col-md-6">
                                                <label for="tipo">Tipo</label>

                                                <select required onchange="cargar_unidades(this.value)" class="form-control" name="tipo" id="tipo" <?php echo $disabled; ?> value="<?php echo $productos->tipo; ?>">
                                                   <option value="1" <?php echo ($productos->tipo == 1 or (empty($_REQUEST['fiche']) and $_GET['accion'] == "productos_nuevo")) ? 'selected="selected"' : '' ?>>Producto</option>
                                                   <option value="2" <?php echo ($productos->tipo == 2 or (empty($_REQUEST['fiche']) and $_GET['accion'] == "servicios_nuevo")) ? 'selected="selected"' : '' ?>>Servicio</option>
                                                </select>

                                             </div>
                                             <div class="col-md-6">
                                                <label for="unidad" class="col-sm-12 col-form-label">Unidad</label>
                                                <div id="unidad_selector" class="input-group">
                                                   <select required class="form-control" name="unidad" id="unidad" <?php echo $disabled; ?>>
                                                      <option value=''>Seleccione</option>
                                                      <?php
                                                      $tipoItem = ($productos->tipo == 1 or $_GET['accion'] == "productos_nuevo") ? '1' : '2';



                                                      $unidades = $productos->obtener_unidades_catalogo($tipoItem);
                                                      //echo json_encode($productos);die();
                                                      foreach ($unidades as $unidad) {
                                                         $selected = ($unidad->codigo == $productos->unidad) ? 'selected="selected"' : '';
                                                         echo '<option ' . $selected . ' value="' . $unidad->codigo . '">' . $unidad->detalle . '</option>';
                                                      }
                                                      ?>
                                                   </select>
                                                   <button <?= $disabled ?> onclick="ver_unidad();" class="btn btn-success" type="button" aria-expanded="false">
                                                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings">
                                                         <circle cx="12" cy="12" r="3"></circle>
                                                         <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                                                      </svg>
                                                   </button>
                                                </div>
                                             </div>
                                          </div>

                                       <?php 

                                          if ($productos->tosell != 1 && $productos->tobuy != 1)
                                          {
                                             $productos->tosell = 1;
                                          }

                                       ?>

                                          <div class="row mb-3">
                                             <div class="col-md-6">
                                                <label for="stock_minimo_alerta">Alerta Inventario mínimo</label>
                                                <input type="text" name="stock_minimo_alerta" id="stock_minimo_alerta" class="form-control" value="<?php echo (empty($productos->stock_minimo_alerta)  ? 0 : $productos->stock_minimo_alerta); ?>" <?php echo $disabled; ?>>
                                             </div>
                                             <div class="col-md-6">
                                                <div class="row h-100 align-items-end">
                                                   <div class="col-md-4">
                                                      <div class="switch form-switch-custom switch-inline form-switch-primary w-100">
                                                         <input <?php echo $disabled; ?> class="switch-input" type="checkbox" role="switch" id="tosell" name="tosell" value="<?php echo $productos->tosell; ?>" <?php echo ($productos->tosell == 1) ? 'checked' : ''; ?>>
                                                         <label class="switch-label small " for="tosell">En Venta</label>
                                                      </div>
                                                   </div>
                                                   <div class="col-md-4">
                                                      <div class="switch form-switch-custom switch-inline form-switch-primary w-100">
                                                         <input <?php echo $disabled; ?> class="switch-input" type="checkbox" role="switch" id="tobuy" name="tobuy" value="<?php echo $productos->tobuy; ?>" <?php echo ($productos->tobuy == 1) ? 'checked' : ''; ?>>
                                                         <label class="switch-label small " for="tobuy">En Compra</label>
                                                      </div>
                                                   </div>
                                                   <?php if ($Entidad->retencion == 1 or $productos->impuesto_retencion) { ?>
                                                      <div class="col-md-4">
                                                         <div class="switch form-switch-custom switch-inline form-switch-primary w-100">
                                                            <input <?php echo $disabled; ?> class="switch-input" type="checkbox" role="switch" id="impuesto_retencion" name="impuesto_retencion" value="<?php echo $productos->impuesto_retencion; ?>" <?php echo ($productos->impuesto_retencion == 1) ? 'checked' : ''; ?>>
                                                            <label class="switch-label small" for="impuesto_retencion">Ret. IRPF</label>
                                                         </div>
                                                      </div>
                                                   <?php } ?>


                                                </div>
                                             </div>

                                          </div>

                                          <div class="row mb-3">
                                             <div class="col-md-6">
                                                <label for="diccionario_1" class="col-sm-12 col-form-label">
                                                   <i class="fa fa-fw fa-folder"></i> Impuesto IVA
                                                </label>
                                                <div class="input-group">
                                                   <select required class="form-control" name="impuesto_fk" id="impuesto_fk" <?php echo $disabled; ?>>
                                                      <option value="">Selecciona</option>
                                                      <?php
                                                      $productos->Impuestos();
                                                      foreach ($productos->impuestos as $clave => $valor) {
                                                         echo "<option value='" . $valor['impuesto'] . "' " . (($productos->impuesto_fk == $valor['impuesto']) ? 'selected="selected"' : "") . " >" . $valor['impuesto'] . "% - " . $valor['impuesto_texto'] . "</option>";
                                                      }
                                                      ?>
                                                   </select>
                                                </div>
                                             </div>
                                             <div class="col-md-6">
                                                <label for="descuento_maximo" class="col-sm-12 col-form-label"> <i class="fas fa-tags"></i> Descuento m&aacute;ximo</label>
                                                <input type="number" class="form-control" name="descuento_maximo" id="descuento_maximo" value="<?php echo $productos->descuento_maximo; ?>" <?php echo $disabled; ?>>
                                             </div>
                                          </div>


                                          <div class="row mb-3">
                                             <div class="col-md-6">
                                                <label for="diccionario_1" class="col-sm-12 col-form-label">
                                                   <i class="fa fa-fw fa-folder"></i> Categoría Producto
                                                </label>
                                                <div class="input-group">
                                                   <select onchange="cargar_subcategorias_producto(this.value);" class="form-control" name="diccionario_1" id="diccionario_1" <?php echo $disabled; ?>>
                                                      <option value="0">No aplica</option>
                                                      <?php
                                                      foreach ($categorias as $categoria) {
                                                         $selected = ($productos->diccionario_1 == $categoria->rowid) ? 'selected="selected"' : '';
                                                         echo '<option value="' . $categoria->rowid . '" ' . $selected . '>' . $categoria->label . '</option>';
                                                      }
                                                      ?>
                                                   </select>
                                                   <button <?= $disabled ?> onclick="ver_categoria();" class="btn btn-success" type="button" aria-expanded="false">
                                                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings">
                                                         <circle cx="12" cy="12" r="3"></circle>
                                                         <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                                                      </svg>
                                                   </button>
                                                </div>
                                             </div>
                                             <div class="col-md-6">
                                                <label for="subcategoria_producto" class="col-sm-12 col-form-label"><i class="fa fa-fw fa-folder"></i> Sub Categoría</label>
                                                <select class="form-control" name="subcategoria_producto" id="subcategoria_producto" <?php echo $disabled; ?>>
                                                   <option value="0">Seleccione</option>
                                                </select>
                                             </div>
                                          </div>
                                          <div class="row mb-3">
                                             <div class="col-md-6">
                                                <label for="notas"><i class="fa fa-fw fa-folder-o"></i> Nota Interna</label>
                                                <textarea class="form-control" name="notas" id="notas" rows="6" placeholder="Detalle" <?php echo $disabled; ?>><?php echo $productos->notas; ?></textarea>
                                             </div>
                                          </div>



                                          </div>
                                                <div class="card-footer mt-12">
                                                   <?php
                                                   if (empty($_REQUEST['fiche'])) { ?>
                                                      <a href="<?php echo ENLACE_WEB; ?>productos_listado" class="btn btn-primary"><i class="fa fa-fw fa-circle"></i>Cancelar</a>
                                                      <button type="button" class="btn btn-primary" onclick="crearProducto(event)"><i class="fa fa-fw fa-circle"></i>Crear Producto o Servicio</button>
                                                   <?php }
                                                   if (!empty($_REQUEST['fiche']) and $_GET['action'] !== "modificar") { ?>
                                                      <a href="<?php echo ENLACE_WEB; ?>productos_listado" class="btn btn-outline-primary">
                                                         <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-box">
                                                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                                            <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                                         </svg>
                                                         Volver al Listado
                                                      </a>
                                                      <a href="#" onclick="confirma_eliminar_producto('<?php echo $_REQUEST['fiche']; ?>')" class="btn btn-danger  bs-tooltip " data-bs-placement="left" title="Tooltip on left"><i class="fa fa-fw fa-trash"></i> Eliminar </a>
                                                      <a href="<?php echo ENLACE_WEB; ?>dashboard.php?accion=productos_editar&action=modificar&fiche=<?php echo $_REQUEST['fiche']; ?>" class="btn btn-success"><i class="fa fa-fw fa-edit"></i> Modificar</a>
                                                   <?php } ?>
                                                   <?php if ($_REQUEST['action'] == "modificar" and !empty($_REQUEST['fiche'])) { ?>
                                                      <a href="<?php echo ENLACE_WEB; ?>dashboard.php?accion=productos_editar&fiche=<?php echo $_REQUEST['fiche']; ?>" class="btn btn-outline-primary"><i class="fa fa-fw fa-circle"></i>Cancelar modificación de <?php echo $productos->tipo_texto; ?></a>
                                                      <button type="button" name="editar" value="true" class="btn btn-primary" onclick="actualizarProducto(event)">
                                                         <i class=" fa fa-fw fa-circle"></i>Guardar Cambios <?php echo $productos->tipo_texto; ?>
                                                      </button>
                                                   <?php } ?>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
               <?php
               //INICIO CONDICION PERMISO
               ?>

               <?php
               //FIN CONDICION PERMISO

               ?>
            </div> <!-- /.row -->
            <!-- Fin tab producto -->
      </div>
      <div class="tab-pane fade" id="pills-clientes" role="tabpanel" aria-labelledby="pills-clientes-tab" tabindex="0">
         <!-- Inicio tab Clientes -->
         <!-- Fin tab Clientes -->
      </div>
      <div class="tab-pane fade" id="pills-costo" role="tabpanel" aria-labelledby="pills-costo-tab" tabindex="0">
      </div>
      <div class="tab-pane fade" id="pills-imagenes" role="tabpanel" aria-labelledby="pills-imagenes-tab" tabindex="0">
      </div>
      <div class="tab-pane fade <?php echo $_GET['tab'] == 'stock' ? 'hide active' : '' ?>" <?php echo $_GET['tab'] == 'stock' ? "type='button'" : '' ?> id="pills-stock" role="tabpanel" aria-labelledby="pills-stock-tab" tabindex="0">
      </div>
   </div>
   </form>
</div>

</div>
</section>
</div>
</div>
<!-- CONTENT AREA -->
<!-- MODAL  -->
<div class="modal fade" id="nueva_diccionario_categoria" tabindex="-1" role="dialog" aria-labelledby="nueva_diccionario_categoria_label" aria-hidden="true">
   <!-- MODAL  -->
</div>
<!-- MODAL  -->
<div class="modal fade" id="nueva_diccionario_moneda" tabindex="-1" role="dialog" aria-labelledby="nueva_diccionario_moneda_label" aria-hidden="true">
   <!-- MODAL  -->
</div>
<!-- MODAL  -->
<div class="modal fade" id="nueva_diccionario_listas_precios" tabindex="-1" role="dialog" aria-labelledby="nueva_diccionario_moneda_label" aria-hidden="true">
   <!-- MODAL  -->
</div>
</div>
<!-- SCRIPTS -->
<script src="<?php echo ENLACE_WEB ?>bootstrap/plugins/src/autocomplete/autoComplete.min.js"></script>
<script>
   $(document).ready(function() {




      // Desactivar todos los elementos del menú
      $(".menu").removeClass('active');
      $(".productos").addClass('active');
      $(".productos > .submenu").addClass('show');
      $("#productos_nuevo").addClass('active');

      $("#tosell").change(function(){
         if($(this).is(':checked') === false && $("#tobuy").is(':checked') === false)
         {
            $("#tobuy").trigger("click");
         }
      });   
      $("#tobuy").change(function(){
         if($(this).is(':checked') === false && $("#tosell").is(':checked') === false)
         {
            $("#tosell").trigger("click");
         }
      });


   });
</script>
<?php include ENLACE_SERVIDOR . 'mod_productos/tpl/scripts_editar.php'; ?>