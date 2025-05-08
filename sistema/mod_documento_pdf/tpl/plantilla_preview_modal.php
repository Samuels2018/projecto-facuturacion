<?php
session_start();

//si no hay usuario autenticado, cerrar conexion
if (!isset($_SESSION['usuario'])) {
     echo acceso_invalido();
     exit(1);
}


include_once("../../conf/conf.php");
include_once ENLACE_SERVIDOR . 'mod_documento_pdf/object/plantilla.object.php';
$obj = new Plantilla($dbh, $_SESSION['Entidad']);

if (!empty($_REQUEST['fiche'])) {
     $obj->fetch($_REQUEST['fiche']);
     
     include ENLACE_SERVIDOR . 'mod_documento_pdf/object/documento_pdf.php';

     $DocumentoPdf = new documento_pdf($dbh, $_SESSION["Entidad"]);
     $contenido_pdf = $DocumentoPdf->genera_preview_plantilla("S", "plantilla.pdf", $_REQUEST['fiche']);
     $contenido_base64 = base64_encode($contenido_pdf);
     $resultado = $contenido_base64;
     
}


?>
<style>
     .header_style_document {
          position: relative !important;
          top: 112 !important;
          left: -15 !important;
          rotate: -90 !important;
          text-align: left !important;
          width: 100% !important;
          font-size: 12px !important;
          border: 1px solid !important;
          padding: 5px !important;
          margin-left: -30px !important;
     }
</style>
<style id="preview_css">
     <?php echo $obj->plantilla_css; ?>
</style>

<div class="modal-dialog modal-xl" role="document">
     <div class="modal-content">

          <div class="modal-header">
               <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-list"></i> Previsualización de Plantilla </h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

               </button>
          </div>
          <div class="modal-body">
               <div class="card-body">

                    <ul class="nav nav-pills" id="animateLine" role="tablist">
                         <li class="nav-item" role="presentation">
                              <button class="nav-link active" id="animated-html-tab" data-bs-toggle="tab" href="#animated-html" role="tab" aria-controls="animated-html" aria-selected="true" >
                              Vista HTML Preview</button>
                         </li>
                         <li class="nav-item" role="presentation">
                              <button class="nav-link" id="animated-pdf-tab" data-bs-toggle="tab" href="#animated-pdf" role="tab" aria-controls="animated-pdf" aria-selected="false" tabindex="-1" >
                              Vista PDF Preview</button>
                         </li>
                    </ul>
                    <div class="tab-content" id="animateLineContent-4">
                         <div class="tab-pane fade active show" id="animated-html" role="tabpanel" aria-labelledby="animated-html-tab">
                              <div class="row mt-3">
                                   <?php echo html_entity_decode($obj->plantilla_html); ?>
                              </div>
                         </div>
                         <div class="tab-pane fade" id="animated-pdf" role="tabpanel" aria-labelledby="animated-pdf-tab">
                              <div class="row mt-3">
                                   <embed src="data:application/pdf;base64,<?php echo $resultado; ?>" type="application/pdf" width="100%" height="600px" >
                              </div>
                         </div>
                    </div>
               </div>
          </div>
          <div class="modal-footer">
               <button class="btn btn btn-light-dark" data-bs-dismiss="modal"><i class="flaticon-cancel-12"></i>Cancelar</button>
          </div>

     </div>
</div>