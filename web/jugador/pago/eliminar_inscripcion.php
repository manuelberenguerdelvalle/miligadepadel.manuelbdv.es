<?php
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_pagos.php");
include_once ("../../funciones/f_email.php");
include_once ("../../../class/mysql.php");
include_once ("../../../class/jugador.php");
include_once ("../../../class/pago_admin.php");
include_once ("../../../class/equipo.php");
include_once ("../../../class/inscripcion.php");
require_once ("../../../PHPMailer/PHPMailerAutoload.php");
require_once ("../../../PHPMailer/class.smtp.php");
header("Content-Type: text/html;charset=ISO-8859-1");
session_start();
$pagina = $_SESSION['pagina'];
$jugador = unserialize($_SESSION['jugador']);
if ( $pagina != 'gestion_pago'){
	header ("Location: ../cerrar_sesion.php");
}
else {
	if(isset($_POST['id_pago_admin'])){////ELIMINACION DE INSCRIPCION SIN PAGAR O GRATIS, ELIMINACIÓN DIRECTA
		$id_pago_admin = limpiaTexto($_POST['id_pago_admin']);
		$motivo = limpiaTexto($_POST['motivo']);
		$pago_admin = new Pago_admin($id_pago_admin,'','','','','','','','','','','','','','','','','');
		$_SESSION['bd'] = $pago_admin->getValor('bd');
		$equipo = new Equipo($pago_admin->getValor('equipo'),'','','','','','','','','');
		$inscripcion = new Inscripcion('',$equipo->getValor('division'),$equipo->getValor('liga'),'','','',$equipo->getValor('jugador1'),'','','','','','','','','','','','',$equipo->getValor('jugador2'),'','','','','','','','','','','','');
		$nombre_liga = obten_consultaUnCampo('session','nombre','liga','id_liga',$equipo->getValor('liga'),'','','','','','','');
		$num_division = obten_consultaUnCampo('session','num_division','division','id_division',$equipo->getValor('division'),'','','','','','','');
		$email_admin = obten_consultaUnCampo('unicas_liga','email','usuario','id_usuario',$pago_admin->getValor('usuario'),'','','','','','','');
		//ENVIAR CORREO AL EMISOR
		include_once ("../../funciones/f_conexion_email.php");//Set who the message is to be sent from
		$mail->setFrom('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set an alternative reply-to address
		$mail->addReplyTo('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set who the message is to be sent to
		//$mail->addAddress('manu_oamuf@hotmail.com');//enviarmos al emisor
		$mail->addAddress($inscripcion->getValor('email1'));//enviarmos al jugador 1
		$mail->addAddress($inscripcion->getValor('email2'));//enviarmos al jugador 2
		//$mail->addCC();//en copia al receptor
		$asunto = utf8_decode('La Inscripción en la Liga <'.$nombre_liga.' división '.$num_division.'> ha sido anulada.');
		$mail->Subject = $asunto;
		$cuerpo = '<br><br>Detalles:<br><br>';
		$cuerpo .= 'El Jugador '.$jugador->getValor("nombre").' '.$jugador->getValor("apellidos").' ha anulado la inscripción a la Liga '.$nombre_liga.' división '.$num_division.'.<br><br>';
		if($motivo != ''){$cuerpo .= 'Motivo: '.$motivo.'.<br><br>';}
		$body = email_jugadorAdmin("<br>Gracias por utilizar nuestros servicios miligadepadel.manuelbdv.es<br>",$cuerpo);
		$mail->msgHTML($body);
		$mail->AltBody = 'This is a plain-text message body';//Replace the plain text body with one created manually
		$mail->send();
		//ENVIAR CORREO AL RECEPTOR
		include_once ("../../funciones/f_conexion_email2.php");
		$mail2->setFrom('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set an alternative reply-to address
		$mail2->addReplyTo('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set who the message is to be sent to
		$mail2->addAddress($email_admin);//enviarmos al usuario
		$asunto = utf8_decode('El Jugador '.$jugador->getValor("nombre").' '.$jugador->getValor("apellidos").' ha anulado una inscripción en tu Liga '.$nombre_liga.' división '.$num_division.'.');
		$mail2->Subject = $asunto;
		$cuerpo = '<br><br>Detalles:<br><br>';
		$cuerpo .= 'Inscripción anulada:<br><br>';
		$cuerpo .= utf8_encode($inscripcion->getValor('nombre1').' '.$inscripcion->getValor('apellidos1').' - '.$inscripcion->getValor('email1')).'<br>';
		$cuerpo .= utf8_encode($inscripcion->getValor('nombre2').' '.$inscripcion->getValor('apellidos2').' - '.$inscripcion->getValor('email2')).'<br><br>';
		if($motivo != ''){$cuerpo .= 'Motivo: '.$motivo.'.<br><br>';}
		$body = email_jugadorAdmin("<br>Gracias por utilizar nuestros servicios miligadepadel.manuelbdv.es<br>",$cuerpo);
		$mail2->msgHTML($body);//el mismo body
		$mail2->AltBody = 'This is a plain-text message body';//Replace the plain text body with one created manually
		$mail2->send();
		$inscripcion->borrar();
		$equipo->borrar();
		$pago_admin->borrar();
		echo '0';
	}//fin if
}//fin else

?>