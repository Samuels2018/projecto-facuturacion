<?php 

 

  

//----------------------------------------------------------------------------------------------------------
//
//          dbermejo@avancescr.com
//          David Bermejo
//          4001-6311
//
//----------------------------------------------------------------------------------------------------------



class Wassenger
{
private     $db;
public      $email;
public      $password;
public      $telefono ; // a quien vamos a enviar el mensaje 




  function  __construct($db){
                            $this->db       = $db; 
                            $this->email    = "api"   ;
                            $this->password = "api"          ;     
                            $this->obtener_cantidad               = 0;  // para poder partir bloques grandes 
                            $this->debug    = false                 ;  
                            $this->token    = "d81f60ca2d8d3d49c77fdd88ca1f544cc6cd51a337237d745a459d59c19da932e5d71c501d39b61d";
                            
                            $this->url      = "https://api.wassenger.com/v1/messages";

                            $this->limit    = 50000                 ;
                            $this->limit_fin = 0                    ;
            }



  //--------------------------------------------------
  //
  //  ObtenerToken
  //  dbermejo@avancescr.com 
  //  2020 
  //  Se encarga Obtener Token  
  //--------------------------------------------------          


function mensaje2($url_attach){
   // $this->telefono = '+50662240241';
    
    $header = sprintf('{
        "email"      : "%s",
        "password"   : "%s"
    }',
        $this->email,
        $this->password
    );

    $numero = $this->telefono;

    // Inicializar el array $data
    $data = array(
        "phone" => "+$numero",
        "message" => $this->mensaje
    );

    // Si $url_attach no es vacío o nulo, agregar 'media' al array $data
    if (!empty($url_attach)) {
        $data["media"] = array(
            "url" => $url_attach,
            "expiration" => "30d"
        );
    }

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $this->url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "Token: " . $this->token
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        echo $response;
    }

    $a =  json_decode($response,true);

    return $a; 
}


 function mensaje(){
     
     
    $header = sprintf('{
        "email"      : "%s",
        "password"   : "%s"
    }',
        $this->email,
        $this->password
    );

    $numero = $this->telefono;

    $data = array(
        "phone" => "+$numero",
        "message" => "Esto es una pruebaddddddddddd"
    );
    
    var_dump($data);

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $this->url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "Token: " . $this->token
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        echo $response;
    }

    $a =  json_decode($response,true);

    return $a; 
}


} // fin de la clase 
