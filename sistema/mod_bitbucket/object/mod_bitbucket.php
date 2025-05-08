<?php
include_once("conf/conf.php");

class BitBucket
{

     public function __construct() {}

     public function fetch()
     {
          // Configuración
          $username = BITBUCKET_USERNAME; // Tu usuario de Bitbucket
          $appPassword = BITBUCKET_PASSWORD; // El App Password generado
          $workspace = BITBUCKET_WORKSPACE; // Nombre del workspace
          $repoSlug = BITBUCKET_REPOSITORY; // Nombre del repositorio
          // echo 'AAA '. $username.$appPassword.$workspace.$repoSlug;
          // URL de la API para obtener commits
          $url = "https://api.bitbucket.org/2.0/repositories/$workspace/$repoSlug/commits";

          // Configuración de cURL
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_VERBOSE, true);
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_USERPWD, "$username:$appPassword");
          curl_setopt($ch, CURLOPT_HTTPHEADER, [
               'Accept: application/json'
          ]);

          // Ejecutar la solicitud
          $response = curl_exec($ch);
          $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
          curl_close($ch);

          $html_commits_retorno = '';
          // Verificar respuesta
          if ($httpCode === 200) {
               $commits = json_decode($response, true);
               foreach ($commits['values'] as $commit) {
                    if (strpos($commit["message"], 'PUBLIC:') === 0) {
                         $nuevaCadena = str_replace('PUBLIC:', "", $commit["message"]);
                         // $json_author = json_encode($commit["author"]);
                         $html_commits_retorno .= "<tr>
                         <td>{$commit["date"]}</td>
                         <td>{$nuevaCadena}</td>
                         </tr>";
                    }
               }
          } else {
               echo "Error al obtener los commits. Código HTTP: $httpCode";
          }

          if (strlen($html_commits_retorno) > 0) {
               echo '<table>' . $html_commits_retorno . '</table>';
          }
     }
}
