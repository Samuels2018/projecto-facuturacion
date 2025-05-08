<?php
SESSION_START();
// USER
if (empty($_SESSION['usuario'])) {
    header("location: " . ENLACE_WEB . "inicio/");
    exit(1);
}

include_once "../../conf/conf.php";

include_once ENLACE_SERVIDOR . "mod_citas_agenda/object/citas.object.php";



$actividad = new Citas($dbh);

switch ($_POST['action']) {

    case 'listadoActividadesAgenda':

        $actividadesAgenda = $actividad->fetchActividadesAgenda($_POST, $_SESSION['usuario'],$_SESSION['Entidad'] );

        echo json_encode($actividadesAgenda);
        break;



    default:
        # code...
        break;
}

