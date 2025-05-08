<?php
namespace Logger;

session_start();
include "../../conf/conf.php";

use Seguridad;

class Logger extends  Seguridad
{
     public static function Logger($tipo, $archivo, $mensaje, $usuario=''){
          $url = $_ENV["ENLACE_WEB_LOG"]."?action=crearlog";
          $method = 'POST';
          $data = [
               'tipo' => $tipo,
               'usuario' => $_SESSION["usuario"]??$usuario,
               'ip' => getRealIP(),
               'clase' => $archivo,
               'error' => $mensaje,
               'entidad' => $_SESSION['Entidad']??'no defined'
           ];
           $ch = curl_init();
           
           // Configurar URL y método
           curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));

          // Si es POST o PUT, enviar datos
          if (in_array(strtoupper($method), ['POST', 'PUT']) && !empty($data)) {
               curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
               curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen(json_encode($data))
               ]);
          }

          // Opciones para llamadas asincrónicas
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, false); // No esperar la respuesta
          curl_setopt($ch, CURLOPT_TIMEOUT, 1);           // Tiempo de espera bajo
          curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);  // Evitar caché
          
          // Ejecutar cURL
          curl_exec($ch);

          // Cerrar el recurso
          curl_close($ch);
     }
     public function consultarlog($fecha_inicio, $fecha_fin, $usuario){
          // URL del servicio
          $url = $_ENV["ENLACE_WEB_LOG"]."?action=consultarlog&fecha_inicio=$fecha_inicio&fecha_fin=$fecha_fin&usuario=$usuario";
          
          $ch = curl_init();

          // Configurar cURL
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Esperar respuesta
          curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

          // Ejecutar la solicitud
          $response = curl_exec($ch);

          // Manejo de errores
          if (curl_errno($ch)) {
               echo 'Error: ' . curl_error($ch);
          }

          curl_close($ch);

          return $response;
     }
     public function downloadLogs($fecha_inicio, $fecha_fin, $usuario){
          $url = $_ENV["ENLACE_WEB_LOG"]."?action=descargarlog&fecha_inicio=$fecha_inicio&fecha_fin=$fecha_fin&usuario=$usuario";
      
          $ch = curl_init();
      
          // Configurar cURL
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Esperar respuesta
          curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
      
          // Ejecutar la solicitud
          $response = curl_exec($ch);
      
          // Manejo de errores
          if (curl_errno($ch)) {
              echo 'Error: ' . curl_error($ch);
              return null;
          }
      
          curl_close($ch);
      
          return $response;
     }
}