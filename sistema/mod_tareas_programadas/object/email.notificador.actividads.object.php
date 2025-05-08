<?php
//  require_once ENLACE_SERVIDOR . "global/object/log.sistema.php";
require_once ENLACE_SERVIDOR."mod_usuarios/object/usuarios.object.php";

class email_notificador_actividades extends usuario
{
    private $db;
    public $fk_entidad; //Entidad compañia
    public $nombre_fantasia; //NOMBRE COMPAÑOA


    function  __construct($dbh)
    {
        $this->db=$dbh; 
    } 


    //Listar entidades con actividades pendientes
    public function listar_entidades()
    {
        $sql="SELECT fk_entidad, nombre_fantasia FROM fi_configuracion_empresa where cron_job_correo_crm_actividades_por_vencer = 1";
        $db = $this->db->prepare($sql);
        $db->execute();
        $u = $db->fetchAll(PDO::FETCH_ASSOC);
        return $u;
    }
    
    public function listar_actividades_entidad()
    {
        $sql = "SELECT 
            foa.*, 
            dca.nombre AS actividad, 
            fo.etiqueta AS oportunidad,
            u.nombre AS cliente,
            CONCAT(ua.nombre, ' ', ua.apellidos) AS usuario_asignado,
            di.etiqueta AS estado_actividad,
            di.color AS estado_color 
        FROM 
            fi_oportunidades_actividades AS foa,
            diccionario_crm_actividades AS dca,
            fi_oportunidades AS fo,
            fi_terceros AS u,
            fi_usuarios AS ua,
            diccionario_crm_actividades_estado as di
        WHERE 
            foa.fk_diccionario_actividad = dca.rowid
            AND foa.fk_oportunidad = fo.rowid
            AND u.rowid = fo.fk_tercero
            AND ua.rowid = foa.fk_usuario_asignado
            AND di.rowid = foa.fk_estado
            AND foa.entidad = :entidad 
            AND foa.tipo = 'tarea'
            AND foa.vencimiento_fecha BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 DAY)";

        $db = $this->db->prepare($sql);
        $db->bindValue(':entidad', $this->fk_entidad, PDO::PARAM_STR);
        $db->execute();
        $u = $db->fetchAll(PDO::FETCH_ASSOC);
        return $u;
    }


    public function buscar_usuarios_por_entidad()
    {
        $array_usuarios = array();
        $sql2 = "SELECT nombre, apellidos,rowid FROM fi_usuarios u WHERE u.entidad = :entidad";
        $db2 = $this->db->prepare($sql2);
        $db2->bindValue('entidad', $this->fk_entidad, PDO::PARAM_STR);
        $db2->execute();
        $usuarios = $db2->fetchAll(PDO::FETCH_ASSOC);

        // Vamos a obtener el listado de usuarios
        $email_usuarios = '';
        foreach ($usuarios as $usuario)
        {
            //buscamos el email
            $id = $usuario['rowid'];
            $email_usuario = parent::buscar_campo_by_tabla_usuario('usuarios','acceso_usuario',$id);
            $email_usuarios.=$email_usuario.',';
        }

        $email_usuarios = substr($email_usuarios,0,-1);
        return $email_usuarios;
    }

}


?>