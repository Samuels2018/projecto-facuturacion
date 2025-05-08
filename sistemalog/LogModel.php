<?php

require_once 'Database.php';

class LogModel {
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    public function createLog($data) {
        $sql = "INSERT INTO log_sistema (tipo, usuario, ip, fecha, clase, error, entidad) 
                VALUES (:tipo, :usuario, :ip, :fecha, :clase, :error, :entidad)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
    }

    public function fetchLogs($filters) {
        $query = "SELECT * FROM log_sistema WHERE 1=1";
        $params = [];
    
        if (!empty($filters['fecha_inicio']) && !empty($filters['fecha_fin'])) {
            $query .= " AND fecha BETWEEN :fecha_inicio AND :fecha_fin";
            $params[':fecha_inicio'] = $filters['fecha_inicio'];
            $params[':fecha_fin'] = $filters['fecha_fin'];
        }
        if (!empty($filters['usuario'])) {
            $query .= " AND usuario = :usuario";
            $params[':usuario'] = $filters['usuario'];
        }
        if (!empty($filters['empresa'])) {
            $query .= " AND entidad = :empresa";
            $params[':empresa'] = $filters['empresa'];
        }
    
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
