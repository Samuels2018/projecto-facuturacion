<?php

class Forma_pago_detalle extends  Seguridad
{

	function  __construct($db){

	    $this->db=$db; 
	    parent::__construct();  // Esto inicializa la clase SEGURIDAD
	} 

    public function crear_forma_pago_detalle(){
        try{

            foreach ($this->detalle as $data) {
                // Asignar valores a los parámetros                
                $sqlDetalle = "INSERT INTO diccionario_formas_pago_detalle (fk_formapago, secuencia, porcentaje, dias, activo, creado_fecha, creado_fk_usuario) 
                VALUES (:fk_formapago, :secuencia, :porcentaje, :dias, 1, NOW(), :creado_fk_usuario)";
                $this->sql = $sqlDetalle;
                // Preparar la consulta
                $stmt = $this->db->prepare($sqlDetalle);

                $secuencia = $data->forma_id;
                $porcentaje = $data->forma_porcentaje !== "" ? $data->forma_porcentaje : 0.00;
                $dias = $data->forma_dias !== "" ? $data->forma_dias : 0;

                $stmt->bindValue(':fk_formapago', $this->rowid);
                $stmt->bindValue(':secuencia', $secuencia);
                $stmt->bindValue(':porcentaje', $porcentaje);
                $stmt->bindValue(':dias', $dias);
                $stmt->bindValue(':creado_fk_usuario', $this->fk_usuario);
                $stmt->execute();
                // $ultimo_id = $this->db->lastInsertId();

                $stmt = null;
                $sqlDetalle = '';
            }

            return ['exito' => 1, 'mensaje' => 'Detalle insertado correctamente!!' ];
        } catch (PDOException $e) {
            return ['exito' => 0, 'mensaje' => $e->getMessage()];
        }
    }

    public function actualizar_forma_pago_detalle()
    {
        try {
            // Inicia la transacción
            $this->db->beginTransaction();

            $sql_diccionario_formas_pago_detalle = "UPDATE diccionario_formas_pago_detalle SET borrado = 1, activo=0,
            borrado_fecha_usuario = now(), borrado_fk_usuario = :borrado_fk_usuario WHERE fk_formapago = :id ";

            $update_stmt = $this->db->prepare($sql_diccionario_formas_pago_detalle);
            $update_stmt->bindValue(':id', $this->rowid);
            $update_stmt->bindValue(':borrado_fk_usuario', $this->fk_usuario);
            $update_stmt->execute();
            
            $this->crear_forma_pago_detalle();

            // Si todo va bien, se confirma la transacción
            $this->db->commit();

            return ['exito' => 1, 'id' => $rowid, 'update' => true, 'data'=> json_encode($this->rowid.'-'.$this->fk_usuario) ];
        } catch (Exception $e) {
            // Si ocurre un error, se revierte la transacción
            $this->db->rollBack();

            // Identifica cuál consulta falló y registra el error
            if ($update_stmt->errorCode() !== '00000') {
                $this->sql = $sql_diccionario_formas_pago;
                $this->error = implode(", ", $update_stmt->errorInfo());
            }

            $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
            $this->Error_SQL();

            return ['exito' => 0, 'error' => $this->error];
        }
    }
}