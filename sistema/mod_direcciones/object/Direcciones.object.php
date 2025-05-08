<?php

class Direccion extends  Seguridad
{

    private     $db;
    private $entidad;

    public $id;
    public $nombre_entidad;
    public $tipo_entidad;
    public $descripcion;
    public $codigo_pais;
    public $codigo_postal;
    public $codigo_poblacion;
    public $codigo_provincia;
    public $codigo_municipio;
    public $codigo_distrito;
    public $codigo_barrio;
    public $latitud;
    public $longitud;
    public $direccion;
    public $otros_datos;
    public $creado_fk_usuario;
    public $borrado_fk_usuario;
    public $fk_entidad;
    public $fk_factura;
    public $activo;

    function  __construct($db, $entidad )
    {
        $this->db           = $db;
        $this->entidad      = $entidad;
        parent::__construct($db, $entidad);  // Esto inicializa la clase SEGURIDAD
    }

    function fetch($id)
    {
        $sql = "SELECT  u.*
                FROM diccionario_direccion u  
                WHERE u.rowid = :rowid";

        $db = $this->db->prepare($sql);
        $db->bindValue('rowid', $id, PDO::PARAM_STR);

        $ejecutado = $db->execute();

        $u = $db->fetch(PDO::FETCH_ASSOC);

        $this->id            = $u['rowid'];
        $this->entidad        = $u['entidad'];
        $this->tipo_entidad        = $u['tipo_entidad'];
        $this->descripcion        = $u['descripcion'];
        $this->codigo_pais        = $u['codigo_pais'];
        $this->codigo_postal        = $u['codigo_postal'];
        $this->codigo_poblacion        = $u['codigo_poblacion'];
        $this->codigo_provincia        = $u['codigo_provincia'];
        $this->codigo_municipio        = $u['codigo_municipio'];
        $this->codigo_distrito        = $u['codigo_distrito'];
        $this->codigo_barrio        = $u['codigo_barrio'];
        $this->latitud        = $u['latitud'];
        $this->longitud        = $u['longitud'];
        $this->direccion        = $u['direccion'];
        $this->otros_datos        = $u['otros_datos'];
        $this->activo = $u['activo'];

        if (!$ejecutado) {
            $this->sql = $sql;
            $this->error = implode(", ", $db->errorInfo())." ". implode(", ", $this->db->errorInfo() );
            $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
            $this->Error_SQL();
            $respuesta['exito']     =   0;
            $respuesta['mensaje']   =   $this->error;
        } else {
            $respuesta['exito']     =   1;
            $respuesta['mensaje']   =   "Cuenta Editada Correctamente";
    
        }
    
        return $respuesta;
    }

    public function obtener_direcciones(){
        $sql = "
                SELECT 0 AS rowid, direccion FROM fi_terceros WHERE rowid = :entidad 
                UNION ALL
                SELECT rowid, descripcion AS direccion FROM diccionario_direccion WHERE entidad = :entidad";

        $db = $this->db->prepare($sql);               
        $db->bindValue(':entidad', $this->fk_entidad, PDO::PARAM_INT);
        $db->execute();
        return $db->fetchAll(PDO::FETCH_OBJ);
    }

    public function crear_direccion()
    {
        $sql = "
        INSERT INTO diccionario_direccion
        SET rowid = :rowid,
            entidad = :entidad,
            tipo_entidad = :tipo_entidad,
            descripcion = :descripcion,
            codigo_pais = :codigo_pais,
            codigo_postal = :codigo_postal,
            codigo_poblacion = :codigo_poblacion,
            codigo_provincia = :codigo_provincia,
            codigo_municipio = :codigo_municipio,
            codigo_distrito = :codigo_distrito,
            codigo_barrio = :codigo_barrio,
            latitud = :latitud,
            longitud = :longitud,
            direccion = :direccion,
            otros_datos = :otros_datos,
            activo = :activo,
            creado_fk_usuario = :creado_fk_usuario,
            creado_fecha = now()";

        $db = $this->db->prepare($sql);

        // Asignar valores a los parámetros
        $db->bindValue(':rowid', $this->id);
        $db->bindValue(':entidad', $this->fk_entidad);
        $db->bindValue(':tipo_entidad', $this->tipo_entidad);
        $db->bindValue(':descripcion', $this->descripcion);
        $db->bindValue(':codigo_pais', $this->codigo_pais);
        $db->bindValue(':codigo_postal', $this->codigo_postal);
        $db->bindValue(':codigo_poblacion', $this->codigo_poblacion);
        $db->bindValue(':codigo_provincia', $this->codigo_provincia);
        $db->bindValue(':codigo_municipio', $this->codigo_municipio);
        $db->bindValue(':codigo_distrito', $this->codigo_distrito);
        $db->bindValue(':codigo_barrio', $this->codigo_barrio);
        $db->bindValue(':latitud', $this->latitud);
        $db->bindValue(':longitud', $this->longitud);
        $db->bindValue(':direccion', $this->direccion);
        $db->bindValue(':otros_datos', $this->otros_datos);
        $db->bindValue(':activo', $this->activo);
        $db->bindValue(':creado_fk_usuario', $this->creado_fk_usuario);

        $ejecutado = $db->execute();

        if (!$ejecutado) {
            $this->sql = $sql;
            $this->error = implode(", ", $db->errorInfo()) . " " . implode(", ", $this->db->errorInfo());
            $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
            $this->Error_SQL();
    
            $respuesta['exito'] = 0;
            $respuesta['mensaje'] = $this->error;
        } else {
            $db = $this->db->lastInsertId();
            $respuesta['exito'] = 1;
            $respuesta['mensaje'] = "Registro insertado correctamente";
            $respuesta['accion'] = "actualizacion";
            $respuesta['data'] = $db;
        }
    
        return $respuesta;
    }

    public function actualizar_factura_direccion(){
        $sql = "UPDATE fi_europa_facturas SET fk_direccion = :rowid  WHERE rowid = :fk_factura;";
        $db = $this->db->prepare($sql);
        $db->bindValue(':rowid', $this->id, PDO::PARAM_INT);
        $db->bindValue(':fk_factura', $this->fk_factura, PDO::PARAM_INT);

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
            $respuesta['mensaje'] = "Dirección actualizada";
            $respuesta['accion'] = "actualizacion";
        }

        return $respuesta;
    }
    public function actualizar_direccion()
    {
        // Preparar la consulta SQL para actualizar la tabla 'diccionario_direccion
        $sqlupdate = "
                UPDATE diccionario_direccion
                SET 
                    descripcion = :descripcion,
                    codigo_pais = :codigo_pais,
                    codigo_postal = :codigo_postal,
                    codigo_poblacion = :codigo_poblacion,
                    codigo_provincia = :codigo_provincia,
                    codigo_municipio = :codigo_municipio,
                    codigo_distrito = :codigo_distrito,
                    codigo_barrio = :codigo_barrio,
                    latitud = :latitud,
                    longitud = :longitud,
                    direccion = :direccion,
                    otros_datos = :otros_datos
                WHERE rowid = :rowid
            ";

        $update_stmt = $this->db->prepare($sqlupdate);

        // Asignar valores a los parámetros
        $update_stmt->bindValue(':descripcion', $this->descripcion);
        $update_stmt->bindValue(':codigo_pais', $this->codigo_pais);
        $update_stmt->bindValue(':codigo_postal', $this->codigo_postal);
        $update_stmt->bindValue(':codigo_poblacion', $this->codigo_poblacion);
        $update_stmt->bindValue(':codigo_provincia', $this->codigo_provincia);
        $update_stmt->bindValue(':codigo_municipio', $this->codigo_municipio);
        $update_stmt->bindValue(':codigo_distrito', $this->codigo_distrito);
        $update_stmt->bindValue(':codigo_barrio', $this->codigo_barrio);
        $update_stmt->bindValue(':latitud', $this->latitud);
        $update_stmt->bindValue(':longitud', $this->longitud);
        $update_stmt->bindValue(':direccion', $this->direccion);
        $update_stmt->bindValue(':otros_datos', $this->otros_datos);
        $update_stmt->bindValue(':rowid', $this->id);

        // Ejecutar la consulta
        if ($update_stmt->execute()) {
            $db = $this->db->lastInsertId();
            $respuesta['exito'] = 1;
            $respuesta['mensaje'] = "Registro actualizado";
            $respuesta['accion'] = "actualizacion";
            $respuesta['data'] = $db;
           
        } else {
            // Manejo de error
            $this->sql = $sqlupdate;
            $this->error = implode(", ", $update_stmt->errorInfo()) . " " . implode(", ", $this->db->errorInfo());
            $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
            $this->Error_SQL();
    
            $respuesta['exito'] = 0;
            $respuesta['mensaje'] = $this->error;
        }
        return $respuesta;
    }



    public function borrar_direccion()
    {
        // Consulta SQL para borrar en la tabla 'sql_diccionario_direccion'
        $sql_diccionario_direccion = "UPDATE diccionario_direccion SET borrado = 1,
        borrado_fecha = now(), borrado_fk_usuario = :borrado_fk_usuario WHERE rowid = :id;";

        // Actualización en la tabla sql_diccionario_direccion
        $update_stmt = $this->db->prepare($sql_diccionario_direccion);
        $update_stmt->bindValue(':id', $this->id);
        $update_stmt->bindValue(':borrado_fk_usuario', $this->borrado_fk_usuario);
        
        if ($update_stmt->execute()) {
            $respuesta['exito'] = 1;
            $respuesta['mensaje'] = "Registro borrado";
            $respuesta['accion'] = "borrar";
        } else {
            // Manejo de error
            $this->sql = $sql_diccionario_direccion;
            $this->error = implode(", ", $update_stmt->errorInfo()) . " " . implode(", ", $this->db->errorInfo());
            $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
            $this->Error_SQL();
    
            $respuesta['exito'] = 0;
            $respuesta['mensaje'] = $this->error;
        }
        return $respuesta;
    }

    
    public function actualizar_direccion_tipo_entidad($fk_direccion, $id_tipo_entidad)
    {
        try {
            // Preparar la consulta SQL para actualizar la tabla 'diccionario_direccion
            $sqlupdate = "
                    UPDATE fi_direccion_tipo_entidad
                    SET activo = 0
                    WHERE id_tipo_entidad = :id_tipo_entidad;
                    INSERT INTO fi_direccion_tipo_entidad
                    SET entidad = :entidad,
                        id_tipo_entidad = :id_tipo_entidad,
                        fk_direccion = :fk_direccion,
                        activo = 1,
                        creado_fk_usuario = :creado_fk_usuario,
                        creado_fecha = now();
                ";
            $update_stmt = $this->db->prepare($sqlupdate);

            // Asignar valores a los parámetros
            $update_stmt->bindValue(':fk_direccion', $fk_direccion);
            $update_stmt->bindValue(':id_tipo_entidad', $id_tipo_entidad);
            $update_stmt->bindValue(':entidad', $_SESSION["Entidad"]);
            $update_stmt->bindValue(':creado_fk_usuario', $this->creado_fk_usuario);

            // Ejecutar la consulta
            if ($update_stmt->execute()) {
                return ['exito' => 1, 'mensaje' => 'Registro actualizado correctamente'];
            } else {
                // Manejo de error
                $this->sql = $sqlupdate;
                $this->error = implode(", ", $update_stmt->errorInfo());
                $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
                $this->Error_SQL();

                return ['exito' => 0, 'mensaje' => $update_stmt->errorInfo()];
            }
        } catch (PDOException $e) {
            return ['exito' => 0, 'mensaje' => $e->getMessage()];
        }
    }
}
