<?php

class Seguridad  {

    protected $tokens;

    /**
     * Constructor de la clase CSRFToken.
     * Inicializa el array de tokens CSRF en la sesión si no está presente.
     */
    public function __construct() {
        if (empty($_SESSION['csrf_tokens'])) {
            $_SESSION['csrf_tokens'] = [];
        }
        $this->tokens = &$_SESSION['csrf_tokens'];

              
                $this->db_log = new PDO('mysql:host=' . DB_HOST_LOG . ';dbname=' . DB_NAME_LOG. ';charset=UTF8', DB_USER_LOG, DB_PASS_LOG, array(
                    PDO::ATTR_PERSISTENT => true,
                ));

                $this->db_log->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

               

    }

    /**
     * Genera un token CSRF para un formulario específico.
     * Si ya existe un token para el formulario, devuelve el token existente.
     * Si no existe un token para el formulario, genera uno nuevo.
     *
     * @param string $formName El nombre único del formulario para el que se generará el token.
     * @return string El token CSRF generado o existente.
     */
    public function generateToken($formName) {
        if (empty($this->tokens[$formName])) {
            $this->tokens[$formName] = bin2hex(random_bytes(32));
        }
        return $this->tokens[$formName];
    }

    /**
     * Obtiene el token CSRF para un formulario específico.
     *
     * @param string $formName El nombre del formulario del que se desea obtener el token.
     * @return string|null El token CSRF del formulario especificado, o null si no se encuentra.
     */
    public function getToken($formName) {
        return isset($this->tokens[$formName]) ? $this->tokens[$formName] : null;
    }

    /**
     * Verifica si un token CSRF es válido para un formulario específico.
     *
     * @param string $formName El nombre del formulario al que pertenece el token.
     * @param string $token El token CSRF proporcionado por el usuario.
     * @return bool true si el token es válido, false de lo contrario.
     */
    public function verifyToken($formName, $token) {
        return !empty($this->tokens[$formName]) && hash_equals($this->tokens[$formName], $token);
    }



    /***********************************************************************
     * 
     * 
     * *
     * Inserta un nuevo registro de error en la tabla sql_logerrores.
     *               @return array Un array con el resultado de la operación:
     *               Si hay un error, devuelve ['error' => 1, 'error_txt' => mensaje de error].
     *               Si la inserción fue exitosa, devuelve ['error' => 0, 'id' => ID del registro insertado].
     *
     * 
     * ************************************************************************/

    public function Error_SQL() {
        // Preparar la consulta SQL
        $sql = "INSERT 
                INTO 
                sql_logerrores 
                (fk_usuario, fecha, proceso, sql_consulta, error, file) 
                VALUES 
                (:fk_usuario, NOW() , :proceso, :sql_consulta, :error, :file)
                ";
        
        

        // Ejecutar la consulta utilizando bindValue para los parámetros
        $stmt = $this->db_log->prepare($sql);
        $stmt->bindValue(':fk_usuario', $this->fk_usuario);
        $stmt->bindValue(':proceso', $this->proceso);
        $stmt->bindValue(':sql_consulta', $this->sql_consulta." ".$this->sql);
        $stmt->bindValue(':error', $this->error);
        $stmt->bindValue(':file', (empty($this->file))? __FILE__ : $this->file);

        
        $stmt->execute();


    }

    public function Error_Log() {
        $sql = "INSERT 
                INTO 
                log_sistema
                (tipo, usuario, ip, fecha, clase, mensaje, entidad, usuario_nombre) 
                VALUES 
                (:tipo, :usuario, :ip, NOW(), :clase, :mensaje, :entidad, :usuario_nombre)
                ";
        
        

        // Ejecutar la consulta utilizando bindValue para los parámetros
        $stmt = $this->db_log->prepare($sql);
        $stmt->bindValue(':tipo', $this->tipo);
        $stmt->bindValue(':usuario', $this->usuario);
        $stmt->bindValue(':ip', $this->ip);
        $stmt->bindValue(':clase', $this->clase);
        $stmt->bindValue(':mensaje', $this->mensaje??'');
        $stmt->bindValue(':entidad', $this->entidad);
        $stmt->bindValue(':usuario_nombre', $this->usuario_nombre);
        
        $stmt->execute();
    }

    public function consultaLogs($fecha_inicio, $fecha_fin, $entidad){
        $query = "SELECT entidad as 'Beneficiario', fecha as 'Fecha Acceso', CONCAT(UPPER(SUBSTRING(clase, 1, 1)), LOWER(SUBSTRING(clase, 2))) as TipoEvento, usuario as CorreoUsuario, usuario_nombre as 'Nombre Usuario', ip as 'Direccion IP',  'Factuguay' as 'Nombre Herramienta'
        FROM log_sistema WHERE 1=1";
        $params = [];

        if (!empty($fecha_inicio) && !empty($fecha_fin)) {
             $query .= " AND fecha BETWEEN :fecha_inicio AND :fecha_fin";
             $params[':fecha_inicio'] = $fecha_inicio. " 00:00:00";
             $params[':fecha_fin'] = $fecha_fin. " 23:59:59";
        }
        if (!empty($entidad)) {
             $query .= " AND entidad = :entidad";
             $params[':entidad'] = $entidad;
        }
        $stmt = $this->db_log->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
   }
   public function consultaLogsMax($max, $entidad){
    $query = "SELECT tipo, usuario, ip, fecha, clase, mensaje FROM log_sistema WHERE 1=1";
    $params = [];

    if (!empty($entidad)) {
         $query .= " AND entidad = :entidad";
         $params[':entidad'] = $entidad;
    }
    $query .= " ORDER BY rowid DESC LIMIT ". $max;
    $stmt = $this->db_log->prepare($query);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}
 


