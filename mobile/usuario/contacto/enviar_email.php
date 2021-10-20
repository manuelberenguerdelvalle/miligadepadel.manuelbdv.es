<?php
//CONTACTO DESDE DENTRO DE USUARIO
//VER LA OPCION DE PASARLO TODO A CONTACTO/C/ENVIAR_EMAIL.PHP
/*include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_email.php");
include_once ("../../class/mysql.php");
require_once ("../../PHPMailer/PHPMailerAutoload.php");
require_once ("../../PHPMailer/class.smtp.php");
session_start();
$modo =  limpiaTexto($_POST['modo']);
$enviar_email = false;
if($modo == 0){//correo enviado desde usuario/registro/elegir_plan.php, cuando desean contactar por el ClonPadel
	$contacto =  limpiaTexto3($_POST['contacto']);
	$telefono =  limpiaTexto3($_POST['telefono']);
	$mensaje =  limpiaTexto3($_POST['mensaje']);
	if($contacto != '' || $telefono != '' || $mensaje != ''){//valido que haya algún campo con datos
		$titulo = '<br>Contacto ClonPadel<br>';
		$subject = utf8_decode('miligadepadel.manuelbdv.es - Consulta sobre ClonPadel.');
		$cuerpo = '<br><br>Has recibido una consulta sobre ClonPadel del contacto: '.$contacto.', con teléfono: '.$telefono.'.<br><br>';
		$destinatario = obten_consultaUnCampo('unicas','c1','datos','id_datos','3','','','','','','','');
		$enviar_email = true;
	}//fin validar correo
}//fin modo 0
else if($modo == 1){//correo enviado de interesado en patrocinar liga de pago
	$contacto =  limpiaTexto3($_POST['contacto']);
	if($contacto != '' && valida_correo($contacto)){//valido que sea diferente de vacio y tenga
		$asunto =  limpiaTexto3($_POST['asunto']);
		$mensaje =  limpiaTexto3($_POST['mensaje']);
		$id_liga =  limpiaTexto($_POST['id_liga']);
		$id_division =  limpiaTexto($_POST['id_division']);
		$bd =  limpiaTexto($_POST['bd']);
		$posicion =  limpiaTexto3($_POST['posicion']);
		if($id_liga != '' && $id_division != '' && $bd != ''){
			if($_SESSION['bd'] != $bd){
				$_SESSION['bd'] = $bd;
			}
			$titulo = '<br>Patrocinador de Liga<br>';
			$nom_liga = obten_consultaUnCampo('session','nombre','liga','id_liga',$id_liga,'','','','','','','');
			$num_division = obten_consultaUnCampo('session','num_division','division','id_division',$id_division,'','','','','','','');
			$id_usuario =  obten_consultaUnCampo('session','usuario','liga','id_liga',$id_liga,'','','','','','','');
			$destinatario = obten_consultaUnCampo('unicas_liga','email','usuario','id_usuario',$id_usuario,'','','','','','','');
			$subject = utf8_decode('miligadepadel.manuelbdv.es - Patrocinador interesado en tu Liga '.$nom_liga.' división '.$num_division);
			$cuerpo = '<br>Liga: '.$nom_liga.' división '.$num_division.'<br><br>Anuncio seleccionado: '.obten_texto_posPubli($posicion).'.<br><br>Asunto: '.$asunto.'<br><br>Contacto:  '.$contacto.'<br><br>';
			
			$enviar_email = true;
		}//fin validar liga,division,bd
	}//fin validar correo
}//fin modo 1
else if($modo == 2){//email enviado desde el contacto general de la web
	$contacto =  limpiaTexto3($_POST['contacto']);
	if($contacto != '' && valida_correo($contacto)){//valido que sea diferente de vacio y tenga
		$asunto =  limpiaTexto3($_POST['asunto']);
		$mensaje =  limpiaTexto3($_POST['mensaje']);
		$titulo = '<br>Contacto Web<br>';
		//REVISAR id_usuario
		$destinatario = obten_consultaUnCampo('unicas_liga','email','usuario','id_usuario',$id_usuario,'','','','','','','');
		$subject = utf8_decode('miligadepadel.manuelbdv.es - Contacto general desde la web');
		$cuerpo = '<br>Contacto: '.$contacto.'<br><br>Asunto: '.$asunto.'<br><br>';
		$destinatario = obten_consultaUnCampo('unicas','c1','datos','id_datos','3','','','','','','','');
		$enviar_email = true;
	}//fin validar correo
}//fin modo 2
else if($modo == 3){//email enviado desde el contacto de liga al administrador
	$contacto =  limpiaTexto3($_POST['contacto']);
	if($contacto != '' && valida_correo($contacto)){//valido que sea diferente de vacio y tenga
		$asunto =  limpiaTexto3($_POST['asunto']);
		$mensaje =  limpiaTexto3($_POST['mensaje']);
		$id_liga =  limpiaTexto($_POST['id_liga']);
		$id_division =  limpiaTexto($_POST['id_division']);
		$nom_liga = obten_consultaUnCampo('session','nombre','liga','id_liga',$id_liga,'','','','','','','');
		$num_division = obten_consultaUnCampo('session','num_division','division','id_division',$id_division,'','','','','','','');
		$id_usuario =  obten_consultaUnCampo('session','usuario','liga','id_liga',$id_liga,'','','','','','','');
		$destinatario = obten_consultaUnCampo('unicas_liga','email','usuario','id_usuario',$id_usuario,'','','','','','','');
		$subject = utf8_decode('miligadepadel.manuelbdv.es - Contacto desde tu Liga '.$nom_liga.' división '.$num_division);
		$titulo = '<br>Contacto Liga '.$nom_liga.' división '.$num_division.'<br>';
		$cuerpo = '<br>Contacto: '.$contacto.'<br><br>Asunto: '.$asunto.'<br><br>';
		$enviar_email = true;
	}//fin validar correo
}//fin modo 3
else{
}

if($enviar_email){//ENVIAR EMAIL
	include_once ("../../funciones/f_conexion_email.php");//Set who the message is to be sent from
	$mail->setFrom('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set an alternative reply-to address
	if($modo == 0){
		$mail->addReplyTo('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set who the message is to be sent to
		$mail->addAddress($destinatario);//enviarmos al emisor
	}
	else if($modo == 1){
		$mail->addReplyTo($destinatario, 'Administrador de Liga '.$nom_liga.' division '.$num_division);//Set who the message is to be sent to
		$mail->addAddress($destinatario);//enviarmos al emisor
	}
	else if($modo == 2){
		$mail->addReplyTo($contacto);//Set who the message is to be sent to
		$mail->addAddress($destinatario);//enviarmos al emisor
	}
	else if($modo == 3){
		$mail->addReplyTo($contacto);//Set who the message is to be sent to
		$mail->addAddress($destinatario);//enviarmos al emisor
	}
	else{
	}
	//$mail->addReplyTo('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set who the message is to be sent to
	$mail->addAddress('manuel.berdelva@gmail.com');//enviarmos al emisor
	//$mail->addCC($contacto);//en copia al receptor
	$mail->Subject = $subject ;
	$cuerpo .= 'Mensaje: '.$mensaje;
	$body = email_jugadorAdmin($titulo,$cuerpo);
	$mail->msgHTML($body);
	$mail->AltBody = 'This is a plain-text message body';//Replace the plain text body with one created manually
	$mail->send();
}
*/
?>
