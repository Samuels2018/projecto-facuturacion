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
  include ENLACE_SERVIDOR . 'mod_usuarios/object/usuarios.object.php';

  $obj = new usuario($dbh);


  // VALID ACTION
  switch ($_POST['action']):



    case 'actualizarUsuario':

      /************************************************************
/*
/*           Modificando un usuario 
/*
/**************************************************************/

      $obj->nombre = $_POST['nombre'];
      $obj->apellidos = $_POST['apellidos'];
      $obj->id = $_POST['id'];
      $obj->fk_idioma = $_POST['fk_idioma'];
      $obj->fk_provincia = $_POST['fk_provincia'];
      $obj->usuario_telefono = $_POST['usuario_telefono'];
      $obj->acceso_usuario = $_POST['acceso_usuario'];
      $obj->acceso_clave = $_POST['acceso_clave'];
      $obj->usuario_editar = $_POST['usuario_editar'];
      $obj->fk_perfil  = $_POST['fk_perfil'];
      $obj->entidad = $_SESSION['Entidad'];
      $obj->activo_empresa = $_POST['activo_empresa'];


      $result = $obj->ActualizarInformacionBasicaUsuario();

      echo json_encode($result);

      break;


    case 'encontrar_duplicado':

      /************************************************************
      /*
      /*           encontrar_duplicado 
      /*
      /**************************************************************/

      $total_duplicados = encontrar_duplicado($_POST['tabla'], $_POST['columna'], $_POST['valor'], $_SESSION['Entidad']);

      echo json_encode($total_duplicados);

      break;

    case 'crearUsuario':

      /************************************************************
        /*
        /*           Creando un usuario 
        /*
        /**************************************************************/


      $obj->nombre = $_POST['nombre'];
      $obj->apellidos = $_POST['apellidos'];
      $obj->acceso_usuario = $_POST['acceso_usuario'];
      $obj->fk_idioma = $_POST['fk_idioma'];
      $obj->usuario_telefono = $_POST['usuario_telefono'];
      $obj->acceso_clave = $_POST['acceso_clave'];
      $obj->fk_perfil  = $_POST['fk_perfil'];
      $obj->entidad = $_SESSION['Entidad'];
      $obj->usuario = $_SESSION['usuario'];

      $result = $obj->nuevo();
      echo json_encode($result);

      break;


    case 'eliminarUsuario':
      $obj->usuario = $_SESSION["usuario"];
      $result = $obj->activar_usuario($_POST['id'], 0);
      echo json_encode($result);
      break;

    case 'activarUsuario':
      $obj->usuario = $_SESSION["usuario"];
      $result = $obj->activar_usuario($_POST['id'], 1);
      echo json_encode($result);
      break;


    case 'validar_correo':
      $obj->email = $_POST['email'];
      $result = $obj->validar_correo();
      echo json_encode($result);
      break;

    case 'generar_correo_activacion':
      $obj->fetch($_POST['id']);
      $result = $obj->generar_correo_activacion($_POST['id'], $obj->nombre . ' ' . $obj->apellidos, $obj->email);
      echo json_encode($result);
      break;

  endswitch;
endif;
// FUNCTION VALID BILLED USER
