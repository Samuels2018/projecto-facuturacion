<?php
session_start();

// Si no hay usuario autenticado, cerrar conexión
if (!isset($_SESSION['usuario'])) {
    echo acceso_invalido();
    exit(1);
}


require_once "../../conf/conf.php";

$documento  = $_POST["documento"];
$tipo_aeat  = "";
$mensaje    = array();



 
 

if ($documento == 'fi_europa_facturas') { 

        require_once(ENLACE_SERVIDOR . "mod_europa_facturacion/object/facturas.object.php");
        $Documento = new Factura($dbh, $_SESSION['Entidad']);
        $Documento->fetch_encriptado($_POST['id']);
        if ($Documento->fk_tercero > 0 ){
            $tipo_aeat =" AND tipo_aeat = 'F1' ";
        } else {
            $tipo_aeat =" AND tipo_aeat = 'F2' ";
        }
     }






$sqlstr = "SELECT * FROM fi_europa_facturas_configuracion 
    WHERE entidad = :entidad
    AND borrado = 0
    AND serie_activa=1 
    AND tipo = :documento
    {$tipo_aeat}
    ORDER BY serie_por_defecto DESC";


// Obtiene el número total de registros
$resultsFilter = $dbh->prepare($sqlstr);
$resultsFilter->bindParam(':entidad'    , $_SESSION['Entidad']  , PDO::PARAM_INT);
$resultsFilter->bindParam(':documento'  , $documento            , PDO::PARAM_STR);
$resultsFilter->execute();

$Records = $resultsFilter->fetchAll();

echo json_encode($Records);
