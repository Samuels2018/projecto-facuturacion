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
  include ENLACE_SERVIDOR . 'mod_tipo_actividad/object/tipo_actividad.object.php';

  $obj = new TipoActividad($dbh, $_SESSION['Entidad']);

  //bloque validacion entidad
  if (!empty($_POST['id'])) {
    $obj->fetch($_POST['id']);
    if ($obj->entidad != $_SESSION['Entidad']) {
      echo json_encode(['exito' => 0, 'mensaje' => 'No tienes acceso a esta seccion']);
      exit(1);
    }
  }

  // VALID ACTION
  switch ($_POST['action']):

    case 'ver_tipo_actividad':

      /************************************************************
      /*           Modificando  
      /**************************************************************/
      $result = $obj->fetch($_POST['id']);
      echo json_encode($obj);
      break;
    case 'crear_tipo_actividad':
      /************************************************************
      /*           Creando  y Actualizando 
      /**************************************************************/
      $obj->id = $_POST['id'];
      $obj->nombre = $_POST['nombre'];
      $obj->color = $_POST['color'];
      $obj->icono = $_POST['icono'];
      $obj->activo = $_POST['activo'];
      $obj->fk_usuario = $_SESSION['usuario'];

      if (intval($obj->id) > 0) {
        $encontrado = encontrar_duplicado('diccionario_crm_actividades', 'nombre', $_POST['nombre'], $_SESSION['Entidad'], intval($obj->id));
        if ($encontrado['total'] > 0 ) {
          echo json_encode(['exito' => 0, 'mensaje' => 'Este tipo de actividad ya existe']);
          exit;
        }
      }

      if (intval($obj->id) > 0) {
        $result = $obj->actualizar();
      } else {
        $result = $obj->crear();
      }
      echo json_encode($result);
      break;
    case 'borrar_tipo_actividad':
      $obj->borrado_fk_usuario = $_SESSION['usuario'];
      $result = $obj->eliminar($_POST['id']);
      echo json_encode($result);
      break;
  endswitch;
endif;
