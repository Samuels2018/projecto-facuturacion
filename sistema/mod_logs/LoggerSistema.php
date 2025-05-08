<?php

session_start();
include_once "../../conf/conf.php";
include_once(ENLACE_SERVIDOR."mod_seguridad/object/seguridad.object.php");

use Seguridad;

class LoggerSistema extends  Seguridad
{
     protected $tipo;
     protected $archivo;
     protected $usuario;
     protected $usuario_nombre;
     protected $ip;
     protected $clase;
     protected $mensaje;
     protected $entidad;

     public function Logger($tipo, $archivo, $mensaje){

          $dbh_plataforma = new PDO('mysql:host=' . $_ENV['DB_HOST_PLATAFORMA'] . ';dbname=' . $_ENV['DB_NAME_PLATAFORMA'] . ';charset=UTF8', $_ENV['DB_USER_PLATAFORMA'], $_ENV['DB_PASS_PLATAFORMA'], array(
               PDO::ATTR_PERSISTENT => true,
           )); 
           
           $sql="select  u.acceso_usuario, CONCAT( u.nombre, ' ', u.apellidos) as nombre
           from usuarios u                 
           where u.rowid = :rowid"; 
           
           $db = $dbh_plataforma->prepare($sql);
           $db->bindValue(':rowid', $_SESSION["usuario"], PDO::PARAM_STR);           
           $db->execute();
           $dataUsuario = $db->fetch(PDO::FETCH_ASSOC);

          $this->tipo = $tipo;
          $this->archivo = $archivo;
          $this->usuario = $dataUsuario['acceso_usuario'];
          $this->usuario_nombre = $dataUsuario['nombre'];
          // $this->usuario = $_SESSION["usuario"];
          $this->ip = getRealIP();
          $this->clase = $archivo;
          $this->mensaje = $mensaje;
          // $this->entidad = $dataEmpresa->$_SESSION["Entidad"]??'';
          
          // $this->entidad = ( count($_SESSION['multientidad'])>0 ? $_SESSION["nombre_entidad"][0]: $_SESSION["nombre_entidad"] );
          
          $this->entidad = $_SESSION['EntidadNombre'];
          $this->Error_Log();
     }
     public function consultarlog($fecha_inicio, $fecha_fin, $entidad){
          $logs = $this->consultaLogs($fecha_inicio, $fecha_fin, $entidad);
          echo json_encode($logs);
     }
     public function downloadLogs($fecha_inicio, $fecha_fin, $entidad){
          $logs = $this->consultaLogs($fecha_inicio, $fecha_fin, $entidad);
          $csvBase64 = $this->generateCSV($logs);

          date_default_timezone_set('Europe/Madrid');
          $fechaHoraActual = date('dmY_Hi');
          echo json_encode(['message' => $csvBase64, 'filename' => $entidad.'_Log_'.$fechaHoraActual.'.csv' ]);
     }

     function generateCSV($data) {
          $filename = tempnam(sys_get_temp_dir(), 'logs_') . '.csv';
          $file = fopen($filename, 'w');
      
          // Add headers
          fputcsv($file, array_keys($data[0]));
      
          // Add rows
          foreach ($data as $row) {
              fputcsv($file, $row);
          }
      
          fclose($file);
      
          // Encode in Base64
          $csvBase64 = base64_encode(file_get_contents($filename));
      
          // Clean up temporary file
          unlink($filename);
      
          return $csvBase64;
      }
      
}