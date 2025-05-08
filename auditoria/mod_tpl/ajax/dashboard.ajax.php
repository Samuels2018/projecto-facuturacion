<?php

if (!empty($_POST['action'])):
     session_start();

     if (!isset($_SESSION['usuario'])) {
          echo acceso_invalido();
          exit(1);
     }

     include_once("../../conf/conf.php");
     include ENLACE_SERVIDOR . 'mod_tpl/object/dashboard.object.php';

     $obj = new Dashboard($dbh, $_SESSION['Entidad']);

     switch ($_POST['action']):

          case 'devolver_datos_dashboard':
               $filtro_mes = $_POST['filtro_mes'];
               $filtro_anio = $_POST['filtro_anio'];
               $result = $obj->devolver_datos_dashboard($filtro_mes, $filtro_anio);
               echo json_encode(['exito' => 1, 'mensaje' => $result]);
               return json_encode(['exito' => 1, 'mensaje' => $result]);
               break;
          case 'devolver_datos_dashboard_fecha':
               $filtro_desde = $_POST['filtro_desde'];
               $filtro_hasta = $_POST['filtro_hasta'];
               $result = $obj->devolver_datos_dashboard_fecha($filtro_desde, $filtro_hasta);
               echo json_encode(['exito' => 1, 'mensaje' => $result]);
               return json_encode(['exito' => 1, 'mensaje' => $result]);
               break;
          case 'devolver_datos_dashboard_anio':
               $filtro_anio = $_POST['filtro_anio'];
               $result = $obj->devolver_datos_dashboard_anio($filtro_anio);
               echo json_encode(['exito' => 1, 'mensaje' => $result]);
               return json_encode(['exito' => 1, 'mensaje' => $result]);
               break;
          case 'devolver_datos_dashboard_verifactum':
               $result = $obj->devolver_datos_dashboard_verifactum();
               echo json_encode(['exito' => 1, 'mensaje' => $result]);
               return json_encode(['exito' => 1, 'mensaje' => $result]);
               break;
          case 'devolver_datos_dashboard_series':
               $filtro = $_POST['filtro']; $filtro_anio = null; $filtro_mes = null;
               if($filtro=='mensual' || $filtro == 'semanal'){
                    $filtro_anio = date("Y");
                    if($filtro == 'semanal'){
                         $filtro_mes = date('n');
                    }
               }
               $result = $obj->devolver_datos_dashboard_series($filtro, $filtro_anio, $filtro_mes);
               echo json_encode(['exito' => 1, 'mensaje' => $result]);
               return json_encode(['exito' => 1, 'mensaje' => $result]);
               break;
          case 'devolver_datos_dashboard_series_base_iva':
               $filtro = $_POST['filtro']; $filtro_anio = null; $filtro_mes = null;
               if($filtro=='mensual' || $filtro == 'semanal'){
                    $filtro_anio = date("Y");
                    if($filtro == 'semanal'){
                         $filtro_mes = date('n');
                    }
               }
               $result = $obj->devolver_datos_dashboard_series_base_iva($filtro, $filtro_anio, $filtro_mes);
               echo json_encode(['exito' => 1, 'mensaje' => $result]);
               return json_encode(['exito' => 1, 'mensaje' => $result]);
               break;
          case 'devolver_datos_dashboard_log':
               $entidad_consulta = ( count($_SESSION['multientidad'])>0 ? $_SESSION["nombre_entidad"][0]: $_SESSION["nombre_entidad"] );
               $result = $obj->consultaLogsMax(10, $entidad_consulta);
               echo json_encode(['exito' => 1, 'mensaje' => $result]);
               return json_encode(['exito' => 1, 'mensaje' => $result]);
               break;
          case 'devolver_productos_mas_vendidos':
               $result = $obj->devolver_productos_mas_vendidos(10);
               echo json_encode(['exito' => 1, 'mensaje' => $result]);
               return json_encode(['exito' => 1, 'mensaje' => $result]);
               break;
          case 'devolver_datos_dashboard_ultimas_facturas':
               $result = $obj->devolver_datos_dashboard_ultimas_facturas(10);
               echo json_encode(['exito' => 1, 'mensaje' => $result]);
               return json_encode(['exito' => 1, 'mensaje' => $result]);
               break;
          case 'devolver_datos_dashboard_estados':
               $result = $obj->devolver_datos_dashboard_estados();
               echo json_encode(['exito' => 1, 'mensaje' => $result]);
               return json_encode(['exito' => 1, 'mensaje' => $result]);
               break;
     endswitch;

endif;
