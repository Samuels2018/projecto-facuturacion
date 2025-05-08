<?php

class Diccionario_ruta extends Seguridad
{
    public $entidad;

    function __construct($db){
        $this->db = $db; 
        parent::__construct();
    } 

    function fetch($id)
    {	
        $sql = "SELECT u.* 
                FROM diccionario_rutas u     
                WHERE u.rowid = :rowid";
        
        $db = $this->db->prepare($sql);
        $db->bindValue('rowid', $id, PDO::PARAM_INT); 
        $db->execute();
        $u = $db->fetch(PDO::FETCH_ASSOC);

        $this->id      = $u['rowid'];
        $this->label   = $u['label'];
        $this->entidad = $u['entidad'];
        $this->activo  = $u['activo'];
    }

    public function crear_ruta()
    {
        try {
            $sqlcheck = "
                INSERT INTO diccionario_rutas (label, entidad, activo, creado_fecha, creado_fk_usuario)
                VALUES (:label, :entidad, :activo, :creado_fecha, :creado_fk_usuario)
            ";
            $insert_stmt = $this->db->prepare($sqlcheck);

            $insert_stmt->bindValue(':label', $this->label);
            $insert_stmt->bindValue(':entidad', $this->entidad);
            $insert_stmt->bindValue(':activo', $this->estado);
            $insert_stmt->bindValue(':creado_fecha', date('Y-m-d H:i:s')); 
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
        } catch (PDOException $e) {
            return ['exito' => 0, 'mensaje' => $e->getMessage()];
        }
    }

    public function actualizar_ruta()
    {
        try {
           
            $sqlupdate = "
                UPDATE diccionario_rutas
                SET label = :label, activo = :activo
                WHERE rowid = :rowid AND entidad = :entidad
            ";
            $update_stmt = $this->db->prepare($sqlupdate);

            $update_stmt->bindValue(':label', $this->label);
            $update_stmt->bindValue(':rowid', $this->id);
            $update_stmt->bindValue(':entidad', $this->entidad);
            $update_stmt->bindValue(':activo', $this->estado);

            if ($update_stmt->execute()) {
                return ['exito' => 1, 'mensaje' => 'Registro actualizado correctamente'];
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

    public function borrar_ruta($id)
    {
       
        $sql_ruta = "UPDATE diccionario_rutas 
                                SET borrado = 1, borrado_fecha = now(), borrado_fk_usuario = :borrado_fk_usuario 
                                WHERE rowid = :id AND entidad = :entidad";

        
            
            $update_stmt = $this->db->prepare($sql_ruta);
            $update_stmt->bindValue(':id', $id);
            $update_stmt->bindValue(':entidad', $this->entidad);
            $update_stmt->bindValue(':borrado_fk_usuario', $this->borrado_fk_usuario);
            $update_stmt->execute();


            $a = $update_stmt->execute();

            if ($a){
                $this->id = $datos->id;
                $resultado['id']      =   $datos->id;
                $resultado['exito']   =   1;
                $resultado['mensaje'] =   "Ruta actualizado con Exito";
                $resultado['update']  =   1;
                
        
          } else { 
                $resultado['exito']= 0;
                $resultado['error']=  implode(", ", $update_stmt->errorInfo());
                $this->sql     =   $sql_ruta;
                $this->error   =   implode(", ", $update_stmt->errorInfo())." ". implode(", ", $this->db->errorInfo());
                $this->proceso = __FUNCTION__ ." Del Objeto ".__CLASS__;
                $this->Error_SQL();
        }

            return $resultado;
 
    }

    public function obtener_rutas()
    {
        $sql = "SELECT * FROM diccionario_rutas WHERE borrado = 0 AND entidad = ". $_SESSION["Entidad"] ." ORDER BY label ASC";
        $db = $this->db->prepare($sql);
        $db->execute();
        return $db->fetchAll(PDO::FETCH_OBJ);
    }
    public function obtener_ruta_actual($id)
    {
        $sql = "SELECT t.*, a.nombre, a.persona_contacto, a.movil, a.telefono, a.web, a.impuesto, a.comision, a.iva
        FROM fi_terceros t left JOIN fi_agentes a ON t.fk_agente = a.rowid
        WHERE t.rowid  = :fk_tercero ";

        $db = $this->db->prepare($sql);

        $db->bindParam(':fk_tercero', $id, PDO::PARAM_INT);
        $db->execute();
        return $db->fetch(PDO::FETCH_OBJ);
    }
}
