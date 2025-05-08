<?php

class Moneda extends Seguridad
{
    public $entidad;
    public $etiqueta;
    public $simbolo;
    public $codigo;
    public $borrado;
    public $activo;
    public $creado_fecha;
    public $creado_fk_usuario;
    public $borrado_fecha_usuario;

    function __construct($db)
    {
        $this->db = $db;
        parent::__construct();  // Esto inicializa la clase SEGURIDAD
    }

    

    function fetch($id)
    {
        $sql = "SELECT u.* 
                FROM diccionario_monedas u     
                WHERE u.rowid = :rowid";

        $db = $this->db->prepare($sql);
        $db->bindValue('rowid', $id, PDO::PARAM_INT);
        $db->execute();
        $u = $db->fetch(PDO::FETCH_ASSOC);
        $this->id = $u['rowid'];
        $this->entidad = $u['entidad'];
        $this->etiqueta = $u['etiqueta'];
        $this->simbolo = $u['simbolo'];
        $this->codigo = $u['codigo'];
        $this->activo = $u['activo'];

    }

    public function listar_monedas()
    {
        $sql = "SELECT u.* 
        FROM diccionario_monedas u     
        WHERE entidad = :entidad";

        $db = $this->db->prepare($sql);
        $db->bindValue('entidad', $this->entidad, PDO::PARAM_INT);
        $db->execute();
        $u = $db->fetchAll(PDO::FETCH_ASSOC);
        return $u;

    }


    public function crear_moneda()
    {
        try {
            // Preparar la consulta SQL para insertar en la tabla 'diccionario_monedas'
            $sqlcheck = "
                INSERT INTO diccionario_monedas (entidad, etiqueta, simbolo, codigo, creado_fecha, creado_fk_usuario)
                VALUES (:entidad, :etiqueta, :simbolo, :codigo, now(), :creado_fk_usuario)
            ";
            $insert_stmt = $this->db->prepare($sqlcheck);

            // Asignar valores a los parámetros
            $insert_stmt->bindValue(':entidad', $this->entidad);
            $insert_stmt->bindValue(':etiqueta', $this->etiqueta);
            $insert_stmt->bindValue(':simbolo', $this->simbolo);
            $insert_stmt->bindValue(':codigo', $this->codigo);
            $insert_stmt->bindValue(':creado_fk_usuario', $this->creado_fk_usuario);

            // Ejecutar la consulta
            if ($insert_stmt->execute()) {
                return ['exito' => 1, 'mensaje' => 'Registro insertado correctamente'];
            } else {
                // Manejo de error
                $this->sql = $sqlcheck;
                $this->error = implode(", ", $insert_stmt->errorInfo());
                $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
                $this->Error_SQL();

                return ['exito' => 0, 'mensaje' => $insert_stmt->errorInfo()];
            }
        } catch (PDOException $e) {
            return ['exito' => 0, 'mensaje' => $e->getMessage()];
        }
    }

    public function actualizar_moneda()
    {
        try {
            // Preparar la consulta SQL para actualizar la tabla 'diccionario_monedas'
            $sqlupdate = "
                UPDATE diccionario_monedas
                SET 
                    etiqueta = :etiqueta,
                    simbolo = :simbolo,
                    codigo = :codigo,
                    activo = :activo
                WHERE rowid = :rowid AND entidad = :entidad
            ";
            $update_stmt = $this->db->prepare($sqlupdate);

            // Asignar valores a los parámetros
            $update_stmt->bindValue(':entidad', $this->entidad);
            $update_stmt->bindValue(':etiqueta', $this->etiqueta);
            $update_stmt->bindValue(':simbolo', $this->simbolo);
            $update_stmt->bindValue(':codigo', $this->codigo);
            $update_stmt->bindValue(':activo', $this->activo);
            
            $update_stmt->bindValue(':rowid', $this->id);

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




    public function borrar_moneda($id)
    {
        // Consulta SQL para borrar en la tabla 'diccionario_monedas'
        $sql_diccionario_monedas = "UPDATE diccionario_monedas SET borrado = 1,
     borrado_fecha_usuario = now(), borrado_fk_usuario = :borrado_fk_usuario WHERE rowid = :id AND entidad = :entidad";

        try {
            // Inicia la transacción
            $this->db->beginTransaction();

            // Actualización en la tabla diccionario_monedas
            $update_stmt = $this->db->prepare($sql_diccionario_monedas);
            $update_stmt->bindValue(':id', $id);
            $update_stmt->bindValue(':entidad', $this->entidad);
            $update_stmt->bindValue(':borrado_fk_usuario', $this->borrado_fk_usuario);
            $update_stmt->execute();

            // Si todo va bien, se confirma la transacción
            $this->db->commit();

            return ['exito' => 1, 'id' => $id, 'update' => true];
        } catch (Exception $e) {
            // Si ocurre un error, se revierte la transacción
            $this->db->rollBack();

            // Identifica cuál consulta falló y registra el error
            if ($update_stmt->errorCode() !== '00000') {
                $this->sql = $sql_diccionario_monedas;
                $this->error = implode(", ", $update_stmt->errorInfo());
            }

            $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
            $this->Error_SQL();

            return ['exito' => 0, 'error' => $this->error];
        }
    }
}
