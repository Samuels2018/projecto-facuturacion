<?php

SESSION_START();
require_once "../../conf/conf.php";
  //LA ENTIDAD DEL USUARIO EN SESION
  if(empty( $_SESSION['usuario']) or empty($_SESSION['Entidad']))
  {
    echo  acceso_invalido( ) ;
    exit(1);
  }



require_once(ENLACE_SERVIDOR . 'mod_productos/object/productos.object.php');
require_once ENLACE_SERVIDOR . "mod_files/object/files.object.php";

$productos = new Productos($dbh, $_SESSION['Entidad']);
$files = new Files($dbh);
$productos->fetch($_REQUEST['fiche']);

$extensiones_permitidas = $files->obtenerExtensiones('imagen');
// accept="image/png, image/jpeg"
//harmos una concatenacion de archivos permitidos en el frontend
$accepts_file = '';
foreach($extensiones_permitidas as $key => $value)
{
  $accepts_file.=$extensiones_permitidas[$key]->extension.',';
}
if(!empty($accepts_file)!='')
{
  $accepts_file = substr($accepts_file,0,-1); // eliminar la ultima coma concatenada
}

?>


<input type="hidden" value="<?php echo $_REQUEST['fiche'] ?>" name="fiche" />

<section class="content">

  <div class="row">
    <!-- left column -->
    <div class="col-md-12">
      <!-- general form elements -->
      <div class="card">
       
        <!-- form start -->

        <div class="card-body">
          <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
            <tr>
                <th>#</th>
                <th><i class="fa fa-fw fa-paperclip"></i>Ficha de Producto</th>
                <th><b> <?php echo $productos->ref;  ?> </b></th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $imagenes = $productos->obtener_imagenes_producto($productos->id);
              
              $i=1;
              
              foreach ($imagenes as $img) {
                // Ahora puedes usar $img como un objeto
                echo '<tr>
                    <td data-label="#">' . $i . '</td>
                    <td data-label="Ficha de Producto"><b>' . $img->descripcion . '</b></td>
                    <td data-label="Imagen">
                    <img style="width:100px" src="' . ENLACE_WEB .'servir_imagenes_productos?img=' . $_SESSION['Entidad'] . '/productos/' . $img->label . '">

                    </td>
                    <td data-label="Acciones">
                        <i style="cursor:pointer;" OnClick="eliminar_imagen(\'' . md5($img->rowid) . '\', \'' . $img->label . '\')" class="fa fa-fw fa-trash"></i>
                    </td>
                </tr>';
                $i++; // Incrementa el contador en cada iteración
            } ?>



          </table>
          </div>
         
          <form action="" method="post" enctype="multipart/form-data" id="MyUploadForm">
            <input type="hidden" name="producto" value="<?php echo $_REQUEST['fiche'] ?>">
            <input name="FileInput" id="FileInput" type="file" onchange="subirArchivo(event)"  accept="<?php echo $accepts_file; ?>">

          </form>

        </div><!-- /.card-body -->


      </div><!-- /.card -->

    </div><!--/.col (left) -->



  </div><!-- /.card-footer -->
  </div>


  </div><!--/.col (left) -->


  <!-- right column -->

  </div> <!-- /.row -->

</section>
<!-- <script>
  function eliminar_imagen(x) {

    if (confirm("Deseas eliminar la imagen")) {


      window.location.href = '<?php echo ENLACE_WEB; ?>dashboard.php?accion=productos_imagenes&fiche=<?php echo $_REQUEST['fiche'];  ?>&eliminar=' + x;


    }

  }
</script> -->