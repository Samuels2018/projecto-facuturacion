<?php

class Idioma {

public $diccionario = array();
public $idiomas     = array();



   public function __construct ( $dbh  ){    
                        $this->dbh = $dbh;
                        $db  = $this->dbh->prepare("select * from diccionario_idiomas ");
                        $db->execute();
                        while ($row = $db->fetch(PDO::FETCH_ASSOC)){
                            $this->idiomas[$row['rowid']]  = $row;    
                        }
    }



    public function cargar_diccionario(  $idioma = 1){


     $this->idioma = (is_null($idioma)) ? 1 : $idioma ;
     $file = ENLACE_SERVIDOR_FILES."traducciones/traducciones_".$this->idioma.".json";

     if (file_exists($file)){
     $contenido_json = file_get_contents($file);
     $decode_json= json_decode($contenido_json, true );
     $this->diccionario = ($decode_json ); //+ $this->diccionario


 
        } else { echo 'Error al cargar Idioma '. $file;   }
}



public function lang( $tag)
{    
    $texto = (!empty($this->diccionario[$tag])) ? $this->diccionario[$tag] : $tag ;
    return $texto;
    
}


}  