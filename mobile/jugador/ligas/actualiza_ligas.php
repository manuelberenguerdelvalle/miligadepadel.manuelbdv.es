<?php
include_once ("../../../class/mysql.php");
include_once ("../../../class/jugador.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_email.php");
require_once ("../../../PHPMailer/PHPMailerAutoload.php");
require_once ("../../../PHPMailer/class.smtp.php");
header("Content-Type: text/html;charset=ISO-8859-1");
session_start();
$pagina = $_SESSION['pagina'];
$jugador = unserialize($_SESSION['jugador']);
if ( $pagina != 'gestion_ligas'){
	header ("Location: ../cerrar_sesion.php");
}
else {
	//include_once ("../../funciones/f_recoger_post.php");//SIEMPRE TIENE QUE ESTAR F_GENERAL MELOOOOONNNNNNNN 2 TARDES CON ESTO
	//llamar a limpiarTexto2
	$tipo = limpiaTexto($_POST['tipo']);
	if($tipo == 'Partidos'){//PARTIDO
		$admin = obten_consultaUnCampo('unicas_liga','email','usuario','id_usuario',limpiaTexto($_POST['admin']),'','','','','','','');
		$nombre = limpiaTexto($_POST['nombre']);
		$texto = limpiaTexto($_POST['texto']);
		$asunto = utf8_decode('Un Jugador/a de tu Liga de Padel <'.$nombre.'>, te envia este e-mail en relación a '.$tipo.'.');
		$cab = 'En relacion a '.$tipo.'<br>El Jugador/a '.$jugador->getValor("nombre").' '.$jugador->getValor("apellidos").', ha escrito:';
	}
	else{//CONSULTA GENERAL
		$equipo = limpiaTexto($_POST['equipo']);
		$bd = limpiaTexto($_POST['bd']);
		$nom_liga = limpiaTexto($_POST['nom_liga']);
		$num_division = limpiaTexto($_POST['num_division']);
		$texto = limpiaTexto($_POST['texto']);
		$id_admin = obten_consultaUnCampo('unicas_liga','usuario','pago_admin','bd',$bd,'equipo',$equipo,'','','','','');
		$admin = obten_consultaUnCampo('unicas_liga','email','usuario','id_usuario',$id_admin,'','','','','','','');
		$asunto = utf8_decode('Un Jugador/a de tu Liga de Padel <'.$nom_liga.' División '.$num_division.'>, te envia esta '.$tipo.'.');
		$cab = $tipo.'<br>El Jugador/a '.$jugador->getValor("nombre").' '.$jugador->getValor("apellidos").', ha escrito:';
	}
	include_once ("../../funciones/f_conexion_email.php");//Set who the message is to be sent from
	$mail->setFrom('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set an alternative reply-to address
	$mail->addReplyTo($jugador->getValor('email'), $jugador->getValor('nombre'));//Set who the message is to be sent to
	$mail->addAddress($admin);//administrador destinatario
	$mail->addCC($jugador->getValor('email'));//en copia al jugador
	//$mail->addCC('manu_oamuf@hotmail.com');//en copia al jugador
	//Set the subject line
	$mail->Subject = $asunto;
	$body = email_jugadorAdmin($cab,$texto);
	$mail->msgHTML($body);
	//Replace the plain text body with one created manually
	$mail->AltBody = 'This is a plain-text message body';
	//send the message, check for errors
	if (!$mail->send()) {
		echo "1";//fallo
	} else {
		echo "0";//ok
	}
	
	/*
	//ENVIAR CORREO A LOS INSCRIPTORES
	include_once ("../../funciones/f_conexion_email.php");//Set who the message is to be sent from
	$mail->setFrom('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set an alternative reply-to address
	$mail->addReplyTo('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set who the message is to be sent to
	$mail->addAddress($admin);//administrador destinatario
	$mail->addCC($jugador->getValor('email'));//en copia al jugador
	$asunto = utf8_decode('Un Jugador/a de tu Liga de Padel <'.$nombre.'>, te envia este e-mail en relación a '.$tipo.'.');
	$mail->Subject = $asunto;
	$cab = 'En relacion a '.$tipo.'<br>El Jugador/a '.$jugador->getValor("nombre").' '.$jugador->getValor("apellidos").', ha escrito:';
	$body = email_jugadorAdmin($cab,$texto);
	$mail->msgHTML($body);
	$mail->AltBody = 'This is a plain-text message body';//Replace the plain text body with one created manually
	if (!$mail->send()) {//no enviado
		echo '1';
	} else {//enviado
		echo '0';
	}
	*/
}//fin else

?>