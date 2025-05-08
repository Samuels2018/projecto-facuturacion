<?php 

class impuestos extends Seguridad
{	

    public $entidad;

	// Propiedades privadas
    private $db;
    public function __construct($db, $entidad)
    {

        $this->entidad = $entidad;
        $this->db = $db;
        parent::__construct();
    }

    public function crear() 
    {
        $sql = "INSERT INTO diccionario_impuestos (
                    entidad, 
                    impuesto, 
                    recargo_equivalencia, 
                    impuesto_texto, 
                    pais, 
                    autogen,
                    activo
                ) VALUES (
                    :entidad, 
                    :impuesto, 
                    :recargo_equivalencia, 
                    :impuesto_texto, 
                    :pais, 
                    :autogen,
                    1
                )";

        $db = $this->db->prepare($sql);
        $db->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);
        $db->bindValue(':impuesto', $this->impuesto, PDO::PARAM_STR);
        $db->bindValue(':recargo_equivalencia', $this->recargo_equivalencia, PDO::PARAM_STR);
        $db->bindValue(':impuesto_texto', $this->impuesto_texto, PDO::PARAM_STR);
        $db->bindValue(':pais', $this->pais, PDO::PARAM_STR);
        $db->bindValue(':autogen', $this->autogen, PDO::PARAM_INT);
        $result = $db->execute();
        $consulta = array();

        if ($result) {
            $consulta['error'] = 0;
            $consulta['mensaje'] = 'Registro de impuesto exitoso';
            $consulta['datos'] = $consulta;
        } else {
            $a = implode('-', $db->errorInfo());
            $a .= implode('-', $this->db->errorInfo());
            $consulta['error'] = 1;
            $consulta['mensaje'] = 'Ocurrio un error al intentar registrar el impuesto';
            $consulta['datos'] = $a;
            // Registro de la consulta erronea
            $this->sql = $sql;
            $this->error = implode(", ", $db->errorInfo()).implode(", ", $db->errorInfo());
            $this->proceso = __FUNCTION__ . " del Objeto " . __CLASS__;
            $this->Error_SQL();
        }

        return $consulta;
    }


    public function actualizar()
    {
        // Array para almacenar los campos que se van a actualizar
        $campos_a_actualizar = [];
        $valores_a_ligar = [];

        // Verificar y agregar cada campo solo si se pasó en $_REQUEST

        if (isset($this->impuesto)) {
            $campos_a_actualizar[] = "impuesto = :impuesto";
            $valores_a_ligar[':impuesto'] = $this->impuesto;
        }
        
        if (isset($this->recargo_equivalencia)) {
            $campos_a_actualizar[] = "recargo_equivalencia = :recargo_equivalencia";
            $valores_a_ligar[':recargo_equivalencia'] = $this->recargo_equivalencia;
        }
        
        if (isset($this->impuesto_texto)) {
            $campos_a_actualizar[] = "impuesto_texto = :impuesto_texto";
            $valores_a_ligar[':impuesto_texto'] = $this->impuesto_texto;
        }

        if (isset($this->pais)) {
            $campos_a_actualizar[] = "pais = :pais";
            $valores_a_ligar[':pais'] = $this->pais;
        }

        if (isset($this->autogen)) {
            $campos_a_actualizar[] = "autogen = :autogen";
            $valores_a_ligar[':autogen'] = $this->autogen;
        }

        // Si no hay campos para actualizar, retornar un error
        if (empty($campos_a_actualizar)) {
            return [
                'error' => 1,
                'mensaje' => 'No se proporcionaron campos para actualizar'
            ];
        }

        // Construir la consulta SQL dinámicamente
        $sql = "UPDATE diccionario_impuestos SET " . implode(', ', $campos_a_actualizar) . " WHERE rowid = :rowid AND entidad=:entidad";

        // Preparar la consulta
        $db = $this->db->prepare($sql);

        // Ligar los valores
        foreach ($valores_a_ligar as $parametro => $valor) {
            $db->bindValue($parametro, $valor);
        }


        // Ligar el rowid
        $db->bindValue(':rowid', $this->rowid, PDO::PARAM_INT);
        $db->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);

        // Ejecutar la consulta
        $result = $db->execute();
        $consulta = array();

        if ($result) {
            $consulta['error'] = 0;
            $consulta['mensaje'] = 'Actualización de impuesto exitosa';
        } else {
            $a = implode('-', $db->errorInfo());
            $a .= implode('-', $this->db->errorInfo());
            $consulta['error'] = 1;
            $consulta['mensaje'] = 'Ocurrió un error al intentar actualizar el impuesto '.$sql;
            $consulta['datos'] = $a;
            // Registro de la consulta errónea
            $this->sql = $sql;
            $this->error = implode(", ", $db->errorInfo()).implode(", ", $db->errorInfo());
            $this->proceso = __FUNCTION__ . " del Objeto " . __CLASS__;
            $this->Error_SQL();
        }
        return $consulta;
    }


    public function eliminar_impuesto()
    {
        // Definir la consulta SQL para actualizar el campo 'activo'
        $sql = "UPDATE diccionario_impuestos SET activo = 0 WHERE rowid = :rowid AND entidad = :entidad";

        // Inicializar el array de respuesta
        $consulta = [];

        try {
            // Preparar la consulta
            $db = $this->db->prepare($sql);

            // Ligar los valores para 'rowid' y 'entidad'
            $db->bindValue(':rowid', $this->rowid, PDO::PARAM_INT);
            $db->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);

            // Ejecutar la consulta
            $result = $db->execute();

            // Verificar si la consulta afectó alguna fila
            if ($result && $db->rowCount() > 0) {
                $consulta['error'] = 0;
                $consulta['mensaje'] = 'Desactivación de impuesto exitosa';
            } else {
                $consulta['error'] = 1;
                $consulta['mensaje'] = 'No se encontró el impuesto para desactivar';
            }
        } catch (PDOException $e) {
            // Manejo de errores
            $a = implode('-', $db->errorInfo());
            $a .= implode('-', $this->db->errorInfo());
            $consulta['error'] = 1;
            $consulta['mensaje'] = 'Ocurrió un error al intentar desactivar el impuesto';
            $consulta['datos'] = $a;
            // Registro de la consulta errónea
            $this->sql = $sql;
            $this->error = implode(", ", $db->errorInfo());
            $this->proceso = __FUNCTION__ . " del Objeto " . __CLASS__;
            $this->Error_SQL();
        }

        return $consulta;
    }



    public function listar_impuestos(){

        $sql =  "SELECT * from diccionario_impuestos WHERE entidad = :entidad AND activo = 1";
        $dbh = $this->db->prepare($sql);
        $dbh->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);
        $dbh->execute();
        $resultado = array();
        while($data   = $dbh->fetch(PDO::FETCH_OBJ)){
          $resultado[$data->rowid]['impuesto_texto'] = $data->impuesto_texto;
          $resultado[$data->rowid]['impuesto']       = $data->impuesto;
          $resultado[$data->rowid]['recargo_equivalencia'] = $data->recargo_equivalencia;
          $resultado[$data->rowid]['rowid'] = $data->rowid;
        }
        return $resultado;
      }

}