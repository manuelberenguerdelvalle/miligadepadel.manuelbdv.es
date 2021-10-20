<?php
include_once ("../../../class/mysql.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_email.php");
require_once ("../../../PHPMailer/PHPMailerAutoload.php");
require_once ("../../../PHPMailer/class.smtp.php");
session_start();
$pagina = $_SESSION['pagina'];
if ( $pagina != 'index' && $_SESSION['intentos_pass'] >= 10){
	header ("Location: http://miligadepadel.manuelbdv.es");
}
else {
	$tipo = limpiaTexto(htmlspecialchars($_POST["tipo"]));
	$email = limpiaTexto(htmlspecialchars($_POST["texto"]));
	$enviar = false;
	$resultado = 0;
	if($tipo == 0){//administrador
		$db = new MySQL('unicas_liga');//UNICAS LIGA
		$consulta = $db->consulta("SELECT password FROM `usuario` WHERE `email` = '$email' and `bloqueo` = 'N' ;");
		$array = $consulta->fetch_array(MYSQLI_ASSOC);
		//$resultado = $consulta->num_rows;
		/*$c = $db->consulta("SELECT COUNT(id_partido) AS s FROM partido WHERE estado='$estado' AND set1_local != '-1' AND (local='$id_equipo' OR visitante='$id_equipo'); ");
	    $r = $c->fetch_array(MYSQLI_ASSOC);*/
		if($array['password'] != ''){
			$resultado = 1;
			$panel = 'Administrador';
			$enviar = true;
		}
		else{$_SESSION['intentos_pass']++;}
	}
	else if($tipo == 1){//jugador
		$db = new MySQL('unicas');//UNICAS LIGA
		$consulta = $db->consulta("SELECT password FROM `jugador` WHERE `email` = '$email' ;");
		$array = $consulta->fetch_array(MYSQLI_ASSOC);
		if($array['password'] != ''){
			$resultado = 1;
			$panel = 'Jugador';
			$enviar = true;
		}
		else{$_SESSION['intentos_pass']++;}
	}
	else if($tipo == 2){//publicidad
		$db = new MySQL('unicas');//UNICAS LIGA
		$consulta = $db->consulta("SELECT password FROM `usuario_publi` WHERE `email` = '$email' and `bloqueo` = 'N' ;");
		$array = $consulta->fetch_array(MYSQLI_ASSOC);
		if($array['password'] != ''){
			$resultado = 1;
			$panel = 'Patrocinador';
			$enviar = true;
		}
		else{$_SESSION['intentos_pass']++;}
	}
	else{
		$resultado = 0;
	}
	if($enviar){
		//ENVIAR CORREO A LOS INSCRIPTORES
		include_once ("../../funciones/f_conexion_email.php");//Set who the message is to be sent from
		$mail->setFrom('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set an alternative reply-to address
		$mail->addReplyTo('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set who the message is to be sent to
		//$mail->addAddress('manu_oamuf@hotmail.com');//enviarmos al emisor
		$mail->addAddress($email);//enviarmos al emisor
		//$mail->addCC();//en copia al receptor
		$asunto = utf8_decode('Recuperación de password panel de '.$panel);
		$mail->Subject = $asunto;
		$cuerpo = '<br><br>Acceso al panel de '.$panel.':<br><br>';
		$cuerpo .= 'Email: '.$email.'<br><br>';
		$cuerpo .= 'Password: '.$array['password'].'<br><br>';
		$cuerpo .= 'Por favor, por seguridad guarde este e-mail en un lugar seguro. <br>Es recomendable cambiar su contraseña al menos cada 6 meses. <br>Si no has solicitado la recuperación de la contraseña, por favor cambie la contraseña la más breve posible.<br><br>';
		$cuerpo .= $mensaje;
		$body = email_jugadorAdmin("<br>Recuperar contraseña de ".$panel."<br>",$cuerpo);
		$mail->msgHTML($body);
		$mail->AltBody = 'This is a plain-text message body';//Replace the plain text body with one created manually
		$mail->send();
	}
	echo $resultado;
}

?>