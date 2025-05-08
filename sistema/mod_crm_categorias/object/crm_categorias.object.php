<?php

class Categoria_crm extends Seguridad
{
    public $entidad;

    function __construct($db){
        $this->db = $db; 
        parent::__construct();  // Esto inicializa la clase SEGURIDAD
    } 

    function fetch($id)
    {	
        $sql = "SELECT u.* 
                FROM diccionario_crm_oportunidades_categorias u     
                WHERE u.rowid = :rowid";
        
        $db = $this->db->prepare($sql);
        $db->bindValue('rowid', $id, PDO::PARAM_INT); 
        $db->execute();
        $u = $db->fetch(PDO::FETCH_ASSOC);

        // Asignar valores a las propiedades de la clase
        $this->id      = $u['rowid'];
        $this->label   = $u['etiqueta'];
        $this->entidad = $u['entidad'];
        $this->prioridad = $u['prioridad'];
        $this->estilo = $u['estilo'];
        $this->activo  = $u['activo'];
    }

    public function crear_categoria_crm()
    {
        try {
            // Preparar la consulta SQL para insertar en la tabla 'diccionario_crm_oportunidades_categorias'
            $sqlcheck = "
                INSERT INTO diccionario_crm_oportunidades_categorias (etiqueta, entidad, prioridad, estilo, activo, creado_fecha, creado_fk_usuario)
                VALUES (:etiqueta, :entidad, :prioridad, :estilo, :activo, :creado_fecha, :creado_fk_usuario)
            ";
            $insert_stmt = $this->db->prepare($sqlcheck);

            // Asignar valores a los parámetros
            $insert_stmt->bindValue(':etiqueta', $this->label);
            $insert_stmt->bindValue(':entidad', $this->entidad);
            $insert_stmt->bindValue(':prioridad', $this->prioridad);
            $insert_stmt->bindValue(':estilo', $this->estilo);
            $insert_stmt->bindValue(':activo', $this->estado);
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

    public function actualizar_categoria_crm()
    {
        try {
            // Preparar la consulta SQL para actualizar la tabla 'diccionario_crm_oportunidades_categorias'
            $sqlupdate = "
                UPDATE diccionario_crm_oportunidades_categorias
                SET etiqueta = :etiqueta, prioridad = :prioridad, estilo = :estilo, activo = :activo
                WHERE rowid = :rowid AND entidad = :entidad
            ";
            $update_stmt = $this->db->prepare($sqlupdate);

            // Asignar valores a los parámetros
            $update_stmt->bindValue(':etiqueta', $this->label);
            $update_stmt->bindValue(':rowid', $this->id);
            $update_stmt->bindValue(':entidad', $this->entidad);
            $update_stmt->bindValue(':prioridad', $this->prioridad);
            $update_stmt->bindValue(':estilo', $this->estilo);
            $update_stmt->bindValue(':activo', $this->estado);

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

    public function borrar_categoria_crm($id)
    {
        // Consulta SQL para borrar en la tabla 'diccionario_crm_oportunidades_categorias'
        $sql_diccionario_crm = "UPDATE diccionario_crm_oportunidades_categorias 
                                SET borrado = 1, borrado_fecha = now(), borrado_fk_usuario = :borrado_fk_usuario 
                                WHERE rowid = :id AND entidad = :entidad";

        
            // Actualización en la tabla diccionario_crm_oportunidades_categorias
            $update_stmt = $this->db->prepare($sql_diccionario_crm);
            $update_stmt->bindValue(':id', $id);
            $update_stmt->bindValue(':entidad', $this->entidad);
            $update_stmt->bindValue(':borrado_fk_usuario', $this->borrado_fk_usuario);
            $update_stmt->execute();


            $a = $update_stmt->execute();

            if ($a){
                $this->id = $datos->id;
                $resultado['id']      =   $datos->id;
                $resultado['exito']   =   1;
                $resultado['mensaje'] =   "Producto actualizado con Exito";
                $resultado['update']  =   1;
                
        
          } else { 
                $resultado['exito']= 0;
                $resultado['error']=  implode(", ", $update_stmt->errorInfo());
                $this->sql     =   $sql_diccionario_crm;
                $this->error   =   implode(", ", $update_stmt->errorInfo())." ". implode(", ", $this->db->errorInfo());
                $this->proceso = __FUNCTION__ ." Del Objeto ".__CLASS__;
                $this->Error_SQL();
        }

            return $resultado;
 
    }
}
