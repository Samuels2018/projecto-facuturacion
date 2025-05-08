<?php


session_start();

//si no hay usuario autenticado, cerrar conexion
if (!isset($_SESSION['usuario'])) {
     echo acceso_invalido();
     exit(1);
}


include_once("../../conf/conf.php");
include ENLACE_SERVIDOR . 'mod_documento_pdf/object/plantilla.object.php';

$obj = new Plantilla($dbh, $_SESSION['Entidad']);
$mensaje = array();
$resultado = null;
switch ($_POST["action"]) {
     case 'crear_plantilla':
          if(empty($_POST['id'])){
               $obj->titulo                  = $_POST['titulo'];
               $obj->orden             = $_POST['orden'];
               $obj->plantilla_html   = $_POST['plantilla_html'];
               $obj->plantilla_css    = $_POST['plantilla_css'];
               $obj->defecto       = $_POST['defecto'];
               $obj->creado_fk_usuario     = $_SESSION['usuario'];
               $obj->tipo     = $_POST['tipo'];
               $obj->titulo     = $_POST['titulo'];
               $obj->entidad               = $_SESSION['Entidad'];
               $result = $obj->crear_plantilla();
               $resultado = json_encode($result);
          }
          break;
     case 'actualizar_plantilla':
          if(!empty($_POST['id'])){
               $obj->id                    = $_POST['id'];
               $obj->titulo                  = $_POST['titulo'];
               $obj->orden             = $_POST['orden'];
               $obj->defecto       = $_POST['defecto'];
               $obj->plantilla_html   = $_POST['plantilla_html'];
               $obj->plantilla_css    = $_POST['plantilla_css'];
               $obj->entidad               = $_SESSION['Entidad'];
               $obj->tipo     = $_POST['tipo'];
               $obj->activo       = $_POST['activo'];
     
               $result = $obj->actualizar_plantilla();
               $resultado = json_encode($result);
          }
          break;
     case 'borrar_plantilla':
          if(!empty($_POST['id'])){
               $obj->entidad            = $_SESSION['Entidad'];
               $obj->borrado_fk_usuario = $_SESSION['usuario'];
               $result = $obj->borrar_plantilla($_POST['id']);
               $resultado = json_encode($result);
          }
          break;
     case 'duplicar_plantilla':
          if(!empty($_POST['id'])){
               $obj->entidad            = $_SESSION['Entidad'];
               $obj->creado_fk_usuario = $_SESSION['usuario'];
               $result = $obj->duplicar_plantilla($_POST['id']);
               $resultado = json_encode($result);
          }
          break;
     case 'preview_pdf_plantilla':
          include ENLACE_SERVIDOR . 'mod_documento_pdf/object/documento_pdf.php';
          if(!empty($_POST['id'])){
               $DocumentoPdf = new documento_pdf($dbh, $_SESSION["Entidad"]);
               $contenido_pdf = $DocumentoPdf->genera_preview_plantilla("S", "plantilla.pdf", $_POST['id']);
               $contenido_base64 = base64_encode($contenido_pdf);
               $resultado = $contenido_base64;
          }
          break;          
     case 'plantilla_tipo_documento':
          if(!empty($_POST['tipo'])){
               $result = $obj->obtener_plantilla_tipo_documento($_POST['tipo']);
               $resultado = json_encode($result);
          }
          break;
     default:
          $result['exito'] = 0;
          $result['mensaje'] = "No se encontro la Accion";
          $resultado = json_encode($result);
          break;
}

echo $resultado;