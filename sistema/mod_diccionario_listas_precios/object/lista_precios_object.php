<?php 

class ListaPreciosClientes extends Seguridad
{
    public $entidad;
    public $etiqueta;
    public $activo;
    public $borrado;
    public $creado_fecha;
    public $creado_fk_usuario;
    public $borrado_fecha_usuario;
    public $borrado_fk_usuario;

    function __construct($db)
    {
        $this->db = $db;
        parent::__construct();  // Inicializa la clase SEGURIDAD
    }

    function fetch($id)
    {
        $sql = "SELECT u.* 
                FROM fi_productos_precios_clientes_listas u     
                WHERE u.rowid = :rowid";

        $db = $this->db->prepare($sql);
        $db->bindValue('rowid', $id, PDO::PARAM_INT);
        $db->execute();
        $u = $db->fetch(PDO::FETCH_ASSOC);

        // Asignar los valores obtenidos a las propiedades de la clase
        $this->id = $u['rowid'];
        $this->entidad = $u['entidad'];
        $this->etiqueta = $u['etiqueta'];
        $this->activo = $u['activo'];
        $this->borrado = $u['borrado'];
        $this->creado_fecha = $u['creado_fecha'];
        $this->creado_fk_usuario = $u['creado_fk_usuario'];
        $this->borrado_fecha_usuario = $u['borrado_fecha_usuario'];
        $this->borrado_fk_usuario = $u['borrado_fk_usuario'];
    }

    public function listar_lista_precios()
    {
        $sql = "SELECT u.* 
                FROM fi_productos_precios_clientes_listas u   
                WHERE entidad = :entidad";
        $db = $this->db->prepare($sql);
        $db->bindValue('entidad', $this->entidad, PDO::PARAM_INT);
        $db->execute();
        $result = $db->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function crear_lista_precios()
    {
        try {
            // Preparar la consulta SQL para insertar en la tabla 'fi_productos_precios_clientes_listas'
            $sqlcheck = "
                INSERT INTO fi_productos_precios_clientes_listas (entidad, etiqueta, creado_fecha, creado_fk_usuario)
                VALUES (:entidad, :etiqueta, now(), :creado_fk_usuario)
            ";
            $insert_stmt = $this->db->prepare($sqlcheck);

            // Asignar valores a los parámetros
            $insert_stmt->bindValue(':entidad', $this->entidad);
            $insert_stmt->bindValue(':etiqueta', $this->etiqueta);
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

    public function actualizar_lista_precios()
    {
        try {
            // Preparar la consulta SQL para actualizar la tabla 'fi_productos_precios_clientes_listas'
            $sqlupdate = "
                UPDATE fi_productos_precios_clientes_listas
                SET 
                    etiqueta = :etiqueta,
                    activo = :activo
                WHERE rowid = :rowid AND entidad = :entidad
            ";
            $update_stmt = $this->db->prepare($sqlupdate);

            // Asignar valores a los parámetros
            $update_stmt->bindValue(':entidad', $this->entidad);
            $update_stmt->bindValue(':etiqueta', $this->etiqueta);
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

    public function borrar_lista_precios($id)
    {
        // Consulta SQL para el borrado lógico en la tabla 'fi_productos_precios_clientes_listas'
        $sql_borrar_lista = "
            UPDATE fi_productos_precios_clientes_listas 
            SET borrado = 1, borrado_fecha_usuario = now(), borrado_fk_usuario = :borrado_fk_usuario 
            WHERE rowid = :id AND entidad = :entidad
        ";

        try {
            // Inicia la transacción
            $this->db->beginTransaction();

            // Actualización para el borrado lógico
            $update_stmt = $this->db->prepare($sql_borrar_lista);
            $update_stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $update_stmt->bindValue(':entidad', $this->entidad);
            $update_stmt->bindValue(':borrado_fk_usuario', $this->borrado_fk_usuario);
            $update_stmt->execute();

            // Si todo va bien, confirmar la transacción
            $this->db->commit();

            return ['exito' => 1, 'id' => $id, 'borrado' => true];
        } catch (Exception $e) {
            // Si ocurre un error, se revierte la transacción
            $this->db->rollBack();

            // Identificar y registrar el error
            if ($update_stmt->errorCode() !== '00000') {
                $this->sql = $sql_borrar_lista;
                $this->error = implode(", ", $update_stmt->errorInfo());
            }

            $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
            $this->Error_SQL();

            return ['exito' => 0, 'error' => $this->error];
        }
    }
}
