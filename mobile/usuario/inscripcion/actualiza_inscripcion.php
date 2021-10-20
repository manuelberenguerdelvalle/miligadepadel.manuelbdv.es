<?php
include_once ("../../funciones/f_obten.php");
include_once ("../../funciones/f_general.php");
include_once ("../../funciones/f_pagos.php");
include_once ("../../funciones/f_email.php");
include_once ("../../../class/mysql.php");
include_once ("../../../class/pago_admin.php");
include_once ("../../../class/equipo.php");
include_once ("../../../class/inscripcion.php");
include_once ("../../../class/notificacion.php");
require_once ("../../../PHPMailer/PHPMailerAutoload.php");
require_once ("../../../PHPMailer/class.smtp.php");
session_start();
$pagina = $_SESSION['pagina'];
$opcion = $_SESSION['opcion'];
if ($pagina != 'gestion_inscripcion' || $opcion != 0){
	header ("Location: ../cerrar_sesion.php");
}
//ESTA PAGINA SE USA SEGUN EL MODO,  ENVIAR E-MAIL O ELIMINAR
$modo = $_POST['modo'];
if($modo == 'email'){//enviar e-mail
	$id_inscripcion = limpiaTexto($_POST['id_inscripcion']);
	$mensaje = limpiaTexto3($_POST['texto']);
	$inscripcion = new Inscripcion($id_inscripcion,'','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','');
	$nombre_liga = utf8_encode($_SESSION['nombre']);
	$num_division = $_SESSION['num_division'];
	//ENVIAR CORREO A LOS INSCRIPTORES
	include_once ("../../funciones/f_conexion_email.php");//Set who the message is to be sent from
	$mail->setFrom('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set an alternative reply-to address
	$mail->addReplyTo('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set who the message is to be sent to
	//$mail->addAddress('manu_oamuf@hotmail.com');//enviarmos al emisor
	$mail->addAddress($inscripcion->getValor('email1'));//enviarmos al emisor
	$mail->addAddress($inscripcion->getValor('email2'));//enviarmos al emisor
	//$mail->addCC();//en copia al receptor
	$asunto = utf8_decode('El Administrador de la Liga de padel <'.$nombre_liga.' division '.$num_division.'> te envia este em@il.');
	$mail->Subject = $asunto;
	$cuerpo = '<br><br>Detalles:<br><br>';
	$cuerpo .= quitarCespeciales($mensaje);
	$body = email_jugadorAdmin("<br>El Administrador le envia este e-mail<br>",$cuerpo);
	$mail->msgHTML($body);
	$mail->AltBody = 'This is a plain-text message body';//Replace the plain text body with one created manually
	if (!$mail->send()) {//no enviado
		echo '1';
	} else {//enviado
		echo '0';
	}
}
else if($modo == 'email_eliminacion'){//enviar e-mail
	$id_inscripcion = limpiaTexto($_POST['id_inscripcion']);
	$mensaje = limpiaTexto3($_POST['texto']);
	$inscripcion = new Inscripcion($id_inscripcion,'','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','');
	$nombre_liga = utf8_encode($_SESSION['nombre']);
	$num_division = $_SESSION['num_division'];
	$db99 = new MySQL('unicas');//UNICAS
	$consulta99 = $db99->consulta("INSERT INTO `eliminar_inscripcion` (`id`, `bd`, `inscripcion`, `usuario`, `liga`, `division`, `respuesta`, `fecha`) VALUES (NULL, '".$_SESSION['bd']."', '".$id_inscripcion."', '".$_SESSION['id_usuario']."', '".$_SESSION['id_liga']."','".$_SESSION['id_division']."', NULL, '".date('Y-m-d H:i:s')."'); ");
	$consulta99 = $db99->consulta("SELECT id FROM `eliminar_inscripcion` WHERE usuario = '".$_SESSION['id_usuario']."' AND inscripcion = '".$id_inscripcion."' ORDER BY id DESC; ");
	$id = $consulta99->fetch_array(MYSQLI_ASSOC);
	//ENVIAR CORREO A LOS INSCRIPTORES
	include_once ("../../funciones/f_conexion_email.php");//Set who the message is to be sent from
	$email_elimi = obten_consultaUnCampo('unicas','c1','datos','id_datos',7,'','','','','','','');
	$mail->setFrom('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set an alternative reply-to address
	$mail->addReplyTo($email_elimi, 'miligadepadel.manuelbdv.es');//Set who the message is to be sent to
	$mail->addReplyTo($inscripcion->getValor('email1'));//Set who the message is to be sent to
	$mail->addReplyTo($inscripcion->getValor('email2'));//Set who the message is to be sent to
	//$mail->addAddress('manu_oamuf@hotmail.com');//enviarmos al emisor
	$mail->addAddress($inscripcion->getValor('email1'));//enviarmos al emisor
	$mail->addAddress($inscripcion->getValor('email2'));//enviarmos al emisor
	$mail->addAddress($email_elimi);//EN COPIA OCULTA A ELIMINACIONES
	//$mail->AddBCC(obten_consultaUnCampo('unicas','c1','datos','id_datos',7,'','','','','','',''));//EN COPIA OCULTA A ELIMINACIONES
	//$mail->addCC();//en copia al receptor
	$asunto = utf8_decode('El Administrador de la Liga de padel <'.$nombre_liga.' division '.$num_division.'> desea eliminar su inscripción en esta liga y división.');
	$mail->Subject = $asunto;
	
	$linksi = 'http://miligadepadel.manuelbdv.es/mobile/v/v/valida_respuesta_elim.php?id='.genera_id_url(100,$id['id'].'SF',13);
	$linkno = 'http://miligadepadel.manuelbdv.es/mobile/v/v/valida_respuesta_elim.php?id='.genera_id_url(100,$id['id'].'NF',13);
	$cuerpo = '<br><br>Comentario: '.utf8_encode($mensaje);
	$cuerpo .= '<br><br>¿Has recibido la devolución económica de tu inscripción?<br>Si pulsa en Si, se eliminará su inscripción en la liga '.$nombre_liga.' y división '.$num_division.'<br><br>';
	$cuerpo .= '<a style="width:70px;background-color:#039;margin-left:15%;margin-right:7%;padding: 5px 11px 5px 11px;font-size:18px;color:#FFF; text-decoration:none;box-shadow:2px 2px 3px rgba(0,0,0,0.5);border:1px #017 solid;" href="'.$linksi.'" target="_blank"><b>Si</b></a>
		<a style="width:70px;background-color:#039;margin-left:7%;padding: 5px 7px 5px 7px;font-size:18px;color:#FFF; text-decoration:none;box-shadow:2px 2px 3px rgba(0,0,0,0.5);border:1px #017 solid;" href="'.$linkno.'" target="_blank"><b>No</b></a><br><br>';
	
	$body = email_jugadorAdmin("<br>El Administrador desea eliminar su inscripción en esta liga<br>",$cuerpo);
	$mail->msgHTML($body);
	$mail->AltBody = 'This is a plain-text message body';//Replace the plain text body with one created manually
	if (!$mail->send()) {//no enviado
		echo '1';
	} else {//enviado
		echo '0';
	}
}//fin else if
/*
NO FUNCIONA YA QUE CADA CUENTA PAYPAL TIENE SU ID, POR SI HABILITO TODOS LOS PAGOS A MI CUENTA
else if($modo == 'eliminacion_online'){//eliminacion online
	$id_inscripcion = limpiaTexto($_POST['id_inscripcion']);
	$id_trans = limpiaTexto3($_POST['texto']);
	if(obten_consultaUnCampo('unicas_liga','COUNT(id_pago_admin)','pago_admin','transaccion',$id_trans,'','','','','','','') == 0){
		$nombre_liga = utf8_encode($_SESSION['nombre']);
		$num_division = $_SESSION['num_division'];
		$inscripcion = new Inscripcion($id_inscripcion,'','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','');
		$equipo = new Equipo('',$inscripcion->getValor('id_jugador1'),'',$inscripcion->getValor('id_jugador2'),'',$inscripcion->getValor('liga'),$inscripcion->getValor('division'),'','','');
		$pago_admin = new Pago_admin('',$inscripcion->getValor('liga'),$inscripcion->getValor('division'),$_SESSION['bd'],$equipo->getValor('id_equipo'),'','','','','','','','','','','','','');
		//if(strtotime(date('Y-m-d H:i:s')) < (strtotime($pago_admin->getValor('fecha')) + pasar_segundos(3)) ){//si estamos dentro de los 3 días que tenemos información
			$res = paypal_PDT_request($id_trans,obten_identPaypal());
			$datos_rec = array();
			$datos_rec = obten_arrayResPaypal($res);
			if(obten_resDespuesIgual($datos_rec[25]) == 'Completed' || obten_resDespuesIgual($datos_rec[24]) == 'Completed' ){//completada
				if($pago_admin->getValor('emisor') == obten_resDespuesIgual($datos_rec[13]) && $pago_admin->getValor('receptor') == obten_resDespuesIgual($datos_rec[16])){//devolucion ok
					$pago_admin->setValor('equipo',0); //si esta a 0, es eliminado
					$pago_admin->setValor('estado','D'); //si esta a D, es eliminado
					$pago_admin->modificar();
					$datos_jugadores = $inscripcion->getValor('nombre1').' '.$inscripcion->getValor('apellidos1').'-'.$inscripcion->getValor('nombre2').' '.$inscripcion->getValor('apellidos2');
					$devolucion = new Pago_admin('',$pago_admin->getValor('liga'),$pago_admin->getValor('division'),$pago_admin->getValor('bd'),-1,$pago_admin->getValor('precio'),$pago_admin->getValor('modo_pago'),'S',$pago_admin->getValor('emisor'),$pago_admin->getValor('usuario'),$pago_admin->getValor('receptor'),date('Y-m-d H:i:s'),obten_resDespuesIgual($datos_rec[14]),'',$datos_jugadores,$pago_admin->getValor('jugador1'),$pago_admin->getValor('jugador2'),'E');
					$devolucion->insertar();
					$notificacion1 = new Notificacion('',$pago_admin->getValor('jugador1'),$pago_admin->getValor('liga'),$pago_admin->getValor('division'),'pago_recibido.php',date('Y-m-d H:i:s'),'N');
					 $notificacion1->insertar();
					 $notificacion2 = new Notificacion('',$pago_admin->getValor('jugador2'),$pago_admin->getValor('liga'),$pago_admin->getValor('division'),'pago_recibido.php',date('Y-m-d H:i:s'),'N');
					 $notificacion2->insertar();
					//ENVIAR CORREO AL EMISOR
						include_once ("../../funciones/f_conexion_email.php");//Set who the message is to be sent from
						$mail->setFrom('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set an alternative reply-to address
						$mail->addReplyTo('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set who the message is to be sent to
						//$mail->addAddress('manu_oamuf@hotmail.com');//enviarmos al emisor
						
						$mail->addAddress($inscripcion->getValor('email1'));//enviarmos al jugador 1
						$mail->addAddress($inscripcion->getValor('email2'));//enviarmos al jugador 2
						
						//$mail->addCC();//en copia al receptor
						$asunto = utf8_decode('La Inscripción en la Liga <'.$nombre_liga.' división '.$num_division.'> ha sido anulada por el Administrador');
						$mail->Subject = $asunto;
						$cuerpo = '<br><br>Detalles:<br><br>';
						$cuerpo .= 'El Administrador ha anulado tu inscripción a la Liga '.$nombre_liga.' división '.$num_division.'.<br><br>';
						$cuerpo .= 'Revisen su correo y cuenta PayPal verán reflejado el abono.<br><br>';
						$body = email_jugadorAdmin("<br>Gracias por utilizar nuestros servicios miligadepadel.manuelbdv.es<br>",$cuerpo);
						$mail->msgHTML($body);
						$mail->AltBody = 'This is a plain-text message body';//Replace the plain text body with one created manually
						$mail->send();
						//ENVIAR CORREO AL RECEPTOR
						include_once ("../../funciones/f_conexion_email2.php");
						$mail2->setFrom('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set an alternative reply-to address
						$mail2->addReplyTo('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set who the message is to be sent to
						$mail2->addAddress($_SESSION['email']);//enviarmos al usuario
						$asunto = utf8_decode('Has anulado una inscripción en la Liga '.$nombre_liga.' división '.$num_division.'.');
						$mail2->Subject = $asunto;
						$cuerpo = '<br><br>Detalles:<br><br>';
						$cuerpo .= 'Inscripción anulada:<br><br>';
						$cuerpo .= utf8_encode($inscripcion->getValor('nombre1').' '.$inscripcion->getValor('apellidos1').' - '.$inscripcion->getValor('email1')).'<br>';
						$cuerpo .= utf8_encode($inscripcion->getValor('nombre2').' '.$inscripcion->getValor('apellidos2').' - '.$inscripcion->getValor('email2')).'<br><br>';
						$body = email_jugadorAdmin("<br>Gracias por utilizar nuestros servicios miligadepadel.manuelbdv.es<br>",$cuerpo);
						$mail2->msgHTML($body);//el mismo body
						$mail2->AltBody = 'This is a plain-text message body';//Replace the plain text body with one created manually
						$mail2->send();
						$inscripcion->borrar();
						$equipo->borrar();
						echo '0';
				}//fin if emisor receptor
				else{
					echo '1';
				}
			}//fin if estado
			else{
				echo '1';
			}

		//}//fin if dias
	}//FIN CONSULTAR TRANSACCION
	else{
		echo '1';
	}
}//fin else if
*/
else if($modo == 'marcar_pagado'){//marcar pagado
	$id_inscripcion = limpiaTexto($_POST['id_inscripcion']);
	$inscripcion = new Inscripcion($id_inscripcion,'','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','');
	$inscripcion->setValor('pagado','S');
	$inscripcion->setValor('pago','M');
	$inscripcion->modificar();
	$equipo = new Equipo('',$inscripcion->getValor('id_jugador1'),'',$inscripcion->getValor('id_jugador2'),'',$inscripcion->getValor('liga'),$inscripcion->getValor('division'),'','','');
	$equipo->setValor('pagado','S');
	$equipo->modificar();
	$pago_admin = new Pago_admin('',$inscripcion->getValor('liga'),$inscripcion->getValor('division'),$_SESSION['bd'],$equipo->getValor('id_equipo'),'','','','','','','','','','','','','');
	$pago_admin->setValor('pagado','S');
	$pago_admin->setValor('fecha',date('Y-m-d H:i:s'));
	$pago_admin->setValor('modo_pago','M');
	$pago_admin->modificar();
	$notificacion = new Notificacion('',$pago_admin->getValor('usuario'),$pago_admin->getValor('liga'),$pago_admin->getValor('division'),'modificar_pago_recibido.php',date('Y-m-d H:i:s'),'N');
	$notificacion->insertar();
	 //ENVIAR CORREO AL PAGADOR JUGADORES DE LA LIGA
				 include_once ("../../funciones/f_conexion_email.php");
				$mail->setFrom('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set an alternative reply-to address
				$mail->addReplyTo('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set who the message is to be sent to
				//$mail->addAddress('manu_oamuf@hotmail.com');//enviarmos al emisor
				
				$mail->addAddress($inscripcion->getValor('email1'));//enviarmos al emisor
				$mail->addAddress($inscripcion->getValor('email2'));//enviarmos al emisor
				$mail->AddBCC(obten_consultaUnCampo('unicas','c1','datos','id_datos',6,'','','','','','',''));//EN COPIA BACKUP AL QUE ENVIA EL PAGO
				//$mail->addCC();//en copia al receptor
				$asunto = utf8_decode('Pago de Inscripción en la Liga <'.utf8_encode($_SESSION['nombre']).' división '.$_SESSION['num_division'].'> realizado correctamente por el Administrador!');
				$mail->Subject = $asunto;
				$cuerpo = '<br><br>Detalles: Pago autorizado manualmente por el Administrador<br><br>';
				$cuerpo .= 'Pago nº: '.$pago_admin->getValor("id_pago_admin").'.<br><br>';
				$cuerpo .= 'Fecha: '.date("Y-m-d H:i:s").'.<br><br>';
				$cuerpo .= 'Descripción : Inscripción en Liga '.utf8_encode($_SESSION['nombre']).' división '.$_SESSION['num_division'].'.<br><br>';
				$cuerpo .= 'Precio : '.$pago_admin->getValor('precio').' EUR (con I.V.A).<br><br>';
				$cuerpo .= 'Pago realizado manualmente por el Administrador.<br><br>';
				
				$body = email_jugadorAdmin("<br>¡Gracias por inscribirte en una Liga de miligadepadel.manuelbdv.es!<br>",$cuerpo);
				$mail->msgHTML($body);
				$mail->AltBody = 'This is a plain-text message body';//Replace the plain text body with one created manually
				$mail->send();
				//ENVIAR CORREO AL RECEPTOR ADMINISTRADOR DE LA LIGA
				include_once ("../../funciones/f_conexion_email2.php");
				$mail2->setFrom('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set an alternative reply-to address
				$mail2->addReplyTo('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set who the message is to be sent to
				$email_usuario  = obten_consultaUnCampo('unicas_liga','email','usuario','id_usuario',$pago_admin->getValor('usuario'),'','','','','','','');
				$mail2->addAddress($email_usuario);//enviamos al email principal del administrador
				if($email_usuario != $pago_admin->getValor('receptor')){//si es diferente envio a los dos
					$mail2->addAddress($pago_admin->getValor('receptor'));//enviarmos al correo paypal del administrador donde ha cobrado
				}
				$asunto = utf8_decode('Has Autorizado de forma manual el pago en tu liga <'.utf8_encode($_SESSION['nombre']).' división '.$_SESSION['num_division'].'>.');
				$mail2->Subject = $asunto;

				$cuerpo = '<br><br>Detalles: Has autorizado manualmente el siguiente pago<br><br>';
				$cuerpo .= 'Jugadores : '.utf8_encode($inscripcion->getValor('nombre1')).' '.utf8_encode($inscripcion->getValor('apellidos1')).' y '.utf8_encode($inscripcion->getValor('nombre2')).' '.utf8_encode($inscripcion->getValor('apellidos2')).'.<br><br>';
				$cuerpo .= 'Pago nº: '.$pago_admin->getValor("id_pago_admin").'.<br><br>';
				$cuerpo .= 'Fecha: '.date("Y-m-d H:i:s").'.<br><br>';
				$cuerpo .= 'Descripción : Inscripción en Liga '.utf8_encode($_SESSION['nombre']).' división '.$_SESSION['num_division'].'.<br><br>';
				$cuerpo .= 'Precio : '.$pago_admin->getValor('precio').' EUR (con I.V.A).<br><br>';
				$cuerpo .= 'Pago realizado manualmente por el Administrador.<br><br>';
				
				$body = email_jugadorAdmin("<br>¡Gracias por utilizar nuestros servicios miligadepadel.manuelbdv.es!<br>",$cuerpo);
				$mail2->msgHTML($body);//el mismo body
				$mail2->AltBody = 'This is a plain-text message body';//Replace the plain text body with one created manually
				$mail2->send();
	echo '0';
}//fin else if
else{//modo eliminacion
	if(isset($_POST['id_inscripcion'])){////ELIMINACION DE INSCRIPCION SIN PAGAR O GRATIS, ELIMINACIÓN DIRECTA
			$motivo = $_POST['motivo'];
			$nombre_liga = utf8_encode($_SESSION['nombre']);
			$num_division = $_SESSION['num_division'];
			$tipo_pago = $_SESSION['tipo_pago'];
			$id_inscripcion = limpiaTexto($_POST['id_inscripcion']);
			$inscripcion = new Inscripcion($id_inscripcion,'','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','');
			$j1 = $inscripcion->getValor('id_jugador1');
			$j2 = $inscripcion->getValor('id_jugador2');
			if($inscripcion->getValor('id_jugador1') == 0){//inscripcion jugador1 rapida
				$id_equipo = obten_consultaUnCampo('session','id_equipo','equipo','seguro_jug1',$id_inscripcion,'liga',$inscripcion->getValor('liga'),'division',$inscripcion->getValor('division'),'','','');
			}
			if($inscripcion->getValor('id_jugador2') == 0){//inscripcion jugador1 rapida
				$id_equipo = obten_consultaUnCampo('session','id_equipo','equipo','seguro_jug2',$id_inscripcion,'liga',$inscripcion->getValor('liga'),'division',$inscripcion->getValor('division'),'','','');
			}
			if(!empty($id_equipo)){//equipo rapido
				$equipo = new Equipo($id_equipo,'','','','','','','','','');
			}
			else{//equipo normal
				$equipo = new Equipo('',$inscripcion->getValor('id_jugador1'),'',$inscripcion->getValor('id_jugador2'),'',$inscripcion->getValor('liga'),$inscripcion->getValor('division'),'','','');
			}
			if($tipo_pago > 0){//elimino el pago creado
				$pago_admin = new Pago_admin('',$inscripcion->getValor('liga'),$inscripcion->getValor('division'),$_SESSION['bd'],$equipo->getValor('id_equipo'),'','','','','','','','','','','','','');
				$pago_admin->setValor('equipo',0); //si esta a 0, es eliminado
				$pago_admin->setValor('estado','D'); //si esta a D, es eliminado
				if(!empty($id_equipo)){//equipo rapido
					$pago_admin->setValor('datos','rapido'); //equipo rapido
				}
				$pago_admin->modificar();
			}
			//ENVIAR CORREO AL EMISOR
			if($inscripcion->getValor('id_jugador1') > 0 || $inscripcion->getValor('id_jugador2') > 0){// si las dos inscripciones son rapidas no envio email
					include_once ("../../funciones/f_conexion_email.php");//Set who the message is to be sent from
					$mail->setFrom('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set an alternative reply-to address
					$mail->addReplyTo('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set who the message is to be sent to
					//$mail->addAddress('manu_oamuf@hotmail.com');//enviarmos al emisor
					
					if($inscripcion->getValor('id_jugador1') > 0){$mail->addAddress($inscripcion->getValor('email1'));}//enviarmos al jugador 1
					if($inscripcion->getValor('id_jugador2') > 0){$mail->addAddress($inscripcion->getValor('email2'));}//enviarmos al jugador 2
					
					//$mail->addCC();//en copia al receptor
					$asunto = utf8_decode('La Inscripción en la Liga <'.$nombre_liga.' división '.$num_division.'> ha sido anulada por el Administrador');
					$mail->Subject = $asunto;
					$cuerpo = '<br><br>Detalles:<br><br>';
					$cuerpo .= 'El Administrador ha anulado tu inscripción a la Liga '.$nombre_liga.' división '.$num_division.'.<br><br>';
					if($motivo != ''){$cuerpo .= 'Motivo: '.$motivo.'.<br><br>';}
					$body = email_jugadorAdmin("<br>Gracias por utilizar nuestros servicios miligadepadel.manuelbdv.es<br>",$cuerpo);
					$mail->msgHTML($body);
					$mail->AltBody = 'This is a plain-text message body';//Replace the plain text body with one created manually
					$mail->send();
					//ENVIAR CORREO AL RECEPTOR
					include_once ("../../funciones/f_conexion_email2.php");
					$mail2->setFrom('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set an alternative reply-to address
					$mail2->addReplyTo('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');//Set who the message is to be sent to
					$mail2->addAddress($_SESSION['email']);//enviarmos al usuario
					$asunto = utf8_decode('Has anulado una inscripción en la Liga '.$nombre_liga.' división '.$num_division.'.');
					$mail2->Subject = $asunto;
					$cuerpo = '<br><br>Detalles:<br><br>';
					$cuerpo .= 'Inscripción anulada:<br><br>';
					$cuerpo .= utf8_encode($inscripcion->getValor('nombre1').' '.$inscripcion->getValor('apellidos1'));
					if($inscripcion->getValor('id_jugador1') > 0){$cuerpo .= utf8_encode(' - '.$inscripcion->getValor('email1'));}
					$cuerpo .='<br>';
					$cuerpo .= utf8_encode($inscripcion->getValor('nombre2').' '.$inscripcion->getValor('apellidos2'));
					if($inscripcion->getValor('id_jugador2') > 0){$cuerpo .= utf8_encode(' - '.$inscripcion->getValor('email2'));}
					$cuerpo .='<br><br>';
					if($motivo != ''){$cuerpo .= 'Motivo: '.$motivo.'.<br><br>';}
					$body = email_jugadorAdmin("<br>Gracias por utilizar nuestros servicios miligadepadel.manuelbdv.es<br>",$cuerpo);
					$mail2->msgHTML($body);//el mismo body
					$mail2->AltBody = 'This is a plain-text message body';//Replace the plain text body with one created manually
					$mail2->send();
			}//fin if si no es rapida
			$inscripcion->borrar();
			$equipo->borrar();
			$id_liga = $_SESSION['id_liga'];
			$id_division = $_SESSION['id_division'];
			$id_usuario = $_SESSION['id_usuario'];
			$id_puntuacion = obten_consultaUnCampo('session','id_puntuacion','puntuacion','usuario',$id_usuario,'liga',$id_liga,'division',$id_division,'aplicacion','L','');
			if($id_puntuacion > 0){//si a insertado en algun momento en puntuaciones
				//solo es posible insertar/eliminar puntuaciones al actualizar partido si es partido de grupo, o si es la final
				$tipo = 0;
				$tipo_puntuacion = 'inscripcion';
				$hay_puntos1 = obten_consultaUnCampo('unicas','COUNT(id_puntos)','puntos','liga',$id_liga,'division',$id_division,'tipo',$tipo,'jugador',$j1,'');
				if($hay_puntos1 > 0){
					realiza_deleteGeneral('unicas','puntos','usuario',$id_usuario,'liga',$id_liga,'division',$id_division,'tipo',$tipo,'jugador',$j1,'');
				}
				$hay_puntos2 = obten_consultaUnCampo('unicas','COUNT(id_puntos)','puntos','liga',$id_liga,'division',$id_division,'tipo',$tipo,'jugador',$j2,'');
				if($hay_puntos2 > 0){
					realiza_deleteGeneral('unicas','puntos','usuario',$id_usuario,'liga',$id_liga,'division',$id_division,'tipo',$tipo,'jugador',$j2,'');
				}
			}//fin de puntuacion
			echo '0';
	}//fin if
}

?>