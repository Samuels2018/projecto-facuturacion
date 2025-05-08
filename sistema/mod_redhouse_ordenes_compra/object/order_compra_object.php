<?php

include_once(ENLACE_SERVIDOR."mod_seguridad/object/seguridad.object.php");

class Orden extends Seguridad
{
    private $db;

    public $rowid;
    public $orden_consecutivo;
    public $fk_proveedor;
    public $fk_proyecto;
    public $fk_moneda;
    public $orden_tipo_cambio;
    public $orden_notas;
    public $orden_estado;
    public $creado_fk_usuario;
    public $borrado;
    public $borrado_fecha;
    public $borrado_fk_usuario;

    // Constructor que acepta la conexión a la base de datos
    public function __construct($db, $entidad = 3)
    {
        $this->db = $db;
        $this->entidad = $entidad;

        parent::__construct();  // Inicializa la clase Seguridad
    }

    // Función para obtener una orden por su rowid
    public function fetch($id)
{
    $query = "SELECT a.rowid, a.orden_consecutivo, a.fk_proveedor, a.fk_proyecto, 
                     a.fk_moneda, a.fk_forma_pago, a.orden_tipo_cambio, 
                     a.fecha_creacion, a.fecha_vigencia, a.orden_notas, 
                     a.orden_estado, a.creado_fk_usuario, a.borrado, 
                     a.borrado_fecha, a.borrado_fk_usuario,
                     CONCAT(t.nombre, ' ', t.apellidos) AS proveedor_nombre_completo, 
                     p.proyecto_descripcion, p.proyecto_lugar, 
                     DATE(p.proyecto_fecha) AS proyecto_fecha, 
                     TIME(p.proyecto_fecha) AS proyecto_hora,
                     m.simbolo AS moneda_simbolo
              FROM a_medida_redhouse_orden_compra a
              LEFT JOIN fi_terceros t ON a.fk_proveedor = t.rowid
              LEFT JOIN a_medida_redhouse_proyecto p ON a.fk_proyecto = p.rowid
              LEFT JOIN diccionario_monedas m ON a.fk_moneda = m.rowid
              WHERE a.rowid = ? AND a.borrado = 0";

    $stmt = $this->db->prepare($query);
    $stmt->bindParam(1, $id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $this->rowid = $row['rowid'];
        $this->orden_consecutivo = $row['orden_consecutivo'];
        $this->fk_proveedor = $row['fk_proveedor'];
        $this->fk_proyecto = $row['fk_proyecto'];
        $this->fk_moneda = $row['fk_moneda'];
        $this->fk_forma_pago = $row['fk_forma_pago'];
        $this->orden_tipo_cambio = $row['orden_tipo_cambio'];
        $this->fecha_creacion = $row['fecha_creacion'];
        $this->fecha_vigencia = $row['fecha_vigencia'];
        $this->orden_notas = $row['orden_notas'];
        $this->orden_estado = $row['orden_estado'];
        $this->creado_fk_usuario = $row['creado_fk_usuario'];
        $this->borrado = $row['borrado'];
        $this->borrado_fecha = $row['borrado_fecha'];
        $this->borrado_fk_usuario = $row['borrado_fk_usuario'];

        // Nuevos campos del proveedor, proyecto y moneda
        $this->proveedor_nombre_completo = $row['proveedor_nombre_completo'];
        $this->proyecto_descripcion = $row['proyecto_descripcion'];
        $this->proyecto_lugar = $row['proyecto_lugar'];
        $this->proyecto_fecha = $row['proyecto_fecha'];
        $this->proyecto_hora = $row['proyecto_hora'];
        $this->moneda_simbolo = $row['moneda_simbolo']; // Símbolo de la moneda
    }
}

    

public function cargar_configuracion_borrador($entidad)
{
    // Consulta para obtener el último valor del borrador
    $sql = "SELECT MAX(CAST(SUBSTRING(orden_consecutivo, -3) AS UNSIGNED)) as ultimo_borrador 
            FROM a_medida_redhouse_orden_compra 
            WHERE orden_consecutivo LIKE 'BORRADOR-%'";

    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si hay un resultado, incrementar el valor del último borrador
    if ($resultado && $resultado['ultimo_borrador'] !== null) {
        $this->siguiente_borrador = $resultado['ultimo_borrador'] + 1;
    } else {
        // Si no hay borradores previos, iniciar desde 1
        $this->siguiente_borrador = 1;
    }

    // Inicializar otros campos a valores por defecto o nulos
    $this->fk_proveedor = null;          // Inicializamos fk_proveedor a null
    $this->fk_proyecto = null;           // Inicializamos fk_proyecto a null
    $this->fk_moneda = null;             // Inicializamos fk_moneda a null
    $this->orden_tipo_cambio = null;     // Inicializamos orden_tipo_cambio a null
    $this->orden_notas = '';             // Notas vacías por defecto
    $this->orden_estado = 0;             // Estado inicial de borrador
    $this->creado_fk_usuario = null;     // Usuario que crea el borrador
    $this->borrado = 0;                  // Borrado inicial como 0
    $this->borrado_fecha = null;         // Fecha de borrado null por defecto
    $this->borrado_fk_usuario = null;    // Usuario que borró la orden (null por defecto)
}


public function actualizar_informacion_orden()
{

    try {
        // SQL para actualizar la tabla con los datos proporcionados
        $sql = "UPDATE a_medida_redhouse_orden_compra
                SET fk_proveedor = :fk_proveedor,
                    fk_proyecto = :fk_proyecto,
                    fk_moneda = :fk_moneda,
                    fk_forma_pago = :fk_forma_pago,
                    orden_notas = :orden_notas,
                    orden_estado = :orden_estado,
                WHERE rowid = :rowid";

        $dbh = $this->db->prepare($sql);

        // Vincular los valores desde los datos recibidos
        $dbh->bindValue(':fk_proveedor', $this->fk_proveedor, PDO::PARAM_INT);
        $dbh->bindValue(':fk_proyecto', $this->fk_proyecto, PDO::PARAM_INT);
        $dbh->bindValue(':fk_moneda', $this->fk_moneda, PDO::PARAM_INT);
        $dbh->bindValue(':fk_forma_pago', $this->fk_forma_pago, PDO::PARAM_INT);
        $dbh->bindValue(':orden_notas', $this->orden_notas, PDO::PARAM_STR);
        $dbh->bindValue(':orden_estado', $this->orden_estado, PDO::PARAM_INT);
        $dbh->bindValue(':rowid', $this->rowid, PDO::PARAM_INT);

        // Ejecutar el update
        if ($dbh->execute()) {
            // Si la actualización fue exitosa, devolvemos el ID y mensaje de éxito
            return json_encode([
                'id' => $this->rowid,
                'mensaje_txt' => 'Información actualizada correctamente' ,
                'error' => 0
            ]);
        } else {
            // Si hubo algún error en la consulta
            $errorInfo = $dbh->errorInfo();
            throw new Exception("Error en la consulta: " . $errorInfo[2]);
        }
    } catch (Exception $e) {
        // Devolvemos un mensaje de error
        return json_encode([
            'id' => 0,
            'mensaje_txt' => 'Error al actualizar la informacion de la orden: ' . $e->getMessage(),
            'error' => 1
        ]);
    }

}



//Vamos a crear una orden
public function crear_orden()
{
    try {
        // Generar el nuevo consecutivo basado en el rowid
        $fecha_actual = date('Ymd');
        $orden_consecutivo = 'ORD-' . $fecha_actual . '-' . str_pad($this->rowid, 5, '0', STR_PAD_LEFT);

        // SQL para actualizar la tabla con los datos proporcionados
        $sql = "UPDATE a_medida_redhouse_orden_compra
                SET fk_proveedor = :fk_proveedor,
                    fk_proyecto = :fk_proyecto,
                    fk_moneda = :fk_moneda,
                    fk_forma_pago = :fk_forma_pago,
                    orden_notas = :orden_notas,
                    orden_estado = :orden_estado,
                    orden_consecutivo = :orden_consecutivo,
                    fecha_creacion = :fecha_creacion,
                    fecha_vigencia = :fecha_vigencia
                WHERE rowid = :rowid";

        $dbh = $this->db->prepare($sql);

        // Vincular los valores desde los datos recibidos
        $dbh->bindValue(':fk_proveedor', $this->fk_proveedor, PDO::PARAM_INT);
        $dbh->bindValue(':fk_proyecto', $this->fk_proyecto, PDO::PARAM_INT);
        $dbh->bindValue(':fk_moneda', $this->fk_moneda, PDO::PARAM_INT);
        $dbh->bindValue(':fk_forma_pago', $this->fk_forma_pago, PDO::PARAM_INT);
        $dbh->bindValue(':orden_notas', $this->orden_notas, PDO::PARAM_STR);
        $dbh->bindValue(':orden_estado', $this->orden_estado, PDO::PARAM_INT);
        $dbh->bindValue(':orden_consecutivo', $orden_consecutivo, PDO::PARAM_STR);
        $dbh->bindValue(':fecha_creacion', $this->fecha_creacion, PDO::PARAM_STR);
        $dbh->bindValue(':fecha_vigencia', $this->fecha_vigencia, PDO::PARAM_STR);
        $dbh->bindValue(':rowid', $this->rowid, PDO::PARAM_INT);

        // Ejecutar el update
        if ($dbh->execute()) {
            // Si la actualización fue exitosa, devolvemos el ID y mensaje de éxito
            return json_encode([
                'id' => $this->rowid,
                'mensaje_txt' => 'Orden actualizada correctamente' ,
                'error' => 0
            ]);
        } else {
            // Si hubo algún error en la consulta
            $errorInfo = $dbh->errorInfo();
            throw new Exception("Error en la consulta: " . $errorInfo[2]);
        }
    } catch (Exception $e) {
        // Devolvemos un mensaje de error
        return json_encode([
            'id' => 0,
            'mensaje_txt' => 'Error al actualizar la orden: ' . $e->getMessage(),
            'error' => 1
        ]);
    }
}






public function nuevo()
{
    // Cargar la configuración del último borrador
    $this->cargar_configuracion_borrador($this->entidad);

    // Generar el consecutivo en formato BORRADOR-YYYYMMDD-XXX
    $fecha_actual = date('Ymd');
    $query = "SELECT COUNT(*) + 1 as siguiente_borrador 
              FROM a_medida_redhouse_orden_compra 
              WHERE DATE(creado_fecha) = CURDATE()";

    $stmt = $this->db->prepare($query);
    $stmt->execute();
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    $siguiente_borrador = $resultado['siguiente_borrador'];

    // Formato del consecutivo: BORRADOR-YYYYMMDD-XXX
    $orden_consecutivo = 'BORRADOR-' . $fecha_actual . '-' . str_pad($siguiente_borrador, 3, '0', STR_PAD_LEFT);

    // Insertar el nuevo borrador en la tabla 'a_medida_redhouse_orden_compra'
    $sql = "INSERT INTO a_medida_redhouse_orden_compra
                (fk_proveedor,
                 fk_proyecto,
                 fk_moneda,
                 orden_tipo_cambio,
                 orden_notas,
                 orden_estado,
                 creado_fk_usuario,
                 borrado,
                 orden_consecutivo,
                 creado_fecha,
                 borrado_fecha,
                 borrado_fk_usuario) 
            VALUES
                (:fk_proveedor,
                 :fk_proyecto,
                 :fk_moneda,
                 :orden_tipo_cambio,
                 :orden_notas,
                 :orden_estado,
                 :creado_fk_usuario,
                 :borrado,
                 :orden_consecutivo,
                 NOW(),
                 NULL, 
                 NULL)";

    $dbh = $this->db->prepare($sql);
    $dbh->bindValue(':fk_proveedor', $this->fk_proveedor, PDO::PARAM_INT);
    $dbh->bindValue(':fk_proyecto', $this->fk_proyecto, PDO::PARAM_INT);
    $dbh->bindValue(':fk_moneda', $this->fk_moneda, PDO::PARAM_INT);
    $dbh->bindValue(':orden_tipo_cambio', $this->orden_tipo_cambio, PDO::PARAM_STR);
    $dbh->bindValue(':orden_notas', $this->orden_notas, PDO::PARAM_STR);
    $dbh->bindValue(':orden_estado', $this->orden_estado, PDO::PARAM_INT);
    $dbh->bindValue(':creado_fk_usuario', $this->creado_fk_usuario, PDO::PARAM_INT); // Usuario que crea el borrador
    $dbh->bindValue(':borrado', 0, PDO::PARAM_INT); // No borrado por defecto
    $dbh->bindValue(':orden_consecutivo', $orden_consecutivo, PDO::PARAM_STR);

    $dbh->execute();

    // Verificar si hubo algún error en la ejecución
    if ($dbh->errorCode() !== '00000') {
        $errorInfo = $dbh->errorInfo();
        throw new Exception("Error en la ejecución de la consulta: " . $errorInfo[2]);
    }

    // Obtener el ID insertado
    $id = $this->db->lastInsertId();
    $this->rowid = $id;

    return $id;
}

// Funcion para insertar los servicios
public function  servicios_insertar() {

    $sql = "
    INSERT INTO 
    a_medida_redhouse_orden_compra_servicios 
    (
        fk_orden,
        fk_producto,
        cantidad,
        precio_unitario,
        precio_subtotal,
        precio_tipo_impuesto,
        precio_total,
        creado_fecha,
        creado_usuario,
        comentario,
        cantidad_dias,
        fk_estado,
        tipo_duracion
    ) VALUES (
        :fk_orden,
        :fk_producto,
        :cantidad,
        :precio_unitario,
        :precio_subtotal,
        :precio_tipo_impuesto,
        :precio_total,
        NOW(),
        :creado_usuario,
        :comentario,
        :cantidad_dias,
        1,
        :tipo_duracion
    )
    ";

    if(intval($this->cantidad_dias)<=0)
    {
        $cantidad_dias = 1;
    }else{
      $cantidad_dias = intval($this->cantidad_dias);
    }
    
    if(intval($this->servicio_tipo_duracion)<=0)
    {
        $this->servicio_tipo_duracion = 1;
        $cantidad_horas = 1;
    }else{
        $cantidad_horas = intval($this->servicio_tipo_duracion);
    }


    //Generamos el total sin impuesto
    //Aqui le sumamos las horas y los dias tambien
    $total_sin_impuesto = ($this->servicio_precio_unitario * $this->servicio_cantidad * $cantidad_horas * $cantidad_dias);

   $subtotal = $total_sin_impuesto;
   $impuesto = $subtotal * floatval($this->servicio_precio_tipo_impuesto)/100; 
    //TOTAL GENERAL
    $total_general = $subtotal + $impuesto;


    $db = $this->db->prepare($sql);

    // Bind de los valores usando las propiedades de $this
    $db->bindValue(":fk_orden" , $this->fk_orden                         , PDO::PARAM_INT);
    $db->bindValue(":fk_producto"   , $this->fk_producto                , PDO::PARAM_INT);
    $db->bindValue(":cantidad"      , $this->servicio_cantidad          , PDO::PARAM_INT);
    $db->bindValue(":precio_unitario", $this->servicio_precio_unitario  , PDO::PARAM_STR);
    $db->bindValue(":precio_subtotal",$subtotal , PDO::PARAM_STR);
    $db->bindValue(":precio_tipo_impuesto" ,$impuesto, PDO::PARAM_STR);
    $db->bindValue(":precio_total",$total_general, PDO::PARAM_STR);
    $db->bindValue(":creado_usuario", $this->creado_fk_usuario, PDO::PARAM_INT);
    $db->bindValue(":comentario", $this->servicio_comentario, PDO::PARAM_STR);
    $db->bindValue(":cantidad_dias", $this->cantidad_dias, PDO::PARAM_INT);
    $db->bindValue(":tipo_duracion", $this->servicio_tipo_duracion, PDO::PARAM_STR);

    $a = $db->execute();
    $idCreado =  $this->db->lastInsertId();

        if ($a) {
            $respuesta['error'] = false;
            $respuesta['id'] = $idCreado;
            $respuesta['respuesta'] = "Item Creado con Exito";

            return $respuesta ;
            
        } else {
        
            $this->sql = $sql;
            $this->error = implode(", ", $db->errorInfo()).implode(", ", $this->db->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
            $this->Error_SQL();
            
            $respuesta['error']     = true;
            $respuesta['id']        = 0;
            $respuesta['respuesta'] =  $this->error;

            return $respuesta ;

        }

    
} // fin de la funcion 



public function servicios_remover($id_servicio)
{

    // Preparamos la consulta SQL para eliminar el registro
    $sql = "DELETE FROM a_medida_redhouse_orden_compra_servicios 
            WHERE rowid = :rowid AND fk_orden = :fk_orden";

    try {
        // Preparamos la declaración
        $stmt = $this->db->prepare($sql);

        // Vinculamos los parámetros a los valores
        $stmt->bindParam(':rowid', $id_servicio, PDO::PARAM_INT);
        $stmt->bindParam(':fk_orden', $this->fk_orden, PDO::PARAM_INT);

        // Ejecutamos la consulta
        $stmt->execute();

        // Comprobamos si se eliminaron filas
        if ($stmt->rowCount() > 0) {
            $respuesta['error'] = false;
            $respuesta['respuesta'] = "Item Eliminado con Exito";
            return $respuesta;
        } else {
                $this->error = implode(", ", $db->errorInfo()).implode(", ", $this->db->errorInfo());
                $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
                $this->Error_SQL();
                $respuesta['error']     = true;
                $respuesta['respuesta'] = $this->error;
                return $respuesta;
        }
    } catch (PDOException $e) {
        // Manejo de errores
        return [
            'error' => true,
            'mensaje' => "Error al eliminar el servicio: " . $e->getMessage()
        ];
    }
}



// Función para obtener las monedas
public function monedas()
{
        $sql = "SELECT * FROM diccionario_monedas WHERE entidad = :entidad";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':entidad', $_SESSION['Entidad'], PDO::PARAM_INT);
        $stmt->execute();
        while ($data = $stmt->fetch(PDO::FETCH_OBJ)) {
            $this->diccionario_moneda[$data->rowid]['etiqueta'] = $data->etiqueta;
            $this->diccionario_moneda[$data->rowid]['simbolo']  = $data->simbolo;
            $this->diccionario_moneda[$data->rowid]['codigo']   = $data->codigo;
        }
}

    // Función para obtener las formas de pago
    public function diccionario_pago()
    {
        $sql = "SELECT * FROM diccionario_formas_pago WHERE entidad = :entidad AND activo = 1 AND borrado = 0";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);
        $stmt->execute();
        while ($data = $stmt->fetch(PDO::FETCH_OBJ)) {
            $this->diccionario_pago[$data->rowid]['label'] = $data->label; 
        }
    }
}
