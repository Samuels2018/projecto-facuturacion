<?php 	

	include_once "../../conf/conf.php";
	require_once ENLACE_SERVIDOR . "mod_cronjobs/object/cron.actividades.object.php";

	 //Vamos a llamar a todas las licencias
    $dbh  = $dbh_plataforma->prepare("select * from sistema_empresa_licencias");
    $dbh->execute(); 
    $row = $dbh->fetch(PDO::FETCH_ASSOC);
    $dbh = new PDO('mysql:host='.$row['server'].';dbname='. sanitize_string($row['bd']) .';charset=UTF8', sanitize_string($row['user']) ,  sanitize_string($row['pass'])  , array(
            PDO::ATTR_PERSISTENT => true,
        ));
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

	//vamos a hacer la consulta por entidades para poder enviar los correos
	$cron_actividades = new cron_actividades($dbh);
	$lista_actividades = $cron_actividades->listar_entidades_actividades();


	var_dump($lista_actividades);




?>