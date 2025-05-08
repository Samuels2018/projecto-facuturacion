<?php 

class regimen_iva extends Seguridad
{	

	// Propiedades privadas
    private $db;
    public function __construct($db)
    {

        $this->db = $db;
        parent::__construct();
    }

    //Listar todos los regimenes de iva registrados
    public function listar_regimen_iva()
    {	
    	$sql = "SELECT *  FROM diccionario_regimen_iva  where activo = 1 order by etiqueta ASC  ";
        $db = $this->db->prepare($sql);
		$db->execute();
		$result = $db->fetchAll();
        return $result;
    }


    //Listar los tipos de retenciones
    public function listar_tipos_retencion()
    {	
    	$sql = "SELECT *  FROM diccionario_regimen_iva_tipos_retencion  order by etiqueta ASC  ";
        $db = $this->db->prepare($sql);
		$db->execute();
		$result = $db->fetchAll();
        return $result;
    }


}