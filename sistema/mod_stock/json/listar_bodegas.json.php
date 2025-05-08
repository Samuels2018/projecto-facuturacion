<?php

if (!defined('ENLACE_WEB')):
    session_start();
    require_once "../../conf/conf.php";
endif;

$sqlstr = "SELECT * FROM fi_bodegas WHERE entidad  = :entidad AND activo = 1 and borrado = 0 ORDER BY bodega_defecto DESC";


// Obtiene el número total de registros
$resultsFilter = $dbh->prepare($sqlstr);
$resultsFilter->bindParam(':entidad', $_SESSION['Entidad'], PDO::PARAM_INT);
$resultsFilter->execute();

$Records = $resultsFilter->fetchAll();

echo json_encode($Records);