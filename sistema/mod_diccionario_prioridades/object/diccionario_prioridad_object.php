<?php

class Diccionario_prioridad extends  Seguridad
{

	public $entidad;
	function  __construct($db){

	    $this->db=$db; 
	    parent::__construct();  // Esto inicializa la clase SEGURIDAD
	} 


function fetch($id)
{	
   
   $sql="SELECT  u.* 
                FROM diccionario_crm_oportunidades_prioridades u     
                WHERE u.rowid = :rowid";
   
		   $db = $this->db->prepare($sql);
		   $db->bindValue('rowid',$id,PDO::PARAM_STR); 
		   $db->execute();
		   $u = $db->fetch(PDO::FETCH_ASSOC);
		   $this->id            = $u['rowid'];
		   $this->etiqueta        = $u['etiqueta'];
           $this->prioridad        = $u['prioridad'];
           $this->estilo        = $u['estilo'];
           $this->entidad        = $u['entidad'];
		   $this->activo = $u['activo'];
		}


        public function crear_prioridad()
        {
            try {
                // Preparar la consulta SQL para insertar en la tabla 'diccionario_crm_oportunidades_prioridades' usando el formato SET
                $sqlcheck = "
                    INSERT INTO diccionario_crm_oportunidades_prioridades
                    SET rowid = :rowid,
                        entidad = :entidad,
                        etiqueta = :etiqueta,
                        prioridad = :prioridad,
                        estilo = :estilo,
                        activo = :activo,
                        creado_fk_usuario = :creado_fk_usuario,
                        creado_fecha = now()
                ";
                $insert_stmt = $this->db->prepare($sqlcheck);
        
                // Asignar valores a los parámetros
                $insert_stmt->bindValue(':rowid', $this->rowid);
                $insert_stmt->bindValue(':entidad', $this->entidad);
                $insert_stmt->bindValue(':etiqueta', $this->etiqueta);
                $insert_stmt->bindValue(':prioridad', $this->prioridad);
                $insert_stmt->bindValue(':estilo', $this->estilo);
                $insert_stmt->bindValue(':activo', $this->activo);
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
        

        public function actualizar_prioridad()
        {
            try {
                // Preparar la consulta SQL para actualizar la tabla 'diccionario_crm_oportunidades_prioridades'
                $sqlupdate = "
                    UPDATE diccionario_crm_oportunidades_prioridades
                    SET etiqueta = :etiqueta,
                        prioridad = :prioridad,
                        estilo = :estilo,
                        activo = :activo
                    WHERE rowid = :rowid AND entidad = :entidad
                ";

                $update_stmt = $this->db->prepare($sqlupdate);
        
                // Asignar valores a los parámetros
                $update_stmt->bindValue(':etiqueta', $this->etiqueta);
                $update_stmt->bindValue(':prioridad', $this->prioridad);
                $update_stmt->bindValue(':estilo', $this->estilo);
                $update_stmt->bindValue(':activo', $this->activo);
                $update_stmt->bindValue(':rowid', $this->id);
                $update_stmt->bindValue(':entidad',$this->entidad); 
        
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



public function borrar_prioridad($id)
{
    // Consulta SQL para borrar en la tabla 'diccionario_crm_oportunidades_prioridades'
    $sql_diccionario_crm_oportunidades_prioridades = "UPDATE diccionario_crm_oportunidades_prioridades SET borrado = 1,
     borrado_fecha_usuario = now(), borrado_fk_usuario = :borrado_fk_usuario WHERE rowid = :id AND entidad = :entidad";

    try {
        // Inicia la transacción
        $this->db->beginTransaction();

        // Actualización en la tabla diccionario_crm_oportunidades_prioridades
        $update_stmt = $this->db->prepare($sql_diccionario_crm_oportunidades_prioridades);
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
            $this->sql = $sql_diccionario_crm_oportunidades_prioridades;
            $this->error = implode(", ", $update_stmt->errorInfo());
        }

        $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
        $this->Error_SQL();

        return ['exito' => 0, 'error' => $this->error];
    }
}






}