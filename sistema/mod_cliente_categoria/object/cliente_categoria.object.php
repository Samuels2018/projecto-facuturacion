<?php

class Categoria_cliente extends  Seguridad
{

	public $entidad;
	function  __construct($db){

	    $this->db=$db; 
	    parent::__construct();  // Esto inicializa la clase SEGURIDAD
	} 


function fetch($id)
{	
   
   $sql="SELECT  u.* 
                FROM diccionario_clientes_categorias u     
                WHERE u.rowid = :rowid";
   
		   $db = $this->db->prepare($sql);
		   $db->bindValue('rowid',$id,PDO::PARAM_STR); 
		   $db->execute();
		   $u = $db->fetch(PDO::FETCH_ASSOC);
		   $this->id            = $u['rowid'];
		   $this->label        = $u['label'];
           $this->entidad        = $u['entidad'];
		   $this->estado = $u['estado'];
		}



	public function crear_categoria_cliente()
	{
	    try {
	        // Preparar la consulta SQL para insertar en la tabla 'diccionario_clientes_categorias'
	        $sqlcheck = "
	            INSERT INTO diccionario_clientes_categorias (label, entidad, creado_fecha, creado_fk_usuario)
	            VALUES (:label, :entidad, :creado_fecha, :creado_fk_usuario)
	        ";
	        $insert_stmt = $this->db->prepare($sqlcheck);

	        // Asignar valores a los parámetros
	        $insert_stmt->bindValue(':label', $this->label);
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

	            return ['exito' => 0, 'mensaje' => $insert_stmt->errorInfo()];
	        }
	    } catch (PDOException $e) {
	        return ['exito' => 0, 'mensaje' => $e->getMessage()];
	    }
	}



public function actualizar_categoria_cliente()
{
    try {
        // Preparar la consulta SQL para actualizar la tabla 'diccionario_clientes_categorias'
        $sqlupdate = "
            UPDATE diccionario_clientes_categorias
            SET label = :label, estado = :estado
              
            WHERE rowid = :rowid and entidad = :entidad
        ";
        $update_stmt = $this->db->prepare($sqlupdate);

        // Asignar valores a los parámetros
        $update_stmt->bindValue(':label', $this->label);
        $update_stmt->bindValue(':rowid', $this->id);
        $update_stmt->bindValue(':entidad', $this->entidad);
        $update_stmt->bindValue(':estado', $this->estado);

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




public function borrar_categoria_cliente($id)
{
    // Consulta SQL para borrar en la tabla 'diccionario_clientes_categorias'
    $sql_diccionario_clientes_categorias = "UPDATE diccionario_clientes_categorias SET borrado = 1,
     borrado_fecha_usuario = now(), borrado_fk_usuario = :borrado_fk_usuario WHERE rowid = :id AND entidad = :entidad";

    try {
        // Inicia la transacción
        $this->db->beginTransaction();

        // Actualización en la tabla diccionario_clientes_categorias
        $update_stmt = $this->db->prepare($sql_diccionario_clientes_categorias);
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
            $this->sql = $sql_diccionario_clientes_categorias;
            $this->error = implode(", ", $update_stmt->errorInfo());
        }

        $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
        $this->Error_SQL();

        return ['exito' => 0, 'error' => $this->error];
    }
}






}