<?php

include_once(ENLACE_SERVIDOR . "mod_seguridad/object/seguridad.object.php");
class TerceroCRMContacto extends Seguridad
{
    private $db;
    public $rowid;
    public $fk_tercero;
    public $nombre;
    public $apellidos;
    public $pais_c;
    public $puesto_t;
    public $email;
    public $telefono;
    public $facebook;
    public $linkedin;
    public $fecha_nacimiento;
    public $extension;
    public $whatsapp;
    public $instagram;
    public $x_twitter;
    public $creado_fecha;
    public $creado_fk_usuario;
    public $borrado;
    public $borrado_fecha;
    public $borrado_fk_usuario;
    public $latitude;
    public $longitud;
    public $entidad;

    public function __construct($db)
    {
        $this->db = $db;
        parent::__construct();
    }

    public function fetch($id)
    {
        $query = "SELECT ct.*,
            concat(ft.nombre, ' ', ft.apellidos) as nombre_cliente
            FROM fi_terceros_crm_contactos ct 
            LEFT JOIN fi_usuarios fu ON fu.rowid = ct.creado_fk_usuario 
            LEFT JOIN fi_terceros ft ON ft.rowid = ct.fk_tercero 
            
            WHERE ct.rowid = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->rowid = $row['rowid'];
        $this->nombre = $row['nombre'];
        $this->apellidos = $row['apellidos'];
        $this->pais_c = $row['pais_c'];
        $this->puesto_t = $row['puesto_t'];
        $this->email = $row['email'];
        $this->telefono = $row['telefono'];
        $this->facebook = $row['facebook'];
        $this->linkedin = $row['linkedin'];
        $this->fecha_nacimiento = $row['fecha_nacimiento'];
        $this->extension = $row['extension'];
        $this->whatsapp = $row['whatsapp'];
        $this->instagram = $row['instagram'];
        $this->x_twitter = $row['x_twitter'];
        $this->creado_fk_usuario = $row['creado_fk_usuario'];
        $this->creado_fecha = $row['creado_fecha'];
        $this->fk_tercero = $row['fk_tercero'];
        $this->nombre_cliente = $row['nombre_cliente'];
        $this->latitude = $row['latitude'];
        $this->longitud = $row['longitud'];
        $this->paginaweb = $row['paginaweb'];
        return $this;
    }


    public function nuevo($datos)
    {
        $sql = "INSERT INTO fi_terceros_crm_contactos (
                    fk_tercero,
                    nombre,
                    apellidos,
                    pais_c,
                    puesto_t,
                    email,
                    telefono,
                    facebook,
                    linkedin,
                    fecha_nacimiento,
                    extension,
                    whatsapp,
                    instagram,
                    x_twitter,
                    creado_fecha,
                    creado_fk_usuario,
                    latitude,
                    longitud,
                    entidad,
                    paginaweb
                  ) VALUES (
                    :fk_tercero,
                    :nombre,
                    :apellidos,
                    :pais_c,
                    :puesto_t,
                    :email,
                    :telefono,
                    :facebook,
                    :linkedin,
                    :fecha_nacimiento,
                    :extension,
                    :whatsapp,
                    :instagram,
                    :x_twitter,
                    now(),
                    :creado_fk_usuario,
                    :latitude,
                    :longitud,
                    :entidad,
                    :paginaweb
                  )";

        $dbh = $this->db->prepare($sql);

        $datos->fecha_nacimiento = !empty($datos->fecha_nacimiento) ? $datos->fecha_nacimiento : null;

        $dbh->bindValue(':fk_tercero', $datos->fk_tercero, PDO::PARAM_INT);
        $dbh->bindValue(':nombre', $datos->nombre, PDO::PARAM_STR);
        $dbh->bindValue(':apellidos', $datos->apellidos, PDO::PARAM_STR);
        $dbh->bindValue(':pais_c', $datos->pais_c, PDO::PARAM_STR);
        $dbh->bindValue(':puesto_t', $datos->puesto_t, PDO::PARAM_STR);
        $dbh->bindValue(':email', $datos->email, PDO::PARAM_STR);
        $dbh->bindValue(':telefono', $datos->telefono, PDO::PARAM_STR);
        $dbh->bindValue(':facebook', $datos->facebook, PDO::PARAM_STR);
        $dbh->bindValue(':linkedin', $datos->linkedin, PDO::PARAM_STR);
        $dbh->bindValue(':fecha_nacimiento', $datos->fecha_nacimiento, PDO::PARAM_STR);
        $dbh->bindValue(':extension', $datos->extension, PDO::PARAM_STR);
        $dbh->bindValue(':whatsapp', $datos->whatsapp, PDO::PARAM_STR);
        $dbh->bindValue(':instagram', $datos->instagram, PDO::PARAM_STR);
        $dbh->bindValue(':x_twitter', $datos->x_twitter, PDO::PARAM_STR);
        $dbh->bindValue(':creado_fk_usuario', $datos->creado_fk_usuario, PDO::PARAM_INT);
        $dbh->bindValue(':latitude', $datos->latitude, PDO::PARAM_STR);
        $dbh->bindValue(':longitud', $datos->longitud, PDO::PARAM_STR);
        $dbh->bindValue(':entidad', $datos->entidad,PDO::PARAM_INT);
        $dbh->bindValue(':paginaweb', $datos->paginaweb,PDO::PARAM_STR);

        $a = $dbh->execute();

        if ($a) {
            $resultado['id']      =   $this->db->lastInsertId();
            $resultado['exito']   =   true;
            $resultado['mensaje'] =   "Contacto creado con éxito";
        } else {
            $resultado['exito'] = 0;
            $resultado['mensaje'] = implode(", ", $dbh->errorInfo());
            $this->sql     =   $sql;
            $this->error   =   implode(", ", $dbh->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
            $this->Error_SQL();
        }

        return $resultado;
    }

    public function modificar($datos)
    {

        $sql = "UPDATE fi_terceros_crm_contactos SET
                    fk_tercero = :fk_tercero,
                    nombre = :nombre,
                    apellidos = :apellidos,
                    pais_c = :pais_c,
                    puesto_t = :puesto_t,
                    email = :email,
                    telefono = :telefono,
                    facebook = :facebook,
                    linkedin = :linkedin,
                    fecha_nacimiento = :fecha_nacimiento,
                    extension = :extension,
                    whatsapp = :whatsapp,
                    instagram = :instagram,
                    x_twitter = :x_twitter,
                    latitude = :latitude,
                    longitud = :longitud,
                    entidad = :entidad,
                    paginaweb = :paginaweb
                WHERE rowid = :rowid";

        $dbh = $this->db->prepare($sql);

        $dbh->bindValue(':rowid', $datos->rowid, PDO::PARAM_INT);
        $dbh->bindValue(':fk_tercero', $datos->fk_tercero, PDO::PARAM_INT);
        $dbh->bindValue(':nombre', $datos->nombre, PDO::PARAM_STR);
        $dbh->bindValue(':apellidos', $datos->apellidos, PDO::PARAM_STR);
        $dbh->bindValue(':pais_c', $datos->pais_c, PDO::PARAM_STR);
        $dbh->bindValue(':puesto_t', $datos->puesto_t, PDO::PARAM_STR);
        $dbh->bindValue(':email', $datos->email, PDO::PARAM_STR);
        $dbh->bindValue(':telefono', $datos->telefono, PDO::PARAM_STR);
        $dbh->bindValue(':facebook', $datos->facebook, PDO::PARAM_STR);
        $dbh->bindValue(':linkedin', $datos->linkedin, PDO::PARAM_STR);
        $dbh->bindValue(':fecha_nacimiento', $datos->fecha_nacimiento ? $datos->fecha_nacimiento: null , PDO::PARAM_STR);
        $dbh->bindValue(':extension', $datos->extension, PDO::PARAM_STR);
        $dbh->bindValue(':whatsapp', $datos->whatsapp, PDO::PARAM_STR);
        $dbh->bindValue(':instagram', $datos->instagram, PDO::PARAM_STR);
        $dbh->bindValue(':x_twitter', $datos->x_twitter, PDO::PARAM_STR);
        $dbh->bindValue(':latitude', $datos->latitude !== ''? $datos->latitude: null, PDO::PARAM_STR);
        $dbh->bindValue(':longitud', $datos->longitud !== ''? $datos->longitud: null, PDO::PARAM_STR);
        $dbh->bindValue(':entidad', $datos->entidad, PDO::PARAM_INT);
        $dbh->bindValue(':paginaweb', $datos->paginaweb, PDO::PARAM_STR);

        $a = $dbh->execute();

        if ($a) {
            $resultado['exito']   =   true;
            $resultado['mensaje'] =   "Contacto actualizado con éxito";
        } else {
            $resultado['exito'] = 0;
            $resultado['mensaje'] = implode(", ", $dbh->errorInfo());
            $this->sql     =   $sql;
            $this->error   =   implode(", ", $dbh->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
            $this->Error_SQL();
        }

        return $resultado;
    }


    public function eliminar($rowid)
    {
        $sql = "UPDATE fi_terceros_crm_contactos SET
                    borrado = 1,
                    borrado_fecha = now(),
                    borrado_fk_usuario = :borrado_fk_usuario
                WHERE rowid = :rowid";

        $dbh = $this->db->prepare($sql);

        $dbh->bindValue(':rowid', $rowid, PDO::PARAM_INT);
        $dbh->bindValue(':borrado_fk_usuario', $this->creado_fk_usuario, PDO::PARAM_INT);

        $a = $dbh->execute();

        if ($a) {
            $resultado['exito']   =   $a;
            $resultado['mensaje'] =   "Contacto eliminado con éxito";
        } else {
            $resultado['exito'] = 0;
            $resultado['mensaje'] = implode(", ", $dbh->errorInfo());
            $this->sql     =   $sql;
            $this->error   =   implode(", ", $dbh->errorInfo());
            $this->proceso = __FUNCTION__ . " Del Objeto " . __CLASS__;
            $this->Error_SQL();
        }

        return $resultado;
    }

    public function obtener_contactos($fk_tercero)
    {

        $sql = "SELECT * FROM fi_terceros_crm_contactos WHERE fk_tercero = ? AND borrado = 0";
        $dbh = $this->db->prepare($sql);

        $dbh->bindValue(':fk_tercero', $fk_tercero, PDO::PARAM_INT);
        $dbh->execute();
        $contactos = $dbh->fetchAll(PDO::FETCH_OBJ);
        
        return $contactos;

    }
}
