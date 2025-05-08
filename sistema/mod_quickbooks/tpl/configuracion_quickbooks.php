<?php
session_start();
$dbname="facturas_001";
$sql = "select sistema_empresa_licencias.rowid as 'db_id',sistema_empresa.rowid as 'empresa_id',sistema_empresa.nombre 
   from licencias.`sistema_empresa_licencias` 
   inner join sistema_empresa on sistema_empresa.fk_sistema_empresa_licencias = sistema_empresa_licencias.rowid ";
$db = $dbh_plataforma->prepare($sql);
$db->execute();
$companies=$db->fetchAll(\PDO::FETCH_ASSOC);






function agregar($dbh,$dbname,$datos){
        $sql = "select * from $dbname.`fi_configuracion` where configuracion = 'quickbooks_client_id' ";
        $db = $dbh->prepare($sql);
        $db->execute();
        $row=$db->fetch(PDO::FETCH_ASSOC);

        if (!boolval($row)){


            $sql = "INSERT INTO $dbname.`fi_configuracion`( `entidad`,`configuracion`, `valor`) 
                VALUES (:entidad,'quickbooks_client_secret',:client_secret)";
        
            $db = $dbh->prepare($sql);
            
            $db->bindValue(':entidad',  $_SESSION["Entidad"]  , PDO::PARAM_INT);
            
            $db->bindValue(':client_secret', $datos['client_secret'] , PDO::PARAM_STR);
            $db->execute();
  

            $sql = "INSERT INTO $dbname.`fi_configuracion`( `entidad`,`configuracion`, `valor`) 
                VALUES (:entidad,'quickbooks_client_id',:client_id)";
            $db = $dbh->prepare($sql);
            $db->bindValue(':entidad',  $_SESSION["Entidad"] , PDO::PARAM_INT);
            $db->bindValue(':client_id', $datos['client_id'] , PDO::PARAM_STR);
            $db->execute();
          
            $sql = "INSERT INTO $dbname.`fi_configuracion`( `entidad`,`configuracion`, `valor`) 
                VALUES (:entidad,'quickbooks_modo',:modo)";
            $db = $dbh->prepare($sql);
            $db->bindValue(':entidad',  $_SESSION["Entidad"] , PDO::PARAM_INT);
            $db->bindValue(':modo', $datos['modo'] , PDO::PARAM_STR);
            $db->execute();
  
          
        }
        else{
          
            $sql = "UPDATE $dbname.`fi_configuracion` SET valor = :client_secret where configuracion = 'quickbooks_client_secret'";
  
            $db = $dbh->prepare($sql);
            $db->bindValue(':client_secret', $datos['client_secret'] , PDO::PARAM_STR);
            $db->execute();
          
            $sql = "UPDATE $dbname.`fi_configuracion` SET valor= :client_id where configuracion = 'quickbooks_client_id'";
            $db = $dbh->prepare($sql);
            $db->bindValue(':client_id', $datos['client_id'] , PDO::PARAM_STR);
            $db->execute();
          
            $sql = "UPDATE $dbname.`fi_configuracion` SET valor= :modo where configuracion = 'quickbooks_modo'";
            $db = $dbh->prepare($sql);
            $db->bindValue(':modo', $datos['modo'] , PDO::PARAM_STR);
            $db->execute();
     
        }
        
        
       
    }
if ($_SERVER["REQUEST_METHOD"]=="POST"){
  agregar($dbh,"facturas_001",[
    "entidad"=> $_SESSION['Entidad'],  
    "client_id"=>$_POST["client_id"],
    "client_secret"=>$_POST["client_secret"],
    "modo"=>$_POST["modo"]
  ]);
}



$sql = "select * from `fi_configuracion` where configuracion = 'quickbooks_client_id' ";
$db = $dbh->prepare($sql);
$db->execute();
$row=$db->fetch(PDO::FETCH_ASSOC);

$sql = "select * from `fi_configuracion` where configuracion = 'quickbooks_modo' ";
$db = $dbh->prepare($sql);
$db->execute();
$option_modo=$db->fetch(PDO::FETCH_ASSOC);
print_r($option_modo["valor"]);
?>


<div class="middle-content container-xxl p-0">
    <!-- BREADCRUMB -->
    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Configuracion de quickbooks</a></li>
            </ol>
        </nav>
    </div>
    <!-- /BREADCRUMB -->
    <!-- CONTENT AREA -->
    <div class="row layout-top-spacing">
    <!-- Button trigger modal -->
<style type="text/css">
  body.layout-boxed:not(.dark) .img_dark {
    display: none !important;
  }
  body.layout-boxed:not(.dark) .img_nodark {
    display: block !important;
  }
  body.layout-boxed.dark .img_dark {
    display: block !important;
  }
  body.layout-boxed.dark .img_nodark {
    display: none !important;
  }
  body .form-control{
    padding: 0.75rem 1.25rem !important;
  }
</style>
<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-9">
                
                    <img src="<?php echo ENLACE_WEB; ?>/bootstrap/assets/img/Intuit_QuickBooks_logo.png" class="img_nodark" style="max-width:300px; display: none;"/>


                    <img src="<?php echo ENLACE_WEB; ?>/bootstrap/assets/img/Intuit_QuickBooks_logo_white.png" class="img_dark" style="max-width:300px; display:none; "/>

                </div>
                <div class="col-md-3">
                    <div style="margin-top: 15px;">
                        <?php include "login_quickbooks.php"; ?>
                    </div>
                </div>
            </div>
            <form action="" method="POST" class="mt-4">
            <div class="row">
                <div class="col-md-6">
                    
                    <div class="form-group">
                        <label for="modo" class="mt-2 mb-2"><i class="fas fa-cogs"></i> Modo</label>
                        <select class="form-control" id="modo" name="modo">
                            <option value="production" <?php echo $option_modo["valor"] == "production" ? "selected" : ""; ?>>Producción</option>
                            <option value="development" <?php echo $option_modo["valor"] == "development" ? "selected" : ""; ?>>Desarrollo</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                         <div id="modo-info" class="alert mt-2 mb-2" role="alert" style="margin-top: 38px !important;
    background-color: #EBEBEC;
    border: none;
    border-left: 5px solid #00AB55;
    border-radius: 0px;
    color: black;">
                            <!-- Mensaje dinámico aparecerá aquí -->
                        </div>
                </div>
            </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="client_id" class="mt-2 mb-2"><i class="fas fa-id-badge"></i> Client ID</label>
                            <input type="text" class="form-control" id="client_id" name="client_id" placeholder="Enter Client ID" value="<?php echo $row["valor"]; ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="client_secret" class="mt-2 mb-2"><i class="fas fa-key"></i> Client Secret</label>
                            <input type="password" class="form-control" id="client_secret" name="client_secret" placeholder="Enter Client Secret">
                        </div>
                    </div>
                </div>
                <button id="enviar-btn" type="submit" class="btn btn-primary mt-4"><i class="fas fa-paper-plane"></i> Enviar</button>
            </form>
        </div>
    </div>
</div>



    <!-- -->
    </div>

</div>


<script>
    jQuery(document).ready(function($) {
        function updateInfo() {
            var selectedMode = $('#modo').val();
            var infoContainer = $('#modo-info');
            if (selectedMode === 'production') {
                infoContainer
                    .removeClass('alert-warning')
                    .addClass('alert-info')
                    .html('<i class="fas fa-info-circle"></i> <strong>Producción</strong>: Para uso en el entorno real.');
            } else if (selectedMode === 'development') {
                infoContainer
                    .removeClass('alert-info')
                    .addClass('alert-warning')
                    .html('<i class="fas fa-exclamation-triangle"></i> <strong>Desarrollo</strong>: Para pruebas y desarrollo.');
            }
        }

        // Actualizar la información cuando se cambie el valor del select
        $('#modo').change(function() {
            updateInfo();
        });

        // Actualizar la información cuando la página cargue inicialmente
        updateInfo();



        
      // Desactivar todos los elementos del menú
      $(".menu").removeClass('active');

$(".configuracion").addClass('active');
$(".configuracion > .submenu").addClass('show');
$("#configuracion_quickbooks").addClass('active');

        
    });
</script>