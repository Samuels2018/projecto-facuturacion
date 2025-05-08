<?php
include_once ENLACE_SERVIDOR . "mod_bitbucket/object/mod_bitbucket.php";

$bitbucket = new BitBucket();
$html_bitbucket = $bitbucket->fetch();

?>

<div class="middle-content container-xxl p-0">
     <!-- BREADCRUMB -->
    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo ENLACE_WEB; ?>">Inicio </a></li>
                <li class="breadcrumb-item active" aria-current="page">Log de Cambios del sistema </li>
            </ol>
        </nav>
    </div>
</div>
    <!-- /BREADCRUMB -->

<div class="row layout-top-spacing">

     <h2>Log de cambios</h2>
     <br>
     <form id="formulario">
          <?echo $html_bitbucket; ?>
     </form>

</div>