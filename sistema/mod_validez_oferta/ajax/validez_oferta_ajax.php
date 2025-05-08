<?php
// VALID DEFINITIO ACTION
if (!empty($_POST['action'])) :
  session_start();

    //si no hay usuario autenticado, cerrar conexion
    if (!isset($_SESSION['usuario'])) {
      echo acceso_invalido();
           exit(1);
    }

  include_once("../../conf/conf.php");
  include ENLACE_SERVIDOR . 'mod_validez_oferta/object/validez_oferta_object.php';

  $obj = new Validez_oferta($dbh);
  $obj->entidad = $_SESSION['Entidad'];


  //bloque validacion entidad
if (!empty($_POST['id'])) {
  $obj->fetch($_POST['id']);
  if ($obj->entidad != $_SESSION['Entidad']) {
    echo json_encode( ['exito' => 0, 'mensaje' => 'No tienes acceso a esta seccion']);
   
       exit(1);
  }
}



  // VALID ACTION
  switch ($_POST['action']):
   

    case 'guardar':

      // validacion al editar
      $id =  intval($_POST['id']);
      if(intval($id)>0)
      {
        if (encontrar_duplicado('diccionario_validez_oferta', 'label', $_POST['label'], $_SESSION['Entidad'], $id)['total'] > 0) {
            echo json_encode(['exito' => 0, 'mensaje' => 'Esta etiqueta ya existe']);
            exit;
        }
      }else{

        
        //validacion de la etiqueta
        if (encontrar_duplicado('diccionario_validez_oferta', 'label', $_POST['label'], $_SESSION['Entidad'])['total'] > 0) {
          echo json_encode(['exito' => 0, 'mensaje' => 'Esta etiqueta ya existe']);
          exit;
        }

      }
      
      $obj->id = $_POST['id'];
      $obj->label = $_POST['label'];
      $obj->activo = $_POST['estado'];
      $obj->fk_usuario = $_SESSION['usuario'];
      $obj->entidad = $_SESSION['Entidad'];
      
      if($id > 0)
      {
        $result = $obj->actualizar();
      }else{
        $result = $obj->crear();
      }
      echo json_encode($result);

    break;

    case 'ver_informacion':
      
      /************************************************************
      /*
      /*           VER INFORMACIÓN  
      /*
      /**************************************************************/
      $obj->entidad = $_SESSION['Entidad'];
      $result = $obj->fetch($_POST['id']);
      echo json_encode($obj);
    break;

    case 'borrar':
          $obj->entidad = $_SESSION['Entidad'];
          $obj->borrado_fk_usuario = $_SESSION['usuario'];
          $result = $obj->borrar($_POST['id']);
          echo json_encode($result);
    break;

  endswitch;
endif;

