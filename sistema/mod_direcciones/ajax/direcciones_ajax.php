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
  include ENLACE_SERVIDOR . 'mod_direcciones/object/Direcciones.object.php';

  $obj = new Direccion($dbh, $_SESSION['Entidad']);



  //bloque validacion entidad
  if (!empty($_POST['id'])) {
    $obj->fetch($_POST['id']);
    /*if (intval($obj->fk_entidad) != intval($_SESSION['Entidad'])) {
      echo json_encode(['exito' => 0, 'mensaje' => 'No tienes acceso a esta seccion']);
      exit(1);
    }*/
  }



  // VALID ACTION
  switch ($_POST['action']):


    case 'actualizar_direccion':




      // validacion al editar
      $id_actual =  $_POST['id'];
      /************************************************************
      /*
      /*           Modificando  
      /*
      /**************************************************************/
      $obj->id = $_POST['id'];
      $obj->descripcion = $_POST['descripcion'];
      $obj->codigo_pais = $_POST['codigo_pais'];
      $obj->codigo_postal = $_POST['codigo_postal_modal'];
      $obj->codigo_poblacion = $_POST['codigo_poblacion'];
      $obj->codigo_provincia = $_POST['codigo_provincia'];
      $obj->codigo_municipio = $_POST['codigo_municipio'];
      $obj->codigo_distrito = $_POST['codigo_distrito'];
      $obj->codigo_barrio = $_POST['codigo_barrio'];
      $obj->latitud = $_POST['latitude'];
      $obj->longitud = $_POST['longitud'];
      $obj->direccion = $_POST['direccion_modal'];
      $obj->escalera = $_POST['escalera'];
      $obj->piso = $_POST['piso'];
      $obj->puerta = $_POST['puerta'];
      $obj->otros_datos = $_POST['otros_datos'];
      $result = $obj->actualizar_direccion();
      echo json_encode($result);

      break;



    case 'ver_direccion':

      /************************************************************
      /*
      /*           Modificando  
      /*
      /**************************************************************/

      $result = $obj->fetch($_POST['id']);
      echo json_encode($obj);

      break;


    case 'crear_direccion':

      //validacion de la etiqueta
      // if (encontrar_duplicado('diccionario_direccion', 'codigo_entidad', $_POST['codigo_entidad'], $_SESSION['Entidad'])['total'] > 0) {
      //   echo json_encode(['exito' => 0, 'mensaje' => 'Esta dirección ya existe']);
      //   exit;
      // }

      /************************************************************
        /*
        /*           Creando  
        /*
        /**************************************************************/

      // $obj->codigo_entidad = $_POST['codigo_entidad'];
      $obj->creado_fk_usuario = $_SESSION['usuario'];
      $obj->fk_entidad = $_POST['fk_entidad'];
      $obj->activo = $_POST['activo'];
      $obj->tipo_entidad = $_POST['tipo_entidad'];
      $obj->descripcion = $_POST['descripcion'];
      $obj->codigo_pais = $_POST['codigo_pais'];
      $obj->codigo_postal = $_POST['codigo_postal'];
      $obj->codigo_poblacion = $_POST['codigo_poblacion'];
      $obj->codigo_provincia = $_POST['codigo_provincia'];
      $obj->codigo_municipio = $_POST['codigo_municipio'];
      $obj->codigo_distrito = $_POST['codigo_distrito'];
      $obj->codigo_barrio = $_POST['codigo_barrio'];
      $obj->escalera = $_POST['escalera'];
      $obj->piso = $_POST['piso'];
      $obj->puerta = $_POST['puerta'];
      $obj->latitud = $_POST['latitud'];
      $obj->longitud = $_POST['longitud'];
      $obj->direccion = $_POST['direccion'];
      $obj->otros_datos = $_POST['otros_datos'];

      $result = $obj->crear_direccion();
      echo json_encode($result);

      break;


    case 'borrar_direccion':
      $obj->id = $_POST['id'];
      $obj->borrado_fk_usuario = $_SESSION['usuario'];
      $result = $obj->borrar_direccion();
      echo json_encode($result);
      break;
    case 'actualizar_direccion_tipo_entidad':




      // validacion al editar
      $id_direccion =  $_POST['id'];
      $id_tipo_entidad =  $_POST['id_tipo_entidad'];
      $obj->creado_fk_usuario = $_SESSION['usuario'];
      /************************************************************
      /*
      /*           Modificando  
      /*
      /**************************************************************/
      $result = $obj->actualizar_direccion_tipo_entidad($id_direccion, $id_tipo_entidad);
      echo json_encode($result);

      break;
  
    endswitch;
endif;
