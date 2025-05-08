<?php

class Medio_pago extends  Seguridad
{

	public $entidad;
	function  __construct($db){

	    $this->db=$db; 
	    parent::__construct();  // Esto inicializa la clase SEGURIDAD
	} 


    function fetch($id)
    {	
       
       $sql="SELECT  u.* 
                    FROM diccionario_medios_pago u     
                    WHERE u.rowid = :rowid AND u.entidad = :entidad";
       
    		   $db = $this->db->prepare($sql);
    		   $db->bindValue(':rowid',$id,PDO::PARAM_STR); 
               $db->bindValue(':entidad',$this->entidad,PDO::PARAM_INT);
    		   $db->execute();
    		   $u = $db->fetch(PDO::FETCH_ASSOC);
    		   $this->id            = $u['rowid'];
    		   $this->label        = $u['label'];
               $this->entidad        = $u['entidad'];
    		   $this->activo = $u['activo'];
    }
        
    public function listar_medios_pago()
    {
        $sql = "SELECT u.* 
                FROM diccionario_medios_pago u     
                WHERE entidad = :entidad AND activo = 1";
        
        $db = $this->db->prepare($sql);
        $db->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);
        $db->execute();
        $resultados = $db->fetchAll(PDO::FETCH_OBJ);
        
        return $resultados;
    }


	public function crear_medio_pago()
	{
	    try {
	        // Preparar la consulta SQL para insertar en la tabla 'diccionario_medios_pago'
	        $sqlcheck = "
	            INSERT INTO diccionario_medios_pago (label, entidad, creado_fecha, creado_fk_usuario, activo)
	            VALUES (:label, :entidad, now(), :creado_fk_usuario, :activo)
	        ";
	        $insert_stmt = $this->db->prepare($sqlcheck);

	        // Asignar valores a los parámetros
	        $insert_stmt->bindValue(':label', $this->label);
	        $insert_stmt->bindValue(':entidad', $this->entidad);
            $insert_stmt->bindValue(':activo', $this->activo);
	        $insert_stmt->bindValue(':creado_fk_usuario', $this->fk_usuario);

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



public function actualizar_medio_pago()
{
    try {
        // Preparar la consulta SQL para actualizar la tabla 'diccionario_medios_pago'
        $sqlupdate = "
            UPDATE diccionario_medios_pago
            SET label = :label, activo = :activo
              
            WHERE rowid = :rowid and entidad = :entidad
        ";
        $update_stmt = $this->db->prepare($sqlupdate);

        // Asignar valores a los parámetros
        $update_stmt->bindValue(':label', $this->label);
        $update_stmt->bindValue(':rowid', $this->id);
        $update_stmt->bindValue(':entidad', $this->entidad);
        $update_stmt->bindValue(':activo', $this->activo);

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




public function borrar_medio_pago($id)
{
    // Consulta SQL para borrar en la tabla 'diccionario_medios_pago'
    $sql_diccionario_medios_pago = "UPDATE diccionario_medios_pago SET borrado = 1,
     borrado_fecha_usuario = now(), borrado_fk_usuario = :borrado_fk_usuario WHERE rowid = :id AND entidad = :entidad";

    try {
        // Inicia la transacción
        $this->db->beginTransaction();

        // Actualización en la tabla diccionario_medios_pago
        $update_stmt = $this->db->prepare($sql_diccionario_medios_pago);
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
            $this->sql = $sql_diccionario_medios_pago;
            $this->error = implode(", ", $update_stmt->errorInfo());
        }

        $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
        $this->Error_SQL();

        return ['exito' => 0, 'error' => $this->error];
    }
}






}