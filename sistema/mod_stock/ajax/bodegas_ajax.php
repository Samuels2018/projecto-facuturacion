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
  include ENLACE_SERVIDOR . 'mod_stock/object/bodegas.object.php';

  $obj = new Bodegas($dbh , $_SESSION['Entidad'] );
  

 
  //bloque validacion entidad
  if (!empty($_POST['id'])) {
    $obj->fetch($_POST['id']);
    if ( $obj->entidad != $_SESSION['Entidad']) {
      echo json_encode(['exito' => 0, 'mensaje' => 'No tienes acceso a esta seccion']);
      exit(1);
    }
  }



  // VALID ACTION
  switch ($_POST['action']):



    case 'ver_bodega':

      /************************************************************
      /*
      /*           Modificando  
      /*
      /**************************************************************/
       $result = $obj->fetch($_POST['id']);
      echo json_encode($obj);

      break;


    case 'crear_actualizar':

      /************************************************************
        /*
        /*           Creando  y Actualizando 
        /*
        /**************************************************************/

        $obj->id = $_POST['id'];
        $obj->label = $_POST['label'];
        $obj->nota = $_POST['nota'];
        $obj->fk_usuario = $_SESSION['usuario'];
        $obj->activo = $_POST['estado'];
        $obj->bodega_defecto = $_POST['bodega_defecto'];
  
        if(intval($obj->id)>0)
        {
          if (encontrar_duplicado('fi_bodegas', 'label', $_POST['label'], $_SESSION['Entidad'], $obj->id)['total'] > 0) {
              echo json_encode(['exito' => 0, 'mensaje' => 'Esta etiqueta ya existe']);
              exit;
          }
        }else{
          //validacion de la etiqueta
          if (encontrar_duplicado('fi_bodegas', 'label', $_POST['label'], $_SESSION['Entidad'])['total'] > 0) {
            echo json_encode(['exito' => 0, 'mensaje' => 'Esta etiqueta ya existe']);
            exit;
          }
      }

  
      if(intval($obj->id)>0)
      {
        $result = $obj->actualizar_bodega();
      }else{
        $result = $obj->crear_bodega();
      }
      
      echo json_encode($result);

    break;


    case 'borrar_bodega':
      $obj->borrado_fk_usuario = $_SESSION['usuario'];
      $result = $obj->borrar_bodega($_POST['id']);
      echo json_encode($result);
    break;




  endswitch;
endif;
