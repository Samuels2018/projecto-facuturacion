<?php

SESSION_START();
require_once "../../conf/conf.php";
require_once(ENLACE_SERVIDOR . 'mod_redhouse_cotizaciones/object/redhouse.cotizaciones.object.php');
require_once ENLACE_SERVIDOR . "mod_files/object/files.object.php";

$files = new Files($dbh);

$Cotizacion = new Redhouse_Cotizacion($dbh , $_SESSION['Entidad']);
$Cotizacion->fetch($_REQUEST['fiche']);

//$extensiones_permitidas = $files->obtenerExtensiones('imagen');
$extensiones_permitidas = $files->obtenerExtensiones(''); //dejamos todas las extensiones
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
                <th><i class="fa fa-fw fa-paperclip"></i>Ficha de cotización</th>
                <th><b> <?php echo $Cotizacion->rowid;  ?> </b></th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $imagenes = $Cotizacion->obtener_adjuntos_cotizacion($Cotizacion->id);
              
              $i=1;
              
              foreach ($imagenes as $img) {
              echo '<tr>
                <td data-label="#">' . $i . '</td>
                <td data-label="Ficha de Producto"><b><a href="' . ENLACE_WEB .'servir_adjuntos_cotizaciones?img=' . $_SESSION['Entidad'] . '/cotizacion/' . $img->label . '" download="'.$img->descripcion.'">' . $img->descripcion . '</a></b></td>
                <td data-label="Imagen">';

                // Obtener la extensión del archivo
                $extension = strtolower(pathinfo($img->label, PATHINFO_EXTENSION));

                // Comprobar si el archivo es una imagen
                if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'])) {
                    echo '<img style="width:100px" src="' . ENLACE_WEB .'servir_adjuntos_cotizaciones?img=' . $_SESSION['Entidad'] . '/cotizacion/' . $img->label . '">';
                } else {
                    // Mostrar un ícono de Font Awesome dependiendo del tipo de archivo
                    switch ($extension) {
                        case 'pdf':
                            echo '<i class="fa fa-file-pdf-o fa-3x" aria-hidden="true"></i>';
                            break;
                        case 'doc':
                        case 'docx':
                            echo '<i class="fa fa-file-word-o fa-3x" aria-hidden="true"></i>';
                            break;
                        case 'xls':
                        case 'xlsx':
                            echo '<i class="fa fa-file-excel-o fa-3x" aria-hidden="true"></i>';
                            break;
                        case 'zip':
                        case 'rar':
                            echo '<i class="fa fa-file-archive-o fa-3x" aria-hidden="true"></i>';
                            break;
                        default:
                            echo '<i class="fa fa-file-o fa-3x" aria-hidden="true"></i>';
                            break;
                    }
                }

        echo '</td>
                <td data-label="Acciones">
                    <i style="cursor:pointer;" OnClick="eliminar_imagen(\'' .$img->rowid . '\', \'' . $img->label . '\')" class="fa fa-fw fa-trash-o"></i>
                </td>
            </tr>';

                $i++; // Incrementa el contador en cada iteración
            } ?>

          </table>
          </div>
         
          <form action="" method="post" enctype="multipart/form-data" id="MyUploadForm">
            <input type="hidden" name="fk_cotizacion" value="<?php echo $_REQUEST['fiche'] ?>">
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
