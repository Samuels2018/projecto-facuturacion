<?php
include_once(ENLACE_SERVIDOR . "mod_campos_extra_formularios/object/campos.extra.object.php");

$tabla = $Documento->documento;
$id_documento = $Documento->id;

$obj_extras = new Extra($dbh, $_SESSION["Entidad"]);
$campos_extras = $obj_extras->obtener_campos_extras($tabla);
if( $Documento->estado > 0 && count($campos_extras) == 0){
     return;
}

?>

<label class="col-sm-3 col-form-label col-form-label-sm">Campos Extras</label>
<a href="<?php echo ENLACE_WEB."factura_campos_extra/".$id_documento; ?>" class="btn btn-warning btn-icon mb-2 me-4 btn-rounded _effect--ripple waves-effect waves-light">
     <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
          <path d="M64 80c-8.8 0-16 7.2-16 16l0 320c0 8.8 7.2 16 16 16l448 0c8.8 0 16-7.2 16-16l0-320c0-8.8-7.2-16-16-16L64 80zM0 96C0 60.7 28.7 32 64 32l448 0c35.3 0 64 28.7 64 64l0 320c0 35.3-28.7 64-64 64L64 480c-35.3 0-64-28.7-64-64L0 96zm96 64a32 32 0 1 1 64 0 32 32 0 1 1 -64 0zm104 0c0-13.3 10.7-24 24-24l224 0c13.3 0 24 10.7 24 24s-10.7 24-24 24l-224 0c-13.3 0-24-10.7-24-24zm0 96c0-13.3 10.7-24 24-24l224 0c13.3 0 24 10.7 24 24s-10.7 24-24 24l-224 0c-13.3 0-24-10.7-24-24zm0 96c0-13.3 10.7-24 24-24l224 0c13.3 0 24 10.7 24 24s-10.7 24-24 24l-224 0c-13.3 0-24-10.7-24-24zm-72-64a32 32 0 1 1 0-64 32 32 0 1 1 0 64zM96 352a32 32 0 1 1 64 0 32 32 0 1 1 -64 0z" />
     </svg>
</a>