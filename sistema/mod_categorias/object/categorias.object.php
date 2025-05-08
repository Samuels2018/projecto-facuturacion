<?php
// require_once ENLACE_SERVIDOR . "global/object/log.sistema.php";
include_once(ENLACE_SERVIDOR."mod_seguridad/object/seguridad.object.php");

class categoria_producto extends Seguridad
{
    public $entidad;
    public $tipo;
    public $activo;
    public $creado;
    public $label;
    public $id;

    function __construct($db){
        $this->db = $db; 
        parent::__construct();  // Esto inicializa la clase SEGURIDAD
    }

    function fetch($id)
    {   
        $sql = "SELECT u.* 
                FROM diccionario_categorias u     
                WHERE u.rowid = :rowid";
        
        $db = $this->db->prepare($sql);
        $db->bindValue('rowid', $id, PDO::PARAM_INT); 
        $db->execute();
        $u = $db->fetch(PDO::FETCH_ASSOC);
        $this->id = $u['rowid'];
        $this->entidad = $u['entidad'];
        $this->tipo = $u['tipo'];
        $this->activo = $u['activo'];
        $this->creado = $u['creado'];
        $this->label = $u['label'];
    }

    public function insertarCategoria()
    {
        try {
            // Preparar la consulta SQL para insertar en la tabla 'diccionario_categorias'
            $sqlcheck = "
                INSERT INTO diccionario_categorias (entidad, tipo, activo, creado, label)
                VALUES (:entidad, :tipo, :activo, :creado, :label)
            ";
            $insert_stmt = $this->db->prepare($sqlcheck);

            // Asignar valores a los parámetros
            $insert_stmt->bindValue(':entidad', $this->entidad);
            $insert_stmt->bindValue(':tipo', $this->tipo);
            $insert_stmt->bindValue(':activo', 1, PDO::PARAM_INT);  // Asignar 1 para activo
            $insert_stmt->bindValue(':creado', date('Y-m-d H:i:s'));  // Fecha actual
            $insert_stmt->bindValue(':label', $this->label);

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

    public function actualizarCategoria()
    {
        try {
            // Preparar la consulta SQL para actualizar la tabla 'diccionario_categorias'
            $sqlupdate = "
                UPDATE diccionario_categorias
                SET tipo = :tipo,
                    label = :label
                WHERE rowid = :rowid
                AND entidad = :entidad
            ";
            $update_stmt = $this->db->prepare($sqlupdate);

            // Asignar valores a los parámetros
            $update_stmt->bindValue(':tipo', $this->tipo);
            $update_stmt->bindValue(':label', $this->label);
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

    public function desactivarCategoria($id)
    {
        // Consulta SQL para desactivar la categoría en la tabla 'diccionario_categorias'
        $sql_diccionario_categorias = "UPDATE diccionario_categorias SET activo = 0 WHERE rowid = :id";

        try {
            // Inicia la transacción
            $this->db->beginTransaction();
            // Actualización en la tabla diccionario_categorias
            $update_stmt = $this->db->prepare($sql_diccionario_categorias);
            $update_stmt->bindValue(':id', $id);
            $update_stmt->execute();

            // Si todo va bien, se confirma la transacción
            $this->db->commit();

            return ['exito' => 1, 'id' => $id, 'update' => true];
        } catch (Exception $e) {
            // Si ocurre un error, se revierte la transacción
            $this->db->rollBack();

            // Identifica cuál consulta falló y registra el error
            if ($update_stmt->errorCode() !== '00000') {
                $this->sql = $sql_diccionario_categorias;
                $this->error = implode(", ", $update_stmt->errorInfo());
            }

            $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
            $this->Error_SQL();

            return ['exito' => 0, 'error' => $this->error];
        }
    }

    public function activarCategoria($id)
    {
        // Consulta SQL para reactivar la categoría en la tabla 'diccionario_categorias'
        $sql_diccionario_categorias = "UPDATE diccionario_categorias SET activo = :activo WHERE rowid = :id";

        try {
            // Inicia la transacción
            $this->db->beginTransaction();

            // Actualización en la tabla diccionario_categorias
            $update_stmt = $this->db->prepare($sql_diccionario_categorias);
            $update_stmt->bindValue(':id', $id);
            $update_stmt->bindValue(':activo', 1);  // Reactivar la categoría estableciendo activo a 1
            $update_stmt->execute();

            // Si todo va bien, se confirma la transacción
            $this->db->commit();

            return ['exito' => 1, 'id' => $id, 'update' => true];
        } catch (Exception $e) {
            // Si ocurre un error, se revierte la transacción
            $this->db->rollBack();

            // Identifica cuál consulta falló y registra el error
            if ($update_stmt->errorCode() !== '00000') {
                $this->sql = $sql_diccionario_categorias;
                $this->error = implode(", ", $update_stmt->errorInfo());
            }

            $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
            $this->Error_SQL();

            return ['exito' => 0, 'error' => $this->error];
        }
    }
}
