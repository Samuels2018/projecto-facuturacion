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
  include ENLACE_SERVIDOR . 'mod_parametros/object/configuracion_parametros_object.php';

  $obj = new Configuracion_parametros($dbh);

  /*

  //bloque validacion entidad
if (!empty($_POST['id'])) {
  $obj->fetch($_POST['id']);
  if ($obj->entidad != $_SESSION['Entidad']) {
    echo json_encode( ['exito' => 0, 'mensaje' => 'No tienes acceso a esta seccion']);
   
       exit(1);
  }
}
*/


  // VALID ACTION
  switch ($_POST['action']):


    case 'actualizar_configuracion':




      // validacion al editar
      $id_actual =  $_POST['id'];
      /*
        if (encontrar_duplicado('diccionario_crm_oportunidades_configuraciones', 'etiqueta', $_POST['etiqueta'], $_SESSION['Entidad'], $id_actual)['total'] > 0) {
            echo json_encode(['exito' => 0, 'mensaje' => 'Esta etiqueta ya existe']);
            exit;
        }
      */

      /************************************************************
      /*
      /*           Modificando  
      /*
      /**************************************************************/
      $obj->id = $_POST['id'];
      $obj->configuracion = $_POST['configuracion'];
      $obj->valor = $_POST['valor'];
      $obj->activo = $_POST['activo'];

      $obj->entidad = $_SESSION['Entidad'];
      $result = $obj->actualizar_configuracion();
      echo json_encode($result);

      break;



    case 'ver_configuracion':

      /************************************************************
      /*
      /*           Modificando  
      /*
      /**************************************************************/

      $result = $obj->fetch($_POST['id']);
      echo json_encode($obj);

      break;


    case 'crear_configuracion':

      //validacion de la etiqueta
      if (encontrar_duplicado('diccionario_crm_oportunidades_configuraciones', 'etiqueta', $_POST['etiqueta'], $_SESSION['Entidad'])['total'] > 0) {
        echo json_encode(['exito' => 0, 'mensaje' => 'Esta etiqueta ya existe']);
        exit;
      }

      /************************************************************
        /*
        /*           Creando  
        /*
        /**************************************************************/


      $obj->configuracion = $_POST['configuracion'];
      $obj->valor = $_POST['valor'];
      $obj->activo = $_POST['activo'];
      $obj->creado_fk_usuario = $_SESSION['usuario'];
      $obj->entidad = $_SESSION['Entidad'];

      $result = $obj->crear_configuracion();
      echo json_encode($result);

      break;


    case 'borrar_parametro':
      $obj->entidad = $_SESSION['Entidad'];
      $obj->borrado_fk_usuario = $_SESSION['usuario'];
      $result = $obj->borrar_configuracion($_POST['id']);
      echo json_encode($result);
      break;




  endswitch;
endif;
