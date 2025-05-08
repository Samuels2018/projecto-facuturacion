<?php

require_once 'LogModel.php';
require_once 'utils.php';

class LogController {
    private $model;
    private $storage;

    public function __construct($storage) {
        $this->model = new LogModel();
        $this->storage = $storage; // 'database' o 'file'
    }

    public function handleRequest($method, $action, $data) {
        switch ($action) {
            case 'crearlog':
                if ($method === 'POST') {
                    $data['fecha'] = $data['fecha'] ?? date('Y-m-d H:i:s');

                    if ($this->storage === 'database') {
                        $this->model->createLog($data);
                    } elseif ($this->storage === 'file') {
                        $this->writeLogToFile($data);
                    }

                    echo json_encode(['message' => 'Log created successfully']);
                }
                break;

            case 'consultarlog':
                if ($method === 'GET') {
                    if ($this->storage === 'database') {
                        $logs = $this->model->fetchLogs($data);
                        echo json_encode($logs);
                    } elseif ($this->storage === 'file') {
                        $logs = $this->fetchLogsFromFiles($data);
                        echo json_encode($logs);
                    }
                }
                break;

            case 'descargarlog':
                if ($method === 'GET') {
                    if ($this->storage === 'database') {
                        $logs = $this->model->fetchLogs($data);
                        if ($logs) {
                            $csvBase64 = generateCSV($logs);
                            echo json_encode(['csv_base64' => $csvBase64]);
                        } else {
                            echo json_encode(['message' => '']);
                        }
                    } elseif ($this->storage === 'file') {
                        $logs = $this->fetchLogsFromFiles($data);
                        if ($logs) {
                            $csvBase64 = generateCSV($logs);
                            echo json_encode(['csv_base64' => $csvBase64]);
                        } else {
                            echo json_encode(['message' => '']);
                        }
                    }
                }
                break;

            default:
                http_response_code(404);
                echo json_encode(['message' => 'Invalid action']);
        }
    }

    private function writeLogToFile($data) {
        $date = date('Y-m-d');
        $entity = $data['entidad'] ?? 'general';
        $filename = "logs_{$entity}_{$date}.log";

        $logEntry = sprintf(
            "[%s] Tipo: %s, Usuario: %s, IP: %s, Clase: %s, Error: %s, Entidad: %s\n",
            $data['fecha'],
            $data['tipo'] ?? 'info',
            $data['usuario'] ?? 'unknown',
            $data['ip'] ?? 'unknown',
            $data['clase'] ?? 'unknown',
            $data['error'] ?? 'none',
            $entity
        );

        file_put_contents($filename, $logEntry, FILE_APPEND);
    }

    private function fetchLogsFromFiles($filters) {
        $logs = [];
        $path = './'; // Directorio donde se almacenan los archivos

        $files = glob("{$path}logs_*_*.log"); // Obtener todos los archivos de logs

        foreach ($files as $file) {
            // Extraer fecha y entidad del nombre del archivo
            if (preg_match('/logs_(.+?)_(\d{4}-\d{2}-\d{2})\.log$/', basename($file), $matches)) {
                $entity = $matches[1];
                $date = $matches[2];

                // Filtrar por entidad y fecha si están definidos
                if ((!empty($filters['empresa']) && $filters['empresa'] !== $entity) ||
                    (!empty($filters['fecha_inicio']) && $date < $filters['fecha_inicio']) ||
                    (!empty($filters['fecha_fin']) && $date > $filters['fecha_fin'])) {
                    continue;
                }

                // Leer el archivo y procesar líneas
                $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach ($lines as $line) {
                    $logs[] = [
                        'log' => $line,
                        'archivo' => $file
                    ];
                }
            }
        }

        return $logs;
    }
}
