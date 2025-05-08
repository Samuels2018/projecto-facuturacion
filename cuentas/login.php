<?php 

session_start();
include_once("conf/conf.php");
require_once ENLACE_SERVIDOR . "mod_usuarios/object/usuarios.object.php";



if (!empty($_POST['usuario']) && !empty($_POST['pass'])) {


$sql = "

SELECT
    u.rowid AS id,
    u.acceso_clave,
    u.usuario_avatar,
    u.nombre AS usuario_txt ,
    de.logeable , 
    de.etiqueta as logeo_txt

FROM
    usuarios u
LEFT JOIN diccionario_usuarios_estado de ON
    de.rowid = u.fk_estado

WHERE
    u.acceso_usuario = :usuario
     ";


$db = $dbh->prepare($sql);
$db->bindValue(':usuario', $_POST['usuario'], PDO::PARAM_STR);

$db->execute();
$obj = $db->fetch(PDO::FETCH_ASSOC);

 

if (empty($obj['id'])){

    $resultado['txt']   = "Usuario No Encontrado";
    $resultado['logear']= 0;
} else if (!empty($obj['id']) and $_POST['pass'] !== $obj['acceso_clave']){
    $resultado['txt']   = "Clave Incorrecta";
    $resultado['logear']= 0;
} else if (!empty($obj['id']) and $_POST['pass'] == $obj['acceso_clave'] and  0  == $obj['logeable']  )  {
    $resultado['txt']   =  $obj['logeo_txt'];
    $resultado['logear']=   0;
} else if (!empty($obj['id']) and $_POST['pass'] == $obj['acceso_clave'] and  $obj['logeable'] == 1 )  {

    $resultado['txt']   =  "Exito";
    $resultado['logear']= 1;


        $_SESSION['usuario_cuentas']            =   $obj['id'];
        header('location: '.ENLACE_WEB_CUENTAS);
        exit(1);
    }

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Facturacion Electronica | Avantec.DS SL </title>

    <link rel="icon" type="image/x-icon" href="<?php echo ENLACE_WEB; ?>bootstrap/assets/img/favicon.ico"/>
    <link href="<?php echo ENLACE_WEB; ?>bootstrap/css/light/loader.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo ENLACE_WEB; ?>bootstrap/css/dark/loader.css" rel="stylesheet" type="text/css" />
    <script src="<?php echo ENLACE_WEB; ?>bootstrap/loader.js"></script>
    
    
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="<?php echo ENLACE_WEB; ?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo ENLACE_WEB; ?>bootstrap/css/light/plugins.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo ENLACE_WEB; ?>bootstrap/css/dark/plugins.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    <link rel="stylesheet" type="text/css" href="<?php echo ENLACE_WEB; ?>bootstrap/assets/css/light/elements/alert.css">
    <link rel="stylesheet" type="text/css" href="<?php echo ENLACE_WEB; ?>bootstrap/assets/css/dark/elements/alert.css">

    <script src="<?php echo ENLACE_WEB; ?>bootstrap/plugins/src/notification/snackbar/snackbar.min.js"></script>

    <script>var ENLACE_WEB = "<?php echo ENLACE_WEB; ?>"; </script>

          <!-- toastr -->
          <link href="<?php echo ENLACE_WEB; ?>bootstrap/plugins/src/notification/snackbar/snackbar.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo ENLACE_WEB; ?>bootstrap/plugins/css/light/notification/snackbar/custom-snackbar.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo ENLACE_WEB; ?>bootstrap/plugins/css/dark/notification/snackbar/custom-snackbar.css" rel="stylesheet" type="text/css" />


</head>
<body class="form">

    <!-- BEGIN LOADER -->
    <div id="load_screen"> <div class="loader"> <div class="loader-content">
        <div class="spinner-grow align-self-center"></div>
    </div></div></div>
    <!--  END LOADER -->

    <div class="auth-container d-flex">
        <div class="container mx-auto align-self-center">
            <div class="row">
                <form action="" method="POST">
                <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-8 col-12 d-flex flex-column align-self-center mx-auto">
                    <div class="card mt-3 mb-3">
                        <div class="card-body">
    
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                  <?php 
                                if(isset($_SESSION['multientidad']) && count($_SESSION['multientidad'])>0)
                                {
                                ?>
                                    <h2> Facturaci&oacute;n Electronica </h2>
                                    <p>Accede a la compañia de tu preferiencia</p>
                                <?php 
                                }else{
                                ?>
                                

                                    <h2> Facturaci&oacute;n Electronica </h2>
                                    <p>Accesa tu Email para Ingresar</p>
                                
                                <?php 
                                    } 
                                ?>

                                </div>
                    <?php if ( isset($resultado['txt']) ){  ?>
                            <div class="col-md-12">
                                <div class="alert alert-arrow-right alert-icon-right alert-light-success alert-dismissible fade show col-md-12" role="alert">
                                        <?php echo  $resultado['txt']; ?>
                                </div>   
                            </div>
                    <?php } ?>

                            <?php 
                                if(isset($_SESSION['multientidad']) && count($_SESSION['multientidad'])>0)
                                {
                                    $multientidad = $_SESSION['multientidad'];
                                    $multiusuario = $_SESSION['multiusuario'];
                                    $nombre_entidad = $_SESSION['nombre_entidad'];
                        ?>

                            <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Seleccione la Entidad a usar</label>
                                <input type="hidden" name="select_usuario" id="select_usuario">
                                <select name="select_entidad" id="select_entidad" class="form-control">
                                    <?php 
                                        foreach($multientidad as $key => $value){
                                    ?>
                                        <option usuario="<?php echo $multiusuario[$key]; ?>" value="<?php echo $multientidad[$key]; ?>"><?php echo $nombre_entidad[$key]; ?></option>
                                <?php } ?>
                                </select>
                                    </div>
                                 <div class="col-12">
                                    <div class="mb-4">
                                        <button type="submit" name="seleccion_entidad" class="btn btn-secondary w-100" id="seleccion_entidad">Seleccionar</button>
                                    </div>
                                </div>
                            </div>
                            <script type="text/javascript">
                               document.addEventListener('DOMContentLoaded', function() {
                                var selectEntidad = document.getElementById('select_entidad');
                                var inputUsuario = document.getElementById('select_usuario');
                                
                                function updateInput() {
                                    var selectedOption = selectEntidad.options[selectEntidad.selectedIndex];
                                    var usuario = selectedOption.getAttribute('usuario');
                                    inputUsuario.value = usuario;
                                }

                                // Añadir evento change al select
                                selectEntidad.addEventListener('change', updateInput);
                                
                                // Seleccionar el valor por defecto al cargar la página
                                updateInput();
                            });

                            </script>

                            <?php 

                                }else{
                            ?>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" name="usuario" value="<?php echo $_POST['usuario']; ?>">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-4">
                                        <label class="form-label">Clave</label>
                                        <input type="password" class="form-control" name="pass" >
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <div class="form-check form-check-primary form-check-inline">
                                            <input class="form-check-input me-3" type="checkbox" id="form-check-default">
                                            <label class="form-check-label" for="form-check-default">
                                                Recuerdame 
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <div class="mb-4">
                                        <button class="btn btn-secondary w-100" id="logeo">Ingresar</button>
                                    </div>
                                </div>

                            <?php }
                             ?>
                                
                              <!--  <div class="col-12 mb-4">
                                    <div class="">
                                        <div class="seperator">
                                            <hr>
                                            <div class="seperator-text"> <span>Or continue with</span></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-sm-4 col-12">
                                    <div class="mb-4">
                                        <button class="btn  btn-social-login w-100 ">
                                            <img src="../src/assets/img/google-gmail.svg" alt="" class="img-fluid">
                                            <span class="btn-text-inner">Google</span>
                                        </button>
                                    </div>
                                </div>
    
                                <div class="col-sm-4 col-12">
                                    <div class="mb-4">
                                        <button class="btn  btn-social-login w-100">
                                            <img src="../src/assets/img/github-icon.svg" alt="" class="img-fluid">
                                            <span class="btn-text-inner">Github</span>
                                        </button>
                                    </div>
                                </div>
    
                                <div class="col-sm-4 col-12">
                                    <div class="mb-4">
                                        <button class="btn  btn-social-login w-100">
                                            <img src="../src/assets/img/twitter.svg" alt="" class="img-fluid">
                                            <span class="btn-text-inner">Twitter</span>
                                        </button>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="text-center">
                                        <p class="mb-0">Dont't have an account ? <a href="javascript:void(0);" class="text-warning">Sign Up</a></p>
                                    </div>
                                </div>
                    -->
                            </div>
                            
                        </div>
                    </div>
                </div>
    </form>
                
            </div>
            
        </div>

    </div>


    <?php if ( isset($resultado['txt']) ){  ?>
    <script>            Snackbar.show({ text: "<?php echo  $resultado['txt']; ?>"  , pos: 'bottom-right', duration: 100000});  
    </script>

        <?php } ?>
</body>
</html>