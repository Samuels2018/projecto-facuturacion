<?php
session_start();

// Validación de sesión
if (!isset($_SESSION['usuario'])) {
    echo json_encode(['error' => 1 , "mensaje" => 'Acceso no válido']);
    exit;
}

    require_once "../../conf/conf.php";
    include_once(ENLACE_SERVIDOR . "mod_europa_albaran_compra/object/Albaran_compra.object.php");
    include_once(ENLACE_SERVIDOR . "mod_europa_compra/object/compras.object.php");


    $Documento = new Albaran_compra($dbh, $_SESSION['Entidad']);
 
    

 
    

 
      
          switch($_POST['accion']){
      
              case 'ligar_documento':

            //                         $Documento->fetch($_POST['documento']);

            //                         $Documento_compra = new Compra ($dbh, $_SESSION['Entidad']);
            // $dataDocumentoClonado  = $Documento_compra->clonar_documento( $Documento->id, $_SESSION['usuario'] , $Documento->documento , $Documento->documento_detalle ) ;
            //                         $Documento_compra->fetch($dataDocumentoClonado["id"]);

            //                         $return = $Documento->ligar_documento( $Documento_compra , $_SESSION['usuario'] ); 
            //                                   $Documento->cambiar_estado(3);



                    $Documento->fetch($_POST['documento']);

                    $Documento_compra = new Compra ($dbh, $_SESSION['Entidad']);

                    $dataDocumentoClonado  = $Documento_compra->clonar_documento( $Documento->id, $_SESSION['usuario'] , $Documento->documento , $Documento->documento_detalle ) ;
                    $Documento_compra->movimiento_origen = 'fi_europa_compras';

                    $Documento_compra->clonar_documento_detalle($dataDocumentoClonado['id'], $_POST['documento'], 
                                    $dataDocumentoClonado['nombre_documento_detalle_base'], 
                                    $dataDocumentoClonado['origen_documento_inicio'], 
                                    $dataDocumentoClonado['origen_fk_documento_inicio'], 
                                    $dataDocumentoClonado['origen_documento_fin'], 
                                    $dataDocumentoClonado['origen_fk_documento_fin'], false);
                    $Documento_compra->fetch($dataDocumentoClonado['id']);
                
                    $dataDocumentoLigado = $Documento->ligar_documento( $Documento_compra , $_SESSION['usuario'] ); 
        
                    $Documento->ligar_documento_detalle($dataDocumentoLigado["documento_movimiento_id"],$Documento_compra);
        
                    $Documento->cambiar_estado(3); // Estado Completa                                              


                    echo json_encode($dataDocumentoLigado);
              break;

              case 'anular_albaran_compra':
                    
                    require_once ENLACE_SERVIDOR . 'mod_stock/object/bodegas.object.php';
                    
                    $motivoCodificado  = urldecode($_POST['motivo']);
                    $motivoDecodificado = urldecode($motivoCodificado);
                    $motivoSanitizado = htmlspecialchars($motivoDecodificado, ENT_QUOTES, 'UTF-8');

                    $documento  = urldecode($_POST['documento']);
                 
                    $Documento->fetch($documento);

                    $paso_validacion = true;
                    foreach ($Documento->movimiento_destinos as $key => $value) {
                        if(intval($value->estado) == 0){
                            $paso_validacion = false;
                        }
                    }

                    if($paso_validacion){
                        $Documento->cambiar_estado(6);
                        $Bodega = new Bodegas($dbh, $_SESSION['Entidad']);
                        $Bodega->usuario = $_SESSION["usuario"];
                        $Bodega->documento_tipo = "albaran_compra";
                        $Bodega->motivo         = $motivoSanitizado;
                        $Bodega->recuperar_bodega_en_compra($Documento, $_SESSION['usuario'], 'disminuir');
                        $Documento->registrar_log_documento($_SESSION['usuario'], 1, "Documento Anulado Dentro del Trimestre Activo");
    
                        $mensaje['mensaje'] = "Albaran anulado correctamente";
                        $mensaje['exito'] = 1;
                    }else{
                        $mensaje['mensaje'] = "No puede anular hasta que todas las compras generadas estén fiscalizados";
                        $mensaje['exito'] = 0;
                    }
                    echo json_encode($mensaje);

                break;

                default: echo  json_encode(['exito' => 0 , "mensaje" => 'Accion No valida' ]); break; 


            }

      