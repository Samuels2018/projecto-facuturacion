<?php 	

	include_once "../../conf/conf.php";
	require_once ENLACE_SERVIDOR."mod_tareas_programadas/object/email.notificador.actividads.object.php";
	//Esto para enviar el correo
	include_once(ENLACE_SERVIDOR."mail_sys/mail/PHPMailer/src/EnviarCorreoSmtp.php");


	 //Vamos a llamar a todas las licencias
    $dbh  = $dbh_plataforma->prepare("select * from sistema_empresa_licencias");
    $dbh->execute(); 
    $row = $dbh->fetch(PDO::FETCH_ASSOC);
    $dbh = new PDO('mysql:host='.$row['server'].';dbname='. sanitize_string($row['bd']) .';charset=UTF8', sanitize_string($row['user']) ,  sanitize_string($row['pass'])  , array(
            PDO::ATTR_PERSISTENT => true,
        ));
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

    //vamos a hacer la consulta por entidades para poder enviar los correos
	$cron_actividades = new email_notificador_actividades($dbh);
	$lista_entidades = $cron_actividades->listar_entidades();


	//VAMOS A HACER UN FOREACH PARA EL LISTADO DE LA EMPRESA
	foreach($lista_entidades as $entidad)
	{	
		
		//vamos a obtener la entidad FK_entidad
		$cron_actividades->fk_entidad = $entidad['fk_entidad'];
		$cron_actividades->nombre_fantasia = $entidad['nombre_fantasia'];

		$emails_usuarios_entidad = $cron_actividades->buscar_usuarios_por_entidad();
		//la data de los usuarios con los correos electronicos 
		$lista_actividades_entidad = $cron_actividades->listar_actividades_entidad();
		$html = '<div style="font-family: Arial, sans-serif; line-height: 1.6;">';
		$html .= '<h2 style="color: #333; text-align: center;">Buen día equipo de la empresa ' . $entidad['nombre_fantasia'] . '</h2>';
		$html .= '<h4 style="color: #555; text-align: center;">Estas son las actividades por vencer en los próximos 3 días</h4>';
		$html .= '<table style="border-collapse: collapse; width: 100%; margin: 20px 0; font-size: 14px;">';
		$html .= '<tr style="background-color: #4CAF50; color: white; text-align: left;">';
		$html .= '<th style="border: 1px solid #ddd; padding: 12px; text-align: left;"></th>';
		$html .= '<th style="border: 1px solid #ddd; padding: 12px; text-align: left;">Cliente</th>';
		$html .= '<th style="border: 1px solid #ddd; padding: 12px; text-align: left;">Oportunidad</th>';
		$html .= '<th style="border: 1px solid #ddd; padding: 12px; text-align: left;">Tarea</th>';
		$html .= '<th style="border: 1px solid #ddd; padding: 12px; text-align: left;">Fecha de vencimiento</th>';
		$html .= '<th style="border: 1px solid #ddd; padding: 12px; text-align: left;">Usuario asignado</th>';
		$html .= '<th style="border: 1px solid #ddd; padding: 12px; text-align: left;">Estado</th>';
		$html .= '</tr>';

		// Listar las actividades
		$i = 1;
		$fecha_actual = date('d/m/Y'); // Puedes ajustar el formato de fecha según sea necesario

		foreach ($lista_actividades_entidad as $actividad) {
		    $backgroundColor = ($i % 2 == 0) ? '#f2f2f2' : '#ffffff';
		    $color_estado = $actividad['estado_color'];
		    $html .= '<tr style="background-color: ' . $backgroundColor . ';">';
		    $html .= '<td style="border: 1px solid #ddd; padding: 12px;">' . $i . '</td>';
		    $html .= '<td style="border: 1px solid #ddd; padding: 12px;">' . $actividad['cliente'] . '</td>';
		    $html .= '<td style="border: 1px solid #ddd; padding: 12px;">' . $actividad['oportunidad'] . '</td>';
		    $html .= '<td style="border: 1px solid #ddd; padding: 12px;">' . $actividad['actividad'] . '</td>';
		    $html .= '<td style="border: 1px solid #ddd; padding: 12px;">' .date('d/m/Y',strtotime($actividad['vencimiento_fecha'])). '</td>';
		    $html .= '<td style="border: 1px solid #ddd; padding: 12px;">' . $actividad['usuario_asignado'] . '</td>';
		    $html .= '<td style="border: 1px solid #ddd; padding: 12px; background-color:' . $color_estado . '; color: white;">' . $actividad['estado_actividad'] . '</td>';
		    $html .= '</tr>';
		    $i++;
		}
		$html .= '</table>';
		$html .= '</div>';
		$html .= '<div style="font-family: Arial, sans-serif; line-height: 1.6; margin-top: 20px;">';
		$html .= '<hr style="border: 0; border-top: 1px solid #ccc; margin: 20px 0;">';
		$html .= '<p style="text-align: center; color: #555;">Sistema Avantec.DS | ' . $fecha_actual . '</p>';
		$html .= '</div>';
        $subject = 'Recordatorio de actividades '.$entidad['nombre_fantasia'];
        $para = $emails_usuarios_entidad;
        $attachments = [];
        $debug = 0;
        $respuesta = Email_SMPT($dbh, $html, $para, $attachments,'enviar_email_logico.php', $subject, $debug);
        var_dump($respuesta);

	}


?>