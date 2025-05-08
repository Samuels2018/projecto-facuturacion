<?php
require 'vendor/autoload.php';
// require(__DIR__ ."conf_env.php");

use BaconQrCode\Renderer\RendererStyle\GradientType;
use BaconQrCode\Writer;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\Label\Font\OpenSans;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;


// if (isset($_GET['text'])) {

    // $param_url_base =   "https://prewww2.aeat.es/wlpl/TIKE-CONT/ValidarQR?"; //VERIFICABLES: Entorno de pruebas
    //$param_url_base =   "https://www2.agenciatributaria.gob.es/wlpl/TIKE-CONT/ValidarQR?"; //VERIFICABLES: Entorno de produccion

    $param_url_base = "https://prewww2.aeat.es/wlpl/TIKE-CONT/ValidarQR?";

    // $param_nif =        $_GET["param_nif"];            //89890001K
    // $param_serie =   $_GET["param_serie"];       //12345678&G33
    // $param_fecha    =   $_GET["param_fecha"];          //01-01-2024
    // $param_importe  =   $_GET["param_importe"];        //241.4


    $data = json_decode(file_get_contents('php://input'), true);
    
    $param_nif =        urldecode($data["param_nif"])?? null;            //89890001K
    $param_serie =   urldecode($data["param_serie"])?? null;       //12345678&G33
    $param_fecha    =   urldecode($data["param_fecha"])?? null;          //01-01-2024
    $param_importe  =   urldecode($data["param_importe"])?? null;        //241.4
    $param_formato  =   isset($data["param_formato"])? urldecode($data["param_formato"]):null;        //no llega nada
    $param_url_logo =        urldecode($data["param_logo"])?? "https://files-dev.avantecds.es/images/flags/1x1/spain.png";            //https://files-dev.avantecds.es/images/flags/1x1/spain.png

    $text_before = "nif=".urlencode($param_nif)."&numserie=".urlencode($param_serie)."&fecha=".urlencode($param_fecha)."&importe=".urlencode($param_importe);
    if($param_formato == 'json'){
        $text_before .= "&formato=".$param_formato;
    }
    $text = $param_url_base.$text_before;

    if($text_before == ''){
        echo 'NO SE ENVIARON DATOS';
    }

    try{
        if($param_formato == 'json'){
            echo ValidarQR($text);
        }else{
            $base64 = GeneraQR($text, $param_url_logo);
            DevolverImagen($base64);
        }


        /****************** GENERACION SENCILLA EN COLOR VERDE ******************/

        // Crear un gradiente de color para el QR: verde en la parte superior, azul en la inferior
        /*
            $foregroundColorTop = new Color(0, 128, 0);    // Verde
            $foregroundColorBottom = new Color(0, 0, 255); // Azul

            $builder = new Builder(
                writer: new PngWriter(),
                writerOptions: [],
                validateResult: false,
                data: $text,
                encoding: new Encoding('UTF-8'),
                errorCorrectionLevel: ErrorCorrectionLevel::High,
                size: 400,
                margin: 10,
                roundBlockSizeMode: RoundBlockSizeMode::Margin,
                logoPath: __DIR__.'/assets/logo_avantec.png',
                logoResizeToWidth: 100,
                logoPunchoutBackground: true,
                labelText: $text,
                labelFont: new OpenSans(20),
                labelAlignment: LabelAlignment::Center,
                foregroundColor: $foregroundColorTop
            );

            $result = $builder->build();

        
            // Enviar la imagen generada al navegador
            // header('Content-Type: ' . $result->getMimeType());
            // echo $result->getString();
            // Meterlo en un HTML

            $base64 = $result->getDataUri();
            DevolverImagen($base64);
            echo '<img src="' . $base64 . '" alt="QR Code">';
        */

        /****************** GENERACION SENCILLA EN COLOR VERDE ******************/
        
    }
    catch(Exception $ex){
        echo $ex->getMessage();
    }
// }


function GeneraQR($text, $url_logo = ''){
    /****************** GENERACION SENCILLA EN COLORES DEGRADADOS ******************/
    // $logoPath = __DIR__ . '/assets/logo_avantec.png';
    $logoPath = $url_logo;

    $builder = new Builder(
        writer : (new PngWriter()),
        writerOptions : [],
        validateResult: false,
        data : $text,
        encoding : new Encoding('UTF-8'),
        errorCorrectionLevel : ErrorCorrectionLevel::High,
        size : 400,
        margin :10,
        roundBlockSizeMode: RoundBlockSizeMode::Margin,
        foregroundColor: (new Color(0, 0, 0)), // Blanco y negro para aplicar gradiente luego
        backgroundColor: (new Color(255, 255, 255)), // Fondo blanco
        labelText: 'VERI*FACTU',
        labelTextColor: (new Color(0, 0, 0)),
        labelFont: (new OpenSans(20)),
        labelAlignment: LabelAlignment::Center,
        logoPath: $logoPath
    );

    $result = $builder->build();
    $qrImage = imagecreatefromstring($result->getString());

    // Obtener las dimensiones del QR
    $width = imagesx($qrImage);
    $height = imagesy($qrImage);

    // Crear el gradiente verde-azul
    for ($y = 0; $y < $height; $y++) {
        // Calcular el color intermedio entre verde y azul
        $green = 128 - (int)(128 * $y / $height);
        $blue = (int)(255 * $y / $height);
        $color = imagecolorallocate($qrImage, 0, $green, $blue);
        // Aplicar el color a los píxeles negros del QR
        for ($x = 0; $x < $width; $x++) {
            $rgb = imagecolorat($qrImage, $x, $y);
            $colors = imagecolorsforindex($qrImage, $rgb);
            if ($colors['red'] == 0 && $colors['green'] == 0 && $colors['blue'] == 0) { // Píxeles negros
                imagesetpixel($qrImage, $x, $y, $color);
            }
        }
    }

    // $qrCodeBase64 = base64_encode($result->getDataUri());
    // echo '<img src="' . $qrCodeBase64 . '" alt="QR Code">';
    // echo '<img src="' . $result->getDataUri() . '" alt="QR Code">';
    
    // echo $qrCodeBase64;
    // header('Content-Type: image/png');

    // Capturar la imagen en un buffer
    ob_start();
    imagepng($qrImage);
    $imageData = ob_get_contents();
    ob_end_clean();

    // Codificar la imagen en Base64
    $base64 = base64_encode($imageData);

    // Liberar la memoria de la imagen
    imagedestroy($qrImage);
    return $base64;
}

function DevolverImagen($base64){
    // echo '<img src="data:image/png;base64,' . $base64 . '" alt="QR Code">';
    echo 'data:image/png;base64,' . $base64;
    // echo $base64;
}

function ValidarQR($url){
    // URL de la API con los parámetros necesarios
    $url = $url;

    // Inicializar cURL
    $curl = curl_init();
    // Configuración de opciones de cURL
    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false, // Desactivar verificación SSL si es un entorno de desarrollo
    ]);

    // Ejecutar la solicitud cURL
    $response = curl_exec($curl);

    // Manejo de errores
    if (curl_errno($curl)) {
        echo "cURL Error: " . curl_error($curl);
    }
    // Cerrar la sesión cURL
    curl_close($curl);
    return $response;
}