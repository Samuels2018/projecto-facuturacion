<?php

switch ($_GET['accion']) {

    case '':                    $tpl = "mod_tpl/tpl/dashboard.vendedor.php";         break;
    case 'usuarios_listado':    $tpl = "mod_catalogo/tpl/catalogo_listado.php";      break;
    case 'usuario_detalle' :    $tpl = "mod_catalogo/tpl/catalogo_listado.php";      break;

    // Clonar Mi perfil
    case 'mi_perfil'       :    $tpl = "mod_catalogo/tpl/catalogo_listado.php";      break;
    case 'kit_digital_listado'     :    $tpl = "mod_kit_digital/tpl/kit_digital_listado.php";      break;
    case 'nuevo_kit_digital'     :    $tpl = "mod_kit_digital/tpl/nuevo_kit_digital.php";      break;
    case 'editar_kit_digital':  $tpl = "mod_kit_digital/tpl/nuevo_kit_digital.php";    break;

    default:    
    $tpl = "mod_tpl/tpl/plantilla.php";
        break;

}



 
