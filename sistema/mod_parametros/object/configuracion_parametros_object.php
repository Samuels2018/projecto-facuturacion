<?php

class Configuracion_parametros extends Seguridad
{
    public $entidad;

    function __construct($db)
    {
        $this->db = $db;
        parent::__construct();  // Esto inicializa la clase SEGURIDAD
    }

    function fetch($id)
    {
        $sql = "SELECT * FROM fi_configuracion WHERE rowid = :rowid";

        $db = $this->db->prepare($sql);
        $db->bindValue(':rowid', $id, PDO::PARAM_INT);
        $db->execute();
        $u = $db->fetch(PDO::FETCH_ASSOC);
        $this->id = $u['rowid'];
        $this->entidad = $u['entidad'];
        $this->configuracion = $u['configuracion'];
        $this->valor = $u['valor'];
        $this->borrado = $u['borrado'];
        $this->activo = $u['activo'];
    }

    public function crear_configuracion()
    {
        try {
            $sqlcheck = "
                INSERT INTO fi_configuracion
                SET entidad = :entidad,
                    configuracion = :configuracion,
                    valor = :valor,
                    activo = :activo,
                    creado_fk_usuario = :creado_fk_usuario,
                    creado_fecha = NOW()
            ";
            $insert_stmt = $this->db->prepare($sqlcheck);

            $insert_stmt->bindValue(':entidad', $this->entidad);
            $insert_stmt->bindValue(':configuracion', $this->configuracion);
            $insert_stmt->bindValue(':valor', $this->valor);
            $insert_stmt->bindValue(':activo', $this->activo);
            $insert_stmt->bindValue(':creado_fk_usuario', $this->creado_fk_usuario);

            if ($insert_stmt->execute()) {
                return ['exito' => 1, 'mensaje' => 'Configuración insertada correctamente'];
            } else {
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

    public function actualizar_configuracion()
    {
    
        try {
            $sqlupdate = "
                UPDATE fi_configuracion
                SET configuracion = :configuracion,
                    valor = :valor,
                    activo = :activo
                WHERE rowid = :rowid AND entidad = :entidad
            ";

            $update_stmt = $this->db->prepare($sqlupdate);

            $update_stmt->bindValue(':configuracion', $this->configuracion);
            $update_stmt->bindValue(':valor', $this->valor);
            $update_stmt->bindValue(':activo', $this->activo);
            $update_stmt->bindValue(':rowid', $this->id);
            $update_stmt->bindValue(':entidad', $this->entidad);

            if ($update_stmt->execute()) {
                return ['exito' => 1, 'mensaje' => 'Configuración actualizada correctamente'];
            } else {
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

    public function borrar_configuracion($id)
    {
        $sql = "
            UPDATE fi_configuracion
            SET borrado = 1, borrado_fecha_usuario = NOW(), borrado_fk_usuario = :borrado_fk_usuario
            WHERE rowid = :id AND entidad = :entidad
        ";

        try {
            $this->db->beginTransaction();

            $update_stmt = $this->db->prepare($sql);
            $update_stmt->bindValue(':id', $id);
            $update_stmt->bindValue(':entidad', $this->entidad);
            $update_stmt->bindValue(':borrado_fk_usuario', $this->borrado_fk_usuario);
            $update_stmt->execute();

            $this->db->commit();

            return ['exito' => 1, 'id' => $id, 'update' => true];
        } catch (Exception $e) {
            $this->db->rollBack();

            if ($update_stmt->errorCode() !== '00000') {
                $this->sql = $sql;
                $this->error = implode(", ", $update_stmt->errorInfo());
            }

            $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
            $this->Error_SQL();

            return ['exito' => 0, 'error' => $this->error];
        }
    }
}
