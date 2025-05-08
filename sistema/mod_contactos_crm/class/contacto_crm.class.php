<?php
 
 
if (!empty($_POST['action'])):
    session_start();
    include_once "../../conf/conf.php";
    include_once ENLACE_SERVIDOR . "mod_contactos_crm/object/contactos_crm.object.php";

    $contacto_crm = new TerceroCRMContacto($dbh);

    switch ($_POST['action']):

    case 'nuevo_contacto':
        $contacto_crm->nombre = $_POST['nombre'];
        $contacto_crm->apellidos = $_POST['apellidos'];
        $contacto_crm->pais_c = $_POST['pais_c'];
        $contacto_crm->puesto_t = $_POST['puesto_t'];
        $contacto_crm->email = $_POST['email'];
        $contacto_crm->telefono = $_POST['telefono'];
        $contacto_crm->facebook = $_POST['facebook'];
        $contacto_crm->linkedin = $_POST['linkedin'];
        $contacto_crm->fecha_nacimiento = $_POST['fecha_nacimiento'];
        $contacto_crm->extension = $_POST['extension'];
        $contacto_crm->whatsapp = $_POST['whatsapp'];
        $contacto_crm->instagram = $_POST['instagram'];
        $contacto_crm->x_twitter = $_POST['x_twitter'];
        $contacto_crm->creado_fecha = date('Y-m-d H:i:s');
        $contacto_crm->creado_fk_usuario = $_SESSION['usuario'];
        $contacto_crm->fk_tercero = $_POST['fk_tercero'];
        $contacto_crm->latitude = $_POST['latitude'];
        $contacto_crm->longitud = $_POST['longitud'];
        $contacto_crm->entidad = $_SESSION['Entidad'];
        $contacto_crm->paginaweb = $_POST['paginaweb'];

        $result = $contacto_crm->nuevo($contacto_crm);

        echo json_encode($result);
        break;

    case 'modificar_contacto':
        $contacto_crm->rowid = $_POST['rowid'];
        $contacto_crm->nombre = $_POST['nombre'];
        $contacto_crm->apellidos = $_POST['apellidos'];
        $contacto_crm->pais_c = $_POST['pais_c'];
        $contacto_crm->puesto_t = $_POST['puesto_t'];
        $contacto_crm->email = $_POST['email'];
        $contacto_crm->telefono = $_POST['telefono'];
        $contacto_crm->facebook = $_POST['facebook'];
        $contacto_crm->linkedin = $_POST['linkedin'];
        $contacto_crm->fecha_nacimiento = $_POST['fecha_nacimiento'];
        $contacto_crm->extension = $_POST['extension'];
        $contacto_crm->whatsapp = $_POST['whatsapp'];
        $contacto_crm->instagram = $_POST['instagram'];
        $contacto_crm->x_twitter = $_POST['x_twitter'];
        $contacto_crm->creado_fecha = $_POST['creado_fecha'];
        $contacto_crm->creado_fk_usuario = $_SESSION['usuario'];
        $contacto_crm->fk_tercero = $_POST['fk_tercero'];
        $contacto_crm->latitude = $_POST['latitude'];
        $contacto_crm->longitud = $_POST['longitud'];
        $contacto_crm->entidad = $_SESSION['Entidad'];
        $contacto_crm->paginaweb = $_POST['paginaweb'];
        
        
        $result = $contacto_crm->modificar($contacto_crm);
        echo json_encode($result);
    break;

    case 'eliminar_contacto':
        $contacto_crm->rowid = $_POST['rowid'];
        $contacto_crm->borrado_fk_usuario = $_SESSION['usuario'];

        $result = $contacto_crm->eliminar($contacto_crm->rowid);

        echo json_encode($result);
        break;

    case 'fetch_contacto':

        $result = $contacto_crm->fetch($_POST['rowid']);

        echo json_encode($result);
        break;


    default:
        echo 'Accion no definida';
        break;

    endswitch;
endif;
