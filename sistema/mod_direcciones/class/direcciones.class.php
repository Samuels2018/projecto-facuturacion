<?php

SESSION_START();
include_once("../../conf/conf.php");


  //LA ENTIDAD DEL USUARIO EN SESION
  if(empty( $_SESSION['usuario']) or empty($_SESSION['Entidad']))
  {
    echo  acceso_invalido( ) ;
    exit(1);
  }

  require_once ENLACE_SERVIDOR . 'mod_direcciones/object/Direcciones.object.php';
  $Direccion = new Direccion($dbh, $_SESSION['Entidad']);

if (!empty($_POST['action'])) :   
    switch ($_POST['action']):
        case 'BuscarDirecciones':            
            $Direccion->fk_entidad = $_REQUEST['fk_entidad'];
            $fk_direccion = $_REQUEST['selected'];
            $direcciones =  $Direccion->obtener_direcciones();
            foreach ($direcciones as $direccion) {
                $selected = (isset($fk_direccion) && intval($fk_direccion) == intval($direccion->rowid) ? 'selected':'' );
                $html .= '<option value="' . $direccion->rowid . '" '. $selected .' >' . $direccion->direccion . '</option>';
            }
            echo $html;
            break;
        case 'ActualizarFacturaDireccion':
            $Direccion->id = $_REQUEST['iddireccion'];
            $Direccion->fk_factura = $_REQUEST['idfactura'];
            $resultado = $Direccion->actualizar_factura_direccion();
            echo json_encode($resultado);
            break;
    endswitch;

endif;