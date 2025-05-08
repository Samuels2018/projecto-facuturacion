<?php
include_once(ENLACE_SERVIDOR . "mod_seguridad/object/seguridad.object.php");
include_once(ENLACE_SERVIDOR . "mod_entidad/object/TipoEntidad.object.php");

class Agente extends  Seguridad
{
    use TipoEntidadTrait;
    
    protected $TipoEntidadClass;

    public $rowid;
    public $nombre;
    public $email;
    public $fk_tipo_identificacion;
    public $movil;
    public $telefono;
    public $meta;
    public $comision;
    public $cedula;
    public $observacion;
    public $creado_fecha;
    public $creado_fk_usuario;
    public $borrado;
    public $borrado_fecha;
    public $borrado_fk_usuario;
    private $db;
    public $sql;
    public $error;
    public $proceso;
    public $tipo_entidad;

    public $direccion_fk;
    public $direccion_txt;
    public $direcciones;

    public function __construct($db)
    {
        $this->db = $db;
        $this->sql = "";
        $this->error = "";
        $this->proceso = "";
        parent::__construct();
        $this->TipoEntidadClass = new TipoEntidadClass($db);
        $this->tipo_entidad = 3;
        $this->TipoEntidadClass->tipo_entidad = 3;
    }

    public function obtenerDirecciones($rowid) {
        $this->direcciones = $this->TipoEntidadClass->obtenerDirecciones($rowid);

        if(count($this->direcciones) > 0){
            $this->direccion_txt = $this->direcciones[0]["descripcion"];
            $this->direccion_fk = $this->direcciones[0]["fk_direccion"];
        }
    }

    public function beginTransaction() {
        $this->db->beginTransaction();
    }

    public function commit() {
        $this->db->commit();
    }

    public function rollBack() {
        $this->db->rollBack();
    }

    public function fetch($id)
    {
        $sql = "
                SELECT a.*, 
                b.rowid AS direccion_fk,
                b.descripcion AS direccion_txt
                FROM fi_agentes AS a 
                LEFT JOIN diccionario_direccion b
                ON a.rowid = b.entidad AND b.borrado = 0 AND tipo_entidad = 3
                WHERE a.rowid = :rowid";
                
        $db = $this->db->prepare($sql);
        $db->bindParam(':rowid', $id, PDO::PARAM_INT);
        $db->execute();
        $row = $db->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->rowid = $row['rowid'];
            $this->nombre = $row['nombre'];
            $this->email = $row['email'];
            $this->fk_tipo_identificacion = $row['fk_tipo_identificacion'];
            $this->movil = $row['movil'];
            $this->telefono = $row['telefono'];
            $this->meta = $row['meta'];
            $this->comision = $row['comision'];
            $this->cedula = $row['cedula'];
            $this->observacion = $row['observacion'];
            $this->creado_fecha = $row['creado_fecha'];
            $this->creado_fk_usuario = $row['creado_fk_usuario'];
            $this->borrado = $row['borrado'];
            $this->borrado_fecha = $row['borrado_fecha'];
            $this->borrado_fk_usuario = $row['borrado_fk_usuario'];
            $this->activo = $row['activo'];
            $this->direccion_fk = $row['direccion_fk'];
            $this->direccion_txt = $row['direccion_txt'];
        }
    }





    public function nuevo()
    {
        $sql = "INSERT INTO fi_agentes (
                        nombre,
                        email,
                        fk_tipo_identificacion,
                        movil,
                        telefono,
                        meta,
                        comision,
                        cedula,
                        observacion,
                        creado_fecha,
                        creado_fk_usuario,
                        entidad,
                        activo
                    ) VALUES (
                        :nombre,
                        :email,
                        :fk_tipo_identificacion,
                        :movil,
                        :telefono,
                        :meta,
                        :comision,
                        :cedula,
                        :observacion,
                        NOW(),
                        :creado_fk_usuario,
                        :entidad,
                        :activo
                    )";

        $dbh = $this->db->prepare($sql);

        $dbh->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $dbh->bindValue(':email', $this->email, PDO::PARAM_STR);
        $dbh->bindValue(':fk_tipo_identificacion', $this->fk_tipo_identificacion, PDO::PARAM_INT);
        $dbh->bindValue(':movil', $this->movil, PDO::PARAM_STR);
        $dbh->bindValue(':telefono', $this->telefono, PDO::PARAM_STR);
        $dbh->bindValue(':meta', empty($this->meta) ? 0 : $this->meta, PDO::PARAM_INT);
        $dbh->bindValue(':comision', empty($this->comision) ? 0 : $this->comision, PDO::PARAM_INT);
        $dbh->bindValue(':cedula', $this->cedula, PDO::PARAM_STR);
        $dbh->bindValue(':observacion', $this->observacion, PDO::PARAM_STR);
        $dbh->bindValue(':creado_fk_usuario', $this->creado_fk_usuario, PDO::PARAM_STR);
        $dbh->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);
        $dbh->bindValue(':activo', $this->activo, PDO::PARAM_INT);

        try {
            $a = $dbh->execute();

            if ($a) {
                $resultado['id'] = $this->db->lastInsertId();
                $resultado['exito'] = $a;
                $resultado['mensaje'] = "Agente insertado con Exito";
            } else {
                $resultado['exito'] = 0;
                $resultado['mensaje'] = "Error desconocido";
            }
        } catch (PDOException $e) {
            $resultado['exito'] = 0;
            $resultado['mensaje'] = $e->getMessage();
            $this->sql = $sql;
            $this->error = $e->getMessage();
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
            $this->Error_SQL();
        }

        return $resultado;
    }

    public function modificar()
    {

        $sql = "UPDATE fi_agentes 
                SET nombre = :nombre, 
                    email = :email, 
                    fk_tipo_identificacion = :fk_tipo_identificacion, 
                    movil = :movil, 
                    telefono = :telefono, 
                    meta = :meta, 
                    comision = :comision, 
                    cedula = :cedula, 
                    observacion = :observacion,
                    entidad = :entidad,
                    activo = :activo
                    WHERE rowid = :rowid";

        $dbh = $this->db->prepare($sql);

        $dbh->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $dbh->bindValue(':email', $this->email, PDO::PARAM_STR);
        $dbh->bindValue(':fk_tipo_identificacion', $this->fk_tipo_identificacion, PDO::PARAM_STR);
        $dbh->bindValue(':movil', $this->movil, PDO::PARAM_STR);
        $dbh->bindValue(':telefono', $this->telefono, PDO::PARAM_STR);
        $dbh->bindValue(':meta', $this->meta, PDO::PARAM_INT);
        $dbh->bindValue(':comision', $this->comision, PDO::PARAM_INT);
        $dbh->bindValue(':cedula', $this->cedula, PDO::PARAM_STR);
        $dbh->bindValue(':observacion', $this->observacion, PDO::PARAM_STR);
        $dbh->bindValue(':rowid', $this->rowid, PDO::PARAM_INT);
        $dbh->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);
        $dbh->bindValue(':activo', $this->activo, PDO::PARAM_INT);

        try {
            $a = $dbh->execute();


            $resultado['id'] = $this->rowid;
            $resultado['exito'] = $a;
            $resultado['mensaje'] = "Agente actualizado con Exito";
        } catch (PDOException $e) {
            $resultado['exito'] = 0;
            $resultado['mensaje'] = $e->getMessage();
            $this->sql = $sql;
            $this->error = $e->getMessage();
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
            $this->Error_SQL();
        }

        return $resultado;
    }


    public function eliminar($id)
    {
        $sql = "UPDATE fi_agentes 
                 SET borrado = 1, 
                     borrado_fecha = NOW(), 
                     borrado_fk_usuario = :borrado_fk_usuario 
                 WHERE rowid = :rowid";

        $dbh = $this->db->prepare($sql);

        // Asumiendo que $this->borrado_fk_usuario ya está establecido en el objeto
        $dbh->bindParam(':borrado_fk_usuario', $this->borrado_fk_usuario, PDO::PARAM_INT);
        $dbh->bindParam(':rowid', $id, PDO::PARAM_INT);

        try {
            $a = $dbh->execute();

            if ($a) {
                $resultado['id'] = $id;
                $resultado['exito'] = $a;
                $resultado['mensaje'] = "Agente eliminado con Exito";
            } else {
                $resultado['exito'] = 0;
                $resultado['mensaje'] = "Error desconocido";
            }
        } catch (PDOException $e) {
            $resultado['exito'] = 0;
            $resultado['mensaje'] = $e->getMessage();
            $this->sql = $sql;
            $this->error = $e->getMessage();
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
            $this->Error_SQL();
        }

        return $resultado;
    }

    public function obtener_agentes()
    {

        $sql = "SELECT * FROM fi_agentes WHERE borrado = 0 AND entidad = ". $_SESSION["Entidad"] ." ORDER BY nombre ASC";
        $db = $this->db->prepare($sql);
        $db->execute();
        return $db->fetchAll(PDO::FETCH_OBJ);
    }

    // public function obtener_agente_actual($id)
    // {
    //     $sql = "SELECT ca.*, fa.nombre, fa.email, fa.persona_contacto FROM configuracion_agentes ca
    //     LEFT JOIN fi_agentes fa ON fa.rowid = ca.fk_agente
    //     WHERE ca.fk_tercero  = :fk_tercero AND actual = 1";

    //     $db = $this->db->prepare($sql);

    //     $db->bindParam(':fk_tercero', $id, PDO::PARAM_INT);
    //     $db->execute();
    //     return $db->fetch(PDO::FETCH_OBJ);
    // }
    public function obtener_agente_actual($id)
    {
        $sql = "SELECT ca.*, fa.nombre, fa.email, fa.persona_contacto FROM fi_terceros ca
        LEFT JOIN fi_agentes fa ON fa.rowid = ca.fk_agente
        WHERE ca.rowid  = :fk_tercero";

        $db = $this->db->prepare($sql);

        $db->bindParam(':fk_tercero', $id, PDO::PARAM_INT);
        $db->execute();
        return $db->fetch(PDO::FETCH_OBJ);
    }

    public function desactivar_agente_actual($datos)
    {
        // var_dump($datos);
        // die();
        $sql = "UPDATE configuracion_agentes SET 
        actual = 0,
        borrado_fk_usuario = :borrado_fk_usuario,
        borrado = 1,
        borrado_fecha = NOW() 
        WHERE fk_tercero = :fk_tercero";
        $dbh = $this->db->prepare($sql);
        $dbh->bindParam(':fk_tercero', $datos->fk_tercero, PDO::PARAM_INT);
        $dbh->bindParam(':borrado_fk_usuario', $datos->borrado_fk_usuario, PDO::PARAM_INT);

        try {
            $a = $dbh->execute();

            if ($a) {
                $resultado['id'] = $datos->fk_agente;
                $resultado['exito'] = $a;
                $resultado['mensaje'] = "Agente eliminado con Exito";
            } else {
                $resultado['exito'] = 0;
                $resultado['mensaje'] = "Error desconocido";
            }
        } catch (PDOException $e) {
            $resultado['exito'] = 0;
            $resultado['mensaje'] = $e->getMessage();
            $this->sql = $sql;
            $this->error = $e->getMessage();
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
            $this->Error_SQL();
        }

        return $resultado;
    }

    public function registrar_agente_actual($datos)
    {
        $sql = "INSERT INTO configuracion_agentes
                SET fk_tercero = :fk_tercero,
                    fk_agente = :fk_agente,
                    actual = 1,
                    creado_fk_usuario = :creado_fk_usuario,
                    creado_fecha = NOW()";

        $dbh = $this->db->prepare($sql);
        $dbh->bindValue(':fk_tercero', $datos->fk_tercero, PDO::PARAM_INT);
        $dbh->bindValue(':fk_agente', $datos->fk_agente, PDO::PARAM_INT);
        $dbh->bindValue(':creado_fk_usuario', $datos->creado_fk_usuario, PDO::PARAM_INT);

        try {
            $a = $dbh->execute();

            if ($a) {
                $resultado['id'] = $this->db->lastInsertId();
                $resultado['exito'] = $a;
                $resultado['mensaje'] = "Agente actualizado con Exito";
            } else {
                $resultado['exito'] = 0;
                $resultado['mensaje'] = "Error desconocido";
            }
        } catch (PDOException $e) {
            $resultado['exito'] = 0;
            $resultado['mensaje'] = $e->getMessage();
            $this->sql = $sql;
            $this->error = $e->getMessage();
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
            $this->Error_SQL();
        }

        return $resultado;
    }
}
