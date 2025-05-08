<?php

SESSION_START();
include_once("../../conf/conf.php");


  //LA ENTIDAD DEL USUARIO EN SESION
  if(empty( $_SESSION['usuario']) or empty($_SESSION['Entidad']))
  {
    echo  acceso_invalido( ) ;
    exit(1);
  }



  require_once(ENLACE_SERVIDOR."mod_gastos_europa/object/Gastos.object.php");
  $Cuentas  = new  Gastos($dbh, $_SESSION['Entidad']);


// VALID DEFINITIO ACTION
if (! empty($_POST['action'])):
	$name 	= $_POST['name'];
	$rowid 	= $_POST['parent'];
    
    // VALID ACTION    
    switch($_POST['action']):




        case 'editTypesExpenses':
                $Cuentas->id        = $_POST['id'];
                $Cuentas->nombre    = $_POST['name'];
                $Cuentas->fk_parent = $_POST['parent'];

                if ($Cuentas->id > 0) { 
                    echo json_encode($Cuentas->editar_cuenta());
                } else {
                    echo json_encode($Cuentas->crear_cuenta());
                }

        break; 


        case 'obtener_detalle' : 
                echo json_encode($Cuentas->fetch_cuenta($_POST['id']));
        break;


        case 'obtener_cuentas' : 
                echo json_encode($Cuentas->obtener_cuentas( $_POST['fk_parent'] ));
        break;



        case 'eliminar_tipo_gasto':
            $Cuentas->fk_gasto  = $rowid;
            //Borrar un tipode gasto
            $resultado = $Cuentas->eliminar_tipo_gasto();
            echo json_encode($resultado);
        break;    
        

        case 'ActualizarPagoGasto':
            $Cuentas->id = $_POST['id'];
            $Cuentas->fk_usuario_pagar = $_SESSION['usuario'];
            $Cuentas->fecha_pago = date('Y-m-d H:i:s');
            $resultado = $Cuentas->actualizar_pago_gasto();
            echo json_encode($resultado);
        break;


        /*******************************************
         * 
         * 
         * 
         *******************************************/
        case 'editar_gasto':

            $Cuentas->id               = $_POST['id'];
            $Cuentas->recibo_numero    = $_POST['recibo_numero'];
            $Cuentas->fecha            = $_POST['fecha'];
            $Cuentas->valor            = $_POST['valor'];
            $Cuentas->fk_gasto         = $_POST['fk_gasto'];
            $Cuentas->detalle          = $_POST['detalle'];
            $Cuentas->fk_tercero       = $_POST['fk_tercero'];
            $Cuentas->fk_proyecto       = $_POST['fk_proyecto'];
        
            // Validar si ya existe la factura con el proveedor
            
            $validacion = $Cuentas->validar_existencia_gasto($Cuentas->fk_tercero, $Cuentas->recibo_numero ,intval($Cuentas->id));
        
            if ($validacion['exito'] == 0) {
                echo json_encode($validacion); // Devuelve el mensaje de error si ya existe
                break;
            }
        
            // Procesar archivo si se ha subido
            if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] == UPLOAD_ERR_OK) {
                $file_name = $_FILES['archivo']['name'];
                $file_tmp = $_FILES['archivo']['tmp_name'];
                $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION)); // Obtener extensión en minúsculas
            
                // Validar extensiones permitidas
                $allowed_extensions = ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'];
                if (!in_array($file_ext, $allowed_extensions)) {
                    echo json_encode(['exito' => 0, 'mensaje' => 'Formato de archivo no permitido.']);
                    exit;
                }
            
                // Generar un nombre aleatorio único para el archivo
                $random_name = uniqid('recibo_', true) . '.' . $file_ext;
            
                // Definir la ruta de destino
                $destination_dir = ENLACE_FILES_EMPRESAS . 'entidad_'. $_SESSION['Entidad'].'/gastos/'; 
                
                // Verificar si la carpeta de destino existe, si no, crearla
                if (!is_dir($destination_dir)) {
                    if (!mkdir($destination_dir, 0775, true)) {
                        echo json_encode(['exito' => 0, 'mensaje' => 'Error al crear la carpeta de destino.']);
                        exit;
                    }
                }
            
                // Ruta completa del archivo
                $destination = $destination_dir . $random_name;
            
                // Intentar mover el archivo subido a la carpeta destino
                if (move_uploaded_file($file_tmp, $destination)) {
                    // Guardar la URL del recibo en la base de datos
                    $Cuentas->url_recibo = $random_name;
                } else {
                    echo json_encode(['exito' => 0, 'mensaje' => 'Error al subir el archivo. Verifique permisos y espacio en disco.']);
                    exit;
                }
            }
            
            
        
            if ($Cuentas->id > 0) { 
                echo json_encode($Cuentas->editar_gasto());
            } else {
                echo json_encode($Cuentas->crear_gasto());
            }
        
        break;
            

        case 'eliminar_gasto':

            $Cuentas->id               = $_POST['id'];
            $Cuentas->borrado = 1;
            $Cuentas->borrado_fecha = date('Y-m-d H:i:s');
            $Cuentas->borrado_fk_usuario       = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : '';

            echo json_encode($Cuentas->borrar_gasto());
         

        break;

     

    endswitch;
endif;

 


