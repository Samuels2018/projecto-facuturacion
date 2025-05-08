<?php
// VALID DEFINITIO ACTION
if (!empty($_POST['action'])) :
  session_start();
  include_once("../../conf/conf.php");

 

  //LA ENTIDAD DEL USUARIO EN SESION
   if(empty( $_SESSION['usuario']) or empty($_SESSION['Entidad']))
  {
    echo json_encode( acceso_invalido("",true) );
    exit(1);
  }

  include ENLACE_SERVIDOR . 'mod_productos/object/productos.object.php';
  include ENLACE_SERVIDOR . 'mod_stock/object/bodegas.object.php';
  
  $productos  = new Productos($dbh, $_SESSION['Entidad']);
  $bodegas    = new Bodegas  ($dbh, $_SESSION['Entidad'] );
  

  // VALID ACTION
  switch ($_POST['action']):
    case "proccessDeleteInfoProduct":
      $productos->id = $_POST['product'];
      $productos->borrado_fk_usuario = $_SESSION['usuario'];
      //$product = $_POST['product'];
      $array = array();

      // Definir las funciones de validación y eliminación en un array asociativo para facilitar el acceso
      $validations = [
        'validBilledProduct' => 'El producto ya se encuentra facturado con anterioridad; por lo que no se puede eliminar.',
        'validQuotedProduct' => 'El producto ya se encuentra cotizado con anterioridad; por lo que no se puede eliminar.',
        'validPurchaseProduct' => 'El producto ya se encuentra inventariado con anterioridad; por lo que no se puede eliminar.'
      ];

      // Iterar sobre las validaciones y detenerse en la primera que falle
      foreach ($validations as $validationMethod => $errorMessage) {
        if ($productos->$validationMethod($product)) {
          $array = ['error' => 1, 'datos' => $errorMessage];
          break;
        }
      }

      // Si todas las validaciones pasaron, proceder con la eliminación
      if (empty($array)) {
        $result = $productos->deleteInfoPriceProduct($productos) &&
          $productos->deleteInfoCostProduct($productos) &&
          $productos->deleteInfoStockProduct($productos) &&
          $productos->borrar_imagenes_producto($productos) &&
          $productos->deleteInfoProduct($productos);

        if ($result) {
          $array = ['error' => 0, 'datos' => 'Proceso de eliminación de la información del producto, realizado con éxito.'];
        } else {
          $array = ['error' => 1, 'datos' => 'Se presentó un error en el proceso de eliminación de la información del producto.'];
        }
      }

      // Retornar el resultado en formato JSON
      echo json_encode($array);
      break;


    case 'actualizarProducto':

      /************************************************************
      /*
      /*           Modificando el producto 
      /*
      /**************************************************************/

      $productos->id = $_POST['id'];
      $productos->tipo = $_POST['tipo'];
      $productos->unidad = $_POST['unidad'];
      $productos->ref = $_POST['ref'];
      $productos->label = $_POST['label'];
      $productos->codigo_barras = $_POST['codigo_barras'];
      $productos->tosell = $_POST['tosell'];
      $productos->tobuy = $_POST['tobuy'];
      $productos->impuesto_retencion  = $_POST['impuesto_retencion'];
      $productos->fk_user_autor = $_POST['fk_user_autor'];
      $productos->stock = $_POST['stock'];
      $productos->stock_minimo_alerta = $_POST['stock_minimo_alerta'];
      $productos->notas = $_POST['notas'];

      $productos->fk_parent_categoria_producto = $_POST['fk_parent_categoria_producto'];


      $productos->diccionario_1 = $_POST['diccionario_1'];
      $productos->diccionario_2 = $_POST['diccionario_2'];
      $productos->diccionario_3 = $_POST['diccionario_3'];
      $productos->diccionario_4 = $_POST['diccionario_4'];
      $productos->diccionario_5 = $_POST['diccionario_5'];
      $productos->diccionario_6 = $_POST['diccionario_6'];
      $productos->diccionario_7 = $_POST['diccionario_7'];
      $productos->diccionario_8 = $_POST['diccionario_8'];
      $productos->diccionario_9 = $_POST['diccionario_9'];
      $productos->diccionario_10 = $_POST['diccionario_10'];
      $productos->fk_bodega_base = $_POST['fk_bodega_base'];
      $productos->descripcion = $_POST['descripcion'];
      $productos->conart                      = $_POST['conart'];
      $productos->impuesto_fk                 = $_POST['impuesto_fk'];
      $productos->descuento_maximo            = $_POST['descuento_maximo'];


      
      $result = $productos->update();
   
      echo json_encode($result);

     
      break;

    case 'actualizarPrecio':
       
      $productos->id          = $_POST['id'];
      $productos->moneda      = $_POST['moneda'];
      $productos->tipo        = $_POST['tipo'];
      $productos->fk_lista    = $_POST['fk_lista'];
      $productos->porcentaje_utilidad   = $_POST['porcentaje_utilidad'];
      $productos->porcentaje_descuento  = $_POST['porcentaje_descuento'];
      $productos->precio_base           = $_POST['precio_base'];
      $productos->impuesto              = $_POST['impuesto'];
      $productos->creado_fk_usuario     = $_SESSION['usuario'];
      $result = $productos->nuevo_precio();
      
      echo json_encode($result);

    break;


    case 'borrarImagenProducto':

      $datos = new stdClass();
      $datos->id          = $_POST['id'];
      $datos->fk_producto = $_POST['fk_producto'];
      $datos->label       = $_POST['label'];
      $datos->creado_fk_usuario = $_SESSION['usuario'];
      $result = $productos->borrar_imagen_producto($datos);
      return json_encode($result);
      break;




    case 'actualizarCosto':
      // Asegúrate de que todos los campos necesarios estén presentes en $_POST
      $datos = new stdClass();
      // Asignar los valores de $_POST a las propiedades del objeto $datos
      $datos->fk_producto = $_POST['fk_producto'];
      $datos->precio = $_POST['precio'];
      $datos->impuesto = $_POST['impuesto'];
      $datos->nota = $_POST['nota'];
      $datos->creado_fk_usuario = $_SESSION['usuario'];

      // Llamar al método updateCosto de la clase Productos
      // Asegúrate de que este método exista y esté correctamente implementado
      $result = $productos->actualizar_precio_costo($datos);

      // Enviar el resultado como respuesta JSON
      echo json_encode($result);
      break;

    case 'crearProducto':

      $productos->ref             = $_POST['ref'];
      $productos->label           = $_POST['label'];
      $productos->codigo_barras  = $_POST['codigo_barras'];
      $productos->tipo            = $_POST['tipo'];
      $productos->unidad          = $_POST['unidad'];
      $productos->impuesto_retencion  = $_POST['impuesto_retencion'];
      $productos->tosell          = $_POST['tosell'];
      $productos->tobuy           = $_POST['tobuy'];
      $productos->stock_minimo_alerta = $_POST['stock_minimo_alerta'];
      $productos->notas           = $_POST['notas'];
      $productos->entidad         = $_SESSION['Entidad'];
      $productos->fk_user_autor   = $_SESSION['usuario'];
      $productos->creado_fk_usuario = $_SESSION['usuario'];
      $productos->diccionario_1     = $_POST['diccionario_1'];
      $productos->fk_parent_categoria_producto = $_POST['fk_parent_categoria_producto'];
      $productos->descripcion                 = $_POST['descripcion'];
      $productos->conart                      = $_POST['conart'];
      $productos->impuesto_fk                 = $_POST['impuesto_fk'];
      $productos->descuento_maximo            = $_POST['descuento_maximo'];
      
      $result = $productos->nuevo() ;
 
      echo json_encode($result);
      break;


    case 'crear_politica':
      $productos->entidad =  $_SESSION['Entidad'];
      $productos->cantidad = $_POST['cantidad'];
      $productos->tipo = $_POST['tipo_politica'];
      $productos->porcentaje_descuento = $_POST['porcentaje_politica'];
      $productos->fk_producto = $_POST['fk_producto'];
      $productos->creado_fk_usuario =  $_SESSION['usuario'];
      $productos->fecha_inicial = $_POST['fecha_inicial'];
      $productos->fecha_final = $_POST['fecha_final'];

      $result = $productos->crear_politica($productos);

      echo json_encode($result);
      break;

    case 'actualizarStock':

      // Se obtienen los datos del formulario
      $bodegas->fk_producto       = $_POST['fk_producto'];
      $bodegas->fk_bodega         = $_POST['fk_bodega'];
      $bodegas->valor             = $_POST['valor'];
      $bodegas->tipo              = $_POST['tipo'];
      $bodegas->motivo            = $_POST['motivo'];
      $bodegas->usuario           = $_SESSION['usuario'];
      $bodegas->creado_fk_usuario = $_SESSION['usuario'];
      $bodegas->documento_tipo    = "Movimiento Manual";  
      $result = $bodegas->movimiento_stock($stock);

      // Se actualiza el stock del producto
      //$result = $bodegas->actualizar_stock_producto($stock);

      // Se retorna el resultado de la operación
      echo json_encode($result);

      break;



    case 'cargar_subcategorias_producto':

       $productos->fk_parent = $_POST['fk_parent'];

      $subcategorias =  $productos->cargar_subcategorias_producto();

      echo json_encode($subcategorias);

      break;


    case 'cargar_categorias':

      $categorias =  $productos->obtener_categorias($_SESSION['Entidad']);

      echo json_encode($categorias);

      break;

    case 'ver_log_precios':

      $data =  $productos->obtener_precios_cliente($_POST['fk_producto'] );



      //armamos el tbody
      foreach ($data as $precio) {

        $tbody_html .= "<tr>
                        <td data-label='Fecha'>" . date('d-m-Y h:i', strtotime($precio->fecha)) . "</td>
                        <td data-label='Precio Base'>" . numero_simple($precio->subtotal) . "</td>
                        <td data-label='Impuesto'>" . $precio->impuesto . " %</td>
                        <td data-label='Total'>" . numero_simple($precio->total) . "</td>
                        <td data-label='Usuario Creo Precio'><i class=\"fa fa-fw fa-user\"></i>" . $precio->nombre_usuario . "</td>
                      </tr>";
      }

      echo ($tbody_html);







      break;


    case 'ver_politica_detalle':

      $data =  $productos->ver_politica_detalle($_POST['fiche']);

      //armamos el tbody
      foreach ($data['data'] as $item) {

        $tbody_html .= "<tr>
                            <td data-label='Monto'>" . $item->base_imponible . "</td>
                                <td data-label='Porcentaje'>" . $item->porcentaje_descuento . "%</td>
                           
                            <td data-label='Borrar'><i class='fa fa-trash' aria-hidden='true'></i></td>
                          </tr>";
      }

      echo ($tbody_html);


      break;

    case 'crear_politica_detalle':
      $productos->entidad =  $_SESSION['Entidad'];
      $productos->cantidad = $_POST['cantidad'];
      $productos->tipo = $_POST['tipo_politica'];
      $productos->porcentaje_descuento = $_POST['porcentaje_politica'];
      $productos->fk_producto = $_POST['fk_producto'];
      $productos->creado_fk_usuario =  $_SESSION['usuario'];
      $productos->fecha_inicial = $_POST['fecha_inicial'];
      $productos->fecha_final = $_POST['fecha_final'];

      //   $result = $productos->crear_politica_detalle($productos); 

      //     echo json_encode($result);
      break;



  endswitch;
endif;
// FUNCTION VALID BILLED PRODUCT
