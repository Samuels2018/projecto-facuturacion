<?php


class Productos  extends  Seguridad
{

  private $db;

  public $id;
  public $tipo;
  public $ref;
  public $fk_bodega_base;
  public $cbproveedor;
  public $cbinterno;

  public $label;
  public $tosell;
  public $tobuy;
  public $fk_user_autor;
  public $stock;
  public $stock_minimo_alerta;
  public $notas;

  public $codigo_barras;

  public $conversion_venta;
  public $conversion_venta_numero;
  public $conversion_compra;
  public $conversion_compra_numero;
  public $entidad;

  public $diccionario_1;
  public $diccionario_2;
  public $diccionario_3;
  public $diccionario_4;
  public $diccionario_5;
  public $diccionario_6;
  public $diccionario_7;
  public $diccionario_8;
  public $diccionario_9;
  public $diccionario_10;
  public $romana;

  public $creado_fecha;
  public $creado_fk_usuario;
  public $borrado;
  public $borrado_fecha;
  public $borrado_fk_usuario;

  public $impuestos;

  public $descripcion;
  public $conart;
  public $descuento_maximo;
  public $impuesto_fk;

  // parametro Extra
  public $tipo_texto; // nos dice en texto si es producto o servicio





  /************************************************************
   * 
   * 
   * 
   *  Funciones Depuradas
   *  6 Enero 2024
   * 
   * 
   *****************************************************************/



  public function __construct($db, $entidad)
  {
    $this->entidad  = $entidad;
    $this->db       = $db;

    if (empty($entidad)) {
      echo "Parametro Entidad No recibido";
      return false;
    }
    parent::__construct($db, $entidad);  // Esto inicializa la clase SEGURIDAD
  }


  public function fetch($id)
  {

    $sql = "select * from fi_productos  where rowid = :rowid and entidad =  :entidad ";
    $dbh = $this->db->prepare($sql);
    $dbh->bindValue(':rowid', $id, PDO::PARAM_INT);
    $dbh->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);
    $dbh->execute();

    $datos      = $dbh->fetch(PDO::FETCH_ASSOC);

    $this->tipo = $datos['tipo'];
    $this->ref  = $datos['ref'];

    $this->fk_bodega_base = $datos['fk_bodega_base'];
    $this->romana         = $datos['romana'];
    $this->cbproveedor    = $datos['cbproveedor'];
    $this->cbinterno      = $datos['cbinterno'];

    $this->tosell              = $datos['tosell'];
    $this->tobuy               = $datos['tobuy'];
    $this->fk_user_autor       = $datos['fk_user_autor'];
    $this->stock               = $datos['stock'];
    $this->stock_minimo_alerta = $datos['stock_minimo_alerta'];
    $this->label               = $datos['label'];
    $this->notas               = $datos['notas'];
    $this->id                  = $datos['rowid'];
    $this->entidad             = $datos['entidad'];
    $this->unidad              = $datos['unidad'];
    $this->fk_parent_categoria_producto              = $datos['fk_parent_categoria_producto'];



    $this->codigo_barras = $datos['codigo_barras'];

    $this->conversion_venta         = $datos['conversion_venta'];
    $this->conversion_venta_numero  = $datos['conversion_venta_numero'];
    $this->conversion_compra        = $datos['conversion_compra'];
    $this->conversion_compra_numero = $datos['conversion_compra_numero'];

    $this->diccionario_1  = $datos['diccionario_1'];
    $this->diccionario_2  = $datos['diccionario_2'];
    $this->diccionario_3  = $datos['diccionario_3'];
    $this->diccionario_4  = $datos['diccionario_4'];
    $this->diccionario_5  = $datos['diccionario_5'];
    $this->diccionario_6  = $datos['diccionario_6'];
    $this->diccionario_7  = $datos['diccionario_7'];
    $this->diccionario_8  = $datos['diccionario_8'];
    $this->diccionario_9  = $datos['diccionario_9'];
    $this->diccionario_10 = $datos['diccionario_10'];
    $this->fk_bodega_base = $datos['fk_bodega_base'];
    $this->descripcion    = $datos['descripcion'];
    $this->conart         = $datos['conart'];
    $this->descuento_maximo = $datos['descuento_maximo'];
    $this->impuesto_fk      = $datos['impuesto_fk'];

    $this->impuesto_retencion      = $datos['impuesto_retencion'];


    if ($this->tipo == 1) {
      $this->tipo_texto = "Producto";
    } else if ($this->tipo == 2) {
      $this->tipo_texto = "Servicio";
    } else {
      $this->tipo_texto = "No Catalogado*";
    }


    return $id;
  }


  public function nuevo()
  {

    $sql =  "SELECT ref, codigo_barras 
      FROM fi_productos
      where entidad = :entidad
        AND (ref LIKE :ref ) or (codigo_barras LIKE :codigo_barra and TRIM(:codigo_barra) <> '' )
      LIMIT 1
    ";

    $dbh = $this->db->prepare($sql);
    $dbh->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);
    $dbh->bindValue(':codigo_barra', $this->codigo_barras, PDO::PARAM_STR);
    $dbh->bindValue(':ref', $this->ref, PDO::PARAM_STR);
    $dbh->execute();
    $data = $dbh->fetch(PDO::FETCH_ASSOC);
    if ($data) {
      return array('error' => 1, 'datos' => 'Ya existen productos con la "Referencia interna" o "Codigo de Barra"');
    }


    $sql = "insert into fi_productos
                                 (
                                  entidad ,
                          tipo,
                                  ref,

                                  label,
                                  codigo_barras ,
              
                                  tosell ,
                                  tobuy  ,
                                  fk_user_autor ,
                                  stock_minimo_alerta ,

                                  notas,
                                  diccionario_1 ,
                                  diccionario_2 ,
                                  diccionario_3 ,
                                  diccionario_4 ,
                                  diccionario_5 ,
                                  diccionario_6 ,
                                  diccionario_7 ,
                                  diccionario_8 ,
                                  diccionario_9 ,
                                  diccionario_10,
                                  fk_parent_categoria_producto,
                                  romana        ,
                                  unidad,
                                  creado_fk_usuario,
                                  creado_fecha,
                                  descripcion,
                                  conart     ,
                                  impuesto_retencion ,
                                  impuesto_fk        ,
                                  descuento_maximo)
                                  values
                                 (
                                  '" . $this->entidad . "' ,
                                  :tipo,
                                  :ref,

                                  :label,
                                  :codigo_barras,
                       
                                  :tosell ,
                                  :tobuy  ,
                                  :fk_user_autor ,
                                  :stock_minimo_alerta,


                                  :notas ,
                                  :diccionario_1 ,
                                  :diccionario_2 ,
                                  :diccionario_3 ,
                                  :diccionario_4 ,
                                  :diccionario_5 ,
                                  :diccionario_6 ,
                                  :diccionario_7 ,
                                  :diccionario_8 ,
                                  :diccionario_9 ,
                                  :diccionario_10 ,
                                  :fk_parent_categoria_producto ,
                                  :romana   ,
                                  :unidad,
                                  :creado_fk_usuario,
                                  now(),
                                  :descripcion,
                                  :conart ,
                                  :impuesto_retencion ,
                                  :impuesto_fk        ,
                                  :descuento_maximo
                                        ) ";

    $dbh = $this->db->prepare($sql);

    $dbh->bindValue(':impuesto_fk', (empty($this->impuesto_fk))        ? NULL  : $this->impuesto_fk);
    $dbh->bindValue(':descuento_maximo', (empty($this->descuento_maximo) or $this->descuento_maximo > 100)   ? 100   : $this->descuento_maximo);


    $dbh->bindValue(':tipo', (empty($this->tipo)) ? ' ' : $this->tipo, PDO::PARAM_INT);
    $dbh->bindValue(':ref', (empty($this->ref)) ? ' ' : $this->ref, PDO::PARAM_STR);
    $dbh->bindValue(':label', (empty($this->label)) ? ' ' : $this->label, PDO::PARAM_STR);
    $dbh->bindValue(':tosell', (empty($this->tosell)) ? ' ' : $this->tosell, PDO::PARAM_INT);
    $dbh->bindValue(':impuesto_retencion', (empty($this->impuesto_retencion)) ? ' ' : $this->impuesto_retencion, PDO::PARAM_INT);
    $dbh->bindValue(':tobuy', (empty($this->tobuy)) ? ' ' : $this->tobuy, PDO::PARAM_INT);
    $dbh->bindValue(':fk_user_autor', (empty($this->fk_user_autor)) ? ' ' : $this->fk_user_autor, PDO::PARAM_INT);
    $dbh->bindValue(':unidad', (empty($this->unidad)) ? ' ' : $this->unidad, PDO::PARAM_STR);
    $dbh->bindValue(':codigo_barras', (empty($this->codigo_barras)) ? '' : $this->codigo_barras, PDO::PARAM_STR);
    $dbh->bindValue(':stock_minimo_alerta', (empty($this->stock_minimo_alerta)) ? ' ' : $this->stock_minimo_alerta, PDO::PARAM_INT);
    $dbh->bindValue(':notas', (empty($this->notas)) ? ' ' : $this->notas, PDO::PARAM_STR);
    $dbh->bindValue(':fk_parent_categoria_producto', $this->fk_parent_categoria_producto,  PDO::PARAM_INT);
    for ($i = 1; $i <= 10; $i++) {
      $dbh->bindValue(':diccionario_' . $i, (empty($this->{'diccionario_' . $i})) ? 0 : $this->{'diccionario_' . $i}, PDO::PARAM_INT);
    }

    $dbh->bindValue(':romana', (empty($this->romana)) ? 0 : $this->romana, PDO::PARAM_INT);
    $dbh->bindValue(':creado_fk_usuario', $this->creado_fk_usuario, PDO::PARAM_INT);
    $dbh->bindValue(':descripcion', $this->descripcion . '', PDO::PARAM_STR);
    $dbh->bindValue(':conart', $this->conart, PDO::PARAM_STR);

    $a = $dbh->execute();


    if (!$a) {
      $this->sql     =   $sql;
      $this->error   =   implode(", ", $dbh->errorInfo()) . " " . implode(" , ", $this->db->errorInfo());
      $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__ . " Tabla " . $this->documento;
      $this->Error_SQL();

      $respuesta['exito']   = 0;
      $respuesta['mensaje'] = "Error " . $this->error;
    } else {

      $respuesta['exito']   = 1;
      $respuesta['mensaje'] = "Creado Con Exito";
      $respuesta['id']      =  $this->db->lastInsertId();
    }






    $this->id = $id;

    return $respuesta;
  }







  /****************************************************************
   * 
   * 
   *      Actualizar Producto Version Europa
   * 
   * 
   *****************************************************************/
  public function Impuestos()
  {

    $sql =  "SELECT * from diccionario_impuestos WHERE entidad = :entidad AND activo = 1";
    $dbh = $this->db->prepare($sql);
    $dbh->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);
    $dbh->execute();
    while ($data   = $dbh->fetch(PDO::FETCH_OBJ)) {
      $this->impuestos[$data->rowid]['impuesto_texto'] = $data->impuesto_texto;
      $this->impuestos[$data->rowid]['impuesto']       = $data->impuesto;
      $this->impuestos[$data->rowid]['recargo_equivalencia'] = $data->recargo_equivalencia;
    }
    return true;
  }

  public function listas_precios()
  {

    $sql =  "SELECT * from fi_productos_precios_clientes_listas WHERE entidad = :entidad and borrado = 0 and activo = 1";
    $dbh = $this->db->prepare($sql);
    $dbh->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);
    $dbh->execute();
    while ($data   = $dbh->fetch(PDO::FETCH_OBJ)) {
      $this->lista_precio[$data->rowid]['etiqueta'] = $data->etiqueta;
      $this->lista_precio[$data->rowid]['rowid']       = $data->rowid;
    }
    return true;
  }



  public function update()
  {
    $sql =  "SELECT ref, codigo_barras 
      FROM fi_productos
      where entidad = :entidad
        AND rowid <> :rowid
        AND (ref LIKE :ref ) or (codigo_barras LIKE :codigo_barra and TRIM(:codigo_barra) <> '' )
      LIMIT 1
    ";

    $dbh = $this->db->prepare($sql);
    $dbh->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);
    $dbh->bindValue(':codigo_barra', $this->codigo_barras, PDO::PARAM_STR);
    $dbh->bindValue(':ref', $this->ref, PDO::PARAM_STR);
    $dbh->bindValue(':rowid', $this->id, PDO::PARAM_INT);
    $dbh->execute();
    $data = $dbh->fetch(PDO::FETCH_ASSOC);
    if ($data) {
      $resultado['id']      =   0;
      $resultado['exito']   =   0;
      $resultado['datos']   =   'Ya existen productos con la "Referencia interna" o "Codigo de Barra"';
      return $resultado;
    }

    $sql = "update  fi_productos

                  set
                  tipo  = :tipo,
                  ref   = :ref,
                  label = :label,
                  codigo_barras = :codigo_barras, 

                  tosell = :tosell,
                  tobuy  = :tobuy ,
                  impuesto_retencion = :impuesto_retencion , 
                  stock_minimo_alerta = :stock_minimo_alerta ,
                  notas = :notas ,
                  unidad  = :unidad  ,

                  diccionario_1 = :diccionario_1 ,
                  diccionario_2 = :diccionario_2 ,
                  diccionario_3 = :diccionario_3 ,
                  diccionario_4 = :diccionario_4 ,
                  diccionario_5 = :diccionario_5 ,
                  diccionario_6 = :diccionario_6 ,
                  diccionario_7 = :diccionario_7 ,
                  diccionario_8 = :diccionario_8 ,
                  diccionario_9 = :diccionario_9 ,
                  diccionario_10 = :diccionario_10 ,
                  fk_parent_categoria_producto = :fk_parent_categoria_producto,
                  romana         = :romana,
                  descripcion    = :descripcion,
                  conart         = :conart     ,
                  impuesto_fk    = :impuesto_fk , 
                  descuento_maximo  =   :descuento_maximo

                  WHERE    rowid = :rowid  and entidad = :entidad ";

    $dbh = $this->db->prepare($sql);

    $dbh->bindValue(':entidad',   $this->entidad, PDO::PARAM_INT);
    $dbh->bindValue(':impuesto_fk', (empty($this->impuesto_fk))        ? NULL  : $this->impuesto_fk);
    $dbh->bindValue(':descuento_maximo', (empty($this->descuento_maximo) or $this->descuento_maximo > 100)   ? 100   : $this->descuento_maximo);

    $dbh->bindValue(':tipo', (empty($this->tipo)) ? ' ' : $this->tipo, PDO::PARAM_INT);
    $dbh->bindValue(':ref', (empty($this->ref)) ? ' ' : $this->ref, PDO::PARAM_STR);
    $dbh->bindValue(':unidad', $this->unidad, PDO::PARAM_STR);

    $dbh->bindValue(':label', (empty($this->label)) ? ' ' : $this->label, PDO::PARAM_STR);
    $dbh->bindValue(':fk_parent_categoria_producto', $this->fk_parent_categoria_producto,  PDO::PARAM_INT);
    $dbh->bindValue(':tosell', (empty($this->tosell)) ? ' ' : $this->tosell, PDO::PARAM_INT);
    $dbh->bindValue(':tobuy', (empty($this->tobuy)) ? ' ' : $this->tobuy, PDO::PARAM_INT);
    $dbh->bindValue(':impuesto_retencion', (empty($this->impuesto_retencion)) ? 0 : 1, PDO::PARAM_INT);


    $dbh->bindValue(':notas', (empty($this->notas)) ? ' ' : $this->notas, PDO::PARAM_STR);
    $dbh->bindValue(':codigo_barras', (empty($this->codigo_barras)) ? '' : $this->codigo_barras, PDO::PARAM_STR);
    $dbh->bindValue(':romana', (empty($this->romana)) ? 0 : $this->romana, PDO::PARAM_INT);

    $dbh->bindValue(':stock_minimo_alerta', (empty($this->stock_minimo_alerta)) ? ' ' : $this->stock_minimo_alerta, PDO::PARAM_INT);

    for ($i = 1; $i <= 10; $i++) {
      $dbh->bindValue(':diccionario_' . $i, (empty($this->{'diccionario_' . $i})) ? 0 : $this->{'diccionario_' . $i}, PDO::PARAM_INT);
    }

    $dbh->bindValue(':descripcion', (empty($this->descripcion)) ? ' ' : $this->descripcion, PDO::PARAM_STR);
    $dbh->bindValue(':conart', (empty($this->conart)) ? ' ' : $this->conart, PDO::PARAM_STR);

    $dbh->bindValue(':rowid', $this->id, PDO::PARAM_INT);

    $a = $dbh->execute();


    if ($a) {
      $this->id = $this->id;
      $resultado['id']      =   $this->id;
      $resultado['exito']   =   $a;
      $resultado['datos'] =   "Producto actualizado con Exito";
    } else {
      $resultado['exito'] = 0;
      $resultado['datos'] =  implode(", ", $dbh->errorInfo());
      $this->sql     =   $sql;
      $this->error   =   implode(", ", $dbh->errorInfo());
      $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
      $this->Error_SQL();
    }



    return $resultado;
  }


  public function obtener_precios_cliente($id)
  {
    $sql = "
    select ppc.*,
       concat(fu.nombre,' ',fu.apellidos) as nombre_usuario
      
    from 
    fi_productos pro 
    
    inner join fi_productos_precios_clientes ppc on ppc.fk_producto = pro.rowid
    left join fi_usuarios fu on fu.rowid = ppc.fk_usuario
    
    where 
            ppc.fk_producto  =  " . $id . "   
      and   pro.entidad      = :entidad 
        order by ppc.rowid DESC  ";

    $db = $this->db->prepare($sql);
    $db->bindValue(":entidad", $this->entidad, PDO::PARAM_INT);
    $db->execute();
    $data   = $db->fetchAll(PDO::FETCH_OBJ);

    return $data;
  }





  function nuevo_precio()
  {


    $sql = "INSERT INTO fi_productos_precios_clientes
              (fk_producto, 
              fk_usuario,
              impuesto,
              subtotal,
              total,
              moneda,
              fecha,
              creado_fk_usuario,
              creado_fecha,
              fk_lista, 
              porcentaje_utilidad,
              porcentaje_descuento)                               
              VALUES 
              ( 
              :fk_producto,
              :fk_usuario,
              :impuesto,
              :subtotal,
              :total,
              :moneda,
              NOW(),
              :creado_fk_usuario,
              NOW(),
              :fk_lista, 
              :porcentaje_utilidad,
              :porcentaje_descuento
              )";

    $db = $this->db->prepare($sql);

    if ($this->tipo == "sumar") {
      if ($this->impuesto == 0) {
        $total = $this->precio_base;
      } else {
        $total = ($this->precio_base * ($this->impuesto / 100));
        $total = $this->precio_base + $total;
      }
    } else { // si el precio hay que calcularle el impuesto el cual ya va sumado al precio
      if ($this->impuesto == 0) {
        $total = $this->precio_base;
      } else {
        $total = $this->precio_base;
        $this->precio_base = ($this->precio_base * 100) / (($this->impuesto + 100));
      }
    }

    $params = [
      ':fk_producto'  => (empty($this->id)) ? null : $this->id,
      ':fk_usuario'   => $this->creado_fk_usuario,
      ':subtotal'     => (empty($this->precio_base)) ? '0' : $this->precio_base,
      ':impuesto'     => (empty($this->impuesto)) ? '0' : $this->impuesto,
      ':total'        => $total,
      ':fk_lista'     => is_numeric($this->fk_lista) ? $this->fk_lista : null,
      ':porcentaje_utilidad'  => is_numeric($this->porcentaje_utilidad) ? $this->porcentaje_utilidad : null,
      ':porcentaje_descuento' => is_numeric($this->porcentaje_descuento) ? $this->porcentaje_descuento : null,
      ':moneda'               => (empty($this->moneda)) ? '1' : $this->moneda,
      ':creado_fk_usuario'    => $this->creado_fk_usuario,
    ];


    // Vincular los valores manualmente
    foreach ($params as $key => &$val) {
      $db->bindParam($key, $val);
    }




    $a = $db->execute();
    $id = $this->db->lastInsertId();

    if ($a) {
      $resultado['id']        =   $id;
      $resultado['exito']     =   1;
      $resultado['mensaje']   =   "Registro Precio Creado con Exito";
    } else {
      $this->sql     =   $sql;
      $this->error   =   implode(", ", $db->errorInfo()) . " " . implode(", ", $this->db->errorInfo());
      $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
      $this->Error_SQL();
      $resultado['exito']   = 0;
      $resultado['mensaje'] = $this->error;
    }



    return $resultado;
  }




  /************************************************************
   * 
   * 
   * 
   * 
   * 
   * 
   * 
   * 
   * 
   * 
   * 
   * 
   * 
   * 
   * 
   * 
   * 
   * 
   * 
   * 
   * 
   * 
   *  Funciones Sin Depurara
   * 
   * 
   * 
   * 
   * 
   * 
   * 
   * 
   * 
   * 
   * 
   * 
   * 
   * 
   * 
   * 
   * 
   * 
   * 
   * 
   * 
   * 
   *****************************************************************/



  public function crear_politica()
  {
    // Consulta SQL utilizando la sintaxis SET para insertar una nueva política de descuento
    $sql = "INSERT INTO fi_productos_politica_descuentos SET
                entidad = :entidad,
                tipo = :tipo,
                fk_producto = :fk_producto,
                creado_fecha = now(),
                creado_fk_usuario = :creado_fk_usuario,
                fecha_inicial = :fecha_inicial,
                fecha_final = :fecha_final";

    // Preparar la consulta
    $dbh = $this->db->prepare($sql);

    // Vincular los valores directamente desde las propiedades del objeto ($this)
    $dbh->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);
    $dbh->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
    $dbh->bindValue(':fk_producto', $this->fk_producto, PDO::PARAM_INT);
    $dbh->bindValue(':creado_fk_usuario', $this->creado_fk_usuario, PDO::PARAM_INT);
    $dbh->bindValue(':fecha_inicial', isset($this->fecha_inicial) ? $this->fecha_inicial : null, PDO::PARAM_STR);
    $dbh->bindValue(':fecha_final', isset($this->fecha_final) ? $this->fecha_final : null, PDO::PARAM_STR);

    // Ejecutar la consulta dentro de un bloque try-catch para capturar errores
    try {
      $dbh->execute();
      //   return $this->db->lastInsertId(); // Retornar el último ID insertado si tiene auto_increment
      return ['exito' => 1, 'mensaje' => 'Registro insertado correctamente'];
    } catch (Exception $e) {
      return "Error al crear política de descuento: " . $e->getMessage();
    }
  }











  public function venta()
  {

    $sql = "select * from fi_productos_precios_clientes   where fk_producto  = :rowid  order by rowid DESC  limit  1 ";
    $dbh = $this->db->prepare($sql);
    $dbh->bindValue(':rowid', $this->id, PDO::PARAM_INT);
    $dbh->execute();
    $a              = $datos              = $dbh->fetch(PDO::FETCH_OBJ);
    $this->impuesto = $datos->impuesto;
    $this->subtotal = $datos->subtotal;
    $this->total    = $datos->total;

    return $a;
  }


  //Funciones Movida de productos.class.php
  function validBilledProduct($product)
  {
    // QUERY
    $sql = "SELECT rowid FROM fi_facturas_detalle WHERE fk_producto =:fk_producto;";
    $db = $this->db->prepare($sql);
    $db->bindValue(":fk_producto", $product, PDO::PARAM_INT);
    $result = $db->execute();
    $data   = $db->fetch(PDO::FETCH_OBJ);
    $row    = $db->rowCount();
    $status = (($row) > 0) ? true : false;
    // RETURN
    return $status;
  }
  // FUNCTION VALID QUOTED PRODUCT
  function validQuotedProduct($product)
  {
    // QUERY
    $sql = "SELECT rowid FROM fi_cotizaciones_detalle WHERE fk_producto =:fk_producto;";
    $db = $this->db->prepare($sql);
    $db->bindValue(":fk_producto", $product, PDO::PARAM_INT);
    $result = $db->execute();
    $data   = $db->fetch(PDO::FETCH_OBJ);
    $row    = $db->rowCount();
    $status = (($row) > 0) ? true : false;
    // RETURN
    return $status;
  }
  // FUNCTION VALID PURCHASE PRODUCT
  function validPurchaseProduct($product)
  {
    // QUERY
    $sql = "SELECT rowid FROM fi_compras_detalle WHERE fk_producto =:fk_producto;";
    $db = $this->db->prepare($sql);
    $db->bindValue(":fk_producto", $product, PDO::PARAM_INT);
    $result = $db->execute();
    $data   = $db->fetch(PDO::FETCH_OBJ);
    $row    = $db->rowCount();
    $status = (($row) > 0) ? true : false;
    // RETURN
    return $status;
  }

  // FUNCTION DELETE INFO PRICE PRODUCT
  function deleteInfoPriceProduct($product)
  {
    // QUERY
    $sql  = "UPDATE fi_productos_precios_clientes SET borrado = 1, borrado_fecha = now(), borrado_fk_usuario = :borrado_fk_usuario WHERE fk_producto =:fk_producto;";
    $db   = $this->db->prepare($sql);
    $db->bindValue(":fk_producto", $product->id, PDO::PARAM_INT);
    $db->bindValue(":borrado_fk_usuario", $product->borrado_fk_usuario, PDO::PARAM_INT);
    $result = $db->execute();
    // RETURN
    return $result;
  }
  // FUNCTION DELETE INFO COST PRODUCT
  function deleteInfoCostProduct($product)
  {
    // QUERY
    $sql  = "UPDATE fi_productos_precios_costo SET borrado = 1, borrado_fecha = now(), borrado_fk_usuario = :borrado_fk_usuario  WHERE fk_producto =:fk_producto;";

    $db   = $this->db->prepare($sql);
    $db->bindValue(":fk_producto", $product->id, PDO::PARAM_INT);
    $db->bindValue(":borrado_fk_usuario", $product->borrado_fk_usuario, PDO::PARAM_INT);
    $result = $db->execute();
    // RETURN
    return $result;
  }
  // FUNCTION DELETE INFO STOCK PRODUCT
  function deleteInfoStockProduct($product)
  {
    // QUERY
    $sql  = "UPDATE fi_bodegas_productos_configuracion SET borrado = 1, borrado_fecha = now(), borrado_fk_usuario = :borrado_fk_usuario  WHERE fk_producto =:fk_producto;";

    $db   = $this->db->prepare($sql);
    $db->bindValue(":fk_producto", $product->id, PDO::PARAM_INT);
    $db->bindValue(":borrado_fk_usuario", $product->borrado_fk_usuario, PDO::PARAM_INT);
    $result = $db->execute();
    // RETURN
    return $result;
  }
  // FUNCTION DELETE INFO PRODUCT
  function deleteInfoProduct($product)
  {

    // QUERY
    $sql  = "UPDATE fi_productos SET eliminado = 1, borrado = 1, borrado_fecha = now(), borrado_fk_usuario = :borrado_fk_usuario WHERE rowid =:rowid;";

    $db   = $this->db->prepare($sql);
    $db->bindValue(":rowid", $product->id, PDO::PARAM_INT);
    $db->bindValue(":borrado_fk_usuario", $product->borrado_fk_usuario, PDO::PARAM_INT);
    $result = $db->execute();
    // RETURN
    return $result;
  }

  public function obtener_precio_costo($product)
  {
    $sql = "select * from fi_productos_precios_costo where fk_producto =  :fk_producto   order by rowid DESC  ";

    $db   = $this->db->prepare($sql);
    $db->bindValue(":fk_producto", $product, PDO::PARAM_INT);
    $db->execute();
    $data   = $db->fetch(PDO::FETCH_OBJ);
    // RETURN
    return $data;
  }


  function borrar_imagen_producto($datos)
  {
    $rutaArchivo = ENLACE_FILES_EMPRESAS . 'imagenes/entidad_' . $_SESSION['Entidad'] . '/productos/' . $datos->label;


    if (file_exists($rutaArchivo)) {
      if (unlink($rutaArchivo)) {
        // El archivo se eliminó con éxito
      } else {
        // No se pudo eliminar el archivo
        $consulta['error'] = 1;
        $consulta['datos'] = "No se pudo eliminar la imagen";
        return $consulta;
      }
    }

    $sql = "UPDATE fi_productos_imagenes SET borrado = 1, borrado_fk_usuario = :borrado_fk_usuario, borrado_fecha = now()  where md5(rowid)  = :rowid  and  fk_producto = :fk_producto ";
    $db = $this->db->prepare($sql);
    $db->bindValue(':fk_producto', $datos->fk_producto, PDO::PARAM_INT);
    $db->bindValue(':rowid', $datos->id, PDO::PARAM_STR);
    $db->bindValue(':borrado_fk_usuario', $datos->borrado_fk_usuario, PDO::PARAM_INT);
    $result = $db->execute();

    if ($result) {
      $consulta['error'] = 0;
      $consulta['datos'] = $consulta;
    } else {
      $a = implode('-', $db->errorInfo());
      $a .= implode('-', $this->db->errorInfo());
      $consulta['error'] = 1;
      $consulta['datos'] = $a;
    }
    return $consulta;
  }

  function borrar_imagenes_producto($producto)
  {

    // $imagenes = $this->obtener_imagenes_producto($fk_producto);

    // $borrados = [];
    //borrado fisico
    /* foreach ($imagenes as $imagen) {

      $rutaArchivo = ENLACE_FILES_EMPRESAS . 'imagenes/entidad_' . $_SESSION['Entidad'] . '/productos/' . $imagen->label;


      if (file_exists($rutaArchivo)) {
        $borrados[] = $imagen->descripcion;
        if (unlink($rutaArchivo)) {
          // El archivo se eliminó con éxito
        } else {
          // No se pudo eliminar el archivo
          $consulta['error'] = 1;
          $consulta['datos'] = "No se pudo eliminar la imagen";
          return $consulta;
        }
      }
    } */
    //validate $sql query
    $sql = "update fi_productos_imagenes set 
    borrado_fecha = now(), 
    borrado_fk_usuario = :borrado_fk_usuario, 
    borrado = 1  where fk_producto = :fk_producto";

    $db = $this->db->prepare($sql);
    $db->bindValue(':fk_producto', $producto->id, PDO::PARAM_INT);
    $db->bindValue(':borrado_fk_usuario', $producto->borrado_fk_usuario, PDO::PARAM_INT);

    $result = $db->execute();

    if ($result) {
      $consulta['error'] = 0;
      $consulta['datos'] = $consulta;
    } else {
      $a = implode('-', $db->errorInfo());
      $a .= implode('-', $this->db->errorInfo());
      $consulta['error'] = 1;
      $consulta['datos'] = $a;
    }
    return $consulta;
  }

  public function obtener_categorias()
  {
    $sql = "SELECT * FROM diccionario_categorias WHERE entidad = " . $this->entidad . " AND fk_parent IS NULL  AND activo = 1 AND borrado = 0 ORDER BY label ASC";
    $db = $this->db->prepare($sql);
    $db->execute();

    return $db->fetchAll(PDO::FETCH_OBJ);
  }

  public function cargar_subcategorias_producto()
  {
    $sql = "SELECT * FROM diccionario_categorias WHERE entidad = " . $this->entidad . " AND fk_parent = :fk_parent  AND activo = 1 AND borrado = 0 ORDER BY label ASC";
    $db = $this->db->prepare($sql);
    $db->bindValue(':fk_parent', $this->fk_parent, PDO::PARAM_STR);
    $db->execute();

    return $db->fetchAll(PDO::FETCH_OBJ);
  }

  public function actualizar_precio_costo($datos)
  {
    $sql = "insert into fi_productos_precios_costo (fk_producto, precio, impuesto, fecha, nota, creado_fk_usuario, creado_fecha)
            values (:fk_producto, :precio, :impuesto, NOW() , :nota, :creado_fk_usuario, NOW())";

    $db = $this->db->prepare($sql);
    $db->bindValue(':fk_producto', $datos->fk_producto, PDO::PARAM_INT);
    $db->bindValue(':precio', $datos->precio, PDO::PARAM_STR);
    $db->bindValue(':nota', $datos->nota, PDO::PARAM_STR);
    $db->bindValue(':impuesto', $datos->impuesto, PDO::PARAM_STR);
    $db->bindValue(':creado_fk_usuario', $datos->creado_fk_usuario, PDO::PARAM_INT);

    $result = $db->execute();

    if ($result) {
      $consulta['error'] = 0;
      $consulta['datos'] = $consulta;
    } else {
      $a = implode('-', $db->errorInfo());
      $a .= implode('-', $this->db->errorInfo());
      $consulta['error'] = 1;
      $consulta['datos'] = $a;
    }
    return $consulta;
  }




  public function obtener_resumen_lista_precio($id)
  {

    //obtenemos el ultimo precio agrupando por columna fk_lista para este producto
    $sql = "SELECT l.etiqueta, p.total, p.porcentaje_utilidad, p.porcentaje_descuento, p.fk_lista
FROM fi_productos_precios_clientes p
LEFT JOIN (
    SELECT fk_lista, MAX(rowid) AS max_rowid
    FROM fi_productos_precios_clientes
    WHERE fk_producto = :fk_producto
    GROUP BY fk_lista
) max_p ON p.fk_lista = max_p.fk_lista AND p.rowid = max_p.max_rowid
LEFT JOIN fi_productos_precios_clientes_listas l ON p.fk_lista = l.rowid
WHERE (p.fk_lista IS NOT NULL AND p.rowid = max_p.max_rowid)
   OR (p.fk_lista IS NULL AND p.rowid = (
       SELECT MAX(rowid)
       FROM fi_productos_precios_clientes
       WHERE fk_producto = :fk_producto AND fk_lista IS NULL
   ))
   AND p.fk_producto = :fk_producto;";

    $db = $this->db->prepare($sql);
    $db->bindValue(':fk_producto', $id, PDO::PARAM_INT);
    $db->execute();
    $data   = $db->fetchAll(PDO::FETCH_OBJ);

    return $data;
  }



  public function obtener_lista_politicas($id)
  {

    //obtenemos el ultimo precio agrupando por columna fk_lista para este producto
    $sql = "select * FROM fi_productos_politica_descuentos where fk_producto = :fk_producto and borrado = 0 and activo = 1";

    $db = $this->db->prepare($sql);
    $db->bindValue(':fk_producto', $id, PDO::PARAM_INT);
    $db->execute();
    $data   = $db->fetchAll(PDO::FETCH_OBJ);

    return $data;
  }


  public function diccionario_moneda()
  {
    $sql = "SELECT * FROM diccionario_monedas where 1 and entidad = :entidad";

    $db = $this->db->prepare($sql);
    $db->bindValue(':entidad', $_SESSION['Entidad'], PDO::PARAM_INT);
    $db->execute();
    $data   = $db->fetchAll(PDO::FETCH_OBJ);

    return $data;
  }

  public function obtener_imagenes_producto($id)
  {
    $sql = "select * from fi_productos_imagenes where  fk_producto   = " . $id . " and borrado = 0   order by rowid  DESC ";
    $dbj = $this->db->prepare($sql);
    $dbj->execute();
    $data = $dbj->fetchAll(PDO::FETCH_OBJ);

    return $data;
  }

  public function obtener_precios_costo($id)
  {
    $sql = "select * from fi_productos_precios_costo where fk_producto =  " . $id . "   order by rowid DESC  ";

    $db = $this->db->prepare($sql);
    $db->execute();
    $data = $db->fetchAll(PDO::FETCH_OBJ);

    return $data;
  }

  public function obtener_unidades_catalogo($tipo)
  {
    $sql = "SELECT * FROM `diccionario_catalogo` WHERE activo = 1 and tipo = :tipo AND entidad = :entidad and borrado = 0 and activo = 1";

    $db = $this->db->prepare($sql);

    $db->bindValue(':tipo', $tipo, PDO::PARAM_INT);
    $db->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);
    $db->execute();
    $data = $db->fetchAll(PDO::FETCH_OBJ);

    return $data;
  }


  public function obtener_lista_productos()
  {

    $sql = "select * FROM fi_productos where entidad = :entidad and borrado = 0";



    $db = $this->db->prepare($sql);
    $db->bindValue(':entidad', $_SESSION['Entidad'], PDO::PARAM_INT);
    $db->execute();
    $data   = $db->fetchAll(PDO::FETCH_ASSOC);

    return $data;
  }

  public function fetch_politica($rowid)
  {

    $sql = "SELECT  * FROM fi_productos_politica_descuentos where rowid = :rowid and entidad = :entidad";
    $dbh = $this->db->prepare($sql);
    $dbh->bindValue(':rowid', $rowid, PDO::PARAM_INT);
    $dbh->bindValue(':entidad', $_SESSION['Entidad'], PDO::PARAM_INT);
    $a = $dbh->execute();
    $data = $dbh->fetch(PDO::FETCH_OBJ);

    if ($a) {
      $resultado['data']      =   $data;
      $resultado['exito']   =   $a;
      $resultado['mensaje'] =   "Registro listado con Exito";
    } else {
      $resultado['exito'] = 0;
      $resultado['mensaje'] =  implode(", ", $dbh->errorInfo());
      $this->sql     =   $sql;
      $this->error   =   implode(", ", $dbh->errorInfo());
      $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
      $this->Error_SQL();
    }



    return $resultado;
  }




  public function ver_politica_detalle($rowid)
  {

    $sql = "SELECT  * FROM fi_productos_politica_descuentos_detalle where fk_politica = :fk_politica";
    $dbh = $this->db->prepare($sql);
    $dbh->bindValue(':fk_politica', $rowid, PDO::PARAM_INT);
    $a = $dbh->execute();
    $data = $dbh->fetchAll(PDO::FETCH_OBJ);

    if ($a) {
      $resultado['data']      =   $data;
      $resultado['exito']   =   $a;
      $resultado['mensaje'] =   "Registro listado con Exito";
    } else {
      $resultado['exito'] = 0;
      $resultado['mensaje'] =  implode(", ", $dbh->errorInfo());
      $this->sql     =   $sql;
      $this->error   =   implode(", ", $dbh->errorInfo());
      $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
      $this->Error_SQL();
    }



    return $resultado;
  }
} // fin de la clase --