<?php

class Bodegas extends  Seguridad
{

    private $db;

    public  $id;
    public  $tipo;
    public  $label;
    public  $nota;
    public  $activo;
    public  $entidad;
    public  $usuario;
    public  $fk_bodega_por_defecto;
    public  $documento_tipo;
    public  $motivo;
    public  $lista_bodegas;


    public function __construct($db, $entidad)
    {
        $this->db       = $db;
        $this->entidad  = $entidad;
        parent::__construct($db, $entidad);  // Esto inicializa la clase SEGURIDAD

        // $lista_bodegas_temporal = $this->obtener_bodegas($entidad);
        // if( count($lista_bodegas_temporal) == 0 ){
        //     // Crea una bodega por defecto
        //     $this->tipo = null;
        //     $this->label = "01";
        //     $this->nota = "Almacén principal";
        //     $this->activo = 1;
        //     $this->fk_usuario = $this->usuario;
        //     $this->bodega_defecto = 1;
        //     $respuesta_creado = $this->crear_bodega();

        //     $resultado['rowid'] = $respuesta_creado["id"];
        //     $resultado['tipo'] = $this->tipo;
        //     $resultado['nota'] = $this->nota;
        //     $resultado['bodega_defecto'] = 1;
        //     $resultado['activo'] = 1;
        //     $resultado['entidad'] = $this->entidad;
        //     $this->lista_bodegas[] = $resultado;
        // }else{
        //     $this->lista_bodegas = $lista_bodegas_temporal;
        // }
    }






    public function fetch($id, $campo_busqueda = " rowid ", $tipo = "")
    {

        if (!empty($tipo)) {
            $and = " and tipo = $tipo ";
        } else {
            $and = "";
        }

        $sql = "select * from fi_bodegas   where $campo_busqueda = :rowid $and  ";
        $dbh = $this->db->prepare($sql);
        $dbh->bindValue(':rowid', $id, PDO::PARAM_INT);
        $dbh->execute();
        $datos = $dbh->fetch(PDO::FETCH_ASSOC);
        $this->tipo                          = $datos['tipo'];
        $this->label                         = $datos['label'];
        $this->nota                           = $datos['nota'];
        $this->id                            = $datos['rowid'];
        $this->label                         = $datos['label'];
        $this->activo                        = $datos['activo'];
        $this->nota                          = $datos['nota'];
        $this->bodega_defecto                = $datos['bodega_defecto'];
    }

    public function validar_producto_bodega($id)
    {
        $sql = "select * from fi_bodegas_productos_configuracion where fk_producto = :fk_producto ";
        $db = $this->db->prepare($sql);
        $db->bindValue('fk_producto', $id, PDO::PARAM_INT);
        $db->execute();
        $data   = $db->fetch(PDO::FETCH_OBJ);
        return $data;
    }

    public function actualizar_stock_producto($datos)
    {
        $validar = $this->validar_producto_bodega($datos->fk_prodcuto);

        if ($validar) {
            $sql = "update fi_bodegas_productos_configuracion set
      stock_minimo =  :stock_minimo  ,
      stock_deseado = :stock_deseado
      where
      fk_producto =  :fk_producto";
        } else {
            $sql = "insert into fi_bodegas_productos_configuracion
      (
       fk_producto,
       stock_minimo,
       stock_deseado
       ) VALUE
       (
       :fk_producto,
       :stock_minimo,
       :stock_deseado  )";
        }

        $db = $this->db->prepare($sql);
        $db->bindValue(':fk_producto', $datos->fk_producto, PDO::PARAM_INT);
        $db->bindValue(':stock_minimo', (isset($datos->stock_minimo) ? $datos->stock_minimo : 0), PDO::PARAM_INT);
        $db->bindValue(':stock_deseado', (isset($datos->stock_deseado) ? $datos->stock_deseado : 0), PDO::PARAM_INT);
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

    public function obtener_bodegas($entidad)
    {
        $sql = "SELECT * FROM fi_bodegas WHERE entidad  = '" . $entidad . "' AND activo = 1 ORDER BY label ASC";
        $db = $this->db->prepare($sql);
        $db->execute();
        $data = $db->fetchAll(PDO::FETCH_OBJ);

        return $data;
    }

    public function obtener_stock_bodegas($id)
    {
        $sql = "select b.rowid as bodega_id , b.label,  b.nota , s.* 
            from fi_bodegas_stock  s
            inner join fi_bodegas b on b.rowid = s.fk_bodega
            where s.fk_producto = '" . $id . "'   ";

        $db = $this->db->prepare($sql);
        $db->execute();

        $data = $db->fetchAll(PDO::FETCH_OBJ);

        return $data;
    }


    public function crear_bodega()
    {
        $sql = "
      INSERT INTO fi_bodegas 
      ( tipo, label, nota, activo, creado_fecha, creado_fk_usuario, entidad, bodega_defecto)
      VALUES 
      ( :tipo, :label, :nota, :activo, NOW(), :creado_fk_usuario, :entidad, :bodega_defecto)
      ";

        $db = $this->db->prepare($sql);

        $db->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $db->bindValue(':label', $this->label, PDO::PARAM_STR);
        $db->bindValue(':nota', $this->nota, PDO::PARAM_STR);
        $db->bindValue(':activo', $this->activo, PDO::PARAM_INT);
        $db->bindValue(':creado_fk_usuario', $this->fk_usuario, PDO::PARAM_INT);
        $db->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);
        $db->bindValue(':bodega_defecto', $this->bodega_defecto, PDO::PARAM_INT);

        $ejecutado = $db->execute();

        if (!$ejecutado) {
            $this->sql = $sql;
            $this->error = implode(", ", $db->errorInfo()) . " " . implode(", ", $this->db->errorInfo());
            $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
            $this->Error_SQL();
            $respuesta['exito'] = 0;
            $respuesta['mensaje'] = $this->error;
        } else {
            $this->id = $this->db->lastInsertId();

            if (intval($this->bodega_defecto) == 1) {
                $this->actualizar_bodegas_defecto($this->entidad, $this->id);
            }

            $respuesta['exito'] = 1;
            $respuesta['mensaje'] = "Bodega insertada correctamente";
            $respuesta['id'] = $this->id;
            $respuesta['accion'] = "crear";
        }

        return $respuesta;
    }


    public function actualizar_bodega()
    {
        $sql = "
      UPDATE fi_bodegas
      SET label = :label, 
          nota = :nota, 
          tipo = :tipo,
          principal_facturar = :principal_facturar,
          activo = :activo,
          bodega_defecto = :bodega_defecto
      WHERE rowid = :rowid   AND entidad = :entidad
      ";

        $db = $this->db->prepare($sql);

        $db->bindValue(':label', $this->label, PDO::PARAM_STR);
        $db->bindValue(':nota', $this->nota, PDO::PARAM_STR);
        $db->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $db->bindValue(':principal_facturar', $this->principal_facturar, PDO::PARAM_INT);
        $db->bindValue(':activo', $this->activo, PDO::PARAM_INT);
        $db->bindValue(':rowid', $this->id, PDO::PARAM_INT);
        $db->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);
        $db->bindValue(':bodega_defecto', $this->bodega_defecto ? 1 : 0, PDO::PARAM_INT);

        $ejecutado = $db->execute();

        if (!$ejecutado) {
            $this->sql = $sql;
            $this->error = implode(", ", $db->errorInfo()) . " " . implode(", ", $this->db->errorInfo());
            $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
            $this->Error_SQL();
            $respuesta['exito'] = 0;
            $respuesta['mensaje'] = $this->error;
        } else {
            if (intval($this->bodega_defecto) == 1) {
                $this->actualizar_bodegas_defecto($this->entidad, $this->id);
            }

            $respuesta['exito'] = 1;
            $respuesta['mensaje'] = "Bodega actualizada correctamente";
            $respuesta['accion'] = "actualizar";
        }

        return $respuesta;
    }

    public function borrar_bodega($id)
    {
        $sql = "
      UPDATE fi_bodegas 
      SET borrado = 1, borrado_fecha = NOW(), borrado_fk_usuario = :borrado_fk_usuario 
      WHERE rowid = :id   AND entidad = :entidad
      ";

        $db = $this->db->prepare($sql);

        $db->bindValue(':id', $id, PDO::PARAM_INT);
        $db->bindValue(':borrado_fk_usuario', $this->borrado_fk_usuario, PDO::PARAM_INT);
        $db->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);

        $ejecutado = $db->execute();

        if (!$ejecutado) {
            $this->sql = $sql;
            $this->error = implode(", ", $db->errorInfo()) . " " . implode(", ", $this->db->errorInfo());
            $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
            $this->Error_SQL();
            $respuesta['exito'] = 0;
            $respuesta['mensaje'] = $this->error;
        } else {
            $respuesta['exito'] = 1;
            $respuesta['mensaje'] = "Bodega borrada correctamente";
            $respuesta['accion'] = "borrar";
            $respuesta['id'] = $id;
        }

        return $respuesta;
    }



    private function actualizar_bodegas_defecto($entidad, $id_actual)
    {
        // Preparar la consulta para actualizar otras bodegas
        $sql_update_otros = "
      UPDATE fi_bodegas
      SET bodega_defecto = 0
      WHERE entidad = :entidad AND rowid <> :rowid
      ";
        $stmt = $this->db->prepare($sql_update_otros);

        // Asignar valores a los parámetros
        $stmt->bindValue(':entidad', $entidad);
        $stmt->bindValue(':rowid', $id_actual);

        // Ejecutar la consulta
        return $stmt->execute();
    }




    /*****************************************************************
     * 
     * 
     *  Movimientos de Inventario
     * 
     * 
     * 
     * 
     *******************************************************************/


    function movimiento_stock()
    {


        if ($this->tipo == 'agregar') {
            $formula = "+";
        } else {
            $formula = "-";
        }


        $sql = "update fi_productos set stock = ( COALESCE(stock, 0)  $formula " . $this->valor . ") where rowid = :fk_producto ";
        $db = $this->db->prepare($sql);
        $db->bindValue(':fk_producto', $this->fk_producto, PDO::PARAM_INT);
        $db->execute();

        $sql = "select * from fi_bodegas_stock where fk_bodega = :fk_bodega and fk_producto = :fk_producto ";
        $db = $this->db->prepare($sql);
        $db->bindValue(':fk_bodega', $this->fk_bodega, PDO::PARAM_INT);
        $db->bindValue(':fk_producto', $this->fk_producto, PDO::PARAM_INT);
        $db->execute();

        $resultado = $db->fetch(PDO::FETCH_OBJ);
        $actual_stock_en_bodega_definida = 0;

        if (!empty($resultado->rowid)) {
            if ($this->tipo == 'agregar') {
                $nuevo_stock = $this->valor + $resultado->stock;
            } else {
                $nuevo_stock = $this->valor - $resultado->stock;
            }

            $sql = "update fi_bodegas_stock set stock = (stock $formula " . $this->valor . " ), creado_fk_usuario = :creado_fk_usuario, creado_fecha = NOW() where fk_bodega = :fk_bodega and fk_producto = :fk_producto   ";
            $db = $this->db->prepare($sql);
            $db->bindValue(':fk_bodega', $this->fk_bodega, PDO::PARAM_INT);
            $db->bindValue(':fk_producto', $this->fk_producto, PDO::PARAM_INT);
            $db->bindValue(':creado_fk_usuario', $this->creado_fk_usuario, PDO::PARAM_INT);
            $db->execute();

            $actual_stock_en_bodega_definida = $resultado->stock;
        } else {
            if ($this->tipo == 'agregar') {
                $stock = $this->valor;
            } else {
                $stock = $this->valor * -1;
            }

            $sql = "INSERT INTO fi_bodegas_stock (fk_bodega ,fk_producto ,stock, creado_fk_usuario, creado_fecha ) VALUES (:fk_bodega ,:fk_producto ,:stock, :creado_fk_usuario, NOW() ) ";
            $db = $this->db->prepare($sql);
            $db->bindValue(':fk_bodega', $this->fk_bodega, PDO::PARAM_INT);
            $db->bindValue(':fk_producto', $this->fk_producto, PDO::PARAM_INT);
            $db->bindValue(':stock', $stock, PDO::PARAM_INT);
            $db->bindValue(':creado_fk_usuario', $this->creado_fk_usuario, PDO::PARAM_INT);
            $db->execute();
        }

        // Movimiento de Stock
        $sql = "insert into 
        fi_bodegas_movimientos (fk_bodega, fk_producto, tipo, valor, stock_actual,fecha,motivo,usuario , creado_fecha , creado_fk_usuario , documento_tipo , documento_fk ) Values 
        (:fk_bodega, :fk_producto, :tipo, :valor, :stock_actual, NOW() , :motivo, :usuario , NOW() , :creado_fk_usuario , :documento_tipo , :documento_fk ) ";
        $db = $this->db->prepare($sql);
        $db->bindValue(':fk_bodega', $this->fk_bodega, PDO::PARAM_INT);
        $db->bindValue(':fk_producto', $this->fk_producto, PDO::PARAM_INT);
        $db->bindValue(':tipo', ($this->tipo == "agregar") ? 0 : 1, PDO::PARAM_INT);
        $db->bindValue(':valor', $this->valor, PDO::PARAM_INT);
        $db->bindValue(':stock_actual', ($this->tipo == "agregar") ? ($actual_stock_en_bodega_definida + $this->valor) : ($actual_stock_en_bodega_definida - $this->valor), PDO::PARAM_STR);
        $db->bindValue(':motivo', (!empty($this->motivo)) ? $this->motivo : '', PDO::PARAM_STR);
        $db->bindValue(':usuario', $this->usuario, PDO::PARAM_INT);
        $db->bindValue(':creado_fk_usuario', $this->usuario, PDO::PARAM_INT);
        $db->bindValue(':documento_tipo', $this->documento_tipo, PDO::PARAM_STR);
        $db->bindValue(':documento_fk', $this->documento_fk, PDO::PARAM_INT);



        $result = $db->execute();


        if ($result) {
            $consulta['error'] = 0;
            $consulta['datos'] = $consulta;
        } else {
            echo $a = implode('-', $db->errorInfo());
            $consulta['error'] = 1;
            $consulta['datos'] = $a;
        }
        return $consulta;
    }


    public function bodega_por_defecto($entidad)
    {
        $sql = "SELECT rowid FROM fi_bodegas WHERE  entidad = :entidad AND bodega_defecto = 1 AND activo = 1 ";

        $db = $this->db->prepare($sql);
        $db->bindParam(':entidad', $entidad, PDO::PARAM_INT);
        $db->execute();
        $result = $db->fetch(PDO::FETCH_ASSOC);
        return $result['rowid'];
    }


    public function mover_bodega_en_venta($datos,   $usuarioid, $id_bodega = 0, $agregar_disminuir = 'agregar')
    {
        $productos = $this->contar_productos_en_factura($datos->id, $datos->documento_detalle);
        // $this->fk_bodega_por_defecto =  (!is_null($bodega_por_defecto )) ? $bodega_por_defecto  :  $this->bodega_por_defecto($datos->entidad);
        // $this->fk_bodega_por_defecto ;
        if ($id_bodega != 0) {
            $this->fk_bodega_por_defecto    =  $id_bodega;
        } else {
            $this->fk_bodega_por_defecto    =  $this->bodega_por_defecto($datos->entidad);
        }
        if ($this->fk_bodega_por_defecto == 0) {
            $respuesta_creado = $this->crear_bodega_inicial($datos->entidad);
            $this->fk_bodega_por_defecto    =  $respuesta_creado["id"];
        }


        if (sizeof($productos) > 0) {
            $sizeproductos = sizeof($productos);

            foreach ($productos as $producto) {
                $this->fk_producto       = $producto->fk_producto;
                $this->fk_bodega         = $this->fk_bodega_por_defecto;
                $this->valor             = $producto->cantidad;
                $this->tipo              = $agregar_disminuir;
                if(empty($this->motivo) ){
                    $this->motivo            = "Factura Venta";
                }
                $this->usuario           = $usuarioid;
                $this->creado_fk_usuario = $usuarioid;
                $this->documento_fk      = $datos->id;

                $result = $this->movimiento_stock();
            }

            $this->fetch($this->fk_bodega_por_defecto);
            $datos->registrar_log_documento(   $_SESSION['usuario'] 
                                        , 1
                                        ,"{$sizeproductos} Producto(s) Movidos al inventario de Almacen <strong>{$this->label}</strong> ");
        }

        return sizeof($productos);
    }

    public function mover_bodega_en_compra($datos,   $usuarioid, $id_bodega = 0, $agregar_disminuir = 'agregar')
    {

        // $this->documento_tipo           = (empty($this->documento_tipo  ))  ?  "compra"         : $this->documento_tipo;  
        $this->motivo                   = (empty($this->motivo))  ?  "Factura Compra" : $this->motivo;


        $productos = $this->contar_productos_en_compra($datos);
        if ($id_bodega != 0) {
            $this->fk_bodega_por_defecto    =  $id_bodega;
        } else {
            $this->fk_bodega_por_defecto    =  $this->bodega_por_defecto($datos->entidad);
        }
        if ($this->fk_bodega_por_defecto == 0) {
            $respuesta_creado = $this->crear_bodega_inicial($datos->entidad);
            $this->fk_bodega_por_defecto    =  $respuesta_creado["id"];
        }


        if (sizeof($productos) > 0) {

            foreach ($productos as $producto) {

                $this->fk_producto       = $producto->fk_producto;
                $this->fk_bodega         = $this->fk_bodega_por_defecto;
                $this->valor             = $producto->cantidad;
                $this->tipo              = $agregar_disminuir;
                $this->usuario           = $usuarioid;
                $this->creado_fk_usuario = $usuarioid;
                $this->documento_fk      = $datos->id;

                $result = $this->movimiento_stock();
            }
        }
    }

    public function recuperar_bodega_en_compra($datos,   $usuarioid, $agregar_disminuir = 'agregar')
    {
        $tipo = 1;
        if ($agregar_disminuir == 'agregar') { $tipo = 0; } else { $tipo = 1; }
        $productos = $this->obtener_productos_de_movimiento($datos, ( $tipo == 0?1:0 ) );
// echo 'Qué paso '.json_encode($productos);
        if (sizeof($productos) > 0) {
            foreach ($productos as $producto) {

                $this->fk_producto       = $producto->fk_producto;
                $this->fk_bodega         = $producto->fk_bodega;
                $this->valor             = $producto->valor;
                $this->tipo              = $agregar_disminuir;
                $this->usuario           = $usuarioid;
                $this->creado_fk_usuario = $usuarioid;
                $this->documento_fk      = $datos->id;
                $result = $this->movimiento_stock();
            }
        }
    }


    public function crear_bodega_inicial($entidad)
    {
        // Crea una bodega por defecto
        $this->tipo = null;
        $this->label = "01";
        $this->nota = "Almacén principal";
        $this->activo = 1;
        $this->fk_usuario = $this->usuario;
        $this->bodega_defecto = 1;
        $this->entidad = $entidad;
        $respuesta = $this->crear_bodega();
        return $respuesta;
    }


    // Esta preparado para albaran y para factura de Compra 


    public function contar_productos_en_compra($compra)
    {
        $sql = "Select 
        
        sum(detalle.cantidad) as cantidad ,
        detalle.fk_producto 

        from  {$compra->documento_detalle} detalle
        left join fi_productos p on p.rowid = detalle.fk_producto 

            where detalle.fk_documento  = {$compra->id} and  detalle.fk_producto > 0 AND    p.tipo = 1    group by fk_producto  ";


        $db = $this->db->prepare($sql);
        $db->execute();

        return $db->fetchAll(PDO::FETCH_OBJ);
    }


    public function contar_productos_en_factura($id_documento, $nombre_documento_detalle)
    {
        $sql = "Select 

        sum(detalle.cantidad) as cantidad ,
        detalle.fk_producto               

        from {$nombre_documento_detalle} detalle 
        left join fi_productos p on p.rowid = detalle.fk_producto 
              where 
              detalle.fk_documento  = $id_documento and  detalle.fk_producto > 0 AND   p.tipo = 1  
              
              group by detalle.fk_producto  ";

        $db = $this->db->prepare($sql);
        $db->execute();

        return $db->fetchAll(PDO::FETCH_OBJ);
    }


    public function obtener_productos_de_movimiento($datos, $tipo)
    {
        $retorno = [];
        if($datos->estado == 6){ //Anulación
            $sql = "SELECT bod.rowid, bod.fk_producto, bod.fk_bodega, bod.tipo, bod.documento_tipo, bod.documento_fk, bod.valor
            FROM fi_bodegas_movimientos bod
            WHERE bod.documento_tipo  = '{$this->documento_tipo}' and bod.documento_fk = {$datos->id} AND bod.tipo = {$tipo} AND bod.borrado = 0  ";
            $db = $this->db->prepare($sql);
            $db->execute();
            $retorno = $db->fetchAll(PDO::FETCH_OBJ);
        }
        if($datos->estado == 5){ //Cancelación
            /* Obtengo el movimiento en bodega original del Albaran */
            $sqlAlbaran = "SELECT bod.rowid, bod.fk_producto, bod.fk_bodega, bod.tipo, bod.documento_tipo, bod.documento_fk, bod.valor
                    FROM fi_bodegas_movimientos bod
                    WHERE bod.documento_tipo = 'albaran_compra'
                    AND bod.documento_fk = {$datos->id} AND bod.tipo = 0 AND bod.borrado = 0;";
                    
            $dbAlbaran = $this->db->prepare($sqlAlbaran);
            $dbAlbaran->execute();
            $productos_albaran = $dbAlbaran->fetchAll(PDO::FETCH_OBJ);
            
            /* Obtengo los productos y cantidades de los detalles de las compras que se originaron del Albarán */
            $sqlCompras = "SELECT det.fk_producto, det.cantidad
            FROM fi_europa_compras cab 
            INNER JOIN fi_europa_compras_detalle det ON cab.rowid = det.fk_documento
            LEFT JOIN (
                SELECT mov.destino_fk_documento, mov.destino_fk_documento_detalle
                FROM fi_europa_documentos_movimientos_detalles mov
                WHERE mov.origen_documento = '{$datos->documento}'
                AND mov.destino_documento = 'fi_europa_compras'
                AND mov.origen_fk_documento = {$datos->id}
                AND mov.borrado = 0
            ) as movimiento ON movimiento.destino_fk_documento = cab.rowid
            WHERE movimiento.destino_fk_documento_detalle = det.rowid
            AND cab.estado = 0;";
            // TODO: Se está considerando Compras en estado CERO, pero lo correcto sería las de estado=1 (fiscalizadas)
            
            $dbCompras = $this->db->prepare($sqlCompras);
            $dbCompras->execute();
            $productos_compras = $dbCompras->fetchAll(PDO::FETCH_OBJ);

            if (sizeof($productos_albaran) > 0) {
                foreach ($productos_albaran as $key => $producto_albaran) {
                    foreach ($productos_compras as $producto_compra) {
                        // echo 'alb anti '. $producto_albaran->valor;
                        // echo 'com anti '. $producto_compra->cantidad;
                        if($producto_albaran->fk_producto == $producto_compra->fk_producto){
                            // echo 'entro';
                            $productos_albaran[$key]->valor == 77;
                        }
                    }
                    // echo 'alb nuevo '. json_encode($productos_albaran);
                }
            }
            $retorno = $productos_albaran;
        }


        return $retorno;
    }
}
