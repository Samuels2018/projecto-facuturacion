<?php
SESSION_START();
// USER
if (empty($_SESSION['usuario'])) {
    header("location: " . ENLACE_WEB . "inicio/");
    exit(1);
}

include_once "../../conf/conf.php";

include_once ENLACE_SERVIDOR . "mod_crm_agenda/object/actividades.object.php";



$actividad = new Actividades_($dbh);

switch ($_POST['action']) {

    case 'listadoActividadesAgenda':

        $actividadesAgenda = $actividad->fetchActividadesAgenda($_POST, $_SESSION['usuario'],$_SESSION['Entidad'] );

        echo json_encode($actividadesAgenda);
        break;
    case 'actualizarTarea':
        include_once ENLACE_SERVIDOR . "mod_crm_actividades/object/actividades.object.php";
        $actividad_new = new Actividades($dbh);
        $actividad_new->rowid               =$_POST['rowid'];
        $actividad_new->fk_estado           =$_POST['fk_estado'];
        $actividad_new->comentario          =$_POST['comentario'];
        $actividad_new->comentario_cierre   =$_POST['comentario_cierre'];
        echo json_encode($actividad_new->actualizarActividad());

        break;


    default:
        # code...
        break;
}