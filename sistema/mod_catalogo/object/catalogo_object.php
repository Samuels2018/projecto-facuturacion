<?php

class DiccionarioCatalogo extends Seguridad
{
    public $rowid;
    public $entidad;
    public $codigo;
    public $detalle;
    public $tipo;
    public $activo;
    public $creado_fecha;
    public $borrado;
    public $borrado_fecha_usuario;
    public $borrado_fk_usuario;

    function __construct($db)
    {
        $this->db = $db;
        parent::__construct();  // Esto inicializa la clase SEGURIDAD
    }

    function fetch($id)
    {
        $sql = "SELECT u.* 
                FROM diccionario_catalogo u     
                WHERE u.rowid = :rowid";

        $db = $this->db->prepare($sql);
        $db->bindValue('rowid', $id, PDO::PARAM_INT);
        $db->execute();
        $u = $db->fetch(PDO::FETCH_ASSOC);
        $this->id = $u['rowid'];
        $this->entidad = $u['entidad'];
        $this->codigo = $u['codigo'];
        $this->detalle = $u['detalle'];
        $this->tipo = $u['tipo'];
        $this->activo = $u['activo'];
        $this->creado_fecha = $u['creado_fecha'];
        $this->borrado = $u['borrado'];
        $this->borrado_fecha_usuario = $u['borrado_fecha_usuario'];
        $this->borrado_fk_usuario = $u['borrado_fk_usuario'];
    }

    public function obtener_unidades_catalogo($tipo)
    {
      $sql = "SELECT * FROM `diccionario_catalogo` WHERE activo = 1 and tipo = :tipo AND entidad = :entidad";
  
      $db = $this->db->prepare($sql);
  
      $db->bindValue(':tipo', $tipo, PDO::PARAM_INT);
      $db->bindValue(':entidad', $_SESSION['Entidad'], PDO::PARAM_INT);
      $db->execute();
      $data = $db->fetchAll(PDO::FETCH_OBJ);
  
      return $data;
    }
  

    public function crear_unidad()
    {
        try {
            // Preparar la consulta SQL para insertar en la tabla 'diccionario_catalogo'
            $sqlcheck = "
                INSERT INTO diccionario_catalogo (entidad, codigo, detalle, tipo, activo, creado_fecha, creado_fk_usuario)
                VALUES (:entidad, :codigo, :detalle, :tipo, :activo, now(), :creado_fk_usuario)
            ";
            $insert_stmt = $this->db->prepare($sqlcheck);

            // Asignar valores a los parámetros
            $insert_stmt->bindValue(':entidad', $this->entidad);
            $insert_stmt->bindValue(':codigo', $this->codigo);
            $insert_stmt->bindValue(':detalle', $this->detalle);
            $insert_stmt->bindValue(':tipo', $this->tipo);
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


    public function actualizar_unidad()
{
    try {
        // Preparar la consulta SQL para actualizar la tabla 'diccionario_catalogo'
        $sqlupdate = "
            UPDATE diccionario_catalogo
            SET 
       
                codigo = :codigo,
                detalle = :detalle,
                tipo = :tipo,
                activo = :activo
            WHERE rowid = :rowid AND entidad = :entidad
        ";
        $update_stmt = $this->db->prepare($sqlupdate);

        // Asignar valores a los parámetros
        $update_stmt->bindValue(':entidad', $this->entidad);
        $update_stmt->bindValue(':codigo', $this->codigo);
        $update_stmt->bindValue(':detalle', $this->detalle);
        $update_stmt->bindValue(':tipo', $this->tipo);
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

public function borrar_catalogo($id)
{
    // Consulta SQL para borrar en la tabla 'diccionario_catalogo'
    $sql_diccionario_catalogo = "UPDATE diccionario_catalogo SET borrado = 1,
    borrado_fecha_usuario = now(), borrado_fk_usuario = :borrado_fk_usuario WHERE rowid = :id AND entidad = :entidad";

    try {
        // Inicia la transacción
        $this->db->beginTransaction();

        // Actualización en la tabla diccionario_catalogo
        $update_stmt = $this->db->prepare($sql_diccionario_catalogo);
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
            $this->sql = $sql_diccionario_catalogo;
            $this->error = implode(", ", $update_stmt->errorInfo());
        }

        $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
        $this->Error_SQL();

        return ['exito' => 0, 'error' => $this->error];
    }
}



}
?>


