<?php

class Agente_rutas extends Seguridad
{
    public $entidad;

    function __construct($db) {
        $this->db = $db;
        parent::__construct(); 
    }

    function fetch($id) {
        $sql = "SELECT u.* 
                FROM diccionario_agente_rutas u     
                WHERE u.rowid = :rowid";
   
        $db = $this->db->prepare($sql);
        $db->bindValue('rowid', $id, PDO::PARAM_STR);
        $db->execute();
        $u = $db->fetch(PDO::FETCH_ASSOC);

        $this->id            = $u['rowid'];
        $this->fk_ruta       = $u['fk_ruta'];
        $this->entidad       = $u['entidad'];
        $this->fk_agente   = $u['fk_agente'];
        $this->activo        = $u['activo'];
        $this->borrado       = $u['borrado'];
    }

    public function obtener_agentes()
    {
        $sql = "SELECT u.* 
                FROM fi_agentes u     
                WHERE entidad = :entidad";
   
        $db = $this->db->prepare($sql);
        $db->bindValue('entidad', $this->entidad, PDO::PARAM_STR);
        $db->execute();
        $result = $db->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function obtener_rutas()
    {
            $sql = "SELECT * FROM diccionario_rutas WHERE entidad = :entidad";
            
            $db = $this->db->prepare($sql);
            $db->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);
            $db->execute();
            $result = $db->fetchAll(PDO::FETCH_ASSOC);
            
            return $result;
    }

    public function crear_agente_ruta() {
        $sqlcheck = "
            INSERT INTO diccionario_agente_rutas (fk_ruta, entidad, fk_agente, activo, creado_fecha, creado_fk_usuario)
            VALUES (:fk_ruta, :entidad, :fk_agente, :activo, :creado_fecha, :creado_fk_usuario)
        ";
        $insert_stmt = $this->db->prepare($sqlcheck);

        $insert_stmt->bindValue(':fk_ruta', $this->fk_ruta);
        $insert_stmt->bindValue(':entidad', $this->entidad);
        $insert_stmt->bindValue(':fk_agente', $this->fk_agente);
        $insert_stmt->bindValue(':activo', $this->activo);
        $insert_stmt->bindValue(':creado_fecha', date('Y-m-d H:i:s'));  // Fecha actual
        $insert_stmt->bindValue(':creado_fk_usuario', $this->fk_usuario);

        if ($insert_stmt->execute()) {
            return ['exito' => 1, 'mensaje' => 'Registro insertado correctamente'];
        } else {
            $this->sql = $sqlcheck;
            $this->error = implode(", ", $insert_stmt->errorInfo());
            $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
            $this->Error_SQL();

            return ['exito' => 0, 'mensaje' => $insert_stmt->errorInfo()];
        }
    }

    public function actualizar_agente_rutas() {
        $sqlupdate = "
            UPDATE diccionario_agente_rutas
            SET fk_ruta = :fk_ruta, fk_agente = :fk_agente, activo = :activo
            WHERE rowid = :rowid AND entidad = :entidad
        ";
        $update_stmt = $this->db->prepare($sqlupdate);

        $update_stmt->bindValue(':fk_ruta', $this->fk_ruta);
        $update_stmt->bindValue(':fk_agente', $this->fk_agente);
        $update_stmt->bindValue(':activo', $this->activo);
        $update_stmt->bindValue(':rowid', $this->id);
        $update_stmt->bindValue(':entidad', $this->entidad);

        if ($update_stmt->execute()) {
            return ['exito' => 1, 'mensaje' => 'Registro actualizado correctamente'];
        } else {
            $this->sql = $sqlupdate;
            $this->error = implode(", ", $update_stmt->errorInfo());
            $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
            $this->Error_SQL();

            return ['exito' => 0, 'mensaje' => $update_stmt->errorInfo()];
        }
    }

    public function borrar_agente_rutas($id) {
        $sql_diccionario_agente_rutas = "
            UPDATE diccionario_agente_rutas 
            SET borrado = 1, borrado_fecha = now(), borrado_fk_usuario = :borrado_fk_usuario
            WHERE rowid = :id AND entidad = :entidad
        ";

        $update_stmt = $this->db->prepare($sql_diccionario_agente_rutas);
        $update_stmt->bindValue(':id', $id);
        $update_stmt->bindValue(':entidad', $this->entidad);
        $update_stmt->bindValue(':borrado_fk_usuario', $this->borrado_fk_usuario);

        if ($update_stmt->execute()) {
            return ['exito' => 1, 'id' => $id, 'update' => true];
        } else {
            $resultado['exito'] = 0;
            $resultado['error'] = implode(", ", $update_stmt->errorInfo());
            $this->sql = $sql_diccionario_agente_rutas;
            $this->error = implode(", ", $update_stmt->errorInfo());
            $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
            $this->Error_SQL();

            return $resultado;
        }
    }
}
