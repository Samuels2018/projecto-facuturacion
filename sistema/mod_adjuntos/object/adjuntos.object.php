<?php 

include_once(ENLACE_SERVIDOR."mod_seguridad/object/seguridad.object.php");

class Adjunto extends  Seguridad
{

    private $db;
    public  $entidad;
    public  $tipo_documento;
    public  $id;
    public  $fk_documento;

   
   
    // Función __construct que acepta una conexión a la base de datos
    public function __construct($db , $entidad )  
    {
        $this->db = $db;

        if (empty($entidad)) { echo "Debe indicarse la entidad antes de continuar "; exit(1);}
        parent::__construct();  // Esto inicializa la clase SEGURIDAD
       
        $this->entidad = $entidad;


    } // Funcion Constructor


      /* Insertar un nuevo adjunto */
    /*********************************************/
    public function insertar_adjunto()
    {
        $sql = "INSERT INTO fi_adjuntos (entidad, fk_documento, tipo_documento, label, activo, descripcion, creado_fecha, creado_fk_usuario) 
                VALUES (:entidad, :fk_documento, :tipo_documento, :label, 1, :descripcion, NOW(), :creado_fk_usuario)";
        
        $st = $this->db->prepare($sql);
        $st->bindValue(':entidad', $this->entidad, PDO::PARAM_INT);
        $st->bindValue(':fk_documento', $this->fk_documento, PDO::PARAM_INT);
        $st->bindValue(':tipo_documento', $this->tipo_documento, PDO::PARAM_STR);
        $st->bindValue(':label', $this->label, PDO::PARAM_STR);
        $st->bindValue(':descripcion', $this->descripcion, PDO::PARAM_STR);
        $st->bindValue(':creado_fk_usuario', $this->creado_fk_usuario, PDO::PARAM_INT);
        
        $result = $st->execute();

        if ($result) {
            return ['error' => 0, 'datos' => $result];
        } else {
            $errorInfo = $st->errorInfo();
            $this->sql = $sql;
            $this->error = implode(", ", $errorInfo).implode(", ", $errorInfo);
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
            $this->Error_SQL();
            return ['error' => 1, 'datos' => implode('-', $errorInfo)];
        }
    }



    /*Obtener los adjuntos de una cotizacion*/
    /*********************************************/
     public function obtener_adjuntos()
    {
            $sql = "SELECT * FROM fi_adjuntos WHERE  fk_documento = ".$this->fk_documento." 
              AND   tipo_documento = '".$this->tipo_documento."' order by rowid  DESC";
            $dbj = $this->db->prepare($sql);
            $dbj->execute();
            $data = $dbj->fetchAll(PDO::FETCH_OBJ);
            return $data;

    }

    public function borrar_adjunto($datos)
    {
        // Ruta del archivo a eliminar
        $rutaArchivo = ENLACE_FILES_EMPRESAS . 'imagenes/entidad_'.$this->entidad.'/'.$this->tipo_documento.'/' . $datos->label;


        // Verificar si el archivo existe y eliminarlo
        if (file_exists($rutaArchivo)) {
            if (unlink($rutaArchivo)) {
                // El archivo se eliminó con éxito
            } else {
                // No se pudo eliminar el archivo
                $consulta['error'] = 1;
                $consulta['datos'] = "No se pudo eliminar la imagen";
                return $consulta;
            }
        }

        // Actualizar la base de datos para marcar el adjunto como borrado
       // Eliminar el registro de la base de datos
        $sql = "DELETE FROM fi_adjuntos 
                WHERE rowid = :rowid AND fk_documento = :fk_documento AND tipo_documento = :tipo_documento AND entidad=:entidad ";

        $db = $this->db->prepare($sql);
        $db->bindValue(':fk_documento', $datos->fk_documento, PDO::PARAM_INT);
        $db->bindValue(':rowid', $datos->id, PDO::PARAM_INT);
        $db->bindValue(':tipo_documento', $datos->tipo_documento, PDO::PARAM_STR);
        $db->bindValue(':entidad', $datos->entidad, PDO::PARAM_STR);

        $result = $db->execute();

        if ($result) {
            $consulta['error'] = 0;
            $consulta['datos'] = "Adjunto borrado exitosamente.";
        } else {

            $errorInfo = $db->errorInfo();
            $this->sql = $sql;
            $this->error = implode(", ", $errorInfo).implode(", ", $errorInfo);
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
            $this->Error_SQL();

            $a = implode('-', $db->errorInfo());
            $a .= implode('-', $this->db->errorInfo());
            $consulta['error'] = 1;
            $consulta['datos'] = $a;
        }

        return $consulta;
    }

}


?>