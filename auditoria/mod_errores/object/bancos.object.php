<?php
//  require_once ENLACE_SERVIDOR . "global/object/log.sistema.php";
include_once(ENLACE_SERVIDOR."mod_seguridad/object/seguridad.object.php");

class banco extends  Seguridad
{

	public $entidad;
	function  __construct($db){

	    $this->db=$db; 
	    parent::__construct();  // Esto inicializa la clase SEGURIDAD
	} 


function fetch($id)
{	
   
   $sql="SELECT  u.* 
                FROM diccionario_bancos u     
                WHERE u.rowid = :rowid";
   
		   $db = $this->db->prepare($sql);
		   $db->bindValue('rowid',$id,PDO::PARAM_STR); 
		   $db->execute();
		   $u = $db->fetch(PDO::FETCH_ASSOC);
           $this->entidad = $u['entidad'];
		   $this->id            = $u['rowid'];
		   $this->nombre_banco        = $u['nombre_banco'];
		   $this->activo = $u['activo'];
		}



public function insertarBanco()
{
    try {
        // Preparar la consulta SQL para insertar en la tabla 'diccionario_bancos'
        $sqlcheck = "
            INSERT INTO diccionario_bancos (entidad, nombre_banco, activo)
            VALUES (:entidad, :nombre_banco, :activo)
        ";
        $insert_stmt = $this->db->prepare($sqlcheck);

        // Asignar valores a los parámetros
        $insert_stmt->bindValue(':entidad', $this->entidad);
        $insert_stmt->bindValue(':nombre_banco', $this->nombre_banco);
        $insert_stmt->bindValue(':activo', 1, PDO::PARAM_INT);  // Asignar 1 para activo

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




public function actualizarBanco()
{
    try {
        // Preparar la consulta SQL para actualizar la tabla 'diccionario_bancos'
        $sqlupdate = "
            UPDATE diccionario_bancos
            SET nombre_banco = :nombre_banco,activo = :activo
            WHERE rowid = :rowid
            AND  entidad = :entidad
        ";
        $update_stmt = $this->db->prepare($sqlupdate);

        // Asignar valores a los parámetros
        $update_stmt->bindValue(':nombre_banco', $this->nombre_banco);
        $update_stmt->bindValue(':activo', $this->activo);
        $update_stmt->bindValue(':entidad', $this->entidad);
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





public function eliminarBanco($id)
{
    // Consulta SQL para desactivar el banco en la tabla 'diccionario_bancos'
    $sql_diccionario_bancos = "UPDATE diccionario_bancos SET borrado = 1 , borrado_fecha_usuario = now(), borrado_fk_usuario = :borrado_fk_usuario = 0 WHERE rowid = :id AND entidad=:entidad ";

    try {
        // Inicia la transacción
        $this->db->beginTransaction();
        // Actualización en la tabla diccionario_bancos
        $update_stmt = $this->db->prepare($sql_diccionario_bancos);
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
            $this->sql = $sql_diccionario_bancos;
            $this->error = implode(", ", $update_stmt->errorInfo());
        }

        $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
        $this->Error_SQL();

        return ['exito' => 0, 'error' => $this->error];
    }
}



public function activarBanco($id)
{
    // Consulta SQL para reactivar el banco en la tabla 'diccionario_bancos'
    $sql_diccionario_bancos = "UPDATE diccionario_bancos SET activo = :activo WHERE rowid = :id";

    try {
        // Inicia la transacción
        $this->db->beginTransaction();

        // Actualización en la tabla diccionario_bancos
        $update_stmt = $this->db->prepare($sql_diccionario_bancos);
        $update_stmt->bindValue(':id', $id);
        $update_stmt->bindValue(':activo', 1);  // Reactivar el banco estableciendo activo a 1
        $update_stmt->execute();

        // Si todo va bien, se confirma la transacción
        $this->db->commit();

        return ['exito' => 1, 'id' => $id, 'update' => true];
    } catch (Exception $e) {
        // Si ocurre un error, se revierte la transacción
        $this->db->rollBack();

        // Identifica cuál consulta falló y registra el error
        if ($update_stmt->errorCode() !== '00000') {
            $this->sql = $sql_diccionario_bancos;
            $this->error = implode(", ", $update_stmt->errorInfo());
        }

        $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
        $this->Error_SQL();

        return ['exito' => 0, 'error' => $this->error];
    }
}



}