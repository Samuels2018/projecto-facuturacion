<?php
class Files
{
    private $rowid;
    private $extension;
    private $categoria;
    private $activo;
    private $db;

    // Constructor que recibe la conexión a la base de datos
    public function __construct($db)
    {
        $this->db = $db;
    }

    public function obtenerExtensiones($categoria)
    {

        if (!empty($categoria)) {
            $and = " AND categoria = :categoria ";
        }

        $sql = "SELECT * FROM diccionario_extensiones_permitidas where activo = 1 " . $and;
        $db = $this->db->prepare($sql);

        if (!empty($categoria)) {
            $db->bindValue(':categoria', $categoria, PDO::PARAM_STR);
        }

        $db->execute();
        return $db->fetchAll(PDO::FETCH_OBJ);
    }

    function obtenerMensajeErrorSubida($codigoError , $type)
    {
        switch ($codigoError) {
            case 'No permitido':
                return "El tipo de archivo que intenta cargar  no es permitido.".$type;
            case 'Excede tamanio':
                return "El archivo subido excede el tamaño máximo permitido";
            default:
                return "Ocurrió un error desconocido durante la subida del archivo.";
        }
    }

   
} // Fin de la clase
