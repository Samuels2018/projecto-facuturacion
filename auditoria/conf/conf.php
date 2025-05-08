<?php 

require_once 'config_env.php'; // Asegúrate de incluir el script de configuración


global $dbh;

error_reporting(E_ERROR | E_PARSE);
date_default_timezone_set('America/Costa_Rica');
setlocale(LC_TIME, "es_ES");

 

define('ENLACE_WEB'                , $_ENV['ENLACE_WEB']);
define('ENLACE_WEB_ERRORES'                , $_ENV['ENLACE_WEB_ERRORES']);
define('ENLACE_WEB_CUENTAS'                , $_ENV['ENLACE_WEB_CUENTAS']);
define('ENLACE_SERVIDOR'           ,$_ENV['ENLACE_SERVIDOR']);
define('ENLACE_SERVIDOR_ERRORES'           ,$_ENV['ENLACE_SERVIDOR_ERRORES']);
define('ENLACE_SERVIDOR_CUENTAS'           ,$_ENV['ENLACE_SERVIDOR_CUENTAS']);
define('ENLACE_SERVIDOR_FILES'     , $_ENV['ENLACE_SERVIDOR_FILES_CUENTAS']);
define('ENLACE_WEB_FILES_CUENTAS'     , $_ENV['ENLACE_WEB_FILES_CUENTAS']);
define('ENLACE_FILES_EMPRESAS'     , $_ENV['ENLACE_FILES_EMPRESAS_CUENTAS']);
define('DB_NAME_UTILIDADES_APOYO' , $_ENV['DB_NAME_UTILIDADES_APOYO']);

 
  

//-------------------------------------------------------------------
// Atencion  Programadores
// Daniel Julio y compañia
// Estos son objetos transversales 
// Asi no tenemos que invocarlos cada archivos
//---------------------------------------------------------------------

require_once (ENLACE_SERVIDOR."mod_seguridad/object/seguridad.object.php"   );
require_once (ENLACE_SERVIDOR.'/mod_utilidad/object/utilidades.object.php'  );

 


    define('DB_HOST_LOG' , $_ENV['DB_HOST_LOG']);
    define('DB_NAME_LOG' , $_ENV['DB_NAME_LOG']);
    define('DB_USER_LOG' , $_ENV['DB_USER_LOG']);
    define('DB_PASS_LOG' , $_ENV['DB_PASS_LOG']);
    
    
// Credenciales para la base de mdatos de la plataforma
$dbh = new PDO('mysql:host=' . $_ENV['DB_HOST_LICENCIAS_CUENTA'] . ';dbname=' . $_ENV['DB_NAME_LICENCIAS_CUENTA'] . ';charset=UTF8', $_ENV['DB_USER_LICENCIAS_CUENTA'], $_ENV['DB_PASS_LICENCIAS_CUENTA'], array(
    PDO::ATTR_PERSISTENT => true,
));

$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 



    
$resultados_pagina  = 20;
function numero($numero,$simbol='€')
{return $simbol." " . number_format($numero, 2, ".", ",");}

function numero_excel($numero)
{return number_format($numero, 2, ".", ",");}

function redondea($numero)
{return round($numero / 10.0, 0) * 10;}

function calculaedad($fechanacimiento)
{
    list($ano, $mes, $dia) = explode("-", $fechanacimiento);
    $ano_diferencia        = date("Y") - $ano;
    $mes_diferencia        = date("m") - $mes;
    $dia_diferencia        = date("d") - $dia;
    if ($dia_diferencia < 0 || $mes_diferencia < 0) {
        $ano_diferencia--;
    }

    return $ano_diferencia;
}

function getRealIP()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    }

    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    }

    return $_SERVER['REMOTE_ADDR'];
}

function numero_decimal($numero)
{return  number_format($numero, 2, ",", ".");}

function numero_dolar($numero)
{return " $ " . number_format($numero, 2, ".", ",");}

function numero_simple($numero)
{return " " . number_format($numero, 2, ".", ",");}

function acceso_invalido($texto = "", $json =false)
{

    
    $aviso = '
    <div class="middle-content container-xxl p-0">
      <div class="row layout-top-spacing">
          <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
              <div class="row my-4">
 

              <div class="col-12">
                            <div class="alert alert-arrow-right alert-icon-right alert-light-success alert-dismissible fade show mb-4" role="alert">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12" y2="16"></line></svg>
                                <strong>No Tienes Acceso a esta secci&oacute;n.</strong> '.$texto.'
                            
                                </div>
                </div>
              </div>
      </div>
  </div>
  </div>';

    $respuesta['error'] = 1;
    $respuesta['exito'] = 0;
    $respuesta['mensaje_txt']   = "Usuario No logeado";
    $respuesta['error_logeo']   = 1;

    if  ($json===true){
        return ($respuesta);
    } else {
        return $aviso;
    }
    


    
}

 

function obtenerFechaEnLetra($fecha_inicial)
{
    $dia  = conocerDiaSemanaFecha($fecha_inicial);
    $num  = date("j", strtotime($fecha_inicial));
    $anno = date("Y", strtotime($fecha_inicial));
    $mes  = array('enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');
    $mes  = $mes[(date('m', strtotime($fecha_inicial)) * 1) - 1];
    return $dia . ', ' . $num . ' de ' . $mes . ' del ' . $anno;
}

function conocerDiaSemanaFecha($fecha_inicial)
{
    $dias = array('Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado');
    $dia  = $dias[date('w', strtotime($fecha_inicial))];
    return $dia;
}

// 18.08.20 ALEXIS SANCHEZ
// FUNCTION CLEAR STRING TO URL
function clear_string_url($string)
{
    $string   = str_replace('(', '', $string);
    $string   = str_replace(')', '', $string);
    $string   = str_replace('-', '_', $string);
    $string   = str_replace(' ', '_', $string);
    $string   = str_replace('/', '', $string);
    $string   = str_replace('&', '_', $string);
    $string   = str_replace('"', '', $string);
    $string   = str_replace("'", '', $string);
    $string   = str_replace(',', '_', $string);
    $original = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
    $replace  = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
    $string   = utf8_decode($string);
    $string   = strtr($string, utf8_decode($original), $replace);
    $string   = strtolower($string);
    return $string;
}

function clear_string($string)
{
    $string   = str_replace('(', '', $string);
    $string   = str_replace(')', '', $string);
    $string   = str_replace('"', '', $string);
    $string   = str_replace("'", '', $string);
    $string   = str_replace(',', '_', $string);
    $string   = utf8_decode($string);
    return $string;
}


function sanitize_string($input) {
    return filter_var($input, FILTER_SANITIZE_STRING);
}

function returnSplitNameClient($name, $tam = 40 ) 
{
    // VALIDA EL TAMAÑO DEL NOMBRE DEL TERCERO
    if (strlen($name) > $tam) {
        $name = substr($name, 0, $tam) . " ...";
    }

    return $name;
}


//Funcion para trazar un cambio de labels por entidades
function reemplazar_label($label,$entidad)
{
    global $dbh;
    // Credenciales para la base de datos de utilidades de apoyo
    $sql = "SELECT * FROM `fi_custom_labels` WHERE label = :label AND entidad = :entidad";

    $db  = $dbh->prepare($sql);
    $db->bindParam(':label', $label, PDO::PARAM_STR);
    $db->bindParam(':entidad', $entidad, PDO::PARAM_INT);
    $db->execute();

    $row = $db->fetch(PDO::FETCH_ASSOC);
    if ($row) { // Verificar si se encontró un registro
        return $row['label_replace']; // el texto a cambiar por entidad
    } else {
        return $label;
    }
}


function encontrar_duplicado($tabla, $campo, $valor, $entidad, $id_actual = 0)
{
    global $dbh;
    try {
        // Seleccionar la conexión adecuada
        $puente_sql = $dbh;

        // Sanitizar las variables $tabla y $campo
        $tabla = preg_replace('/[^a-zA-Z0-9_]/', '', $tabla);
        $campo = preg_replace('/[^a-zA-Z0-9_]/', '', $campo);

        // Preparar la consulta SQL
        $sql = "SELECT count($campo) as total FROM $tabla WHERE $campo = :valor AND entidad = :entidad AND rowid != :id_actual AND borrado = 0";
        $db = $puente_sql->prepare($sql);

        // Asignar valores a los parámetros
        $db->bindValue(':valor', $valor);
        $db->bindValue(':entidad', $entidad);
        $db->bindValue(':id_actual', $id_actual, PDO::PARAM_INT);

        // Ejecutar la consulta
        $db->execute();
        $u = $db->fetch(PDO::FETCH_ASSOC);
    
        return ['exito' => 1, 'total' => $u['total']];

    } catch (PDOException $e) {
        // Manejo de error
        $this->error = $e->getMessage();
        $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
        $this->Error_SQL();

        return ['exito' => 0, 'error_txt' => $db->errorInfo()];
    }
}



//Buscar el nombre de una entidad
function buscar_nombre_entidad($entidad)
{
   global $dbh_plataforma;
   // Credenciales para la base de datos de utilidades de apoyo
    $db  = $dbh_plataforma->prepare("SELECT nombre FROM `sistema_empresa` WHERE rowid = $entidad ");
    $db->execute();
    $row = $db->fetch(PDO::FETCH_ASSOC);
    return $row['nombre'];
}

//Obtener el monto actual en colones
function monto_actual_colones()
{

    // Credenciales para la base de datos de utilidades de apoyo
    global $dbh_utilidades_Apoyo;
    $db  = $dbh_utilidades_Apoyo->prepare("SELECT * FROM `tipo_cambio` ORDER BY rowid DESC limit 1");
    $db->execute();
    $row = $db->fetch(PDO::FETCH_ASSOC);
    return floatval($row['venta']);
}


//FUNCION GLOBAL PARA CONVERSION DE MONEDAS 
function conversionMoneda_old($monto, $monedaOrigen, $monedaDestino,$monedaactual_colones)
{
    $monto = floatval($monto);

    //obtenemos  el monto de colones para cuando sea en dolares cambiar a colones
    $exchange_from_usd_to_crc = $monedaactual_colones; 


    if($exchange_from_usd_to_crc <= 0) $exchange_from_usd_to_crc = 1;

    // Tasas de cambio ejemplo
    $tasaColonesADolares = 1 / $exchange_from_usd_to_crc; // 1 Colón = 0.0017 Dólares
    $tasaDolaresAColones = $exchange_from_usd_to_crc; // 1 Dólar = 588 Colones

    $tasaColonesAEuros = 0.0015; // 1 Colón = 0.0015 Euros
    $tasaEurosAColones = 667; // 1 Euro = 667 Colones
    $tasaDolaresAEuros = 0.88; // 1 Dólar = 0.88 Euros
    $tasaEurosADolares = 1.14; // 1 Euro = 1.14 Dólares

    // Convertir monto
    if ($monedaOrigen === "CRC" && $monedaDestino === "USD") 
    {
        return $monto * $tasaColonesADolares;
    } elseif ($monedaOrigen === "USD" && $monedaDestino === "CRC") 
    {
        return $monto * $tasaDolaresAColones;
    } elseif ($monedaOrigen === "CRC" && $monedaDestino === "EUR")
    {
        return $monto * $tasaColonesAEuros;
    } elseif ($monedaOrigen === "EUR" && $monedaDestino === "CRC")
    {
        return $monto * $tasaEurosAColones;
    } elseif ($monedaOrigen === "USD" && $monedaDestino === "EUR")
    {
        return $monto * $tasaDolaresAEuros;
    } elseif ($monedaOrigen === "EUR" && $monedaDestino === "USD")
    {
        return $monto * $tasaEurosADolares;
    } else {
        //retornamos el monto normal
        return  $monto;
    }
}


//POR API
function conversionMoneda($monto , $currency_from , $currency_to  ){
    $token = "2572faf289e1bcea855c3f7e";
    $req_url = "https://v6.exchangerate-api.com/v6/{$token}/pair/{$currency_from}/{$currency_to}";
    $response_json = file_get_contents($req_url);
    // Continuing if we got a result
    if(false !== $response_json) {
        try {
            $response = json_decode($response_json);
            if('success' === $response->result) {
                //$monto =  round($monto * $response->conversion_rate , 2);
                $monto =  $monto * $response->conversion_rate ; 
            }
            return $monto;
        }
        catch(Exception $e)
        {
            return  $monto;
        }
    }

}


// Función para limpiar archivos existentes de un avatar en el directorio
function cleanOldFiles($directory, $userId) {
    $files = glob($directory . $userId . '*'); // Buscar archivos que comienzan con el ID de usuario
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file); // Eliminar el archivo
        }
    }
}


// Función para crear el thumbnail de una imagen
function createThumbnail($src, $dest, $targetWidth, $targetHeight) {
    // Obtener el tamaño original
    list($width, $height) = getimagesize($src);

    // Crear una nueva imagen con las dimensiones deseadas
    $thumb = imagecreatetruecolor($targetWidth, $targetHeight);

    // Crear la imagen desde el archivo dependiendo de su extensión
    $image = null;
    switch (strtolower(pathinfo($src, PATHINFO_EXTENSION))) {
        case 'jpeg':
        case 'jpg':
            $image = imagecreatefromjpeg($src);
            break;
        case 'png':
            $image = imagecreatefrompng($src);
            break;
        case 'gif':
            $image = imagecreatefromgif($src);
            break;
        default:
            return false;
    }

    // Redimensionar la imagen
    imagecopyresampled($thumb, $image, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height);

    // Guardar la imagen thumbnail
    $result = false;
    switch (strtolower(pathinfo($src, PATHINFO_EXTENSION))) {
        case 'jpeg':
        case 'jpg':
            $result = imagejpeg($thumb, $dest);
            break;
        case 'png':
            $result = imagepng($thumb, $dest);
            break;
        case 'gif':
            $result = imagegif($thumb, $dest);
            break;
    }

    // Liberar memoria
    imagedestroy($thumb);
    imagedestroy($image);

    return $result;
}