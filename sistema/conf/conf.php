<?php 

require_once 'config_env.php'; // Asegúrate de incluir el script de configuración

global $dbh_utilidades_Apoyo;
global $dbh_plataforma;


error_reporting(E_ERROR | E_PARSE);
date_default_timezone_set('Europe/Madrid');
setlocale(LC_TIME, "es_ES");

define('ENLACE_WEB'                , $_ENV['ENLACE_WEB']);
define('ENLACE_SERVIDOR'           , $_ENV['ENLACE_SERVIDOR']);
define('ENLACE_SERVIDOR_FILES'     , $_ENV['ENLACE_SERVIDOR_FILES']);
//define('ENLACE_WEB_FILES'          , $_ENV['ENLACE_WEB_FILES']);
define('ENLACE_WEB_FILES'           , $_ENV['ENLACE_WEB_FILES']);
define('ENLACE_WEB_QR'              , $_ENV['ENLACE_WEB_QR']);
define('ENLACE_WEB_VERIFACTU'       , $_ENV['ENLACE_WEB_VERIFACTU']);


define('ENLACE_WEB_FILES_CUENTAS'           , $_ENV['ENLACE_WEB_FILES_CUENTAS']);

define('ENLACE_FILES_EMPRESAS'     , $_ENV['ENLACE_FILES_EMPRESAS']);
define('ENLACE_WEB_FILES_EMPRESAS' , $_ENV['ENLACE_WEB_FILES_EMPRESAS']);

define('DB_NAME_UTILIDADES_APOYO' , $_ENV['DB_NAME_UTILIDADES_APOYO']);
define('ENLACE_FILES_XML' , $_ENV['ENLACE_FILES_XML']);


define('ENLACE_SERVIDOR_FILES_XML'      , $_ENV['ENLACE_SERVIDOR_FILES_XML']);
DEFINE('ENLACE_FILES_XML'               , $_ENV['ENLACE_FILES_XML']);

define('DB_HOST_LOG' , $_ENV['DB_HOST_LOG']);
define('DB_NAME_LOG' , $_ENV['DB_NAME_LOG']);
define('DB_USER_LOG' , $_ENV['DB_USER_LOG']);
define('DB_PASS_LOG' , $_ENV['DB_PASS_LOG']);
define('IP'           , getRealIP()        );

define('BITBUCKET_USERNAME' , $_ENV['BITBUCKET_USERNAME']);
define('BITBUCKET_PASSWORD' , $_ENV['BITBUCKET_PASSWORD']);
define('BITBUCKET_WORKSPACE' , $_ENV['BITBUCKET_WORKSPACE']);
define('BITBUCKET_REPOSITORY' , $_ENV['BITBUCKET_REPOSITORY']);

//-------------------------------------------------------------------
// Atencion  Programadores
// Daniel Julio y compañia
// Estos son objetos transversales 
// Asi no tenemos que invocarlos cada archivos
//---------------------------------------------------------------------

require_once (ENLACE_SERVIDOR."mod_seguridad/object/seguridad.object.php"   );
require_once (ENLACE_SERVIDOR.'/mod_utilidad/object/utilidades.object.php'  );



// Credenciales para la base de datos de la plataforma
$dbh_plataforma = new PDO('mysql:host=' . $_ENV['DB_HOST_PLATAFORMA'] . ';dbname=' . $_ENV['DB_NAME_PLATAFORMA'] . ';charset=UTF8', $_ENV['DB_USER_PLATAFORMA'], $_ENV['DB_PASS_PLATAFORMA'], array(
    PDO::ATTR_PERSISTENT => true,
));
$dbh_plataforma->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 


// Credenciales para la base de datos de utilidades de apoyo
$dbh_utilidades_Apoyo = new PDO('mysql:host=' . $_ENV['DB_HOST_UTILIDADES_APOYO'] . ';dbname=' . $_ENV['DB_NAME_UTILIDADES_APOYO'] . ';charset=UTF8', $_ENV['DB_USER_UTILIDADES_APOYO'], $_ENV['DB_PASS_UTILIDADES_APOYO'], array(
    PDO::ATTR_PERSISTENT => true,
));
$dbh_utilidades_Apoyo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$Utilidades = new Utilidades($dbh_utilidades_Apoyo);




if (!empty($_SESSION['licencia'])){
 
    $db  = $dbh_plataforma->prepare("select * from sistema_empresa_licencias where md5(rowid) = :licencia ");
    $db->bindValue(":licencia", $_SESSION['licencia'] ,PDO::PARAM_STR);
    $db->execute();
    $row = $db->fetch(PDO::FETCH_ASSOC);
  
    $dbh = new PDO('mysql:host='.$row['server'].';dbname='. sanitize_string($row['bd']) .';charset=UTF8', sanitize_string($row['user']) ,  sanitize_string($row['pass'])  , array(
        PDO::ATTR_PERSISTENT => true,
    ));
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
 
    unset($row);
    
}

$resultados_pagina  = 20;
function numero($numero,$simbol='€')
{return number_format($numero, 2, ".", ",") ."  $simbol" ;}

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
 
function numero_euro($numero)
{return " € " . number_format($numero, 2, ".", ",");}

function numero_simple_coma($numero){
$valor_limpio = str_replace(',', '', $numero);
$valor_numerico = floatval($valor_limpio);
{return number_format($valor_numerico, 2, '.', '');}
}

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

function encontrar_duplicado_single($tabla, $campo, $valor, $entidad, $id_actual = 0)
{
    global $dbh;
    try {
        // Seleccionar la conexión adecuada
        $puente_sql = $dbh;

        // Sanitizar las variables $tabla y $campo
        $tabla = preg_replace('/[^a-zA-Z0-9_]/', '', $tabla);
        $campo = preg_replace('/[^a-zA-Z0-9_]/', '', $campo);

        // Preparar la consulta SQL
        $sql = "SELECT count($campo) as total FROM $tabla WHERE $campo = :valor AND entidad = :entidad AND borrado = 0";
        if($id_actual>0){
            $sql .= " AND rowid != :id_actual ";
        }
        $db = $puente_sql->prepare($sql);

        // Asignar valores a los parámetros
        $db->bindValue(':valor', $valor);
        $db->bindValue(':entidad', $entidad);
        if($id_actual>0){
            $db->bindValue(':id_actual', $id_actual, PDO::PARAM_INT);
        }
        
        // Ejecutar la consulta
        $db->execute();
        $u = $db->fetch(PDO::FETCH_ASSOC);
        
        $query1 = $sql; $query1 = str_replace(':valor', $puente_sql->quote($valor), $query1); $query1 = str_replace(':entidad', $puente_sql->quote($entidad), $query1); $query1 = str_replace(':id_actual', $id_actual, $query1);

        return ['exito' => 1, 'total' => $u['total']];

    } catch (PDOException $e) {
        // Manejo de error
        $this->error = $e->getMessage();
        $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
        $this->Error_SQL();

        return ['exito' => 0, 'error_txt' => $db->errorInfo()];
    }
}

function encontrar_duplicado_array($tabla, $campos=[], $valores=[], $entidad, $id_actual = 0)
{
    global $dbh;
    try {
        // Seleccionar la conexión adecuada
        $puente_sql = $dbh;

        // Sanitizar las variables $tabla y $campo
        $tabla = preg_replace('/[^a-zA-Z0-9_]/', '', $tabla);
        $campo = preg_replace('/[^a-zA-Z0-9_]/', '', $campos);
        foreach ($campos as $key=>$campo) {
            $campos[$key] = preg_replace('/[^a-zA-Z0-9_]/', '', $campo);
        }

        // Preparar la consulta SQL
        $sql = "SELECT count($campo) as total FROM $tabla WHERE entidad = :entidad AND borrado = 0";
        foreach ($campos as $key=>$campo) {
            $sql .= " AND $campo = :valor_$key ";
        }
        if($id_actual>0){
            $sql .= " AND rowid != :id_actual ";
        }
        $db = $puente_sql->prepare($sql);

        // Asignar valores a los parámetros
        $db->bindValue(':entidad', $entidad);
        foreach ($valores as $key=>$valor) {
            $db->bindValue(":valor_$key", $valor);
        }
        if($id_actual>0){
            $db->bindValue(':id_actual', $id_actual, PDO::PARAM_INT);
        }
        // Ejecutar la consulta
        $db->execute();
        $u = $db->fetch(PDO::FETCH_ASSOC);
        return ['exito' => 1, 'total' => $u['total'], 'sql'=> $sql];
        return ['exito' => 1, 'total' => $u['total']];

    } catch (PDOException $e) {
        // Manejo de error
        $this->error = $e->getMessage();
        $this->proceso = __FUNCTION__ . " del objeto " . __CLASS__;
        $this->Error_SQL();

        return ['exito' => 0, 'error_txt' => $db->errorInfo()];
    }
}
function encontrar_duplicado() {
    $args = func_get_args();
    
    // throw new Exception('AA'.strval(is_array('b')).'B');
    // return ['exito' => 1, 'total' => $args];
    
    $tabla = $args[0]; $campo = $args[1]; $valor = $args[2]; $entidad = $args[3]; $id_actual = $args[4];
    if (is_array($valor)) {
        return encontrar_duplicado_array($tabla, $campo, $valor, $entidad, $id_actual);
    } else{
        return encontrar_duplicado_single($tabla, $campo, $valor, $entidad, $id_actual);
    }
    
    // if (!is_array($args[1])) {
    //     $tabla = $args[0]; $campo = $args[1]; $valor = $args[2]; $entidad = $args[3]; $id_actual = $args[4];
    //     encontrar_duplicado_single($tabla, $campo, $valor, $entidad, $id_actual);
    // } elseif ( is_array($args[1])) {
    //     $tabla = array_shift($args); $entidad = array_shift($args); $campos = array_shift($args); $valores = array_shift($args); $id_actual = $args ? array_shift($args) : 0;
    //     encontrar_duplicado_array($tabla, $entidad, $campos, $valores);
    // } else {
    //     throw new InvalidArgumentException("Número de argumentos no válido.");
    // }
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