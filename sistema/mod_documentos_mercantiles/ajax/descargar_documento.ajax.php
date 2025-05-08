<?php  

session_start();
if (empty($_SESSION['Entidad'])){ exit(1); }

require("../../conf/conf.php");

 
include_once(ENLACE_SERVIDOR . "mod_europa_facturacion/object/facturas.object.php");
include_once(ENLACE_SERVIDOR . "mod_europa_albaran_compra/object/Albaran_compra.object.php");
include_once(ENLACE_SERVIDOR . "mod_europa_albaran_venta/object/Albaran_venta.object.php");
include_once(ENLACE_SERVIDOR . "mod_europa_compra/object/compras.object.php");
include_once(ENLACE_SERVIDOR . "mod_europa_presupuestos/object/presupuestos.object.php");
include_once(ENLACE_SERVIDOR . "mod_europa_pedido/object/pedido.object.php");

include_once(ENLACE_SERVIDOR."mod_documento_pdf/object/documento_pdf.php");

$documento_id   = $_POST['documento'];
$tipo           = $_POST['tipo'];
$nombre_documento = $_POST['nombre_documento'];
$fk_plantilla = $_POST['id_plantilla'];


$Documento = new $tipo($dbh, $_SESSION['Entidad']);
$Documento->nombre_clase = $tipo;
$Documento->fetch($documento_id);

if(! ($fk_plantilla > 0) ){
     if(!$Documento->fk_plantilla > 0){
          if(! ($Documento->fk_serie_plantilla > 0) ){
               require_once ENLACE_SERVIDOR . 'mod_documento_pdf/object/plantilla.object.php';
               $Plantilla = new Plantilla($dbh, $_SESSION['Entidad']);
               $lista_plantillas = $Plantilla->obtener_plantilla_tipo_documento($Documento->documento);
               if(count($lista_plantillas)>0){
                    foreach ($lista_plantillas as $itemplantilla) {
                         if($itemplantilla["defecto"] == 1){
                              $fk_plantilla = $itemplantilla["rowid"];
                              break;
                         }
                    }
               }
          }else{
               $fk_plantilla = $Documento->fk_serie_plantilla;
          }
     }else{
          $fk_plantilla = $Documento->fk_plantilla;
     }
}

// if(! ($fk_plantilla > 0) ){
//      if(! ($Documento->fk_serie_plantilla > 0) ){
//           require_once ENLACE_SERVIDOR . 'mod_documento_pdf/object/plantilla.object.php';
//           $Plantilla = new Plantilla($dbh, $_SESSION['Entidad']);
//           $lista_plantillas = $Plantilla->obtener_plantilla_tipo_documento($Documento->documento);
//           if(count($lista_plantillas)>0){
//                foreach ($lista_plantillas as $itemplantilla) {
//                     if($itemplantilla["defecto"] == 1){
//                          $fk_plantilla = $itemplantilla["rowid"];
//                          break;
//                     }
//                }
//           }
//      }else{
//           $fk_plantilla = $Documento->fk_serie_plantilla;
//      }
// }

$Documento->registrar_log_documento($_SESSION['usuario'] , $Documento->estado , "Generando PDF" );
$DocumentoPdf = new documento_pdf($dbh, $_SESSION['Entidad']);
$DocumentoPdf->objDocumento = $Documento;

$pdf = $DocumentoPdf->genera_pdf('D', $nombre_documento, $fk_plantilla);
echo $pdf;