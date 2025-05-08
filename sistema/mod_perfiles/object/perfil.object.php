<?php
//  require_once ENLACE_SERVIDOR . "global/object/log.sistema.php";
include_once(ENLACE_SERVIDOR."mod_seguridad/object/seguridad.object.php");

class perfil extends  Seguridad
{

	public $entidad;
	function  __construct($db){

	    $this->db=$db; 
	    parent::__construct();  // Esto inicializa la clase SEGURIDAD
	} 


function fetch($id)
{	
   
   $sql="SELECT  u.* 
                FROM fi_usuarios_perfiles u     
                WHERE u.rowid = :rowid";
   
		   $db = $this->db->prepare($sql);
		   $db->bindValue('rowid',$id,PDO::PARAM_STR); 
		   $db->execute();
		   $u = $db->fetch(PDO::FETCH_ASSOC);
		   $this->id            = $u['rowid'];
		   $this->etiqueta        = $u['etiqueta'];
		   $this->borrado = $u['borrado'];
		}



	public function insertarPerfilUsuario()
	{
	    try {
	        // Preparar la consulta SQL para insertar en la tabla 'fi_usuarios_perfiles'
	        $sqlcheck = "
	            INSERT INTO fi_usuarios_perfiles (etiqueta, entidad, creado_fecha, creado_fk_usuario, borrado, borrado_fecha_usuario)
	            VALUES (:etiqueta, :entidad, :creado_fecha, :creado_fk_usuario, 0, NULL)
	        ";
	        $insert_stmt = $this->db->prepare($sqlcheck);

	        // Asignar valores a los parámetros
	        $insert_stmt->bindValue(':etiqueta', $this->etiqueta);
	        $insert_stmt->bindValue(':entidad', $this->entidad);
	        $insert_stmt->bindValue(':creado_fecha', date('Y-m-d H:i:s'));  // Fecha actual
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

	            return ['exito' => 0, 'error_txt' => $insert_stmt->errorInfo()];
	        }
	    } catch (PDOException $e) {
	        return ['exito' => 0, 'error_txt' => $e->getMessage()];
	    }
	}



public function actualizarPerfilUsuario()
{
    try {
        // Preparar la consulta SQL para actualizar la tabla 'fi_usuarios_perfiles'
        $sqlupdate = "
            UPDATE fi_usuarios_perfiles
            SET etiqueta = :etiqueta,
                entidad = :entidad
            WHERE rowid = :rowid
        ";
        $update_stmt = $this->db->prepare($sqlupdate);

        // Asignar valores a los parámetros
        $update_stmt->bindValue(':etiqueta', $this->etiqueta);
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

            return ['exito' => 0, 'error_txt' => $update_stmt->errorInfo()];
        }
    } catch (PDOException $e) {
        return ['exito' => 0, 'error_txt' => $e->getMessage()];
    }
}




public function borrarPerfil($id)
{
    // Consulta SQL para inactivar el perfil en la tabla 'fi_usuarios_perfiles'
    $sql_fi_usuarios_perfiles = "UPDATE fi_usuarios_perfiles SET borrado = 1, borrado_fecha_usuario = :borrado_fecha WHERE rowid = :id";

    try {
        // Inicia la transacción
        $this->db->beginTransaction();

        // Actualización en la tabla fi_usuarios_perfiles
        $update_stmt = $this->db->prepare($sql_fi_usuarios_perfiles);
        $update_stmt->bindValue(':id', $id);
        $update_stmt->bindValue(':borrado_fecha', date('Y-m-d H:i:s'));  // Fecha actual
        $update_stmt->execute();

        // Si todo va bien, se confirma la transacción
        $this->db->commit();

        return ['exito' => 1, 'id' => $id, 'update' => true];
    } catch (Exception $e) {
        // Si ocurre un error, se revierte la transacción
        $this->db->rollBack();

        // Identifica cuál consulta falló y registra el error
        if ($update_stmt->errorCode() !== '00000') {
            $this->sql = $sql_fi_usuarios_perfiles;
            $this->error = implode(", ", $update_stmt->errorInfo());
        }

        $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
        $this->Error_SQL();

        return ['exito' => 0, 'error' => $this->error];
    }
}


public function activarPerfil($id)
{
    // Consulta SQL para reactivar el perfil en la tabla 'fi_usuarios_perfiles'
    $sql_fi_usuarios_perfiles = "UPDATE fi_usuarios_perfiles SET borrado = :borrado, borrado_fecha_usuario = NULL WHERE rowid = :id";

    try {
        // Inicia la transacción
        $this->db->beginTransaction();

        // Actualización en la tabla fi_usuarios_perfiles
        $update_stmt = $this->db->prepare($sql_fi_usuarios_perfiles);
        $update_stmt->bindValue(':id', $id);
        $update_stmt->bindValue(':borrado',0);
        $update_stmt->execute();

        // Si todo va bien, se confirma la transacción
        $this->db->commit();

        return ['exito' => 1, 'id' => $id, 'update' => true];
    } catch (Exception $e) {
        // Si ocurre un error, se revierte la transacción
        $this->db->rollBack();

        // Identifica cuál consulta falló y registra el error
        if ($update_stmt->errorCode() !== '00000') {
            $this->sql = $sql_fi_usuarios_perfiles;
            $this->error = implode(", ", $update_stmt->errorInfo());
        }

        $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
        $this->Error_SQL();

        return ['exito' => 0, 'error' => $this->error];
    }
}




}